<?php

namespace App\Http\Controllers;

use App\Helpers\SalesHelper;
use App\Models\CustomerDiscount;
use App\Models\CustomerSpecialPrice;
use App\Models\Subitem;
use Illuminate\Http\Request;
use DB;
class CustomerDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $CustomerSpecialPrice = CustomerDiscount::where('customer_discounts.status',1)
            ->leftJoin('customers','customers.id','customer_discounts.customer_id')
            ->select('customer_discounts.*','customers.name as customer_name')
            ->get();
        return view('Purchase.customerDiscount.index', compact('CustomerSpecialPrice'));
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
        // Validate the form data
        $request->validate([
            'brands' => 'required',
            'products' => 'required',
            'customers' => 'required',
            'discount' => 'required|numeric',
        ]);

        $brandId = $request->input('brands');
        $productId = $request->input('products');
        $discountPercentage = $request->input('discount');

        // If "All Customers" is selected
        if (in_array('all', $request->input('customers'))) {
            // Get all customer IDs
            $customers = SalesHelper::get_all_customer();
            $customerIds = $customers->pluck('id');
        } else {
            // If specific customers are selected
            $customerIds = collect($request->input('customers'))->map(function($customer) {
                return explode('*', $customer)[0]; // Get the customer ID from the value
            });
        }

        // Loop through each customer and handle the discount
        foreach ($customerIds as $customerId) {
            // Check if an entry already exists for the same product and customer
            $existingDiscount = CustomerDiscount::where('product_id', $productId)
                ->where('customer_id', $customerId)
                ->where('status', 1)
                ->first();

            if ($existingDiscount) {
                // Set the status of the previous entry to 0 (inactive)
                CustomerDiscount::where('id', $existingDiscount->id)
                    ->update(['status' => 0]);
            }

            // Create a new row for the current customer and product
            CustomerDiscount::create([
                'status' => 1,
                'customer_id' => $customerId,
                'brand_id' => $brandId,
                'product_id' => $productId,
                'discount_percentage' => $discountPercentage,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Discounts applied successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerDiscount  $customerDiscount
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerDiscount $customerDiscount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerDiscount  $customerDiscount
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerDiscount $customerDiscount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerDiscount  $customerDiscount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerDiscount $customerDiscount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerDiscount  $customerDiscount
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerDiscount $customerDiscount)
    {
        //
    }



    public function customerDiscountBrandWiseImport(Request $request)
    {
        // Validate input
        $request->validate([
            'customers' => 'required|array',
            'file' => 'required|file',
        ]);



        // Determine customer IDs to process
        if (in_array('all', $request->input('customers'))) {
            $customers = SalesHelper::get_all_customer();
            $customerIds = $customers->pluck('id');
        } else {
            $customerIds = collect($request->input('customers'))->map(function($customer) {
                return $customer; // Get the customer ID from the value
            });
        }

        $file = $request->file('file')->getRealPath();

        if (($handle = fopen($file, 'r')) !== FALSE) {
            $header = fgetcsv($handle, 1000, ","); // Skip the header row
            $processedCustomers = [];

            // Using transaction to ensure atomicity
            DB::transaction(function() use ($handle, $customerIds, &$processedCustomers) {

                foreach ($customerIds as $customerId) {
                    rewind($handle); // Reset file pointer for each customer
                    fgetcsv($handle, 1000, ","); // Skip the header row again

                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {


                        if (count($row) < 3) continue; // Skip rows with missing columns

//                        $productId = $row[0];
                        $brandId = $row[0];
                        $productIds = Subitem::where('brand_id', $brandId)->get()->pluck('id')->toArray();

                        foreach ($productIds as $productId) {
                            // Check if the entry already exists
                            $existingEntry = CustomerDiscount::where('customer_id', $customerId)
                                ->where('product_id', $productId)
                                ->latest()
                                ->first();

                            // Update existing entry to inactive if found
                            if ($existingEntry) {
                                $existingEntry->update(['status' => 0]);
                            }

                            // Create a new entry with active status
                            CustomerDiscount::create([
                                'customer_id' => $customerId,
                                'brand_id' => $brandId,
                                'product_id' => $productId,
                                'discount_percentage' => $row[1],
                                'discount_price' => $row[2],
                                'status' => 1,
                            ]);
                        }

                    }
                    $processedCustomers[] = $customerId;
                }
            });

            fclose($handle);

            return response()->json(['success' => true, 'message' => 'Excel data imported successfully.', 'data' => $processedCustomers]);
        } else {
            return response()->json(['success' => false, 'message' => 'Unable to open file.']);
        }
    }
    public function customerDiscountBrandItemWiseImport(Request $request)
    {
        // Validate input
        $request->validate([
            'customers' => 'required|array',
            'file' => 'required|file',
        ]);



        // Determine customer IDs to process
        if (in_array('all', $request->input('customers'))) {
            $customers = SalesHelper::get_all_customer();
            $customerIds = $customers->pluck('id');
        } else {
            $customerIds = collect($request->input('customers'))->map(function($customer) {
                return $customer; // Get the customer ID from the value
            });
        }

        $file = $request->file('file')->getRealPath();

        if (($handle = fopen($file, 'r')) !== FALSE) {
            $header = fgetcsv($handle, 1000, ","); // Skip the header row
            $processedCustomers = [];

            // Using transaction to ensure atomicity
            DB::transaction(function() use ($handle, $customerIds, &$processedCustomers) {

                foreach ($customerIds as $customerId) {
                    rewind($handle); // Reset file pointer for each customer
                    fgetcsv($handle, 1000, ","); // Skip the header row again

                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {


                        if (count($row) < 3) continue; // Skip rows with missing columns

                        $productId = $row[0];

                        $existingEntry = CustomerDiscount::where('customer_id', $customerId)
                            ->where('product_id', $productId)
                            ->latest()
                            ->first();

                        // Update existing entry to inactive if found
                        if ($existingEntry) {
                            $existingEntry->update(['status' => 0]);
                        }
                        $produc = Subitem::where('id', $productId)->first();


                        if($produc){
                            // Create a new entry with active status
                            CustomerDiscount::create([
                                'customer_id' => $customerId,
                                'brand_id' => $produc->brand_id,
                                'product_id' => $productId,
                                'discount_percentage' => $row[1],
                                'status' => 1,
                            ]);

                        }

                    }
                    $processedCustomers[] = $customerId;
                }
            });

            fclose($handle);

            return response()->json(['success' => true, 'message' => 'Excel data imported successfully.', 'data' => $processedCustomers]);
        } else {
            return response()->json(['success' => false, 'message' => 'Unable to open file.']);
        }
    }




}
