<?php declare(strict_types = 1);

namespace Vanaheim\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Vanaheim\Core\Http\Rules\IsBuyable;

class CartUpdateRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'items' => ['array'],
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.type' => ['required', 'string'],
            'personalia' => ['array'],
            'personalia.isCompany' => ['boolean'],
            'personalia.companyName' => ['string', 'required_if:personalia.isCompany, true'],
            'personalia.companyIdentifier' => ['string', 'required_if:personalia.isCompany, true'],
            'personalia.givenName' => ['string'],
            'personalia.surname' => ['string'],
            'personalia.phone' => ['string'],
            'personalia.email' => ['email'],
            'personalia.addresses' => ['array'],
            'personalia.addresses.delivery' => ['array'],
            'personalia.addresses.delivery.country' => ['string'],
            'personalia.addresses.delivery.city' => ['string'],
            'personalia.addresses.delivery.zipCode' => ['string'],
            'personalia.addresses.delivery.street' => ['string'],
            'personalia.addresses.delivery.careOf' => ['nullable', 'string'],
            'personalia.addresses.billing' => ['array'],
        ];

        foreach ($this->request->get('items', []) as $index => $value) {
            $rules['items.*.id'] = [new IsBuyable($value['id'], $value['type']), 'required', 'integer'];
        }

        return $rules;
    }

}
