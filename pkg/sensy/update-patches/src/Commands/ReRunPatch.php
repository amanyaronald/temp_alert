<?php


namespace Sensy\UpdatePatches\Commands;

use Sensy\UpdatePatches\Models\Patch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ReRunPatch extends Command
{
    protected $signature = 'sensy-deploy:rerun-patch';
    protected $description = 'Create a new Update Patch';

    public function handle()
    {
        DB::beginTransaction();
        $this->line('');
        $this->warn('NOTE::Please note that all pending patches will be applied by the end of this execution.');
        $this->line('');

        try {#pick all patches
            $patches = Patch::where('status', 'applied')->get();#show a selection
            $this->info('Select a patch to re-run');
            $this->table(['id', 'Patch Name', 'Author', 'Date'], $patches->map(function ($patch) {
                return [$patch->id, $patch->name, $patch->author, $patch->created_at];
            }));
            $patch_id = $this->ask('Enter the id of the patch to re-run');#find the $patch_id
            $patch = Patch::find($patch_id);#look for the patch file
            $root_path = config('update-patch.root-path');
            $fileName = str_replace('_', '', ucfirst($patch->name));
            $file_path = $root_path . '/' . $fileName . '.php';
            if (!File::exists($file_path)) {
                $this->error('Patch file not found');
                return;
            }#update the patch status
            $patch->update(['status' => 'pending']);#update all its children
            $patch->tasks()->update(['status' => 'pending']);

            #commit
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }

        #run the patch
        $this->call('sensy-deploy:deploy-patches');
    }
}
