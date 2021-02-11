<?php

namespace Vanaheim\Core\Http\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Vanaheim\Core\Contracts\BuyableItem;

class IsBuyable implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attributeIndex
     * @param  mixed  $type
     * @return bool
     */
    public function passes($attributeIndex, $type)
    {
        try {
            $resolveAble = resolve($type) && app($type) instanceof BuyableItem;
        } catch (Exception $e) {
            return false;
        }

        return $resolveAble;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Type is not a valid known buyable object.';
    }
}
