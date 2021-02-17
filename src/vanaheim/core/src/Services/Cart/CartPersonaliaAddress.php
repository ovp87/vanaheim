<?php declare(strict_types=1);

namespace Vanaheim\Core\Services\Cart;

use Illuminate\Contracts\Support\Arrayable;

class CartPersonaliaAddress implements Arrayable
{
    protected string $country = '';
    protected string $city = '';
    protected string $zipCode = '';
    protected string $street = '';
    protected string $careOf = '';

    public function __construct(array $data)
    {
        $this->country = $data['country'] ?? '';
        $this->city = $data['city'] ?? '';
        $this->zipCode = $data['zipCode'] ?? '';
        $this->street = $data['street'] ?? '';
        $this->careOf = $data['careOf'] ?? '';
    }

    public function isComplete(): bool
    {
        return !empty($this->country)
            && !empty($this->city)
            && !empty($this->zipCode)
            && !empty($this->street);
    }

    public function toArray(): array
    {
        return [
            'country' => $this->country,
            'city' => $this->city,
            'zipCode' => $this->zipCode,
            'street' => $this->street,
            'careOf' => $this->careOf
        ];
    }
}
