<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'google_id',
        'identity_number',
        'phone_number',
        'avatar',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Cek apakah pengguna memiliki akses administratif (Admin atau Super Admin).
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin', 'superadmin', 'petugas'], true);
    }

    /**
     * Cek apakah pengguna memiliki peran Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'superadmin'], true);
    }

    /**
     * Label representasi peran pengguna.
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'super_admin', 'superadmin' => 'Super Admin',
            'admin' => 'Admin',
            'petugas' => 'Petugas',
            default => 'Pelanggan',
        };
    }

    /**
     * Cek apakah pengguna adalah pelanggan / peminjam.
     */
    public function isCustomer(): bool
    {
        return in_array($this->role, ['customer', 'user'], true);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function chatConversations()
    {
        return $this->hasMany(ChatConversation::class, 'user_id');
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }
}
