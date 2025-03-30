<?php

namespace Database\Factories;

use App\Models\Position;
use App\Models\User;
use App\Providers\RandomAvatarFakerProvider;
use App\Providers\UkrainianPhoneFakerProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Mmo\Faker\LoremSpaceProvider;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function __construct($count = null, ?Collection $states = null, ?Collection $has = null, ?Collection $for = null, ?Collection $afterMaking = null, ?Collection $afterCreating = null, $connection = null, ?Collection $recycle = null, bool $expandRelationships = true)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection, $recycle, $expandRelationships);
        fake()->addProvider(new RandomAvatarFakerProvider($this->faker));
        fake()->addProvider(new UkrainianPhoneFakerProvider($this->faker));
    }

    public function definition()
    {
        $companyEmail = fake()->unique()->companyEmail();
        return [
            'name' => fake()->name(),
            'email' => $companyEmail,
            'phone' => fake()->unique()->e164UkrainianPhoneNumber(),
            'position_id' => Position::factory(),
            'photo' => fake()->storeRandomAvatar(
                size: 70,
                storagePath: User::PHOTO_PATH,
                uniqueId: $companyEmail,
            ),
        ];
    }
}
