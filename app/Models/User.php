<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $google_id
 * @property string|null $identity_number
 * @property string|null $phone_number
 * @property string|null $avatar
 * @property string $role
 * @property string|null $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $email_verified_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read string $role_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChatConversation> $chatConversations
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChatMessage> $chatMessages
 * @method static \Illuminate\Database\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User orWhere($column, $operator = null, $value = null)
 * @method static User create(array $attributes = [])
 * @method static User|null find($id, $columns = ['*'])
 * @method static User findOrFail($id, $columns = ['*'])
 * @method static int count($columns = '*')
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 */
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
