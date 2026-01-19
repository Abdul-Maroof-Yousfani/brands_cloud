<?php

namespace App\Http\Controllers;

use App\Permissions;
use App\Roles;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\BaLocationModel;

class UserNewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  view('auth.users.index');
    }
    public function getList(Request $request)
    {
        $rolesQuery = User::query()
            ->leftJoin('roles', 'roles.id', '=', 'users.ba_role_id')  // Left join with roles table
            ->select('users.*', 'roles.name as role_name');  // Select columns you need (users and role name in this case)

        if ($request->has('search') && $request->search != '') {
            $rolesQuery->where('name', 'like', '%' . $request->search . '%');
        }

        $data['roles'] = $rolesQuery->where('acc_type', 'ba')
        ->get();
        // ->paginate(10);

       

        // Return the view with the roles data
        return view('auth.users.getList', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Roles::get();
        return  view('auth.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      
        $validated = $request->validate([
            'employee' => 'required',
            'name' => 'required|string|max:255',
            // 'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'status' => 'required|boolean',
            'roles' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // Create the user
            $user = User::create([
                'emp_code' => $request->employee,
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->email,
                'acc_type' => 'ba',
                'password' => $request->password,
                'status' => $request->status,
                'ba_role_id' => $request->roles
            ]);


             if (
            $request->shop_location == 1 &&
            !empty($request->latitude) &&
            is_array($request->latitude)
        ) {
            foreach ($request->latitude as $index => $lat) {
                if (!empty($lat) && isset($request->longitude[$index])) {
                    BaLocationModel::create([
                        'ba_id'        => $user->id,
                        'location_name' => $request->location_name[$index] ?? null,
                        'latitude'      => $lat,
                        'longitude'     => $request->longitude[$index],
                        'radius'        => $request->radius[$index] ?? null,
                    ]);
                }
            }
        }

            // Attach roles to the user
            // $user->roles()->attach($request->roles);

            DB::commit();

            // return response()->json([
            //     'message' => 'User created successfully.',
            //     'user' => $user,
            // ], 201);
             return redirect()->back()->with([
            'success' => 'User created successfully.',
            'status'  => 201
        ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // return response()->json([
            //     'message' => 'Failed to create user.',
            //     'error' => $e->getMessage(),
            // ], 500);

               return redirect()->back()->with([
            'error'  => 'Failed to create user. ' . $e->getMessage(),
            'status' => 500
        ]);
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
        // // Fetch the user with their roles using the provided ID
        // $user = User::with('roles')->findOrFail($id);

        $user = User::query()
            ->leftJoin('roles', 'roles.id', '=', 'users.ba_role_id')  // Left join with roles table
            ->select('users.*', 'roles.name as role_name')
            ->where('users.id', $id)
            ->first();
            

        // Fetch all roles to display in the form
        $roles = Roles::all();

        // Pass the user and roles to the edit view
        return view('auth.users.edit', compact('user', 'roles'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {

   
    //     $employeeValidationRule = 'nullable';
    //     if ($request->has('employee') && $request->input('employee') != $request->old('employee')) {
    //         $employeeValidationRule = 'required';  // Make it required if the user is updating employee
    //     }

    //     // Validate the input
    //     $validated = $request->validate([
    //         'employee' => $employeeValidationRule,
    //         'name' => 'required|string|max:255',
    //         // 'email' => 'required|email|unique:users,email,' . $id, // Ignore unique check for the current user
    //         'status' => 'required|boolean',
    //         'roles' => 'required', // Ensure at least one role is selected
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         // Fetch the user by ID
    //         $user = User::findOrFail($id);

    //         // Update the user data
    //         $user->update([
    //             'emp_id' => $request->employee,
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'status' => $request->status,
    //             'ba_role_id' => $request->roles, // Update the selected role
    //         ]);

    //         // If needed, you can update many-to-many relationships, like roles
    //         // $user->roles()->sync($request->roles);

    //         DB::commit();

    //         return redirect()->route('users.index')->with('success', 'User updated successfully.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return redirect()->back()->withErrors([
    //             'message' => 'Failed to update user.',
    //             'error' => $e->getMessage(),
    //         ]);
    //     }
    // }




public function update(Request $request, $id)
{
    $employeeValidationRule = 'nullable';
    if ($request->has('employee') && !empty($request->input('employee'))) {
        $employeeValidationRule = 'required';
    }

    // Validation
    $validated = $request->validate([
        'employee' => $employeeValidationRule,
        'name' => 'required|string|max:255',
        'email' => 'required|string|max:255',
        'status' => 'required|boolean',
        'roles' => 'required',
        'password' => 'nullable|string|confirmed|min:8',
    ]);

    DB::beginTransaction();

    try {
        $user = User::findOrFail($id);

        // Base data
        $data = [
            'emp_code'   => $request->employee,
            'emp_id'     => $request->employee,
            'name'       => $request->name,
            'email'      => $request->email,
            'username'   => $request->email,
            'status'     => $request->status,
            'ba_role_id' => $request->roles,
        ];

        // Only update password if it’s not empty
        if (!empty($request->password)) {
            $data['password'] = $request->password;
        }

        // Update user
        $user->update($data);



          if ($request->shop_location != 1) {
            $inputs['location_name'] = null;
            $inputs['latitude'] = null;
            $inputs['longitude'] = null;
            $inputs['radius'] = null;

            // Purani locations delete kar do
            $user->locations()->delete();
        } else {
            // ✅ Shop Location hai → Update / Recreate
            // Purani entries delete kar do
            $user->locations()->delete();

            // Nayi entries save karo
            if (!empty($request->latitude) && is_array($request->latitude)) {
                foreach ($request->latitude as $index => $lat) {
                    if (!empty($lat) && isset($request->longitude[$index])) {
                        BaLocationModel::create([
                            'ba_id'        => $user->id,
                            'location_name' => $request->location_name[$index] ?? null,
                            'latitude'      => $lat,
                            'longitude'     => $request->longitude[$index],
                            'radius'        => $request->radius[$index] ?? null,
                        ]);
                    }
                }
            }
        }

        DB::commit();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors([
            'message' => 'Failed to update user.',
            'error' => $e->getMessage(),
        ]);
    }
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
