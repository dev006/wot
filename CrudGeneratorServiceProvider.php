<?php

namespace Wot\CrudGenerator;

use Illuminate\Support\ServiceProvider;

class CrudGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(
            'Wot\CrudGenerator\Commands\CrudCommand',
            'Wot\CrudGenerator\Commands\CrudControllerCommand',
            'Wot\CrudGenerator\Commands\CrudModelCommand',
            'Wot\CrudGenerator\Commands\CrudMigrationCommand',
            'Wot\CrudGenerator\Commands\CrudViewCommand'
        );
    }
}
