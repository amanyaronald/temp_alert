<?php

namespace Sensy\Scrud\Commands;

//#REQUIRED
use App\Models\Menu;
use App\Models\SubMenu;
use App\Models\SystemModule;
use App\Models\SystemModuleCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Sensy\Scrud\Traits\CrudTrait;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

//#REQUIRED

class CrudScaffold extends Command
{
    use CrudTrait;

    public $component_name;

    public $dirOption;

    public $ServiceFolder;

    public $show_sub_menus = false;

    public $EXCLUSIONS = [
        'id',
        'created_at',
        'created_by',
        'updated_at',
        'deleted_at',
        'company_id',
        'request_id',
        'workflow_id',
        'workflow_definition_id',
        'workflow_status',
        'assigned_to',
        'status',
        'app_status',
        'deleted_by',
        'delete_comment',
        'tocken',
        'status_id',
        'token',
        'updated_by',
        'deactivated_by'
    ];

    public $SEARCH_COLUMNS = [
        'name',
        'description',
    ];

    public $COMMENTED_OUT = [];

    public $USER_ID_FILL = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sensy-scrud:crud
                            {--dir=}
                            {--full}
                            {--class=}
                            {--rm}
                            {--api : Scaffold API resources}
                            {--dependency-only : Check for dependencies}
                            {--m : Run migration before scaffold}
                            {--all : Scaffold with all functionality}
                            {--basic : Scaffold with basic functionality}
                            {--c : Scaffold with controller}
                            {--v : Scaffold with view}
                            {--r : Scaffold with routes}
                            {--menus : Scaffold with menus}
                            {--p : Scaffold with permissions}
                            {--f : Scaffold with factory}
                            {--t : Scaffold with test}
                            {--s : Scaffold with Service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold a full CRUD';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelPath = app_path('/Models/');
        $class = $this->option('class') ?? '';
        $migrate = $this->option('m');
        $remove = $this->option('rm');
        $all = $this->option('all');
        $basic = $this->option('basic');
        $controller = $this->option('c');
        $view = $this->option('v');
        $route = $this->option('r');
        $menus = $this->option('menus');
        $permissions = $this->option('p');
        $api = $this->option('api');

        $factory = $this->option('f');
        $test = $this->option('t');
        $service = $this->option('s');
        $dependencies = $this->option('dependency-only');

        if ($migrate) {
            $this->call('migrate');
        }

        if ($remove) {
            if ($class === '') {
                $q = $this->ask('!!!No Module was selected, would you like to clear everything!!!', false);
                if ($q) {
                    $q = $this->ask('!!!I Just Want to make sure YOU WANT TO ====CLEAR EVERYTHING====!!!', false);
                }
                if ($q) {
                    return $this->cleanup();
                } else {
                    return exit();
                }
            } else {
                $q = $this->ask('Proceed to remove [' . $class . '] Module?', false);
                if ($q) {
                    return $this->cleanupSingle($class);
                } else {
                    return;
                }
            }
        }

        //Check Dependencies
//        $this->info('Checking Dependencies...');
//        if ($this->checkDependencies() === 0) {
//            return $this->error('Scaffold Terminated Unmet Dependencies');
//        }

        //#Loop through all models
        if ($dependencies) {
            return $this->info('Dependencies Check Completed');
        }

        //#Run Scaffold Checks
        if ($class === '') {
            $to_create = [];
            // Check Get all files from model
            if (File::exists($modelPath)) {

                $choices = ['Generate New Module(Missing on Database)', 'Regenerate Existing Modules', 'Mixed generation(Both New Module Creation and Regenerate )', 'Generate Specific ', 'Quit'];
                //Get Scaffolded System Modules
                $system_modules = SystemModule::all()->pluck('name')->toArray();
                if ($system_modules) {
                    $action = $this->choice('Some modules are already scafolded. How do you wish to proceed?', $choices);

                    if ($action === 'Quit') {
                        return $this->warn('Scaffold Terminated');
                    }

                    if (in_array($action, ['Regenerate Existing', 'Mixed generation', 'Generate Specific ']) || !in_array($action, $choices)) {
                        return $this->error('Implementation Not supported');
                    }
                }

                $files = File::files($modelPath);
                foreach ($files as $file) {
                    $service_ = str_replace('.php', '', $file->getFilename());

                    if (!in_array($service_, $system_modules)) {
                        $to_create[] = $service_;
                    }
                }
            }
            if (count($to_create) === 0) {
                return $this->warn('Scaffold Terminated: No New Modules');
            }
        } else {
            $to_create = [$class];
        }

        //#Scaffold
        foreach ($to_create as $class) {
            $this->warn('');
            $this->warn('===========================================');
            $this->warn('======= Scaffolding [' . $class . '] =======');
            $this->warn('===========================================');

            //#Migration
            // Migrate that specific file
            //Controller
            if (!$api && $service || $all || $basic) {
                $this->info('Generating Service...');
                if ($this->generateService($class) === 0) {
                    $this->warn('Scaffold for [' . $class . '] Terminated');
                    continue;
                }
            }

            if ($api && $service || $all || $basic) {
                $this->info('Generating API Service...');
                if ($this->generateService($class, true) === 0) {
                    $this->warn('Scaffold for [' . $class . '] Terminated');
                    continue;
                }
            }

            if (!$api && $controller || $all || $basic) {
                $this->info('Generating Controller...');
                if ($this->generateController($class) === 0) {
                    $this->warn('Scaffold for [' . $class . '] Terminated');
                    continue;
                }
            }

            if ($api && $controller || $all || $basic) {
                $this->info('Generating API Controller...');
                if ($this->generateController($class,true) === 0) {
                    $this->warn('Scaffold for [' . $class . '] Terminated');

                    continue;
                }
            }

            //Views
            if ($view || $all || $basic) {
                $this->info('Generating Views...');
                if ($this->generateViews($class) === 0) {
                    $this->warn('Scaffold for [' . $class . '] Terminated');

                    continue;
                }
            }

            //Register resource route
            if ($route || $all || $basic) {
                $this->info('Registering Route...');
                if ($this->registerRoutes($class) === 0) {
                    $this->warn('Scaffold for [' . $class . '] Terminated');

                    continue;
                }
            }

            //Generate side Menu for it
            if ($menus || $all || $basic) {
                $this->info('Registering Side Menus...');
                if ($this->generateSideMenus($class) === 0) {
                    $this->warn('Scaffold for [' . $class . '] Terminated');

                    continue;
                }
            }

            //Generate and assign Permissions
            if ($permissions || $all || $basic) {
                $this->info('Generating Permissions...');
                if ($this->generatePermissions($class) === 0) {
                    $this->warn('Scaffold for [' . $class . '] Terminated');

                    continue;
                }
            }

            //            #Generate Factory
            //            if ($factory || $all || $basic) {
            //                $this->info('Generating Factory...');
            //                if ($this->generateFactory($class) === 0) {
            //                    $this->warn("Scaffold for [" . $class . "] Terminated");
            //                    continue;
            //                }
            //            }
            //
            //            #Generate Test
            //            if ($test || $all || $basic) {
            //                $this->info('Generating Test...');
            //                if ($this->generateTest($class) === 0) {
            //                    $this->warn("Scaffold for [" . $class . "] Terminated");
            //                    continue;
            //                }
            //            }
            //
            //            #Generate Seeder
            //            if ($seeder || $all || $basic) {
            //                $this->info('Generating Seeder...');
            //                if ($this->generateSeeder($class) === 0) {
            //                    $this->warn("Scaffold for [" . $class . "] Terminated");
            //                    continue;
            //                }
            //            }
        }
    }

