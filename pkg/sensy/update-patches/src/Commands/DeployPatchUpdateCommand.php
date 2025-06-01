<?php

namespace Sensy\UpdatePatches\Commands;

use Sensy\UpdatePatches\Models\Patch;
use Sensy\UpdatePatches\Models\PatchTask;
use Sensy\UpdatePatches\Traits\UpdatePatchTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Throwable;

class DeployPatchUpdateCommand extends Command
{
    use UpdatePatchTrait;

    protected $signature = 'sensy-deploy:deploy-patches';
    protected $description = 'Deploy pending patches';

    public function handle()
    {
        \Log::info('Starting Deployment Update...');



        $rootPath = config('update-patch.root-path');

        if (!File::exists($rootPath)) {
            $this->error("Root path {$rootPath} does not exist.");
            return;
        }

        $files = collect(File::files($rootPath))->sortBy(function ($file) {
            return $file->getFilename();
        });

        $latestPatch = Patch::where('status', 'applied')->latest()->first();
        $latestPatchNo = $latestPatch ? (int)explode('_', $latestPatch->name)[1] : 0;

        # Filter new and pending patches
        $newPatches = $files->filter(function ($file) use ($latestPatchNo) {
            preg_match('/Patch(\d+)\.php$/', $file->getFilename(), $matches);
            $patchNo = isset($matches[1]) ? (int)$matches[1] : 0;
            return $patchNo > $latestPatchNo;
        });

        if ($newPatches->isEmpty()) {
            $this->info('No new patches to deploy.');
            return;
        }

        $this->info("Found " . $newPatches->count() . " patches to deploy.");
        $auth=$this->authenticateMaintainer();
        if(!$auth) return;

        try {
                DB::beginTransaction();
            foreach ($newPatches as $patchFile) {
                $patchName = pathinfo($patchFile->getFilename(), PATHINFO_FILENAME);

                #create a new instance of the patch and get the author and date
                #namespace
                $patchClass = config('update-patch.root-path-namespace') . $patchName;
                $patchInstance = new $patchClass();
                $author = $patchInstance->author;
                $date = $patchInstance->date;
                $taskList = $patchInstance->taskList;
                $name = $patchInstance->patch_name;

                # Check if the patch already exists with "pending" status in the database
                $patchRecord = Patch::firstOrCreate(
                    ['name' => $name],
                    [
                        'author' => $author, # Adjust as needed
                        'task_list' => json_encode($taskList),
                        'status' => 'pending',
                    ]
                );

                $patchName = $patchClass;
                # If the patch is already completed, skip it
                if ($patchRecord->status === 'applied') {
                    $this->info("Patch {$patchName} already deployed. Skipping.");
                    continue;
                }

                #create tasklist
                foreach ($taskList as $f=>$t){
                    $patchRecord->tasks()->firstOrCreate([
                        'function' =>$f,
                        'description' =>$t
                    ]);
                }

                if (class_exists($patchName)) {
                    $this->info("Running patch: {$patchName}");

                    $signature = $patchInstance->signature;
                    $call = $this->call($signature);
                    if(!$call) throw new \Exception("Patch class {$patchName} failed to run.");
                } else {
                    throw new \Exception("Patch class {$patchName} not found.");
                }
            }

            DB::commit();
            $this->info('Patch deployment Complete.');
            return 1;

        } catch (Throwable $e) {
            DB::rollBack();
            $this->error("Failed to deploy patches: {$e->getMessage()}");

            return 0;
        }
    }
}
