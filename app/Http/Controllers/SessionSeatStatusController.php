<?php

namespace App\Http\Controllers;

use App\SessionSeat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SessionSeatStatusController extends Controller
{
    /**
     * Checks and modifies the order status
     *
     * @return void
     */
    public function __invoke()
    {
        Log::info('Comprobando estado de las butacas (Automático)');
        $now = Carbon::now();

        $sessionSeatIdsToRelease = collect();
        $sessionSeats = SessionSeat::whereStatus('reserved')->get();
        foreach ($sessionSeats as $sessionSeat) {
            if (Carbon::parse($sessionSeat->updatedAt)->diffInSeconds($now, false) >= 600) {
                $sessionSeatIdsToRelease->push($sessionSeat->id);
            }
        }
        $releasedCount = SessionSeat::whereIn('id', $sessionSeatIdsToRelease->values())->update([
            'status' => 'enabled',
            'updatedAt' => Carbon::now()
        ]);
        if ($releasedCount > 0) {
            Log::info('Liberadas ' . $releasedCount . ' butacas.');
        }
    }
}
