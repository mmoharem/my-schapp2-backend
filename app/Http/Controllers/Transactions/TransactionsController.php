<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Payments\StudPayments;
use App\Models\Student;
use App\Models\StudTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class transactionsController extends ApiController
{
    private $paymentController;

    /**
     * Function Constructor
     */
    public function __construct(StudPayments $StudentPayment)
    {
        $this->paymentController = $StudentPayment;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $transactions =  StudTransaction::all();
////
////        return $this->showAll($transactions);
        return 'hello';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->type == 'suspend') {
            return 'type is suspend';
        } else {
            return $this->errorResponse('type undefined', 333);
        }
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

        if($request->type == 'suspend') {
            return $this->suspendPayment($request);

        } else if($request->type == 'hold') {
            return $this->holdPayment($request, 'add');

        } else if($request->type == 'confirmed-fees') {
            return $this->confPayment($request);

        } else {
            return $this->errorResponse('type undefined', 333);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function Oldstore(Request $request)
    {
        $rules = [
            'type' => 'required',
            'amount' => 'required',
            'student_id' => 'required'
        ];

        $this->validate($request, $rules);

        $student = Student::findOrFail($request->student_id);

        $transaction = new StudTransaction([
            'type' => $request->type,
            'amount' => $request->amount
        ]);

        $student->transactions()->save($transaction);

        $transactions = $student->transactions;

//        return $this->showOne($transaction, 201);
        return $this->showAll($transactions, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudTransaction  $studTransaction
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::findOrFail($id);

        $transactions = $student->user()->with('student.transactions', 'student.payment')->get()->pluck('student');
//        $transactions = $student->transactions;

        return $this->showAll($transactions);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudTransaction  $studTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(StudTransaction $studTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudTransaction  $studTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudTransaction $studTransaction)
    {
        $rules = [
            'type' => 'required',
            'amount' => 'required',
            'student_id' => 'required',
            'id' => 'required'
        ];

        $this->validate($request, $rules);

//        $student = Student::findOrFail($request->student_id);

        if($request->type == 'hold') {
            return $this->holdPayment($request, 'update');

        }elseif ($request->type == 'confirmed') {
            return $this->confPayment($request);

        }else {
            return $this->errorResponse('type undefined', 333);
        }
//        return $student;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudTransaction  $studTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudTransaction $transaction)
    {
        $transaction->delete();

        return $this->showOne($transaction);
    }

    /**
     *
     */
    public function suspendPayment($request) {
        $rules = [
            'amount' => 'required'
        ];

        $this->validate($request, $rules);

        $student = Student::findOrFail($request->student_id);

        $transaction = new StudTransaction([
            'type' => $request->type,
            'amount' => $request->amount
        ]);

        $student->transactions()->save($transaction);

        $transactions = $student->transactions;

        return $this->showAll($transactions, 201);

    }


    /**
     *
     */
    public function holdPayment(Request $request, $state) {
        $rules = [
            'amount' => 'required'
        ];

        $this->validate($request, $rules);

        $student = Student::findOrFail($request->student_id);

        if($state == 'add') {
            $transaction = new StudTransaction([
                'type' => $request->type,
                'amount' => $request->amount
            ]);

            $student->transactions()->save($transaction);
        }

        elseif($state == 'update') {
            $transaction = StudTransaction::findOrFail($request->id);

            $transaction->type = $request->type;
            $transaction->save();
        }

        $transactions = $student->transactions;

        return $this->showAll($transactions, 201);
    }


    /**
     *
     */
    public function confPayment(Request $request) {
        $rules = [
            'bank_date' => 'required',
            'bank_no' => 'required'
        ];

        $this->validate($request, $rules);

        $student = Student::findOrFail($request->student_id);

//        $payment = $student->payment;

        $transaction = StudTransaction::findOrFail($request->id);

        $transaction->type = $request->type;
        $transaction->bank_date = $request->bank_date;
        $transaction->bank_no = $request->bank_no;
        $transaction->save();

        $this->paymentController->calcStudTransPay($request->student_id);

        $transactions = $student->transactions;

        return $this->showAll($transactions, 201);
    }

}
