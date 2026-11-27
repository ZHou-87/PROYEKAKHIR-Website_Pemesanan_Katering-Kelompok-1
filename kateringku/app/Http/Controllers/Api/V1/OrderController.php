<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\HandlesOrderLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    use HandlesOrderLogic;

    public function index(Request $request): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Order::class);

        $user = $request->user();
        $status = $request->query('status');
        $allowedStatuses = ['pending', 'waiting_validation', 'processed', 'completed', 'cancelled'];

        $query = $user->can('validasi-pesanan') && $request->boolean('all')
            ? Order::query()->with(['user', 'items.menu'])
            : $user->orders()->with(['items.menu']);

        $orders = $query
            ->when(
                in_array($status, $allowedStatuses, true),
                fn ($q) => $q->where('order_status', $status)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $orders->getCollection()->each(fn (Order $order) => $this->expireIfOverdue($order));

        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        Gate::authorize('create', Order::class);

        $order = $this->createOrderTransaction($request->user(), $request->validated());
        $order->load('items.menu');

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Order $order): OrderResource
    {
        Gate::authorize('view', $order);

        $this->expireIfOverdue($order);
        $order->load(['user', 'items.menu']);

        return new OrderResource($order);
    }

    public function pay(Order $order): OrderResource|JsonResponse
    {
        Gate::authorize('pay', $order);

        if ($this->expireIfOverdue($order)) {
            return response()->json([
                'message' => 'Waktu pembayaran 10 menit telah habis. Pesanan otomatis dibatalkan.',
                'order_status' => $order->order_status,
            ], 422);
        }

        if ($order->order_status !== 'pending') {
            return response()->json([
                'message' => 'Pesanan tidak berstatus pending.',
            ], 422);
        }

        $order->update(['order_status' => 'waiting_validation']);
        $order->load('items.menu');

        return new OrderResource($order);
    }

    public function cancel(Order $order): OrderResource|JsonResponse
    {
        Gate::authorize('cancel', $order);

        $this->expireIfOverdue($order);

        if (! in_array($order->order_status, ['pending', 'waiting_validation'], true)) {
            return response()->json([
                'message' => 'Pesanan dengan status saat ini tidak dapat dibatalkan.',
            ], 422);
        }

        $order->update(['order_status' => 'cancelled']);
        $order->load('items.menu');

        return new OrderResource($order);
    }

    public function updateStatus(Request $request, Order $order): OrderResource|JsonResponse
    {
        Gate::authorize('validate', $order);

        $this->expireIfOverdue($order);

        $validated = $request->validate([
            'order_status' => ['required', 'string', Rule::in(['processed', 'completed', 'cancelled'])],
        ]);

        $nextStatus = $validated['order_status'];
        $allowed = $this->allowedStatusTransitions()[$order->order_status] ?? [];

        if (! in_array($nextStatus, $allowed, true)) {
            return response()->json([
                'message' => "Perubahan status dari {$order->order_status} ke {$nextStatus} tidak diizinkan.",
            ], 422);
        }

        $order->update(['order_status' => $nextStatus]);
        $order->load(['user', 'items.menu']);

        return new OrderResource($order);
    }
}
