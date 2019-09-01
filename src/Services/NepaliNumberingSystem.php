<?php

namespace MilanTarami\NumberToWordsConverter\Services;

use MilanTarami\NumberToWordsConverter\Services\NumberToWords;
use MilanTarami\NumberToWordsConverter\Exceptions\NumberToWordsException;

class NepaliNumberingSystem extends NumberToWords
{


    private $nnsEN  = [
        'Thousand', 'Lakh', 'Crore', 'Arab', 'Kharab', 'Neel', 'Padam', 'Shankha', 'Udpadh', 'Ank', 'Jald', 'Madh', 'Paraardha',
        'Ant', 'Maha Ant', 'Shishant', 'Singhar', 'Maha Singhar', 'Adanta Singhar'
    ];

    private $nnsNP  = [
        'हजार', 'लाख', 'करोड', 'अर्ब', 'खर्ब', 'नील', 'पद्म'
    ];


    public function output($input, $lang)
    {
        /**
         * PHP dropping decimals without rounding up
         * https://stackoverflow.com/a/9079182/10525009
         **/
        $input = number_format(($input * 100) / 100, 2, '.', '');
        list($integerVal, $pointVal) = explode('.', $input);
        $pointInWords = parent::lessThan100((int) $pointVal, $lang);
        list($aboveHundreds, $hundreds) = $integerVal > 999 ? preg_split('/(?<=.{' . (strlen($integerVal) - 3) . '})/', $integerVal, 2) : [0, $integerVal];
        $integerInWords = parent::lessThan1000($hundreds, $lang);
        if ($aboveHundreds > 0) {
            $aboveHundredsArr = array_map(function ($num) {
                return strrev($num);
            }, str_split(strrev($aboveHundreds), 2));
            foreach ($aboveHundredsArr as $key => $number) {

                switch ($lang) {
                    case 'en':
                        $largeNumVal = $this->nnsEN[$key];
                        break;
                    case 'np':
                        $largeNumVal = $this->nnsNP[$key];
                        break;
                }
                $integerInWords = ($number > 0) ? (parent::lessThan100((int) $number, $lang) . ' ' .  $largeNumVal . ' ' . $integerInWords) : '';
            }
        }
        return [
            'integer' => (int) $integerVal,
            'integer_in_words' => trim($integerInWords),
            'point' => (int) $pointVal,
            'point_in_words' => trim($pointInWords),
            'original_input' => (float) $input,
            'formatted_input' => $this->moneyFormat($input),
        ];
    }

    private function moneyFormat($number)
    {
        $splitted_number = explode('.', $number);
        if ($splitted_number[0] < 1000)
            return $number;
        else {
            list($aboveHundreds, $hundreds) = preg_split('/(?<=.{' . (strlen($splitted_number[0]) - 3) . '})/', $splitted_number[0], 2);
            $aboveHundreds = str_split(strrev($aboveHundreds), 2);
            $result = strrev(join(',', $aboveHundreds)) . ',' . $hundreds;
            $result .= !empty($splitted_number[1]) ? '.' . $splitted_number[1] : '';
            return $result;
        }
    }
}
