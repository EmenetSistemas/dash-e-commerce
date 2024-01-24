<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblCarritoCompras extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'pkTblCarritoCompras';
    protected $table = 'tblCarritoComrpas';
    protected $fillable = 
    [
        'pkTblCarritoCompras',
	    'idItem',
	    'cantidad'
    ];
}