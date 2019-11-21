<?php

use App\Models\Sensor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Generator;

class SensorTableSeeder extends Seeder
{

    public function run(Generator $faker)
    {
       // factory(Sensor::class,60)->create();
        for($i = 1 ; $i < 21 ; $i++)
        {
            for($j = 1 ; $j < 5 ; $j++) {
                DB::table('sensors')->insert([
                    'id_type' => $faker->numberBetween($min = 1, $max = 6),
                    'id_board' => $i,
                    'id_sensor' => $j,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }


        DB::table('sensors')->insert([
            'id_type' => 1,
            'id_board' => 21,
            'id_sensor' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('sensors')->insert([
            'id_type' => 2,
            'id_board' => 21,
            'id_sensor' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('sensors')->insert([
            'id_type' => 3,
            'id_board' => 21,
            'id_sensor' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('sensors')->insert([
            'id_type' => 4,
            'id_board' => 21,
            'id_sensor' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('sensors')->insert([
            'id_type' => 5,
            'id_board' => 21,
            'id_sensor' => 7,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('sensors')->insert([
            'id_type' => 6,
            'id_board' => 21,
            'id_sensor' => 6,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('sensors')->insert([
            'id_type' => 7,
            'id_board' => 21,
            'id_sensor' => 5,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
