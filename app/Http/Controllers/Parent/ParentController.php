<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\UserController;
use App\Http\Requests\ParentRegRequest;
use App\Models\Parents;
use App\Models\Student;
use App\Repositories\BaseRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ParentController extends ApiController
{
    private $parentsRepo;
    private $usersRepo;
    private $studentsRepo;
    private $userController;

    public function __construct
    (
        Parents $parents,
        User $user,
        Student $student,
        UserController $UserController
    )
    {
        $this->parentsRepo = new BaseRepository($parents);
        $this->usersRepo = new BaseRepository($user);
        $this->studentsRepo = new BaseRepository($student);
        $this->userController = $UserController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $parents = $this->usersRepo->whereWith($parents)
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ParentRegRequest $request)
    {
        $user = $this->userController->store($request);

        $student = $this->studentsRepo->find($request->student_id);

        $parent = $this->parentsRepo->createUpdate($request->only($this->parentsRepo->getModel()->fillable));

        $user->parent()->save($parent);

        $student->parents()->syncWithoutDetaching($parent);

        $userStud = $student->user()->with('images', 'student.parents.user', 'student.grade.fees')->get();

        return $this->showAll($userStud, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Parents  $parents
     * @return \Illuminate\Http\Response
     */
//    public function show(Parents $parents)
    public function show($id)
    {
        $parent = $this->parentsRepo->find($id);


        $user = $parent->user()->with('images', 'parent.students')->get();

        return $this->showAll($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Parents  $parents
     * @return \Illuminate\Http\Response
     */
    public function edit(Parents $parents)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Parents  $parents
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Parents $parents)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Parents  $parents
     * @return \Illuminate\Http\Response
     */
    public function destroy(Parents $parents)
    {
        //
    }

    public function addSibling(Request $request) {

        $rules = [
            'parents_id' => 'required',
            'student_id' => 'required'
        ];

        $this->validate($request, $rules);

        $student = $this->studentsRepo->find($request->student_id);

        $parents_id = $request->parents_id;


        foreach ($parents_id as $parentId) {

            $parent = $this->parentsRepo->find($parentId);

            $parent->students()->syncWithoutDetaching($student);
        }

        $user = $parent->user()->with('images', 'parent.students')->get();

        return $this->showAll($user);
    }

    public function showSiblings($id) {
        $parent = $this->parentsRepo->find($id);

//        $user = $parent->user()->with('images', 'parent.students')->get();

        $siblings = $parent->students()->with('user.images', 'user.student.parents', 'user.student.grade')->get()->pluck('user');

        return $this->showAll($siblings);
    }
}
