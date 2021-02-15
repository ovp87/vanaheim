<?php declare(strict_types=1);

namespace Vanaheim\Core\Services\Locale;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Vanaheim\Core\Exceptions\UnsupportedLocale;

class Locale
{
    public static function set($locale)
    {
        if (!in_array($locale, config('locale.supported_locales'))) {
            throw new UnsupportedLocale($locale);
        }

        App::setLocale($locale);
        Session::put('locale', $locale);
    }
}
