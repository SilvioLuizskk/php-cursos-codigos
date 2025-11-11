<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    // Campos permitidos para mass-assignment
    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'position',
        'status',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];
}
