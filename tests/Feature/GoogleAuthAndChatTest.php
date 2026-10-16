<?php

namespace Tests\Feature;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthAndChatTest extends TestCase
{
    use DatabaseTransactions;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test: Halaman Login Google dapat diakses publik.
     */
    public function test_login_page_renders_successfully()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('Masuk dengan Google');
    }

    /**
     * Test: Redirect ke Google OAuth mengembalikan URL Google.
     */
    public function test_redirect_to_google_initiates_oauth_flow()
    {
        $response = $this->get(route('auth.google'));
        $response->assertStatus(302);
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location'));
    }

    /**
     * Test: Callback Google untuk pengguna baru berhasil membuat akun dengan role customer.
     */
    public function test_google_callback_registers_new_customer_successfully()
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn('google_test_id_99999');
        $socialiteUser->shouldReceive('getName')->andReturn('Fajar Ramadhan');
        $socialiteUser->shouldReceive('getEmail')->andReturn('fajar.ramadhan.test@gmail.com');
        $socialiteUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/fajar.jpg');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('pelanggan.dashboard'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'fajar.ramadhan.test@gmail.com',
            'google_id' => 'google_test_id_99999',
            'role' => 'customer',
        ]);
    }

    /**
     * Test: Login ulang menggunakan akun Google yang sudah terdaftar tidak membuat duplikasi akun.
     */
    public function test_google_callback_existing_user_does_not_create_duplicate()
    {
        $existing = User::create([
            'name' => 'Lestari Indah',
            'email' => 'lestari.indah.test@gmail.com',
            'role' => 'customer',
            'google_id' => 'google_test_id_88888',
        ]);

        $initialUserCount = User::count();

        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn('google_test_id_88888');
        $socialiteUser->shouldReceive('getName')->andReturn('Lestari Indah Updated');
        $socialiteUser->shouldReceive('getEmail')->andReturn('lestari.indah.test@gmail.com');
        $socialiteUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/lestari.jpg');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('pelanggan.dashboard'));
        $this->assertAuthenticatedAs($existing);

        $this->assertEquals($initialUserCount, User::count());
    }

    /**
     * Test: Pelanggan (Customer) ditolak mengakses Dashboard Admin Chat dan diarahkan ke Login Admin.
     */
    public function test_customer_cannot_access_admin_chat_dashboard()
    {
        $customer = User::create([
            'name' => 'Mahasiswa Pemohon',
            'email' => 'pemohon.test@gmail.com',
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->get(route('admin.chat.index'));
        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHas('error');
    }

    /**
     * Test: Admin dan Petugas dapat mengakses Dashboard Admin Chat.
     */
    public function test_admin_can_access_admin_chat_dashboard()
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin Test',
                'email' => 'admin.test@kotabogor.go.id',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.chat.index'));
        $response->assertStatus(200);
        $response->assertSee('PANEL ADMINISTRATOR & PETUGAS', false);
    }

    /**
     * Test: Pelanggan dapat mengirim dan mengambil pesan chat ke admin.
     */
    public function test_customer_can_send_and_retrieve_chat_messages()
    {
        $customer = User::create([
            'name' => 'Peminjam Ruang Rapat',
            'email' => 'peminjam.rapat@gmail.com',
            'role' => 'customer',
        ]);

        // Kirim pesan dari pelanggan
        $sendResponse = $this->actingAs($customer)->postJson(route('chat.send'), [
            'message' => 'Halo Admin, apakah auditorium tersedia untuk seminar besok?'
        ]);

        $sendResponse->assertStatus(200);
        $sendResponse->assertJson(['success' => true]);

        $this->assertDatabaseHas('chat_messages', [
            'sender_id' => $customer->id,
            'message' => 'Halo Admin, apakah auditorium tersedia untuk seminar besok?',
            'is_read' => false,
        ]);

        // Ambil riwayat percakapan pelanggan
        $fetchResponse = $this->actingAs($customer)->getJson(route('chat.conversation'));
        $fetchResponse->assertStatus(200);
        $fetchResponse->assertJsonFragment([
            'message' => 'Halo Admin, apakah auditorium tersedia untuk seminar besok?',
            'is_me' => true,
        ]);
    }

    /**
     * Test: Admin dapat membalas pesan pelanggan dan menandai pesan pelanggan sebagai telah dibaca.
     */
    public function test_admin_can_reply_to_customer_and_mark_as_read()
    {
        $admin = User::where('role', 'admin')->first();
        $customer = User::create([
            'name' => 'Pelanggan Diskusi',
            'email' => 'pelanggan.diskusi@gmail.com',
            'role' => 'customer',
        ]);

        $conversation = ChatConversation::create([
            'user_id' => $customer->id,
            'status' => 'active',
            'last_message_at' => now(),
        ]);

        $customerMessage = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $customer->id,
            'message' => 'Mohon info syarat berkas proposal acara.',
            'is_read' => false,
        ]);

        // Admin membalas pesan
        $replyResponse = $this->actingAs($admin)->postJson(route('admin.chat.reply', $conversation->id), [
            'message' => 'Proposal format PDF bertanda tangan pimpinan organisasi, maksimal 10MB.'
        ]);

        $replyResponse->assertStatus(200);
        $replyResponse->assertJson(['success' => true]);

        $this->assertDatabaseHas('chat_messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $admin->id,
            'message' => 'Proposal format PDF bertanda tangan pimpinan organisasi, maksimal 10MB.',
        ]);

        // Cek admin messages fetch (sekaligus memvalidasi is_read diubah ke true)
        $fetchResponse = $this->actingAs($admin)->getJson(route('admin.chat.messages', $conversation->id));
        $fetchResponse->assertStatus(200);
        $this->assertTrue(ChatMessage::find($customerMessage->id)->is_read);
    }

    /**
     * Test: Validasi menolak pesan chat kosong.
     */
    public function test_empty_chat_message_is_rejected()
    {
        $customer = User::create([
            'name' => 'Pelanggan Uji',
            'email' => 'pelanggan.uji@gmail.com',
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->postJson(route('chat.send'), [
            'message' => ''
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test: Logout mengakhiri sesi dan mengarahkan ke halaman beranda.
     */
    public function test_logout_invalidates_session_and_redirects()
    {
        $customer = User::create([
            'name' => 'Pelanggan Logout',
            'email' => 'pelanggan.logout@gmail.com',
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->post(route('logout'));

        $response->assertRedirect(route('booking.index'));
        $this->assertGuest();
    }

    /**
     * Test: Halaman Portal Pelanggan (/pelanggan) memerlukan autentikasi login.
     */
    public function test_customer_portal_requires_authentication()
    {
        $response = $this->get(route('pelanggan.dashboard'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test: Pelanggan yang login dapat mengakses Portal Pelanggan (/pelanggan).
     */
    public function test_authenticated_customer_can_access_customer_portal()
    {
        $customer = User::create([
            'name' => 'Pelanggan Dashboard Test',
            'email' => 'dashboard.test@gmail.com',
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->get(route('pelanggan.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Selamat Datang, Pelanggan Dashboard Test!');
        $response->assertSee('Daftar Reservasi & Peminjaman Saya', false);
    }

    /**
     * Test: Halaman Login Admin (/admin/login) terpisah dan menampilkan form email dinas.
     */
    public function test_admin_login_page_renders_dedicated_staff_form()
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertSee('Panel Pegawai & Petugas', false);
        $response->assertSee('Email Kedinasan Resmi');
        $response->assertSee('Masuk ke Panel Pegawai');
    }

    /**
     * Test: Booking yang diajukan oleh pelanggan yang login otomatis tertaut dan muncul di portal.
     */
    public function test_logged_in_customer_booking_is_linked_and_reflected_in_portal()
    {
        $customer = User::create([
            'name' => 'Pelanggan Booking Sync',
            'email' => 'sync.test@gmail.com',
            'role' => 'customer',
        ]);

        $room = \App\Models\Room::first();

        // Ajukan booking saat login sebagai customer
        $bookingResponse = $this->actingAs($customer)->post(route('booking.store'), [
            'name' => 'Pelanggan Booking Sync',
            'email' => 'sync.test@gmail.com',
            'identity_number' => '99887766',
            'phone_number' => '081299998888',
            'room_id' => $room->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'start_time' => '09:00',
            'end_time' => '12:00',
            'participant_count' => 10,
            'purpose' => 'Rapat Koordinasi Literasi',
        ]);

        $bookingResponse->assertSessionHas('success_booking');

        // Pastikan booking terhubung dengan user_id customer
        $booking = \App\Models\Booking::where('user_id', $customer->id)->first();
        $this->assertNotNull($booking);
        $this->assertEquals('Rapat Koordinasi Literasi', $booking->purpose);

        // Buka dashboard portal pelanggan dan pastikan muncul
        $portalResponse = $this->actingAs($customer)->get(route('pelanggan.dashboard'));
        $portalResponse->assertStatus(200);
        $portalResponse->assertSee($booking->booking_code);
        $portalResponse->assertSee('Rapat Koordinasi Literasi');
    }

    /**
     * Test: Pelanggan dapat login menggunakan email dan password.
     */
    public function test_customer_can_login_with_email_and_password()
    {
        $customer = User::create([
            'name' => 'Budi Customer Test',
            'email' => 'budi.customer@gmail.com',
            'role' => 'user',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('pelanggan.login.submit'), [
            'email' => 'budi.customer@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('pelanggan.dashboard'));
        $this->assertAuthenticatedAs($customer);
    }

    /**
     * Test: Login pelanggan gagal jika password salah.
     */
    public function test_customer_login_fails_with_invalid_password()
    {
        User::create([
            'name' => 'Siti Customer Test',
            'email' => 'siti.customer@gmail.com',
            'role' => 'user',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('pelanggan.login.submit'), [
            'email' => 'siti.customer@gmail.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test: Halaman pendaftaran akun pelanggan (/pelanggan/daftar) berhasil dimuat.
     */
    public function test_customer_register_page_renders_successfully()
    {
        $response = $this->get(route('pelanggan.register'));
        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Pelanggan');
        $response->assertSee('Daftar Sebagai Pelanggan');
    }

    /**
     * Test: Pelanggan dapat mendaftar dan otomatis terdaftar dengan role customer.
     */
    public function test_customer_can_register_successfully_as_customer()
    {
        $response = $this->post(route('pelanggan.register.submit'), [
            'name' => 'Fauzi Pelanggan Baru',
            'email' => 'fauzi.baru@gmail.com',
            'identity_number' => '1234567890',
            'phone_number' => '081298765432',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('pelanggan.dashboard'));

        $user = User::where('email', 'fauzi.baru@gmail.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Fauzi Pelanggan Baru', $user->name);
        $this->assertEquals('customer', $user->role);
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test: Pendaftaran pelanggan gagal jika email sudah digunakan atau password tidak cocok.
     */
    public function test_customer_registration_validates_inputs()
    {
        User::create([
            'name' => 'Existing Customer',
            'email' => 'duplicate@gmail.com',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post(route('pelanggan.register.submit'), [
            'name' => 'Attempt Duplicate',
            'email' => 'duplicate@gmail.com',
            'password' => 'secret123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }
}
