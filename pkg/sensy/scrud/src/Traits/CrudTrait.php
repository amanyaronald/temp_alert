<?php

namespace Sensy\Scrud\Traits;

use App\Models\SystemModule;
use DirectoryIterator;
use Illuminate\Support\Str;

trait CrudTrait
{
    /** @param array $special Model name */
    protected $special = ['User', 'Role', 'SystemModule', 'Menu', 'SubMenu', 'Permission', 'Session', 'PasswordResetToken'];

    /** @param array $exclude_models Model name */
    protected $exclude_models = ['Session', 'PasswordResetToken'];

    protected $routePath = 'routes/scrud.php';

    /**
     * Generate model
     *
     * @param  string  $class  Model name
     */
    private function migrationExists($class)
    {
        $table_name = Str::plural(Str::snake($class));

        $path = database_path("migrations/*_create_{$table_name}_table.php");
        $files = glob($path);

        return count($files) > 0 ? $files[0] : null;
    }

    /**
     * @param $migrationFile
     * @return array
     */
    public function extractAttributesFromMigration($migrationFile)
    {
        // Get the content of the migration file
        $content = file_get_contents($migrationFile);

        // Match column definitions, validations, and relationships
        preg_match_all('/\$table->([a-z_]+)\([\'|"]([a-zA-Z_]+)[\'|"],?([^)]*)\)/i', $content, $matches);

        $data = [];
        foreach ($matches[2] as $key => $match) {
            $attribute = (string)$match;

            // Check if the attribute already exists in the array
            $existingAttribute = array_search($attribute, array_column($data, 'attribute'));
            if ($existingAttribute !== false) {
                // If the attribute already exists, update its relationship data
                $data[$existingAttribute]['relationship'] = $this->extractRelationship($content, $attribute);
            } else {
                // If the attribute doesn't exist, add it to the array
                $dataType = (string)$matches[1][$key];
                $validations = $this->extractValidations($content, $attribute);
                $relationship = $this->extractRelationship($content, $attribute);

                $data[] = [
                    'attribute' => $attribute,
                    'datatype' => $dataType,
                    'validations' => $validations,
                    'relationship' => $relationship,
                ];
            }
        }

        return $data ?? [];
    }

    /**
     * @param $content
     * @param $attribute
     * @return array|mixed|void
     */
    private function extractValidations($content, $attribute)
    {
        // Define the regex pattern
        $regex_pattern = '/.*\$table->[a-z_]+\([\'|"](' . $attribute . ')[\'|"],?(.*?)\).*/i';

        // Perform the regex match
        preg_match_all($regex_pattern, $content, $matches, PREG_SET_ORDER);
        $validations = [];
        foreach ($matches as $match) {
            $method = $match[1];
            $parameter = isset($match[2]) ? trim($match[2]) : '';
            $nullable = strpos($match[0], '->nullable()') !== false;
            $unique = strpos($match[0], '->unique()') !== false;
            $otherValidations = isset($match[3]) ? $match[3] : '';

            // Handle array parameters
            if (strpos($parameter, '[') !== false) {
                $parameter = str_replace(['[', ']'], '', $parameter);
                $parameter = str_replace(['\'', '\"'], '', $parameter);
            } else {
                // Handle multiple parameters
            }

            // Construct validation array
            $validations[] = [
                'attribute' => $attribute,
                'nullable' => $nullable,
                'unique' => $unique,
                'parameter' => $parameter,
            ];

            // Extracting additional validations with optional commas
            preg_match_all('/([a-zA-Z_]+)(\(([^)]*)\))?/', $otherValidations, $additionalMatches, PREG_SET_ORDER);
            $additionalValidations = [];

            foreach ($additionalMatches as $additionalMatch) {
                $validation = $additionalMatch[1];
                $parameter = isset($additionalMatch[3]) ? $additionalMatch[3] : null;

                // Handle array parameters
                if (strpos($parameter, '[') !== false) {
                    $parameter = str_replace(['[', ']'], '', $parameter);
                    $parameter = explode(',', $parameter);
                } else {
                    // Handle multiple parameters
                    $parameter = explode(',', $parameter);
                }

                $additionalValidations[$validation] = $parameter;

                $validations['additional_validations'] = $additionalValidations;
            }

            try {
                return $validations[0];
            } catch (\Exception $e) {
                dd($attribute, $content, $this->EXCLUSIONS);
            }
        }
    }

