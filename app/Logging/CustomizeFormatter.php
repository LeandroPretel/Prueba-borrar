<?php

namespace App\Logging;

use Illuminate\Log\Logger;
use Monolog\Formatter\LineFormatter;

class CustomizeFormatter
{
    /**
     * Customize the given logger instance.
     *
     * @param Logger $logger
     * @return void
     */
    public function __invoke(Logger $logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new LineFormatter(
                '%level_name%: %message%'
            ));
            // '[%datetime%] %channel%.%level_name%: %message% %context% %extra%'
        }
    }
}
