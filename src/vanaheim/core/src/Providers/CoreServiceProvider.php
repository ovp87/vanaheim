<?php

declare(strict_types = 1);

namespace Vanaheim\Core\Providers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Money\Currency;
use Vanaheim\Core\Services\Locale\Locale;
use Vanaheim\Core\Exceptions\UnsupportedLocale;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * @throws UnsupportedLocale
     */
	public function boot()
	{
		$this->loadRoutes();
		$this->loadMigrations();
		$this->loadConfig();

		$this->setLocale();
		$this->setCurrency();
	}

    /**
     * @throws UnsupportedLocale
     */
	protected function setLocale()
    {
        $defaultLocale = config('locale.default_locale');

        if (Session::has('locale')) {

            $locale = Session::get('locale');

            if (!in_array($locale, config('locale'))) {
                Locale::set($defaultLocale);
            }

        } else {
            Locale::set($defaultLocale);
        }
    }

    protected function setCurrency()
    {
        $defaultCurrency = new Currency(
            config('currency.default_currency_iso4217')
        );

        if (Session::has('cart.currency')) {

            $currencyIsValid = in_array(
                (string) unserialize(Session::get('cart.currency')),
                config('currency.supported_currencies')
            );

            if (!$currencyIsValid) {
                Session::put('cart.currency', serialize($defaultCurrency));
            }

        } else {
            Session::put('cart.currency', serialize($defaultCurrency));
        }
    }

	protected function loadRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
    }

    protected function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    }

    protected function loadConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/currency.php', 'currency');
        $this->mergeConfigFrom(__DIR__ . '/../config/sanctum.php', 'sanctum');
        $this->mergeConfigFrom(__DIR__ . '/../config/locale.php', 'locale');
    }
}
