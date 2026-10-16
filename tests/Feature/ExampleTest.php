<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/beranda');
        $response->assertStatus(200);

        $redirect = $this->get('/');
        $redirect->assertRedirect(route('booking.index'));
    }

    /**
     * Test: Berkas proposal/lampiran dapat dibuka melalui route storage.
     */
    public function test_storage_attachment_can_be_accessed(): void
    {
        $response = $this->get('/storage/attachments/bkg_1792077454_1fsSj6N2.pdf');
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    /**
     * Test: Halaman beranda memuat modal dengan label Tanggal Pesanan dan Estimasi Target Audiens untuk Videotron.
     */
    public function test_landing_page_contains_videotron_updated_labels(): void
    {
        $response = $this->get('/beranda');
        $response->assertStatus(200);
        $response->assertSee('labelBookingDate', false);
        $response->assertSee('labelScreenAudience', false);
        $response->assertSee('Estimasi Target Audiens / Penonton');
        $response->assertSee('Tanggal Selesai Tayang');
    }

    /**
     * Test: Pengajuan pesanan Videotron dengan target audiens berhasil disimpan.
     */
    public function test_videotron_booking_can_be_submitted_successfully(): void
    {
        $videotron = \App\Models\Room::where('category', 'layar_iklan')->first();
        if (!$videotron) {
            $videotron = \App\Models\Room::create([
                'name' => 'Videotron Lobi Utama',
                'category' => 'layar_iklan',
                'capacity' => 1,
                'floor' => 'Lantai 1',
                'is_active' => true,
            ]);
        }

        $response = $this->post(route('booking.store'), [
            'name' => 'Budi Pengiklan',
            'email' => 'pengiklan' . time() . '@gmail.com',
            'identity_number' => '1234567890',
            'phone_number' => '08123456789',
            'room_id' => $videotron->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'end_date' => now()->addDays(9)->toDateString(),
            'participant_count' => 500, // Estimasi Target Audiens
            'purpose' => 'Penayangan poster digital Lomba Karya Ilmiah Pelajar',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('bookings', [
            'room_id' => $videotron->id,
            'participant_count' => 500,
            'purpose' => 'Penayangan poster digital Lomba Karya Ilmiah Pelajar',
        ]);
    }
}
