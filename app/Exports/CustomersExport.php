<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use App\Helpers\CommonHelper;
use Illuminate\Support\Carbon;

class CustomersExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        // Use the main database name for countries, states, and cities
        $mainDatabase = 'inpl2erp_brands_master';
        
        $query = Customer::leftJoin(DB::raw("`$mainDatabase`.`countries` as countries"), 'countries.id', 'customers.country')
            ->leftJoin(DB::raw("`$mainDatabase`.`states` as states"), 'states.id', 'customers.province')
            ->leftJoin(DB::raw("`$mainDatabase`.`cities` as cities"), 'cities.id', 'customers.city')
            ->leftJoin('stores_categories', 'stores_categories.id', 'customers.store_category')
            ->leftJoin('territories', 'territories.id', 'customers.territory_id')
            ->leftJoin('customer_types', 'customer_types.id', 'customers.CustomerType')
            ->leftJoin('warehouse as wf', 'wf.id', 'customers.warehouse_from')
            ->leftJoin('warehouse as wt', 'wt.id', 'customers.warehouse_to')
            ->leftJoin('bank_detail', 'bank_detail.acc_id', 'customers.acc_id');

        // Apply filters
        if (!empty($this->filters['customer_type'])) {
            $query->whereIn('customers.CustomerType', $this->filters['customer_type']);
        }

        if (!empty($this->filters['territory_id'])) {
            $query->whereIn('customers.territory_id', $this->filters['territory_id']);
        }

        if (!empty($this->filters['search'])) {
            $search = strtolower($this->filters['search']);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(customers.name) LIKE ?', ["%$search%"])
                    ->orWhereRaw('LOWER(customers.phone_1) LIKE ?', ["%$search%"])
                    ->orWhereRaw('LOWER(customers.address) LIKE ?', ["%$search%"]);
            });
        }

        $customers = $query
            ->select(
                'customers.name',
                'customers.customer_code',
                'customers.email',
                'customers.phone_1',
                'customers.phone_2',
                'countries.name as country_name',
                'states.name as state_name',
                'cities.name as city_name',
                'customers.address',
                'customers.zip',
                'customers.title',
                'customers.contact_person',
                'customers.contact_person_email',
                'customers.company_shipping_type',
                'customers.shipping_city',
                'customers.shipping_state',
                'customers.shipping_country',
                'customers.opening_balance',
                'customers.opening_balance_date',
                'customers.regd_in_income_tax',
                'customers.cnic_ntn',
                'customers.strn',
                'customers.strn_term',
                'customers.display_note_invoice',
                'customers.wh_tax',
                'customers.creditDaysLimit',
                'customers.creditLimit',
                'customers.locality',
                'stores_categories.name as store_category_name',
                'territories.name as territory_name',
                'customers.SaleRep',
                'customers.accept_cheque',
                'customers.display_pending_payment_invoice',
                'bank_detail.account_no as bank_account_no',
                'bank_detail.account_title as bank_account_title',
                'bank_detail.bank_name',
                'bank_detail.swift_code as branch_code',
                'customer_types.name as customer_type_name',
                'customers.status',
                'customers.employee_id',
                'customers.special_price_mapped',
                'wf.name as warehouse_from_name',
                'wt.name as warehouse_to_name',
                'customers.ba_mapping',
                'customers.adv_tax'
            )
            ->get()
            ->map(function ($item, $index) {
                return [
                 
                    $item->name,
                    $item->customer_code,
                    $item->email,
                    $item->phone_1,
                    $item->phone_2,
                    $item->country_name,
                    $item->state_name,
                    $item->city_name,
                    $item->address,
                    $item->zip,
                    $item->title,
                    $item->contact_person,
                    $item->contact_person_email,
                    $item->company_shipping_type,
                    $item->shipping_city,
                    $item->shipping_state,
                    $item->shipping_country,
                    $item->opening_balance,
                    $item->opening_balance_date ? Carbon::parse($item->opening_balance_date)->format('Y-m-d') : '',
                    $item->regd_in_income_tax,
                    $item->cnic_ntn,
                    $item->strn,
                    $item->strn_term,
                    $item->display_note_invoice,
                    $item->wh_tax,
                    $item->creditDaysLimit,
                    $item->creditLimit,
                    $item->locality,
                    $item->store_category_name,
                    $item->territory_name,
                    $item->SaleRep,
                    $item->accept_cheque,
                    $item->display_pending_payment_invoice,
                    $item->bank_account_no,
                    $item->bank_account_title,
                    $item->bank_name,
                    $item->branch_code,
                    $item->customer_type_name,
                    $item->status == 1 ? 'Active' : 'Inactive',
                    $item->employee_id,
                    $item->special_price_mapped,
                    $item->warehouse_from_name,
                    $item->warehouse_to_name,
                    $item->ba_mapping == 1 ? 'Yes' : 'No',
                    $item->adv_tax
                ];
            });

        return $customers;
    }

    public function headings(): array
    {
        return [
           
            'Name',
            'Customer Code',
            'Email',
            'Phone no 1',
            'Phone no 2',
            'Country',
            'State',
            'City',
            'Address',
            'Zip',
            'Title',
            'Contact Person',
            'Contact Person Email',
            'Company Shipping Address',
            'Shipping City',
            'Shipping State',
            'Shipping Country',
            'Opening Balance',
            'Opening Balance Date',
            'Tax Filer Registered',
            'NTN',
            'STRN',
            'STRN Note',
            'Display Notes in Invoice',
            'W.H Tax',
            'Credit Days',
            'Credit Amount Limit',
            'Locality',
            'Stores Category',
            'Territory Id',
            'Sales Person',
            'Accept Cheque',
            'Display Pending Payment in Invoice',
            'Bank Account No',
            'Bank Account Title',
            'Bank',
            'Branch Code',
            'Customer Type',
            'Status',
            'Employee',
            'Special Price Mapped',
            'Warehouse From',
            'Warehouse To',
            'BA Mapping',
            'Adv Tax'
        ];
    }
}