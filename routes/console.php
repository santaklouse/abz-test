<?php

use App\Models\User;
use App\Providers\RandomAvatarFakerProvider;
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('fakerTest', function () {
    $faker = Faker\Factory::create();
    $faker->addProvider(new RandomAvatarFakerProvider($faker));

    $avatarPath = $faker->storeRandomAvatar(size: 70, storagePath: User::PHOTO_PATH, fullPath: TRUE);


    $this->info(Storage::disk('public')->url($avatarPath));
    // remove file
//    Storage::disk('public')->delete($avatar);

    // check is file exists
    $this->info(Storage::disk('public')->exists($avatarPath) ? 'File exists' : 'File not exists');

})->purpose('Test Faker provider for random avatar');
