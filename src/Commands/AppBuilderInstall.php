<?php

namespace Wovosoft\AppBuilder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AppBuilderInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'builder:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs Required resources of App Builder Package';

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
        if (!File::exists(storage_path("app/appbuilder/records.json"))) {
            File::makeDirectory(storage_path("app/appbuilder"));
        }

        $this->info("Module Recorder Created Successfully!");

        File::copyDirectory(__DIR__ . "/../../stubs/Assets", app_path("Assets"));
        $this->info("Assets copied successfully");

        File::copyDirectory(__DIR__ . "/../../stubs/Traits", app_path("Traits"));
        $this->info("Traits copied successfully");

        if (!File::exists(base_path("stubs"))) {
            File::makeDirectory(base_path("stubs"));
        }
        File::copy(__DIR__ . "/../../stubs/controller.module.stub", base_path("stubs/controller.module.stub"));
        File::copy(__DIR__ . "/../../stubs/model.stub", base_path("stubs/model.stub"));
        $this->info("Controller & Model stubs are copied successfully");
        File::copyDirectory(__DIR__ . "/../../stubs/vue_files", base_path("stubs/vue_files"));
        $this->info("Assets copied successfully");

        File::copyDirectory(__DIR__ . "/../../stubs/Helpers", app_path("Helpers"));
        $this->info("Helpers copied successfully");

        File::copyDirectory(__DIR__ . "/../../stubs/Models", app_path("Models"));
        $this->info("Models copied successfully");

        File::copyDirectory(__DIR__ . "/../../stubs/database", base_path("database"));
        $this->info("Database Files copied successfully");

        File::copy(__DIR__ . "/../../stubs/Providers/AuthServiceProvider.php", app_path("Providers/AuthServiceProvider.php"));
        $this->info("AuthServiceProvider.php updated");

        File::copyDirectory(__DIR__ . "/../../stubs/Http", app_path("Http"));
        $this->info("Http Files Copied Added");

        File::copyDirectory(__DIR__ . "/../../stubs/routes", base_path("routes"));
        $this->info("Routes Copied Added");

        File::copyDirectory(__DIR__ . "/../../stubs/js", resource_path("js"));
        File::copyDirectory(__DIR__ . "/../../stubs/css", resource_path("css"));
        $this->info("Vue application scaffolded");

        File::copy(__DIR__ . "/../../stubs/package.json", base_path("package.json"));
        $this->info("package.json updated");

        File::append(base_path("webpack.mix.js"),
            "mix
                    .sass('resources/css/admin.scss', 'public/css')
                    .js('resources/js/admin.js', 'public/js')
                    .version()
                    .vue();"
        );


        $composer = json_decode(File::get(base_path("composer.json")), true);
        $composer["autoload"]["files"] = [
            "app/Helpers/functions.php"
        ];

        $composer = Str::of(json_encode($composer, JSON_PRETTY_PRINT))->replace("\/", "/");

        File::put(base_path("composer.json"), $composer);
        $this->info("helpers inserted in composer.json file");


        File::put(storage_path("app/appbuilder/records.json"), json_encode([
            "modules" => [],
            "assets" => [
                "Datatable" => "app/Assets/Datatable.php",
                "Utils" => "app/Assets/Utils.php",
                "ControllerTraits" => "app/Traits/ControllerTraits.php",
                "UserTrait" => "app/Traits/UserTrait.php"
            ],
            "helpers" => [
                "functions" => "app/Helpers/functions.php"
            ],
            "stubs" => [
                "controller" => "stubs/controller.module.stub",
                "model" => "stubs/model.stub",
                "vue_files" => "stubs/vue_files"
            ]
        ], JSON_PRETTY_PRINT));

        $this->info("ALL DONE");
        $this->table(["Now run following Commands"], [
            ["yarn install"],
            ["composer install"],
            ["composer dump-autoload"],
            ["php artisan storage:link"],
        ]);
        return 0;
    }
}
