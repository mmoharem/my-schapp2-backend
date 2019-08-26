<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\ApiController;
use App\Models\SchFees;
use App\Models\ShGrade;
use App\Models\Student;
use App\Models\StudPayment;
use App\Models\StudTransaction;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudPayments extends ApiController
{
    /**
     * Init Payment for each student
     */
    public function initStudPayment($fees) {

        $payment = new StudPayment();

        if($fees->totFees) {
            $payment->fees = $fees->totFees;
            $payment->isOldFees = false;
        } else {
            $payment->fees = $fees->old_totFees;
            $payment->isOldFees = true;
        }

        $payment->to_pay_fees = $payment->fees;

        $payment->grade_name = $fees->grade->name;

        $payment->sch_year_id = $fees->sch_year_id;

        return $payment;
    }

     /**
      * On Update
     * Init Payment for each student
     */
    public function initFeesPayment($fees, $payment) {

        if($fees->sch_year_id !== $payment->sch_year) {
            return $this->initStudPayment($fees);
        }

        if($fees->totFees) {
            $payment->fees = $fees->totFees;
            $payment->isOldFees = fale;
        } else {
            $payment->fees = $fees->old_totFees;
            $payment->isOldFees = true;
        }

        if($payment)

        return $payment;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments =  StudPayment::all();

        return $this->showAll($payments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'type' => 'required',
            'amount' => 'required',
            'student_id' => 'required'
        ];

        $this->validate($request, $rules);

        $student = Student::findOrFail($request->student_id);

        $payment = new StudPayment([
            'type' => $request->type,
            'amount' => $request->amount
        ]);

        $student->payments()->save($payment);

        return $this->showOne($payment, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::findOrFail($id);

//        $tranactions = $student->transactions()->where('sch_year_id', 1)->with('student.schYear_id')->get();
        $tranactions = $student->transactions()->where('sch_year_id', $student->schYear_id)->get();

        return $this->showAll($tranactions);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    public function calcStudTransPay($id) {

        $total_payments = 0;

        $student = Student::findOrFail($id);
        $payment = $student->payment()->where('sch_year_id', $student->schYear_id)->get()->first();

        $tranactions = $student->transactions()->where('sch_year_id', $student->schYear_id)->get();

        foreach ($tranactions as $transaction) {

            if($transaction->type == 'confirmed') {
                $total_payments += $transaction->amount;
            } else {
                $total_payments = 0;
            }
        }


        $payment->total_payments = $total_payments;

        if($payment->discount_verify) {
            $payment->to_pay_fees = $payment->fees - $total_payments - $payment->discount;
        } elseif ($payment->old_unpaid){
            $payment->to_pay_fees = $payment->fees + $payment->old_unpaid - $total_payments;
        }
        else {
            $payment->to_pay_fees = $payment->fees - $total_payments;
        }

        $payment->save();

//        $student->payment()->save($payment);

//        return $tot_paid_fees;
//        return $tranactions ;
//        return $total_payments;
        return $payment;
    }

}
