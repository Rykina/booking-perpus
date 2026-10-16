<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'room_id',
        'booking_date',
        'end_date',
        'start_time',
        'end_time',
        'participant_count',
        'purpose',
        'attachment',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'booking_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
    ];

    protected $appends = [
        'is_full_day',
        'booking_date_formatted',
        'end_date_formatted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function getIsFullDayAttribute()
    {
        if ($this->room && $this->room->category === 'layar_iklan') {
            return false;
        }
        $s = substr((string)$this->start_time, 0, 5);
        $e = substr((string)$this->end_time, 0, 5);
        return $s === '08:00' && ($e === '15:00' || $e === '20:00');
    }

    public function getBookingDateFormattedAttribute(): string
    {
        if (!$this->booking_date) {
            return '';
        }
        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];
        $m = (int)$this->booking_date->format('n');
        $monthName = $months[$m] ?? $this->booking_date->format('M');
        return (int)$this->booking_date->format('j') . ' ' . $monthName . ' ' . $this->booking_date->format('Y');
    }

    public function getEndDateFormattedAttribute(): string
    {
        if (!$this->end_date) {
            return '';
        }
        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];
        $m = (int)$this->end_date->format('n');
        $monthName = $months[$m] ?? $this->end_date->format('M');
        return (int)$this->end_date->format('j') . ' ' . $monthName . ' ' . $this->end_date->format('Y');
    }
}
