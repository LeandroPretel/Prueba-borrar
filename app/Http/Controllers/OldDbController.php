<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Log;

class OldDbController extends Controller
{
    public function testConnection(): void
    {
        try {
            DB::connection('oldRedentradas')->getPdo();
            Log::info('Check old DB');
            echo 'ok';
        } catch (Exception $e) {
            echo 'bad';
            die("Could not connect to the database.  Please check your configuration. error:" . $e);
        }
    }

    public function testLog(): void
    {
        Log::info('Check log');
    }
}