    /**
     * Check for system dependencies
     */
    public function checkDependencies()
    {
        // Check for System Modules
        if (!\Schema::hasTable('system_module_categories')) {
            $this->warn("\nSystem Modules Category dependencies do not exist.");
            if (!$this->confirm('Generate System Module Category dependencies?', true)) {
                return 0;
            }

            $this->call('sensy-scrud:setup', ['class' => 'SystemModuleCategory', '--m' => true]);
            $this->call('migrate');

            $this->generateController('SystemModuleCategory');
            $this->generateViews('SystemModuleCategory');
//            $this->generateSideMenus('SystemModuleCategory');
            $this->registerRoutes('SystemModuleCategory');

            $this->registerModuleCategory();
        }


        // Check for System Modules
        if (!\Schema::hasTable('system_modules')) {
            $this->warn("\nSystem Modules dependencies do not exist.");
            if (!$this->confirm('Generate System Module dependencies?', true)) {
                return 0;
            }

            $this->call('sensy-scrud:setup', ['class' => 'SystemModule', '--m' => true]);
            $this->call('migrate');

            $this->generateController('SystemModule');
            $this->generateViews('SystemModule');
            $this->generateSideMenus('SystemModule');
            $this->registerRoutes('SystemModule');
        }

        // Check for System Settings
        if (!\Schema::hasTable('settings')) {
            $this->warn("\nSettings Modules dependencies do not exist.");
            if (!$this->confirm('Generate Settings Module dependencies?', true)) {
                return 0;
            }

            $this->call('sensy-scrud:setup', ['class' => 'Setting', '--m' => true]);
            $this->call('migrate');

            $this->generateController('Setting');
            $this->generateViews('Setting');
            $this->generateSideMenus('Setting');
            $this->registerRoutes('Setting');
        }

        // Menu dependency
        if (!\Schema::hasTable('menus')) {
            $this->warn("\nMenus dependencies do not exist.");
            $this->warn('----------------------------------------------');
            if (!$this->confirm('Generate Menu dependencies?', true)) {
                return 0;
            }

            $this->call('sensy-scrud:setup', ['class' => 'Menu', '--m' => true]);
            $this->call('migrate');
        }

        // Submenu dependency
        if (!\Schema::hasTable('sub_menus')) {
            $this->warn("\nSubmenus dependencies do not exist.");
            $this->warn('----------------------------------------------');

            if (!$this->confirm('Generate Submenu dependencies?', true)) {
                return 0;
            }

            $this->call('sensy-scrud:setup', ['class' => 'SubMenu', '--m' => true]);
            $this->call('migrate');
        }

        // Jetstream dependency
        if (!$this->isPackageInstalled('laravel/jetstream')) {
            $this->warn("\nJetstream Auth not installed!");
            $this->warn('----------------------------------------------');

            if (!$this->confirm('Perform Jetstream Auth dependency install?')) {
                return 0;
            }

            $this->installJetstream();
        }

        // Spatie Permission dependency
        if (!$this->isPackageInstalled('spatie/laravel-permission')) {
            $this->warn("\nSpatie not installed!");
            $this->warn('----------------------------------------------');

            if (!$this->confirm('Perform Spatie dependency install?')) {
                return 0;
            }

            $this->installSpatie();
        }

        // Check impersonate
//        if (!$this->isPackageInstalled('lab404/laravel-impersonate')) {
//            $this->warn("\nImpersonate not installed!");
//            $this->warn('----------------------------------------------');
//
//            if (!$this->confirm('Perform Impersonate dependency install?')) {
//                return 0;
//            }
//
//            $this->installImpersonate();
//        }

        // Check and generate missing view dependencies
        $this->generateViewDependencies();

        $this->info('All dependencies are present.');
        $this->info('');
    }

