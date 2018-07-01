<?php

use Illuminate\Database\Seeder;

class PincodeStateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pincode')->insert([
                                       'city' => str_random(10),
                                       'district' => str_random(10),
                                       'division' => str_random(10),
                                       'pincode' => rand(100000,999999),
                                       'statecode' => str_random(2),
                                   ]);

        DB::table('state')->insert([
                                         'name' => str_random(10),
                                         'code' => str_random(2),
                                     ]);
    }
}
