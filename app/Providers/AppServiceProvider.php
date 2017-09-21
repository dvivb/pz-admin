<?php

namespace App\Providers;

use App\Dictionary;
use App\HouseLevy;
use App\LandLevy;
use App\Observers\DictionaryObserver;
use App\Observers\HouseLevyObserver;
use App\Observers\LandLevyObserver;
use App\Observers\PeriodObserver;
use App\Period;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Dictionary::observe(DictionaryObserver::class);
        HouseLevy::observe(HouseLevyObserver::class);
        LandLevy::observe(LandLevyObserver::class);
        Period::observe(PeriodObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
