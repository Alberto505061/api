<?php


use App\Models\Board;
use Illuminate\Database\Seeder;

class BoardTableSeeder extends Seeder
{

    public function run()
    {
        factory(Board::class,20)->create();
        DB::table('boards')->insert([
            'deveui' => '313135386E377718',
            'id_user' => '6',
            'location' => '879 Avenue de Mimet, 13120 Gardanne'
        ]);
    }
}
