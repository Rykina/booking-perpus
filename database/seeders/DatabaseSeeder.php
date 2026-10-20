<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users
        $superAdmin = \App\Models\User::create([
            'name' => 'Super Administrator Perpustakaan & Galeri',
            'email' => 'superadmin@gmail.com',
            'identity_number' => '198001012005011001',
            'phone_number' => '08111222333',
            'role' => 'super_admin',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        $admin = \App\Models\User::create([
            'name' => 'Administrator Perpustakaan & Galeri Kota Bogor',
            'email' => 'admin@gmail.com',
            'identity_number' => '198501012010011001',
            'phone_number' => '081234567890',
            'role' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        $petugas = \App\Models\User::create([
            'name' => 'Petugas Layanan Fasilitas',
            'email' => 'petugas@gmail.com',
            'identity_number' => '199203152018021002',
            'phone_number' => '081234567891',
            'role' => 'petugas',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        $mhs1 = \App\Models\User::create([
            'name' => 'Budi Pratama',
            'email' => 'budi.pratama@gmail.com',
            'identity_number' => '2201010012',
            'phone_number' => '081298765432',
            'role' => 'user',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        $mhs2 = \App\Models\User::create([
            'name' => 'Siti Rahmawati',
            'email' => 'siti.rahmawati@gmail.com',
            'identity_number' => '2201010045',
            'phone_number' => '081298765433',
            'role' => 'user',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        // 2. Ruangan & Fasilitas Layar Iklan Perpustakaan Dan Galeri Kota Bogor
        $auditorium = \App\Models\Room::create([
            'name' => 'Auditorium',
            'code' => 'AUD-01',
            'category' => 'ruangan',
            'floor' => 'Lantai 2',
            'capacity' => 72,
            'facilities' => 'Panggung Utama, Sound System Profesional, Proyektor Laser & Layar 200 inch, 4x Wireless Mic, Lighting Event, AC Sentral, Kursi Teater Bertingkat',
            'description' => 'Auditorium utama Perpustakaan Dan Galeri Kota Bogor untuk penyelenggaraan seminar akbar, bedah buku, workshop literasi, pameran seni budaya, dan konferensi.',
            'image' => 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?auto=format&fit=crop&w=1200&q=80',
            'status' => 'tersedia',
        ]);

        $multimedia = \App\Models\Room::create([
            'name' => 'Ruang Multimedia',
            'code' => 'RMM-01',
            'category' => 'ruangan',
            'floor' => 'Lantai 2',
            'capacity' => 30,
            'facilities' => '25 Unit PC Workstation, Smart Screen Interaktif, Headset Audio, Akses E-Journal & Database Riset, Internet Fiber Optik, Full AC',
            'description' => 'Ruangan komputer multimedia untuk pelatihan literasi informasi digital masyarakat, workshop riset, webinar edukasi, dan produksi multimedia audio-visual.',
            'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80',
            'status' => 'tersedia',
        ]);

        $rapat = \App\Models\Room::create([
            'name' => 'Ruang Rapat',
            'code' => 'RRP-01',
            'category' => 'ruangan',
            'floor' => 'Lantai 1',
            'capacity' => 16,
            'facilities' => 'Smart TV 65 inch 4K, Kamera Video Conference 360, Meja Rapat Konferensi Eksekutif, Whiteboard Kaca, Mic Meja Rapat, AC',
            'description' => 'Ruangan rapat representatif untuk pertemuan koordinasi instansi, komunitas literasi Kota Bogor, diskusi akademis, dan pertemuan organisasi.',
            'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
            'status' => 'tersedia',
        ]);

        $lobiLuar = \App\Models\Room::create([
            'name' => 'Lobi Luar',
            'code' => 'LOB-01',
            'category' => 'ruangan',
            'floor' => 'Lantai 1',
            'capacity' => 50,
            'facilities' => 'Area Terbuka / Selasar Luar, Tenda / Kanopi Peneduh, Stop Kontak Outdoor, Meja Registrasi, Dekat Akses Utama & Taman',
            'description' => 'Area lobi luar Perpustakaan Dan Galeri Kota Bogor untuk kegiatan pameran terbuka, bazaar literasi, pendaftaran atau registrasi acara, dan aktivitas komunitas.',
            'image' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200&q=80',
            'status' => 'tersedia',
        ]);
    }
}
