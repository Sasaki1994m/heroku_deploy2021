<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timestamp extends Model
{
    // protected $table = 'csv_attendances';

    protected $fillable = [
        'user_id', 'punch_in', 'punch_out',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
