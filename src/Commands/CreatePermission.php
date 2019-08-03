<?php
namespace Maben\Permissions\Commands;

use Illuminate\Console\Command;
use Maben\Permissions\Models\Permission;

class CreatePermission extends Command
{
    protected $signature = 'permissions:create-permission {permission} {description?}';
    
    protected $description = 'Create a new permission';
    
    public function handle() {
        $permission = Permission::findOrCreate($this->argument('permission'), $this->argument('description'));
        
        $this->info("Permissions '{$permission->permission}' created");
    }
}