    public function generateService(string $class, $isApi = false)
    {
        if (!$isApi) {
            $_service = "{$class}Service";
            $dir = config('scrud.directories.service.web');
            $namespace = config('scrud.class.service.web');
        } else {
            $_service = "{$class}ApiService";
            $dir = config('scrud.directories.service.api');
            $namespace = config('scrud.class.service.api');
        }

        #get last letter, check f its \ then replace remove it.
        $namespace = rtrim($namespace, '\\'); // Remove trailing backslash from namespace
        $namespace = ltrim($namespace, '\\'); // Remove start backslash from namespace
//        $dir = rtrim($dir, '/'); // Remove trailing slash from directory path

        $servicePath = base_path($dir . $_service . '.php');

        if (file_exists($servicePath)) {
            $display = (str_replace(base_path(), '', $servicePath));
            $this->info("Service for model '[$class]' already exists File:'[$servicePath]'");
            if (!$confirm = $this->confirm('Override?', true)) {
                return 0;
            }
        }

        // Load the content of the stub file
        $stubPath = $this->getStubPath('service', $class);
        $stub = file_get_contents($stubPath);

        // Check if the migration file exists
        $_migration_file = $this->migrationExists($class);

        if (!$_migration_file) {
            //Check if its special
            if (!in_array($class, $this->special)) {
                $this->error('');
                $this->error("Migration file not found for model '[$class]' and not in specials list");

                return 0;
            } else {
                $this->warn('Running Special Service...');
            }
        } else {
            // Extract attributes from the migration file
            $attributes = $this->extractAttributesFromMigration($_migration_file);

            // Generate passable data for views
            $passable_data = $this->generatePassableData($attributes);

            $_data = $passable_data['_data'];
            $passable_ = $passable_data['passable_'];
            $_data_imports = $passable_data['_data_imports'];

            // Generate attribute strings and validation rules
            $validation = $this->generateValidationRules($attributes, []);

            // Replace placeholders in the stub content with actual values
            $stub = str_replace('{{ serviceName }}', $_service, $stub);
            $stub = str_replace('{{ bind }}', $this->generateAttributeBind($attributes), $stub);
            $stub = str_replace('{{ namespace }}', $namespace, $stub);

            $stub = str_replace('{{ imports }}', $_data_imports, $stub);
            $stub = str_replace('{{ passable_ }}', $passable_, $stub);
            $stub = str_replace('{{ _dataRetrieve }}', $_data, $stub);


            //SEARCH
            $stub = str_replace('{{search}}', $this->getSearchBind($attributes), $stub);
        }

        File::put($servicePath, $stub);
        $this->info("Service Scaffold successfully created at '[$servicePath]'");
    }

    public function getSearchBind($attributes)
    {

        $search = '';
        foreach ($this->SEARCH_COLUMNS as $column) {
            #check if the column is there in the attributes
            if (!in_array($column, array_column($attributes, 'attribute')))
                continue;


            if ($column == reset($this->SEARCH_COLUMNS)) {
                $search .= "\$subQuery->where('{$column}', 'LIKE', '%' . \$query . '%')";
            } else {
                $search .= "\n\t\t\t\t\t\t->orWhere('{$column}', 'LIKE', '%' . \$query . '%')";
            }
            //check if last and add terminator
            if ($column == end($this->SEARCH_COLUMNS)) {
                $search .= ';';
            } else {
                $search .= '';
            }
        }

        return $search;
    }

