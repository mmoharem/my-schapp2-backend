<?php

namespace App;

use App\Models\Employee;
use App\Models\Image;
use App\Models\Parents;
use App\Models\Salary;
use App\Models\Student;
use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstName', 'lastName', 'fullName', 'address', 'gender', 'nationality', 'birthDate', 'email', 'phoneNumber', 'mobilePhone',
        'medicalState', 'notes', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     public function student() {
         return $this->hasOne(Student::class, 'user_id')->
             where('schYear_id', 1);

     }public function parent() {
         return $this->hasOne(Parents::class, 'user_id');
     }

     public function images() {
         return $this->morphToMany(Image::class, 'imageable');
     }

     public function employee() {
         return $this->hasOne(Employee::class, 'user_id');
     }

//    public function filter(Request $request, User $user) {
//
//         $user = $user->newQuery();
//
//         if($request->has(firstName)) {
//             $user->where('firstName', $request->firstName);
//         }
//
//         return $user->get();
//
//    }
}
