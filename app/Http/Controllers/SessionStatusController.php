<?php

namespace App\Http\Controllers;

use App\SimpleMailTemplate;
use App\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SessionStatusController extends Controller
{
    /**
     * Checks and modifies the order status
     *
     * @return void
     */
    public function __invoke()
    {
        Log::info('Comprobando estado de las sesiones (Automático)');
        $now = Carbon::now();
        $sessionController = new SessionController();

        $sessionIdsToUpdate = collect();
        $sessions = Session::where('status', '<>', 'Finalizada')
            ->with(['showTemplate'])
            ->get();
        /** @var Session $session */
        foreach ($sessions as $session) {
            if (Carbon::parse($session->date)->addMinutes($session->showTemplate->duration)->diffInSeconds($now, false) >= 0) {
                $sessionIdsToUpdate->push($session->id);
                Log::info('La sesión ' . $session->id . ' ha finalizado automáticamente al pasar su fecha.');

                $reportData = $sessionController->getSessionPurchaseReport($session);
                $reportData['session'] = $session;

                $pdf = App::make('dompdf.wrapper');
                $pdf->loadView('sessions.purchase-report', $reportData);
//                $logoUrl = 'https://staging.api.redentradas.beebit.es/images/logo.png';

                \Mail::to($session->createdBy)->send(
                    new SimpleMailTemplate(
                        'Hoja de Taquilla - Sessión: '. $session->show->webName . ' | ' . $session->date,
                        'Sesión ' . $session->show->webName . ' | ' . $session->date,
                        $pdf->output(),
                        [
                            'La sesión ' . $session->show->webName . ' | ' . $session->date . ' ha finalizado',
                            ' correctamente, la hoja de taquilla para la sesión se ha adjuntado a este correo'
                        ],
                        null,
                        null,
                        null,
                        null
                    )
                );
            }
        }
        $finishedCount = Session::whereIn('id', $sessionIdsToUpdate->values())->update(['status' => 'Finalizada', 'updatedAt' => Carbon::now()]);
        if ($finishedCount > 0) {
            Log::info('Se han finalizado ' . $finishedCount . ' sesiones.');
        }
    }
}
