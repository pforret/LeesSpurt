<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'peter@forret.com';
        $appKey = config('app.key');
        $password = substr(hash('sha256', $appKey.$email), 0, 16);
        printf("%s : %s\n", $email, $password);

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Peter Forret',
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );
    }
}
