<?php

use MilanTarami\NumberToWordsConverter\Facades\NumberToWords;
use Orchestra\Testbench\TestCase;

class NumberToWordsTest extends TestCase
{

    /** @test */
    public function number_to_words_in_np_lang()
    {
        NumberToWords::get('122');
    }
}
