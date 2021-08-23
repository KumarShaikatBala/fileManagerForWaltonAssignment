<?php

use App\Folder;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Folder::firstOrCreate([
            'folder' => 'My Folder',
        ]);

        User::firstOrCreate([
            'name' => 'Admin',
            'email' => 'xtreamtomal@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
