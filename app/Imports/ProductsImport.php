<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CommonHelper;


class ProductsImport implements ToCollection
{
    private $lookupCache = [];

    private function getCachedId($value, $table)
    {
        $key = $table . ':' . strtolower(trim($value));

        if (isset($this->lookupCache[$key])) {
            return $this->lookupCache[$key];
        }

        $id = CommonHelper::get_id_from_db_by_name_for_product($value, $table);
        $this->lookupCache[$key] = $id;

        return $id;
    }


    public function collection(Collection $rows)
    {
        DB::connection('mysql2')->beginTransaction();

        try {
            foreach ($rows as $key => $row) {
                if ($key === 0) continue; // Skip header row

                $row = $row->toArray();
                $row = array_pad($row, 33, null); // Pad to 33 columns

                $sys_no = $row[1] ?? null;
                $productData = [
                    'sku_code' => $row[2] ?? null,
                    'product_name' => $row[3] ?? null,
                    'product_description' => $row[4] ?? null,
                    // 'uom' => CommonHelper::get_id_from_db_by_name_for_product($row[5], 'uom') ?? 0,
                    'uom' => $this->getCachedId($row[5], 'uom') ?? 0,
                    'packing' => $row[6] ?? null,
                    'product_barcode' => $row[7] ?? null,
                    'brand_id' => $this->getCachedId($row[8], 'brands') ?? 0,
                    'group_id' => $this->getCachedId($row[9], 'company_groups') ?? 0,
                    'main_ic_id' => $this->getCachedId($row[10], 'category') ?? 0,
                    'sub_category_id' => $this->getCachedId($row[11], 'sub_category') ?? 0,
                    'product_classification_id' => $this->getCachedId($row[12], 'product_classifications') ?? 0,
                    'product_type_id' => $this->getCachedId($row[13], 'product_type') ?? 0,
                    'product_trend_id' => $this->getCachedId($row[14], 'product_trends') ?? 0,
                    'purchase_price' => $row[15] ?? 0,
                    'sale_price' => $row[16] ?? 0,
                    'mrp_price' => $row[17] ?? 0,
                    'is_tax_apply' => strtolower($row[18]) === 'yes' ? 1 : 0,
                    'tax_type_id' => $this->getCachedId($row[19], 'tax_types') ?? 0,
                    'tax_applied_on' => $row[20] ?? null,
                    'tax_policy' => $row[21] ?? null,
                    'tax' => $row[22] ?? null,
                    'flat_discount' => $row[23] ?? 0,
                    'min_qty' => $row[24] ?? 0,
                    'max_qty' => $row[25] ?? 0,
                    'hs_code' => $this->getCachedId($row[26], 'hs_codes') ?? 0,
                    'locality' => $row[27] ?? null,
                    'origin' => $row[28] ?? null,
                    'color' => $row[29] ?? null,
                    'product_status' => $row[30] ?? null,
                    'is_barcode_scanning' => strtolower($row[32]) === 'yes' ? 1 : (strtolower($row[32]) === 'no' ? 0 : null),
                    'username' => Auth::user()->name ?? 'system',
                    'date' => date('Y-m-d'),
                ];

                if (!empty($productData['sku_code'])) {
                    if ($sys_no) {
                        $existing = DB::connection('mysql2')->table('subitem')->where('sys_no', $sys_no)->first();
                        if ($existing) {
                            DB::connection('mysql2')->table('subitem')->where('sys_no', $sys_no)->update($productData);
                            continue;
                        }
                    }

                    $productData['sys_no'] = $sys_no ?: CommonHelper::generateUniqueNumber('ITEM-', 'subitem', 'sys_no');
                    DB::connection('mysql2')->table('subitem')->insert($productData);
                }
            }

            DB::connection('mysql2')->commit();
        } catch (\Exception $e) {
            DB::connection('mysql2')->rollBack();
            throw $e;
        }
    }
}
