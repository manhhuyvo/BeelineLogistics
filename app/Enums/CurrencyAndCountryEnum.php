<?php
namespace App\Enums;

class CurrencyAndCountryEnum
{
    /** Available countries */
    const COUNTRY_AU = 'AU';
    const COUNTRY_US = 'US';
    const COUNTRY_CA = 'CA';
    const COUNTRY_JPN = 'JPN';
    const COUNTRY_UK = 'UK';
    const COUNTRY_ES = 'ES';
    const COUNTRY_KR = 'KR';

    const MAP_COUNTRIES = [
        self::COUNTRY_AU => 'Australia',
        self::COUNTRY_US => 'America',
        self::COUNTRY_CA => 'Canada',
        self::COUNTRY_JPN => 'Japan',
        self::COUNTRY_UK => 'United Kingdom',
        self::COUNTRY_ES => 'Span',
        self::COUNTRY_KR => 'Korea',
    ];

    /** Available units */
    const CURRENCY_AUD = 'AUD';    
    const CURRENCY_VND = 'VND';
    const CURRENCY_USD = 'USD';
    const CURRENCY_CAD = 'CAD';
    const CURRENCY_JPY = 'JPY';
    const CURRENCY_GBP = 'GBP';
    const CURRENCY_EUR = 'EUR';
    const CURRENCY_KRW = 'KRW';

    const MAP_CURRENCIES = [
        self::COUNTRY_AU => self::CURRENCY_AUD,
        self::COUNTRY_US => self::CURRENCY_USD,
        self::COUNTRY_CA => self::CURRENCY_CAD,
        self::COUNTRY_JPN => self::CURRENCY_JPY,
        self::COUNTRY_UK => self::CURRENCY_GBP,
        self::COUNTRY_ES => self::CURRENCY_EUR,
        self::COUNTRY_KR => self::CURRENCY_KRW,
    ];

}