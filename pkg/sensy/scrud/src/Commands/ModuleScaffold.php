<?php

namespace Sensy\Scrud\Commands;

use Sensy\Scrud\Traits\CrudTrait;
use Illuminate\Console\Command;

class ModuleScaffold extends Command
{
    use CrudTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sensy-scrud:setup
                            {class}
                            {--m}
                            {--dir=}
                            {--full}';

    public $dir = '';

    public $m = false;

    protected $namespace = "App\Models";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create models with migration by default. Use option --m=* to create model only';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->dir = $this->option('dir');
        $this->m = $this->option('m');
        $class = $this->argument('class');

        $this->generateModel($class);

        if ($this->m) {
            $this->generateMigration($class);
        }
    }

    public function generateModel(string $class)
    {
        $_type = 'model';

        $stubPath = $this->getStubPath($_type, $class);
        $modelPath = base_path(config('scrud.directories.model') . $class . '.php' );

        if (file_exists($modelPath)) {
            $modelFilePath = str_replace(base_path().'/', '', $modelPath);
            $this->info("Model [{$class}] already exists. File: [".$modelFilePath.']');

            return;
        }

        $stubContent = file_get_contents($stubPath);

        $this->replaceNamespaceAndClass($stubContent, $class);

        file_put_contents($modelPath, $stubContent);

        $this->info("Model [{$class}] created successfully. File: [".str_replace(base_path().'/', '', app_path("Models/{$class}.php")).']');
    }

    public function generateMigration(string $class)
    {
        $table_name = $this->toSnake($class, true);

        $_migration = $this->migrationExists($class);

        if (! is_null($_migration)) {
            $migrationFilePath = str_replace(base_path().'/', '', $_migration);
            $this->info("Migration for model [$class] already exists. File: ".$migrationFilePath);

            return;
        }

        $stubPath = $this->getStubPath('migration', $class);

        $stubContent = file_get_contents($stubPath);

        $stubContent = str_replace('{{table_name}}', $table_name, $stubContent);
        $stubContent = str_replace('{{class}}', $class, $stubContent);

        $migrationFileName = date('Y_m_d_His').'_create_'.$table_name.'_table.php';
        $migrationFilePath = database_path('migrations/'.$migrationFileName);

        file_put_contents($migrationFilePath, $stubContent);

        $this->info("Migration for model [$class] created successfully. File: ".str_replace(base_path().'/', '', $migrationFilePath));
    }

    private function replaceNamespaceAndClass(&$content, $class)
    {
        $namespace = $this->dir ? $this->namespace.'\\'.$this->dir : $this->namespace;
        $content = str_replace('{{namespace}}', $namespace, $content);
        $content = str_replace('{{class}}', $class, $content);
    }
}
