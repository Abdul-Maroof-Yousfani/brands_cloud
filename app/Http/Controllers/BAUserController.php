<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BAFormation;
use App\Roles;
use App\Employees;
use DB;
class BAUserController extends Controller
{
    public function index()
    {
        $bAs = BAFormation::leftJoin('employees', 'b_a_formations.employee_id', 'employees.emp_id')->select('b_a_formations.ba_no', 'employees.*')->get();
        $roles = Roles::get();
        return view('BA.users.index', compact('bAs', 'roles'));
    }

    public function getList(Request $request)
    {
        $data['BAFormations'] = BAFormation::leftJoin('employees', 'employees.id', '=', 'b_a_formations.employee_id')->leftJoin('customers', 'customers.id', '=', 'b_a_formations.customer_id')->select('b_a_formations.id', 'b_a_formations.status', 'b_a_formations.ba_no', 'b_a_formations.brands_ids', 'b_a_formations.employee_id', 'employees.name as employee_name', 'b_a_formations.customer_id', 'customers.name as customer_name')->paginate(10);
        return view('BA.baFormation.getList', $data);
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
    $request->validate([
        'employee' => 'required',
        'ba_role_id' => 'required|exists:roles,id',
        'password' => 'required|min:6|confirmed',
        'status' => 'required|boolean',
    ]);

    // Check if the user already exists
    $employee = Employees::where('emp_id', $request->employee)->first();

    if (!$employee) {
        return response()->json([
            'success' => false,
            'message' => 'Employee not found.'
        ], 404);
    }

    $userExists = DB::table('users')->where('email', $employee->email)->exists();

    if ($userExists) {
        return response()->json([
            'success' => false,
            'message' => 'User already exists with this email.'
        ], 409);
    }

    // Create the user
    $user = DB::table('users')->insert([
        'acc_type'=>'ba',
        'name' => $employee->name,
        'email' => $employee->email,
        'emp_code' => $employee->emp_id,
        'password' => bcrypt($request->password),
        'ba_role_id' => $request->ba_role_id,
        'status' => $request->status,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    if ($user) {
        return response()->json([
            'success' => true,
            'message' => 'BA Formation created successfully.'
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create BA Formation.'
        ], 500);
    }
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
