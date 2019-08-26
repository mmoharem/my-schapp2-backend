<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    protected $fillable = [
        'relation', 'edu_degree', 'profession', 'job', 'company_name', 'work_phone', 'position'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function students() {
        return $this->belongsToMany(Student::class, 'parent_student');
    }

}
