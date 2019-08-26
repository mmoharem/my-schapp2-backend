<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Models\Image;
use App\Repositories\BaseRepository;
use App\Repositories\UserRepositoriesInterface;
use App\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    private $userRepo;
    private $imageRepo;

    // Constract Function
    public function __construct
    (
        User $user,
        Image $image
    )
    {
        $this->userRepo = new BaseRepository($user);
        $this->imageRepo = new BaseRepository($image);
    }

    //
    public function user(Request $request) {
        return $request->user();
    }

    // Show All Users
    public function index () {
        return $this->userRepo->getAllUsers();
    }

    // Find User
    public function show($id) {
        return $this->userRepo->getUsersById($id);
    }

    // Create User
    public function store(Request $request) {

        $this->validateUser($request);

        $user = $this->userRepo->createUpdate($request->only($this->userRepo->getModel()->fillable));

        $image = $this->imageRepo->find($request->image_id);

        $user->images()->save($image);

        return $user;
    }

    // Update User
    public function update(Request $data, $id) {
        $user = $this->userRepo->CreateUpdateUser($data, $id);

        return $data->firstName;
    }

    // Delete User
    public function destroy($id) {
        $this->userRepo->DeleteUser($id);

        return 'user deleted';
    }

    // Validate Request
    public function validateUser($request) {
        $rules = [
            'firstName' => 'required',
            'lastName' => 'required',
            'fullName' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'nationality' => 'required',
            'birthDate' => 'required',
            'email' => 'required|email',
            'phoneNumber' => 'required',
            'mobilePhone' => 'required',
            'password' => 'required|confirmed|min:6',
            'image_id' => 'required'
        ];

        return $this->validate($request, $rules);
    }

}
