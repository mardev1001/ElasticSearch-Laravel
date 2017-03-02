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
    protected $signature = 'users:assign-roles';

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
        /**
        14 Historien Leser
        16 Wiki Leser
        40 Intranet Benutzer
        **/
        $roleIds = array(14, 16, 40); // Assign all roles IDs to populate the MandantUserRole-s
        foreach($roleIds as $roleId){
            $mandantUsersRoles = MandantUserRole::where('role_id', $roleId)->groupBy('mandant_user_id')->get();
            $mandantUsers = MandantUser::whereNotIn('id', array_pluck($mandantUsersRoles, 'mandant_user_id'))->orderBy('mandant_id')->orderBy('user_id')->get();
            foreach($mandantUsers as $mu){
                MandantUserRole::create(['mandant_user_id' => $mu->id, 'role_id' => $roleId]);
            }
        }
    }
}
