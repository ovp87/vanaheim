<?php

namespace Vanaheim\Core\Http\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Vanaheim\Core\Contracts\BuyableItem;
use Vanaheim\Core\Models\Buyable;

class IsBuyable implements Rule
{

    private int $id;
    private string $type;

    public function __construct($id, $type)
    {
        $this->id = $id;
        $this->type = $type;
    }

    public function passes($attribute, $value): bool
    {
        try {
            $resolveAbleType = resolve($this->type) && app($this->type) instanceof BuyableItem;
        } catch (Exception $e) {
            report($e);
            return false;
        }

        $modelExists = Buyable::where('buyable_type', $this->type)
            ->where('buyable_id', $this->id)
            ->exists();

        return $resolveAbleType && $modelExists;
    }

    public function message(): string
    {
        return 'Type and Id is not a valid buyable object.';
    }
}
