<?php

namespace App\Http\Controllers;

use App\ErpRole;
use App\User;
use App\Permissions;
use Illuminate\Http\Request;
use App\Models\CostCenter;
use DB;
class ErpRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  view('auth.erproles.index');

    }
    public function getList(Request $request)
    {
        // Initialize the query for roles
        $rolesQuery = ErpRole::query();

        // Check if there's a search parameter and apply a like filter
        if ($request->has('search') && $request->search != '') {
            $rolesQuery->where('name', 'like', '%' . $request->search . '%');
        }

        // Paginate the results
        $data['roles'] = $rolesQuery->paginate(10);

        // Return the view with the roles data
        return view('auth.erproles.getList', $data);
    }

    // public function create(Request $request)
    // {


    //     $cost_center=new CostCenter();
    //     //$cost_center=$cost_center->SetConnection('mysql2');

    //     $company_id=$request->company_id;

    //     $cost_center=$cost_center->where('status',1)->select('*')

    //     ->orderBy('level1', 'ASC')
    //     ->orderBy('level2', 'ASC')
    //     ->orderBy('level3', 'ASC')
    //     ->orderBy('level4', 'ASC')
    //     ->orderBy('level5', 'ASC')

    //     ->get();


    //     $user_id=$request->users;
    //     $MainModuelCode = $request->MainModuelCode;
    //     $crud_rights=DB::table('users')->where('emp_code',$user_id)->select('crud_rights')->first()->crud_rights;
    //     $crud_rights=explode(',',$crud_rights);


    //     $m=$_GET['m'];


    //     $permissions = Permissions::get();
    //     // return  view('auth.erproles.create',compact('permissions'));


    //     return view('auth.erproles.create',compact('permissions','user_id','cost_center','crud_rights','m','MainModuelCode','company_id'));

    // }

    public function create(Request $request)
{
    $company_id = $request->company_id;

    // Get all active cost centers, ordered by level1 to level5
    $cost_center = CostCenter::where('status', 1)
        ->orderBy('level1', 'ASC')
        ->orderBy('level2', 'ASC')
        ->orderBy('level3', 'ASC')
        ->orderBy('level4', 'ASC')
        ->orderBy('level5', 'ASC')
        ->get();

    $user_id = $request->users;
    $MainModuelCode = $request->MainModuelCode;

    // Get user's CRUD rights
    $user = DB::table('users')->where('emp_code', $user_id)->select('crud_rights')->first();
    if ($user && isset($user->crud_rights)) {
        $crud_rights = explode(',', $user->crud_rights);
    } else {
        $crud_rights = []; // fallback if user or rights not found
    }

    // Get the module id from query string
    $m = $request->query('m'); // better than $_GET['m']

    $permissions = Permissions::get();

    return view('auth.erproles.create', compact(
        'permissions',
        'user_id',
        'cost_center',
        'crud_rights',
        'm',
        'MainModuelCode',
        'company_id'
    ));
}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Dumping request data for debugging (you can remove this later)
        // dd($request);
    
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'main' => 'nullable|array', 
            'sub' => 'nullable|array',  
            'rights' => 'nullable|array',
        ]);
    
        // Create a new ERP Role record
        // Create a new ERP Role record
    $erpRole = new ErpRole();
    $erpRole->name = $validatedData['name'];
    $erpRole->status = $validatedData['status'];
    $erpRole->main = isset($validatedData['main']) ? json_encode(array_unique($validatedData['main'])) : null;
    $erpRole->sub = isset($validatedData['sub']) ? json_encode(array_unique($validatedData['sub'])) : null;
    $erpRole->rights = isset($validatedData['rights']) ? json_encode(array_unique($validatedData['rights'])) : null;

    
        // Save the record to the database
        $erpRole->save();
        return response()->json(['success' => 'Successfully Deleted.', 'data' => []]);

        // Return a response after saving
        return redirect()->route('erp_roles.index')->with('success', 'ERP Role created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ErpRole  $erpRole
     * @return \Illuminate\Http\Response
     */
    public function show(ErpRole $erpRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ErpRole  $erpRole
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $role = ErpRole::findorfail($id);
        $cost_center=new CostCenter();
        //$cost_center=$cost_center->SetConnection('mysql2');

        $company_id=1;

        $cost_center=$cost_center->where('status',1)->select('*')

        ->orderBy('level1', 'ASC')
        ->orderBy('level2', 'ASC')
        ->orderBy('level3', 'ASC')
        ->orderBy('level4', 'ASC')
        ->orderBy('level5', 'ASC')

        ->get();


        // $user_id=$request->users;
        // $MainModuelCode = $request->MainModuelCode;
        $crud_rights=[];
        $crud_rights=[];


        $m=$_GET['m'];


        $permissions = Permissions::get();
        // return  view('auth.erproles.create',compact('permissions'));


        return view('auth.erproles.edit',compact('permissions','cost_center','crud_rights','m','company_id','role'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ErpRole  $erpRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'main' => 'nullable|array',
            'sub' => 'nullable|array',
            'rights' => 'nullable|array',
        ]);
    
        // Begin transaction
        DB::beginTransaction();
    
        try {
            // Find the ERP Role by ID
            $erpRole = ErpRole::findOrFail($id);
    
            // Update the ERP Role record
            $erpRole->name = $validatedData['name'];
            $erpRole->status = $validatedData['status'];
            $erpRole->main = isset($validatedData['main']) ? json_encode(array_unique($validatedData['main'])) : null;
            $erpRole->sub = isset($validatedData['sub']) ? json_encode(array_unique($validatedData['sub'])) : null;
            $erpRole->rights = isset($validatedData['rights']) ? json_encode(array_unique($validatedData['rights'])) : null;
    
            // Save the updated role
            $erpRole->save();
    
            // Convert JSON-encoded fields to comma-separated strings
           // $rights = implode(',', json_decode($erpRole->rights));
          //  $main_menu_id = implode(',', json_decode($erpRole->main));
           // $sub_menu_id = implode(',', json_decode($erpRole->sub));
            
            
            $rights = implode(',', json_decode($erpRole->rights, true));   // ✅ true for array
$main_menu_id = implode(',', json_decode($erpRole->main, true));
$sub_menu_id = implode(',', json_decode($erpRole->sub, true));
    
            // Fetch all users with the specific role_id
            $users = DB::table('users')->where('role_id', $id)->get();

            foreach ($users as $user) {
                User::findorfail($user->id)->update(['crud_rights'=>$rights]);
                // Prepare data for updating menu privileges
                $data = [
                    'main_modules' => $main_menu_id,
                    'emp_code' => $user->emp_code,
                    'submenu_id' => $sub_menu_id,
                    'compnay_id' => 1,
                ];
    
                // Check if menu_privileges record exists for the user
                $countToBeChecked = DB::table('menu_privileges')
                    ->where('emp_code', $user->emp_code)
                    ->where('compnay_id', 1)
                    ->count();

                if ($countToBeChecked > 0) {
                  
                    // Update existing menu_privileges record
                    DB::table('menu_privileges')
                        ->where('emp_code', $user->emp_code)
                        ->where('compnay_id', 1)
                        ->update($data);
                } else {
                    // Insert new menu_privileges record
                    DB::table('menu_privileges')->insert($data);
                }
            }
    
            // Commit the transaction
            DB::commit();
    
            // Return a success response
            return redirect()->to(route("erproles.index"))->with(["success" => true, "message" => "ERP Role and associated menu privileges updated successfully!"]);

        } catch (Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
    
            // Return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update ERP Role and menu privileges.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ErpRole  $erpRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(ErpRole $erpRole)
    {
        //
    }
}
