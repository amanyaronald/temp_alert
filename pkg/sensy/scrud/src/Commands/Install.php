<?php

namespace Sensy\Scrud\Commands;

use Illuminate\Console\Command;

class Install extends Command
{
    public $layout_path;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sensy-scrud:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Scrud By Sensy';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->layout_path = app_path('/Scrud/View/AdminLayout.php');

        $this->call('vendor:publish', ['--tag' => 'scrud', '--force' => true]);

        //settup the system dependencies
        $this->call('sensy-scrud:crud', ['--dependency-only' => true]);

    }
}
