<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Semua role terdaftar memiliki akses ke backoffice / mobile panel sesuai wewenangnya
        return true;
    }

    public function roleLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->role) {
                'admin' => 'Administrator Pusat',
                'supervisor' => 'Supervisor Kolam',
                'teknisi_utama' => 'Teknisi Utama Tambak',
                'asisten_teknisi' => 'Asisten Teknisi Lapangan',
                'operator_pakan' => 'Operator Pakan Harian',
                default => ucfirst(str_replace('_', ' ', $this->role ?? 'Teknisi')),
            }
        );
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSupervisor(): bool
    {
        return in_array($this->role, ['admin', 'supervisor']);
    }

    public function feedingLogs(): HasMany
    {
        return $this->hasMany(FeedingLog::class, 'recorded_by');
    }

    public function waterQualityLogs(): HasMany
    {
        return $this->hasMany(WaterQualityLog::class, 'recorded_by');
    }
}
