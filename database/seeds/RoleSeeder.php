<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => '',
            'mandant_required' => '',
            'admin_role' => '',
            'system_role' => '',
            'mandant_role' => '',
            'wiki_role' => ''
        ]);
    }
}
