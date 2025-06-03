<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ZdravstveniKarton extends Model
{
    use HasFactory;

    protected $fillable = [
        'pacijent_id',
        'user_id',
        'visina',
        'tezina',
        'krvni_pritisak',
        'dijagnoza',
        'tretman',
    ];
    protected $table = 'zdravstveni_kartoni';


    public function pacijent()
    {
        return $this->belongsTo(Pacijent::class);
    }

    public function lekar()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pregledi()
    {
        return $this->hasMany(Pregled::class, 'karton_id');
    }
}
