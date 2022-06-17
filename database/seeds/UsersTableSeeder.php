<?php

use App\Http\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $users =  [
            [
                'name' => 'Andy',
                'email' => 'andy@example.com',
                'password' => '123456',
            ],
            [
                'name' => 'Joe',
                'email' => 'joe@example.com',
                'password' => '123456',
            ],
            [
                'name' => 'Grace',
                'email' => 'grace@example.com',
                'password' => '123456',
            ],
            [
                'name' => 'John',
                'email' => 'john@example.com',
                'password' => '123456',
            ]
        ];

        User::insert($users);
    }
}
