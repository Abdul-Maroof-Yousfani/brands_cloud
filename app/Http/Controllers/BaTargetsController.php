<?php

namespace App\Http\Controllers;

use App\BAFormation;
use App\BaTargets;
use App\Models\Brand;
use App\Models\Customer;
use App\TargetItems;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class BaTargetsController extends Controller
{
    public function index()
    {
        // Get all Business Associates (Employees) from formations
        $data['employees'] = \App\Employees::whereIn('emp_id', \App\BAFormation::pluck('employee_id')->unique())->get();
        return view('BA.BaTargets.index', $data);
    }

    public function getCustomers(Request $request) {
        [$year, $month] = explode("-", $request->month);
     
        $customers = Customer::where("status", 1)
                        ->get();

        $target_items = TargetItems::where('month', (int)$month)
                        ->where('year', (int)$year)
                        ->get()
                        ->mapWithKeys(function ($item) {
                            return [$item->customer_id . '_' . $item->brand_id => $item];
                        })
                        ->toArray();
   
      

        $brands = Brand::where("status", 1)->get();


        return view("BA.BaTargets.customers", compact("customers", "brands", "target_items", "year", "month"));
    }

    public function insertTarget(Request $request)
{
    $customer_ids = $request->customer_id ?? [];
    $targets      = $request->target ?? [];     // corrected from $request->targets
    $year         = $request->year;
    $month        = $request->month;
    $brands       = $request->brand_id ?? [];

    if (empty($customer_ids) || empty($targets)) {
        return back()->with('error', 'No data submitted');
    }

    $lookupKeys = [];
    $dataByKey  = [];

    foreach ($customer_ids as $customer_id) {
        if (!isset($targets[$customer_id]) || !is_array($targets[$customer_id])) {
            continue;
        }

        foreach ($targets[$customer_id] as $index => $targetValue) {
            if ($targetValue === null || $targetValue === '' || $targetValue == 0) {
                continue;
            }

            $brand_id = $brands[$customer_id][$index] ?? null;
            if (!$brand_id) {
                continue;
            }

            $key = "{$year}-{$month}-{$customer_id}-{$brand_id}";

            $lookupKeys[] = [
                'year'       => $year,
                'month'      => $month,
                'customer_id' => $customer_id,
                'brand_id'   => $brand_id,
            ];

            $dataByKey[$key] = [
                'target'     => $targetValue,
                'customer_id' => $customer_id,
                'brand_id'   => $brand_id,
            ];
        }
    }

    if (empty($lookupKeys)) {
        return back()->with('warning', 'No valid targets to process');
    }

    $existing = TargetItems::where('year', $year)
        ->where('month', $month)
        ->whereIn('customer_id', $customer_ids)
        ->get(['id', 'customer_id', 'brand_id', 'target'])
        ->keyBy(function ($item) use ($year, $month) {
            return "{$year}-{$month}-{$item->customer_id}-{$item->brand_id}";
        });

    $toInsert = [];
    $toUpdate = [];

    foreach ($dataByKey as $key => $data) {
        if (isset($existing[$key])) {
            // exists → update
            $toUpdate[] = [
                'id'     => $existing[$key]->id,
                'target' => $data['target'],
            ];
        } else {
            $toInsert[] = [
                'year'        => $year,
                'month'       => $month,
                'customer_id'  => $data['customer_id'],
                'brand_id'    => $data['brand_id'],
                'target'      => $data['target'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }
    }
    DB::transaction(function () use ($toInsert, $toUpdate) {
        // Bulk insert
        if (!empty($toInsert)) {
            TargetItems::insert($toInsert);
        }

        if (!empty($toUpdate)) {
            foreach ($toUpdate as $item) {
                TargetItems::where('id', $item['id'])
                    ->update(['target' => $item['target']]);
            }
        }
    });

    return back()->with('success', 'Targets processed successfully');
}

    public function listIndex()
    {
        return view('BA.BaTargets.list_index');
    }

    public function getList(Request $request)
    {
        $query = TargetItems::query()
            ->join('customers', 'customers.id', '=', 'target_items.customer_id')
            ->select(
                'target_items.year',
                'target_items.month',
                'target_items.employee_id',
                'target_items.customer_id',
                'customers.name as customer_name',
                DB::raw('MAX(target_items.updated_at) as last_updated')
            );

        if ($request->date) {
            [$year, $month] = explode('-', $request->date);
            $query->where('target_items.year', $year)->where('target_items.month', (int)$month);
        }

        if ($request->employee_id) {
            $query->where('target_items.employee_id', $request->employee_id);
        }

        $query->groupBy('target_items.year', 'target_items.month', 'target_items.employee_id', 'target_items.customer_id', 'customers.name')
            ->orderBy('target_items.year', 'desc')
            ->orderBy('target_items.month', 'desc')
            ->orderBy('last_updated', 'desc');

        $paginated = $query->paginate(10);

        // Fetch brands for these specific groupings to show brand breakdown
        foreach ($paginated as $row) {
            $brandDetails = TargetItems::where([
                'year' => $row->year,
                'month' => $row->month,
                'employee_id' => $row->employee_id,
                'customer_id' => $row->customer_id
            ])->get();

            $row->qty_targets = $brandDetails->where('target_type', 'qty')->pluck('target', 'brand_id')->toArray();
            $row->amount_targets = $brandDetails->where('target_type', 'amount')->pluck('target', 'brand_id')->toArray();
        }

        $data['BaTargets'] = $paginated;
        $data['employees'] = \App\Employees::pluck('name', 'emp_id')->toArray();
        $data['brands'] = \App\Helpers\CommonHelper::get_all_brand()->pluck('name', 'id')->toArray();
        
        return view('BA.BaTargets.getList', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function create()
    {
        return view("BA.BaTargets.create");
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
        'start_date'              => 'required|date',
        'end_date'                => 'required|date|after_or_equal:start_date',
        'status'                  => 'required|integer|in:0,1',

        // Targets array ke andar ke fields ke liye specific rules
        'targets.*.customer_id'   => 'required',
        'targets.*.code'          => 'nullable|string|max:50',           // code optional string
        'targets.*.zone'          => 'nullable|string|max:100',          // zone optional string (N/A allowed)
        'targets.*.brands.*'      => 'nullable|numeric|min:0',           // har brand qty number ya null
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    DB::beginTransaction();
    try {
        $created = [];
        $targets_input = $request->input('targets', []);
        
        foreach ($targets_input as $index => $row) {
            $customer_id    = $row['customer_id'] ?? null;
            $brand_targets  = $row['brands'] ?? [];

            $has_target = false;
            foreach ($brand_targets as $qty) {
                if (is_numeric($qty) && $qty > 0) {
                    $has_target = true;
                    break;
                }
            }

            if (!$customer_id || !$has_target) {
                continue; // skip empty/invalid rows
            }

            $data = [
                'customer_id' => $customer_id,
                'start_date'  => $request->start_date,
                'end_date'    => $request->end_date,
                'targets'     => $brand_targets, // array [brand_id => qty]
                'status'      => $request->status,
            ];

            $baTarget = BaTargets::create($data);
            dd("test");
            $created[] = $baTarget;
        }

        DB::commit();

        return response()->json([
            'success' => 'Successfully Saved.',
            'count'   => count($created),
            'data'    => $created
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    /**
     * Display the specified resource.
     *
     * @param  \App\BaTargets  $baTargets
     * @return \Illuminate\Http\Response
     */
    public function show(BaTargets $baTargets)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BaTargets  $baTargets
     * @return \Illuminate\Http\Response
     */
    public function edit(BaTargets $baTargets)
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
        $validator = Validator::make($request->all(), [
            'customer' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|integer|in:0,1',
            'targets.*' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $data = [
                'customer_id' => $request->input('customer'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'targets' => $request->input('targets', []),
                'status' => $request->input('status'),
            ];

            $baTarget = BaTargets::findOrFail($id);
            $baTarget->update($data);

            DB::commit();

            return response()->json(['success' => 'Successfully Updated.', 'data' => $baTarget]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BaTargets  $baTargets
     * @return \Illuminate\Http\Response
     */
    public function destroy(BaTargets $baTargets)
    {
        //
    }    public function targetReport(Request $request)
    {
        $date = $request->date ?? date('Y-m');
        $target_type = $request->target_type ?? 'qty';
        $employee_filter = $request->employee_id; // Can be array now
        [$year, $month] = explode('-', $date);

        $reportsQuery = TargetItems::where('year', $year)
            ->where('month', (int)$month)
            ->where('target_type', $target_type);
        
        if ($employee_filter) {
            $reportsQuery->where('employee_id', $employee_filter);
        }

        $reports = $reportsQuery->get();

        $employeeIds = $reports->pluck('employee_id')->unique();
        $brandIds = $reports->pluck('brand_id')->unique();

        // Get all employees for the filter dropdown
        $all_employees = \App\Employees::pluck('name', 'emp_id');
        
        $employees = \App\Employees::whereIn('emp_id', $employeeIds)->pluck('name', 'emp_id');
        $brands = Brand::whereIn('id', $brandIds)->pluck('name', 'id');

        // Sales - Grouped by employee_id and brand_id
        $salesQuery = DB::connection('mysql2')->table('retail_sale_orders as so')
            ->join('retail_sale_order_details as sod', 'so.id', '=', 'sod.retail_sale_order_id')
            ->whereIn('so.user_id', $employeeIds)
            ->whereYear('so.sale_order_date', $year)
            ->whereMonth('so.sale_order_date', $month);

        if ($target_type == 'amount') {
            $salesQuery->join('subitem as si', 'si.id', '=', 'sod.product_id')
                ->select('so.user_id as employee_id', 'sod.brand_id', DB::raw('SUM(sod.qty * si.mrp_price) as total_val'));
        } else {
            $salesQuery->select('so.user_id as employee_id', 'sod.brand_id', DB::raw('SUM(sod.qty) as total_val'));
        }

        $sales = $salesQuery->groupBy('so.user_id', 'sod.brand_id')->get();

        $salesData = [];
        foreach ($sales as $sale) {
            $salesData[$sale->employee_id][$sale->brand_id] = $sale->total_val;
        }

        // Returns - Grouped by employee_id and brand_id
        $returnsQuery = DB::connection('mysql2')->table('retail_sale_order_returns as rsor')
            ->join('retail_sale_order_return_details as rsord', 'rsor.id', '=', 'rsord.retail_sale_order_return_id')
            ->whereIn('rsor.user_id', $employeeIds)
            ->whereYear('rsor.created_at', $year)
            ->whereMonth('rsor.created_at', $month);

        if ($target_type == 'amount') {
            $returnsQuery->join('subitem as si', 'si.id', '=', 'rsord.product_id')
                ->select('rsor.user_id as employee_id', 'rsord.brand_id', DB::raw('SUM(rsord.quantity * si.mrp_price) as total_val'));
        } else {
            $returnsQuery->select('rsor.user_id as employee_id', 'rsord.brand_id', DB::raw('SUM(rsord.quantity) as total_val'));
        }

        $returns = $returnsQuery->groupBy('rsor.user_id', 'rsord.brand_id')->get();

        $returnsData = [];
        foreach ($returns as $ret) {
            $returnsData[$ret->employee_id][$ret->brand_id] = $ret->total_val;
        }

        return view('BA.BaTargets.report', compact(
            'reports',
            'employees',
            'all_employees', // For filter
            'brands',
            'year',
            'month',
            'salesData',
            'returnsData',
            'target_type',
            'employee_filter'
        ));
    }


    public function loadBaWise(Request $request)
    {
        $employee_id = $request->employee_id;
        $month_year = $request->month_year;
        $target_type = $request->target_type;
        
        if (empty($month_year) || strpos($month_year, '-') === false) {
            return '<div class="alert alert-warning">Invalid Date selected.</div>';
        }

        [$year, $month] = explode('-', $month_year);

        // Ensure target_type column exists
        try {
            if (!Schema::connection('mysql2')->hasColumn('target_items', 'target_type')) {
                DB::connection('mysql2')->statement("ALTER TABLE target_items ADD COLUMN target_type VARCHAR(20) DEFAULT 'qty' AFTER brand_id");
            }
        } catch (\Exception $e) {
            // Log or ignore if cannot alter
        }

        // Get all formations for this BA (which define assigned stores and brands)
        $formations = \App\BAFormation::where('employee_id', $employee_id)
            ->with(['customer'])
            ->get();

        // Get existing targets to pre-fill (FILTERED BY TYPE)
        $existing_targets = \App\TargetItems::where('year', $year)
            ->where('month', (int)$month)
            ->where('employee_id', $employee_id)
            ->where('target_type', $target_type)
            ->get()
            ->keyBy(function($item) {
                return $item->customer_id . '_' . $item->brand_id;
            });

        // Resolve Brands for each formation
        foreach ($formations as $f) {
            $brandIds = json_decode($f->brands_ids, true) ?? [];
            $f->assigned_brands = \App\Models\Brand::whereIn('id', $brandIds)->get();
        }

        return view('BA.BaTargets.ba_wise_load', compact('formations', 'target_type', 'month_year', 'employee_id', 'existing_targets'));
    }

    public function saveBaWise(Request $request) 
    {
        try {
            $employee_id = $request->employee_id;
            $month_year = $request->month_year;
            $target_type = $request->target_type;
            $targets = $request->targets ?? [];

            if (empty($month_year) || strpos($month_year, '-') === false) {
                return response()->json(['success' => false, 'message' => 'Invalid Date.']);
            }

            [$year, $month] = explode('-', $month_year);

            DB::beginTransaction();

            foreach ($targets as $cust_id => $brands) {
                foreach ($brands as $brand_id => $value) {
                    if ($value === null || $value === '') continue;

                    \App\TargetItems::updateOrCreate(
                        [
                            'year'        => $year,
                            'month'       => (int)$month,
                            'employee_id' => $employee_id,
                            'customer_id' => $cust_id,
                            'brand_id'    => $brand_id,
                            'target_type' => $target_type // Use target_type in key to separate them
                        ],
                        [
                            'target'      => $value,
                            'updated_at'  => now()
                        ]
                    );
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Targets saved successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function import()
    {
        return view('BA.BaTargets.import');
    }

    public function exportTemplate(Request $request)
    {
        $filters = $request->only(['year', 'month', 'employee_id', 'target_type']);
        
        $year = $filters['year'] ?? date('Y');
        $month = (int)($filters['month'] ?? date('m'));
        $monthName = date('F', mktime(0, 0, 0, $month, 1));
        
        $fileName = 'BA_Targets_' . $year . '_' . $monthName . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BaTargetsExport($filters), $fileName);
    }

    public function exportPdf(Request $request)
    {
        $filters = $request->only(['year', 'month', 'employee_id']);
        $year = $filters['year'] ?? date('Y');
        $month = (int)($filters['month'] ?? date('m'));
        
        $export = new \App\Exports\BaTargetsExport($filters);
        $data = $export->collection();
        
        $data_arr = [
            'rows' => $data,
            'year' => $year,
            'month' => date('F', mktime(0, 0, 0, $month, 1)),
            'title' => 'BA Performance Targets'
        ];

        // If dompdf is available, we can use it. Otherwise, return a printable view.
        // For now, let's return a clean printable view that the browser can save as PDF.
        return view('BA.BaTargets.pdf_export', $data_arr);
    }

    public function importExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'xlsx_file' => 'required|mimes:xlsx,xls'
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Please upload a valid Excel file.');
        }

        try {
            $data = \Maatwebsite\Excel\Facades\Excel::toArray([], $request->file('xlsx_file'));
            if (empty($data) || empty($data[0])) {
                return back()->with('error', 'The uploaded file is empty.');
            }

            $rows = array_slice($data[0], 1); // skip header
            $success_count = 0;

            DB::beginTransaction();
            foreach ($rows as $row) {
                $employee_id = $row[0] ?? null;
                $customer_id = $row[2] ?? null;
                $brand_id    = $row[4] ?? null;
                $year         = $row[6] ?? null;
                $month        = $row[7] ?? null;
                $target_type = $row[8] ?? 'qty';
                $target_value = $row[9] ?? null;

                if (!$employee_id || !$customer_id || !$brand_id || $target_value === null || $target_value === '') {
                    continue;
                }

                TargetItems::updateOrCreate(
                    [
                        'year'        => $year,
                        'month'       => (int)$month,
                        'employee_id' => $employee_id,
                        'customer_id' => $customer_id,
                        'brand_id'    => $brand_id,
                        'target_type' => $target_type
                    ],
                    [
                        'target'      => $target_value,
                        'updated_at'  => now()
                    ]
                );
                $success_count++;
            }
            DB::commit();

            return back()->with('success', "Successfully imported $success_count target records.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
