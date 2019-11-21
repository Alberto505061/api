<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;


$factory->define(User::class, function(Faker\Generator $faker){
    return [
        'firstName' => $faker->firstName,
        'lastName' => $faker->lastName,
        'email' => $faker->email,
        'password' => Hash::make('pass'),
        'status' => 'user'


    ];
});