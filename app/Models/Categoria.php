<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriaFactory> */
    use HasFactory;

    protected $fillable = [
        'pai_id',
        'nome',
        'descricao'
    ];

   
    public function subCategorias(): HasMany
    {
        return $this->hasMany(Categoria::class, 'pai_id', 'id');
    }
}
