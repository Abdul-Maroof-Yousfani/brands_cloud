<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\ProductsPrincipalGroup;

class BrandController extends Controller
{
    // public function index()
    // {

    //     $brands = Brand::where('status',1)->get();
    //     return view('Purchase.Brand.brand_list', compact('brands'));
    // }


 public function index()
{
    $brands = Brand::with('principalGroup')
                   ->where('status', 1)
                   ->orderBy('id', 'desc') // latest first
                   ->get();

    return view('Purchase.Brand.brand_list', compact('brands'));
}


    public function create()
    {

         $principalGroups = ProductsPrincipalGroup::where('status', 1)->get();
        return view('Purchase.Brand.add_brand',compact('principalGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Brand::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'principal_group_id' => $request->input('principal_group_id'), // new column
            'status'=>'1'
        ]);

        return redirect()->route('brands.create')->with('success', 'Brand created successfully.');
    }

    // public function edit($id)
    // {
    //     $brand = Brand::findOrFail($id);
    //     return view('Purchase.Brand.edit_brand', compact('brand'));
    // }

    public function edit($id)
{
    $brand = Brand::findOrFail($id);

    // Fetch all principal groups for dropdown
    $principalGroups = ProductsPrincipalGroup::all();

    return view('Purchase.Brand.edit_brand', compact('brand', 'principalGroups'));
}


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $brand = Brand::findOrFail($id);
        $brand->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully.');
    }
}
