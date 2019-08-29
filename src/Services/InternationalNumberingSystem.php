<?php

namespace MilanTarami\NumberToWordsConverter\Services;

use MilanTarami\NumberToWordsConverter\Services\NumberToWords;

class InternationalNumberingSystem extends NumberToWords
{

    private $insEN = [
      'thousand' ,'million', 'billion', 'trillon', 'quadrillion', 'quintillion'
    ];

    private $insNP = [
        '', 'हजार', 'मिलियन', 'बिलियन', 'त्रिलियन', 'quadrillion', 'quintillion'
    ];

    public function output($input, $lang)
    {
        $input = number_format(intval($input * 100) / 100, 2, '.', '');
        list($integerVal, $pointVal) = explode('.', $input);
        $pointInWords = $this->lessThan100((int)$pointVal, $lang);
        $integerInWords = '';
        if ($integerVal > 0) {
            $integerValArray = array_map(function ($num) {
                return strrev($num);
            }, str_split(strrev($integerVal), 3));
            foreach ($integerValArray as $key => $number) {

                switch ($lang) {
                    case 'en':
                        $largeNumVal = $this->insEN[$key];
                        break;
                    case 'np':
                        $largeNumVal = $this->insNP[$key];
                        break;
                    default:
                        throw new Exception('Error in NNS : Supported languages are nepali / english');
                }
                $integerInWords = ($number > 0) ? (parent::lessThan1000((int)$number, $lang) . ' ' .  $largeNumVal . ' ' . $integerInWords) : '';
            }
        }
       
        return [
            'integerInWords' => $integerInWords,
            'pointsInWords' => $pointInWords,
            'originalInput' => $input,
            'formattedInput' => $input = number_format(intval($input * 100) / 100, 2, '.', '')
        ];

     }
}
