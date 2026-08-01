<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reservation extends Model
{
    protected $table = 'reservations';

    protected $primaryKey = 'idreserv';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'idreserv',
        'idvoit',
        'idcli',
        'place',
        'date_reserv',
        'date_voyage',
        'payement',
        'montant_avance',
    ];

    public function voiture()
    {
        return $this->belongsTo(voiture::class, 'idvoit', 'idvoit');
    }

    public function client()
    {
        return $this->belongsTo(client::class, 'idcli', 'idcli');
    }
}
