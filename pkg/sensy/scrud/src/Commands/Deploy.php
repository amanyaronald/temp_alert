<?php

namespace Sensy\Scrud\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class Deploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sensy-scrud:deploy {--live : Deploying to live}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy the system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = 'Maintainer';
        $email = 'maintainer@sensy.com';
        $password = "SECRET@2024'";

        if ($this->option('live')) {
            $this->line('');
            $this->warn('Deploying to live...');
            $this->line('');

            //Wipe on live
            $this->call('db:wipe');

            //Migrate on live
            $this->call('migrate');

            //Seed on live
            $this->call('db:seed');

            //CleanUp
            $this->info('--------------');
            $this->info('Cleaning up...');
            $this->cleanUp();

            $this->info('');
            $this->info('Clean up Complete...');
            $this->info('--------------------');
        } else {
            $this->call('db:wipe');
            $this->call('migrate');
            $this->call('sensy-scrud:crud', ['--p' => true, '--menus' => true]);
            $this->call('sensy-scrud:create-user', ['name' => $name, 'email' => $email, '--password' => $password]);
        }

//        $this->info('');
          $this->info('Scafold Successfull!');
    //    $this->info('Maintainer User:       '.$name);
      //  $this->info('Maintainer email:      '.$email);
//        $this->info('Maintainer Password:   '.$password);
  //      $this->info('');
    }

    public function cleanUp()
    {
        $directories = [app_path('Console/Commands'), base_path('stubs')];
//
//        dd($directories);
//
//        $directoryPath = storage_path('app/public/example-directory');
//        // Check if the directory exists before attempting to delete it
//        if (File::exists($directoryPath)) {
//            // Delete the directory and its contents
//            File::deleteDirectory($directoryPath);
//        } else {
//            // Directory does not exist
//            echo 'Directory does not exist.';
//        }
    }
}
