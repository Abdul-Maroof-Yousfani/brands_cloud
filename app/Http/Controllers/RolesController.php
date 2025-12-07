<?php

namespace App\Http\Controllers;

use App\BaTargets;
use App\Permissions;
use App\Roles;
use Illuminate\Http\Request;
use DB;
class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  view('auth.roles.index');

    }
    public function getList(Request $request)
    {
        // Initialize the query for roles
        $rolesQuery = Roles::query();

        // Check if there's a search parameter and apply a like filter
        if ($request->has('search') && $request->search != '') {
            $rolesQuery->where('name', 'like', '%' . $request->search . '%');
        }

        // Paginate the results
        $data['roles'] = $rolesQuery->paginate(10);

        // Return the view with the roles data
        return view('auth.roles.getList', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request)
    {
        $permissions = Permissions::get();
        return  view('auth.roles.create',compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        DB::beginTransaction();

        try {
            // Create Role
            $role = Roles::create([
                'name' => $request->name,
                'status' => $request->status,
            ]);

            // Attach Permissions
            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }



        return redirect()->back()->with('success', 'Role created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function show(Roles $roles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Roles  $roles
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        // Find the role by its ID or fail if not found
        $role = Roles::findOrFail($id);

        // Get all permissions to display in the checkbox list
        $permissions = Permissions::all();

        // Return the edit view and pass the role and permissions data
        return view('auth.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        $role =  Roles::findorfail($id);
        // Update Role
        $role->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Sync Permissions
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->back()->with('success', 'Role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $roles = Roles::findorfail($id)->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['success' => 'Successfully Deleted.', 'data' => $roles]);
    }


}
