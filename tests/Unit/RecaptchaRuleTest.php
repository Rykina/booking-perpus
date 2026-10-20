<?php

namespace Tests\Unit;

use App\Rules\Recaptcha;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RecaptchaRuleTest extends TestCase
{
    public function test_recaptcha_passes_in_testing_environment()
    {
        $rule = new Recaptcha();
        $failed = false;

        $rule->validate('g-recaptcha-response', 'test_token', function () use (&$failed) {
            $failed = true;
        });

        $this->assertFalse($failed, 'Recaptcha rule should pass in testing environment without calling external API.');
    }

    public function test_recaptcha_verification_with_mocked_http_success()
    {
        // Mock external Google siteverify endpoint
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'hostname' => 'localhost',
            ], 200),
        ]);

        $this->app->detectEnvironment(fn() => 'production');
        config(['services.recaptcha.secret_key' => 'dummy_secret']);

        $rule = new Recaptcha();
        $failed = false;

        $rule->validate('g-recaptcha-response', 'valid_token', function () use (&$failed) {
            $failed = true;
        });

        $this->assertFalse($failed);
    }

    public function test_recaptcha_verification_fails_when_google_returns_false()
    {
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => false,
                'error-codes' => ['invalid-input-response'],
            ], 200),
        ]);

        $this->app->detectEnvironment(fn() => 'production');
        config(['services.recaptcha.secret_key' => 'dummy_secret']);

        $rule = new Recaptcha();
        $errorMessage = null;

        $rule->validate('g-recaptcha-response', 'invalid_token', function ($message) use (&$errorMessage) {
            $errorMessage = $message;
        });

        $this->assertNotNull($errorMessage);
        $this->assertStringContainsString('Verifikasi reCAPTCHA tidak valid', $errorMessage);
    }

    public function test_admin_login_view_contains_recaptcha()
    {
        $content = file_get_contents(resource_path('views/auth/admin-login.blade.php'));
        $this->assertStringContainsString('class="g-recaptcha"', $content);
        $this->assertStringContainsString('https://www.google.com/recaptcha/api.js', $content);
        $this->assertStringContainsString("config('services.recaptcha.site_key')", $content);
    }

    public function test_pelanggan_login_view_contains_recaptcha()
    {
        $content = file_get_contents(resource_path('views/auth/pelanggan-login.blade.php'));
        $this->assertStringContainsString('class="g-recaptcha"', $content);
        $this->assertStringContainsString('https://www.google.com/recaptcha/api.js', $content);
        $this->assertStringContainsString("config('services.recaptcha.site_key')", $content);
    }
}
