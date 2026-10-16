@extends('layouts.admin')

@section('title', 'Katalog & Status Fasilitas - Panel Pegawai Perpustakaan')

@section('content')
<div>
    
    <!-- Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h1 style="font-size: 1.55rem; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
                Katalog & Pemantauan Fasilitas
            </h1>
            <p style="color: #64748b; font-size: 0.88rem; margin: 0;">
                Informasi detail kapasitas, fasilitas pendukung, dan okupansi ruangan di Perpustakaan dan Galeri Kota Bogor.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline" style="padding: 8px 16px; border-radius: 9999px; font-size: 0.82rem; font-weight: 600; text-decoration: none;">
                Kelola Seluruh Jadwal
            </a>
        </div>
    </div>

    <!-- Room Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
        @foreach($rooms as $r)
            @php
                $activeToday = $todayActiveBookings->where('room_id', $r->id);
            @endphp
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.03); display: flex; flex-direction: column;">
                
                <!-- Room Image & Header Tag -->
                <div style="position: relative; height: 180px; overflow: hidden; background: #e2e8f0;">
                    <img src="{{ $r->card_image }}" alt="{{ $r->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; top: 12px; left: 12px; background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(4px); color: #ffffff; padding: 4px 10px; border-radius: 9999px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase;">
                        {{ $r->category === 'layar_iklan' ? 'Layar Iklan Digital' : 'Ruangan Pertemuan' }}
                    </div>
                    <div style="position: absolute; bottom: 12px; right: 12px; background: {{ $activeToday->isNotEmpty() ? '#f59e0b' : '#10b981' }}; color: #ffffff; padding: 4px 10px; border-radius: 9999px; font-size: 0.72rem; font-weight: 700;">
                        {{ $activeToday->isNotEmpty() ? 'Ada Jadwal Hari Ini' : 'Tersedia' }}
                    </div>
                </div>

                <!-- Room Details -->
                <div style="padding: 20px; flex: 1; display: flex; flex-direction: column;">
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; margin-bottom: 6px;">
                        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">{{ $r->name }}</h3>
                        <span style="font-size: 0.76rem; font-weight: 700; color: #64748b; background: #f1f5f9; padding: 2px 8px; border-radius: 6px;">{{ $r->code }}</span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 12px; font-size: 0.8rem; color: #64748b; margin-bottom: 12px;">
                        <span>📍 {{ $r->floor }}</span>
                        <span>👥 Kapasitas: <strong>{{ $r->capacity }} {{ $r->category === 'layar_iklan' ? 'Layar' : 'Orang' }}</strong></span>
                    </div>

                    <p style="font-size: 0.82rem; color: #475569; line-height: 1.45; margin: 0 0 16px 0; flex: 1;">
                        {{ $r->description }}
                    </p>

                    <!-- Facilities Chips -->
                    <div style="margin-bottom: 16px; padding: 10px 12px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0;">
                        <div style="font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">Fasilitas Termasuk:</div>
                        <div style="font-size: 0.78rem; color: #334155;">{{ $r->facilities }}</div>
                    </div>

                    <!-- Usage Stats -->
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; text-align: center; border-top: 1px solid #f1f5f9; padding-top: 12px;">
                        <div>
                            <div style="font-size: 0.7rem; color: #94a3b8;">Total Booking</div>
                            <div style="font-weight: 800; font-size: 1rem; color: #0f172a;">{{ $r->total_bookings }}</div>
                        </div>
                        <div>
                            <div style="font-size: 0.7rem; color: #d97706;">Menunggu</div>
                            <div style="font-weight: 800; font-size: 1rem; color: #d97706;">{{ $r->pending_bookings }}</div>
                        </div>
                        <div>
                            <div style="font-size: 0.7rem; color: #16a34a;">Disetujui</div>
                            <div style="font-weight: 800; font-size: 1rem; color: #16a34a;">{{ $r->approved_bookings }}</div>
                        </div>
                    </div>

                    <div style="margin-top: 16px;">
                        <a href="{{ route('admin.bookings.index', ['room_id' => $r->id]) }}" 
                           class="btn btn-outline btn-block" 
                           style="border-radius: 9999px; padding: 8px; font-size: 0.82rem; font-weight: 700; text-decoration: none; text-align: center; display: block;">
                            Lihat Reservasi Ruangan Ini &rarr;
                        </a>
                    </div>
                </div>

            </div>
        @endforeach
    </div>

</div>
@endsection

