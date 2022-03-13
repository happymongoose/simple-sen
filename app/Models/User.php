<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



//TODO: When a user is deleted, allocate all of their notes to the admin user

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Return the role object
    public function role() {
      return $this->belongsTo('App\Models\Role', 'role_id', 'role_id');
    }
    //Return the role ID only
    public function role_id() {
      return $this->role()->role_id;
    }
    //Return true if user is admin (i.e. role ID is 0)
    public function isAdmin() {
      return $this->role_id==0;
    }
    //Return true if the user is a standard user (i.e. not admin - where role ID is 1)
    public function isUser() {
      return $this->role_id==1;
    }

    //Get the user's name in title case
    public function getName() {
      return ucwords($this->name);
    }
}
