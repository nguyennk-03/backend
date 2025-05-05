<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\{Order, User, Product, Category, Brand, Discount, Review, Comment};
use App\Enums\OrderStatusEnum;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
        $this->middleware(function ($request, $next) {
            if (Auth::user()?->role !== 'admin') {
                return redirect()->route('dang-nhap')->withErrors(['role' => 'Bạn không có quyền truy cập!']);
            }
            return $next($request);
        });
    }

    public function index()
    {
        // Tổng hợp thông tin nhanh
        $totalOrders = Order::count();
        $totalPendingOrders = Order::where('status', OrderStatusEnum::PENDING)->count();
        $revenue = Order::where('status', OrderStatusEnum::DELIVERED)->sum('total_price');

        $counts = [
            'totalUsers' => User::count(),
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalBrands' => Brand::count(),
            'totalDiscounts' => Discount::count(),
            'totalReviews' => Review::count(),
            'totalRatings' => Review::sum('rating'),
            'totalComments' => Comment::count(),
        ];

        // ===== Doanh thu tuần trước =====
        $start = Carbon::now()->subWeek()->startOfWeek();
        $end = Carbon::now()->subWeek()->endOfWeek();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $weeklyRevenue = Order::selectRaw('DAYNAME(created_at) as day, SUM(total_price) as revenue')
            ->whereBetween('created_at', [$start, $end])
            ->where('status', OrderStatusEnum::DELIVERED)
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $weeklyOrders = Order::selectRaw('DAYNAME(created_at) as day, COUNT(*) as count')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $weeklyValues = [];
        $weeklyOrderData = [];

        foreach ($days as $day) {
            $weeklyValues[] = ($weeklyRevenue[$day]->revenue ?? 0);
            $weeklyOrderData[] = $weeklyOrders[$day]->count ?? 0;
        }

        // ===== Dữ liệu tháng =====
        $monthlyRevenue = Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_price) as revenue')
            ->where('status', OrderStatusEnum::DELIVERED)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $monthlyOrders = Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $months = collect($monthlyRevenue->keys())
            ->merge($monthlyOrders->keys())
            ->unique()
            ->sort()
            ->values();

        $comboRevenueData = [];
        $comboOrderData = [];

        foreach ($months as $month) {
            $comboRevenueData[] = ($monthlyRevenue[$month]->revenue ?? 0);
            $comboOrderData[] = $monthlyOrders[$month]->count ?? 0;
        }

        // ===== Dữ liệu năm =====
        $yearlyRevenue = Order::selectRaw('YEAR(created_at) as year, SUM(total_price) as revenue')
            ->where('status', OrderStatusEnum::DELIVERED)
            ->groupBy('year')
            ->get()
            ->keyBy('year');

        $yearlyOrders = Order::selectRaw('YEAR(created_at) as year, COUNT(*) as count')
            ->groupBy('year')
            ->get()
            ->keyBy('year');

        $startYear = Order::min(DB::raw('YEAR(created_at)')) ?? Carbon::now()->year;
        $years = range($startYear, Carbon::now()->year);
        $yearlyValues = [];
        $yearlyOrderData = [];

        foreach ($years as $year) {
            $yearlyValues[] = ($yearlyRevenue[$year]->revenue ?? 0);
            $yearlyOrderData[] = $yearlyOrders[$year]->count ?? 0;
        }

        // ===== Trạng thái đơn hàng =====
        $orderStatusRaw = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $orderStatusLabels = [];
        $orderStatusValues = [];

        foreach ($orderStatusRaw as $item) {
            $label = $item->status?->label() ?? 'Không xác định';
            $orderStatusLabels[] = $label;
            $orderStatusValues[] = $item->count;
        }

        // ===== Đơn hàng mới nhất =====
        $latestOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', array_merge([
            'totalOrders' => $totalOrders,
            'totalPendingOrders' => $totalPendingOrders,
            'revenue' => $revenue,
            'weeklyLabels' => $days,
            'weeklyValues' => $weeklyValues,
            'weeklyOrderData' => $weeklyOrderData,
            'comboLabels' => $months,
            'comboRevenueData' => $comboRevenueData,
            'comboOrderData' => $comboOrderData,
            'yearlyLabels' => $years,
            'yearlyValues' => $yearlyValues,
            'yearlyOrderData' => $yearlyOrderData,
            'orderStatusLabels' => $orderStatusLabels,
            'orderStatusValues' => $orderStatusValues,
            'latestOrders' => $latestOrders,
        ], $counts));
    }
}
