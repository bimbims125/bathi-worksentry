<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Pram',
            'email' => 'pramdpb@gmail.com',
            'username' => 'pramdpb',
            'role' => 'superadmin',
            'password' => Hash::make('12345'),
        ]);
        // User::create([
        //     'name' => 'Test',
        //     'email' => 'levine@gmail.com',
        //     'username' => 'levine',
        //     'password' => Hash::make('12345'),
        // ]);
    }
}
