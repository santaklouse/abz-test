<?php

namespace App\Helpers;

use Gumlet\ImageResize;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mockery\Exception;
use Tinify\Source;
use Tinify\Tinify;

class Image {
    /**
     * Optimize an image by resizing and compressing it.
     *
     * @param string $path The path to the image file.
     * @throws \Gumlet\ImageResizeException
     * @throws \Exception
     */
    public static function optimize($path)
    {
        Log::debug("optimize: $path");
        $TINIFY_API_KEY = env('TINIFY_API_KEY');

        if (!$TINIFY_API_KEY) {
            throw new \Exception('TINIFY_API_KEY is not set');
        }
        Tinify::setKey($TINIFY_API_KEY);

        $source = Source::fromBuffer(Storage::disk('public')->get($path));
        $result = $source->toFile(Storage::disk('public')->path($path));
        if ($result === FALSE) {
            throw new \Exception('Failed to optimize image');
        }
        return $path;
    }

    public static function crop($path): string
    {
        Log::debug("crop: $path");
        // Use ImageResize to resize the image to 72x72
        $image = ImageResize::createFromString(Storage::disk('public')->get($path));
        $image->crop(72, 72);
        if (!Storage::disk('public')->put($path, $image->getImageAsString())) {
            throw new Exception('Error crop image.');
        }
        return $path;
    }

    public static function cropAndOptimize($path): string
    {
        return self::optimize(
            self::crop($path)
        );
    }

    public static function fetchImage($url): string {
        $valid_types = ['image/png', 'image/jpeg'];
        $client = new HttpClient();
        $response = $client->get($url);
        if (
            !empty(Arr::first($response->getHeader('content-type'))) &&
            in_array(Arr::first($response->getHeader('content-type')), $valid_types, true)
        ) {
            return $response->getBody()->getContents();
        } else {
            throw new \Exception('Invalid content type');
        }
    }

    public static function downloadImage($url, $storagePath, $fullPath = TRUE): string {
        $imageContent = self::fetchImage($url);
        $fileName = Str::random(40);
        $fileName .= '.jpg';

        $path = $storagePath . DIRECTORY_SEPARATOR . $fileName;
        $publicStorage = Storage::disk('public');
        try {
            if ($publicStorage->directoryMissing($storagePath)) {
                $publicStorage->makeDirectory($storagePath);
            }
            $result = $publicStorage->put($path, $imageContent);
            if (!$result) {
                throw new \Exception('Error saving file');
            }
        } catch (\Exception $e) {
            echo 'error: ' . $e->getMessage() . PHP_EOL;
        }
        return $fullPath ? $path : $fileName;
    }

}

