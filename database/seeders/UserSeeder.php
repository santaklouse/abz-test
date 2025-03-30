<?php

namespace Database\Seeders;

use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $positions = Position::all();

        User::factory()
            ->count(45)
            ->state(new Sequence(
                fn (Sequence $sequence) => ['position_id' => $positions->random()],
            ))
            ->create();
    }
}
