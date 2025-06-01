<?php

namespace Sensy\Scrud\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Sensy\Scrud\Traits\CrudTrait;

class CreateApiDocumentation extends Command
{
    use CrudTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sensy-scrud:api-document';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the api documentation for CRUD from the migrations';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info("Documentation started...");
        $this->info("\nImporting migration files...");

        $migrations_files = File::allFiles(database_path('/migrations'));

        $excluded_files = [
            "2014_10_12_100000_create_password_reset_tokens_table.php",
            "2016_06_01_000001_create_oauth_auth_codes_table.php",
            "2016_06_01_000002_create_oauth_access_tokens_table.php",
            "2016_06_01_000003_create_oauth_refresh_tokens_table.php",
            "2016_06_01_000004_create_oauth_clients_table.php",
            "2016_06_01_000005_create_oauth_personal_access_clients_table.php",
            "2019_08_19_000000_create_failed_jobs_table.php",
            "2019_12_14_000001_create_personal_access_tokens_table.php",
        ];

        // Filter out the files matching the exclude pattern
        $filter_excluded_files = array_filter($migrations_files, function ($file) use ($excluded_files)
        {
            return !in_array($file->getFilename(), $excluded_files);
        });

        $filter_files_without_include_pattern = array_filter($filter_excluded_files, function ($file)
        {
            return stripos($file->getFilename(), 'create') !== false;
        });

        $items = [];

        foreach ($filter_files_without_include_pattern as $migration) {

            $migration_path = $migration->getPathname();
            $model = $this->extractName($migration_path);
            $results[] = $this->extractAttributesFromMigration($migration_path);


            foreach ($results as $result) {
                $attributes = [];

                // Get the attributes
                foreach ($result as $attribute => $value) {
                    if($value['attribute'] !== 'created_at' && $value['attribute'] !== 'updated_at' && $value['attribute'] !== 'deleted_at' && $value['attribute'] !== 'id' && $value['attribute'] !== 'active' && $value['attribute'] !== 'deactivated_by') {
                        $field = $value['attribute'];
                        $not_required = $value['validations']['nullable'];

                        if(!$not_required) {
                            $attributes = [...$attributes, $field => ""];
                        }

                    }
                }
            }

            $items = [...$items, $this->generateJsonArray($model, $attributes)];

            $this->info("\n$model module has been documented.");
        }

        $content = [
            "info" => [
                "_postman_id" => "7cb0c9a6-0767-4780-bc57-8e1aeb912407",
                "name" => config('app.name'),
                "schema" => "https://schema.getpostman.com/json/collection/v2.1.0/collection.json",
                "_exporter_id" => "14307129"
            ],
            "item" => $items
        ];

        $json_content =json_encode($content, JSON_PRETTY_PRINT);

        if(!File::exists(base_path('/doc'))){
            File::makeDirectory(base_path('/doc'));
            $this->info("\ndoc directory has been created...");
        }

        $files = File::files(base_path('/doc'));

        foreach ($files as $file) {
            if ($file->getExtension() === 'json') {
                File::delete($file->getPathname());
            }
        }

        File::put(base_path('/doc/api_doc.json'), $json_content);

        $this->info("\nApi documentation created successfully.");
    }

    function generateJsonArray($itemName, $attributes): array
    {
        // Define the possible values for name and their corresponding methods
        $methods = [
            'create' => 'POST',
            'index' => 'GET',
            'show' => 'GET',
            'update' => 'PUT',
            'delete' => 'DELETE'
        ];

        // Create the main item
        $item = [
            'name' => $itemName,
            'item' => []
        ];

        // Iterate over the methods to create item elements
        foreach (['create', 'index', 'show', 'update', 'delete'] as $action) {
            // Determine the HTTP method based on the action
            $method = $methods[$action] ?? 'GET';

            // Conditionally include attributes in raw content for 'create' and 'update'
            $rawContent = null;
            if (in_array($action, ['create', 'update'])) {
                $rawContent = json_encode($attributes, JSON_PRETTY_PRINT);
            }

            // Conditionally include id on show, delete and update
            $url = null;
            $formatted_module_name = Str::lower(Str::kebab(Str::plural($itemName)));

            if (in_array($action, ['show', 'update', 'delete'])) {
                $url = [
                    'raw' => "{{base_url}}/{$formatted_module_name}/:id",
                    'host' => [
                        '{{base_url}}'
                    ],
                    'path' => [
                        $formatted_module_name,
                        ":id"
                    ],
                    'variable' => [
                        "key" => "id",
                        "value" => "1"
                    ]
                ];
            } else {
                $url = [
                    'raw' => "{{base_url}}/{$formatted_module_name}",
                    'host' => [
                        '{{base_url}}'
                    ],
                    'path' => [
                        $formatted_module_name,
                    ]
                ];
            }

            // Create the item element
            $item['item'][] = [
                'name' => $action,
                'request' => [
                    'method' => $method,
                    'header' => [
                        [
                            'key' => 'Accept',
                            'value' => 'application/json',
                            'type' => 'text'
                        ]
                    ],
                    'body' => [
                        'mode' => 'raw',
                        'raw' => $rawContent,
                        'options' => [
                            'raw' => [
                                'language' => 'json'
                            ]
                        ]
                    ],
                    'url' => $url
                ],
                'response' => []
            ];
        }

        return $item;
    }

    private function extractName($migration_path): string
    {
        // Get the base name of the file
        $file_name = basename($migration_path);

        // Remove the timestamp and the file extension
        $file_nameParts = explode('_', $file_name);
        $model_name_parts = array_slice($file_nameParts, 4, -1); // Skip the first 4 parts (timestamp) and last part (extension)

        // Combine the remaining parts to form the model name
        $model_name = implode('_', $model_name_parts);

        // Singularize the model name (optional, assumes English language rules)
        $model_name = str_replace('create_', '', $model_name);
        $model_name = str_replace('_table', '', $model_name);

        // Capitalize the model name
        $model_name = ucwords(str_replace('_', ' ', $model_name));
        $model_name = str_replace(' ', '', $model_name);

        return Str::singular($model_name);
    }

