<?php namespace App\Bootstrap;

use Illuminate\Log\Writer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\ConfigureLogging as BaseLoggingConfiguration;

class ConfigureLogging extends BaseLoggingConfiguration {

    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected function configureSingleHandler(Application $app, Writer $log)
    {
        //sets the path to custom app/log/single-xxxx-xx-xx.log file.
        $log->useFiles(base_path() . '/log/single.log');
    }

    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected function configureDailyHandler(Application $app, Writer $log)
    {
        //sets the path to custom app/log/daily-xxxx-xx-xx.log file.
        $log->useDailyFiles(base_path() . '/logs/daily.log', 5);
    }

}