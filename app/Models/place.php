<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class place extends Model
{
    protected $table = 'places';
    public $incrementing  = false;
    public $timestamps = false;
    protected $fillable = [
        'idvoit',
        'place',
        'occupation'
        ];
    

    public function voiture()
    {
        return $this->belongsTo(
            voiture::class,
            'idvoit',
            'idvoit'
        );
    }

}
