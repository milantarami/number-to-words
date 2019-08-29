<?php

namespace MilanTarami\NumberToWordsConverter\Services;

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
        '', 'एक', 'दुई', 'तिन', 'चार', 'पाँच', 'छ', 'सात', 'आठ', 'नौ', 'दश',
        'एघार', 'बाह्र', 'तेह्र', 'चौध', 'पन्ध्र', 'सोह्र', 'सत्र', 'अठार', 'उन्नाइस', 'बिस',
        'एक्काइस', 'बाइस', 'तेइस', 'चौबिस', 'पच्चीस', 'छब्बीस', 'सत्ताइस', 'अठाइस', 'उनन्तीस', 'तिस',
        'एकतिस', 'बत्तीस', 'तेत्तीस', 'चाैतीस', 'पैतिस', 'छत्तीस', 'सरतीस', 'अरतीस', 'उननचालीस', 'चालीस',
        'एकचालीस', 'बयालिस', 'तीरचालीस', 'चौवालिस', 'पैंतालिस', 'छयालिस', 'सरचालीस', 'अरचालीस', 'उननचास', 'पचास',
        'एकाउन्न', 'बाउन्न', 'त्रिपन्न', 'चौवन्न', 'पच्पन्न', 'छपन्न', 'सन्ताउन्न', 'अन्ठाउँन्न', 'उनान्न्साठी', 'साठी',
        'एकसट्ठी', 'बयसट्ठी', 'त्रिसट्ठी', 'चौंसट्ठी', 'पैंसट्ठी', 'छयसट्ठी', 'सतसट्ठी', 'अठसट्ठी', 'उनन्सत्तरी', 'सत्तरी',
        'एकहत्तर', 'बहत्तर', 'त्रिहत्तर', 'चौहत्तर', 'पचहत्तर', 'छहत्तर', 'सत्हत्तर', 'अठ्हत्तर', 'उनास्सी', 'अस्सी',
        'एकासी', 'बयासी', 'त्रीयासी', 'चौरासी', 'पचासी', 'छयासी', 'सतासी', 'अठासी', 'उनान्नब्बे', 'नब्बे',
        'एकान्नब्बे', 'बयान्नब्बे', 'त्रियान्नब्बे', 'चौरान्नब्बे', 'पंचान्नब्बे', 'छयान्नब्बे', 'सन्तान्‍नब्बे', 'अन्ठान्नब्बे', 'उनान्सय', ' एक सय'
    ];

    private $nepaliNumberingSystem, $internationalNumberingSystem;
    // references http://www.nepaliclass.com/large-nepali-numbers-lakh-karod-arab-kharab/

    public function __construct()
    {
        
    }

    /**
     *
     * @param Mixed $input
     * @param Array $optional array_keys => [  ]
     * **/


    public function get($input, $optional = [])
    {
        $monetaryUnitEnable = array_key_exists('monetary_unit_enable', $optional) ? $optional['monetary_unit_enable'] : config('number_to_words.monetary_unit_enable');
        $monetaryUnit = array_key_exists('monetary_unit', $optional) ? $optional['monetary_unit'] : config('number_to_words.monetary_unit');
        $numberingSystem = array_key_exists('numbering_system', $optional) ? $optional['numbering_system'] : config('number_to_words.numbering_system');
        $lang = array_key_exists('lang', $optional) ? $optional['lang'] : config('number_to_words.lang');
        $responseType = array_key_exists('response_type', $optional) ? $optional['response_type'] : config('number_to_words.response_type');
        switch ($numberingSystem) {
            case 'nns':
                $nepaliNumberingSystem = new NepaliNumberingSystem();
                $result = $nepaliNumberingSystem->output($input, $lang);
                break;
            case 'ins':

                break;
            default:
                throw new Exception('Unkonwn Numbering System');
        }

        dd($result);
    }

    /**
     * Numbers Between 0 - 99
     * @param Int $number
     **/
    protected function lessThan100($number)
    {
        $numArr = str_split($number, 1);
        dd($this->lang);
        switch ($this->lang) {
            case 'en':
                return $number < 20 ? $this->en1[(int) $number] : (($number % 10 == 0) ? $this->en2[$numArr[0]] : $this->en2[$numArr[0]] . '-' . strtolower($this->en1[$numArr[1]]));
                break;
            case 'np':
                return $this->np[$number];
                break;
            default:
                throw new Exception('Supported language English / Nepali');
        }
        // if ($number < 20)
        //     return $this->en1[$number];
        // else
        //     return ($number % 10 == 0) ? $this->en2[$numArr[0]] : $this->en2[$numArr[0]] . '-' . strtolower($this->en1[$numArr[1]]);
    }

    /**
     * Numbers Between 0 - 999
     * @param Int $number
     **/
    protected function lessThan1000($number)
    {
        $numArr =  array_map(function ($num) {
            return strrev($num);
        }, str_split(strrev($number), 2));
        return array_key_exists('1', $numArr) && $numArr[1] > 0 ? $this->en1[$numArr[1]] . ' Hundred ' . $this->lessThan100($numArr[0]) : $this->lessThan100($numArr[0]);
    }

    private function isValidInput()
    {

        //number must not start with zero]
        //must not contain multiple dots(.)
        // throw new Exception('Only int & double values are supported');
    }

}
