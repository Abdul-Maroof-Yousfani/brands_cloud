<?php

namespace App\Http\Controllers;

use App\BAFormation;
use App\BAStock;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\RetailSaleOrder;
use App\Models\RetailSaleOrderDetail;
use App\RetailSaleOrderReturn;
use App\RetailSaleOrderReturnDetail;
use App\SurveyForm;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BAReportingController extends Controller
{
    public function baSalesReport()
    {
        $data['employees'] = User::where('acc_type', 'ba')->get();
        $data['customers'] = Customer::where('status', 1)->get();
        $data['report_title'] = 'BA Sales Report';
        return view('BA.Reports.sales_report', $data);
    }

    public function listBaSalesReport(Request $request)
    {
        $query = RetailSaleOrder::with(['distributor', 'details.product', 'details.brand', 'user']);

        if ($request->employee_id) {
            $query->where('retail_sale_orders.user_id', $request->employee_id);
        }

        if ($request->customer_id) {
            $query->where('retail_sale_orders.distributor_id', $request->customer_id);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('retail_sale_orders.sale_order_date', [$request->from_date, $request->to_date]);
        }

        $sales = $query->orderBy('retail_sale_orders.sale_order_date', 'desc')->get();

        if ($request->export == 'excel') {
            $exportData = [];
            foreach ($sales as $sale) {
                foreach ($sale->details as $detail) {
                    $exportData[] = [
                        'Date' => $sale->sale_order_date,
                        'BA Name' => $sale->user->name ?? 'N/A',
                        'Store' => $sale->distributor->name ?? 'N/A',
                        'Product' => $detail->product->product_name ?? 'N/A',
                        'Brand' => $detail->brand->name ?? 'N/A',
                        'Qty' => $detail->qty
                    ];
                }
            }
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BAReportExport($exportData, ['Date', 'BA Name', 'Store', 'Product', 'Brand', 'Qty']), 'BA_Sales_Report.xlsx');
        }

        $data['sales'] = $sales;
        return view('BA.Reports.AjaxPages.sales_report_ajax', $data);
    }

    public function stockAdjustmentReport()
    {
        $data['employees'] = User::where('acc_type', 'ba')->get();
        $data['customers'] = Customer::where('status', 1)->get();
        $data['report_title'] = 'BA Store Stock Adjustment Report';
        return view('BA.Reports.stock_adjustment_report', $data);
    }

    public function listStockAdjustmentReport(Request $request)
    {
        $query = \App\BAStock::with(['user', 'customer', 'product'])
            ->whereIn('voucher_type', [1, 2]);

        if ($request->employee_id) {
            $query->where('user_id', $request->employee_id);
        }

        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('voucher_date', [$request->from_date, $request->to_date]);
        }

        $adjustments = $query->orderBy('voucher_date', 'desc')->get();

        if ($request->export == 'excel') {
            $exportData = [];
            foreach ($adjustments as $adj) {
                $exportData[] = [
                    'Date' => $adj->voucher_date,
                    'Voucher #' => $adj->voucher_no,
                    'BA Name' => $adj->user->name ?? 'N/A',
                    'Store' => $adj->customer->name ?? 'N/A',
                    'Product' => $adj->product->product_name ?? 'N/A',
                    'Qty' => $adj->qty,
                    'Type' => $adj->voucher_type == 1 ? 'Gain' : 'Loss'
                ];
            }
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BAReportExport($exportData, ['Date', 'Voucher #', 'BA Name', 'Store', 'Product', 'Qty', 'Type']), 'BA_Stock_Adjustment_Report.xlsx');
        }

        $data['adjustments'] = $adjustments;
        return view('BA.Reports.AjaxPages.stock_adjustment_report_ajax', $data);
    }

    public function returnStockReport()
    {
        $data['employees'] = User::where('acc_type', 'ba')->get();
        $data['customers'] = Customer::where('status', 1)->get();
        $data['report_title'] = 'BA Store Return Stock Report';
        return view('BA.Reports.return_stock_report', $data);
    }

    public function listReturnStockReport(Request $request)
    {
        $query = RetailSaleOrderReturn::with(['distributor', 'details.product', 'details.brand', 'user']);

        if ($request->employee_id) {
            $query->where('retail_sale_order_returns.user_id', $request->employee_id);
        }

        if ($request->customer_id) {
            $query->where('retail_sale_order_returns.distributor_id', $request->customer_id);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween(DB::raw('DATE(retail_sale_order_returns.created_at)'), [$request->from_date, $request->to_date]);
        }

        $returns = $query->orderBy('retail_sale_order_returns.return_date', 'desc')->get();

        if ($request->export == 'excel') {
            $exportData = [];
            foreach ($returns as $ret) {
                foreach ($ret->details as $detail) {
                    $exportData[] = [
                        'Date' => $ret->return_date,
                        'BA Name' => $ret->user->name ?? 'N/A',
                        'Store' => $ret->distributor->name ?? 'N/A',
                        'Product' => $detail->product->product_name ?? 'N/A',
                        'Brand' => $detail->brand->name ?? 'N/A',
                        'Qty' => $detail->quantity
                    ];
                }
            }
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BAReportExport($exportData, ['Date', 'BA Name', 'Store', 'Product', 'Brand', 'Qty']), 'BA_Return_Stock_Report.xlsx');
        }

        $data['returns'] = $returns;
        return view('BA.Reports.AjaxPages.return_stock_report_ajax', $data);
    }

    public function surveyReport()
    {
        $data['employees'] = User::where('acc_type', 'ba')->get();
        $data['customers'] = Customer::where('status', 1)->get();
        $data['report_title'] = 'BA Customer Survey Report';
        return view('BA.Reports.survey_report', $data);
    }

    public function listSurveyReport(Request $request)
    {
        $query = SurveyForm::with(['distributor', 'product', 'currentlyUsingBrand', 'currentlyUsingBrand2', 'user']);

        if ($request->employee_id) {
            $query->where('surveyform.user_id', $request->employee_id);
        }

        if ($request->customer_id) {
            $query->where('surveyform.distributor_id', $request->customer_id);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween(DB::raw('DATE(surveyform.created_at)'), [$request->from_date, $request->to_date]);
        }

        $surveys = $query->orderBy('surveyform.created_at', 'desc')->get();

        if ($request->export == 'excel') {
            $exportData = [];
            foreach ($surveys as $sv) {
                $exportData[] = [
                    'Date' => $sv->created_at,
                    'BA Name' => $sv->user->name ?? 'N/A',
                    'Customer' => $sv->customer_name,
                    'Contact' => $sv->contact,
                    'Current Brand 1' => $sv->currentlyUsingBrand->name ?? 'N/A',
                    'Current Brand 2' => $sv->currentlyUsingBrand2->name ?? 'N/A',
                    'Recommended' => $sv->product->product_name ?? 'N/A',
                    'Remarks' => $sv->remarks
                ];
            }
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BAReportExport($exportData, ['Date', 'BA Name', 'Customer', 'Contact', 'Current Brand 1', 'Current Brand 2', 'Recommended', 'Remarks']), 'BA_Survey_Report.xlsx');
        }

        $data['surveys'] = $surveys;
        return view('BA.Reports.AjaxPages.survey_report_ajax', $data);
    }
}
