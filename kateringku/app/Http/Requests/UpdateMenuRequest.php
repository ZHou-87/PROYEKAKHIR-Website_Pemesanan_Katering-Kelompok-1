<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateMenuRequest extends StoreMenuRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['name'] = [
            'required',
            'string',
            'max:255',
            Rule::unique('menus', 'name')->ignore($this->route('menu')),
        ];

        return $rules;
    }
}
