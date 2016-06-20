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
        $this->call(DummyDocumentStatusSeeder::class);
        $this->call(DummyDocumentTypeSeeder::class);
        $this->call(DummyRoleSeeder::class);
        $this->call(DummyMandantSeeder::class);
        $this->call(DummyUserSeeder::class);
        $this->call(DummyIsoCategorySeeder::class);
        $this->call(DummyAdressatSeeder::class);
        $this->call(DummyDocumentSeeder::class);
    }
}
