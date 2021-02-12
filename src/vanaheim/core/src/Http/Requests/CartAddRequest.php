<?php declare(strict_types = 1);

namespace Vanaheim\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Vanaheim\Core\Http\Rules\IsBuyable;

class CartAddRequest extends FormRequest
{
    public function rules()
    {
        $isBuyable = new IsBuyable(
            $this->request->get('id'),
            $this->request->get('type')
        );

        return [
            'id' => ['required', $isBuyable],
            'type' => ['required', $isBuyable],
            'quantity' => [
                'sometimes',
                'integer',
                Rule::notIn([
                    0
                ])
            ],
        ];
    }
}
