<?php

namespace App\Models;

use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory, UuidGenerator;

    protected $fillable = [
        'title',
        'description',
        'image',
        'date'
    ];

    protected $casts = [
        'date' => 'datetime:d-m-Y'
    ];
}
