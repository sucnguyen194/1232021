<?php namespace App\Http\Controllers\Admin;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\UserAgency;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {
	public function index(){

            check_admin_systems(SystemsModuleType::DASHBOARD);

            $star = now()->startOfMonth();
            $end = now()->endOfMonth();
            $month = CarbonPeriod::create($star,$end);
            $today = Carbon::today()->format('Y-m-d');
            $yesterday = Carbon::yesterday()->format('Y-m-d');

            $order = Order::selectRaw('DATE(created_at) as date, SUM(total) as total, SUM(revenue) as revenue')->whereBetween('created_at', [$star, $end])
                ->groupByRaw('date')
                ->get()->keyBy('date')->toArray();

            $revenues = [];
            $total_month = 0;
            $total_today = 0;
            $total_yesterday = 0;
            $revenues_month = 0;
            $revenues_today = 0;
            $revenues_yesterday = 0;
            foreach($month as $date){
                $day = $date->format('Y-m-d');
                $total[$day] = $order[$day]['total'] ?? 0;
                $revenues[$day] = $order[$day]['revenue'] ?? 0;
                $total_month += $order[$day]['total'] ?? 0;
                $revenues_month += $order[$day]['revenue'] ?? 0;
            }

            $total_today += $order[$today]['total'] ?? 0;
            $total_yesterday += $order[$yesterday]['total'] ?? 0;
            $revenues_yesterday += $order[$yesterday]['revenue'] ?? 0;
            $revenues_today += $order[$today]['revenue'] ?? 0;


            if($total_today >= 0 && $total_yesterday == 0){
                $percent = $total_today / ($total_yesterday+1) * 100;
            }elseif($total_today >=0 && $total_yesterday > 0){
                if($total_today > $total_yesterday){
                    $percent = $total_today / $total_yesterday * 100;
                }else{
                    $percent = - $total_today / $total_yesterday * 100;
                }
            }elseif($total_today >= 0 && $total_yesterday < 0){
                $percent = abs($total_yesterday) / $total_today * 100;
            }elseif ($total_today < 0 && $total_yesterday == 0){
                $percent = $total_today / ($total_yesterday +1) * 100;
            }elseif ($total_today < 0 && $total_yesterday < 0){
                if($total_today > $total_yesterday){
                    $percent = $total_today / $total_yesterday * 100;
                }else{
                    $percent = - $total_yesterday / $total_today * 100;
                }
            }else{
                $percent = - $total_yesterday / $total_today * 100;
            }

        if($revenues_today >= 0 && $revenues_yesterday == 0){
            $percent_revenues = $revenues_today / ($revenues_yesterday+1) * 100;
        }elseif($revenues_today >=0 && $revenues_yesterday > 0){
            if($revenues_today > $revenues_yesterday){
                $percent_revenues = $revenues_today /$revenues_yesterday * 100;
            }else{
                $percent_revenues = - $revenues_today / $revenues_yesterday * 100;
            }
        }elseif($revenues_today >= 0 && $revenues_yesterday < 0){
            $percent_revenues = abs($revenues_yesterday) / $revenues_today * 100;
        }elseif ($revenues_today < 0 && $revenues_yesterday == 0){
            $percent_revenues = $revenues_today / ($revenues_yesterday +1) * 100;
        }elseif ($revenues_today < 0 && $revenues_yesterday < 0){
            if($revenues_today > $revenues_yesterday){
                $percent_revenues = $revenues_today / $revenues_yesterday * 100;
            }else{
                $percent_revenues = - $revenues_yesterday / $revenues_today * 100;
            }
        }else{
            $percent_revenues = - $revenues_yesterday / $revenues_today * 100;
        }

            $data['revenues'] = $revenues;
            $data['total'] = $total;
            $data['total_month'] = $total_month;
            $data['revenues_month'] = $revenues_month;
            $data['total_today'] = $total_today;
            $data['revenues_today'] = $revenues_today;
            $data['total_yesterday'] = $total_yesterday;
            $data['percent'] = is_nan($percent) ? 0 : round($percent, 2);
            $data['percent_revenues'] = is_nan($percent_revenues) ? 0 : round($percent_revenues, 2);

            $data['orders'] = Order::get();

            $data['agency_debt'] = UserAgency::sum('debt');
            $data['user_debt'] = User::sum('debt');

	        return view('Admin.Dashboard.dashboard', $data);
	}

}