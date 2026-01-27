<?php

namespace App\Http\Controllers;

use App\BAFormation;
use App\BaTargets;
use App\Models\Brand;
use App\Models\Customer;
use App\TargetItems;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BaTargetsController extends Controller
{
  public function index()
    {
        $data['customers'] = \App\Helpers\SalesHelper::get_all_customer_only_distributors();
        $data['brands'] = \App\Helpers\CommonHelper::get_all_brand();
        return view('BA.BaTargets.index', $data);
    }

    public function getCustomers(Request $request) {
        [$year, $month] = explode("-", $request->month);
     
        $customers = Customer::where("status", 1)
                        ->get();

        $target_items = TargetItems::where('month', (int)$month)
                        ->where('year', (int)$year)
                        ->get()
                        ->mapWithKeys(function ($item) {
                            return [$item->customer_id . '_' . $item->brand_id => $item];
                        })
                        ->toArray();
   
      

        $brands = Brand::where("status", 1)->get();


        return view("BA.BaTargets.customers", compact("customers", "brands", "target_items", "year", "month"));
    }

    public function insertTarget(Request $request)
{
    $customer_ids = $request->customer_id ?? [];
    $targets      = $request->target ?? [];     // corrected from $request->targets
    $year         = $request->year;
    $month        = $request->month;
    $brands       = $request->brand_id ?? [];

    if (empty($customer_ids) || empty($targets)) {
        return back()->with('error', 'No data submitted');
    }

    $lookupKeys = [];
    $dataByKey  = [];

    foreach ($customer_ids as $customer_id) {
        if (!isset($targets[$customer_id]) || !is_array($targets[$customer_id])) {
            continue;
        }

        foreach ($targets[$customer_id] as $index => $targetValue) {
            if ($targetValue === null || $targetValue === '' || $targetValue == 0) {
                continue;
            }

            $brand_id = $brands[$customer_id][$index] ?? null;
            if (!$brand_id) {
                continue;
            }

            $key = "{$year}-{$month}-{$customer_id}-{$brand_id}";

            $lookupKeys[] = [
                'year'       => $year,
                'month'      => $month,
                'customer_id' => $customer_id,
                'brand_id'   => $brand_id,
            ];

            $dataByKey[$key] = [
                'target'     => $targetValue,
                'customer_id' => $customer_id,
                'brand_id'   => $brand_id,
            ];
        }
    }

    if (empty($lookupKeys)) {
        return back()->with('warning', 'No valid targets to process');
    }

    $existing = TargetItems::where('year', $year)
        ->where('month', $month)
        ->whereIn('customer_id', $customer_ids)
        ->get(['id', 'customer_id', 'brand_id', 'target'])
        ->keyBy(function ($item) use ($year, $month) {
            return "{$year}-{$month}-{$item->customer_id}-{$item->brand_id}";
        });

    $toInsert = [];
    $toUpdate = [];

    foreach ($dataByKey as $key => $data) {
        if (isset($existing[$key])) {
            // exists → update
            $toUpdate[] = [
                'id'     => $existing[$key]->id,
                'target' => $data['target'],
            ];
        } else {
            $toInsert[] = [
                'year'        => $year,
                'month'       => $month,
                'customer_id'  => $data['customer_id'],
                'brand_id'    => $data['brand_id'],
                'target'      => $data['target'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }
    }
    DB::transaction(function () use ($toInsert, $toUpdate) {
        // Bulk insert
        if (!empty($toInsert)) {
            TargetItems::insert($toInsert);
        }

        if (!empty($toUpdate)) {
            foreach ($toUpdate as $item) {
                TargetItems::where('id', $item['id'])
                    ->update(['target' => $item['target']]);
            }
        }
    });

    return back()->with('success', 'Targets processed successfully');
}

    public function getList(Request $request)
    {
        $data['BaTargets'] = BaTargets::leftJoin('customers', 'customers.id', '=', 'ba_targets.customer_id')
            ->select('ba_targets.*', 'customers.name as customer_name', 'customers.zone as zone')
            ->paginate(10);
        $data['brands'] = \App\Helpers\CommonHelper::get_all_brand()->pluck('product_name', 'id')->toArray();
        return view('BA.BaTargets.getList', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("BA.BaTargets.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'start_date'              => 'required|date',
        'end_date'                => 'required|date|after_or_equal:start_date',
        'status'                  => 'required|integer|in:0,1',

        // Targets array ke andar ke fields ke liye specific rules
        'targets.*.customer_id'   => 'required',
        'targets.*.code'          => 'nullable|string|max:50',           // code optional string
        'targets.*.zone'          => 'nullable|string|max:100',          // zone optional string (N/A allowed)
        'targets.*.brands.*'      => 'nullable|numeric|min:0',           // har brand qty number ya null
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    DB::beginTransaction();
    try {
        $created = [];
        $targets_input = $request->input('targets', []);
        
        foreach ($targets_input as $index => $row) {
            $customer_id    = $row['customer_id'] ?? null;
            $brand_targets  = $row['brands'] ?? [];

            $has_target = false;
            foreach ($brand_targets as $qty) {
                if (is_numeric($qty) && $qty > 0) {
                    $has_target = true;
                    break;
                }
            }

            if (!$customer_id || !$has_target) {
                continue; // skip empty/invalid rows
            }

            $data = [
                'customer_id' => $customer_id,
                'start_date'  => $request->start_date,
                'end_date'    => $request->end_date,
                'targets'     => $brand_targets, // array [brand_id => qty]
                'status'      => $request->status,
            ];

            $baTarget = BaTargets::create($data);
            dd("test");
            $created[] = $baTarget;
        }

        DB::commit();

        return response()->json([
            'success' => 'Successfully Saved.',
            'count'   => count($created),
            'data'    => $created
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    /**
     * Display the specified resource.
     *
     * @param  \App\BaTargets  $baTargets
     * @return \Illuminate\Http\Response
     */
    public function show(BaTargets $baTargets)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BaTargets  $baTargets
     * @return \Illuminate\Http\Response
     */
    public function edit(BaTargets $baTargets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'customer' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|integer|in:0,1',
            'targets.*' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $data = [
                'customer_id' => $request->input('customer'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'targets' => $request->input('targets', []),
                'status' => $request->input('status'),
            ];

            $baTarget = BaTargets::findOrFail($id);
            $baTarget->update($data);

            DB::commit();

            return response()->json(['success' => 'Successfully Updated.', 'data' => $baTarget]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BaTargets  $baTargets
     * @return \Illuminate\Http\Response
     */
    public function destroy(BaTargets $baTargets)
    {
        //
    }
}