    public function generateController(string $class, $isApi)
    {

        if (!$isApi) {
            $_controller = "{$class}Controller";
            $dir = config('scrud.directories.controller.web');
            $namespace = config('scrud.class.controller.web');
        } else {
            $_controller = "{$class}ApiController";
            $dir = config('scrud.directories.controller.api');
            $namespace = config('scrud.class.controller.api');
        }

        #get last letter, check f its \ then replace remove it.
        $namespace = rtrim($namespace, '\\'); // Remove trailing backslash from namespace
        $namespace = ltrim($namespace, '\\'); // Remove start backslash from namespace


        $controllerFileName = base_path($dir . $_controller . '.php');

        if (file_exists($controllerFileName)) {
            $this->info("Controller for model '[$class]' already exists File:'[$controllerFileName]'");
            if (!$confirm = $this->confirm('Override?', true)) {
                return 0;
            }
        }

        // Load the content of the stub file
        $stubPath = $this->getStubPath('controller', $class,api:$isApi);
        $stub = file_get_contents($stubPath);

        // Check if the migration file exists
        $_migration_file = $this->migrationExists($class);

        if (!$_migration_file) {
            //Check if its special
            if (!in_array($class, $this->special)) {
                // code...
                $this->error('');
                $this->error("Migration file not found for model '[$class]' and not in specials list");

                return 0;
            } else {
                $this->warn('Running Special Controller...');
            }
        } else {
            // Extract attributes from the migration file
            $attributes = $this->extractAttributesFromMigration($_migration_file);

//            dd($attributes);


            // Generate passable data for views
            $passable_data = $this->generatePassableData($attributes);
            $_data = $passable_data['_data'];
            $passable_ = $passable_data['passable_'];
            $_data_imports = $passable_data['_data_imports'];

            // Generate attribute strings and validation rules
            $validation = $this->generateValidationRules($attributes, []);

            // Replace placeholders in the stub content with actual values
            $stub = str_replace('{{ studlyModelName }}', $class, $stub);
            $stub = str_replace('{{ validationRules }}', $validation, $stub);
            $stub = str_replace('{{ modelName }}', $class, $stub);
            $stub = str_replace('{{ controllerName }}', $_controller, $stub);

            $stub = str_replace('{{ imports }}', $_data_imports, $stub);
            $stub = str_replace('{{ passable_ }}', $passable_, $stub);
            $stub = str_replace('{{ _dataRetrieve }}', $_data, $stub);

            $stub = str_replace('{{returnView}}', $this->viewname($class), $stub);
            $stub = str_replace('{variable}', $this->variableName($class), $stub);
            $stub = str_replace('{displayName}', $this->displayName($class), $stub);

            //SEARCH
            $search = '';
            $count = count($attributes);
            foreach ($attributes as $attribute) {
                //check if its the first
                if ($attribute == reset($attributes)) {
                    $search .= "Return {$class}::where('{$attribute['attribute']}', 'LIKE', '%' . \$query . '%')";
                } else {
                    $search .= "\n\t\t\t\t\t\t->orWhere('{$attribute['attribute']}', 'LIKE', '%' . \$query . '%')";
                }
                //check if last and add terminator
                if ($attribute == end($attributes)) {
                    $search .= ';';
                } else {
                    $search .= '';
                }
            }
            $stub = str_replace('{{search}}', $search, $stub);

        }

        File::put($controllerFileName, $stub);
        $this->info("Controller Scaffold successfully created at '[$controllerFileName]'");
    }


    // * NEW

    public function generatePassableData($attributes)
    {

        $passable_ = '';
        $_data = '';
        $_data_imports = '';

        $exists = [];
        $count = 0;
        foreach ($attributes as $key => $attribute) {


            if (!empty($attribute['relationship'])) {
                $count++;
                if (in_array($attribute['relationship']['relationship'], $exists)) {
                    continue;
                }

                $exists[] = $attribute['relationship']['relationship'];

                $modelName = Str::singular(Str::studly($attribute['relationship']['relationship']));

                if ($count != 1) $passable_ .= ',';

                $passable_ .= "'{$attribute['relationship']['relationship']}'";

                if ($attribute['relationship']['relationship'] == 'users') {

                    $_data .= '$' . $attribute['relationship']['relationship'] . ' = ' . $modelName . "::LP()->get();\n";
                } else { // Construct the code snippet for fetching all records
                    $_data .= '$' . $attribute['relationship']['relationship'] . ' = ' . $modelName . "::all();\n";
                }

                // Generate the import statement for the model
                $_data_imports .= "use App\Models\\" . $modelName . ";\n ";
            }
        }

        return [
            'passable_' => $passable_,
            '_data' => $_data,
            '_data_imports' => $_data_imports,
        ];
    }

