<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'type',
        'description',
        'organizer_user_id',
        'quota',
        'registered_count',
        'start_date',
        'end_date',
        'location',
        'status'
    ];
}
