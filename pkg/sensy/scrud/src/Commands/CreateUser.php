<?php

namespace Sensy\Scrud\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sensy-scrud:create-user
                            {name : The name of the user}
                            {email : The email of the user}
                            {--password= : The password of the user}
                            {--role= : The role of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user with an optional role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->option('password') ?? 'password'; //Default password if not provided
        $roleName = $this->option('role') ?? '_Maintainer';

        $userData = [
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password), //Encrypt the password
        ];

        DB::beginTransaction();
        try {
            //Create the user
            $user = User::create($userData);
            //If a role is provided, find the role by name and associate it with the user
            if ($roleName) {
                $role = Role::where('name', $roleName)->first();
                if (! $role) {
                    $this->error("Role '{$roleName}' not found.");
                    //prompt to  select a role
                    $roles = Role::all()->pluck('name')->toArray();
                    $roleName = $this->choice('Select a role', $roles);
                    $role = Role::where('name', $roleName)->first();
                    if (! $role) {
                        $this->error("Selected role '{$roleName}' not found. Please select a valid role.");
                        //rollback
                        DB::rollBack();

                        return $this->warn('Rolled back');
                    }
                } else {
                    $user->assignRole($roleName);
                }
            }
            DB::commit();
            $this->info('User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }
}
