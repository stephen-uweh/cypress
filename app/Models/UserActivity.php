<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'userId',
        'title',
        'description',
        'image',
        'date'
    ];

    protected $casts = [
        'date' => 'datetime:d-m-Y'
    ];

}
