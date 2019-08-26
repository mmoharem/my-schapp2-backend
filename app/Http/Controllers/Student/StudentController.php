<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Payments\StudPayments;
use App\Http\Controllers\UserController;
use App\Http\Requests\StudentRegisterRequest;
use App\Models\Image;
use App\Models\School_year;
use App\Models\Student;
use App\Models\ShGrade;
use App\Repositories\BaseRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends ApiController
{

    private $studentRepo;
    private $userRepo;
    private $gradeRepo;
    private $imgRepo;
    private $userController;
    private $paymentController;

    public function __construct
    (
        Student $student,
        User $user,
        ShGrade $grade,
        Image $image,
        UserController $userController,
        StudPayments $PaymentContr
    )
    {
        $this->studentRepo = new BaseRepository($student);
        $this->userRepo = new BaseRepository($user);
        $this->gradeRepo = new BaseRepository($grade);
        $this->imgRepo = new BaseRepository($image);
        $this->userController = $userController;
        $this->paymentController = $PaymentContr;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

//        $userStud = Student::where('schYear_id', 1)->with('user.student.grade')->get()->pluck('user');

//        $student = $this->studentRepo->with(['user.images', 'grade.fees', 'payment'])->get();

        $student = $this->userRepo->whereWith('student', ['images', 'student.grade.fees', 'student.payment', 'student.parents'])->get();

        return $this->showAll($student);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentRegisterRequest $request)
    {

        $user = $this->userController->store($request);

        $student = $this->studentRepo->createUpdate($request->only($this->studentRepo->getModel()->fillable));

        $user->student()->save($student);

        $grade = $this->gradeRepo->find($request->grade_id);

        $student->grade()->associate($grade)->save();

        return $this->showOne($student, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $student = $this->studentRepo->find($id);

        $user = $student->user()->with('images', 'student.grade.fees', 'student.parents')->get();

        return $this->showAll($user);

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
        $rules = [

            'image_id' => 'required'
        ];

        $this->validate($request, $rules);

        $student = Student::findOrFail($id);

        $image = Image::findOrFail($request->image_id);

        $user = $student->user;

        $user->images()->save($image);

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        $user = $student->user;

        $student->delete();
        $user->delete();

        return $student;
    }

    public function testPayment($id) {
        $student = $this->studentRepo->find($id);

        $fees = $student->grade->fees;

        $payment = $this->paymentController->initStudPayment($fees);

        $student->payment()->save($payment);

        $userStud = $student->user()->with('student.payment')->get();

        return $userStud;
    }
}
