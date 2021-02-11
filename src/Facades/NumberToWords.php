<?php

namespace MilanTarami\NumberToWordsConverter\Facades;

use Illuminate\Support\Facades\Facade;

class NumberToWords extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'numbertowords';
    }
}
