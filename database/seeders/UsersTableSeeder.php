<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'slcodingtask@gmail.com',
            'type' => User::SUPER_ADMIN,
            'password' => '$2y$10$yUtdSEMnHxm/YknUKXH6C.BZwtkSbihfQFePwx3ngSbgIG2ECahwa'
        ];

        User::create($data);
    }
}
