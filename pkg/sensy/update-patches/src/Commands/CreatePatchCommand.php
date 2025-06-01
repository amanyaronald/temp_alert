<?php


namespace Sensy\UpdatePatches\Commands;

use Sensy\UpdatePatches\Models\Patch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CreatePatchCommand extends Command
{
    protected $signature = 'sensy-deploy:create-patch {author : The Creator of the Patch}';
    protected $description = 'Create a new Update Patch';

    public function handle()
    {
        $author = $this->argument('author');
//        $tasks = $this->argument('tasks');

        $name = str_replace([' '], '_', $author);

        #check database for the last patch that run
        $latest = Patch::latest()?->first();

        if($latest){
            #get the name and string split at the string
          $patchName = explode('_',$latest->name);

          $patchNo = (integer) $patchName[1] +1;
          $patchName = 'patch_'.$patchNo;
        }else{
            $patchName = 'patch_1';
        }


        #create the file if it does not exist
        $root_path = config('update-patch.root-path');

        // Create the directory if it doesn't exist
        if (!File::exists($root_path)) {
            File::makeDirectory($root_path, 0755, true);
        }

        #file name
        $fileName = str_replace('_','',ucfirst($patchName));
        $signatureName = str_replace(['_'],'',$patchName);
        $file_path= $root_path . '/' .$fileName .'.php';

        #check if file exists
        $file = File::exists($file_path);

        if(!$file){
            #Copy Stub
            $stub = __DIR__.'/../stubs/patch-command.stub';
            $stub_file = File::get($stub);

            #replace content
            $stub_content = str_replace(['{{fileName}}','{{name}}','{{author}}',"{{date}}"], [
                $fileName,
                $patchName,
                $author,
                now()->format('Y-m-d')
            ], $stub_file);

            #write the file to the location
            File::put($file_path, $stub_content);
        }

        // Convert task list to JSON format
        $taskList = json_encode([]);

        // Insert patch record in the database
        Patch::firstOrCreate([
            'name' => $patchName],[
            'author' => $author,
            'task_list' => $taskList,
            'created_at' => now(),
            'status' => 'pending'
        ]);

        $this->info("Patch '{$patchName}' created by '{$author}' and registered with tasks.");
    }
}
