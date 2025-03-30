<?php

namespace App\Providers;

use App\Helpers\Image;
use Faker\Provider\Base as BaseProvider;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class RandomAvatarFakerProvider extends BaseProvider
{
    private static string $API_URL = 'https://i.pravatar.cc/';

    public static function setApiUrl(string $url)
    {
        self::$API_URL = rtrim($url, '/') . '/';
    }

    public static function getApiUrl()
    {
        return self::$API_URL;
    }

    public static function randomAvatarUrl($size = 150, $uniqueId = NULL, $img = NULL): string
    {
        return self::buildRandomAvatarUrl($size, self::buildQueryString(['u' => $uniqueId, 'img' => $img]));
    }

    public static function storeRandomAvatar($storagePath, $size, $uniqueId = NULL, $img = NULL, $fullPath = TRUE): string
    {
        $url = self::buildRandomAvatarUrl($size, self::buildQueryString(['u' => $uniqueId, 'img' => $img]));

        return Image::downloadImage($url, $storagePath, $fullPath);
    }

    public static function randomAvatarFileContent($size, $uniqueId = NULL, $img = NULL): string
    {
        $url = self::buildRandomAvatarUrl($size, self::buildQueryString(['u' => $uniqueId, 'img' => $img]));

        return Image::fetchImage($url);
    }

    private static function buildQueryString($queryParams)
    {
        $queryParams = array_filter($queryParams, function ($value) {
            return !is_null($value);
        });
        if (empty($queryParams)) {
            return '';
        }
        return '?' . http_build_query($queryParams);
    }

    protected static function buildRandomAvatarUrl($size, $queryString): string
    {
        $baseUrl = self::getApiUrl();

        return $baseUrl . $size . $queryString;
    }



}
