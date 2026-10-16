<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;
    protected $superAdmin;
    protected $customer;
    protected $room;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::where('role', 'super_admin')->first();
        if (!$this->superAdmin) {
            $this->superAdmin = User::create([
                'name' => 'Super Admin Penguji',
                'email' => 'superadmin.test@kotabogor.go.id',
                'role' => 'super_admin',
                'password' => bcrypt('password'),
            ]);
        }

        $this->admin = User::where('role', 'admin')->first();
        if (!$this->admin) {
            $this->admin = User::create([
                'name' => 'Admin Penguji',
                'email' => 'admin.test@kotabogor.go.id',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]);
        }

        $this->customer = User::create([
            'name' => 'Customer Penguji',
            'email' => 'customer.test@gmail.com',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);

        $this->room = Room::first();
    }

    /**
     * Test: Login Pegawai menggunakan Email & Password resmi.
     */
    public function test_staff_can_login_with_email_and_password()
    {
        $response = $this->post(route('login.email'), [
            'email' => $this->admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->admin);
    }

    /**
     * Test: Login Pegawai gagal jika password salah.
     */
    public function test_staff_login_fails_with_invalid_password()
    {
        $response = $this->post(route('login.email'), [
            'email' => $this->admin->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test: Dashboard Utama Admin dapat diakses oleh Admin.
     */
    public function test_admin_dashboard_renders_successfully()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('PANEL PEGAWAI & ADMINISTRATOR', false);
        $response->assertSee('Verifikasi Pengajuan');
    }

    /**
     * Test: Halaman Kelola Data Reservasi dapat diakses dan menampilkan filter.
     */
    public function test_admin_bookings_index_renders_with_filters()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.bookings.index'));
        $response->assertStatus(200);
        $response->assertSee('Kelola Data Reservasi Fasilitas');
        $response->assertSee('Menunggu Verifikasi');
    }

    /**
     * Test: Admin dapat menyetujui pengajuan reservasi.
     */
    public function test_admin_can_approve_pending_booking()
    {
        $booking = Booking::create([
            'booking_code' => 'TEST-BKG-001',
            'user_id' => $this->customer->id,
            'room_id' => $this->room->id,
            'booking_date' => now()->addDays(5)->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'participant_count' => 15,
            'purpose' => 'Seminar Literasi Digital',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.bookings.status', $booking->id), [
            'status' => 'disetujui',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('disetujui', $booking->fresh()->status);
    }

    /**
     * Test: Admin dapat menolak pengajuan reservasi dengan catatan alasan.
     */
    public function test_admin_can_reject_booking_with_notes()
    {
        $booking = Booking::create([
            'booking_code' => 'TEST-BKG-002',
            'user_id' => $this->customer->id,
            'room_id' => $this->room->id,
            'booking_date' => now()->addDays(6)->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '14:00:00',
            'participant_count' => 20,
            'purpose' => 'Lomba Puisi Kota',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.bookings.status', $booking->id), [
            'status' => 'ditolak',
            'admin_notes' => 'Jadwal bentrok dengan agenda resmi dinas.',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('ditolak', $booking->fresh()->status);
        $this->assertEquals('Jadwal bentrok dengan agenda resmi dinas.', $booking->fresh()->admin_notes);
    }

    /**
     * Test: Halaman Laporan & Rekapitulasi dapat memfilter periode Harian, Mingguan, Bulanan, Tahunan.
     */
    public function test_admin_reports_filters_and_renders_successfully()
    {
        // 1. Bulanan (Default)
        $response = $this->actingAs($this->admin)->get(route('admin.reports.index', ['period' => 'bulanan']));
        $response->assertStatus(200);
        $response->assertSee('Laporan & Rekapitulasi Reservasi');
        $response->assertSee('TOTAL PENGAJUAN');

        // 2. Harian
        $resDaily = $this->actingAs($this->admin)->get(route('admin.reports.index', [
            'period' => 'harian',
            'date' => now()->toDateString()
        ]));
        $resDaily->assertStatus(200);

        // 3. Mingguan
        $resWeekly = $this->actingAs($this->admin)->get(route('admin.reports.index', [
            'period' => 'mingguan',
            'date' => now()->toDateString()
        ]));
        $resWeekly->assertStatus(200);

        // 4. Tahunan
        $resYearly = $this->actingAs($this->admin)->get(route('admin.reports.index', [
            'period' => 'tahunan',
            'year' => now()->year
        ]));
        $resYearly->assertStatus(200);
    }

    /**
     * Test: Halaman Cetak Laporan Resmi (Print View) memuat Kop Surat dan data rekap.
     */
    public function test_admin_print_report_renders_with_official_letterhead()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.print', ['period' => 'bulanan']));
        $response->assertStatus(200);
        $response->assertSee('Dinas Kearsipan dan Perpustakaan');
        $response->assertSee('Laporan Rekapitulasi Pelayanan Fasilitas & Videotron', false);
    }

    /**
     * Test: Halaman Pemantauan Fasilitas & Okupansi Ruangan.
     */
    public function test_admin_rooms_monitoring_renders_successfully()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.rooms.index'));
        $response->assertStatus(200);
        $response->assertSee('Katalog & Pemantauan Fasilitas', false);
    }

    /**
     * Test: Pelanggan TIDAK bisa login melalui form login admin.
     */
    public function test_customer_cannot_login_through_admin_email_form()
    {
        $response = $this->post(route('login.email'), [
            'email' => $this->customer->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test: Super Admin dapat login menggunakan form email & password.
     */
    public function test_super_admin_can_login_with_email_and_password()
    {
        $response = $this->post(route('login.email'), [
            'email' => $this->superAdmin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->superAdmin);
    }

    /**
     * Test: Super Admin dapat mengakses halaman Manajemen Anggota Admin.
     */
    public function test_super_admin_can_access_admin_users_index()
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertSee('Kelola Anggota Admin', false);
        $response->assertSee('Tambah Anggota Admin Baru', false);
    }

    /**
     * Test: Admin biasa DITOLAK mengakses halaman Manajemen Anggota Admin (khusus Super Admin).
     */
    public function test_regular_admin_cannot_access_admin_users_index()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');
    }

    /**
     * Test: Super Admin dapat menambahkan anggota admin baru dan menentukan rolenya.
     */
    public function test_super_admin_can_create_new_admin_member()
    {
        $newEmail = 'petugas.baru.' . uniqid() . '@kotabogor.go.id';

        $response = $this->actingAs($this->superAdmin)->post(route('admin.users.store'), [
            'name' => 'Petugas Baru Perpus',
            'email' => $newEmail,
            'role' => 'admin',
            'phone_number' => '08123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => $newEmail,
            'role' => 'admin',
        ]);
    }

    /**
     * Test: Super Admin dapat mengubah data dan role anggota admin.
     */
    public function test_super_admin_can_update_admin_member_role()
    {
        $targetUser = User::create([
            'name' => 'Staf Training',
            'email' => 'training.' . uniqid() . '@kotabogor.go.id',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($this->superAdmin)->put(route('admin.users.update', $targetUser->id), [
            'name' => 'Staf Promosi Super Admin',
            'email' => $targetUser->email,
            'role' => 'super_admin',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertEquals('super_admin', $targetUser->fresh()->role);
    }

    /**
     * Test: Super Admin dapat mengubah role pengguna menjadi Pelanggan (customer).
     */
    public function test_super_admin_can_update_user_role_to_customer()
    {
        $targetUser = User::create([
            'name' => 'Staf Ingin Jadi Pelanggan',
            'email' => 'demote.' . uniqid() . '@kotabogor.go.id',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($this->superAdmin)->put(route('admin.users.update', $targetUser->id), [
            'name' => 'Pengguna Pelanggan Biasa',
            'email' => $targetUser->email,
            'role' => 'customer',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertEquals('customer', $targetUser->fresh()->role);
    }

    /**
     * Test: Super Admin tidak dapat menghapus akunnya sendiri demi keamanan.
     */
    public function test_super_admin_cannot_delete_self()
    {
        $response = $this->actingAs($this->superAdmin)->delete(route('admin.users.destroy', $this->superAdmin->id));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->superAdmin->id]);
    }

    /**
     * Test: Super Admin dapat memperbarui kata sandi anggota dan pengguna dapat login dengan sandi baru.
     */
    public function test_super_admin_can_update_member_password_and_login()
    {
        $targetUser = User::create([
            'name' => 'Staf Ganti Sandi',
            'email' => 'gantisandi.' . uniqid() . '@kotabogor.go.id',
            'role' => 'admin',
            'password' => bcrypt('oldpassword'),
        ]);

        $response = $this->actingAs($this->superAdmin)->put(route('admin.users.update', $targetUser->id), [
            'name' => 'Staf Ganti Sandi Updated',
            'email' => $targetUser->email,
            'role' => 'admin',
            'password' => 'newpassword123',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        // Logout super admin
        $this->post(route('logout'));

        // Coba login dengan sandi baru
        $loginResponse = $this->post(route('login.email'), [
            'email' => $targetUser->email,
            'password' => 'newpassword123',
        ]);

        $loginResponse->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($targetUser->fresh());
    }

    /**
     * Test: Halaman login admin tidak menampilkan kotak kredensial default untuk keamanan.
     */
    public function test_admin_login_page_does_not_display_credentials_box()
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertDontSee('Akun Resmi Petugas:');
        $response->assertDontSee('Kata Sandi Default:');
    }

    /**
     * Test: Pengguna dengan sesi Pelanggan dapat membuka /admin/login tanpa dilempar ke /pelanggan.
     */
    public function test_customer_can_open_admin_login_page_without_being_redirected()
    {
        $response = $this->actingAs($this->customer)->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertSee('Panel Pegawai & Petugas', false);
        $response->assertSee('Saat ini Anda sedang terhubung dengan akun Pelanggan');
    }

    /**
     * Test: Logout admin melalui /admin/logout mengarahkan kembali ke /admin/login.
     */
    public function test_admin_logout_redirects_to_admin_login()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.logout'));
        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    /**
     * Test: Admin yang mengakses /pelanggan dialihkan secara aman ke /admin/dashboard.
     */
    public function test_admin_visiting_customer_dashboard_is_redirected_to_admin_dashboard()
    {
        $response = $this->actingAs($this->admin)->get(route('pelanggan.dashboard'));
        $response->assertRedirect(route('admin.dashboard'));
    }
}
