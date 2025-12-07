<?php

namespace App\Http\Controllers;

use App\Helpers\SalesHelper;
use App\Models\CustomerSpecialPrice;
use Illuminate\Http\Request;
use DB;
class SpecialPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $CustomerSpecialPrice = CustomerSpecialPrice::where('customer_special_prices.status',1)
            ->leftJoin('customers','customers.id','customer_special_prices.customer_id')
            ->select('customer_special_prices.*','customers.name as customer_name')
            ->get();
        return view('Purchase.SpecialPrice.index', compact('CustomerSpecialPrice'));

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Purchase.SpecialPrice.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SpecialPrice  $specialPrice
     * @return \Illuminate\Http\Response
     */
    public function show(SpecialPrice $specialPrice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SpecialPrice  $specialPrice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $CustomerSpecialPrice = CustomerSpecialPrice::where('customer_special_prices.id', $id)
            ->where('customer_special_prices.status',1)
            ->leftJoin('customers', 'customers.id', '=', 'customer_special_prices.customer_id')
            ->select('customer_special_prices.*', 'customers.name as customer_name')
            ->firstOrFail(); // This will retrieve the first record or fail if not found

        return view('Purchase.SpecialPrice.edit',compact('CustomerSpecialPrice'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SpecialPrice  $specialPrice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $CustomerSpecialPrice = CustomerSpecialPrice::findorfail($id);
        $CustomerSpecialPrice->update($request->all());
        return redirect()->back()->with('success','Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SpecialPrice  $specialPrice
     * @return \Illuminate\Http\Response
     */
    public function destroy(SpecialPrice $specialPrice)
    {
        //
    }



    public function import(Request $request)
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
                return explode('*', $customer)[0]; // Get the customer ID from the value
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
                        if (count($row) < 4) continue; // Skip rows with missing columns

                        $productId = $row[0];

                        // Check if the entry already exists
                        $existingEntry = CustomerSpecialPrice::where('customer_id', $customerId)
                            ->where('product_id', $productId)
                            ->latest()
                            ->first();

                        // Update existing entry to inactive if found
                        if ($existingEntry) {
                            $existingEntry->update(['status' => 0]);
                        }

                        // Create a new entry with active status
                        CustomerSpecialPrice::create([
                            'customer_id' => $customerId,
                            'product_id' => $productId,
                            'product_code' => $row[1] ?? '',
                            'mrp_price' => $row[2] ?? '',
                            'sale_price' => $row[3] ?? '',
                            'status' => 1,
                        ]);
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



    public function importexxx(Request $request)
    {
        $request->validate([
            'customers' => 'required',
            'file' => 'required',
        ]);






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







        $file = $request->file('file')->getRealPath();




        // Open the file for reading
        if (($handle = fopen($file, 'r')) !== FALSE) {
            $header = fgetcsv($handle, 1000, ","); // Skip the header row

//            DB::beginTransaction();

            try {
                foreach ($customerIds as $customerId) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {


                        // Assuming first column is customer_id, second is product_id, and so on.
                        $productId = $row[0];  // Product ID

                        // Check if the entry already exists
                        $existingEntry = CustomerSpecialPrice::where('customer_id', $customerId)
                            ->where('product_id', $productId)
                            ->latest() // Fetch the most recent entry
                            ->first(); // Get the single record


                        if ($existingEntry) {
                            // If entry exists, update status to 0
                            $existingEntry->update(['status' => 0]);
                        }

                        // Create a new entry with active status
                        CustomerSpecialPrice::create([
                            'customer_id' => $customerId,
                            'product_id' => $productId,
                            'product_code' => $row[1] ?? '',   // Third column: product_code
                            'mrp_price' => $row[2] ?? '',      // Fourth column: mrp_price
                            'sale_price' => $row[3] ?? '',     // Fifth column: sale_price
                            'status' => 1,                     // New entry with active status
                        ]);
                    }
                    $dddd[] = $customerId;
                }
                // Commit the transaction after successful processing
//                DB::commit();

                return response()->json(['success' => true, 'message' => 'Excel data imported successfully.','data'=>$dddd]);
            } catch (\Exception $e) {
                // Rollback the transaction if there's an error
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded.']);
        }
    }


    public function importeee(Request $request)
    {
        $request->validate([
            'customers' => 'required',
            'file' => 'required',
        ]);

        // Retrieve selected customer IDs
        if (in_array('all', $request->input('customers'))) {
            $customers = SalesHelper::get_all_customer();
            $customerIds = $customers->pluck('id')->toArray();
        } else {
            $customerIds = collect($request->input('customers'))->map(fn($customer) => explode('*', $customer)[0])->toArray();
        }

        // Handle file processing
        $filePath = $request->file('file')->getRealPath();
        if (($handle = fopen($filePath, 'r')) === FALSE) {
            return response()->json(['success' => false, 'message' => 'Failed to open the uploaded file.']);
        }

        // Skip header
        fgetcsv($handle, 1000, ",");

        DB::beginTransaction();
        try {
            $newEntries = [];
            $existingEntries = CustomerSpecialPrice::whereIn('customer_id', $customerIds)->get()->groupBy('customer_id');

            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $productId = $row[0] ?? null;

                foreach ($customerIds as $customerId) {
                    $existingEntry = $existingEntries[$customerId]->firstWhere('product_id', $productId);

                    if ($existingEntry) {
                        // Update status to inactive if entry exists
                        $existingEntry->update(['status' => 0]);
                    }

                    // Prepare new active entry
                    $newEntries[] = [
                        'customer_id' => $customerId,
                        'product_id' => $productId,
                        'product_code' => $row[1] ?? '',
                        'mrp_price' => $row[2] ?? '',
                        'sale_price' => $row[3] ?? '',
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Batch insert new entries
            CustomerSpecialPrice::insert($newEntries);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Excel data imported successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        } finally {
            fclose($handle);
        }
    }


// Custom function to manually read Excel file
    protected function readExcel($filePath)
    {
        $data = [];

        // Open the file for reading
        if (($handle = fopen($filePath, 'rb')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                $data[] = $row; // Store the row in data array
            }
            fclose($handle);
        }

        return $data;
    }

}