    /**
     * @param $content
     * @param $attribute
     * @return array
     */
    private function extractRelationship($content, $attribute)
    {
        $relationship = [];

        // Define the simplified regex pattern
        $regex_pattern = '/\$table->(?:foreignId|foreign)\(["\'](' . $attribute . ')["\']\)' . // Match the attribute with single or double quotes
            '(?:.*?->(?:references)\([\'"]([^\'"]+)?[\'"]\))?' . // Match optional references with its argument
            '(?:.*?->(?:constrained|on)\([\'"]([^\'"]+)?[\'"]\))*' . // Match optional methods and related table in any order
            '/i';

        // Perform the regex match
        preg_match_all($regex_pattern, $content, $matches, PREG_SET_ORDER);
        // Iterate over matches
        foreach ($matches as $match) {
            // Get constrained table name
            if (!isset($match[3]) || $match[3] == '') {
                $match[3] = $this->getTableNameFromForeignKey($attribute);
            }

            if (!isset($match[2]) || $match[2] == '') {
                $match[2] = 'id';
            }

            $relationship = [
                'referenced' => $match[2],
                'constrained' => $match[3],
                'relationship' => Str::plural(Str::camel($match[3])),
            ];
        }

        return $relationship;
    }

    /**
     * Get the stub file path
     *
     * @param  string  $type
     * @param  string|null  $name
     * @return string
     */
    private function getStubPath($type = null, $class = null, $view = false,$api = false)
    {
        if ($type == 'route') {
            $stub = __DIR__.'/../stubs/routes/web.stub';

            return $stub;
        }
        if (! $view) {
            $class = $this->toSnake($class);

            $stub_folder = strtolower(Str::plural($type));

            if (is_null($class)) {
                $class = $type;
            } else {
                $class = Str::singular($class).'_'.$type;
            }

            $stub = __DIR__."/../stubs/{$stub_folder}/{$class}.stub";

            //check if the file exists in the speciaal folder
            if (! file_exists($stub)) {
                $stub = __DIR__."/../stubs/{$stub_folder}/{$type}.stub";
            }
        } else {
            //If a folder exists with the class name, use it
            $stub_folder = $this->viewName($class,$api);
            $stub = __DIR__."/../stubs/views/{$stub_folder}/{$type}.stub";

            dd($stub);
            if (! file_exists($stub)) {
                $stub = __DIR__."/../stubs/views/{$type}.stub";
            }
        }

        return $stub;
    }

    /**
     * Convert string to snake case
     *
     * @param  string  $input  String to convert
     * @param  bool  $plural  Pluralize the string
     * @return string
     */
    public function toSnake($input, $plural = false)
    {
        return $plural ? Str::plural(Str::snake($input)) : Str::snake($input);
    }

    /**
     * This will clean the whole system plus the database
     */
    public function cleanup()
    {
        // TODO:: Add a confirmation
        return $this->info('Going Clean');
    }

    /**
     * This will clean one Class the whole system plus the database
     */
    public function cleanupSingle($class)
    {
        $module = SystemModule::whereName($class)->first();
        if (is_null($module)) {
            return $this->error('No Module Found with that name');
        }

        // dd($module);
        $this->warn('Working on database');
        $this->warn('======================');

        //# Remove from System Module + Menus + Sub Menus
        $module->delete();

        //# Unasign Permissions
        //##PENDING###

        // FILES
        $this->warn('Working on Files');
        $this->warn('======================');
        // Remove controller
        $_cp = app_path('Http/Controllers/'.$class.'Controller.php');
        $c = file_exists($_cp);
        if ($c) {
            unlink($_cp);
        } else {
            $this->warn('Controller Does not Exits');
        }

        // Remove views //##CHECK CONFIGS FOR LOCATION
        $_vp = resource_path('views/pages/backend/'.$this->viewName($class));
        $v = is_dir($_vp);
        if ($v) {
            $this->deleteContent($_vp);
        } else {
            $this->warn('View Path not found');
        }

        // Remove Migration
        // Remove Model

        // Remove Route
        //# Get route file
        $webPath = __DIR__.'/../'.$this->routePath;

        $web = file_get_contents($webPath);
        $web = str_replace("Route::resource('{$this->viewName($class)}', '{$class}Controller');", '', $web);
        file_put_contents($webPath, $web);

        return $this->info('Going Clean');
    }

    public function deleteContent($path)
    {
        try {
            $iterator = new DirectoryIterator($path);
            foreach ($iterator as $fileinfo) {
                if ($fileinfo->isDot()) {
                    continue;
                }
                if ($fileinfo->isDir()) {
                    if ($this->deleteContent($fileinfo->getPathname())) {
                        @rmdir($fileinfo->getPathname());
                    }
                }
                if ($fileinfo->isFile()) {
                    @unlink($fileinfo->getPathname());
                }
            }
            @rmdir($path);
        } catch (\Exception $e) {
            // write log
            return false;
        }

        return true;
    }

    public function getTableNameFromForeignKey($foreignKey)
    {
        // Remove common foreign key suffixes like "_id"
        $tableName = str_replace('_id', '', $foreignKey);

        // Pluralize the table name
        $pluralTableName = strtolower(Str::plural(Str::snake($tableName)));

        return $pluralTableName;
    }

    protected function viewName($class,$api = false)
    {
        if($api) {
            return 'api_'.str_replace('_', '-', $this->toSnake($class, true));
        }
        return str_replace('_', '-', $this->toSnake($class, true));
    }
}
