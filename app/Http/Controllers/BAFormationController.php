<?php

namespace App\Http\Controllers;

use App\BAFormation;
use App\Employees;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class BAFormationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('BA.baFormation.index');
    }

    public function getList(Request $request)
    {
        $query = BAFormation::leftJoin('employees', 'employees.emp_id', '=', 'b_a_formations.employee_id')
            ->leftJoin('customers', 'customers.id', '=', 'b_a_formations.customer_id');

        // Apply Filters
        if ($request->has('filter_customer') && $request->filter_customer != '') {
            $query->where('b_a_formations.customer_id', $request->filter_customer);
        }

        if ($request->has('filter_employee') && $request->filter_employee != '') {
            $query->where('b_a_formations.employee_id', $request->filter_employee);
        }

        if ($request->has('filter_status') && $request->filter_status != '') {
            $query->where('b_a_formations.status', $request->filter_status);
        }

        $data['BAFormations'] = $query->select(
            'b_a_formations.id',
            'b_a_formations.status',
            'b_a_formations.ba_no',
            'b_a_formations.brands_ids',
            'b_a_formations.employee_id',
            'employees.name as employee_name',
            'b_a_formations.customer_id',
            'customers.name as customer_name'
        )->orderBy('b_a_formations.id', 'desc')->get();

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
        $validator = Validator::make($request->all(), [
            'customer' => 'nullable|integer',
            'brands' => 'required',
            'employee' => 'required|integer|unique:mysql2.b_a_formations,employee_id',
            'status' => 'required|integer',
        ], [
            'employee.unique' => 'This employee already has a BA Formation record.'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => implode('<br>', $validator->errors()->all())]);
            }
            return redirect()->back()->with(["error" => implode('<br>', $validator->errors()->all())]);
        }

        DB::beginTransaction();
        try {
            $lastBaNo = BAFormation::orderBy('ba_no', 'desc')->first(); // Get the last ba_no
            $baNo = '0001';

            if ($lastBaNo) {
                $lastBaNoNumber = (int) substr($lastBaNo->ba_no, 1); // Extract numeric part, assuming ba_no starts with a prefix (like '0')
                $baNo = str_pad($lastBaNoNumber + 1, 4, '0', STR_PAD_LEFT); // Increment and format with leading zeros
            }

            $data = [
                'ba_no' => $baNo, // Auto-generated ba_no
                'customer_id' => $request->input('customer'),
                'employee_id' => $request->input('employee'),
                'brands_ids' => json_encode($request->input('brands')), // Store brands as JSON
                'status' => $request->input('status'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $baFormation = BAFormation::create($data);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Successfully Saved.']);
            }

            return redirect()->back()->with(["success" => "Successfully saved"]);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
            return back()->with("error", $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BAFormation  $bAFormation
     * @return \Illuminate\Http\Response
     */
    public function show(BAFormation $bAFormation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BAFormation  $bAFormation
     * @return \Illuminate\Http\Response
     */
    public function edit(BAFormation $bAFormation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BAFormation  $bAFormation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'customer' => 'nullable|integer',
            'brands' => 'required',
            'employee' => 'required|integer|unique:mysql2.b_a_formations,employee_id,' . $id,
            'status' => 'required|integer',
        ], [
            'employee.unique' => 'This employee already has a BA Formation record.'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => implode('<br>', $validator->errors()->all())]);
            }
            return redirect()->back()->with(["error" => implode('<br>', $validator->errors()->all()), "status" => 404]);
        }

        DB::beginTransaction();
        try {

            $data = [
                'customer_id' => $request->input('customer'),
                'employee_id' => $request->input('employee'),
                'brands_ids' => json_encode($request->input('brands')), // Store brands as JSON
                'status' => $request->input('status'),
            ];

            BAFormation::findorfail($id)->update($data);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Successfully Updated.']);
            }

            return redirect()->back()->with(["success" => "Successfully saved"]);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BAFormation  $bAFormation
     * @return \Illuminate\Http\Response
     */
    public function destroy(BAFormation $bAFormation)
    {
        //
    }


    public function syncEmployee(Request $request)
    {


        // Fetch data from an external API or service
        $client = new Client();

        // Make the GET request to the external API

        $response = $client->get('https://brands.smrsoftwares.com/api/getEmployeesList');

        if ($response->getStatusCode() === 200) {
            $employees = json_decode($response->getBody()->getContents(), true);
            $employees = $employees['data'];
            // dd($employees);
            // Insert or update employee data in the database
            foreach ($employees ?? [] as $employeeData) {
                Employees::updateOrCreate(
                    ['emp_id' => $employeeData['emp_id']], // Unique identifier
                    [
                        'emp_id' => $employeeData['emp_id'],  // Ensure emp_id is also inserted/updated
                        'emp_code' => $employeeData['emp_id'],
                        'name' => $employeeData['emp_name'],
                        'email' => $employeeData['professional_email'],
                    ]
                );
            }

            return response()->json(['message' => 'Employees synced successfully.'], 200);
        } else {
            return response()->json(['message' => 'Failed to sync employees.'], 500);
        }
    }
}
