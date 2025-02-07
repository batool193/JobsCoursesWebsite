<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    public function run()
    {


        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Adminnn@12345678'),
        ]);
        $admin->assignRole('admin');


        $owner = User::create([
            'name' => 'owner',
            'email' => 'owner@gmail.com',
            'password' => Hash::make('Owner@12345678'),
        ]);
        $owner->assignRole('owner');
    }
}
