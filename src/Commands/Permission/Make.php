<?php

namespace MabenDev\Permissions\Commands\Permission;

use Illuminate\Console\Command;
use MabenDev\Permissions\Models\Permission;

/**
 * Class Make
 * @package MabenDev\Permissions\Commands\Permission
 *
 * @author Michael Aben
 */
class Make extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:make
                {permission : Permission key}
                {description : Description for the permission}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new permission';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $permission = Permission::findOrCreate($this->argument('permission'), $this->argument('description'));

        $this->info('Permission (' . $permission->permission . ') created');
    }
}
