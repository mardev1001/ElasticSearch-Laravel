<?php

use Illuminate\Database\Seeder;

class IsoCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        /*
            $table->integer('iso_category_parent_id');
            $table->string('name');
            $table->boolean('active');
            $table->boolean('parent');
        */
        
       DB::table('iso_categories')->insert(
            [
                [
                    'iso_category_parent_id' => null,
                    'name' => 'Hauptkategorie',
                    'parent' => true,
                    'active' => true,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'iso_category_parent_id' => 1,
                    'name' => 'Unterkategorie 1',
                    'parent' => false,
                    'active' => true,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'iso_category_parent_id' => 1,
                    'name' => 'Unterkategorie 2',
                    'parent' => false,
                    'active' => true,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
            ]
        );
    }
}
