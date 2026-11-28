<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard ringkasan pesanan untuk role customer.
     */
    public function customer(Request $request): View
    {
        $user = $request->user();

        $statusCounts = Order::query()
            ->where('user_id', $user->id)
            ->selectRaw('order_status, COUNT(*) as total')
            ->groupBy('order_status')
            ->pluck('total', 'order_status');

        $counts = [
            'pending' => (int) ($statusCounts['pending'] ?? 0),
            'waiting_validation' => (int) ($statusCounts['waiting_validation'] ?? 0),
            'processed' => (int) ($statusCounts['processed'] ?? 0),
            'completed' => (int) ($statusCounts['completed'] ?? 0),
        ];

        $recentOrders = Order::with('orderItems.menu')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact('counts', 'recentOrders'));
    }

    /**
     * Dashboard operasional untuk role admin.
     */
    public function admin(): View
    {
        $waitingValidationCount = Order::query()
            ->where('order_status', 'waiting_validation')
            ->count();

        $processedCount = Order::query()
            ->where('order_status', 'processed')
            ->count();

        $activeMenusCount = Menu::query()
            ->where('is_active', true)
            ->count();

        $recentOrders = Order::with(['user', 'orderItems.menu'])
            ->whereIn('order_status', ['waiting_validation', 'processed'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'waitingValidationCount',
            'processedCount',
            'activeMenusCount',
            'recentOrders'
        ));
    }

    /**
     * Dashboard ringkasan bisnis keseluruhan untuk role owner.
     */
    public function owner(): View
    {
        $totalOmzet = (float) Order::query()
            ->where('order_status', 'completed')
            ->sum('total_price');

        $completedOrdersCount = Order::query()
            ->where('order_status', 'completed')
            ->count();

        $totalMenusCount = Menu::query()->count();

        $totalCustomersCount = User::role('customer')->count();

        return view('owner.dashboard', compact(
            'totalOmzet',
            'completedOrdersCount',
            'totalMenusCount',
            'totalCustomersCount'
        ));
    }
}
