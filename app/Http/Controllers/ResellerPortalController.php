<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\ResellerSoRequest;
use App\ResellerSoRequestDetail;

class ResellerPortalController extends Controller
{
    public function createSoRequest()
    {
        // Fetch brands
        $brands = DB::connection('mysql2')->table('brands')->where('status', 1)->get();
        return view('reseller.so_requests.create', compact('brands'));
    }

    public function getProductsByBrand(Request $request)
    {
        $brandId = $request->brand_id;
        $reseller = Auth::guard('reseller')->user();
        $resellerCustomerId = $reseller->customer_id;

        // Fetch products for this brand
        $products = DB::connection('mysql2')->table('subitem')->where('brand_id', $brandId)->where('status', 1)->get();

        // Fetch stock for this reseller
        $rawStock = DB::connection('mysql2')->table('ba_stock as s')
            ->select(
                's.sub_item_id as product_id',
                DB::raw('SUM(CASE WHEN s.voucher_type IN (1,4,6,10,11,51) AND s.transfer_status != 1 THEN s.qty ELSE 0 END) AS in_stock'),
                DB::raw('SUM(CASE WHEN s.voucher_type IN (2,5,3,9,50) THEN s.qty ELSE 0 END) AS out_stock')
            )
            ->where('s.status', 1)
            ->where('s.customer_id', $resellerCustomerId)
            ->groupBy('s.sub_item_id')
            ->get()
            ->keyBy('product_id');

        $result = [];
        foreach ($products as $p) {
            $stock = isset($rawStock[$p->id]) ? $rawStock[$p->id] : null;
            $qty = 0;
            if ($stock) {
                $qty = (float) $stock->in_stock - (float) $stock->out_stock;
            }
            $result[] = [
                'id' => $p->id,
                'name' => $p->product_name . ' - ' . $p->sku_code,
                'available' => max(0, $qty)
            ];
        }

        return response()->json($result);
    }

    public function storeSoRequest(Request $request)
    {
        $request->validate([
            'request_date' => 'required|date',
            'customer_name' => 'required|string|max:255',
            'product_id' => 'required|array|min:1',
            'qty' => 'required|array|min:1'
        ]);

        $resellerId = Auth::guard('reseller')->id();

        // Save the Request
        $soRequest = new ResellerSoRequest();
        $soRequest->reseller_id = $resellerId;
        $soRequest->customer_name = $request->customer_name;
        $soRequest->request_date = $request->request_date;
        $soRequest->status = 0; // 0 = Pending
        $soRequest->save();

        // Save the details
        $reseller = Auth::guard('reseller')->user();
        foreach ($request->product_id as $key => $productId) {
            if (!empty($productId) && $request->qty[$key] > 0) {
                $detail = new ResellerSoRequestDetail();
                $detail->request_id = $soRequest->id;
                $detail->product_id = $productId;
                $detail->qty = $request->qty[$key];
                $detail->save();

                // Deduct stock from reseller (ba_stock)
                $stockRecord = DB::connection('mysql2')->table('ba_stock')
                    ->where('customer_id', $reseller->customer_id)
                    ->where('sub_item_id', $productId)
                    ->where('status', 1)
                    ->first();
                $warehouseId = $stockRecord ? $stockRecord->warehouse_id : 3;

                DB::connection('mysql2')->table('ba_stock')->insert([
                    'customer_id' => $reseller->customer_id,
                    'sub_item_id' => $productId,
                    'qty' => $request->qty[$key],
                    'voucher_type' => 2, // 2 = Out for ba_stock
                    'status' => 1,
                    'transfer_status' => 0,
                    'warehouse_id' => $warehouseId,
                    'created_date' => date('Y-m-d'),
                    'description' => 'SO Request Created: ' . $soRequest->id
                ]);
            }
        }

        return redirect()->route('reseller.so.list')->with('success', 'SO Request submitted successfully.');
    }

    public function soRequestList()
    {
        $resellerId = Auth::guard('reseller')->id();

        $requests = ResellerSoRequest::where('reseller_id', $resellerId)
            ->orderBy('id', 'DESC')
            ->get();

        return view('reseller.so_requests.list', compact('requests'));
    }

    public function showSoRequest($id)
    {
        $resellerId = Auth::guard('reseller')->id();

        $request = ResellerSoRequest::where('id', $id)
            ->where('reseller_id', $resellerId)
            ->firstOrFail();

        $details = ResellerSoRequestDetail::where('request_id', $id)->get();

        // Fetch product info from mysql2 for details
        $productIds = $details->pluck('product_id')->toArray();
        $products = DB::connection('mysql2')->table('subitem')
            ->whereIn('id', $productIds)
            ->select('id', 'product_name', 'sku_code')
            ->get()
            ->keyBy('id');

        return view('reseller.so_requests.show', compact('request', 'details', 'products'));
    }

    public function myStock(Request $request)
    {
        $reseller = Auth::guard('reseller')->user();
        $resellerCustomerId = $reseller->customer_id;

        // Fetch stock based on customer_id in ba_stock
        $query = DB::connection('mysql2')->table('ba_stock as s')
            ->join('subitem as si', 's.sub_item_id', '=', 'si.id')
            ->leftJoin('product_type as pt', 'si.product_type_id', '=', 'pt.product_type_id')
            ->leftJoin("customers as cus", "cus.id", "=", "s.customer_id")
            ->leftJoin('category as c', 'si.main_ic_id', '=', 'c.id')
            ->leftJoin('warehouse as w', 's.warehouse_id', '=', 'w.id')
            ->leftJoin('brands as b', 'si.brand_id', '=', 'b.id')
            ->select(
                'si.id as product_id',
                'si.sku_code',
                'si.product_name',
                'si.product_barcode as barcode',
                'pt.type as item_type',
                'si.pack_size as packing',
                'b.name as brand',
                'cus.name as customer_name',
                'cus.id as customer_id',
                'w.id as warehouse_id',
                'w.name as warehouse_name',
                DB::raw('SUM(CASE WHEN s.voucher_type IN (1,4,6,10,11) AND s.transfer_status != 1 THEN s.qty ELSE 0 END) AS in_stock'),
                DB::raw('SUM(CASE WHEN s.voucher_type IN (2,5,3,9) THEN s.qty ELSE 0 END) AS out_stock')
            )
            ->where('s.status', 1)
            ->where('s.customer_id', $resellerCustomerId);

        if ($request->has('product_id') && !empty($request->product_id)) {
            $query->where('s.sub_item_id', $request->product_id);
        }

        $rawStock = $query->groupBy('si.id', 'w.id', 'cus.id')->get();

        $stocks = [];
        foreach ($rawStock as $stock) {
            $stockQty = (float) $stock->in_stock - (float) $stock->out_stock;
            if ($stockQty > 0) {
                $stocks[] = (object) [
                    'product_name' => $stock->product_name,
                    'sku_code' => $stock->sku_code,
                    'available_qty' => abs($stockQty),
                    'warehouse_name' => $stock->warehouse_name
                ];
            }
        }

        $products = DB::connection('mysql2')->table('subitem')->where('status', 1)->get(['id', 'product_name', 'sku_code']);

        return view('reseller.inventory.stock', compact('stocks', 'products'));
    }
}
