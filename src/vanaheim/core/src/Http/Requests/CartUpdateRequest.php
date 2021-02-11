<?php declare(strict_types = 1);

namespace Vanaheim\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Vanaheim\Core\Http\Rules\IsBuyable;

class CartUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.type' => ['required', new IsBuyable],
        ];
    }

}
