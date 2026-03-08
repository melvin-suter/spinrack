<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobStatus extends Model
{
    protected $table = "job_status";
    
    protected $fillable = [
        'type',
        'reference_id',
        'status',
        'error',
        'started_at',
        'finished_at'
    ];
}
