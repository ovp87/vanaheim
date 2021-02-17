<?php declare(strict_types=1);

namespace Vanaheim\Core\Services\Cart;

use Illuminate\Contracts\Support\Arrayable;

class CartPersonalia implements Arrayable
{
    protected bool $isCompany = false;
    protected string $companyName = '';
    protected string $companyIdentifier = '';
    protected string $givenName = '';
    protected string $surname = '';
    protected string $phone = '';
    protected string $email = '';
    protected string $source = '';

    protected CartPersonaliaAddress $deliveryAddress;
    protected CartPersonaliaAddress | null $billingAddress;

    public function __construct(array $data)
    {
        $this->isCompany = $data['isCompany'] ?? false;
        $this->companyName = $data['companyName'] ?? '';
        $this->companyIdentifier = $data['companyIdentifier'] ?? '';
        $this->givenName = $data['givenName'] ?? '';
        $this->surname = $data['surname'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->source = $data['source'] ?? '';

        $this->deliveryAddress = new CartPersonaliaAddress(
            $data['addresses']['delivery'] ?? []
        );

        if (isset($data['addresses']['billing'])) {
            $this->billingAddress = new CartPersonaliaAddress(
                $data['addresses']['billing'] ?? []
            );
        }
    }

    public function isComplete(): bool
    {
        $businessDataIsComplete = !$this->isCompany
            ? true
            : !empty($this->companyName) && $this->companyIdentifier;

        $personaliaDataIsComplete = !empty($this->givenName)
            && !empty($this->surname)
            && !empty($this->phone)
            && !empty($this->email);

        $billingAddressIsValid = isset($this->billingAddress)
            ? $this->billingAddress->isComplete()
            : true;

        return $personaliaDataIsComplete
            && $businessDataIsComplete
            && $billingAddressIsValid
            && $this->deliveryAddress->isComplete();
    }

    public function toArray(): array
    {
        return [
            'source' => $this->source,
            'isCompany' => $this->isCompany,
            'companyName' => $this->companyName,
            'companyIdentifier' => $this->companyIdentifier,
            'givenName' => $this->givenName,
            'surname' => $this->surname,
            'phone' => $this->phone,
            'email' => $this->email,
            'deliveryAddress' => $this->deliveryAddress->toArray(),
            'billingAddress' => isset($this->billingAddress) ? $this->billingAddress->toArray() : null,
            'isComplete' => $this->isComplete()
        ];
    }
}
