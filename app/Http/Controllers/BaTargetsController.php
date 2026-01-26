<?php

namespace App\Http\Controllers;

use App\BAFormation;
use App\BaTargets;
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
        //
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

            // Optional: agar sab brands 0/null hain to skip kar do (ya save karo agar chahte ho)
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
