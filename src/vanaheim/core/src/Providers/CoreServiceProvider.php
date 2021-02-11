<?php

declare(strict_types = 1);

namespace Vanaheim\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Vanaheim\Core\Services\Cart\Cart;

class CoreServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
		$this->loadMigrationsFrom(__DIR__ . '/../Migrations');
		$this->mergeConfigFrom(
            __DIR__ . '/../config/currency.php', 'currency'
        );
	}
}
