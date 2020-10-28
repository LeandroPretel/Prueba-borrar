<?php

namespace App\Jobs;

use App\Anfix\Exceptions\AnfixException;
use App\Anfix\Exceptions\AnfixResponseException;
use App\Http\Controllers\AnfixController;
use App\OrderReturn;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AnfixReturn implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $orderReturn;

    /**
     * Create a new job instance.
     *
     * @param OrderReturn $orderReturn
     */
    public function __construct(OrderReturn $orderReturn)
    {
        $this->orderReturn = $orderReturn;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws AnfixException
     * @throws AnfixResponseException
     */
    public function handle(): void
    {
        AnfixController::createReturn($this->orderReturn);
    }
}
