<?php

namespace App\Http\Requests;

use App\Models\Menu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $items = collect($this->input('items', []))
            ->filter(fn ($item) => isset($item['menu_id'], $item['quantity']) && (int) $item['quantity'] > 0)
            ->values()
            ->all();

        $merge = ['items' => $items];

        if ($this->input('delivery_method') === 'pickup') {
            $merge['distance_km'] = 0;
        }

        $this->merge($merge);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'delivery_method' => ['required', 'string', Rule::in(['delivery', 'pickup'])],
            'distance_km' => ['exclude_if:delivery_method,pickup', 'required_if:delivery_method,delivery', 'numeric', 'min:0.1', 'max:40'],
            'delivery_address' => ['required', 'string', 'max:1000'],
            'phone' => ['required', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'string', Rule::in(['transfer_bca', 'transfer_mandiri', 'qris', 'cash'])],
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['required', 'integer', 'exists:menus,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $items = $this->input('items', []);
            if (! is_array($items) || empty($items)) {
                return;
            }

            $menuIds = collect($items)->pluck('menu_id')->filter()->unique()->all();
            $menus = Menu::query()->whereIn('id', $menuIds)->get()->keyBy('id');

            foreach ($items as $index => $item) {
                $menu = $menus->get($item['menu_id'] ?? null);
                if (! $menu) {
                    continue;
                }

                if (! $menu->is_active) {
                    $validator->errors()->add(
                        "items.{$index}.menu_id",
                        "Menu {$menu->name} sedang tidak aktif."
                    );
                    continue;
                }

                $qty = (int) ($item['quantity'] ?? 0);
                if ($qty < $menu->min_order) {
                    $validator->errors()->add(
                        "items.{$index}.quantity",
                        "Minimal pemesanan untuk {$menu->name} adalah {$menu->min_order} porsi."
                    );
                }
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'event_date.required' => ':attribute wajib diisi.',
            'event_date.after_or_equal' => ':attribute tidak boleh sebelum hari ini.',
            'delivery_method.required' => ':attribute wajib dipilih.',
            'delivery_method.in' => ':attribute harus berupa delivery atau pickup.',
            'distance_km.required_if' => ':attribute wajib diisi untuk pengiriman delivery.',
            'distance_km.min' => ':attribute minimal 0.1 km.',
            'distance_km.max' => ':attribute maksimal 40 km.',
            'delivery_address.required' => ':attribute wajib diisi meskipun memilih pickup.',
            'phone.required' => ':attribute wajib diisi.',
            'payment_method.required' => ':attribute wajib dipilih.',
            'items.required' => 'Pilih minimal satu menu untuk dipesan.',
            'items.min' => 'Pilih minimal satu menu untuk dipesan.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'event_date' => 'Tanggal acara',
            'delivery_method' => 'Metode pengiriman',
            'distance_km' => 'Jarak pengiriman',
            'delivery_address' => 'Alamat lengkap',
            'phone' => 'Nomor telepon',
            'notes' => 'Catatan',
            'payment_method' => 'Metode pembayaran',
            'items' => 'Daftar menu',
        ];
    }
}
