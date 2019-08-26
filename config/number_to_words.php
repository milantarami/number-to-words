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
    * default = en
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
    **/

    'monetary_units' => [
        'en' => [ 
            'Rupees', 'Paisa'
        ],
        'np' => [
            'रुपैया', 'पैसा'
        ]
    ],


];
