<?php
namespace App\Helpers;
use App\Models\Countries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\Input;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\SupplierInfo;
use App\Models\Subitem;
use App\Models\Employee;
use App\Models\EmployeeExitClearance;
use App\Models\MenuPrivileges;
use App\Models\Menu;
use App\Models\WorkStation;
use App\Models\UOM;
use App\Models\PurchaseVoucher;
use App\Models\Account;
use App\Models\FinanceDepartment;
use App\Models\Transactions;
use App\Models\CostCenter;
use App\Models\DepartmentAllocation1;
use App\Models\SalesTaxDepartmentAllocation;
use App\Models\CostCenterDepartmentAllocation;
use App\Models\PurchaseType;
use App\Models\Currency;
use App\Models\GRNData;
use App\Models\DemandType;
use App\Models\Warehouse;
use App\Models\GoodsReceiptNote;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestData;
use App\Models\PurchaseVoucherThroughGrn;
use App\Models\PurchaseVoucherThroughGrnData;
use App\Models\SubItemCharges;
use App\Models\Pvs;
use App\Models\Pvs_data;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Region;
use App\Models\SurveryBy;
use App\Models\Client;
use App\Models\Type;
use App\Models\ProductType;
use App\Models\Cities;
use App\Models\ResourceAssigned;
use App\Models\ClientJob;
use App\Models\IncomeTaxDeduction;
use App\Models\NewPvData;
use App\Models\JobOrder;
use App\Models\JobOrderData;
use App\Models\Estimate;
use App\Models\NewPurchaseVoucherPayment;
use App\Models\NewPurchaseVoucher;
use App\Models\PaidTo;
use App\Models\Emp;
use App\Models\Branch;
use App\Models\CompanyGroup;
use App\Models\CompanyLocation;
use App\Models\MaterialRequisitionData;
use App\Models\ProductionWorkOrder;
use App\Models\Prospect;
use App\Models\Regions;
use App\Models\SaleQuotation;
use App\Models\Sales_Order;
use App\Models\SalesTaxGroup;
use App\User;
use Illuminate\Support\Facades\Storage;
use Session;

class DashboardHelper
{

