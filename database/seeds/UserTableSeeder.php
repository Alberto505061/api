<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class,5)->create();


        DB::table('users')->insert([
            'firstName' => 'Matt',
            'lastName' => 'Rath',
            'email' => 'joan19@bartoletti.net',
            'status' => 'admin',
            'password' => Hash::make('pass'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
