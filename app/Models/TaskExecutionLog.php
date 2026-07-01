<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskExecutionLog extends Model
{
    public $timestamps = false;
    protected $fillable = ['command_signature', 'status', 'memory_used', 'output', 'executed_at'];
    protected $casts = ['executed_at' => 'datetime'];
}