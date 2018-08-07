<?php

namespace Wot\CrudGenerator\Commands;

use File;
use Illuminate\Console\Command;

class CrudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generate
                            {name : The name of the Crud.}
                            {--fields= : Fields name for the form & model.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Crud.';

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
     * @return mixed
     */
    public function handle()
    {

        $name = $this->argument('name');

        $controllerNamespace = '';

        if ($this->option('fields')) {
            $fields = $this->option('fields');
            $viewPath = '';

            $fillableArray = explode(',', $fields);
            foreach ($fillableArray as $value) {
                $data[] = preg_replace("/(.*?):(.*)/", "$1", trim($value));
            }

            $commaSeparetedString = implode("', '", $data);
            $fillable = "['" . $commaSeparetedString . "']";

            $this->call('crud:controller', ['name' => $controllerNamespace . $name . 'Controller', '--crud-name' => $name, '--view-path' => $viewPath]);
            $this->call('crud:model', ['name' => $name, '--fillable' => $fillable, '--table' => str_plural(strtolower($name))]);
            $this->call('crud:migration', ['name' => str_plural(strtolower($name)), '--schema' => $fields]);
            $this->call('crud:view', ['name' => $name, '--fields' => $fields, '--view-path' => $viewPath]);
        } 

        $routeFile = base_path('routes/web.php');
        if (file_exists($routeFile)) {
            $controller = ($controllerNamespace != '') ? $controllerNamespace . '\\' . $name . 'Controller' : $name . 'Controller';

            $isAdded = File::append($routeFile, "\nRoute::resource('" . strtolower($name) . "', '" . $controller . "');");
            if ($isAdded) {
                $this->info('Crud/Resource route added to ' . $routeFile);
            } else {
                $this->info('Unable to add the route to ' . $routeFile);
            }
        }
    }
}
