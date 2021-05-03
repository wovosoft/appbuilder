<?php

namespace Wovosoft\AppBuilder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class AppBuilderMake extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'builder:make_module
            {name : Name of the Module. It can be nested.}
            {prefix? : Route URL Prefix. Each route url will be prefixed by this value.}
            {group? : Route Group Name}
            {model? : Name of the Model. Not the Full Location. If Model doesn\'t exits it will be created}
            {select? : Columns to be selected in datatable.}
            {fillable_columns? : Columns to be filled in store method}
            {add_modal_title=Add/Edit Details : Add Modal Title}
            {form_fields? : Instructions in https://github.com/spatie/html-element & Separated by comma}
            {datatable_fields=id,action : Datatable Fields}
            {datatable_appends=id : Datatable Appends}
            {--rw=1 : Add Routes in web.php?}
            {--mi=1 : Add Module In routes/index.js?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates New Module';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $name = $this->argument("name");

        $records = json_decode(File::get(storage_path("app/appbuilder/records.json")), true);
        if (isset($records['modules'][$name])) {
            $this->info("Module $name already Exists.");
            return 0;
        }


        $this->info("Creating Model, Controller, Migration, Seeder and Factory");

        Artisan::call("make:model", [
            "name" => $name,
            "--migration" => true,
            "--factory" => true,
            "--seed" => true
        ]);
        echo Artisan::output();

        Artisan::call("make:controller", [
            "name" => $name . "Controller",
            "--type" => "module"
        ]);
        echo Artisan::output();

        if (!File::exists(storage_path("app/appbuilder/records.json"))) {
            File::makeDirectory(storage_path("app/appbuilder"));
            File::put(storage_path("app/appbuilder/records.json"), json_encode([
                "modules" => []
            ]));
        }

        $record = json_decode(File::get(storage_path("app/appbuilder/records.json")), true);

        $record['modules'][$name] = [
            "controller" => "app/Http/Controllers/" . $name . "Controller.php",
            "model" => "app/Models/" . $name . ".php",
            "factory" => "database/factories/{$name}Factory.php",
            "seed" => "database/seeders/" . basename($name) . "Seeder.php",
            "migration" => "database/migrations/" . $this->getMigrationFileName(),
            "vue_module" => "resources/js/pages/" . Str::plural(strtolower($name))
        ];

        File::put(storage_path("app/appbuilder/records.json"), json_encode($record, JSON_PRETTY_PRINT));

        $prefix = Str::plural($this->argument('name') ?? Str::of($name)->lower()->trim());
        $group = Str::plural($this->argument('group') ?? Str::of($name)->lower()->trim());
        $model = $this->argument('model') ?? $name;
        $select = $this->argument('select') ?? "*";
        $fillable_columns = $this->argument('fillable_columns') ?? "id,name";
        $addModalTitle = $this->argument("add_modal_title");
        $formFields = $this->argument('form_fields') ?? "b-form-group[label=Testing]>b-input[v-model=currentItem.test=4]";
        $datatableFields = $this->argument('datatable_fields');
        $appends = $this->argument('datatable_appends');


        $this
            ->put(
                $record['modules'][$name]['controller'],
                $this
                    ->replaceMatches(Str::of(File::get($record['modules'][$name]['controller'])), [
                        "/{{ ROUTE_PREFIX }}/" => $prefix,
                        "/{{ GROUP_NAME }}/" => $group,
                        "/{{ MODEL }}/" => "\\App\\Models\\" . str_replace("/", "\\", $model),
                        "/{{ SELECTED_COLUMNS }}/" => json_encode(explode(",", $select)),
                        "/{{ OTHER_QUERIES }}/" => $this->otherQueries($select)
                    ])
                    ->replace("//FILL_ABLE_COLUMNS", $fillable_columns ? $this->fillableColumns($fillable_columns) : "")
            );

        if ($this->option("rw") == "1" || $this->option("rw") == 1 || $this->option("rw") == true) {
            $this::put(
                base_path("routes/web.php"),
                Str::of(File::get(base_path("routes/web.php")))
                    ->replace(
                        "//BACKEND_ROUTES_ADDED_BY_GENERATOR",
                        "\\App\\Http\\Controllers\\{$name}Controller::routes();" .
                        "\n\t\t//BACKEND_ROUTES_ADDED_BY_GENERATOR"
                    )
            );
        }

        $this->info("Generating Vue Files...");
        File::copyDirectory(
            base_path("stubs/vue_files/module/full"),
            resource_path("js/pages/" . Str::plural(strtolower($name)))
        );

        $addFormPath = resource_path("js/pages/" . Str::plural(strtolower($name)) . "/Add.vue");

        $this
            ->put(
                $addFormPath,
                Str::of(File::get($addFormPath))
                    ->replace("STORE_URL", "/$prefix/store")
                    ->replace("ADD_EDIT_DETAILS_TITLE", $addModalTitle)
                    ->replace("FORM_FIELDS", (function () use ($formFields) {
                        $form = "";
                        $this->info("Instructions in https://github.com/spatie/html-element & Separated by comma");
                        foreach (explode("|", $formFields) as $f) {
                            $form .= $this->el(...explode(",", $f));
                        }
                        return $form;
                    })())
            );

        $IndexPath = resource_path("js/pages/" . Str::plural(strtolower($name)) . "/Index.vue");

        $this
            ->put(
                $IndexPath,
                Str::of(File::get($IndexPath))
                    ->replace("API_URL", "/$prefix")
                    ->replace("FIELDS", (function ($items) {
                            $ff = [];
                            foreach ($items as $item) {
                                if ($item !== 'action') {
                                    $ff[] = [
                                        "key" => $item,
                                        "sortable" => true,
                                        "visible" => true
                                    ];
                                } else {
                                    $ff[] = [
                                        "key" => $item,
                                        "sortable" => false,
                                        "visible" => true,
                                        "thClass" => "text-right",
                                        "tdClass" => "text-right"
                                    ];
                                }
                            }
                            return json_encode($ff);
                        })(explode(",", $datatableFields))
                    )
                    ->replace("APPENDS", (function ($items) {
                            $appends = [];
                            foreach ($items as $item) {
                                $appends[$item] = null;
                            }
                            return json_encode($appends);
                        })(explode(",", $appends))
                    )
            );


        if ($this->option('mi') == "1" || $this->option("mi") == 1 || $this->option("mi") == true) {
            if (File::exists(resource_path("js/routes/index.js"))) {
                $this->put(
                    resource_path("js/routes/index.js"),
                    Str::of(File::get(resource_path("js/routes/index.js")))
                        ->replace(
                            "//ROUTES_PLACEHOLDER_USED_BY_GENERATOR",
                            "const " . $name . " = () => import(\"../pages/" . Str::plural(strtolower($name)) . "/Index\");" .
                            "\n//ROUTES_PLACEHOLDER_USED_BY_GENERATOR"
                        )
                );
            }
        }

        return 0;
    }

    private function getMigrationFileName(): string
    {
        $files = File::files(database_path("migrations"));
        return $files[count($files) - 1]->getFilename();
    }

    private function el(string $tag, $attributes = null, $content = null): string
    {
        return \Spatie\HtmlElement\HtmlElement::render(...func_get_args());
    }

    private function replaceMatches(Stringable $content, array $options): Stringable
    {
        foreach ($options as $pattern => $value) {
            $content = $content->replaceMatches($pattern, $value);
        }
        return $content;
    }

    private function put(string $path, string $content)
    {
        File::put($path, $content);
    }

    private function otherQueries(string $select): string
    {
        $other_queries = "";
        if ($select !== "['*']") {
            $other_queries .= "\$builder";
            foreach (explode(",", $select) as $col) {
                if (trim($col) !== 'id') {
                    $other_queries .= "\n\t\t\t\t\t\t->orWhere('$col', 'LIKE', '%' . \$request->post('filter') . '%')";
                }
            }
            $other_queries .= ";";
        }
        return $other_queries;
    }

    private function fillableColumns(string $fillable_columns)
    {
        $fillable_columns = explode(",", $fillable_columns);
        $fc = "";
        $count = 1;
        foreach ($fillable_columns as $fillable_column) {
            if ($count == 1) {
                $fc .= "'$fillable_column'=>\$request->post('$fillable_column'),";
            } else {
                $fc .= "\n\t\t\t\t'$fillable_column'=>\$request->post('$fillable_column'),";
            }
            $count++;
        }
        return $fc;
    }
}
