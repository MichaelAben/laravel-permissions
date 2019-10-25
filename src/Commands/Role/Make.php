<?php

namespace MabenDev\Permissions\Commands\Role;

use Illuminate\Console\Command;

/**
 * Class Make
 * @package MabenDev\Permissions\Commands\Role
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
    protected $signature = 'role:make
                {name : role name}
                {description : Description for the permission}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new role';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $role = \MabenDev\Permissions\Models\Role::findOrCreate($this->argument('name'), $this->argument('description'));

        $this->info('Role (' . $role->name . ') created');
    }
}
