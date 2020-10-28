<?php

namespace App\Observers;

use App\Client;
use Exception;

class ClientObserver
{
    /**
     * Handle the client "created" event.
     *
     * @param Client $client
     * @return void
     */
    public function created(Client $client)
    {
        //
    }

    /**
     * Handle the client "updated" event.
     *
     * @param Client $client
     * @return void
     */
    public function updated(Client $client)
    {
        //
    }

    /**
     * Handle the client "deleted" event.
     *
     * @param Client $client
     * @return void
     * @throws Exception
     */
    public function deleted(Client $client): void
    {
        $user = $client->user;
        if ($user) {
            $user->deletedBy = $client->deletedBy;
            $user->save();
            $user->delete();
        }
    }

    /**
     * Handle the client "restored" event.
     *
     * @param Client $client
     * @return void
     */
    public function restored(Client $client)
    {
        //
    }

    /**
     * Handle the client "force deleted" event.
     *
     * @param Client $client
     * @return void
     */
    public function forceDeleted(Client $client)
    {
        //
    }
}
