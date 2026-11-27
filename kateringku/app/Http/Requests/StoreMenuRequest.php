<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('kelola-menu') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('menus', 'name')],
            'category' => ['required', 'string', Rule::in(['makanan', 'minuman'])],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'min_order' => ['required', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => ':attribute wajib diisi.',
            'name.unique' => ':attribute sudah terdaftar, gunakan nama lain.',
            'category.required' => ':attribute wajib dipilih.',
            'category.in' => ':attribute harus berupa makanan atau minuman.',
            'price.required' => ':attribute wajib diisi.',
            'price.numeric' => ':attribute harus berupa angka.',
            'price.min' => ':attribute tidak boleh kurang dari 0.',
            'min_order.required' => ':attribute wajib diisi.',
            'min_order.integer' => ':attribute harus berupa bilangan bulat.',
            'min_order.min' => ':attribute minimal adalah 1 porsi.',
            'image.image' => ':attribute harus berupa file gambar.',
            'image.mimes' => ':attribute harus berformat jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran :attribute maksimal 2 MB.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nama menu',
            'category' => 'Kategori',
            'description' => 'Deskripsi',
            'price' => 'Harga',
            'min_order' => 'Minimal order',
            'image' => 'Foto menu',
            'is_active' => 'Status aktif',
        ];
    }
}
