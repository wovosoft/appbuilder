<?php

namespace Wovosoft\AppBuilder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AppBuilderDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'builder:delete_module {name : Module Name, should be same as created value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes A Module';

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
            return "Module Record isn't available";
        }
        $records = json_decode(File::get(storage_path("app/appbuilder/records.json")), true);
        foreach ($records["modules"][$this->argument("name")] as $key => $path) {
            if (File::isDirectory(base_path($path))) {
                File::deleteDirectory(base_path($path));
                $this->info("$key deleted successfully!");
            } else if (File::exists(base_path($path))) {
                File::delete(base_path($path));
                $this->info("$key deleted successfully!");
            }

        }
        unset($records["modules"][$this->argument("name")]);
        File::put(storage_path("app/appbuilder/records.json"), json_encode($records, JSON_PRETTY_PRINT));
        return 0;
    }
}
