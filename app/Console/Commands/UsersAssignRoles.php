<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\MandantUser;
use App\MandantUserRole;

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
        $roleIds = array(16); // Assign all roles IDs to populate the MandantUserRole-s
        $mandantUsersRoles = MandantUserRole::whereIn('role_id', $roleIds)->groupBy('mandant_user_id')->get();
        $mandantUsers = MandantUser::whereNotIn('id', array_pluck($mandantUsersRoles, 'mandant_user_id'))->orderBy('mandant_id')->orderBy('user_id')->get();
        foreach($mandantUsers as $mu){
            foreach($roleIds as $roleId){
                MandantUserRole::create(['mandant_user_id' => $mu->id, 'role_id' => $roleId]);
            }
        }
    }
}
