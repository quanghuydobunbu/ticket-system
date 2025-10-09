<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
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
        
        // Thống kê tổng số sự kiện theo tháng
        $totalEvents = Event::select(
            DB::raw('MONTH(start_datetime) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('start_datetime', $currentYear)
        ->groupBy('month')
        ->pluck('count', 'month')
        ->toArray();

        // Thống kê sự kiện đã hoàn thành
        $completedEvents = Event::select(
            DB::raw('MONTH(start_datetime) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('status', 'completed')
        ->whereYear('start_datetime', $currentYear)
        ->groupBy('month')
        ->pluck('count', 'month')
        ->toArray();

        // Thống kê sự kiện bị hủy
        $cancelledEvents = Event::select(
            DB::raw('MONTH(start_datetime) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('status', 'cancelled')
        ->whereYear('start_datetime', $currentYear)
        ->groupBy('month')
        ->pluck('count', 'month')
        ->toArray();

        // Thống kê sự kiện đã publish (đang diễn ra hoặc sắp diễn ra)
        $publishedEvents = Event::select(
            DB::raw('MONTH(start_datetime) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('status', 'published')
        ->whereYear('start_datetime', $currentYear)
        ->groupBy('month')
        ->pluck('count', 'month')
        ->toArray();

        // Tạo mảng dữ liệu đầy đủ cho 12 tháng
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

    // API endpoint để lấy dữ liệu JSON (nếu cần)
    public function getStatisticsJson()
    {
        $statistics = $this->getEventStatistics();
        return response()->json($statistics);
    }

    public function getUserStatusStats()
    {
        // Đếm số người dùng active và inactive
        $activeUsers = User::where('is_active', '1')->count();
        $inactiveUsers = User::where('is_active', '0')->count();
        
        return [
            'active' => $activeUsers,
            'inactive' => $inactiveUsers,
            'total' => $activeUsers + $inactiveUsers
        ];
    }
}
