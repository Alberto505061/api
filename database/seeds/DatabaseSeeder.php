<?php

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call(SensorTableSeeder::class);
        $this->call(MeasureTableSeeder::class);
        $this->call(BoardTableSeeder::class);
        $this->call(SensorTypeTableSeeder::class);
        $this->call(UserTableSeeder::class
        );
    }
}
