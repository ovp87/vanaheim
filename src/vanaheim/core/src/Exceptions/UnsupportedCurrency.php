<?php declare(strict_types = 1);

namespace Vanaheim\Core\Exceptions;

use Exception;

class UnsupportedCurrency extends Exception
{
    public function __construct($iso4217)
    {
        return parent::__construct('Currency ' . $iso4217 . ' is not supported.', 500);
    }
}
