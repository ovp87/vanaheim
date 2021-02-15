<?php declare(strict_types = 1);

namespace Vanaheim\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Vanaheim\Core\Http\Rules\IsBuyable;

class CartUpdateRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'items' => 'required|array',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.type' => ['required', 'string'],
        ];

        foreach ($this->request->get('items') as $index => $value) {
            $rules['items.*.id'] = [new IsBuyable($value['id'], $value['type']), 'required', 'integer'];
        }

        return $rules;
    }

}
