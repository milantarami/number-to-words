# Number to words

[![Issues](https://img.shields.io/github/issues/milantarami/number-to-words?style=flat-square&logo=appveyor)](https://github.com/milantarami/number-to-words/issues)
[![Stars](https://img.shields.io/github/stars/milantarami/number-to-words?style=flat-square&logo=appveyor)](https://github.com/milantarami/number-to-words/stargazers)
<!-- [![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-backup.svg?style=flat-square&logo=appveyor)](https://packagist.org/packages/milantarami/number-to-words) -->

## Installation and setup

You can install this package via composer using:

``` bash
composer require milantarami/number-to-words
```

The package will automatically register its service provider for laravel 5.5.* and above. <br>
For below version need to register a service provider manually in <code>config/app.php</code>

``` bash
'providers' => [

    /*
    * Package Service Providers...
    */
    
   MilanTarami\NumberToWordsConverter\NumberToWordsServiceProvider::class         

],
```

The package will automatically load alias for laravel 5.5.* and above. <br>
For below version need to add alias manually in <code>config/app.php</code>

``` bash
'aliases' => [
    .
    .
    'NumberToWords' => MilanTarami\NumberToWordsConverter\Facades\NumberToWordsFacade::class,

]
```

To publish the config file to <code>config/number_to_words.php</code> run:

``` bash
php artisan vendor:publish --tag=number-to-words-config
```

This is the default contents of the configuration:

``` bash
<?php

return [

    /** 
    *  Add a monetary unit notation to response
    * [ true / false ]
    * default = true
    **/

    'monetary_unit_enable' => true,
    
    /** 
    * supported response language 
    * [ English (en) / Nepali [np] ]
    * default = en
    **/

    'lang' => 'en',

    /** 
    * supported Response Type
    * [ 'string', 'array' ]
    * default = string
    **/

    'response_type' => 'string',

    /** 
    * supported numbering systems
    * [ Nepali Numbering System (nns) / International Numbering System (ins) ]
    * default = nns
    **/

    'numbering_system' => 'nns',

    /** 
    * Monetary Units for Nepal [ in English and Nepali ]
    * ex [ 'Dollar', 'Cent ]
    **/
        
    'monetary_unit' => [

        'en' => [ 
            'Rupees', 'Paisa'
        ],
        'np' => [
            'रुपैया', 'पैसा'
        ]
    ],


];

```

## Optional Paramater ( Array - available keys and values)

<table style="width: 100%;">
    <thead>
        <tr>
            <th>Key</th>
            <th>Description</th>
            <th>Type</th>
            <th>Avaliable Values</th>
            <th>Default</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>monetary_unit_enable</td>
            <td>Enable / Disable monetary unit in response</td>
            <td>Boolean</td>
            <td>true, false</td>
            <td>true</td>
         </tr>
         <tr>
            <td>lang</td>
            <td>Nepali and English Languages are avaliable</td>
            <td>String</td>
            <td>en, np</td>
            <td>en</td>
         </tr>
         <tr>
            <td>response_type</td>
            <td>Output can be returned in String and Array Format</td>
            <td>String</td>
            <td>string, array</td>
            <td>string</td>
         </tr>
        <tr>
            <td>numbering_system</td>
            <td>Two Numbering System are avaliable are Nepali Numbering System (nns) and International Numbering System (ins)</td>
            <td>String</td>
            <td>nns, ins</td>
            <td>nns</td>
         </tr>
         <tr>
            <td>monetary_unit</td>
            <td>You can setup your custom monetary unit in output by replacing Rupees / Paisa as Dollar / Cent . </td>
            <td>Array</td>
            <td>Use custom value as array  [ 'Dollar', 'Cent' ]</td>
            <td>Depends upon language selected</td>
         </tr>
    </tbody>
</table>

##  Basic Usage

``` bash
echo NumberToWords::get(123456789);

//output : Twelve Crore Thirty-four Lakh Fifty-six Thousand Seven Hundred Eighty-nine Rupees and Twelve Paisa

```

## Usage with config as optional paramater

### Example 1

``` bash
$config = [
     'monetary_unit' => [ 'Dollar', 'Cent' ],
     'numbering_system' => 'ins'
 ];
 echo NumberToWords::get(123456789.12, $config);
 
 //output : One Hundred Twenty-three Million Four Hundred Fifty-six Thousand Seven Hundred Eighty-nine Dollar and Twelve Cent
 
 ```
 
 ### Example 2
 ``` bash
 $config = [
     'monetary_unit' => [ 'Dollar', 'Cent' ],
     'numbering_system' => 'ins',
     'response_type' => 'array'
 ];
 
 dd(NumberToWords::get(123456789.12, $config));
 
 //output :
 array:7 [▼
  "integer" => 123456789
  "integer_in_words" => "One Hundred Twenty-three Million Four Hundred Fifty-six Thousand Seven Hundred Eighty-nine Dollar"
  "point" => 12
  "point_in_words" => "Twelve Cent"
  "original_input" => "123456789.12"
  "formatted_input" => "123,456,789.12"
  "in_words" => "One Hundred Twenty-three Million Four Hundred Fifty-six Thousand Seven Hundred Eighty-nine Dollar and Twelve Cent"
]
 
 
```
