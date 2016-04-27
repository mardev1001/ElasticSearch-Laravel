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
        DB::table('roles')->insert(
            [
                [
                    'name' => 'Struktur Admin',
                    'mandant_required' => '0',
                    'admin_role' => '1',
                    'system_role' => '1',
                    'mandant_role' => '1',
                    'wiki_role' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Geschäftsführer',
                    'mandant_required' => '1',
                    'admin_role' => '0',
                    'system_role' => '1',
                    'mandant_role' => '1',
                    'wiki_role' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Team Assistent',
                    'mandant_required' => '1',
                    'admin_role' => '0',
                    'system_role' => '1',
                    'mandant_role' => '1',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Niederlassungsleiter',
                    'mandant_required' => '1',
                    'admin_role' => '0',
                    'system_role' => '1',
                    'mandant_role' => '1',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Neptun Fachkraft',
                    'mandant_required' => '0',
                    'admin_role' => '0',
                    'system_role' => '1',
                    'mandant_role' => '1',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Neptun Verwalter',
                    'mandant_required' => '0',
                    'admin_role' => '1',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Neptun Mitarbeiter',
                    'mandant_required' => '0',
                    'admin_role' => '1',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Schnellfreigeber',
                    'mandant_required' => '0',
                    'admin_role' => '1',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Kommentar Leser',
                    'mandant_required' => '0',
                    'admin_role' => '1',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Rundschreiben Freigeber',
                    'mandant_required' => '0',
                    'admin_role' => '1',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Rundschreiben Verfasser',
                    'mandant_required' => '0',
                    'admin_role' => '1',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Rundschreiben Empfänger',
                    'mandant_required' => '0',
                    'admin_role' => '1',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Dokument Verfasser',
                    'mandant_required' => '0',
                    'admin_role' => '1',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Historien Leser',
                    'mandant_required' => '0',
                    'admin_role' => '1',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Wiki Redakteur',
                    'mandant_required' => '0',
                    'admin_role' => '1',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Wiki Leser',
                    'mandant_required' => '0',
                    'admin_role' => '1',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Benutzer Verwalter',
                    'mandant_required' => '0',
                    'admin_role' => '1',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Mandanten Verfasser',
                    'mandant_required' => '0',
                    'admin_role' => '1',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Mandanten Leser',
                    'mandant_required' => '0',
                    'admin_role' => '1',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],

                [
                    'name' => 'Mandanten Leser 2',
                    'mandant_required' => '0',
                    'admin_role' => '1',
                    'system_role' => '1',
                    'mandant_role' => '0',
                    'wiki_role' => '0',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ]

            ]
        );
    }
}