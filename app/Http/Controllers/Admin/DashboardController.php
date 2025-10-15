<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        if(!HasPermission(Auth::user(), 'admin.dashboard')) {
            abort(403);
        }
        $monthlySales = DB::table('ticket_types')
            ->selectRaw('
                MONTH(created_at) as month,
                SUM(quantity_sold) as total_sold,
                SUM(quantity_sold * price) as revenue,
                SUM(quantity_total - quantity_sold) as remaining
            ')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month'); 

        $chartData = [
            'sold' => [],
            'revenue' => [],
            'remaining' => []
        ];

        for ($i = 1; $i <= 12; $i++) {
            $monthData = $monthlySales->get($i);
            
            $chartData['sold'][] = $monthData ? (int)$monthData->total_sold : 0;
            $chartData['revenue'][] = $monthData ? (float)$monthData->revenue : 0;
            $chartData['remaining'][] = $monthData ? (int)$monthData->remaining : 0;
        }
        $statistics = $this->getEventStatistics();
        $userStats = $this->getUserStatusStats();
        return view('admin.dashboard')->with('chartData', $chartData)
        ->with('statistics', $statistics)
        ->with('userStats', $userStats);
    }

    public function getEventStatistics()
    {
        $currentYear = date('Y');
        
        $totalEvents = Event::select(
            DB::raw('MONTH(start_datetime) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('start_datetime', $currentYear)
        ->groupBy('month')
        ->pluck('count', 'month')
        ->toArray();

        $completedEvents = Event::select(
            DB::raw('MONTH(start_datetime) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('status', 'completed')
        ->whereYear('start_datetime', $currentYear)
        ->groupBy('month')
        ->pluck('count', 'month')
        ->toArray();

        $cancelledEvents = Event::select(
            DB::raw('MONTH(start_datetime) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('status', 'cancelled')
        ->whereYear('start_datetime', $currentYear)
        ->groupBy('month')
        ->pluck('count', 'month')
        ->toArray();

        $publishedEvents = Event::select(
            DB::raw('MONTH(start_datetime) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('status', 'published')
        ->whereYear('start_datetime', $currentYear)
        ->groupBy('month')
        ->pluck('count', 'month')
        ->toArray();

        $statistics = [
            'total' => [],
            'completed' => [],
            'cancelled' => [],
            'published' => []
        ];

        for ($month = 1; $month <= 12; $month++) {
            $statistics['total'][] = $totalEvents[$month] ?? 0;
            $statistics['completed'][] = $completedEvents[$month] ?? 0;
            $statistics['cancelled'][] = $cancelledEvents[$month] ?? 0;
            $statistics['published'][] = $publishedEvents[$month] ?? 0;
        }

        return $statistics;
    }

    public function getStatisticsJson()
    {
        $statistics = $this->getEventStatistics();
        return response()->json($statistics);
    }

    public function getUserStatusStats()
    {
        $activeUsers = User::where('is_active', '1')->count();
        $inactiveUsers = User::where('is_active', '0')->count();
        
        return [
            'active' => $activeUsers,
            'inactive' => $inactiveUsers,
            'total' => $activeUsers + $inactiveUsers
        ];
    }
}
