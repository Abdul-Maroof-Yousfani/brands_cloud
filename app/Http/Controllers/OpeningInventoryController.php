<?php

namespace App\Http\Controllers;

use App\Models\BAStock;
use App\Models\Customer;
use App\Models\Stock;
use App\Models\Subitem;
use App\Models\Warehouse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\OnEachRow;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Row;

class OpeningInventoryController extends Controller
{
    public function index() {

        return  view('BA.opening-inventory.index');
    }

    public function import(Request $request)
{
    // 1. Get uploaded file
    $file = $request->file('xlsx_file');

    try {

        // 2. Read Excel into array
        // Structure: [sheet][row][column]
        $data = Excel::toArray([], $file);

        // 3. First sheet
        $sheet = $data[0];

        // 4. Header row
        $header = $sheet[0];

        // 5. Remaining rows (actual data)
        $rows = array_slice($sheet, 1);

        // 6. Map customers from header (skip first 4 columns)
        $customer_ids = [];
        $no_customers = [];

        foreach ($header as $colIndex => $customerName) {
            if ($colIndex < 4) continue;

            $customer_ids[$colIndex] = Customer::where('name', $customerName)->value('id');
            if(!$customer_ids[$colIndex]) {
                $no_customers[] = $customerName;
            }
        }

        // 7. Get virtual warehouse
        $warehouse = DB::connection('mysql2')
            ->table('warehouse')
            ->where('is_virtual', 1)
            ->first();

        // Optional: safety check
        if (!$warehouse) {
            return response()->json('Virtual warehouse not found', 404);
        }

        // 8. Loop through rows
        foreach ($rows as $rowIndex => $row) {

            // SKU is first column
            $sku = $row[0] ?? null;
            if (!$sku) continue;

            $sub_item_id = Subitem::where('sku_code', $sku)->value('id');
            if (!$sub_item_id) continue;

            // 9. Loop customer quantity columns
            foreach ($row as $colIndex => $qty) {

                if ($colIndex < 4) continue;       // skip SKU, barcode, brand, item
                if (!isset($customer_ids[$colIndex])) continue;
                

               
              DB::Connection('mysql2')->table('ba_stock')->insert([
    'customer_id'  => $customer_ids[$colIndex],
    'voucher_type' => 9,
    'sub_item_id'  => $sub_item_id,
    'qty'          => $qty,
    'warehouse_id' => $warehouse->id,
    'status'       => 1,
    'created_date' => now(),
    'username'     => auth()->user()->username,
    'opening'      => 1,
]);
            }
        }

        return response()->json($no_customers, 200);

    } catch (\Exception $e) {
        return response()->json($e->getMessage(), 500);
    }
} 
}
