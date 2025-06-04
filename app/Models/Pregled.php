<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregled extends Model
{
    use HasFactory;

    protected $fillable = [
        'karton_id',
        'lekar_id',
        'datum',
        'tip_pregleda',
        'opis',
    ];

    protected $table = 'pregledi';

    public function karton()
    {
        return $this->belongsTo(ZdravstveniKarton::class, 'karton_id');
    }

    public function lekar()
    {
        return $this->belongsTo(User::class, 'lekar_id');
    }
}
