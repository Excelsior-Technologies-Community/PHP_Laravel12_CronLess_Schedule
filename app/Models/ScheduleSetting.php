<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleSetting extends Model
{
    protected $fillable = ['command_signature', 'interval_type', 'is_active'];
}