<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    public function pacijent()
    {
    return $this->hasOne(Pacijent::class, 'user_id');
    }

    public function kartoni()
    {
    return $this->hasMany(ZdravstveniKarton::class, 'user_id');
    }
    public function isDoktor()
    {
        return $this->role === 'doktor';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPacijent()
    {
        return $this->role === 'pacijent';
    }
    // app/Models/User.php
    public function createToken(string $name)
    {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable')
                ->create(['name' => $name, 'token' => Str::random(40)]);
    }

}
