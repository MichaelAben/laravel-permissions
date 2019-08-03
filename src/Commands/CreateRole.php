<?php
namespace Maben\Permissions\Commands;

use Illuminate\Console\Command;
use Maben\Permissions\Models\Role;

class CreateRole extends Command
{
    protected $signature = 'permissions:create-role {role} {description?}';
    
    protected $description = 'Create a new role';
    
    public function handle() {
        $role = Role::findOrCreate($this->argument('role'), $this->argument('description'));
        
        $this->info("Role '{$role->role}' created");
    }
}

