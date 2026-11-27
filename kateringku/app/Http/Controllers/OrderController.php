<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesOrderLogic;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    use HandlesOrderLogic;

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Order::class);

        $orders = $request->user()
            ->orders()
            ->with('items.menu')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $orders->getCollection()->each(fn (Order $order) => $this->expireIfOverdue($order));

        return view('orders.index', compact('orders'));
    }

    public function create(Request $request): View
    {
        Gate::authorize('create', Order::class);

        $menus = Menu::query()
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $selectedMenuId = (int) $request->query('menu_id', 0);

        return view('orders.create', compact('menus', 'selectedMenuId'));
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        Gate::authorize('create', Order::class);

        $order = $this->createOrderTransaction($request->user(), $request->validated());

        return redirect()
            ->route('customer.orders.pay', $order)
            ->with('success', 'Pesanan berhasil dibuat. Selesaikan pembayaran dalam 10 menit.');
    }

    public function pay(Order $order): View|RedirectResponse
    {
        Gate::authorize('pay', $order);

        $this->expireIfOverdue($order);
        $order->load('items.menu');

        return view('orders.pay', compact('order'));
    }

    public function confirmPayment(Order $order): RedirectResponse
    {
        Gate::authorize('pay', $order);

        if ($this->expireIfOverdue($order)) {
            return redirect()
                ->route('customer.orders.pay', $order)
                ->with('error', 'Waktu pembayaran 10 menit telah habis. Pesanan otomatis dibatalkan.');
        }

        if ($order->order_status !== 'pending') {
            return redirect()
                ->route('customer.orders.index')
                ->with('error', 'Pesanan ini sudah diproses atau tidak berstatus pending.');
        }

        $order->update(['order_status' => 'waiting_validation']);

        return redirect()
            ->route('customer.orders.index')
            ->with('success', 'Pembayaran berhasil dikonfirmasi dan sedang menunggu validasi admin.');
    }

    public function cancel(Order $order): RedirectResponse
    {
        Gate::authorize('cancel', $order);

        $this->expireIfOverdue($order);

        if (! in_array($order->order_status, ['pending', 'waiting_validation'], true)) {
            return redirect()
                ->route('customer.orders.index')
                ->with('error', 'Pesanan dengan status saat ini tidak dapat dibatalkan.');
        }

        $order->update(['order_status' => 'cancelled']);

        return redirect()
            ->route('customer.orders.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function adminIndex(Request $request): View
    {
        Gate::authorize('validasi-pesanan');

        $status = $request->query('status');
        $allowedStatuses = ['pending', 'waiting_validation', 'processed', 'completed', 'cancelled'];

        $orders = Order::query()
            ->with(['user', 'items.menu'])
            ->when(
                in_array($status, $allowedStatuses, true),
                fn ($query) => $query->where('order_status', $status)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $orders->getCollection()->each(fn (Order $order) => $this->expireIfOverdue($order));

        return view('orders.admin-index', compact('orders', 'status', 'allowedStatuses'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        Gate::authorize('validate', $order);

        $this->expireIfOverdue($order);

        $validated = $request->validate([
            'order_status' => ['required', 'string', Rule::in(['processed', 'completed', 'cancelled'])],
        ]);

        $nextStatus = $validated['order_status'];
        $allowed = $this->allowedStatusTransitions()[$order->order_status] ?? [];

        if (! in_array($nextStatus, $allowed, true)) {
            return redirect()
                ->back()
                ->with('error', "Perubahan status dari {$order->order_status} ke {$nextStatus} tidak diizinkan.");
        }

        $order->update(['order_status' => $nextStatus]);

        return redirect()
            ->back()
            ->with('success', "Status pesanan {$order->order_code} diperbarui menjadi {$nextStatus}.");
    }
}
