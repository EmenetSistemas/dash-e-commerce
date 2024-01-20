<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatApartados extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'pkCatApartado';
    protected $table = 'catApartados';
    protected $fillable = 
    [
        'pkCatApartado',
	    'fkCatCategoria',
	    'nombre',
	    'descripcion'
    ];
}