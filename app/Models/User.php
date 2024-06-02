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

    protected $table = 'tbusers'; // Sesuaikan dengan nama tabel Anda

    protected $fillable = [
        'username', // Kolom untuk nama pengguna
        'password', // Kolom untuk kata sandi
        // tambahkan kolom lainnya yang mungkin perlu diisi
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
