<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    public function destroy(int $id) {
        $branch = Branch::find($id);
        $branch->delete();
        return back()->with("Message", "Branch has been deleted");
    }
    public function edit(Request $request, int $id) {
        $branch = Branch::find($id);
        return view("Purchase.AjaxPages.branchAjax", compact("branch"));
    }
    public function update(Request $request, int $id) {
        $branch_name = $request->branch_name;
        DB::connection("mysql2")->table("branch")->where("id", $id)->update([
            "branch_name" => $branch_name
        ]);

        return back();
    }
}
