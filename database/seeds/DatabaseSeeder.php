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
        $this->call(DocumentStatusSeeder::class);
        $this->call(DocumentTypeSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(MandantSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(IsoCategorySeeder::class);
        $this->call(AdressatSeeder::class);
    }
}
