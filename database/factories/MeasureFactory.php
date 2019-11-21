<?php

use App\Models\Measure;
use Faker\Generator;


$factory->define(Measure::class, function(Generator $faker){
    return [
        'id_sensor' => $faker->numberBetween($min = 1, $max = 80),
        'value' => $faker->numberBetween($min = 19, $max = 25)
    ];
});