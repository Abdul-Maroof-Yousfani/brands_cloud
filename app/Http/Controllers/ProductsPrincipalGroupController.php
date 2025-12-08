<?php

namespace App\Http\Controllers;

use App\Models\ProductsPrincipalGroup;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsPrincipalGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listProductsPrincipalGroup()
    {
        $responses = ProductsPrincipalGroup::where('status', 1)->get();
        return view('productsprincipalgroup.listProductsPrincipalGroup', compact('responses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createProductsPrincipalGroup()
    {
        return view('productsprincipalgroup.createProductsPrincipalGroup');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeProductsPrincipalGroup(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
            $productsPrincipalGroup = new ProductsPrincipalGroup;
            $productsPrincipalGroup->products_principal_group = $request->products_principal_group;
            $productsPrincipalGroup->status = "1";
            $productsPrincipalGroup->save();

            DB::Connection('mysql2')->commit();
            return redirect()->route('listProductsPrincipalGroup')->with('dataInsert', 'Products Principal Group Created Successfully');
        } catch (Exception $e) {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('createProductsPrincipalGroup')->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editProductsPrincipalGroup($id)
    {
        $response = ProductsPrincipalGroup::find($id);
        return view('productsprincipalgroup.editProductsPrincipalGroup', compact('response'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProductsPrincipalGroup(Request $request, $id)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
            $productsPrincipalGroup = ProductsPrincipalGroup::find($id);
            $productsPrincipalGroup->products_principal_group = $request->products_principal_group;
            $productsPrincipalGroup->save();

            DB::Connection('mysql2')->commit();
            return redirect()->route('listProductsPrincipalGroup')->with('dataInsert', 'Products Principal Group Updated Successfully');
        } catch (Exception $e) {
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('editProductsPrincipalGroup', $id)->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProductsPrincipalGroup($id)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
            $productsPrincipalGroup = ProductsPrincipalGroup::find($id);
            $productsPrincipalGroup->delete();

            DB::Connection('mysql2')->commit();
            return redirect()->route('listProductsPrincipalGroup')->with('dataInsert', 'Products Principal Group Deleted Successfully');
        } catch (Exception $e) {
            dd($e);
            DB::Connection('mysql2')->rollBack();
            return redirect()->route('listProductsPrincipalGroup')->with('error', $e->getMessage());
        }
    }
}

