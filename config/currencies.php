<?php

/*
|--------------------------------------------------------------------------
| Currencies & locations
|--------------------------------------------------------------------------
| Product prices in the database are stored in the BASE currency (PKR).
| `rate` = how many units of that currency 1 unit of the base currency buys
| (display price = stored price × rate). Rates are approximate and can be
| overridden from .env (e.g. CURRENCY_RATE_USD=0.0036).
|
| `default` is what a visitor sees before (or if) IP detection fails.
*/

return [

    'base'    => 'PKR',
    'default' => env('CURRENCY_DEFAULT', 'USD'),

    'currencies' => [
        'USD' => ['symbol' => '$',   'name' => 'US Dollar',          'rate' => (float) env('CURRENCY_RATE_USD', 0.00357), 'decimals' => 2],
        'PKR' => ['symbol' => 'Rs',  'name' => 'Pakistani Rupee',    'rate' => 1.0,                                       'decimals' => 0],
        'GBP' => ['symbol' => '£',   'name' => 'British Pound',      'rate' => (float) env('CURRENCY_RATE_GBP', 0.00282), 'decimals' => 2],
        'EUR' => ['symbol' => '€',   'name' => 'Euro',               'rate' => (float) env('CURRENCY_RATE_EUR', 0.00333), 'decimals' => 2],
        'AED' => ['symbol' => 'AED', 'name' => 'UAE Dirham',         'rate' => (float) env('CURRENCY_RATE_AED', 0.01312), 'decimals' => 2],
        'SAR' => ['symbol' => 'SAR', 'name' => 'Saudi Riyal',        'rate' => (float) env('CURRENCY_RATE_SAR', 0.01340), 'decimals' => 2],
        'CAD' => ['symbol' => 'C$',  'name' => 'Canadian Dollar',    'rate' => (float) env('CURRENCY_RATE_CAD', 0.00488), 'decimals' => 2],
        'AUD' => ['symbol' => 'A$',  'name' => 'Australian Dollar',  'rate' => (float) env('CURRENCY_RATE_AUD', 0.00540), 'decimals' => 2],
        'INR' => ['symbol' => '₹',   'name' => 'Indian Rupee',       'rate' => (float) env('CURRENCY_RATE_INR', 0.29900), 'decimals' => 0],
    ],

    /*
    | Countries shown in the header Location dropdown (in this order) and
    | the currency each one uses. Any other detected country falls back to
    | `default`.
    */
    'countries' => [
        'US' => ['name' => 'United States',        'flag' => '🇺🇸', 'currency' => 'USD'],
        'PK' => ['name' => 'Pakistan',             'flag' => '🇵🇰', 'currency' => 'PKR'],
        'GB' => ['name' => 'United Kingdom',       'flag' => '🇬🇧', 'currency' => 'GBP'],
        'CA' => ['name' => 'Canada',               'flag' => '🇨🇦', 'currency' => 'CAD'],
        'AU' => ['name' => 'Australia',            'flag' => '🇦🇺', 'currency' => 'AUD'],
        'AE' => ['name' => 'United Arab Emirates', 'flag' => '🇦🇪', 'currency' => 'AED'],
        'SA' => ['name' => 'Saudi Arabia',         'flag' => '🇸🇦', 'currency' => 'SAR'],
        'IN' => ['name' => 'India',                'flag' => '🇮🇳', 'currency' => 'INR'],
        'DE' => ['name' => 'Germany',              'flag' => '🇩🇪', 'currency' => 'EUR'],
        'FR' => ['name' => 'France',               'flag' => '🇫🇷', 'currency' => 'EUR'],
        'IT' => ['name' => 'Italy',                'flag' => '🇮🇹', 'currency' => 'EUR'],
        'ES' => ['name' => 'Spain',                'flag' => '🇪🇸', 'currency' => 'EUR'],
        'NL' => ['name' => 'Netherlands',          'flag' => '🇳🇱', 'currency' => 'EUR'],
        'IE' => ['name' => 'Ireland',              'flag' => '🇮🇪', 'currency' => 'EUR'],
    ],

    /* Detected countries not in the list above but that use a listed currency. */
    'country_currency_extra' => [
        'AT' => 'EUR', 'BE' => 'EUR', 'FI' => 'EUR', 'PT' => 'EUR', 'GR' => 'EUR', 'LU' => 'EUR',
        'SK' => 'EUR', 'SI' => 'EUR', 'EE' => 'EUR', 'LV' => 'EUR', 'LT' => 'EUR', 'MT' => 'EUR', 'CY' => 'EUR', 'HR' => 'EUR',
    ],

    /* IP geolocation (free, no key). Set GEOIP_ENABLED=false to skip lookups. */
    'geoip' => [
        'enabled'  => filter_var(env('GEOIP_ENABLED', true), FILTER_VALIDATE_BOOL),
        'timeout'  => 3,
        /* Country code to assume for localhost / private IPs while developing. */
        'dev_country' => env('GEOIP_DEV_COUNTRY', 'US'),
    ],
];
