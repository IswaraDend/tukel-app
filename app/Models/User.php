<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    /**
     * Kolom yang bisa diisi mass-assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'is_active',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi (JSON, API response, dsb).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting kolom agar otomatis dikonversi ke tipe data tertentu.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Override kunci primari agar tidak auto increment.
     */
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Relasi contoh (optional)
     *  - User bisa punya banyak Team (sebagai owner)
     *  - User bisa punya banyak Assignment yang dia buat
     */
    // public function teams()
    // {
    //     return $this->hasMany(Team::class, 'owner_id');
    // }

    // public function assignments()
    // {
    //     return $this->hasMany(Assignment::class, 'created_by');
    // }
}
