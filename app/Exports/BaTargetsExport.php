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
        $target_type = $this->filters['target_type'] ?? null; // If null, we might want to export both or just one.
        $employee_id = $this->filters['employee_id'] ?? null;

        // Get all formations with eager loading
        $query = BAFormation::with(['customer', 'employee']);
        
        if ($employee_id) {
            $query->where('employee_id', $employee_id);
        }

        $formations = $query->get();
        
        $data = [];
        foreach ($formations as $f) {
            $brandIds = json_decode($f->brands_ids, true) ?? [];
            if (empty($brandIds)) continue;

            $brands = Brand::whereIn('id', $brandIds)->get();
            
            // If target_type is specified, only export that type. 
            // If not (like from the list view), we might want to export both rows for each brand?
            // Actually, the list view doesn't specify target_type in the filter.
            $types = $target_type ? [$target_type] : ['qty', 'amount'];

            foreach ($brands as $brand) {
                foreach ($types as $type) {
                    // Get existing target if any
                    $existing = TargetItems::where('year', $year)
                        ->where('month', (int)$month)
                        ->where('employee_id', $f->employee_id)
                        ->where('customer_id', $f->customer_id)
                        ->where('brand_id', $brand->id)
                        ->where('target_type', $type)
                        ->first();

                    // Only add if target exists or if we are exporting a template?
                    // Usually from the list view we only want existing targets.
                    // But if it's a "template", we want all formations.
                    // Let's keep it consistent: if target doesn't exist and it's from the list, skip?
                    // User said "export me sahi data nhe ay rah hai", probably means they want the filtered data.
                    
                    if (!$existing && $target_type === null) continue; // Skip empty rows if we are just listing

                    $data[] = [
                        'employee_id'   => $f->employee_id,
                        'employee_name' => $f->employee->name ?? '',
                        'customer_id'   => $f->customer_id,
                        'customer_name' => $f->customer->name ?? '',
                        'brand_id'      => $brand->id,
                        'brand_name'    => $brand->name ?? '',
                        'year'          => $year,
                        'month'         => (int)$month,
                        'target_type'   => $type,
                        'target_value'  => $existing ? $existing->target : ''
                    ];
                }
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
