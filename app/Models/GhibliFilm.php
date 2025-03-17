<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GhibliFilm extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'director',
        'release_year',
        'runtime',
        'box_office',
        'is_classic',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'release_year' => 'integer',
        'runtime' => 'integer',
        'box_office' => 'integer',
        'is_classic' => 'boolean',
    ];
}
