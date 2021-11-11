<?php

namespace MilanTarami\NumberToWordsConverter;

use Illuminate\Foundation\AliasLoader;
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
        // $this->app->register('MilanTarami\NumberToWordsConverter\NumberToWordsServiceProvider');
        $this->mergeConfigFrom(__DIR__ . '/../config/number_to_words.php', 'number_to_words');
    }

    /**
     * Bootstrap services.
     *
     * Publishes configuration file.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [__DIR__ . '/../config/number_to_words.php' => config_path('number_to_words.php'),],
                'number-to-words-config'
            );
        }
        $this->app->bind('numbertowords', function () {
            return new NumberToWords();
        });

        AliasLoader::getInstance()->alias('NumberToWords', 'MilanTarami\NumberToWordsConverter\Facades\NumberToWordsFacade');
    }
}
