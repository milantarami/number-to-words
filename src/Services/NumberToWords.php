<?php

namespace MilanTarami\NumberToWordsConverter\Services;

use ErrorException;
use Exception;
use MilanTarami\NumberToWordsConverter\Exceptions\NumberToWordsException;
use MilanTarami\NumberToWordsConverter\Exceptions\NumberToWordsException as MilanTaramiNumberToWordsException;
use MilanTarami\NumberToWordsConverter\Services\NepaliNumberingSystem;
use MilanTarami\NumberToWordsConverter\Services\InternationalNumberingSystem;

class NumberToWords
{
    protected $lang;

    protected $en1  = [
        '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen',
        'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
    ];

    protected $en2  = ['', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety', 'Hundred'];

    protected $np = [
        'सुन्य', 'एक', 'दुई', 'तिन', 'चार', 'पाँच', 'छ', 'सात', 'आठ', 'नौ', 'दश',
        'एघार', 'बाह्र', 'तेह्र', 'चौध', 'पन्ध्र', 'सोह्र', 'सत्र', 'अठार', 'उन्नाइस', 'बिस',
        'एक्काइस', 'बाइस', 'तेइस', 'चौबिस', 'पच्चीस', 'छब्बीस', 'सत्ताइस', 'अठाइस', 'उनन्तीस', 'तिस',
        'एकतिस', 'बत्तीस', 'तेत्तीस', 'चाैतीस', 'पैतिस', 'छत्तीस', 'सरतीस', 'अरतीस', 'उननचालीस', 'चालीस',
        'एकचालीस', 'बयालिस', 'तीरचालीस', 'चौवालिस', 'पैंतालिस', 'छयालिस', 'सरचालीस', 'अरचालीस', 'उननचास', 'पचास',
        'एकाउन्न', 'बाउन्न', 'त्रिपन्न', 'चौवन्न', 'पच्पन्न', 'छपन्न', 'सन्ताउन्न', 'अन्ठाउँन्न', 'उनान्न्साठी', 'साठी',
        'एकसट्ठी', 'बयसट्ठी', 'त्रिसट्ठी', 'चौंसट्ठी', 'पैंसट्ठी', 'छयसट्ठी', 'सतसट्ठी', 'अठसट्ठी', 'उनन्सत्तरी', 'सत्तरी',
        'एकहत्तर', 'बहत्तर', 'त्रिहत्तर', 'चौहत्तर', 'पचहत्तर', 'छहत्तर', 'सत्हत्तर', 'अठ्हत्तर', 'उनास्सी', 'अस्सी',
        'एकासी', 'बयासी', 'त्रीयासी', 'चौरासी', 'पचासी', 'छयासी', 'सतासी', 'अठासी', 'उनान्नब्बे', 'नब्बे',
        'एकान्नब्बे', 'बयान्नब्बे', 'त्रियान्नब्बे', 'चौरान्नब्बे', 'पंचान्नब्बे', 'छयान्नब्बे', 'सन्तान्‍नब्बे', 'अन्ठान्नब्बे', 'उनान्सय', 'सय'
    ];

    private $nepaliNumberingSystem, $internationalNumberingSystem;
    // references http://www.nepaliclass.com/large-nepali-numbers-lakh-karod-arab-kharab/

    /**
     *
     * @param Mixed $input
     * @param Array $optional array_keys => [  ]
     * **/


    public function get($input, $optional = [])
    {
        $this->isValidInput($input, $optional);
        $monetaryUnitEnable = array_key_exists('monetary_unit_enable', $optional) ? $optional['monetary_unit_enable'] : config('number_to_words.monetary_unit_enable');
        $numberingSystem = array_key_exists('numbering_system', $optional) ? $optional['numbering_system'] : config('number_to_words.numbering_system');
        $lang = array_key_exists('lang', $optional) ? strtolower($optional['lang']) : config('number_to_words.lang');
        switch ($lang) {
            case 'en':
                $monetaryUnit = config('number_to_words.monetary_unit.en');
                break;
            case 'np':
                $monetaryUnit = config('number_to_words.monetary_unit.np');
                break;
            default:
                throw new MilanTaramiNumberToWordsException('Language not supported. Supported languages are English (en), Nepali (np).');
        }
        $monetaryUnit = array_key_exists('monetary_unit', $optional) ? $optional['monetary_unit'] : $monetaryUnit;
        $responseType = array_key_exists('response_type', $optional) ? $optional['response_type'] : config('number_to_words.response_type');
        $this->checkException($lang, $responseType, $monetaryUnitEnable, $numberingSystem, $monetaryUnit);
        switch ($numberingSystem) {
            case 'nns':
                $nepaliNumberingSystem = new NepaliNumberingSystem();
                $result = $nepaliNumberingSystem->output($input, $lang);
                break;
            case 'ins':
                $internationalNumberingSystem = new InternationalNumberingSystem();
                $result = $internationalNumberingSystem->output($input, $lang);
                break;
        }
        $result = $this->processResult($result, $lang, $monetaryUnitEnable, $monetaryUnit, $responseType);
        return $result;
    }


    /**
     * Modify Output
     * @param Array $result
     * @param String $lang
     * @param Array $monetaryUnit
     * @param String $responseType
     **/
    private function processResult($result, $lang, $monetaryUnitEnable, $monetaryUnit, $responseType)
    {
        if ($monetaryUnitEnable) {
            $result['integer_in_words'] = ($result['integer'] > 0) ? $result['integer_in_words'] . ' ' . $monetaryUnit[0] : '';
            $result['point_in_words'] = ($result['point'] > 0) ? $result['point_in_words'] . ' ' . $monetaryUnit[1] : '';
        }
        switch ($lang) {
            case 'en':
                $separator = ' and ';
                break;
            case 'np':
                $separator = ' ';
                break;
        }
        $result['in_words'] = ($result['integer'] > 0) ? $result['integer_in_words']  : '';
        $result['in_words'] .= ($result['integer'] > 0) && ($result['point'] > 0) ? $separator : '';
        $result['in_words'] .= $result['point_in_words'];
        switch (strtolower($responseType)) {
            case 'string':
                return $result['in_words'];
                break;
            case 'array':
                return $result;
                break;
        }
        return $result;
    }

    /**
     * Numbers Between 0 - 99
     * @param Int $number
     **/
    protected function lessThan100($number, $lang)
    {
        $numArr = str_split($number, 1);
        switch ($lang) {
            case 'en':
                if ($number < 20) {
                    $inWords = ($number == 0) ? '' : $this->en1[(int)$number];
                } else {
                    $inWords = ($number % 10 == 0) ? $this->en2[(int)$numArr[0]] : $this->en2[(int)$numArr[0]] . '-' . strtolower($this->en1[(int)$numArr[1]]);
                }
                break;
            case 'np':
                $inWords = $this->np[(int)$number];
                break;
        }
        return $inWords;
    }

    /**
     * Numbers Between 0 - 999
     * @param Int $number
     **/
    protected function lessThan1000($number, $lang)
    {
        $numArr =  array_map(function ($num) {
            return strrev($num);
        }, str_split(strrev($number), 2));

        switch ($lang) {
            case 'en':
                if (array_key_exists('1', $numArr) && $numArr[1] > 0) {
                    // $inWords = $this->en1[$numArr[1]] . ' ' . $this->en2[10] . ' ' . $this->lessThan100((int) $numArr[0], $lang);
                    $inWords = $this->en1[(int)$numArr[1]] . ' ' . $this->en2[10];
                    $inWords .= ($numArr[0] > 0) ? ' ' . $this->lessThan100((int) $numArr[0], $lang) : '';
                } else {
                    $inWords =  $this->lessThan100((int)$numArr[0], $lang);
                }
                break;
            case 'np':
                if (array_key_exists('1', $numArr) && $numArr[1] > 0) {
                    // $inWords = $this->np[$numArr[1]] . ' ' . $this->np[100] . ' ' . $this->lessThan100((int) $numArr[0], $lang);
                    $inWords = $this->np[(int)$numArr[1]] . ' ' . $this->np[100];
                    $inWords .= ($numArr[0] > 0) ? ' ' . $this->lessThan100((int) $numArr[0], $lang) : '';
                } else {
                    $inWords =  $this->lessThan100((int) $numArr[0], $lang);
                }
                break;
        }

        return $inWords;
    }

    private function isValidInput($input, $optional)
    {
        if (!is_numeric($input)) {
            throw new NumberToWordsException('Input must be int or float type. Given ' . gettype($input) . ' type.');
        }

        if ($input > 9999999999999.99) {
            throw new NumberToWordsException('Max supported number is 9999999999999.99');
        }

        if (gettype($optional) !== 'array') {
            throw new NumberToWordsException('Config value must be given in array type');
        }
    }

    private function checkException($lang, $responseType, $monetaryUnitEnable, $numberingSystem, $monetaryUnit)
    {

        if (!in_array($lang, ['en', 'np'])) {
            throw new MilanTaramiNumberToWordsException('Language not supported. Supported languages are English (en), Nepali (np).');
        }


        if (!in_array($responseType, ['string', 'array'])) {
            throw new MilanTaramiNumberToWordsException('Reponse Type not supported. Supported types are Array, String.');
        }


        if (!in_array($monetaryUnitEnable, [true, false])) {
            throw new MilanTaramiNumberToWordsException("'monetary_unit_enable' value must be boolean value");
        }

        if (!in_array($numberingSystem, ['nns', 'ins'])) {
            throw new MilanTaramiNumberToWordsException('Unsupported Numbering System. Supported Numbering System are International Numbering System ( ins ), Nepali Numbering System ( nns )');
        }

        if (strtolower(gettype($monetaryUnit)) == 'array') {
            if (sizeof($monetaryUnit) == 2) {
                if (gettype($monetaryUnit[0]) !== 'string' || gettype($monetaryUnit[1]) !== 'string') {
                    throw new NumberToWordsException("'monetary_unit' indexes value must be string type");
                }
            } else {
                throw new NumberToWordsException("'monetary_unit' must be of length 2");
            }
        } else {
            throw new NumberToWordsException("'monetary_unit' " . 'must be array type.' . 'Given ' . gettype($responseType) . ' type.');
        }
    }
}