    public static function SalesFlowChart($year)
    {
        $data = DB::Connection('mysql2')->table(DB::raw("
                        (SELECT 'January' as month_name
                        UNION SELECT 'February'
                        UNION SELECT 'March'
                        UNION SELECT 'April'
                        UNION SELECT 'May'
                        UNION SELECT 'June'
                        UNION SELECT 'July'
                        UNION SELECT 'August'
                        UNION SELECT 'September'
                        UNION SELECT 'October'
                        UNION SELECT 'November'
                        UNION SELECT 'December') calendar
                    "))
                    ->leftJoin('sales_tax_invoice as sti', function ($join) use ($year) {
                        $join->on(DB::raw('calendar.month_name'), '=', DB::raw('MONTHNAME(sti.gi_date)'));
                        $join->whereYear('sti.gi_date', $year);
                    })
                    ->leftJoin('sales_tax_invoice_data as stid', 'sti.id', '=', 'stid.master_id')
                    ->groupBy('calendar.month_name')
                    ->orderBy(DB::raw("MONTH(STR_TO_DATE(calendar.month_name, '%M'))"))
                    ->select('calendar.month_name', DB::raw('IFNULL(SUM(stid.amount), 0) as total_amount'))
                    ->get();

                    return response()->json($data);
    }


    public static  function BankBalanceReport()
    {
        // return $data =   DB::Connection('mysql2')->table('accounts as a')
        // ->join('transactions as t', 't.acc_id', '=', 'a.id')
        // ->where('a.code', 'LIKE', '%1-2-8-%')
        // ->groupBy('a.id')
        // ->select('a.name', DB::raw('SUM(t.amount) as total_amount'))
        // ->get();

            $data = DB::Connection('mysql2')->table('accounts as a')
                    ->join('transactions as t', 't.acc_id', '=', 'a.id')
                    ->where('a.code', 'LIKE', '%1-2-8-%')
                    ->groupBy('a.name', 't.debit_credit')
                    ->select('a.name', 't.debit_credit', DB::raw('SUM(t.amount) as total_amount'))
                    ->get();

                    $result =DB::Connection('mysql2')->table('accounts as a')
         ->select('a.id','a.name','a.code',
         DB::raw('(SELECT SUM(CASE
                         WHEN b.debit_credit = 1 and status=1 THEN b.amount
                         WHEN b.debit_credit = 0 and status=1 THEN -b.amount
                         ELSE 0
                     END) FROM transactions as b
                     WHERE b.acc_id = a.id
                     and b.status=1) AS balance')
     )
         ->where('a.code', 'LIKE', '%1-2-8-%')
         ->where('a.status', 1)
         ->where('operational',1)
             ->orderBy('a.level1', 'ASC')
             ->orderBy('a.level2', 'ASC')
             ->orderBy('a.level3', 'ASC')
             ->orderBy('a.level4', 'ASC')
             ->orderBy('a.level5', 'ASC')
             ->orderBy('a.level6', 'ASC')
             ->orderBy('a.level7', 'ASC')
             ->groupBy('a.id')
             ->get();




            // $result = $data->groupBy('name')
            //     ->map(function ($group) {
            //         return $group->sum(function ($item) {
            //             return $item->debit_credit == 1 ? $item->total_amount : -$item->total_amount;
            //         });
            //     });

            return $result;

    }
    public static  function CustomerWiseSales()
    {
        $data = DB::Connection('mysql2')->table('sales_tax_invoice as sti')
            ->join('sales_tax_invoice_data as stid', 'sti.id', '=', 'stid.master_id')
            ->join('customers as c', 'c.id', '=', 'sti.buyers_id')
            ->groupBy('c.id')
            ->select('c.name', DB::raw('SUM(stid.amount) as amount'))
            ->get();

        return $data;
    }

    public static function getSaleSummaryAmount($monthStartDate, $monthEndDate)
    {
        $sale = DB::Connection('mysql2')->table('sales_order')
            ->where('sales_order.status', 1)
            ->whereBetween('sales_order.date', [$monthStartDate, $monthEndDate])
            ->select(DB::raw('sum(sales_order.total_amount_after_sale_tax) as sale_amount'))
            ->first();

        if ($sale) {
            return $sale->sale_amount;
        } else {
            return 0;
        }
    }

    public static function getCollectionSummaryAmount($startDate, $endDate)
    {
        return DB::connection('mysql2')->table('transactions')
            ->where('status', 1)
            ->whereBetween('date', [$startDate, $endDate])
            ->where(function ($query) {
                $query->where('voucher_no', 'like', 'brv%')
                    ->orWhere('voucher_no', 'like', 'crv%')
                    ->orWhere('voucher_no', 'like', 'ADV%');
            })
            ->where('debit_credit', 1)
            ->sum('amount');
    }

    public static function getTotalReceivablesAmount()
    {
        $debits = DB::connection('mysql2')->table('transactions')
            ->where('status', 1)
            ->where('acc_code', 'like', '1-%')
            ->where('debit_credit', 1)
            ->sum('amount');
        $credits = DB::connection('mysql2')->table('transactions')
            ->where('status', 1)
            ->where('acc_code', 'like', '1-%')
            ->where('debit_credit', 0)
            ->sum('amount');
        return $debits - $credits;
    }

    public static function getTotalPayablesAmount()
    {
        $credits = DB::connection('mysql2')->table('transactions')
            ->where('status', 1)
            ->where('acc_code', 'like', '2-%')
            ->where('debit_credit', 0)
            ->sum('amount');
        $debits = DB::connection('mysql2')->table('transactions')
            ->where('status', 1)
            ->where('acc_code', 'like', '2-%')
            ->where('debit_credit', 1)
            ->sum('amount');
        return $credits - $debits;
    }

    public static function getBankBalance()
    {
        $debits = DB::connection('mysql2')->table('transactions')
            ->where('status', 1)
            ->where(function ($query) {
                $query->where('acc_code', 'like', '1-1%')
                    ->orWhere('acc_code', 'like', '1-18%');
            })
            ->where('debit_credit', 1)
            ->sum('amount');
        $credits = DB::connection('mysql2')->table('transactions')
            ->where('status', 1)
            ->where(function ($query) {
                $query->where('acc_code', 'like', '1-1%')
                    ->orWhere('acc_code', 'like', '1-18%');
            })
            ->where('debit_credit', 0)
            ->sum('amount');
        return $debits - $credits;
    }

    public static function getMonthlySalesData()
    {
        $sales = [];
        $labels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $startDate = "$month-01";
            $endDate = date('Y-m-t', strtotime($startDate));
            $amount = self::getSaleSummaryAmount($startDate, $endDate);
            $sales[] = (float)$amount;
            $labels[] = date('M', strtotime($startDate));
        }
        return ['labels' => $labels, 'data' => $sales];
    }

