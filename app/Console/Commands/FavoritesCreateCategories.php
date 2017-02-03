<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\DocumentType;
use App\FavoriteCategory;

class FavoritesCreateCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favorites:create-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates favorite categories based on existing document types.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $docTypes = DocumentType::all();
    }
}
