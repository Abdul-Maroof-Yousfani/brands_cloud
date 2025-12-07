<?php

namespace App\Http\Controllers;

use App\BAFormation;
use App\BaTargets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BaTargetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  view('BA.BaTargets.index');

    }
    public function getList(Request $request)
    {
//        $data['BAFormations'] = [];
        $data['BaTargets'] = BaTargets::leftJoin('employees', 'employees.id', '=', 'ba_targets.employee_id')
            ->leftJoin('customers', 'customers.id', '=', 'ba_targets.customer_id')
            ->select('ba_targets.*','employees.name as employee_name','customers.name as customer_name')
            ->paginate(10);
        return  view('BA.BaTargets.getList',$data);
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
            'customer' => 'nullable|integer',
            'brands' => 'required',
            'employee' => 'required|integer',
            'start_date' => 'required',
            'end_date' => 'required',
            'target_qty' => 'required|numeric',
            'status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $data = [
                'customer_id' => $request->input('customer'),
                'employee_id' => $request->input('employee'),
                'brands' => $request->input('brands'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'target_qty' => $request->input('target_qty'),
                'status' => $request->input('status'),
            ];

            $baFormation = BaTargets::create($data);

            DB::commit();

            return response()->json(['success' => 'Successfully Saved.', 'data' => $baFormation]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
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
     * @param  \App\BaTargets  $baTargets
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'customer' => 'nullable|integer',
            'brands' => 'required',
            'employee' => 'required|integer',
            'start_date' => 'required',
            'end_date' => 'required',
            'target_qty' => 'required|numeric',
            'status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {

            $data = [
                'customer_id' => $request->input('customer'),
                'employee_id' => $request->input('employee'),
                'brands' => $request->input('brands'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'target_qty' => $request->input('target_qty'),
                'status' => $request->input('status'),
            ];

            $baFormation = BaTargets::findorfail($id)->update($data);

            DB::commit();

            return response()->json(['success' => 'Successfully Saved.', 'data' => $baFormation]);
        } catch (Exception $e) {
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
