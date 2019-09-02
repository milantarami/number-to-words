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
For below version need to register a service provider in <code>config/app.php</code>

``` bash
'providers' => [

    /*
    * Package Service Providers...
    */
    
   MilanTarami\NumberToWordsConverter\NumberToWordsServiceProvider::class         

],
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