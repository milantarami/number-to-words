<?php

namespace MilanTarami\NumberToWordsConverter;

use Illuminate\Support\ServiceProvider;
use MilanTarami\NumberToWordsConverter\Services\NumberToWords;

class NumberToWordsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('MilanTarami\NumberToWordsConverter\NumberToWordsServiceProvider');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->app->bind('NumberToWords', NumberToWords::class);
    }
}
