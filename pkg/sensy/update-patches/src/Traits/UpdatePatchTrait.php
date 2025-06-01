<?php

namespace Sensy\UpdatePatches\Traits;

use Sensy\UpdatePatches\Models\Patch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait UpdatePatchTrait
{

    public function initPatch()
    {
        Log::info('Starting Deployment Update...');
        $this->author($this->author, $this->date);

        $confirm = $this->activityList();
        if (!$confirm) return 0;

        try {

            $response = $this->startPatchRun();
            if ($response['status'] == 0) {
                throw new \Exception($response['message']);
            }
            $this->info(__class__ . ' update completed successfully.');

            return 1;
        } catch (\Exception $e) {
            $this->error(__class__ . ' Failed, Rolling Back');
            $this->error('-----------------------------------');
            $this->error($e->getMessage());
            $this->error('-----------------------------------');
            Log::error('PATCH ERROR:::', [$e]);
            return 0;
        }
    }

    public function activityList()
    {

//        $this->info('Running Updates...');
        $this->info('=============================================================');
        $this->info('Activity List');
        foreach ($this->taskList as $func => $li) {
            $this->info('-' . $li);
        }
        $this->info('=============================================================');

        # Confirm
        $confirm = $this->confirm('Proceed?');
        if (!$confirm) {
            $this->warn('Execution terminated');
            Log::info('Deployment Update terminated by user.');
            return 0;
        }
        return 1;
    }

    public function author($name, $date, $amended_on = null)
    {
        $this->info('Author: ' . $name);
        $this->info('Date: ' . $date);
        if ($amended_on) $this->info('Amended on: ' . $amended_on);
    }

    public function authenticateMaintainer()
    {
        $this->info('Authenticating as Maintainer');
        $this->info('---------------------------');
        Log::info('Authenticating as Maintainer');
        $email = $this->ask('Enter your email');
        $password = $this->secret('Enter your password');

        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            $this->error('Authentication failed. Please try again.');
            Log::error('Authentication failed. Please try again.');
            return 0;
        }
        $this->info('Authentication successful');
        $this->line('');

        return 1;
    }

    public function startPatchRun()
    {
        foreach ($this->taskList as $func => $task) {
            if (method_exists($this, $func)) {
                $this->line('');
                $this->info($task);
                $this->info('---------------------------');
                Log::info($task);

                $response = $this->{$func}();
                $response = $response ?? ['status' => 1, 'message' => 'Task function ' . $task . ' executed successfully.'];
                $this->info('---------------------------');


            } else {
                $this->error('Task function ' . $task . ' not found.');
                $response = [
                    'status' => 0,
                    'message' => 'Task function ' . $task . ' not found.'
                ];
            }
            if ($response['status'] == 0) {
                $this->error('Task function ' . $task . ' failed.');
                Log::error('Task function ' . $task . ' failed.');
                return $response;
            }

            #update task status
            $this->info($response['message']);
            Log::info($response['message']);
            $this->updatePatchTask($func, $response);
            $this->line('');


        }
        $update = $this->updatePatch($response);

        if (!$update) return $response;
        return ['status' => 1, 'message' => 'All tasks executed successfully.'];
    }

    public function updatePatch($response)
    {

        $patch = $this->findPatch();

        if (!$patch) {
            $this->error('Patch record not found.');
            return false;
        }

        $patch->applied_at = now();
        $patch->user_id = auth()?->user()?->id;
        if ($response['status'] == 0) {
            $patch->status = 'failed';
            $patch->save();
            return false;
        }

        $patch->status = 'applied';
        $patch->save();
    }

    public function updatePatchTask($task_func, $response)
    {
        $patch = $this->findPatch();

        if (!$patch) {
            $this->error('Patch record not found.');
            return false;
        }

        #get the task
        $task = $patch->tasks()->where('function', $task_func)->first();

        $task->applied_at = now();
        $task->user_id = auth()?->user()?->id;
        if ($response['status'] == 0) {
            $task->status = 'failed';

            $task->save();
            return false;
        }

        $task->status = 'applied';
        $task->save();
    }

    public function findPatch()
    {
        $patch = Patch::where('name', $this->patch_name)->first();
        return $patch;
    }
}
