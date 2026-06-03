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
        $data['brands'] = DB::connection('mysql2')->table('brands')->where('status', 1)->orderBy('name')->get();
        $data['zones'] = Employees::whereNotNull('zone')->where('zone', '!=', '')->distinct()->pluck('zone');
        return view('BA.Reports.attendance_report', $data);
    }

    public function generateReport(Request $request)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $employee_ids = $request->employee_ids;
        $brand_id = $request->brand_id;
        $targetType = $request->target_type ?? 'qty';
        $zone = $request->zone;

        $dates = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        $bas = Employees::whereIn('emp_id', function ($query) use ($brand_id) {
            $query->select('employee_id')
                ->from('b_a_formations')
                ->when(!empty($brand_id), function ($q) use ($brand_id) {
                    $q->whereJsonContains('brands_ids', (string) $brand_id);
                });
        })
            ->when(!empty($employee_ids), function ($query) use ($employee_ids) {
                $query->whereIn('emp_id', $employee_ids);
            })
            ->when(!empty($zone), function ($query) use ($zone) {
                $query->where('zone', $zone);
            })
            ->get()
            ->sortBy(function ($ba) {
                $formation = BAFormation::where('employee_id', $ba->emp_id)->first();
                if ($formation && $formation->brands_ids) {
                    $bIds = json_decode($formation->brands_ids, true);
                    if (is_array($bIds) && !empty($bIds)) {
                        $firstBrand = DB::connection('mysql2')->table('brands')->where('id', $bIds[0])->value('name');
                        return $firstBrand;
                    }
                }
                return 'zzz'; // BAs without brands at the end
            });

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

        // Prepare requests for all BAs
        $requests = [];
        foreach ($bas as $ba) {
            $requests[$ba->emp_id] = function () use ($client, $ba, $startDate, $endDate) {
                return $client->getAsync("https://brands.smrsoftwares.com/api/viewAttendanceReport?emp_id={$ba->emp_id}&from_date={$startDate->format('Y-m-d')}&to_date={$endDate->format('Y-m-d')}");
            };
        }

        $allAttendanceData = [];
        $pool = new \GuzzleHttp\Pool($client, $requests, [
            'concurrency' => 10,
            'fulfilled' => function ($response, $emp_id) use (&$allAttendanceData) {
                $resData = json_decode($response->getBody(), true);
                if (isset($resData['data'])) {
                    $allAttendanceData[$emp_id] = $resData['data'];
                }
            },
            'rejected' => function ($reason, $emp_id) {
                // Ignore failures for individual BAs
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();

        foreach ($bas as $ba) {
            $formation = BAFormation::where('employee_id', $ba->emp_id)->first();
            $brandIds = [];
            if ($formation && $formation->brands_ids) {
                $bIds = json_decode($formation->brands_ids, true);
                if (is_array($bIds)) {
                    $brandIds = $bIds;
                }
            }

            // If a specific brand is filtered, only process that brand
            if (!empty($brand_id)) {
                if (in_array((string) $brand_id, $brandIds)) {
                    $brandIds = [(string) $brand_id];
                } else {
                    $brandIds = []; // BA doesn't belong to filtered brand
                }
            }

            if (empty($brandIds))
                continue;

            // Filter brandIds to only those that have a target for this BA in this month/year
            $dt_context = Carbon::parse($dates[0]);
            $targetedBrandIds = DB::connection('mysql2')->table('target_items')
                ->where('employee_id', $ba->emp_id)
                ->whereIn('brand_id', $brandIds)
                ->where('year', $dt_context->year)
                ->where('month', (int) $dt_context->month)
                ->where('target_type', $targetType)
                ->where('target', '>', 0)
                ->pluck('brand_id')
                ->unique()
                ->toArray();

            $hasAttendance = false;
            if (isset($allAttendanceData[$ba->emp_id])) {
                foreach ($allAttendanceData[$ba->emp_id] as $att) {
                    if (($att['clock_in'] && $att['clock_in'] != '-') || (isset($att['attendance_status']) && $att['attendance_status'] == 'P')) {
                        $hasAttendance = true;
                        break;
                    }
                }
            }

            if (empty($targetedBrandIds) && !$hasAttendance)
                continue;

            if (!empty($targetedBrandIds)) {
                $brandNames = DB::connection('mysql2')->table('brands')->whereIn('id', $targetedBrandIds)->pluck('name')->toArray();
                $brandIds = $targetedBrandIds; // Only process targeted brands
            } else {
                $brandNames = DB::connection('mysql2')->table('brands')->whereIn('id', $brandIds)->pluck('name')->toArray();
            }


            $baData = [
                'emp_id' => $ba->emp_id,
                'name' => $ba->name,
                'brands' => implode(', ', $brandNames),
                'days' => []
            ];

            $baData['customer'] = $formation->customer->name ?? 'N/A';
            $baData['city'] = 'N/A';
            $baData['zone'] = $ba->zone ?? 'N/A';
            $baData['location'] = 'N/A';

            $apiAttendance = [];
            if (isset($allAttendanceData[$ba->emp_id])) {
                foreach ($allAttendanceData[$ba->emp_id] as $att) {
                    $apiAttendance[$att['attendance_date']] = $att;
                }
            }

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

                // Aggregate Targets and Achievements for ALL brands of this BA
                foreach ($brandIds as $bId) {
                    // Target
                    $dt = Carbon::parse($dateStr);
                    $monthlyTarget = DB::connection('mysql2')->table('target_items')
                        ->where('employee_id', $ba->emp_id)
                        ->where('brand_id', $bId)
                        ->where('year', $dt->year)
                        ->where('month', (int) $dt->month)
                        ->where('target_type', $targetType)
                        ->sum('target');

                    if ($monthlyTarget > 0) {
                        $daysInMonth = $dt->daysInMonth;
                        $dayData['target'] += round($monthlyTarget / $daysInMonth, 2);
                        // For the total summary, we'll sum these up outside this loop or handle carefully
                    }

                    // Achievement
                    $user = User::where('emp_id', $ba->emp_id)->first();
                    if ($user) {
                        $query = DB::connection('mysql2')->table('retail_sale_order_details')
                            ->join('retail_sale_orders', 'retail_sale_orders.id', '=', 'retail_sale_order_details.retail_sale_order_id')
                            ->where('retail_sale_orders.user_id', $user->id)
                            ->where('retail_sale_order_details.brand_id', $bId)
                            ->whereDate('retail_sale_orders.sale_order_date', $dateStr);

                        if ($targetType == 'amount') {
                            $query->join('subitem', 'subitem.id', '=', 'retail_sale_order_details.product_id');
                            $ach = $query->sum(DB::raw('subitem.sale_price * retail_sale_order_details.qty'));
                        } else {
                            $ach = $query->sum('retail_sale_order_details.qty');
                        }

                        $dayData['ach'] += $ach;
                    }
                }

                $totalAch += $dayData['ach'];
                $grandTotals['days'][$dateStr]['ach'] += $dayData['ach'];
                $grandTotals['days'][$dateStr]['target'] += $dayData['target'];

                $baData['days'][$dateStr] = $dayData;
            }

            // Calculate total target for the BA (sum of monthly targets for all brands)
            $dt = Carbon::parse($dates[0]); // Use first date for month/year context
            $totalMonthlyTarget = DB::connection('mysql2')->table('target_items')
                ->where('employee_id', $ba->emp_id)
                ->whereIn('brand_id', $brandIds)
                ->where('year', $dt->year)
                ->where('month', (int) $dt->month)
                ->where('target_type', $targetType)
                ->sum('target');

            $baData['total_present'] = $totalPresent;
            $baData['total_absent'] = $totalAbsent;
            $baData['total_target'] = round($totalMonthlyTarget, 2);
            $baData['total_ach'] = $totalAch;

            $grandTotals['present'] += $totalPresent;
            $grandTotals['absent'] += $totalAbsent;
            $grandTotals['target'] += $baData['total_target'];
            $grandTotals['ach'] += $totalAch;

            $reportData[] = $baData;
        }

        usort($reportData, function ($a, $b) {
            return strcmp($a['brands'], $b['brands']);
        });

        if ($request->export == 'excel') {
            $exportData = [];
            $headings = ['BA Code', 'BA Name', 'Zone', 'Customer', 'Brand(s)'];

            foreach ($dates as $date) {
                $d = \Carbon\Carbon::parse($date)->format('d M Y');
                $headings[] = $d . ' (In)';
                $headings[] = $d . ' (Out)';
                $headings[] = $d . ' (Tgt)';
                $headings[] = $d . ' (Ach)';
            }

            $headings[] = 'Total Pres';
            $headings[] = 'Total Abs';
            $headings[] = 'Total Tgt';
            $headings[] = 'Total Ach';

            foreach ($reportData as $ba) {
                $row = [
                    $ba['emp_id'],
                    $ba['name'],
                    $ba['zone'],
                    $ba['customer'],
                    $ba['brands']
                ];

                foreach ($dates as $date) {
                    $row[] = $ba['days'][$date]['time_in'];
                    $row[] = $ba['days'][$date]['time_out'];
                    $row[] = $ba['days'][$date]['target'];
                    $row[] = $ba['days'][$date]['ach'];
                }

                $row[] = $ba['total_present'];
                $row[] = $ba['total_absent'];
                $row[] = $ba['total_target'];
                $row[] = $ba['total_ach'];

                $exportData[] = $row;
            }

            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BAReportExport($exportData, $headings), 'BA_Attendance_Report.xlsx');
        }

        return view('BA.Reports.attendance_report_data', compact('reportData', 'dates', 'grandTotals'));
    }
}
