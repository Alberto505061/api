<?php

use App\Models\Board;

use Faker\Generator;

// 'deveui' : 8 bytes string, unique LoRa network identifier

$factory->define(Board::class, function(Generator $faker){
    return [
        'deveui' => $faker->randomNumber($nbDigits = 5, $strict = true)
            .$faker->randomNumber($nbDigits = 5, $strict = true)
            . $faker->randomElement($array = array('A','B','C','D','E','F'))
            . $faker->randomNumber($nbDigits = 5, $strict = true),
        'id_user' => $faker->numberBetween($min = 1, $max = 5),
        'location' => $faker->city.', '.$faker->streetAddress
    ];
});