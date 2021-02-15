<?php declare(strict_types = 1);

namespace Vanaheim\Core\Exceptions;

use Exception;

class UnsupportedLocale extends Exception
{
    public function __construct(string $locale)
    {
        return parent::__construct('Locale ' . (string) $locale . ' is not supported.', 500);
    }
}
