<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reception extends Model
{
    protected $fillable = [
        'date',
        'time_slot',
        'in_out',
        'name',
        'cage_count',
        'type',
        'category',
        'pickup_at',
        'memo',
    ];
}
