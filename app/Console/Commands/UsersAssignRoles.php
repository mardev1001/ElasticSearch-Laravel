<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UsersAssignRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:assignRoles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigns specified user roles.';

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
        //
    }
}
