<?php

namespace App\Observers;

use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Savitar\Auth\Mail\ConfirmMail;

class UserObserver
{
    /**
     * Handle the savitarUser "created" event.
     *
     * @param User $user
     * @return void
     */
    public function created(User $user): void
    {
        $role = $user->role;
        // If not client, send the confirm email
        if ($role->slug !== 'cliente' && config('savitar_auth.confirm_mail_enabled_aux') && $user->email !== config('savitar_auth.super_admin_mail')) {
            $token = base64_encode(Carbon::now()->toIso8601String() . '.' . $user->id) . '.'
                . base64_encode(hash_hmac('sha256', Carbon::now()->toIso8601String() . '.' . $user->id, config('savitar_auth.app_key')));

            $mailRoutes = config('savitar_auth.confirm_mail_routes', []);
            // If the user has password.
            if ($user->password) {
                if ($mailRoutes && isset($mailRoutes[$user->role->slug])) {
                    $url = $mailRoutes[$user->role->slug]['withPassword'] . urlencode($token);
                } else {
                    $url = config('savitar_auth.app_url') . '/confirmacion/' . urlencode($token);
                }
            } // If the user has no password.
            else if ($mailRoutes && isset($mailRoutes[$user->role->slug])) {
                $url = $mailRoutes[$user->role->slug]['withoutPassword'] . urlencode($token);
            } else {
                $url = config('savitar_auth.app_url') . '/confirmacion-credenciales/' . urlencode($token);
            }

            if (config('savitar_auth.confirm_mail_title')) {
                $title = config('savitar_auth.confirm_mail_title');
            } else {
                $user->name ? $title = 'Bienvenido ' . $user->name . '!' : $title = '¡Bienvenido!';
            }
            // Try to send the email and catch the error.
            try {
                Mail::to($user->email)->send(
                    new ConfirmMail($user->email, $url, $title,
                        config('savitar_auth.confirm_mail_subtitle'),
                        config('savitar_auth.confirm_mail_content'),
                        config('savitar_auth.confirm_mail_logo_url'),
                        config('savitar_auth.app_theme', '#e85d0f'),
                        config('savitar_auth.confirm_mail_help_email'),
                        config('savitar_auth.confirm_mail_help_web')
                    ));
                Log::info('Se ha enviado un correo de confirmación de email a ' . $user->email);
            } catch (Exception $exception) {
                Log::error('Ha ocurrido un error al enviar el correo de confirmación a ' . $user->email . '. ' . $exception->getMessage());
            }
        }
    }
}
