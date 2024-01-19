<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblMetodosPago extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'tblMetodosPago';
    protected $fillable = 
    [
        'fkTblUsuario',
	    'emisor',
	    'tipo',
	    'noTarjeta',
        'statusActual'
    ];
}