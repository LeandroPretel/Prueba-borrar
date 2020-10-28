<?php

namespace App\Observers;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Savitar\Auth\Mail\ConfirmMail;
use Savitar\Auth\SavitarUser;

class SavitarUserObserver
{
    /**
     * Handle the savitarUser "created" event.
     *
     * @param SavitarUser $savitarUser
     * @return void
     */
    public function created(SavitarUser $savitarUser): void
    {
        $role = $savitarUser->role;
        // If not client, send the confirm email
        if ($role->slug !== 'cliente' && config('savitar_auth.confirm_mail_enabled_aux') && $savitarUser->email !== config('savitar_auth.super_admin_mail')) {
            $token = base64_encode(Carbon::now()->toIso8601String() . '.' . $savitarUser->id) . '.'
                . base64_encode(hash_hmac('sha256', Carbon::now()->toIso8601String() . '.' . $savitarUser->id, config('savitar_auth.app_key')));

            $mailRoutes = config('savitar_auth.confirm_mail_routes', []);
            // If the user has password.
            if ($savitarUser->password) {
                if ($mailRoutes && isset($mailRoutes[$savitarUser->role->slug])) {
                    $url = $mailRoutes[$savitarUser->role->slug]['withPassword'] . urlencode($token);
                } else {
                    $url = config('savitar_auth.app_url') . '/confirmacion/' . urlencode($token);
                }
            } // If the user has no password.
            else if ($mailRoutes && isset($mailRoutes[$savitarUser->role->slug])) {
                $url = $mailRoutes[$savitarUser->role->slug]['withoutPassword'] . urlencode($token);
            } else {
                $url = config('savitar_auth.app_url') . '/confirmacion-credenciales/' . urlencode($token);
            }

            if (config('savitar_auth.confirm_mail_title')) {
                $title = config('savitar_auth.confirm_mail_title');
            } else {
                $savitarUser->name ? $title = 'Bienvenido ' . $savitarUser->name . '!' : $title = '¡Bienvenido!';
            }
            // Try to send the email and catch the error.
            try {
                Mail::to($savitarUser->email)->send(
                    new ConfirmMail($savitarUser->email, $url, $title,
                        config('savitar_auth.confirm_mail_subtitle'),
                        config('savitar_auth.confirm_mail_content'),
                        config('savitar_auth.confirm_mail_logo_url'),
                        config('savitar_auth.app_theme', '#e85d0f'),
                        config('savitar_auth.confirm_mail_help_email'),
                        config('savitar_auth.confirm_mail_help_web')
                    ));
                Log::info('Se ha enviado un correo de confirmación de email a ' . $savitarUser->email);
            } catch (Exception $exception) {
                Log::error('Ha ocurrido un error al enviar el correo de confirmación a ' . $savitarUser->email . '. ' . $exception->getMessage());
            }
        }
    }

}
