<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class voiture extends Model
{
    protected $table = 'voitures';

    protected $primaryKey = 'idvoit';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    protected $fillable = [
        'idvoit',
        'design',
        'type',
        'nbrplace',
        'frais'
    ];

    public function places()
    {
        return $this->hasMany(place::class, 'idvoit', 'idvoit');
    }

    public function reservations()
    {
        return $this->hasMany(reservation::class, 'idvoit', 'idvoit');
    }
}
