<?php

namespace MabenDev\Permissions\Commands\Role;

use Illuminate\Console\Command;

class Give extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:give
                {role : role to give}
                {modelId : id to give role to}
                {model? : model to give role to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Give role to a given model';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(!empty($this->argument('model'))) {
            $class = str_replace('/', '\\', $this->argument('model'));
        } else {
            $class = config('MabenDevPermissions.userModel');
        }

        $model = $class::findOrFail($this->argument('modelId'));
        if(!$model->giveRole($this->argument('role'))) {
            $this->error('Could not give ' . $this->argument('mode') . ' role: ' . $this->argument('role'));
            die;
        }

        $this->info('Role (' . $this->argument('role') . ') given to: ' . $class . '(' . $this->argument('modelId') . ')');
    }
}
