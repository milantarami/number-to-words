<?php

namespace MilanTarami\NumberToWordsConverter\Services\NepaliNumberingSystem;

use MilanTarami\NumberToWordsConverter\Services\NumberToWords;

class NepaliNumberingSystem extends NumberToWords{

    
    protected $en  = [
        'Thousand', 'Lakh', 'Crore', 'Arab', 'Kharab', 'Neel', 'Padam', 'Shankha', 'Udpadh', 'Ank', 'Jald', 'Madh', 'Paraardha',
        'Ant', 'Maha Ant', 'Shishant', 'Singhar', 'Maha Singhar', 'Adanta Singhar'
    ];

    protected $np  = [
        'हजार', 'लाख', 'करोड', 'अर्ब', 'खर्ब', 'नील', 'पद्म'
    ];

    public function output($input)
    {
        /**
         * PHP dropping decimals without rounding up
         * https://stackoverflow.com/a/9079182/10525009
         **/
        $input = number_format(intval($input * 100) / 100, 2, '.', '');
        list($rupeesVal, $paisaVal) = explode('.', $input);
        $paisaInWords = parent::lessThan100($paisaVal);
        list($aboveHundreds, $hundreds) = $rupeesVal > 999 ? preg_split('/(?<=.{' . (strlen($rupeesVal) - 3) . '})/', $rupeesVal, 2) : [0, $rupeesVal];
        $rupeesInWords = parent::lessThan1000($hundreds);
        $aboveHundredsArr = empty($rupeesVal) ? [] : array_map(function ($num) {
            return strrev($num);
        }, str_split(strrev($aboveHundreds), 2));
        if (!empty($aboveHundredsArr)) {
            foreach ($aboveHundredsArr as $key => $number) {
                $rupeesInWords = ($number > 0) ? (parent::lessThan100($number) . ' ' . $this->en[$key] . ' ' . $rupeesInWords) : '';
            }
        }
        return [
            'rupeesInWords' => !empty($rupeesInWords) ? $rupeesInWords . ' Rupees' : '',
            'paisaInWords' => !empty($paisaInWords) ? $paisaInWords . ' Paisa' : '',
        ];
    }

}
