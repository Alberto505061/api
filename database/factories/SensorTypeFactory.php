<?php


use App\Models\SensorType;
use Faker\Generator;


$factory->define(SensorType::class, function(Generator $faker){
    return [
        'code_type' => $faker->randomNumber($nbDigits = 2, $strict = true),
        'label' => $faker->unique()->randomElement($array = array ('Temperature','Particules fines','Tension','Vitesse Vent','Eclairement')),
        'unit' => $faker->unique()->randomElement($array = array ('C','ppm','V','m/s','lux'))

    ];
});