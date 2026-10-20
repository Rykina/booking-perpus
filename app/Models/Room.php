<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'category',
        'floor',
        'capacity',
        'facilities',
        'description',
        'image',
        'status',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get primary image for room card
     */
    public function getCardImageAttribute(): string
    {
        $id = $this->id;
        $code = strtoupper($this->code ?? '');
        $name = strtolower($this->name ?? '');

        if ($id == 1 || str_contains($code, 'AUD') || str_contains($name, 'auditorium')) {
            return asset('images/rooms/auditorium/auditorium1.jpg');
        } elseif ($id == 2 || str_contains($code, 'RMM') || str_contains($name, 'multimedia')) {
            return asset('images/rooms/multimedia/multimedia1.jpeg');
        } elseif ($id == 3 || str_contains($code, 'RRP') || str_contains($name, 'rapat')) {
            return asset('images/rooms/rapat/ruangrapat1.jpg');
        } elseif ($id == 4 || str_contains($code, 'LOB') || str_contains($name, 'lobi') || str_contains($code, 'LYR') || str_contains($name, 'videotron')) {
            return asset('images/gedung-perpus-bogor-real.jpg');
        }

        return $this->image ?: asset('images/gedung-perpus-bogor-real.jpg');
    }

    /**
     * Get all local photos for carousel / gallery
     */
    public function getGalleryImagesAttribute(): array
    {
        $id = $this->id;
        $code = strtoupper($this->code ?? '');
        $name = strtolower($this->name ?? '');

        if ($id == 1 || str_contains($code, 'AUD') || str_contains($name, 'auditorium')) {
            return [
                asset('images/rooms/auditorium/auditorium1.jpg'),
                asset('images/rooms/auditorium/auditorium2.jpg'),
                asset('images/rooms/auditorium/auditorium3.jpg'),
            ];
        } elseif ($id == 2 || str_contains($code, 'RMM') || str_contains($name, 'multimedia')) {
            return [
                asset('images/rooms/multimedia/multimedia1.jpeg'),
                asset('images/rooms/multimedia/multimedia2.jpeg'),
            ];
        } elseif ($id == 3 || str_contains($code, 'RRP') || str_contains($name, 'rapat')) {
            return [
                asset('images/rooms/rapat/ruangrapat1.jpg'),
                asset('images/rooms/rapat/ruangrapat2.jpg'),
            ];
        } elseif ($id == 4 || str_contains($code, 'LOB') || str_contains($name, 'lobi') || str_contains($code, 'LYR') || str_contains($name, 'videotron')) {
            return [
                asset('images/gedung-perpus-bogor-real.jpg'),
                asset('images/rooms/videotron/videotron1.jpeg'),
            ];
        }

        return [$this->card_image];
    }

    /**
     * Get short display title matching mockup
     */
    public function getDisplayNameAttribute(): string
    {
        if (str_contains(strtolower($this->name), 'lobi') || $this->id == 4) {
            return 'Lobi Luar';
        }
        if ($this->category === 'layar_iklan' || str_contains(strtolower($this->name), 'videotron')) {
            return 'Lobi Luar';
        }
        return $this->name;
    }

    /**
     * Get badge label
     */
    public function getBadgeLabelAttribute(): string
    {
        if (str_contains(strtolower($this->name), 'lobi') || $this->id == 4) {
            return 'AREA TERBUKA';
        }
        return $this->category === 'layar_iklan' ? 'LAYANAN VIDEOTRON' : 'RUANGAN PERTEMUAN';
    }

    /**
     * Capacity text
     */
    public function getCapacityTextAttribute(): string
    {
        if ($this->category === 'layar_iklan' && !str_contains(strtolower($this->name), 'lobi') && $this->id != 4) {
            return '-';
        }
        return ($this->capacity ?: 50) . ' Orang';
    }

    /**
     * Key facility snippet for card pill
     */
    public function getKeyFacilityAttribute(): string
    {
        $id = $this->id;
        $name = strtolower($this->name ?? '');

        if ($id == 1 || str_contains($name, 'auditorium')) {
            return 'LCD, Sound System';
        } elseif ($id == 2 || str_contains($name, 'multimedia')) {
            return 'PC, Headset, LCD';
        } elseif ($id == 3 || str_contains($name, 'rapat')) {
            return 'TV 65", Kamera 360°';
        } elseif ($id == 4 || str_contains($name, 'lobi')) {
            return 'Kanopi, Meja, Listrik';
        } elseif ($this->category === 'layar_iklan') {
            return 'Full HD (1080p)';
        }

        return 'Fasilitas Lengkap';
    }
}
