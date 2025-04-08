<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Discount;
use App\Models\Review;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enums\OrderStatusEnum;

class AdminController extends Controller
{
    /**
     * Constructor to apply middleware for authentication and role checking.
     */
    public function __construct()
    {
        $this->middleware('auth:web');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                return redirect()->route('dang-nhap')->withErrors(['role' => 'Bạn không có quyền truy cập!']);
            }
            return $next($request);
        });
    }

    /**
     * Display the admin dashboard with statistics, charts, and latest orders.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $totalOrders = Order::count();
        $totalPendingOrders = Order::where('status', OrderStatusEnum::PENDING->value)->count();
        $revenue = Order::where('status', OrderStatusEnum::COMPLETED->value)->sum('total_price');

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

        // Dữ liệu doanh thu theo tuần (tuần trước)
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();

        $weeklyRevenue = Order::selectRaw('DAYNAME(created_at) as day, SUM(total_price) as revenue')
            ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
            ->where('status', OrderStatusEnum::COMPLETED->value)
            ->groupBy('day')
            ->orderByRaw("FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->get();

        $weeklyOrders = Order::selectRaw('DAYNAME(created_at) as day, COUNT(*) as count')
            ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
            ->groupBy('day')
            ->orderByRaw("FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->get();

        $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $weeklyLabels = $allDays;
        $weeklyValues = array_fill(0, 7, 0);
        $weeklyOrderData = array_fill(0, 7, 0);

        foreach ($weeklyRevenue as $data) {
            $dayIndex = array_search($data->day, $allDays);
            if ($dayIndex !== false) {
                $weeklyValues[$dayIndex] = $data->revenue;
            }
        }

        foreach ($weeklyOrders as $data) {
            $dayIndex = array_search($data->day, $allDays);
            if ($dayIndex !== false) {
                $weeklyOrderData[$dayIndex] = $data->count;
            }
        }

        // Dữ liệu doanh thu theo tháng
        $monthlyRevenue = Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_price) as revenue')
            ->where('status', OrderStatusEnum::COMPLETED->value)
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        // Dữ liệu số đơn hàng theo tháng
        $monthlyOrders = Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        // Lấy danh sách các tháng có dữ liệu (gộp từ cả doanh thu và số đơn hàng)
        $revenueMonths = $monthlyRevenue->pluck('month')->toArray();
        $orderMonths = $monthlyOrders->pluck('month')->toArray();
        $allMonthsWithData = array_unique(array_merge($revenueMonths, $orderMonths));
        usort($allMonthsWithData, function ($a, $b) {
            return strtotime($a) - strtotime($b);
        });

        // Chuẩn bị dữ liệu cho biểu đồ (chỉ lấy các tháng có dữ liệu)
        $comboLabels = $allMonthsWithData;
        $comboRevenueData = array_fill(0, count($allMonthsWithData), 0);
        $comboOrderData = array_fill(0, count($allMonthsWithData), 0);

        foreach ($monthlyRevenue as $data) {
            $monthIndex = array_search($data->month, $allMonthsWithData);
            if ($monthIndex !== false) {
                $comboRevenueData[$monthIndex] = $data->revenue;
            }
        }

        foreach ($monthlyOrders as $data) {
            $monthIndex = array_search($data->month, $allMonthsWithData);
            if ($monthIndex !== false) {
                $comboOrderData[$monthIndex] = $data->count;
            }
        }

        // Dữ liệu doanh thu theo năm
        $yearlyRevenue = Order::selectRaw('YEAR(created_at) as year, SUM(total_price) as revenue')
            ->where('status', OrderStatusEnum::COMPLETED->value)
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        $yearlyOrders = Order::selectRaw('YEAR(created_at) as year, COUNT(*) as count')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        $firstOrderYear = Order::min(DB::raw('YEAR(created_at)'));
        $currentYear = Carbon::now()->year;
        $firstYear = $firstOrderYear ? $firstOrderYear : $currentYear;
        $allYears = range($firstYear, $currentYear);

        $yearlyLabels = $allYears;
        $yearlyValues = array_fill(0, count($allYears), 0);
        $yearlyOrderData = array_fill(0, count($allYears), 0);

        foreach ($yearlyRevenue as $data) {
            $yearIndex = array_search($data->year, $allYears);
            if ($yearIndex !== false) {
                $yearlyValues[$yearIndex] = $data->revenue;
            }
        }

        foreach ($yearlyOrders as $data) {
            $yearIndex = array_search($data->year, $allYears);
            if ($yearIndex !== false) {
                $yearlyOrderData[$yearIndex] = $data->count;
            }
        }

        $orderStatusData = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $orderStatusLabels = $orderStatusData->pluck('status')->map(function ($status) {
            return $status->label();
        })->toArray();
        $orderStatusValues = $orderStatusData->pluck('count')->toArray();

        $latestOrders = Order::with('user')->orderBy('id', 'desc')->take(5)->get();

        return view('admin.dashboard', array_merge([
            'totalOrders' => $totalOrders,
            'totalPendingOrders' => $totalPendingOrders,
            'revenue' => $revenue,
            'weeklyLabels' => $weeklyLabels,
            'weeklyValues' => $weeklyValues,
            'weeklyOrderData' => $weeklyOrderData,
            'comboLabels' => $comboLabels,
            'comboRevenueData' => $comboRevenueData,
            'comboOrderData' => $comboOrderData,
            'yearlyLabels' => $yearlyLabels,
            'yearlyValues' => $yearlyValues,
            'yearlyOrderData' => $yearlyOrderData,
            'orderStatusLabels' => $orderStatusLabels,
            'orderStatusValues' => $orderStatusValues,
            'latestOrders' => $latestOrders,
        ], $counts));
    }
}