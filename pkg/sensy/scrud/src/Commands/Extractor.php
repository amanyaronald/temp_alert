<?php

namespace Sensy\Scrud\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class Extractor extends Command
{
    public $exceptions = ['migrations', 'password_reset_tokens', 'sessions'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sensy-scrud:extractor {--tables=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract database data to be synced';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Extracting Database Data');
        $this->info('--------------------');

        if ($this->option('tables')) {
            $tables = explode(',', $this->option('tables'));
        } else {
            $tables = Schema::getTableListing();
        }

        $this->info('Extracting from Database');
        foreach ($tables as $table) {
            if (in_array($table, $this->exceptions)) {
                continue;
            }

            $this->info('Extracting from '.$table.' with prefix');
            $this->call('iseed', ['tables' => $table, '--classnameprefix' => 'extracted_', '--force' => true]);
        }

        $this->info('--------------------');
        $this->info('Extraction Completed');
        $this->info('--------------------');
    }
}
