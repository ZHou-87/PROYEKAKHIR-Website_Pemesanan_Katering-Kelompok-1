<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait HandlesOrderLogic
{
    /**
     * Hitung ongkos kirim: pickup = 0, delivery = kelipatan 5 km ke atas * Rp 15.000.
     */
    protected function calculateDeliveryFee(string $deliveryMethod, float $distanceKm): int
    {
        if ($deliveryMethod === 'pickup') {
            return 0;
        }

        return (int) (ceil($distanceKm / 5) * 15000);
    }

    /**
     * Buat pesanan beserta itemnya di dalam transaksi database.
     *
     * @param  array<string, mixed>  $validated
     */
    protected function createOrderTransaction(User $user, array $validated): Order
    {
        return DB::transaction(function () use ($user, $validated): Order {
            $deliveryMethod = $validated['delivery_method'];
            $distanceKm = $deliveryMethod === 'pickup'
                ? 0.0
                : (float) ($validated['distance_km'] ?? 0);

            $deliveryFee = $this->calculateDeliveryFee($deliveryMethod, $distanceKm);

            $menuIds = collect($validated['items'])->pluck('menu_id')->unique()->all();
            $menus = Menu::query()->whereIn('id', $menuIds)->get()->keyBy('id');

            $subtotalItems = 0;
            $preparedItems = [];

            foreach ($validated['items'] as $item) {
                /** @var Menu $menu */
                $menu = $menus->get($item['menu_id']);
                $quantity = (int) $item['quantity'];
                $pricePerPortion = (float) $menu->price;

                $subtotalItems += $pricePerPortion * $quantity;
                $preparedItems[] = [
                    'menu_id' => $menu->id,
                    'quantity' => $quantity,
                    'price_per_portion' => $pricePerPortion,
                ];
            }

            $totalPrice = $subtotalItems + $deliveryFee;

            $order = new Order([
                'order_code' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                'event_date' => $validated['event_date'],
                'delivery_method' => $deliveryMethod,
                'distance_km' => $distanceKm,
                'delivery_fee' => $deliveryFee,
                'delivery_address' => $validated['delivery_address'],
                'phone' => $validated['phone'],
                'notes' => $validated['notes'] ?? null,
                'total_price' => $totalPrice,
                'payment_method' => $validated['payment_method'],
                'payment_expired_at' => now()->addMinutes(10),
                'order_status' => 'pending',
            ]);

            // Wajib set user_id secara eksplisit untuk mencegah mass-assignment IDOR.
            $order->user_id = $user->id;
            $order->save();

            $order->items()->createMany($preparedItems);

            return $order;
        });
    }

    /**
     * Batalkan pesanan secara otomatis jika batas waktu 10 menit telah lewat dan status masih pending.
     */
    protected function expireIfOverdue(Order $order): bool
    {
        if (
            $order->order_status === 'pending'
            && $order->payment_expired_at !== null
            && now()->greaterThan($order->payment_expired_at)
        ) {
            $order->update(['order_status' => 'cancelled']);

            return true;
        }

        return false;
    }

    /**
     * Daftar transisi status yang diizinkan untuk Admin/Owner.
     *
     * @return array<string, list<string>>
     */
    protected function allowedStatusTransitions(): array
    {
        return [
            'pending' => ['cancelled'],
            'waiting_validation' => ['processed', 'cancelled'],
            'processed' => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => [],
        ];
    }
}
