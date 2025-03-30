<?php

namespace App\Providers;

use Faker\Provider\Base as BaseProvider;

class UkrainianPhoneFakerProvider extends BaseProvider
{
    private static string $COUNTRY_CODE = '380';

    public static function setCountryCode(string $code)
    {
        self::$COUNTRY_CODE = $code;
    }

    public static function getCountryCode()
    {
        return self::$COUNTRY_CODE;
    }

    public static function e164UkrainianPhoneNumber($countryCode = NULL): string
    {
        return self::buildE164PhoneNumber($countryCode);
    }

    private static function buildE164PhoneNumber($countryCode): string
    {
        $countryCode = $countryCode ?? self::getCountryCode();

        return $countryCode . self::numerify('#########');
    }

}
