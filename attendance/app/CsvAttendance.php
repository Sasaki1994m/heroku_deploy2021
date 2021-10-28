<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CsvAttendance extends Model
{

    protected $fillable = [
        'year', 'month', 'day',
        'work_start', 'work_end', 'break_time',
        'user_id', 'punch_in', 'punch_out','status',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
