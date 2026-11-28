<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman laporan pemasukan owner (status completed).
     */
    public function index(Request $request): View
    {
        $filters = $this->resolveFilters($request);

        $orders = $this->baseReportQuery($filters)
            ->orderBy('orders.' . $filters['sort_by'], $filters['sort_dir'])
            ->paginate(10)
            ->withQueryString();

        $totalRevenue = $this->calculateTotalRevenue($filters);

        return view('owner.reports.index', [
            'orders' => $orders,
            'totalRevenue' => $totalRevenue,
            'filters' => $filters,
        ]);
    }

    /**
     * Tampilkan halaman khusus cetak / export PDF laporan pemasukan owner.
     */
    public function print(Request $request): View
    {
        $filters = $this->resolveFilters($request);

        $orders = $this->baseReportQuery($filters)
            ->orderBy('orders.' . $filters['sort_by'], $filters['sort_dir'])
            ->get();

        $totalRevenue = $this->calculateTotalRevenue($filters);

        return view('owner.reports.print', [
            'orders' => $orders,
            'totalRevenue' => $totalRevenue,
            'filters' => $filters,
        ]);
    }

    /**
     * Validasi filter tanggal dan whitelist kolom pengurutan untuk mencegah SQL Injection.
     */
    private function resolveFilters(Request $request): array
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'sort_by' => ['nullable', 'string'],
            'sort_dir' => ['nullable', 'string'],
        ]);

        $allowedSorts = ['event_date', 'total_price', 'created_at'];
        $sortBy = $validated['sort_by'] ?? 'event_date';
        if (! in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'event_date';
        }

        $sortDir = strtolower((string) ($validated['sort_dir'] ?? 'desc')) === 'asc' ? 'asc' : 'desc';

        return [
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'sort_by' => $sortBy,
            'sort_dir' => $sortDir,
        ];
    }

    /**
     * Query Builder laporan transaksi completed yang kompatibel dengan PostgreSQL.
     */
    private function baseReportQuery(array $filters): Builder
    {
        return DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.order_status', 'completed')
            ->when($filters['start_date'], function (Builder $query, string $startDate) {
                $query->whereDate('orders.event_date', '>=', $startDate);
            })
            ->when($filters['end_date'], function (Builder $query, string $endDate) {
                $query->whereDate('orders.event_date', '<=', $endDate);
            })
            ->select(
                'orders.id',
                'orders.order_code',
                'orders.event_date',
                'orders.delivery_method',
                'orders.delivery_fee',
                'orders.total_price',
                'orders.payment_method',
                'orders.created_at',
                'users.name as customer_name',
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_portions')
            )
            ->groupBy(
                'orders.id',
                'orders.order_code',
                'orders.event_date',
                'orders.delivery_method',
                'orders.delivery_fee',
                'orders.total_price',
                'orders.payment_method',
                'orders.created_at',
                'users.name'
            );
    }

    /**
     * Hitung total pendapatan dari pesanan completed sesuai filter tanggal.
     */
    private function calculateTotalRevenue(array $filters): float
    {
        return (float) DB::table('orders')
            ->where('order_status', 'completed')
            ->when($filters['start_date'], function (Builder $query, string $startDate) {
                $query->whereDate('event_date', '>=', $startDate);
            })
            ->when($filters['end_date'], function (Builder $query, string $endDate) {
                $query->whereDate('event_date', '<=', $endDate);
            })
            ->sum('total_price');
    }
}
