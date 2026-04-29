<?php

namespace App\Exports;

use App\BAFormation;
use App\Models\Brand;
use App\Employees;
use App\TargetItems;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class BaTargetsExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $year = $this->filters['year'] ?? date('Y');
        $month = $this->filters['month'] ?? date('n');
        $target_type = $this->filters['target_type'] ?? 'qty';

        // Get all formations with eager loading
        $formations = BAFormation::with(['customer', 'employee'])->get();
        
        $data = [];
        foreach ($formations as $f) {
            $brandIds = json_decode($f->brands_ids, true) ?? [];
            if (empty($brandIds)) continue;

            $brands = Brand::whereIn('id', $brandIds)->get();
            
            foreach ($brands as $brand) {
                // Get existing target if any
                $existing = TargetItems::where('year', $year)
                    ->where('month', (int)$month)
                    ->where('employee_id', $f->employee_id)
                    ->where('customer_id', $f->customer_id)
                    ->where('brand_id', $brand->id)
                    ->where('target_type', $target_type)
                    ->first();

                $data[] = [
                    'employee_id'   => $f->employee_id,
                    'employee_name' => $f->employee->name ?? '',
                    'customer_id'   => $f->customer_id,
                    'customer_name' => $f->customer->name ?? '',
                    'brand_id'      => $brand->id,
                    'brand_name'    => $brand->name ?? '',
                    'year'          => $year,
                    'month'         => (int)$month,
                    'target_type'   => $target_type,
                    'target_value'  => $existing ? $existing->target : ''
                ];
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Employee ID',
            'Employee Name',
            'Store ID',
            'Store Name',
            'Brand ID',
            'Brand Name',
            'Year',
            'Month',
            'Target Type',
            'Target Value (Qty/Amount)'
        ];
    }
}
