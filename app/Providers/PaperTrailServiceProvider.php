<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\SyslogUdpHandler;

class PaperTrailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $monolog = Log::getLogger();
        $syslogHandler = new SyslogUdpHandler(config('papertrail.PAPERTRAIL_URL'), config('papertrail.PAPERTRAIL_PORT'));

        // $formatter = new LineFormatter('%channel%.%level_name%: %message% %extra%');
        $formatter = new LineFormatter('%level_name%: %message%');
        $syslogHandler->setFormatter($formatter);

        $monolog->pushHandler($syslogHandler);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
