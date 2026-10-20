<?php

namespace App\Rules;

use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Dalam mode testing, lewati verifikasi API eksternal agar unit test berjalan cepat dan independen
        if (app()->environment('testing')) {
            return;
        }

        $secret = config('services.recaptcha.secret_key');

        if (empty($secret)) {
            Log::warning('reCAPTCHA Secret Key belum dikonfigurasi di file .env.');
            return;
        }

        if (empty($value)) {
            $fail('Silakan centang verifikasi reCAPTCHA terlebih dahulu bahwa Anda bukan robot.');
            return;
        }

        try {
            $response = Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            if (!$response->successful() || !$response->json('success')) {
                Log::warning('reCAPTCHA verification failed:', [
                    'status' => $response->status(),
                    'body'   => $response->json(),
                ]);
                $fail('Verifikasi reCAPTCHA tidak valid atau telah kedaluwarsa. Silakan centang kembali.');
            }
        } catch (Exception $e) {
            Log::error('Error connecting to Google reCAPTCHA service: ' . $e->getMessage());
            // Fallback toleransi jika server tidak dapat menghubungi Google karena gangguan jaringan
            // agar login tidak terkunci total di server lokal
            if (app()->environment('local')) {
                Log::info('reCAPTCHA diizinkan lewat karena fallback lingkungan lokal.');
                return;
            }

            $fail('Gagal menghubungi server verifikasi Google reCAPTCHA. Silakan coba beberapa saat lagi.');
        }
    }
}

