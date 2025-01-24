<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Admin',
            'email'=>'Admin@gmail.com',
            'password'=>'1234',
        ]);
        User::create([
            'name'=>'Ali',
            'email'=>'Ali@gmail.com',
            'password'=>'1234',
        ]);
    }
}
