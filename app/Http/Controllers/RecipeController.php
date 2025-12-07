<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\RecipeData;
use App\Models\ProductionBom;
use App\Models\ProductionBomData;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    public $m;
    public function __construct()
    {
        $this->m = Session::get('run_company');
    }
    public function addRecipe()
    {

        $workName = DB::connection('mysql2')->table('work_station')->get();
        return view('Inventory.recipe.addRecipe',compact('workName'));
    }

    public function insertRecipe(Request $request)
    {
        
        DB::connection('mysql2')->beginTransaction();

            try {
                // Create a new instance of ProductionBom with the specified connection
                $recipe = new ProductionBom();
                $recipe->setConnection('mysql2');

                $validator = Validator::make($request->all(), [
                    'product_id' => 'required', // Assuming 'product_id' is the name of the field corresponding to 'finish_goods'
                    'main_description' => 'required',
                    'receipe_name' => 'required',
                ]);
                
                // Check if the validation fails
                if ($validator->fails()) {
                    // Return a response with validation errors
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $recipe->finish_goods = $request->product_id;
                $recipe->description = $request->main_description;
                $recipe->receipe_name = $request->receipe_name;
                $recipe->qty = $request->qty;

                $recipe->username = Auth::user()->name;
                $recipe->date = date("Y-m-d");
                $recipe->status = 1;
                $recipe->save();

                // Access the last inserted ID after saving the model
                $recipeId = $recipe->id;

                $data = $request->category;
                
                if (empty($data[0])) {
                    // Rollback the transaction and return a response with validation errors
                    DB::connection('mysql2')->rollBack();
                    return redirect()->back()->withErrors('category quantity are required')->withInput();
                }
                foreach ($data as $key => $row) {

                    $validator = Validator::make(
                        [
                            'category' => $request->category[$key] ,
                            'required_qty' => $request->required_qty[$key] ,
                        ]
                        , [
                        'required_qty' => 'required',
                        'category' => 'required',
                    ]);
            
                    // Check if the validation fails
                    if ($validator->fails()) {
                        // Rollback the transaction and return a response with validation errors
                        DB::connection('mysql2')->rollBack();
                        return redirect()->back()->withErrors($validator)->withInput();
                    }

                    $RecipeData = new ProductionBomData();
                    $RecipeData->main_id = $recipeId; // Use the saved recipe ID
                    $RecipeData->item_id = ($request->item_id != 'select') ? $request->item_id[$key] ?? 0 : 0;
                    $RecipeData->category_total_qty = $request->required_qty[$key] ?? 0;
                    $RecipeData->category_id = $request->category[$key];
                    $RecipeData->status = 1;
                    $RecipeData->type = 1;
                    $RecipeData->username = Auth::user()->name;
                    $RecipeData->save();
                }

                // Commit the transaction
                DB::connection('mysql2')->commit();
            } catch (\Exception $ex) {
                // Rollback the transaction in case of an exception
                DB::connection('mysql2')->rollBack();

                // Handle the exception (e.g., log the error)
                return self::addRecipe($request)->withErrors($ex->getMessage());
            }
        // $data = $request->item_id_wip;
        // foreach ($data as $key => $row) :
        //     $RecipeData = new ProductionBomData();
        //     $RecipeData->main_id = $recipe->id;
        //     $RecipeData->item_id = $row;
        //     $actual_qty = str_replace( ',', '', $request->actual_qty_wip1[$key]);
        //     $RecipeData->qty =  $actual_qty ?? 0;
        //     $RecipeData->work_station_id = $request->item_workname_wip[$key];
        //     $RecipeData->status = 1;
        //     $RecipeData->type = 2;
        //     $RecipeData->username = Auth::user()->name;
        //     $RecipeData->save();
        // endforeach;

        // $RecipeData = new ProductionBomData();
        // $RecipeData->main_id = $recipe->id;
        // $RecipeData->item_id = $request->item_id_Matterial;
        // $RecipeData->work_station_id = $request->item_workname_Matterial;
        // $actual_qty = str_replace( ',', '', $request->actual_qty_Matterial);
        // $RecipeData->qty = $actual_qty;
        // $RecipeData->status = 1;
        // $RecipeData->type = 3;
        // $RecipeData->username = Auth::user()->name;
        // $RecipeData->save();

        // dd('saved');
        return redirect('recipe/recipeList');
    }

    public function recipeList()
    {
        $recipeList = ProductionBom::where('status', 1)->get();
        $m = $this->m;
        // $expenseList = Expense::with(['expenseItem:id,name', 'expenseCategory:id,name'])->where('status', 1)->get();
      return view('Inventory.recipe.viewRecipe', compact('recipeList', 'm'));
    }
    public function recipeDelete(Request $request)
    {
        // $data['created_by'] = Auth::user()->name;
        $data['status'] = 0;
        // Recipe::find($request->id)->update($data);
        $recipe = ProductionBom::find($request->id)->update($data);
        $recipeData = ProductionBomData::where('main_id',$request->id)->update($data);
        echo $request->id;

    }


    public function viewRecipeInfo(Request $request)
    {
        $recipe = ProductionBom::find($request->id);
        return view('Inventory.recipe.viewRecipeInfo',compact('recipe'));
    }

    public function recipeDataItemDelete(Request $request)
    {
        $data['created_by'] = Auth::user()->name;
        $data['status'] = 0;
        $recipe = ProductionBom::find($request->id)->update($data);
        $recipeData = ProductionBomData::where('main_id',$request->id)->update($data);
        // RecipeData::find($request->id)->update($data);
         return $request->id;
    }
    public function recipeEdit(Request $request)
    {
        $m = $request->m;
        $recipe = ProductionBom::where('status', 1)->where('id',$request->id)->first();
        $recipeData = ProductionBomData::where('status', 1)->where('main_id',$recipe->id)->get();

        // echo "<pre>";
        // print_r($recipe->id);
        // echo "<pre>";
        // print_r($request->id);
        // echo "<pre>";
        // print_r($recipeData);
        // exit();
        return view('Inventory.recipe.editRecipe', compact('recipe', 'recipeData', 'm'));
    }

    public function UpdateRecipe(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // exit();
        $m = $this->m;
        $request['created_by'] = Auth::user()->name;
        $request['status'] = 1;
        // ProductionBom::find($request->recordId)->update([
        //     'item_id' => $request->product_id,
        //     'description' => $request->main_description,
        //     'created_by' => Auth::user()->name,
        //     'status' => 1,
        // ]);
        // // return $request->all();
        // foreach ($request->item_id as $key => $value) {
        //     $recipeData = ProductionBomData::find($request->recipeDataId[$key] ?? null);
        //     if (empty($recipeData)) {
        //         $recipeData = new RecipeData();
        //     }
        //     $recipeData->item_id = $value;
        //     $recipeData->recipe_id = $request->recordId;
        //     $recipeData->quantity = $request->actual_qty1[$key];
        //     $recipeData->status = 1;
        //     $recipeData->created_by = Auth::user()->name;
        //     $recipeData->save();
        // }

        $recipe = ProductionBom::where('id',$request->recordId)->update([
            'finish_goods' => $request->product_id,
            'description' => $request->main_description,
            'receipe_name'=>$request->receipe_name,
            'qty' => $request->qty,
            'username' => Auth::user()->name,
            'date' => date("Y-m-d"),
            'status' => 1,
        ]);

        ProductionBomData::where('main_id',$request->recordId)->delete();
        // dd($recipe);
        $data = $request->category; 
        foreach ($data as $key => $row) :
            $RecipeData = new ProductionBomData();
            $RecipeData->main_id = $request->recordId;
            $RecipeData->category_total_qty =$request->required_qty[$key];
            $RecipeData->item_id = ($request->item_id != 'select') ? $request->item_id[$key] ?? 0 : 0;
            $RecipeData->category_id = $request->category[$key];
            $RecipeData->status = 1;
            $RecipeData->type = 1;
            $RecipeData->username = Auth::user()->name;
            $RecipeData->save();
        endforeach;
        Session::flash('dataEdit', 'successfully edit.');
        return redirect('recipe/recipeList');
    }
}
