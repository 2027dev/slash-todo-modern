<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'completed', 'position'];

    protected $casts = [
        'completed' => 'boolean',
    ];
}