//    public function extractAttributesFromMigration($migrationFile): array
//    {
//        # Get the content of the migration file
//        $content = file_get_contents($migrationFile);
//
//        # Match column definitions, validations, and relationships
//        preg_match_all('/\$table->([a-z_]+)\([\'|"]([a-zA-Z_]+)[\'|"],?([^)]*)\)/i', $content, $matches);
//
//        $data = [];
//        foreach ($matches[2] as $key => $match)
//        {
//            $attribute = (string) $match;
//
//            # Check if the attribute already exists in the array
//            $existingAttribute = array_search($attribute, array_column($data, 'attribute'));
//            if ($existingAttribute !== false)
//            {
//                # If the attribute already exists, update its relationship data
//                $data[$existingAttribute]['relationship'] = $this->extractRelationship($content, $attribute);
//            }
//            else
//            {
//                # If the attribute doesn't exist, add it to the array
//                $dataType     = (string) $matches[1][$key];
//                try{
//                    $validations  = $this->extractValidations($content, $attribute);
//                } catch (\Exception $e){
//                    dd($e, $migrationFile, $attribute);
//                }
//
//                $relationship = $this->extractRelationship($content, $attribute);
//
//                $data[] = [
//                    'attribute'    => $attribute,
//                    'datatype'     => $dataType,
//                    'validations'  => $validations,
//                    'relationship' => $relationship
//                ];
//            }
//        }
//
//        return $data ?? [];
//    }

//    private function extractValidations($content, $attribute)
//    {
//        // Define the regex pattern
//        $regex_pattern = '/.*\$table->[a-z_]+\([\'|"](' . $attribute . ')[\'|"],?(.*?)\).*/i';
//
//        // Perform the regex match
//        preg_match_all($regex_pattern, $content, $matches, PREG_SET_ORDER);
//        $validations = [];
//        foreach ($matches as $match) {
//            $method = $match[1];
//            $parameter = isset($match[2]) ? trim($match[2]) : '';
//            $nullable = strpos($match[0], '->nullable()') !== false;
//            $unique = strpos($match[0], '->unique()') !== false;
//            $otherValidations = isset($match[3]) ? $match[3] : '';
//
//            // Handle array parameters
//            if (strpos($parameter, '[') !== false) {
//                $parameter = str_replace(['[', ']'], '', $parameter);
//                $parameter = str_replace(['\'', '\"'], '', $parameter);
//            } else {
//                // Handle multiple parameters
//            }
//
//            // Construct validation array
//            $validations[] = [
//                'attribute' => $attribute,
//                'nullable' => $nullable,
//                'unique' => $unique,
//                'parameter' => $parameter,
//            ];
//
//            // Extracting additional validations with optional commas
//            preg_match_all('/([a-zA-Z_]+)(\(([^)]*)\))?/', $otherValidations, $additionalMatches, PREG_SET_ORDER);
//            $additionalValidations = [];
//
//            foreach ($additionalMatches as $additionalMatch) {
//                $validation = $additionalMatch[1];
//                $parameter = isset($additionalMatch[3]) ? $additionalMatch[3] : null;
//
//                // Handle array parameters
//                if (strpos($parameter, '[') !== false) {
//                    $parameter = str_replace(['[', ']'], '', $parameter);
//                    $parameter = explode(',', $parameter);
//                } else {
//                    // Handle multiple parameters
//                    $parameter = explode(',', $parameter);
//                }
//
//                $additionalValidations[$validation] = $parameter;
//
//                $validations['additional_validations'] = $additionalValidations;
//            }
//
//            try {
//                return $validations[0];
//            } catch (\Exception $e) {
//                dd($attribute, $content, $this->EXCLUSIONS);
//            }
//        }
//    }

//    private function extractRelationship($content, $attribute): array
//    {
//        $relationship = [];
//
//        // Define the simplified regex pattern
//        $regex_pattern = '/\$table->(?:foreignId|foreign)\(["\'](' . $attribute . ')["\']\)' .  // Match the attribute with single or double quotes
//            '(?:.*?->(?:references)\([\'"]([^\'"]+)?[\'"]\))?'     .                                                                        // Match optional references with its argument
//            '(?:.?->(?:constrained|on)\([\'"]([^\'"]+)?[\'"]\))' .                                                                        // Match optional methods and related table in any order
//            '/i';
//
//        // Perform the regex match
//        preg_match_all($regex_pattern, $content, $matches, PREG_SET_ORDER);
//        // Iterate over matches
//        foreach ($matches as $match)
//        {
//            # Get constrained table name
//            if (!isset($match[3]) || $match[3] == '')
//                $match[3] = $this->getTableNameFromForeignKey($attribute);
//
//            if (!isset($match[2]) || $match[2] == '')
//                $match[2] = 'id';
//
//            $relationship = [
//                'referenced'   => $match[2],
//                'constrained'  => $match[3],
//                'relationship' => lcfirst(str_replace('', '', ucwords($match[3], ''))),
//            ];
//        }
//        return $relationship;
//    }

}
