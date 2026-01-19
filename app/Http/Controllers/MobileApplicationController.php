<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Merchandise;
use App\Models\Brand;
use App\Models\RetailSaleOrder;
use App\Models\RetailSaleOrderDetail;
use App\Models\Stock;
use App\RetailSaleOrderReturn;
use App\RetailSaleOrderReturnDetail;
use App\SurveyForm;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;

class MobileApplicationController extends Controller
{
    // public function login(Request $request)
    // {
    //     $rules = [
    //         'name' => 'required',
    //         'password' => 'required',
    //     ];

    //     // Create the validator instance
    //     $validator = Validator::make($request->all(), $rules);

    //     // Check if validation fails
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $validator->errors()
    //         ], 422); // 422 Unprocessable Entity
    //     }

        
    //     // Find the user by email
    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json(['message' => 'Invalid name or password'], 401);
    //     }

    //     if ($user->acc_type != 'ba') {
    //         return response()->json(['message' => 'Account type is not authorized'], 403); // Using 403 Forbidden for account type check
    //     }

    //     // Generate a new API token
    //     $token = $user->generateApiToken();
    //     // dd(123);
    //     return response()->json([
    //         'message' => 'Login successful',
    //         'user' => $user,
    //         'token' => $token,
    //     ]);
    // }
public function login(Request $request)
{
    // ✅ Validation rules
    $validator = Validator::make($request->all(), [
        'name' => ['required', 'string'],
        'password' => 'required|string',
        'imei' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

    // ✅ Check user by username
    $user = User::where('username', $request->name)->first();

    if($user->imei != $request->imei) {
        return response()->json(["message" => "invalid IMEI"], 401);
    }

    if (!$user) {
        return response()->json(['message' => 'Invalid username or password'], 401);
    }

    // ✅ Verify password
    if (!Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid username or password'], 401);
    }

    // ✅ Check account type
    if ($user->acc_type !== 'ba') {
        return response()->json(['message' => 'Unauthorized account type1'], 403);
    }

    // ✅ Generate token (ensure generateApiToken() exists)
    $token = $user->generateApiToken();

    return response()->json([
        'message' => 'Login successful',
        'user' => $user,
        'token' => $token,
    ], 200);
}


    public function logout(Request $request)
    {
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }

        $user->api_token = null;
        $user->save();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }


    public function get_permissions()
    {
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }

        $permissions = $user->ba_permissions();

        return response()->json([
            'message' => 'User Permissions',
            'data' => $permissions,
        ], 200);
    }

    public function get_distributor()
    {
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }

        $distributors = $user->customers()->select('customers.id', 'customers.name')->get();

        return response()->json([
            'message' => 'all distributors',
            'data' => $distributors,
        ], 200);
    }

    public function get_brands(Request $request)
    {

        $user = $this->getAuthenticatedUser();

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }

        // Validate that emp_id, from_date, and to_date are provided in the request
        $rules = [
            'distributor_id' => 'required|integer',
        ];

        // Create the validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        $brands = $user->brands($request->distributor_id);

        return response()->json([
            'message' => 'all brands',
            'data' => $brands,
        ], 200);
    }

    public function get_products(Request $request)
    {

        $user = $this->getAuthenticatedUser();

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }

        // Validate that emp_id, from_date, and to_date are provided in the request
        $rules = [
            'distributor_id' => 'required|integer',
            'brand_id' => 'required|integer',
        ];

        // Create the validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        $brand = Brand::find($request->brand_id);
        $subitems = $brand->subitems()->selected()->get();

        return response()->json([
            'message' => 'all products',
            'data' => $subitems,
        ], 200);
    }

    // public function get_stock(Request $request)
    // {

    //     $user = $this->getAuthenticatedUser();

    //     if ($user instanceof \Illuminate\Http\JsonResponse) {
    //         return $user; // Return the error response if no authenticated user
    //     }

    //     // Validate that emp_id, from_date, and to_date are provided in the request
    //     $rules = [
    //         'distributor_id' => 'required|integer',
    //         'product_id' => 'required|integer',
    //     ];

    //     // Create the validator instance
    //     $validator = Validator::make($request->all(), $rules);

    //     // Check if validation fails
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $validator->errors()
    //         ], 422); // 422 Unprocessable Entity
    //     }

    //     $distributor = $user->customers()->where('customers.id', $request->distributor_id)->first();
    //     $available_qty = 0;
    //     if ($distributor) {
    //         $result = Stock::where([
    //             'customer_id' => $distributor->id,
    //             'warehouse_id' => $distributor->warehouse_to,
    //             'sub_item_id' => $request->product_id
    //         ])
    //             ->selectRaw('
    //             SUM(CASE WHEN voucher_type = 1 THEN qty ELSE 0 END) AS stock,
    //             SUM(CASE WHEN voucher_type = 50 THEN qty ELSE 0 END) AS stock_sale,
    //             SUM(CASE WHEN voucher_type = 51 THEN qty ELSE 0 END) AS stock_return
    //         ')
    //             ->first();

    //         // Calculate available quantity
    //         $available_qty = $result->stock + $result->stock_return - $result->stock_sale;
    //     }

    //     return response()->json([
    //         'message' => 'product wise available stock',
    //         'available_qty' => $available_qty,
    //     ], 200);
    // }


    //   public function get_stock(Request $request)
    // {
    //     $user = $this->getAuthenticatedUser();
    //     if ($user instanceof \Illuminate\Http\JsonResponse) {
    //         return $user;
    //     }

    //     $rules = [
    //         'product_id' => 'required|integer',
    //         'distributor_id' => 'required|integer',
    //     ];

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     $total_stock = Stock::where('sub_item_id', $request->product_id)
    //         ->where('customer_id', $request->distributor_id)
    //         ->where('voucher_type', 1)
    //         ->sum('qty');

    //     $total_sale = Stock::where('sub_item_id', $request->product_id)
    //         ->where('customer_id', $request->distributor_id)
    //         ->where('voucher_type', 50)
    //         ->sum('qty');

    //     $total_return = Stock::where('sub_item_id', $request->product_id)
    //         ->where('customer_id', $request->distributor_id)
    //         ->where('voucher_type', 51)
    //         ->sum('qty');

    //     $available_qty = max(0, $total_stock + $total_return - $total_sale);

    //     return response()->json([
    //         'message' => 'Total stock for product',
    //         'available_qty' => $available_qty,
    //     ], 200);
    // }
 
