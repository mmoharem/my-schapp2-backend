<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudAttendance extends Model
{
    protected $fillable = [
        'attend', 'day'
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function schYear() {
        return $this->belongsTo(School_year::class);
    }
}
