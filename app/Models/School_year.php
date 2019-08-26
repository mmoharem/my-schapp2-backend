<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School_year extends Model
{
    protected $fillable = ['title', 'year'];

    public function students() {
        return $this->hasMany(Student::class, 'schYear_id');
//            ->where('schYear_id', '=', 1);
    }

    public function schFees() {
        return $this->hasMany(SchFees::class, 'sch_year_id');
    }

    public function studPayments() {
        return $this->hasMany(StudPayment::class, 'sch_year_id');
    }

    public function studTransactions() {
        return $this->hasMany(StudTransaction::class, 'sch_year_id');
    }
}
