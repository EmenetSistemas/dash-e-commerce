<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatStatusPedidos extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'pkCatStatus';
    protected $table = 'catStatusPedido';
    protected $fillable = 
    [
        'pkCatStatus',
        'nombreStatus',
        'tituloStatus',
	    'descripcionStatus'
    ];
}