public function get_stock(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }

        $rules = [
            'product_id' => 'required|integer',
            'distributor_id' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $total_stock = DB::Connection('mysql2')->table('ba_stock')->where('sub_item_id', $request->product_id)
            ->where('customer_id', $request->distributor_id)
            ->whereIn("voucher_type", [1, 9])
            ->sum('qty');

        $total_sale = DB::Connection('mysql2')->table('ba_stock')->where('sub_item_id', $request->product_id)
            ->where('customer_id', $request->distributor_id)
            ->where('voucher_type', 50)
            ->sum('qty');

        $total_return = DB::Connection('mysql2')->table('ba_stock')->where('sub_item_id', $request->product_id)
            ->where('customer_id', $request->distributor_id)
            ->where('voucher_type', 51)
            ->sum('qty');

        $available_qty = max(0, $total_stock + $total_return - $total_sale);

        return response()->json([
            'message' => 'Total stock for product',
            'available_qty' => $available_qty,
        ], 200);
    }

// public function getSaleAmount(Request $request)
// {
//     // Validate inputs
//     $validator = Validator::make($request->all(), [
//         'emp_code' => 'required|integer|exists:mysql.users,emp_code',
//         'year' => 'nullable|integer|min:2000|max:' . date('Y'),
//         'month' => 'nullable|integer|min:1|max:12',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'success' => false,
//             'errors' => $validator->errors()
//         ], 422);
//     }

//     try {
//         // Get user from main DB
//         $user = DB::connection('mysql')
//             ->table('users')
//             ->where('emp_code', $request->emp_code)
//             ->first();

//         if (!$user) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Employee not found.'
//             ], 404);
//         }

//         // Build query in mysql2
//         $query = DB::connection('mysql2')
//             ->table('retail_sale_orders as so')
//             ->join('retail_sale_order_details as sod', 'so.id', '=', 'sod.retail_sale_order_id')
//             ->join('subitem as si', 'sod.product_id', '=', 'si.id') // join for rate
//             ->where('so.user_id', $user->id);

//         // Optional filters
//         if ($request->year) {
//             $query->whereYear('so.sale_order_date', $request->year);
//         }

//         if ($request->month) {
//             $query->whereMonth('so.sale_order_date', $request->month);
//         }

//         // Calculate total sale amount (qty * rate)
//         $totalAmount = $query->sum(DB::raw('sod.qty * si.rate'));

//         return response()->json([
//             'success' => true,
//             'emp_code' => $request->emp_code,
//             'year' => $request->year,
//             'month' => $request->month,
//             'total_sale_amount' => $totalAmount ?? 0,
//         ], 200);

