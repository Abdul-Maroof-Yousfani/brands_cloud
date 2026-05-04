<?php

namespace App\Http\Controllers;

use App\BAFormation;
use App\BaTargets;
use App\Employees;
use App\Models\Attendance;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaAttendanceReportController extends Controller
{
    public function index()
    {
        $data['employees'] = Employees::whereIn('emp_id', BAFormation::pluck('employee_id')->unique())->get();
        return view('BA.Reports.attendance_report', $data);
    }

    public function generateReport(Request $request)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $employee_ids = $request->employee_ids;
        $targetType = $request->target_type ?? 'qty';

        $dates = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        $bas = Employees::whereIn('emp_id', BAFormation::pluck('employee_id')->unique())
            ->when(!empty($employee_ids), function($query) use ($employee_ids) {
                $query->whereIn('emp_id', $employee_ids);
            })
            ->get();

        $reportData = [];
        $client = new \GuzzleHttp\Client();

        $grandTotals = [
            'present' => 0,
            'absent' => 0,
            'target' => 0,
            'ach' => 0,
            'days' => []
        ];

        foreach ($dates as $dateStr) {
            $grandTotals['days'][$dateStr] = [
                'target' => 0,
                'ach' => 0
            ];
        }

        foreach ($bas as $ba) {
            $baData = [
                'emp_id' => $ba->emp_id,
                'name' => $ba->name,
                'mobile' => $ba->phone_no ?? 'N/A',
                'days' => []
            ];

            $formation = BAFormation::where('employee_id', $ba->emp_id)->first();
            $baData['customer'] = $formation->customer->name ?? 'N/A';
            $baData['city'] = 'N/A';
            $baData['zone'] = 'N/A';
            $baData['location'] = 'N/A';

            $apiAttendance = [];
            try {
                $response = $client->get("https://brands.smrsoftwares.com/api/viewAttendanceReport?emp_id={$ba->emp_id}&from_date={$startDate->format('Y-m-d')}&to_date={$endDate->format('Y-m-d')}");
                $resData = json_decode($response->getBody(), true);
                if (isset($resData['data'])) {
                    foreach ($resData['data'] as $att) {
                        $apiAttendance[$att['attendance_date']] = $att;
                    }
                }
            } catch (\Exception $e) {}

            $totalPresent = 0;
            $totalAbsent = 0;
            $totalTarget = 0;
            $totalAch = 0;

            foreach ($dates as $dateStr) {
                $dayData = [
                    'time_in' => '-',
                    'time_out' => '-',
                    'target' => 0,
                    'ach' => 0
                ];

                if (isset($apiAttendance[$dateStr])) {
                    $att = $apiAttendance[$dateStr];
                    $dayData['time_in'] = $att['clock_in'] ?? '-';
                    $dayData['time_out'] = $att['clock_out'] ?? '-';
                    
                    if (($att['clock_in'] && $att['clock_in'] != '-') || (isset($att['attendance_status']) && $att['attendance_status'] == 'P')) {
                        $totalPresent++;
                    } else {
                        $totalAbsent++;
                    }
                } else {
                    $totalAbsent++;
                }

                // Target from target_items
                $dt = Carbon::parse($dateStr);
                $dailyTarget = DB::connection('mysql2')->table('target_items')
                    ->where('employee_id', $ba->emp_id) // FIXED: Use emp_id
                    ->where('year', $dt->year)
                    ->where('month', (int)$dt->month)
                    ->where('target_type', $targetType)
                    ->sum('target');
                
                if ($dailyTarget > 0) {
                    $daysInMonth = $dt->daysInMonth;
                    $dayData['target'] = round($dailyTarget / $daysInMonth, 2);
                    $totalTarget += $dayData['target'];
                    $grandTotals['days'][$dateStr]['target'] += $dayData['target'];
                }

                // Achievement
                $user = User::where('emp_id', $ba->emp_id)->first();
                if ($user) {
                    $query = DB::connection('mysql2')->table('retail_sale_order_details')
                        ->join('retail_sale_orders', 'retail_sale_orders.id', '=', 'retail_sale_order_details.retail_sale_order_id')
                        ->where('retail_sale_orders.user_id', $user->id)
                        ->whereDate('retail_sale_orders.sale_order_date', $dateStr);
                    
                    if ($targetType == 'amount') {
                        $query->join('subitem', 'subitem.id', '=', 'retail_sale_order_details.product_id');
                        $ach = $query->sum(DB::raw('subitem.sale_price * retail_sale_order_details.qty'));
                    } else {
                        $ach = $query->sum('retail_sale_order_details.qty');
                    }
                    
                    $dayData['ach'] = $ach;
                    $totalAch += $ach;
                    $grandTotals['days'][$dateStr]['ach'] += $ach;
                }

                $baData['days'][$dateStr] = $dayData;
            }

            $baData['total_present'] = $totalPresent;
            $baData['total_absent'] = $totalAbsent;
            $baData['total_target'] = round($totalTarget, 2);
            $baData['total_ach'] = $totalAch;

            $grandTotals['present'] += $totalPresent;
            $grandTotals['absent'] += $totalAbsent;
            $grandTotals['target'] += $baData['total_target'];
            $grandTotals['ach'] += $totalAch;

            $reportData[] = $baData;
        }

        return view('BA.Reports.attendance_report_data', compact('reportData', 'dates', 'grandTotals'));
    }
}
