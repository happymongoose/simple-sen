<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Role;
use App\Models\Note;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;

//TODO: Add delete user
//On that delete, allocate any notes attached to students to admin; delete their noteboard notes
//Make sure user can't delete admin (or themselves)

//TODO: Check user can delete their own notes

//TODO: Limit what each standard user can see


class UsersController extends Controller
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      //Authorised users only
      $this->middleware('role:admin');

  }

  //Return specified user as JSON
  public function get(User $user) {

    //Remove unecessary fields from request (for security purposes)
    unset($user->password);
    unset($user->email_verified_at);
    unset($user->created_at);
    unset($user->updated_at);

    return $user;
  }

  public function index(Request $request) {

    //Get the pagination length (if set)
    $max_results = $request->input("max_results", 20);

    //Grab any search text passed by user
    $search_text = $request->input("search");

    //Get all the users
    $users = User::where('id', '>=', 0)->orderBy('name')->paginate($max_results);
    $users->append([ 'max_results' => $max_results]);

    //Get all the roles
    $roles = Role::all();

    //Show view
    return view('users.index', compact('users', 'max_results', 'roles'));

  }


  //-----------------------------------------------------------------------------------
  //
  // Store
  //
  //-----------------------------------------------------------------------------------

  public function store(CreateUserRequest $request) {

    //Get request data
    $params = $request->all();

    //Check passwords match
    if ($params['password']!=$params['password-confirm']) {
      notify()->error("Passwords didn't match");
      return redirect()->route('users.index');
    }

    $user = new User();
    $user->password = Hash::make($params['password']);
    $user->email = $params['email'];
    $user->name = $params['name'];
    $user->role_id = $params['role'];
    $user->save();

    notify()->success("Adult '" . $user->name . "' created");
    return redirect()->route('users.index');

  }

  //-----------------------------------------------------------------------------------
  //
  // Update
  //
  //-----------------------------------------------------------------------------------

  public function update(UpdateUserRequest $request) {

    //Get request data
    $params = $request->all();

    //Check passwords match (if passed)
    if (isset($params['password'])) {

      //If confirmation password not set, create one that is incorrect
      if (!isset($params['password-confirm']))
        $params['password-confirm']=$params['password'] . "X";

      //Throw out passwords that don't match
      if ($params['password']!=$params['password-confirm']) {
        notify()->error("Passwords didn't match");
        return redirect()->route('users.index');
      }

    }

    //Grab the user
    $user = User::find($params['id']);
    $user->email = $params['email'];
    $user->name = $params['name'];
    $user->role_id = $params['role'];
    if (isset($params['password']))
      $user->password = Hash::make($params['password']);

    $user->save();

    notify()->success("Adult '" . $user->name . "' updated");
    return redirect()->route('users.index');

  }


  //-----------------------------------------------------------------------------------
  //
  // Delete
  //
  //-----------------------------------------------------------------------------------

  public function delete(Request $request) {

    //Get request data
    $params = $request->all();
    $id = $request->input('id', null);

    //Null user?
    if ($id==null) {
      notify()->error("An error occurred.");
      return redirect()->route('users.index');
    }

    //Can't delete original admin user
    if ($id==1) {
      notify()->error("Can't delete primary admin user.");
      return redirect()->route('users.index');
    }

    //Stop the user from deleting themselves
    $user_id = Auth::user()->id;
    if ($user_id==$id) {
      notify()->error("A user cannot delete their own account.");
      return redirect()->route('users.index');
    }

    //Delete any personal notes belonging to this user
    Note::where('class', 'user')->where('instance_id', $id)->delete();
    //Transfer any other notes to the admin user
    Note::where('user_id', $id)->update(['user_id'=>1]);

    //Delete user
    $user = User::find($id);
    $name = $user->name;
    $user->delete();

    notify()->success("Adult '" . $name . "' deleted");
    return redirect()->route('users.index');


  }



}
