<?php
namespace App\Enums;

class CurrencyAndCountryEnum
{
    /** Available countries */
    const COUNTRY_AU = 'AU';
    const COUNTRY_US = 'US';
    const COUNTRY_CA = 'CA';

    const MAP_COUNTRIES = [
        self::COUNTRY_AU => 'Australia',
        self::COUNTRY_US => 'America',
        self::COUNTRY_CA => 'Canada',
    ];

    /** Available units */
    const CURRENCY_AUD = 'AUD';    
    const CURRENCY_VND = 'VND';
    const CURRENCY_USD = 'USD';
    const CURRENCY_CAD = 'CAD';

    const MAP_CURRENCIES = [
        self::COUNTRY_AU => self::CURRENCY_AUD,
        self::COUNTRY_US => self::CURRENCY_USD,
        self::COUNTRY_CA => self::CURRENCY_CAD,
    ];

}