    protected function generateValidationRules($attributes, $validations)
    {
        // Filter out excluded attributes
        $attributes = $this->filterExclusion($attributes, ['is_visible']);

        $validationRules = [];

        foreach ($attributes as $attribute) {
            $val = $attribute['validations'];

            // Determine if the field is nullable or required
            $condition = $val['nullable'] ? 'nullable' : 'required';

            // Add unique validation if applicable
            if ($val['unique']) {
                $condition .= '|unique:' . $this->getTableNameFromForeignKey($attribute['attribute']) . ',' . $attribute['attribute'];
            }

            // Add datatype-specific validation if applicable
            if (in_array($attribute['datatype'], ['integer', 'decimal'])) {
                $condition .= '|' . $attribute['datatype'];
            }

            // Format the rule with the attribute name and condition
            $rule = "'{$attribute['attribute']}' => '$condition',";
            $validationRules[] = $rule;
        }

        // Join the rules with new lines and wrap them in square brackets
        return "[\n" . implode("\n", $validationRules) . "\n]";
    }

    protected function filterExclusion($attributes, $exception = [], $additional = [])
    {
        $filtered = [];
        foreach ($attributes as $attribute) {
            $excluded = false;

            //Check if the attribute is in the global exclusions list
            if (in_array($attribute['attribute'], $this->EXCLUSIONS)) {
                $excluded = true;
            }

            //Check if the attribute is in the exceptions list
            if (in_array($attribute['attribute'], $exception)) {
                $excluded = false;
            }

            //Check if the attribute is in the additional exclusions list
            if (in_array($attribute['attribute'], $additional)) {
                $excluded = true;
            }

            //Add the attribute to the filtered array if it's not excluded
            if (!$excluded) {
                $filtered[] = $attribute;
            }
        }

        return $filtered;
    }



    public function variableName($text)
    {
        // Add a space before capital letters (except the first letter)
        $text = preg_replace('/(?<!^)([A-Z])/', ' $1', $text);
        // Capitalize the first letter
        $text = strtolower($text);

        $text = str_replace(' ', '_', $text);

        return $text;
    }

    public function displayName($text)
    {
        // Add a space before capital letters (except the first letter)
        $text = preg_replace('/(?<!^)([A-Z])/', ' $1', $text);
        // Capitalize the first letter
        $text = ucfirst($text);

        return $text;
    }

    public function generateAttributeBind($attributes_full)
    {
        $code = '';
        foreach ($attributes_full as $attribute) {
            if (in_array($attribute['attribute'], $this->EXCLUSIONS))
                # Exclusions
                continue;
            elseif (in_array($attribute['attribute'], $this->COMMENTED_OUT)) {
                # Commented out
                $code .= "\n\n// public \${$attribute};";
                continue;
            } else
                $code .= "\n \$this->_m->{$attribute['attribute']} = \$data['{$attribute['attribute']}'];";
        }
        // dd($code);

        return $code;
    }

    protected function generateViews($class)
    {
        $view_name = $this->viewName($class);

        $folder = resource_path('views/pages/backend/' . $view_name);

        // Create Module folder if it does not exist
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        if (!in_array($class, ['Role', 'Permissions'])) {
            $migrationFileName = $this->migrationExists($class);
            if (!$migrationFileName) {
                $this->error("Migration file not found for model '[$class]'");

                return;
            }
            $attributes = $this->extractAttributesFromMigration($migrationFileName);
        } else {
            $this->warn('Running Special case: ' . $class);
            $attributes = [];
        }

        //Index
        $this->createIndexView($view_name, $attributes, $class);
        //Create
        $this->createCreateEditView($view_name, $attributes, $class);
        //Show
        $this->createShowView($view_name, $attributes, $class);

        return true;
    }

    protected function createIndexView($view_name, $form_data, $class)
    {

        //#index
        $view = 'index';

        //Check if file exists
        $file = resource_path('views/pages/backend/' . $view_name . '/' . $view_name . '-' . $view . '.blade.php');

        if (file_exists($file)) {
            $confirm = $this->confirm('[' . $view . '] View for [' . $class . '] Exists. Override?', true);
            if (!$confirm) {
                return 0;
            }
        }

        $stubPath = $this->getStubPath($view, $class, view: true);
        $stub = file_get_contents($stubPath);

        //Replacements
        $thead = '';
        $tbody = '';
        $form_data = $this->filterExclusion($form_data);

        foreach ($form_data as $attribute) {
            $value = $attribute['attribute'];
            $name = $this->formatName($value);

            //#Table
            //THead
            $thead .= "\n<th class='align-middle'>{$name}</th>";

            //TBody
            if (!empty($attribute['relationship'])) {
                $rel = Str::singular($attribute['relationship']['relationship']);
                $tbody .= "\n<td class='align-middle py-0'>{{optional(\$data->{$rel})->name}}</td>";
            } else {
                if (in_array($attribute['attribute'], ['active', 'is_active']))
                    $tbody .= "\n<td class='align-middle'>@recordStatus(\$data->active)</td>";
                else
                    $tbody .= "\n<td class='align-middle'>{{\$data->$value ?? '--'}}</td>";
            }
        }

        $stub = str_replace('{class}', $this->viewName($class), $stub);
        $stub = str_replace('{thead}', $thead, $stub);
        $stub = str_replace('{tbody}', $tbody, $stub);

        //Write the modified stub content to the new file
        $result = file_put_contents($file, $stub);
        if ($result !== false) {
            $this->info("View [$view] created successfully.");
        } else {
            $this->info("Error [$view] Creation failed.");
        }
    }

