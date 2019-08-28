<?php

namespace MilanTarami\NumberToWordsConverter\Facades;

use Illuminate\Support\Facades\Facade;

class NumberToWordsFacade extends Facade {

    protected static function getFacadeAccessor() {
        return 'numbertowords';
    }

}
