<?php

namespace App\Http\Controllers;

use App\Models\BAStock;
use App\Models\Customer;
use App\Models\Stock;
use App\Models\Subitem;
use App\Models\Warehouse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\OnEachRow;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Row;

class OpeningInventoryController extends Controller
{
    public function index() {

        return  view('BA.opening-inventory.index');
    }

    public function import(Request $request)
    {
        // 1. Validate request
        $validator = Validator::make($request->all(), [
            'xlsx_file' => 'required|mimes:xlsx,xls'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => implode('<br>', $validator->errors()->all())], 422);
        }

        // 2. Get uploaded file
        $file = $request->file('xlsx_file');

        DB::beginTransaction();
        try {

            // 3. Read Excel into array
            // Structure: [sheet][row][column]
            $data = Excel::toArray([], $file);

            if (empty($data) || empty($data[0])) {
                return response()->json(['message' => 'The uploaded file is empty.'], 400);
            }

            // 4. First sheet
            $sheet = $data[0];

            // 5. Header row
            $header = $sheet[0];

            // 6. Remaining rows (actual data)
            $rows = array_slice($sheet, 1);

            // 7. Map customers from header (skip first 4 columns)
            $customer_ids = [];
            $no_customers = [];

            foreach ($header as $colIndex => $customerName) {
                if ($colIndex < 4) continue;
                if (empty($customerName)) continue;

                $customer = Customer::where('name', $customerName)->first();
                if ($customer) {
                    $customer_ids[$colIndex] = $customer->id;
                } else {
                    $no_customers[] = $customerName;
                }
            }

            // 8. Get virtual warehouse
            $warehouse = DB::connection('mysql2')
                ->table('warehouse')
                ->where('is_virtual', 1)
                ->first();

            // Optional: safety check
            if (!$warehouse) {
                return response()->json(['message' => 'Virtual warehouse not found'], 404);
            }

            // 9. Loop through rows
            $success_count = 0;
            foreach ($rows as $rowIndex => $row) {

                // SKU is first column
                $sku = $row[0] ?? null;
                if (!$sku) continue;

                $sub_item_id = Subitem::where('sku_code', $sku)->value('id');
                if (!$sub_item_id) continue;

                // 10. Loop customer quantity columns
                foreach ($row as $colIndex => $qty) {

                    if ($colIndex < 4) continue;       // skip SKU, barcode, brand, item
                    if (!isset($customer_ids[$colIndex])) continue;
                    if (empty($qty) || $qty == 0) continue;

                    DB::connection('mysql2')->table('ba_stock')->insert([
                        'customer_id'  => $customer_ids[$colIndex],
                        'voucher_type' => 9,
                        'sub_item_id'  => $sub_item_id,
                        'qty'          => $qty,
                        'warehouse_id' => $warehouse->id,
                        'status'       => 1,
                        'created_date' => now(),
                        'username'     => auth()->user()->username,
                        'opening'      => 1,
                    ]);
                    $success_count++;
                }
            }

            DB::commit();

            $message = "Successfully processed $success_count records.";
            if (!empty($no_customers)) {
                $message .= "<br><br><b>Note:</b> These customers were not found: " . implode(', ', array_unique($no_customers));
            }

            return response()->json(['message' => $message], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Import failed: ' . $e->getMessage()], 500);
        }
    } 
}
