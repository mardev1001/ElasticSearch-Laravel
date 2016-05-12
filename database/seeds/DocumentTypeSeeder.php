<?php

use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('document_types')->insert(
            [
                [
                    'name' => 'Rundschreiben QMR',
                    'document_art' => '0',
                    'document_role' => '1',
                    'read_required' => '1',
                    'allow_comments' => '0',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'name' => 'Rundschreiben News',
                    'document_art' => '0',
                    'document_role' => '1',
                    'read_required' => '1',
                    'allow_comments' => '0',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'name' => 'Rundschreiben',
                    'document_art' => '0',
                    'document_role' => '1',
                    'read_required' => '1',
                    'allow_comments' => '0',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'name' => 'ISO Dokument',
                    'document_art' => '0',
                    'document_role' => '0',
                    'read_required' => '1',
                    'allow_comments' => '0',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ],
                
                [
                    'name' => 'Vorlagedokument',
                    'document_art' => '1',
                    'document_role' => '0',
                    'read_required' => '0',
                    'allow_comments' => '0',
                    'active' => '1',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ]
                
            ]
        );
    }
}
