<?php

use Illuminate\Database\Seeder;

class AdressatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('adressats')->insert(
            [
                [
                    'name' => 'Filip Filipovic',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'name' => 'Marijan Gudelj',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'name' => 'Verena Rometsch',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
            ]
        );
    }
}