    public static function getTopSellingProducts($limit = 5, $type = 'value', $period = 'month')
    {
        $query = DB::connection('mysql2')->table('sales_order_data')
            ->join('sales_order', 'sales_order_data.master_id', '=', 'sales_order.id')
            ->join('subitem', 'sales_order_data.item_id', '=', 'subitem.id')
            ->where('sales_order.status', 1);

        if ($period == 'today') {
            $query->whereDate('sales_order.date', date('Y-m-d'));
        } else {
            $query->whereBetween('sales_order.date', [date('Y-m-01'), date('Y-m-t')]);
        }

        $orderBy = $type == 'qty' ? 'total_qty' : 'total_sales';

        return $query->select(
                'subitem.product_name',
                'subitem.sku_code',
                DB::raw('SUM(sales_order_data.qty) as total_qty'),
                DB::raw('SUM(sales_order_data.qty * COALESCE(subitem.sale_price, 0)) as total_sales')
            )
            ->groupBy('subitem.id', 'subitem.product_name', 'subitem.sku_code')
            ->orderBy($orderBy, 'DESC')
            ->limit($limit)
            ->get();
    }

    public static function getApprovedInvoicesAmount($startDate, $endDate)
    {
        return DB::Connection('mysql2')->table('sales_order')
            ->where('status', 1)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('total_amount_after_sale_tax');
    }

    public static function getMonthlySalesValue($startDate, $endDate)
    {
        return DB::Connection('mysql2')->table('sales_order')
            ->where('status', 1)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('total_amount_after_sale_tax');
    }

    public static function getPendingSalesOrdersTotal()
    {
        return DB::Connection('mysql2')->table('sales_order')
            ->where('so_status', 0)
            ->sum('total_amount_after_sale_tax');
    }

    public static function getBATargetAchievementList()
    {
        $year = date('Y');
        $month = (int)date('m');

        $targets = DB::connection('mysql2')->table('target_items')
            ->where('year', $year)
            ->where('month', $month)
            ->select('employee_id', DB::raw('SUM(target) as total_target'))
            ->groupBy('employee_id')
            ->get()
            ->keyBy('employee_id');

        $actuals = DB::connection('mysql2')->table('retail_sale_orders as so')
            ->join('retail_sale_order_details as sod', 'so.id', '=', 'sod.retail_sale_order_id')
            ->whereYear('so.sale_order_date', $year)
            ->whereMonth('so.sale_order_date', $month)
            ->select('so.user_id', DB::raw('SUM(sod.qty) as total_actual'))
            ->groupBy('so.user_id')
            ->get()
            ->keyBy('user_id');

        $result = [];
        $employeeIds = $targets->keys()->merge($actuals->keys())->unique();
        $employees = DB::table('users')->whereIn('id', $employeeIds)->pluck('name', 'id');

        foreach ($employees as $id => $name) {
            $targetVal = $targets[$id]->total_target ?? 0;
            $actualVal = $actuals[$id]->total_actual ?? 0;
            $percentage = $targetVal > 0 ? ($actualVal / $targetVal) * 100 : 0;
            
            $result[] = (object)[
                'name' => $name,
                'target' => $targetVal,
                'actual' => $actualVal,
                'percentage' => round($percentage, 2)
            ];
        }

        usort($result, function($a, $b) {
            return $b->percentage <=> $a->percentage;
        });

        return $result;
    }

    public static function getPendingSalesOrdersForApproval()
    {
        $territory_ids = json_decode(auth()->user()->territory_id);
        if (!is_array($territory_ids)) {
            $territory_ids = [auth()->user()->territory_id];
        }

        return DB::Connection('mysql2')->table('sales_order')
            ->join('customers', 'sales_order.buyers_id', 'customers.id')
            ->whereIn('customers.territory_id', $territory_ids)
            ->where('sales_order.status', 1)
            ->where('sales_order.so_status', 0)
            ->select('sales_order.*', 'customers.name')
            ->orderBy('sales_order.id', 'DESC')->get();
    }
}
