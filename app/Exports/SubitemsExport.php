<?php

namespace App\Exports;

use App\Models\Subitem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class SubitemsExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Subitem::leftJoin('brands', 'brands.id', 'subitem.brand_id')
            ->leftJoin('uom', 'uom.id', 'subitem.uom')
            ->leftJoin('company_groups', 'company_groups.id', 'subitem.group_id')
            ->leftJoin('category', 'category.id', 'subitem.main_ic_id')
            ->leftJoin('sub_category', 'sub_category.id', 'subitem.sub_category_id')
            ->leftJoin('product_classifications', 'product_classifications.id', 'subitem.product_classification_id')
            ->leftJoin('product_type', 'product_type.product_type_id', 'subitem.product_type_id')
            ->leftJoin('product_trends', 'product_trends.id', 'subitem.product_trend_id')
            ->leftJoin('tax_types', 'tax_types.id', 'subitem.tax_type_id')
            ->leftJoin('hs_codes', 'hs_codes.id', 'subitem.hs_code')
             ->leftJoin('products_principal_group', 'products_principal_group.id', 'subitem.principal_group_id') // New join
            ->where('subitem.status', 1);

        // Apply filters
        if (!empty($this->filters['category'])) {
            $query->where('subitem.main_ic_id', $this->filters['category']);
        }
        if (!empty($this->filters['sub_category'])) {
            $query->where('subitem.sub_category_id', $this->filters['sub_category']);
        }
        if (!empty($this->filters['product_trend_id'])) {
            $query->whereIn('subitem.product_trend_id', $this->filters['product_trend_id']);
        }
        if (!empty($this->filters['product_classification_id'])) {
            $query->whereIn('subitem.product_classification_id', $this->filters['product_classification_id']);
        }
        if (!empty($this->filters['brand_ids'])) {
            $query->whereIn('subitem.brand_id', $this->filters['brand_ids']);
        }
        if (!empty($this->filters['username'])) {
            $query->whereIn('subitem.username', $this->filters['username']);
        }
        if (isset($this->filters['product_status']) && $this->filters['product_status'] !== '') {
            $query->where('subitem.product_status', $this->filters['product_status']);
        }
        if (!empty($this->filters['search'])) {
            $search = strtolower($this->filters['search']);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(subitem.product_name) LIKE ?', ["%$search%"])
                    ->orWhereRaw('LOWER(subitem.sku_code) LIKE ?', ["%$search%"])
                    ->orWhereRaw('LOWER(subitem.product_barcode) LIKE ?', ["%$search%"])
                    ->orWhereRaw('LOWER(subitem.sys_no) LIKE ?', ["%$search%"])
                    ->orWhereRaw('LOWER(brands.name) LIKE ?', ["%$search%"]);
            });
        }

        $subitems = $query
            ->select(
                'subitem.sys_no',
                'subitem.sku_code',
                'subitem.product_name',
                'subitem.product_description',
                'uom.uom_name as uom_name',
                'subitem.packing',
                'subitem.product_barcode',
                'brands.name as brand_name',
                'company_groups.name as group_name',
                'category.main_ic as category_name',
                'sub_category.sub_category_name as sub_category_name',
                'product_classifications.name as product_classification_name',
                'product_type.type as product_type_name',
                'product_trends.name as product_trend_name',
                'subitem.purchase_price',
                'subitem.sale_price',
                'subitem.mrp_price',
                'subitem.is_tax_apply',
                'tax_types.name as tax_type_name',
                'subitem.tax_applied_on',
                'subitem.tax_policy',
                'subitem.tax',
                'subitem.flat_discount',
                'subitem.min_qty',
                'subitem.max_qty',
                'subitem.hs_code as hs_code_name',
                'subitem.locality',
                'subitem.origin',
                'subitem.color',
                'subitem.product_status',
                'subitem.is_barcode_scanning',
                'products_principal_group.products_principal_group as principal_group_name' // New column
            
            )
            ->get()
            ->map(function ($item, $index) {
                return [
                $index + 1, // S No (serial number)
                $item->sys_no, // System Code
                $item->sku_code, // SKU / Article No
                $item->product_name, // Product Name
                $item->product_description, // Product Description
                $item->uom_name, // UOM
                $item->packing, // Packing
                $item->product_barcode, // Product barcode
                $item->brand_name, // Brand
                $item->group_name, // Group
                $item->category_name, // Category
                $item->sub_category_name, // Sub-Category
                $item->product_classification_name, // Product Classification
                $item->product_type_name, // Product Type
                $item->product_trend_name, // Product trend
                $item->purchase_price, // Purchase Price
                $item->sale_price, // Sale Price
                $item->mrp_price, // MRP Price
                $item->is_tax_apply ? 'yes' : 'no', // is Tax apply
                $item->tax_type_name, // Tax Type
                $item->tax_applied_on, // Tax Applied On
                $item->tax_policy, // Tax Policy
                $item->tax, // Tax %
                $item->flat_discount, // Product Flat Discount(%)
                $item->min_qty, // Min Qty
                $item->max_qty, // Max Qty
               // Image Link (empty as requested) - यह पोजीशन 27 पर होना चाहिए
                $item->hs_code_name, // HS Code - यह पोजीशन 28 पर होना चाहिए
                $item->locality, 
               
                
                $item->origin, // Origin - यह पोजीशन 30 पर होना चाहिए
                   '',// Locality - यह पोजीशन 29 पर होना चाहिए
                $item->color, // Color - यह पोजीशन 31 पर होना चाहिए
                $item->product_status, // Product Status - यह पोजीशन 32 पर होना चाहिए
                $item->is_barcode_scanning ? 'yes' : 'no', // Barcode Scanning - यह पोजीशन 33 पर होना चाहिए
                $item->principal_group_name // Principal Group - यह पोजीशन 34 पर होना चाहिए
            ];

            });

        return $subitems;
    }

    public function headings(): array
    {
        return [
            'S No',
            'System Code',
            'SKU / Article No',
            'Product Name',
            'Product Description',
            'UOM',
            'Packing',
            'Product barcode',
            'Brand',
            'Group',
            'Category',
            'Sub-Category',
            'Product Classification',
            'Product Type',
            'Product trend',
            'Purchase Price',
            'Sale Price',
            'MRP Price',
            'is Tax apply',
            'Tax Type',
            'Tax Applied On',
            'Tax Policy',
            'Tax %',
            'Product Flat Discount(%)',
            'Min Qty',
            'Max Qty',
            'Image Link',
            'HS Code',
            'Locality',
            'Origin',
            'Color',
            'Product Status',
            'Barcode Scanning',
             'Principal Group'
        ];
    }
}