    public function formatName($attribute)
    {
        return str_replace(' id', '', str_replace('_', ' ', ucwords($attribute)));
    }

    protected function createCreateEditView($view_name, $form_data, $class)
    {
        //#index
        $view = 'create';

        //Check if file exists
        $file = resource_path('views/pages/backend/' . $view_name . '/' . $view_name . '-' . $view . '.blade.php');

        if (file_exists($file)) {
            $confirm = $this->confirm('[' . $view . '] View for [' . $class . '] Exists. Override?', true);
            if (!$confirm) {
                return 0;
            }
        }

        $stubPath = $this->getStubPath($view, $class, view: true);
        $stub = file_get_contents($stubPath);

        //Replacements
        $code = '';
        $form_data = $this->filterExclusion($form_data);

        foreach ($form_data as $attribute) {
            $value = $attribute['attribute'];
            $name = $this->formatName($attribute['attribute']);

            $datatype = $this->getDataType($attribute['datatype']);

            //Default value
            if ($attribute['datatype'] == 'boolean') {
                $val = 'true';
            } else {
                $val = 'null';
            }

            // Relationship
            if (!empty($attribute['relationship'])) {
                $option = '$' . $attribute['relationship']['relationship'];
            } else {
                $option = '[]';
            }

            $code .= "\n <x-scrud::dynamics.forms.input col='4' model='{$attribute['attribute']}' type='{$datatype}' label='{$name}'  :option='{$option}' value=\"{{ isset(\$data) ? \$data->{$value} : old('{$value}')  }}\"/>";
        }
        $stub = str_replace('{{formBind}}', $code, $stub);
        $stub = str_replace('{class}', $this->viewName($class), $stub);

        //Write the modified stub content to the new file
        $result = file_put_contents($file, $stub);

        if ($result !== false) {
            $this->info("View [$view] created successfully.");
        } else {
            $this->info("Error [$view] creating View.");
        }
    }

    public function getDataType($datatype)
    {
        if (in_array($datatype, ['foreignId', 'foreign', 'unsignedBigInteger'])) {
            $type = 'select';
        } elseif (in_array($datatype, ['string'])) {
            $type = 'text';
        } elseif ($datatype === 'boolean') {
            $type = 'checkbox';
        } elseif ($datatype === 'longText') {
            $type = 'textarea';
        } elseif ($datatype === 'dateTime') {
            $type = 'date';
        } elseif ($datatype === 'time') {
            $type = 'time';
        } elseif (in_array($datatype, ['integer', 'decimal', 'float', 'double'])) {
            $type = 'number';
        } else {
            $type = $datatype;
        }

        return $type;
    }

    protected function createShowView($view_name, $form_data, $class)
    {

        //#index
        $view = 'show';

        //Check if file exists
        $file = resource_path('views/pages/backend/' . $view_name . '/' . $view_name . '-' . $view . '.blade.php');

        if (file_exists($file)) {
            $confirm = $this->confirm('[' . $view . '] View for [' . $class . '] Exists. Override?', true);
            if (!$confirm) {
                return 0;
            }
        }

        $stubPath = $this->getStubPath($view, $class, view: true);
        $stub = file_get_contents($stubPath);

        //Replacements
        //#----##
        $code = '';
        $form_data = $this->filterExclusion($form_data);

        foreach ($form_data as $attribute) {
            $value = $attribute['attribute'];
            $name = $this->formatName($attribute['attribute']);

            $code .= "\n<div class='col-md-3'>";
            $code .= "\n<div class='form-group'>";
            $code .= "\n<label class='fw-bold'>{$name}</label>";
            $code .= "\n<p>";

            if (!empty($attribute['relationship'])) {
                $rel = Str::singular($attribute['relationship']['relationship']);
                $code .= "\n<span class=''>{{\$data->{$rel}->name}}</span>";
            } else {
                $code .= "\n<span class=''>{{\$data->{$value}}}</span>";
            }
            $code .= "\n</p>";
            $code .= "\n</div>";
            $code .= "\n</div>";
        }

        $stub = str_replace('{{showBind}}', $code, $stub);
        $stub = str_replace('{class}', $this->viewName($class), $stub);

        //Write the modified stub content to the new file
        $result = file_put_contents($file, $stub);

        if ($result !== false) {
            $this->info("View [$view] created successfully.");
        } else {
            $this->info("Error [$view] creating View.");
        }
    }

