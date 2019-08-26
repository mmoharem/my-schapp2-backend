<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShClass extends Model
{
    public function teachers() {
        return $this->belongsToMany(Teacher::class);
    }
}