//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Failed to fetch sale amount.',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }


  public function getSaleAmount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emp_code' => 'required|integer|exists:mysql.users,emp_code',
            'year' => 'nullable|integer|min:2000|max:' . date('Y'),
            'month' => 'nullable|integer|min:1|max:12',
        ]);
 
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
 
        try {
            // Fetch employee (main DB)
            $employee = DB::connection('mysql')
                ->table('users')
                ->where('emp_code', $request->emp_code)
                ->first();
 
            if (!$employee) {
                return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
            }
 
            /*
            |--------------------------------------------------------------------------
            | 1. GET ALL CUSTOMERS ASSIGNED TO EMPLOYEE (BA FORMATION)
            |--------------------------------------------------------------------------
            */
            $customers = DB::connection('mysql2')
                ->table('b_a_formations')
                ->where('employee_id', $employee->emp_id)
                ->pluck('customer_id');
 
            /*
            |--------------------------------------------------------------------------
            | 2. SECONDARY SALE (SALE ORDER TABLE)
            |--------------------------------------------------------------------------
            */
            $secondaryQuery = DB::connection('mysql2')
                ->table('sales_order')
                ->whereIn('buyers_id', $customers);
 
            if ($request->year) {
                $secondaryQuery->whereYear('so_date', $request->year);
            }
            if ($request->month) {
                $secondaryQuery->whereMonth('so_date', $request->month);
            }
 
            $secondaryAmount = $secondaryQuery->sum('total_amount');
            $secondaryQty = $secondaryQuery->sum('total_qty');
 
            /*
            |--------------------------------------------------------------------------
            | 3. TERTIARY SALE (RETAIL SALE ORDERS)
            |--------------------------------------------------------------------------
            */
            $tertiaryQuery = DB::connection('mysql2')
                ->table('retail_sale_orders as so')
                ->join('retail_sale_order_details as sod', 'so.id', '=', 'sod.retail_sale_order_id')
                ->join('subitem as si', 'sod.product_id', '=', 'si.id')
                ->where('so.user_id', $employee->id);
 
            if ($request->year) {
                $tertiaryQuery->whereYear('so.sale_order_date', $request->year);
            }
            if ($request->month) {
                $tertiaryQuery->whereMonth('so.sale_order_date', $request->month);
            }
 
            $tertiaryAmount = $tertiaryQuery->sum(DB::raw('sod.qty * si.sale_price'));
            $tertiaryQty = $tertiaryQuery->sum('sod.qty');
 
            /*
            |--------------------------------------------------------------------------
            | 4. FINAL RESPONSE
            |--------------------------------------------------------------------------
            */
            return response()->json([
                'success' => true,
                'emp_code' => $request->emp_code,
                'year' => $request->year,
                'month' => $request->month,
 
                // Secondary sale (customer-wise)
                'secondary_sale_amount' => $secondaryAmount ?? 0,
                'secondary_sale_qty'    => $secondaryQty ?? 0,
 
                // Tertiary sale (employee-created)
                'tertiary_sale_amount' => $tertiaryAmount ?? 0,
                'tertiary_sale_qty'    => $tertiaryQty ?? 0,
                // 'distributor_id'    => $tertiaryQuery->first()->distributor_id ?? null,
                // 'data'    => $secondaryQuery->get(),
                // 'customers'    => $customers,
                // 'employee'    => $employee,
 
            ], 200);
 
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sales.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function CreateSaleOrder(Request $request)
    {

        $user = $this->getAuthenticatedUser();



        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }

        // Validate that emp_id, from_date, and to_date are provided in the request
        $rules = [
            'distributor_id' => 'required|integer',
            'remarks' => 'nullable|string',
            'details' => 'required|array',
            'details.*.brand_id' => 'required|integer',
            'details.*.product_id' => 'required|integer',
            'details.*.qty' => 'required|integer|min:1',
        ];

        // Create the validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }


        try {
            // Create the retail sale order
            $Subtime = Carbon::now("Asia/Karachi");
            if ($Subtime->hour >= 0 && $Subtime->hour < 4) {
                $Subtime->subDay();
            }
            $order = RetailSaleOrder::create([
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'distributor_id' => $request->distributor_id,
                'remarks' => $request->remarks,
                'sale_order_date' => $Subtime
            ]);

            $distributor = $user->customers()->where('customers.id', $request->distributor_id)->first();

           
            if (!$distributor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Distributor not found.',
                ], 404);
            }

            if (!$distributor->warehouse_to) {
                return response()->json([
                    'success' => false,
                    'message' => 'Virtual Warehouse is not assigned to this distributor. Please assign a warehouse first.',
                ], 400);
            }

            // Generate order_no and update the order
            $order->order_no = 'SO-' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
            $order->save();

            // Insert order details
            foreach ($request->details as $detail) {
                $order->details()->create([
                    'brand_id' => $detail['brand_id'],
                    'product_id' => $detail['product_id'],
                    'qty' => $detail['qty'],
                ]);

                $stock = array(
                    'main_id' => 0,   //  delivery note id
                    'master_id' => 0, // delievry note data id
                    'voucher_no' => $order->order_no,      //DN gd_no
                    'voucher_date' => date('Y-m-d'),  // DN
                    'supplier_id' => 0,
                    'customer_id' => $request->distributor_id,   //DN
                    'voucher_type' => 50,
                    'rate' => 0,    //DNA
                    'sub_item_id' => $detail['product_id'], // DNA
                    'batch_code' => 0,  //DNA
                    'qty' => $detail['qty'],     //DNA
                    'discount_amount' => 0, // $request->input('send_discount_amount' . $i),    //DNA
                    'amount' => 0,  //DNA
                    'status' => 1,
                    'warehouse_id' => $distributor->warehouse_to,
                    'username' => Auth::user()->username,
                    'created_date' => date('Y-m-d'),
                    'opening' => 0,
                    'so_data_id' => 0   //DNA
                );
                DB::Connection('mysql2')->table('ba_stock')->insert($stock);
            }

            return response()->json([
                'message' => 'sale order created successfully!',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retail sale order.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // public function SaleOrderList(Request $request)
    // {
    //     $user = $this->getAuthenticatedUser();

    //     if ($user instanceof \Illuminate\Http\JsonResponse) {
    //         return $user; // Return the error response if no authenticated user
    //     }

    //     // Find the sale order by its ID
    //     $saleOrder = RetailSaleOrder::with([
    //         'details' => function ($query) {
    //             $query->with(['brand:id,name', 'product:id,product_name']); // Ensure 'id' and 'name' are actual columns
    //         },
    //         'distributor:id,name' // Fetch distributor with selected fields
    //     ])
    //         ->where('user_id', Auth::user()->id) // Filter by user_id
    //         ->get();

    //     if (!$saleOrder) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Sale order not found',
    //         ], 404); // 404 Not Found
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'data' => $saleOrder,
    //     ]);
    // }


//   


 public function SaleOrderList(Request $request)
{
    $user = $this->getAuthenticatedUser();
 
    if ($user instanceof \Illuminate\Http\JsonResponse) {
        return $user;
    }
 
    // Start query
    $query = RetailSaleOrder::with([
        'details' => function ($query) {
            $query->with(['brand:id,name', 'product:id,product_name,product_barcode,sku_code']);
        },
        'distributor:id,name'
    ])->where('user_id', $user->id);
 
    // Apply date filter
    if ($request->filled('start_date') && $request->filled('end_date')) {
 
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate   = Carbon::parse($request->end_date)->endOfDay();
 
        $query->whereBetween('created_at', [$startDate, $endDate]);
    }
 
    $saleOrder = $query->get();
 
    // Calculate total count
    $totalCount = $saleOrder->count();
 
    if ($saleOrder->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No sale orders found for the given date range.',
            'total' => $totalCount
        ], 404);
    }
 
    return response()->json([
        'success' => true,
        'data' => $saleOrder,
        'total' => $totalCount
    ]);
}

    public function getSaleOrder(Request $request)
    {
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }

        // Validate that emp_id, from_date, and to_date are provided in the request
        $rules = [
            'order_id' => 'required|integer',
        ];

        // Create the validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // Find the sale order by its ID
        $saleOrder = RetailSaleOrder::with([
            'details' => function ($query) {
                $query->with(['brand:id,name', 'product:id,product_name']); // Ensure 'id' and 'name' are actual columns
            },
            'distributor:id,name' // Fetch distributor with selected fields
        ])->find($request->order_id);

        if (!$saleOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Sale order not found',
            ], 404); // 404 Not Found
        }

        return response()->json([
            'success' => true,
            'data' => $saleOrder,
        ]);
    }

    public function updateSaleOrder(Request $request)
    {
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }

        // Validate that emp_id, from_date, and to_date are provided in the request
        $rules = [
            'order_id' => 'required|integer',
            'distributor_id' => 'required|integer',
            'remarks' => 'nullable|string',
            'details' => 'required|array',
            'details.*.brand_id' => 'required|integer',
            'details.*.product_id' => 'required|integer',
            'details.*.qty' => 'required|integer|min:1',
        ];

        // Create the validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // Find the sale order by its ID
        $saleOrder = RetailSaleOrder::find($request->order_id);

        if (!$saleOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Sale order not found',
            ], 404); // 404 Not Found
        }

        // Update the sale order fields
        $saleOrder->distributor_id = $request->input('distributor_id');
        $saleOrder->remarks = $request->input('remarks', $saleOrder->remarks);
        $saleOrder->save();

        $distributor = $user->customers()->where('customers.id', $request->distributor_id)->first();
        // Update sale order details
        // First, delete existing details
        RetailSaleOrderDetail::where('retail_sale_order_id', $request->order_id)->delete();

        //existin stock delete
        $delete_stock = DB::Connection('mysql2')->table('stock')->where('voucher_no', $saleOrder->order_no)->delete();

        // Insert new details
        foreach ($request->input('details') as $detail) {
            RetailSaleOrderDetail::create([
                'retail_sale_order_id' => $saleOrder->id,
                'brand_id' => $detail['brand_id'],
                'product_id' => $detail['product_id'],
                'qty' => $detail['qty'],
            ]);

            $stock = array(
                'main_id' => 0,   //  delivery note id
                'master_id' => 0, // delievry note data id
                'voucher_no' => $saleOrder->order_no,      //DN gd_no
                'voucher_date' => date('Y-m-d'),  // DN
                'supplier_id' => 0,
                'customer_id' => $request->distributor_id,   //DN
                'voucher_type' => 50,
                'rate' => 0,    //DNA
                'sub_item_id' => $detail['product_id'], // DNA
                'batch_code' => 0,  //DNA
                'qty' => $detail['qty'],     //DNA
                'discount_amount' => 0, // $request->input('send_discount_amount' . $i),    //DNA
                'amount' => 0,  //DNA
                'status' => 1,
                'warehouse_id' => $distributor->warehouse_to,
                'username' => Auth::user()->username,
                'created_date' => date('Y-m-d'),
                'opening' => 0,
                'so_data_id' => 0   //DNA
            );
            DB::Connection('mysql2')->table('stock')->insert($stock);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sale order updated successfully',
        ]);
    }


    public function CreateReturnSaleOrder(Request $request)
    {

        $user = $this->getAuthenticatedUser();

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }

        // Validate that emp_id, from_date, and to_date are provided in the request
        $rules = [
            'distributor_id' => 'required|integer',
            'remarks' => 'nullable|string',
            'details' => 'required|array',
            'details.*.brand_id' => 'required|integer',
            'details.*.product_id' => 'required|integer',
            'details.*.qty' => 'required|integer|min:1',
            'details.*.barcode' => 'nullable|string',
            'details.*.reason' => 'nullable|string',
     'details.*.damage_photo' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
        'details.*.expiry_date' => 'nullable|date',

        ];

        // Create the validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }


        try {
          
            $order = RetailSaleOrderReturn::create([
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'distributor_id' => $request->distributor_id,
                'return_date' => date('Y-m-d'),
                'return_reason' => $request->remarks,
            ]);

            $distributor = $user->customers()->where('customers.id', $request->distributor_id)->first();

            // Generate order_zno and update the order
            $order->return_no = 'SR-' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
            $order->save();

           foreach ($request->details as $index => $detail) {

                    $damagePhotoPath = null;

                    // Check if file exists in this detail item
                    if ($request->hasFile("details.$index.damage_photo")) {
                        $photoFile = $request->file("details.$index.damage_photo");
                        $damagePhotoPath = $photoFile->store('damage_photos', 'public');
                    }

                    $order->returnDetails()->create([
                        'brand_id' => $detail['brand_id'],
                        'product_id' => $detail['product_id'],
                        'quantity' => $detail['qty'],
                        'barcode' => $detail['barcode'],
                        'reason' => $detail['reason'] ?? null,
                        'damage_photo' => $damagePhotoPath,
                        'expiry_date' => $detail['expiry_date'] ?? null,
                    ]);

                $stock = array(
                    'main_id' => 0,   //  delivery note id
                    'master_id' => 0, // delievry note data id
                    'voucher_no' => $order->return_no,      //DN gd_no
                    'voucher_date' => date('Y-m-d'),  // DN
                    'supplier_id' => 0,
                    'customer_id' => $request->distributor_id,   //DN
                    'voucher_type' => 51,
                    'rate' => 0,    //DNA
                    'sub_item_id' => $detail['product_id'], // DNA
                    'batch_code' => 0,  //DNA
                    'qty' => $detail['qty'],     //DNA
                    'discount_amount' => 0, // $request->input('send_discount_amount' . $i),    //DNA
                    'amount' => 0,  //DNA
                    'status' => 1,
                    'warehouse_id' => $distributor->warehouse_to,
                    'username' => Auth::user()->username,
                    'created_date' => date('Y-m-d'),
                    'opening' => 0,
                    'so_data_id' => 0   //DNA
                );
                DB::Connection('mysql2')->table('ba_stock')->insert($stock);
            }

            return response()->json([
                'message' => 'sale return created successfully!',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retail sale return.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function ReturnSaleOrderList(Request $request)
    {
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }


        // Find the sale order by its ID
        $saleReturn = RetailSaleOrderReturn::with([
            'returnDetails' => function ($query) {
                $query->with(['brand:id,name', 'product:id,product_name']); // Ensure 'id' and 'name' are actual columns
            },
            'distributor:id,name' // Fetch distributor with selected fields
        ])
            ->where('user_id', Auth::user()->id) // Filter by user_id
            ->get();

        if (!$saleReturn) {
            return response()->json([
                'success' => false,
                'message' => 'Sale return not found',
            ], 404); // 404 Not Found
        }

        return response()->json([
            'success' => true,
            'data' => $saleReturn,
        ]);
    }

    public function getReturnSaleOrder(Request $request)
    {
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }

        // Validate that emp_id, from_date, and to_date are provided in the request
        $rules = [
            'return_id' => 'required|integer',
        ];

        // Create the validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // Find the sale order by its ID
        $saleReturn = RetailSaleOrderReturn::with([
            'returnDetails' => function ($query) {
                $query->with(['brand:id,name', 'product:id,product_name']); // Ensure 'id' and 'name' are actual columns
            },
            'distributor:id,name' // Fetch distributor with selected fields
        ])->find($request->return_id);

        if (!$saleReturn) {
            return response()->json([
                'success' => false,
                'message' => 'Sale return not found',
            ], 404); // 404 Not Found
        }

        return response()->json([
            'success' => true,
            'data' => $saleReturn,
        ]);
    }

    public function updateReturnSaleOrder(Request $request)
    {
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }

        // Validate that emp_id, from_date, and to_date are provided in the request
        $rules = [
            'return_id' => 'required|integer',
            'distributor_id' => 'required|integer',
            'remarks' => 'nullable|string',
            'details' => 'required|array',
            'details.*.brand_id' => 'required|integer',
            'details.*.product_id' => 'required|integer',
            'details.*.qty' => 'required|integer|min:1',
            'details.*.barcode' => 'nullable|string',
        ];

        // Create the validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // Find the sale order by its ID
        $saleReturn = RetailSaleOrderReturn::find($request->return_id);

        if (!$saleReturn) {
            return response()->json([
                'success' => false,
                'message' => 'Sale return not found',
            ], 404); // 404 Not Found
        }

        // Update the sale order fields
        $saleReturn->distributor_id = $request->input('distributor_id');
        $saleReturn->return_reason = $request->input('remarks', $saleReturn->return_reason);
        $saleReturn->save();

        $distributor = $user->customers()->where('customers.id', $request->distributor_id)->first();
        // Update sale order details
        // First, delete existing details
        RetailSaleOrderReturnDetail::where('retail_sale_order_return_id', $request->order_id)->delete();

        //existin stock delete
        $delete_stock = DB::Connection('mysql2')->table('stock')->where('voucher_no', $saleReturn->return_no)->delete();

        // Insert new details
        foreach ($request->input('details') as $detail) {
            RetailSaleOrderReturnDetail::create([
                'retail_sale_order_return_id' => $saleReturn->id,
                'brand_id' => $detail['brand_id'],
                'product_id' => $detail['product_id'],
                'quantity' => $detail['qty'],
                'barcode' => $detail['barcode'],
            ]);

            $stock = array(
                'main_id' => 0,   //  delivery note id
                'master_id' => 0, // delievry note data id
                'voucher_no' => $saleReturn->return_no,      //DN gd_no
                'voucher_date' => date('Y-m-d'),  // DN
                'supplier_id' => 0,
                'customer_id' => $request->distributor_id,   //DN
                'voucher_type' => 51,
                'rate' => 0,    //DNA
                'sub_item_id' => $detail['product_id'], // DNA
                'batch_code' => 0,  //DNA
                'qty' => $detail['qty'],     //DNA
                'discount_amount' => 0, // $request->input('send_discount_amount' . $i),    //DNA
                'amount' => 0,  //DNA
                'status' => 1,
                'warehouse_id' => $distributor->warehouse_to,
                'username' => Auth::user()->username,
                'created_date' => date('Y-m-d'),
                'opening' => 0,
                'so_data_id' => 0   //DNA
            );
            DB::Connection('mysql2')->table('stock')->insert($stock);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sale return updated successfully',
        ]);
    }

    public function merchandiseCreate(Request $request)
    {
        date_default_timezone_set("Asia/Karachi");

        // Validation
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }

        // Validate that emp_id, from_date, and to_date are provided in the request
        $rules = [
            'distributor_id' => 'required|integer',
            'remarks' => 'nullable|string',
            'before_rack' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // Max file size 2MB
            'after_rack' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // Max file size 2MB
        ];

        // Create the validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        DB::beginTransaction();

        try {
            // Store merchandise data
            $merchandise = new Merchandise();
            $merchandise->user_id = Auth::user()->id;
            $merchandise->username = Auth::user()->name;
            $merchandise->distributor_id = $request->distributor_id;
            $merchandise->remarks = $request->remarks;
            $merchandise->merchandise_date = date('Y-m-d');


            // Handle file uploads
            if ($request->hasFile('before_rack')) {
                $merchandise->before_rack = $request->file('before_rack')->store('uploads/merchandise', 'public');
            }

            if ($request->hasFile('after_rack')) {
                $merchandise->after_rack = $request->file('after_rack')->store('uploads/merchandise', 'public');
            }

            $merchandise->save();


            DB::commit();

            return response()->json([
                'message' => 'Merchandise Created Successfully!',
                'success' => true
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create Merchandise.',
                'error' => $e->getMessage() . ' on line ' . $e->getLine(),
            ], 500);
        }
    }

    public function merchandiseList(Request $request)
    {

        $user = $this->getAuthenticatedUser();

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }

        // Fetch merchandise with related store data
        $merchandise = Merchandise::with('distributor:id,name') // Load distributor relationship
            ->where('user_id', Auth::user()->id) // Filter by user_id
            ->get();


        return response()->json([
            'success' => true,
            'messae' => 'Merchandise List Successfully Retrieved',
            'data' => $merchandise,
        ]);
    }

    public function createSurvey(Request $request)
    {
        date_default_timezone_set("Asia/Karachi");

        $user = $this->getAuthenticatedUser();

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }

        // Validate that emp_id, from_date, and to_date are provided in the request
        $rules = [
            'distributor_id' => 'required',
            'customer_name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'alternate_contact' => 'nullable|string|max:20',
            'area_address' => 'nullable|string|max:255',
            'currently_using_brand_id' => 'required',
            'reason_of_usage_1' => 'nullable|string',
            'currently_using_brand_2_id' => 'required',
            'reason_of_usage_2' => 'nullable|string',
            'product_id' => 'required',
            'remarks' => 'nullable|string',
        ];

        // Create the validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        DB::beginTransaction();

        try {


            // Insert the survey form data using the model
            $survey = SurveyForm::create([
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'distributor_id' => $request->distributor_id,
                'customer_name' => $request->customer_name,
                'contact' => $request->contact,
                'alternate_contact' => $request->alternate_contact,
                'area_address' => $request->area_address,
                'currently_using_brand_id' => $request->currently_using_brand_id,
                'reason_of_usage_1' => $request->reason_of_usage_1,
                'currently_using_brand_2_id' => $request->currently_using_brand_2_id,
                'reason_of_usage_2' => $request->reason_of_usage_2,
                'product_id' => $request->product_id,
                'remarks' => $request->remarks,
                'status' => 1, // Default status
            ]);

            DB::commit();
            return response()->json([
                'message' => 'Survey Created Successfully!',
                'success' => true
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create SurveyForm.',
                'error' => $e->getMessage() . ' on line ' . $e->getLine(),
            ], 500);
        }
    }


    public function surveyList(Request $request)
    {


        $surveys = SurveyForm::with('distributor', 'currentlyUsingBrand', 'currentlyUsingBrand2', 'product') // Load necessary relationships
            ->where('user_id', Auth::user()->id) // Filter by user_id
            ->get();

        // Map the response to include user details directly
        $surveys->transform(function ($item) {
            return [
                'id' => $item->id,
                'user_id' => $item->user_id,
                'user_name' => $item->user_name,
                'distributor_id' => $item->distributor_id,
                'distributor_name' => $item->distributor->name ?? 'N/A',
                'customer_name' => $item->customer_name,
                'contact' => $item->contact ?? 'N/A',
                'alternate_contact' => $item->alternate_contact ?? 'N/A',
                'area_address' => $item->area_address ?? 'N/A',
                'currently_using_brand' => $item->currentlyUsingBrand->name ?? 'N/A',
                'reason_of_usage_1' => $item->reason_of_usage_1 ?? '',
                'currently_using_brand_2' => $item->currentlyUsingBrand2->name ?? 'N/A',
                'reason_of_usage_2' => $item->reason_of_usage_2 ?? '',
                'product_id' => $item->product_id ?? 'N/A', // Keep this to indicate the product ID
                'product_name' => $item->product->product_name ?? 'N/A', // Correctly map the product name
                'remarks' => $item->remarks ?? '',
                'survey_date' => $item->created_at->toDateTimeString(), // Return survey creation date
            ];
        });

        return response()->json([
            'success' => true,
            'messae' => 'Survey List Successfully Retrieved',
            'data' => $surveys,
        ]);
    }

    public function getAttendance(Request $request)
    {
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }

        // Validate that emp_id is provided in the request
        $rules = [
            'emp_id' => 'required|integer',
        ];

        // Create the validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // Fetch data from an external API or service
        $client = new Client();

        // Retrieve emp_id from the request
        $empId = $request->input('emp_id');

        // Make the GET request to the external API with the dynamic emp_id
        $response = $client->get("https://brands.smrsoftwares.com/api/getAttendance?emp_id={$empId}");

        // Process the response (optional)
        $data = json_decode($response->getBody(), true);

        // Return the response or do something with the data
        return response()->json($data);
    }

    public function viewAttendanceReport(Request $request)
    {
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }

        // Validate that emp_id, from_date, and to_date are provided in the request
        $rules = [
            'emp_id' => 'required|integer',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ];

        // Create the validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // Fetch data from an external API or service
        $client = new Client();

        // Retrieve emp_id, from_date, and to_date from the request
        $empId = $request->input('emp_id');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Construct the URL with query parameters
        $url = "https://brands.smrsoftwares.com/api/viewAttendanceReport?emp_id={$empId}&from_date={$fromDate}&to_date={$toDate}";

        // Make the GET request to the external API
        $response = $client->get($url);

        // Process the response (optional)
        $data = json_decode($response->getBody(), true);

        return response()->json([
            'checkin_status' => true,
            'data' => $data
        ]);
        // Return the response or do something with the data
        // return response()->json($data);
    }

    public function addAttendance(Request $request)
    {
        $user = $this->getAuthenticatedUser();

        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the error response if no authenticated user
        }

        // Validate the required parameters
        $rules = [
            'emp_id' => 'required|integer',
            'latittude' => 'required',
            'longitude' => 'required',
            'check' => 'required',
            'username' => 'required',
             'checkin_status' => 'nullable',
        ];

        // Create the validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }




        // Fetch data from the request
        $empId = $request->input('emp_id');
        $latitude = $request->input('latittude');
        $longitude = $request->input('longitude');
        $check = $request->input('check');
        $username = $request->input('username');
         $checkin_status = $request->input('checkin_status', null);



 $user_id = Auth::user()->id;
        $status = $this->findUsersNearby($request->latittude , $request->longitude , $user_id);

        if (!$status) {
            return response()->json([
                'success' => false,
                'message' => 'You are not in location'
            ]);
        }



        // Initialize Guzzle HTTP client
        $client = new Client();

     
        // Make the POST request with the required data
        $response = $client->post('https://brands.smrsoftwares.com/api/addAttendance', [
            'form_params' => [
                'emp_id' => $empId,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'check' => $check,
                'username' => $username,
                 'checkin_status' => $checkin_status,
            ]
        ]);

        // Process the response (optional)
        $data = json_decode($response->getBody(), true);

        // Return the response or handle it accordingly
        return response()->json($data);
    }



    
    public function findUsersNearby($latitude, $longitude, $user_id)
{
    // dd($latitude, $longitude, $user_id);
    $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&accept-language=en";
    $options = [
        'http' => [
            'header' => "User-Agent: popular-snd 1.0"
        ]
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $json = json_decode($response, true);
    $address = $json['display_name'] ?? '';

    // 🔹 Get TSO with all saved locations
    $user = User::with('locations')->find($user_id);

    if (!$user) {
        return false; // TSO not found
    }

    // Agar koi location hi nahi set hai → sab allowed
    if ($user->locations->count() == 0) {
        return true;
    }

    $earthRadius = 6371; // Earth's radius in kilometers

    foreach ($user->locations as $location) {
        if ($location->latitude && $location->longitude && $location->radius) {
            $lat2 = $location->latitude;
            $lon2 = $location->longitude;
            $lat1 = $latitude;
            $lon1 = $longitude;

            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);

            $a = sin($dLat / 2) * sin($dLat / 2) +
                 cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                 sin($dLon / 2) * sin($dLon / 2);

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distance = $earthRadius * $c;

            // ✅ Agar user is location ke radius ke andar hai → allow
            if ($distance <= $location->radius) {
                return true;
            }
        }
    }

    // ✅ Agar koi bhi location match nahi hui
    return false;
}


    // public function getSalesData()
    // {
    //     $user = $this->getAuthenticatedUser();

    //     if ($user instanceof \Illuminate\Http\JsonResponse) {
    //         return $user; // Return the error response if no authenticated user
    //     }


    //     $today_sale = RetailSaleOrder::where('user_id', $user->id)
    //         ->whereDate('created_at', Carbon::today())
    //         ->count();

    //     // Count orders created in the current month
    //     $monthly_sale = RetailSaleOrder::where('user_id', $user->id)
    //         ->whereYear('created_at', Carbon::now()->year)
    //         ->whereMonth('created_at', Carbon::now()->month)
    //         ->count();


    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Dashboard Data Fetched Successfully',
    //         'data' => [
    //             'today_sale' => $today_sale,
    //             'monthly_sale' => $monthly_sale,
    //         ],
    //     ]);
    // }


    public function getSalesData()
{
    $user = $this->getAuthenticatedUser();

    if ($user instanceof \Illuminate\Http\JsonResponse) {
        return $user; // Return the error response if no authenticated user
    }

    // Sum of qty for today's sale
    $today_sale = RetailSaleOrder::where('user_id', $user->id)
        ->whereDate('retail_sale_orders.created_at', Carbon::today())
        ->join('retail_sale_order_details', 'retail_sale_orders.id', '=', 'retail_sale_order_details.retail_sale_order_id')
        ->sum('retail_sale_order_details.qty');

    // Sum of qty for current month's sale
    $monthly_sale = RetailSaleOrder::where('user_id', $user->id)
        ->whereYear('retail_sale_orders.created_at', Carbon::now()->year)
        ->whereMonth('retail_sale_orders.created_at', Carbon::now()->month)
        ->join('retail_sale_order_details', 'retail_sale_orders.id', '=', 'retail_sale_order_details.retail_sale_order_id')
        ->sum('retail_sale_order_details.qty');

   return response()->json([
    'success' => true,
    'message' => 'Dashboard Data Fetched Successfully',
    'data' => [
        'today_sale' => (int) $today_sale,
        'monthly_sale' => (int) $monthly_sale,
    ],
]);
}

    protected function getAuthenticatedUser()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'No authenticated user'], 401);
        }

        return $user;
    }
}
