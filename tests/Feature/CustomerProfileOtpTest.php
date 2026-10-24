<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CustomerProfileOtpTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_guest_cannot_request_profile_otp(): void
    {
        $response = $this->postJson(route('pelanggan.profile.send_otp'));
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_request_profile_otp(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($user)->postJson(route('pelanggan.profile.send_otp'));
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertNotNull(session('profile_password_otp_hash'));
        $this->assertNotNull(session('profile_password_otp_raw'));
    }

    public function test_update_profile_without_password_change_succeeds_without_otp(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'role' => 'customer',
        ]);

        $response = $this->actingAs($user)->put(route('pelanggan.profile.update'), [
            'name' => 'Updated Name',
            'email' => $user->email,
            'identity_number' => '1234567890',
            'phone_number' => '08123456789',
        ]);

        $response->assertRedirect(route('pelanggan.dashboard'));
        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
    }

    public function test_change_password_without_otp_fails(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
            'role' => 'customer',
        ]);

        $response = $this->actingAs($user)->put(route('pelanggan.profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors(['otp_code']);
        $user->refresh();
        $this->assertTrue(Hash::check('oldpassword123', $user->password));
    }

    public function test_change_password_with_invalid_otp_fails(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
            'role' => 'customer',
        ]);

        // Request OTP first
        $this->actingAs($user)->postJson(route('pelanggan.profile.send_otp'));

        // Submit with wrong OTP
        $response = $this->actingAs($user)->put(route('pelanggan.profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'otp_code' => '999999',
        ]);

        $response->assertSessionHasErrors(['otp_code']);
        $user->refresh();
        $this->assertTrue(Hash::check('oldpassword123', $user->password));
    }

    public function test_change_password_with_valid_otp_succeeds(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
            'role' => 'customer',
        ]);

        // Request OTP
        $this->actingAs($user)->postJson(route('pelanggan.profile.send_otp'));
        $otp = session('profile_password_otp_raw');

        // Submit with valid OTP
        $response = $this->actingAs($user)->put(route('pelanggan.profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'otp_code' => $otp,
        ]);

        $response->assertRedirect(route('pelanggan.dashboard'));
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
        $this->assertNull(session('profile_password_otp_hash'));
    }
}

