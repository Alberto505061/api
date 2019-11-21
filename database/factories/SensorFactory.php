<?php

use App\Models\Sensor;
use Faker\Generator;


$factory->define(Sensor::class, function(Generator $faker){
    return [
        'id_type' => $faker->numberBetween($min = 1, $max = 5),
        'id_board' => $faker->numberBetween($min = 1, $max = 20),

    ];
});