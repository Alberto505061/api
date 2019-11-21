<?php


use App\Models\SensorType;
use Illuminate\Database\Seeder;

class SensorTypeTableSeeder extends Seeder
{

    public function run()
    {
        //factory(SensorType::class,5)->create();
        DB::table('sensor_types')->insert([
            'code_type' => '5d',
            'label' => 'PM1',
            'unit' => 'µg/m3'
        ]);

        DB::table('sensor_types')->insert([
            'code_type' => '6d',
            'label' => 'PM2.5',
            'unit' => 'µg/m3'
        ]);

        DB::table('sensor_types')->insert([
            'code_type' => '7d',
            'label' => 'PM10',
            'unit' => 'µg/m3'
        ]);


        DB::table('sensor_types')->insert([
            'code_type' => '69',
            'label' => 'Tension',
            'unit' => 'V'
        ]);

        DB::table('sensor_types')->insert([
            'code_type' => '67',
            'label' => 'Température',
            'unit' => '°C'
        ]);

        DB::table('sensor_types')->insert([
            'code_type' => '70',
            'label' => 'Courant',
            'unit' => 'A'
        ]);

        DB::table('sensor_types')->insert([
            'code_type' => '71',
            'label' => 'Tout ou Rien'
        ]);


    }
}
