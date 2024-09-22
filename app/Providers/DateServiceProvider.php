<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Date;
use App\Models\EraName;
use App\Models\Wareki;
use Carbon\CarbonImmutable;

/**
 * 日付サービスプロバイダ
 * @description 日付サービスプロバイダ
 * Class DateServiceProvider
 * @package App\Providers
 */
class DateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (\Schema::hasTable('era_names')) {
            Wareki::setEraNames();
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        Date::use(CarbonImmutable::class);

        Date::macro('toWareki', function () {
            return new Wareki($this);
        });
    }
}
