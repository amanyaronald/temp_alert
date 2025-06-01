<?php

namespace Sensy\Scrud\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sensy-scrud:permission
                            {--role= : Role name to sync permissions}
                            {--permission= : Permission name to sync}
                            {--all : Sync all permissions}
                            {--create-role= : Role name to create}
                            {--create-permission= : Permission name to create}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync permissions to a role, create roles and permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $role = $this->option('role') ?? '_' . config('scrud.default_role');
        $permission = $this->option('permission');
        $all = $this->option('all');
        $createRole = $this->option('create-role');
        $createPermission = $this->option('create-permission');

        if ($createRole) {
            $this->createRole($createRole);
            $this->info("Role '{$createRole}' created successfully.");
        }

        if ($createPermission) {
            $this->createPermission($createPermission);
            $this->info("Permission '{$createPermission}' created successfully.");
        }

        if ($all) {
            $this->syncAllPermissions($role);
            $this->info('All permissions synced to ' . $role . ' role');
            return;
        }

        if ($role && $permission) {
            $this->syncPermissionToRole($role, $permission);
            $this->info("Permission '{$permission}' synced to role '{$role}' successfully.");
        }
    }

    private function createRole($roleName)
    {
        Role::firstOrCreate(['name' => $roleName]);
    }

    private function createPermission($permissionName)
    {
        Permission::firstOrCreate(['name' => $permissionName]);
    }

    private function syncAllPermissions($role)
    {
        $maintainer_role = Role::where('name', $role)->first();
        $permissions = Permission::all()->pluck("id")->toArray();

        # Sync all permissions to the role
        $this->info('Syncing all ('.count($permissions).') permissions to ' . $role . ' role...');
        $maintainer_role->syncPermissions($permissions);
    }

    private function syncPermissionToRole($role, $permission)
    {
        $role = Role::where('name', $role)->first();
        $permission = Permission::where('name', $permission)->first();

        if ($role && $permission) {
            $role->givePermissionTo($permission);
        } else {
            $this->error('Role or Permission not found.');
        }
    }
}
