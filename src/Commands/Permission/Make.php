<?php

namespace MabenDev\Permissions\Commands\Permission;

use Illuminate\Console\Command;

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
     *
     * @return mixed
     */
    public function handle()
    {
        $permission = \MabenDev\Permissions\Models\Permission::findOrCreate($this->argument('permission'), $this->argument('description'));

        $this->info('Permission (' . $permission->permission . ') created');
    }
}
