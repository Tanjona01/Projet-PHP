<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class client extends Model
{
    protected $table = 'client';

    protected $primaryKey = 'idcli';

    public $incrementing = true;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'nom',
        'numtel'
    ];
}

