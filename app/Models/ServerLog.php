<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerLog extends Model
{
    protected $fillable = [
        'server_name',
        'status',
        'message',
        'checked_at'
    ];
}