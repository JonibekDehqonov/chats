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
            'login'=>'123456',
            'avatar'=>'https://marketplace.canva.com/EAFmXS7R66Y/1/0/1600w/canva-avatar-foto-de-perfil-hombre-gafas-dibujo-ilustrado-moderno-verde-I9qMvdruJU8.jpg',
            'password'=>'12345678',
        ]);
    }
}
