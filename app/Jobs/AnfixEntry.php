<?php

namespace App\Jobs;

use App\Anfix\Exceptions\AnfixException;
use App\Anfix\Exceptions\AnfixResponseException;
use App\Http\Controllers\AnfixController;
use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AnfixEntry implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $ticket;

    /**
     * Create a new job instance.
     *
     * @param Ticket $ticket
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
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
        AnfixController::createEntry($this->ticket->order, $this->ticket);
    }
}