    public function generateSideMenus($class)
    {
        // Choose Icon Set
        $icon = 'bx bx-home'; // Default icon set.
        try {
            //check if a module category exists else add it to the system
            $module_category = new SystemModuleCategory;
            $module_category = $module_category->whereId(1)->first();
            if (!$module_category) {
                $this->registerModuleCategory();
            }
            $module = $this->registerModule($class);

            //Check dependencies;
            $this->checkDependencies();
            //Check database for the name
            $menu = new Menu;
            if (!is_null($menu->where('name', $this->displayName($class))->first())) {
                $this->warn('Side Menu already exists');

                return 1;
            }

            // Add menu to database
            $menu = $menu->create([
                'system_module_id' => $module->id,
                'name' => $this->displayName($class),
                'icon' => $icon,
                'route' => $this->viewName($class) . '.index',
                'description' => $this->viewName($class) . ' menu',
                // 'show_sub_menus' => $this->show_sub_menus,
            ]);

            $default_sub_menus = ['index', 'create'];
            foreach ($default_sub_menus as $sub_menu) {
                //Create Sub Menus
                SubMenu::create([
                    'menu_id' => $menu->id,
                    'name' => $this->viewName($sub_menu),
                    'route' => $this->viewName($class) . '.' . $sub_menu,
                    'icon' => $icon,
                    'description' => $this->viewName($class) . ' menu',
                ]);
            }

            $this->info('');
            $this->info('Side menus generated successfully.');
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
            $this->error('Side Menu Generation encountered an issue');
            Log::error($e->getMessage());
        }
    }

    protected function registerModule($class)
    {
        $system_module = new SystemModule;

        try {
            return $system_module->firstOrCreate(['name' => $class,], ['system_module_category_id' => 1, 'is_active' => true]);
        } catch (\Exception $e) {
//            dd($e->getCode());
            if ($e->getCode() == '42S02') $this->warn('MODULE NOT REGISTERED: ' . $e->getMessage());

            return 0;
        }
    }

    /**
     * Register Module in the Database
     */
    protected function registerModuleCategory()
    {
        $module_category = new SystemModuleCategory;

        try {
            return $module_category->create(['name' => 'Pages', 'position' => 50, 'is_active' => true]);
        } catch (\Exception $e) {
            dd('here2');
            $this->warn('MODULE CATEGORY NOT REGISTERED: ' . $e->getMessage());

            return 0;
        }
    }

    protected function registerRoutes($class)
    {
        $stub = $this->getStubPath('route');

        $routePath = __DIR__ . '/../' . $this->routePath;
        if (!file_exists($routePath)) {
            //copy from stub
            $this->warn('Route file not found. Initializing');
            $result = copy($stub, $routePath);
            if ($result) {
                $this->info('Route file created successfully.');
            } else {
                return $this->error('Route file creation failed.');
            }
        }
        // Read the contents of the web.php file
        $contents = file_get_contents($routePath);

        // Find the position of the marker
        $marker = '##--GENERATED ROUTES--##';
        $pos = strpos($contents, $marker);

        // Define the resource route
        $resourceRoute = "Route::resource('" . $this->viewName($class) . "', '" . $class . "Controller');";

        // Check if the marker is found
        if ($pos !== false) {
            // Check if the resource route already exists after the marker
            $routePos = strpos($contents, $resourceRoute, $pos);

            if ($routePos === false) {
                // Insert the resource route after the marker with a newline
                $newContents = substr_replace($contents, "\n" . $resourceRoute, $pos + strlen($marker), 0);
            } else {
                $this->warn("Resource route for '{$class}' already exists in web.php.");

                return 1;
            }
        } else {
            // Marker not found in the web.php file. Create marker and add routes at the end of the file.
            $this->info('Marker not found in the web.php file. Creating marker and adding routes at the end of the file...');
            $newContents = rtrim($contents); // Remove trailing whitespaces

            // Add marker and resource route at the end of the file
            $newContents .= "\n\n" . $marker . "\n" . $resourceRoute;
        }

        // Write the modified contents back to the web.php file
        file_put_contents($routePath, $newContents);

        if (file_exists(base_path('/routes/scrud.php'))) {
            file_put_contents(base_path('/routes/scrud.php'), $newContents);
        }

        $this->info('Resource route added successfully.');

        return 1;
    }

    public function isPackageInstalled($packageName)
    {
        $installedPackages = json_decode(File::get(base_path('vendor/composer/installed.json')), true);

        foreach ($installedPackages as $content) {
            if (!is_array($content)) continue;

            if ($package = 'packages') {
                foreach ($content as $p) {
                    if (!is_array($p)) {
                        continue;
                    }
                    Log::info('Found: ' . $p['name']);
                    if ($p['name'] === $packageName) {
                        return true;
                    }
                }
            }
        }

        return 0;
    }

    private function installJetstream()
    {
        $command = 'composer require laravel/jetstream';
        $this->executeCommand($command);

        // Other installation steps
    }

