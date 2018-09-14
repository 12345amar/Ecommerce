<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Permission;
use App\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class UserController extends Controller
{
    use Authorizable;

    /**
     * Display all user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = User::latest()->paginate();
        return view('user.index', compact('result'));
    }

    /**
     * Open new user form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'id');
        return view('user.new', compact('roles'));
    }

    /**
     * Create new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'bail|required|min:2',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6',
            'mobile'    => 'required|digits:10|unique:users',
            'roles'     => 'required|min:1'
        ]);
        
        $user   = new User();
        if ($user = $user->createUser($request)) {
            $this->syncPermissions($request, $user);
            Session::flash('success', 'User has been created successfully.');
        } else {
            Session::flash('error', 'Unable to create user.');
        }

        return redirect()->route('users.index');
    }

    /**
     * Display user information.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user       = User::findOrFail($id);
        $userImage  = User::getUserImage($user->avatar);
        return view('user.show', compact('user', 'userImage'));
    }

    /**
     * Open edir user form.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user           = User::find($id);
        $roles          = Role::pluck('name', 'id');
        $userImage      = User::getUserImage($user->avatar);
        $permissions    = Permission::all('name', 'id');

        return view('user.edit', compact('user', 'permissions', 'roles', 'userImage'));
    }

    /**
     * Update user based on provided user id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'      => 'bail|required|min:2',
            'email'     => 'required|email|unique:users,email,' . $id,
            'roles'     => 'required|min:1'
        ]);

        $user = new User();
        if($user = $user->updateUser($request, $id)) {
            $this->syncPermissions($request, $user);
            $user->save();
            Session::flash('success', 'User has been created successfully.');
        } else {
            Session::flash('error', 'Unable to create user.');
        }
        
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->id == $id) {
            flash()->warning('Deletion of currently logged in user is not allowed :(')->important();
            return redirect()->back();
        }
        $user   = new User();
        if ($user->deleteUser($id)) {
            Session::flash('success', 'User has been deleted successfully.');
            return redirect()->route('users.index');
        } else {
            Session::flash('error', 'Unable to delete user, try again.');
            return redirect()->route('users.index');
        }
    }

    /**
     * Sync roles and permissions
     *
     * @param Request $request
     * @param $user
     * @return string
     */
    private function syncPermissions(Request $request, $user)
    {
        // Get the submitted roles
        $roles = $request->get('roles', []);
        $permissions = $request->get('permissions', []);

        // Get the roles
        $roles = Role::find($roles);

        // check for current role changes
        if( ! $user->hasAllRoles( $roles ) ) {
            // reset all direct permissions for user
            $user->permissions()->sync([]);
        } else {
            // handle permissions
            $user->syncPermissions($permissions);
        }

        $user->syncRoles($roles);

        return $user;
    }
    
   
}
