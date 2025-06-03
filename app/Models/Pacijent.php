<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pacijent extends Model
{
    use HasFactory;

    // Dozvoljena polja za unos preko mass assignment-a
    protected $fillable = [
        'user_id',
        'ime',
        'prezime',
        'jmbg',
        'datum_rodjenja',
        'pol',
        'telefon',
        'email',
        'istorija_pacijenta',
    ];
    protected $table = 'pacijenti';

    // Relacija 1:1 – jedan pacijent ima jedan zdravstveni karton
    public function zdravstveniKarton()
    {
        return $this->hasOne(ZdravstveniKarton::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
