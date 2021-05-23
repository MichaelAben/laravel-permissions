<?php

namespace MabenDev\Permissions\Commands\Permission;

use Illuminate\Console\Command;
use MabenDev\Permissions\Models\Permission;
use MabenDev\Permissions\Models\Role;

/**
 * Class Give
 * @package MabenDev\Permissions\Commands\Permission
 *
 * @author Michael Aben
 */
class Give extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:give
                {permission : permission to give}
                {modelId : id to give permission to}
                {model? : model to give permission to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Give permission to given model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if(!empty($this->argument('model'))) {
            $class = str_replace('/', '\\', $this->argument('model'));
        } else {
            $class = Role::class;
        }

        $model = $class::findOrFail($this->argument('modelId'));
        if(!$model->givePermission($this->argument('permission'))) {
            $this->error('Could not give ' . $this->argument('mode') . ' permission: ' . $this->argument('permission'));
            die;
        }

        $this->info('Permission (' . $this->argument('permission') . ') given to: ' . $class . '(' . $this->argument('modelId') . ')');
    }
}
