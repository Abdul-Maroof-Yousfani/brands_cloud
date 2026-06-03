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

        // HR Portal Verification for Activation
        if ($request->input('status') == 1) {
            if (!\App\Helpers\CommonHelper::isEmployeeActiveInHR($request->input('employee'))) {
                $msg = 'Cannot activate. This employee is currently INACTIVE in the HR Portal.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg]);
                }
                return back()->with("error", $msg);
            }
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

        // HR Portal Verification for Activation
        if ($request->input('status') == 1) {
            if (!\App\Helpers\CommonHelper::isEmployeeActiveInHR($request->input('employee'))) {
                $msg = 'Cannot activate. This employee is currently INACTIVE in the HR Portal.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg]);
                }
                return back()->with("error", $msg);
            }
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
                        'zone' => $employeeData['zone'],
                    ]
                );
            }

            return response()->json(['message' => 'Employees synced successfully.'], 200);
        } else {
            return response()->json(['message' => 'Failed to sync employees.'], 500);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:csv,txt,xlsx,xls'
        ]);

        $file = $request->file('import_file');
        $path = $file->getRealPath();
        $handle = fopen($path, 'r');

        // Skip header row
        $header = fgetcsv($handle);

        $successCount = 0;
        $errorRows = [];
        $rowNum = 1;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNum++;

                // Expected columns: Employee Name/ID, Customer Name/ID, Brands (comma-separated Names/IDs), Status (Active/Deactive)
                $empVal = trim($row[0] ?? '');
                $custVal = trim($row[1] ?? '');
                $brandsStr = trim($row[2] ?? '');
                $statusVal = trim($row[3] ?? 'Active');

                if (empty($empVal) || empty($custVal) || empty($brandsStr)) {
                    $errorRows[] = "Row $rowNum: Missing required data (Employee, Customer, or Brands).";
                    continue;
                }

                // 1. Resolve Employee ID
                $empVal = preg_replace('/[[:^print:]]/u', '', $empVal);
                $empVal = html_entity_decode($empVal);
                $empVal = trim(preg_replace('/\s+/', ' ', $empVal));

                $employeeQ = DB::connection('mysql2')->table('employees');
                if (is_numeric($empVal)) {
                    $employeeQ->where('emp_id', $empVal);
                } else {
                    $searchEmp = str_replace(' ', '%', $empVal);
                    $employeeQ->where('name', 'LIKE', $searchEmp);
                }
                $employee = $employeeQ->first();

                if (!$employee) {
                    $errorRows[] = "Row $rowNum: Employee '$empVal' not found.";
                    continue;
                }
                $empId = $employee->emp_id;

                // 2. Resolve Customer ID
                $custVal = preg_replace('/[[:^print:]]/u', '', $custVal);
                $custVal = html_entity_decode($custVal);
                $custVal = trim(preg_replace('/\s+/', ' ', $custVal));

                $customerQ = DB::connection('mysql2')->table('customers');
                if (is_numeric($custVal)) {
                    $customerQ->where('id', $custVal);
                } else {
                    $searchCust = str_replace(' ', '%', $custVal);
                    $customerQ->where('name', 'LIKE', $searchCust);
                }
                $customer = $customerQ->first();

                if (!$customer) {
                    $errorRows[] = "Row $rowNum: Customer '$custVal' not found.";
                    continue;
                }
                $custId = $customer->id;

                // Check if employee already exists in BA Formation
                $exists = BAFormation::where('employee_id', $empId)->first();
                if ($exists) {
                    $errorRows[] = "Row $rowNum: Employee '$empId' (" . ($employee->name ?? '') . ") already has a record.";
                    continue;
                }

                // Generate BA No
                $lastBaNo = BAFormation::orderBy('ba_no', 'desc')->first();
                $baNo = '0001';
                if ($lastBaNo) {
                    $lastBaNoNumber = (int) substr($lastBaNo->ba_no, 1);
                    $baNo = str_pad($lastBaNoNumber + 1, 4, '0', STR_PAD_LEFT);
                }

                // 3. Resolve Brands IDs
                $brandsInput = array_map('trim', explode(',', $brandsStr));
                $brandsIds = [];
                foreach ($brandsInput as $bVal) {
                    $bVal = preg_replace('/[[:^print:]]/u', '', $bVal);
                    $bVal = html_entity_decode($bVal);
                    $bVal = trim(preg_replace('/\s+/', ' ', $bVal));

                    $brandQ = DB::connection('mysql2')->table('brands');
                    if (is_numeric($bVal)) {
                        $brandQ->where('id', $bVal);
                    } else {
                        $searchBrand = str_replace(' ', '%', $bVal);
                        $brandQ->where('name', 'LIKE', $searchBrand);
                    }
                    $brand = $brandQ->first();

                    if ($brand) {
                        $brandsIds[] = (string) $brand->id;
                    }
                }

                if (empty($brandsIds)) {
                    $errorRows[] = "Row $rowNum: No valid brands found for '$brandsStr'.";
                    continue;
                }

                // 4. Resolve Status
                $finalStatus = 1;
                $statusValLower = strtolower($statusVal);
                if ($statusValLower == 'deactive' || $statusValLower == 'inactive' || $statusValLower == '0') {
                    $finalStatus = 0;
                }

                BAFormation::create([
                    'ba_no' => $baNo,
                    'customer_id' => $custId,
                    'employee_id' => $empId,
                    'brands_ids' => json_encode($brandsIds),
                    'status' => $finalStatus,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $successCount++;
            }

            fclose($handle);
            DB::commit();

            $message = "Successfully imported $successCount records.";
            if (!empty($errorRows)) {
                $message .= " Errors: " . implode(' | ', $errorRows);
            }

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return response()->json(['success' => false, 'message' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    /**
     * API for HR Portal to update employee status
     */
    public function hrUpdateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emp_id' => 'required',
            'status' => 'required|in:0,1'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $empId = $request->emp_id;
        $status = $request->status;

        // Check if employee exists in either BA Formation or Users
        $baExists = BAFormation::where('employee_id', $empId)->exists();
        $userExists = DB::table('users')->where('emp_code', $empId)->where('acc_type', 'ba')->exists();

        if (!$baExists && !$userExists) {
            return response()->json([
                'success' => false, 
                'message' => "Employee ID $empId not found in BA Formation or Users list."
            ], 404);
        }

        DB::beginTransaction();
        try {
            $updatedBA = 0;
            $updatedUser = 0;

            // 1. Update BA Formation
            if ($baExists) {
                $updatedBA = BAFormation::where('employee_id', $empId)->update(['status' => $status]);
            }

            // 2. Update BA User
            if ($userExists) {
                $updatedUser = DB::table('users')
                    ->where('emp_code', $empId)
                    ->where('acc_type', 'ba')
                    ->update(['status' => $status]);
            }

            DB::commit();
            
            $statusLabel = ($status == 1) ? 'Active' : 'Inactive';
            return response()->json([
                'success' => true, 
                'message' => "Employee $empId status successfully updated to $statusLabel.",
                'details' => [
                    'ba_formation_updated' => $updatedBA > 0,
                    'user_account_updated' => $updatedUser > 0
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Sync failed: ' . $e->getMessage()], 500);
        }
    }

    public function testApi()
    {
        return response()->json(['success' => true, 'message' => 'API is working!']);
    }
}
