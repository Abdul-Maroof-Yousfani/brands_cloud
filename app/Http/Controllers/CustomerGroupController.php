<?php

namespace App\Http\Controllers;

use App\Models\CustomerGroup;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listCustomerGroup()
    {
        $responses = CustomerGroup::where('status', 1)->get();
        return view('customergroup.listCustomerGroup', compact('responses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCustomerGroup()
    {
        return view('customergroup.createCustomerGroup');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCustomerGroup(Request $request)
    {
        $request->validate([
            'customer_group' => 'required'
        ]);

        // Check duplicate
        $exists = CustomerGroup::where('customer_group', $request->customer_group)
            ->where('status', 1)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Customer Group name already exists!');
        }

        DB::connection('mysql2')->beginTransaction();

        try {
            $customerGroup = new CustomerGroup;
            $customerGroup->customer_group = $request->customer_group;
            $customerGroup->status = "1";
            $customerGroup->save();

            DB::connection('mysql2')->commit();

            return back()->with('dataInsert', 'Customer Group Created Successfully');
        } catch (Exception $e) {
            DB::connection('mysql2')->rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editCustomerGroup($id)
    {
        $response = CustomerGroup::find($id);
        return view('customergroup.editCustomerGroup', compact('response'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCustomerGroup(Request $request, $id)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
            $customerGroup = CustomerGroup::find($id);
            $customerGroup->customer_group = $request->customer_group;
            $customerGroup->save();

            DB::Connection('mysql2')->commit();
            return redirect()->route('listCustomerGroup')->with('dataInsert', 'Customer Group Updated Successfully');
        } catch (Exception $e) {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('editCustomerGroup', $id)->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCustomerGroup($id)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
            $customerGroup = CustomerGroup::find($id);
            $customerGroup->delete();

            DB::Connection('mysql2')->commit();
            return redirect()->route('listCustomerGroup')->with('dataInsert', 'Customer Group Deleted Successfully');
        } catch (Exception $e) {
            dd($e);
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('listCustomerGroup')->with('error', $e->getMessage());
        }
    }
}
