<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatCaracteristicas extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'pkCatCaracteristica';
    protected $table = 'CatCaracteristicas';
    protected $fillable = 
    [
        'pkCatCaracteristica',
        'nombre',
        'icono'
    ];
}