    private function installImpersonate()
    {
        $command = 'composer require lab404/laravel-impersonate';
        $this->executeCommand($command);

        // Add service provider -> To package
        $added = $this->confirm('Add Service Provider [Lab404\Impersonate\ImpersonateServiceProvider::class,] to your list of providers...', true);

        if ($added) {
            $modelAdded = $this->confirm('Add trait [Lab404\Impersonate\Models\Impersonate] to User Model...', true);
            if ($modelAdded) {
                // Add necessary functions to the User model
                $file = file_get_contents(app_path('Models/User.php'));

                $newContent = "\n\npublic function canImpersonate() : bool\n";
                $newContent .= "{\n";
                $newContent .= "    return \$this->is_maintainer();\n";
                $newContent .= "}\n\n";

                $newContent .= "public function canBeImpersonated() : bool\n";
                $newContent .= "{\n";
                $newContent .= "    return ! \$this->is_maintainer();\n";
                $newContent .= "}\n\n";

                $newContent .= "public function is_maintainer()\n";
                $newContent .= "{\n";
                $newContent .= "    return \$this->hasRole('_Maintainer');\n";
                $newContent .= "}\n";

                // Insert the new content just above the last }
                $position = strrpos($file, '}');
                $file = substr_replace($file, $newContent, $position, 0);

                file_put_contents(app_path('Models/User.php'), $file);

                //                #give permission to impersonate;
                // Publish Assets
                $this->call('vendor:publish', ['--tag' => 'impersonate']);

                return 1;
            } else {
                return 0;
            }
        } else {
            $this->warn('The installation was interrupted, please follow the Lab404/laravel-impersonate Documentation to complete it.');
        }
    }

    public function executeCommand($command)
    {
        // Open a process for the command
        $descriptors = [
            0 => ['pipe', 'r'], // stdin
            1 => ['pipe', 'w'], // stdout
            2 => ['pipe', 'w'], // stderr
        ];

        $process = proc_open($command, $descriptors, $pipes);

        if (is_resource($process)) {
            // Read the output from the process line by line
            while ($line = fgets($pipes[1])) {
                echo $line; // Output the line
                flush(); // Flush the output buffer to display in real-time
            }

            fclose($pipes[0]);
            fclose($pipes[1]);
            fclose($pipes[2]);

            // Close the process
            $returnValue = proc_close($process);

            // Check if the return value indicates success
            return $returnValue === 0;
        }

        return 0;
    }

    private function installSpatie()
    {
        $command = 'composer require spatie/laravel-permission';
        $this->executeCommand($command);

        // Other installation steps
    }

    private function generateViewDependencies()
    {
        $dependencies = [
            'SystemModuleCategory', 'Role', 'SystemModule', 'Menu', 'SubMenu', 'Permission',
        ];

        foreach ($dependencies as $class) {
            $directoryExists = File::exists(resource_path("views/pages/backend/{$this->viewName($class)}"));
            if (!$directoryExists) {
                $this->warn("{$class} View Dependency not found. Generating...");
                $this->generateController($class);
                $this->generateViews($class);
                $this->generateSideMenus($class);
                $this->registerRoutes($class);
            }
        }
    }

    public function generatePermissions($class)
    {
        //Create Permissions for the Item
        $default_permissions = ['index', 'show', 'create', 'update', 'destroy'];

        $permissions = [];
        foreach ($default_permissions as $permission) {
            $permissions[] = Str::plural(strtolower($class)) . '.' . $permission;
        }

        $this->info('');
        $this->info('Creating Permissions...');
        foreach ($permissions as $p) {
            try {
                Permission::create(['name' => $p]);
            } catch (\Exception $e) {
                $this->warn($e->getMessage());
            }
        }

        try {
            //impersonate permission
            Permission::create(['name' => 'impersonate']);
        } catch (\Exception $e) {
            //            $this->warn($e->getMessage());
        }
        $this->info('Permissions Created');

        //Assign the permissions
        $this->info('');
        $this->info('Assigning [' . $class . '] Permissions...');
        $this->assignPermissionsToDefaultRoles($permissions);
        $this->info('[' . $class . '] permissions assigned');
    }

    /**
     * Assign Permissions to default Roles
     */
    public function assignPermissionsToDefaultRoles($permissions)
    {
        $role = $this->getDefaultRole();

        //check if role can already impersonate
        if (!$role->hasPermissionTo('impersonate')) {
            $permissions[] = 'impersonate';
        }
        $role->givePermissionTo($permissions);

        return 1;
    }

    public function getDefaultRole()
    {
        //Create default  for the Item
        $role = new Role;

        $this->info('');
        // Create a new role with the name 'Admin_Default' if not existing
        $role = Role::firstOrCreate(['name' => '_Maintainer']);

        return $role;
    }

    public function generateRolesAndPermissionsView()
    {
    }

    protected function generateAttributeStrings($attributes)
    {
        $attributes = $this->filterExclusion($attributes);
        $attributeStrings = [];

        foreach ($attributes as $attribute) {
            $attributeStrings[] = "'{$attribute['attribute']}'";
        }

        return implode(', ', $attributeStrings);
    }
}
