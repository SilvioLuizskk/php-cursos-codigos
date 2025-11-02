<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;
    protected $fillable = ['nome'];
    protected $with = ['temporadas'];

    public function seasons()
    {
        return $this->hasMany(Season::class, 'serie_id');
    }

    public function temporadas()
    {
        return $this->hasMany(Season::class, 'serie_id');
    }

    protected static function booted()
    {
        self::addGlobalScope('ordenar', function (Builder $queryBuilder) {
            $queryBuilder->orderBy('nome');
        });
    }

}
