<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use Session;
use Illuminate\Support\Facades\Storage;
use App\User;

class UserController extends Controller
{
    private $baseRouteName = 'user';
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        
        $this->middleware('adminauth');
    }

    /**
     * List all users.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex()
    {
      $users = User::all();
      
      return view('admin.user_list', ['users' => $users->toArray()]);
    }
    
    /**
     * Show new user form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showNewForm()
    {
      return view('admin.user_new_form');
    }
    
    /**
     * Create new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      $message = [
        'required' => 'This field is required.'
      ];
      $rules = [
        'name' => 'required|string',
        'email' => 'required|email',
        'password' => 'required|string',
        'confirmpassword' => 'required|same:password',
        'level' => 'required|in:Admin,CSO'
      ];
      $validator = Validator::make($request->all(), $rules, $message);
      
      if ($validator->fails()) {
        return back()->withInput()->withErrors($validator);
      }
      
      $user = new User;
      $user->name = $request->name;
      $user->email = $request->email;
      $user->password = bcrypt($request->password);
      $user->level = $request->level;
      $user->save();
      
      return redirect()->route($this->baseRouteName)->with('message', 'User Created');
    }
    
    /**
     * Show edit user form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showEditForm($id)
    {
      $result = User::find($id);
      
      return view('admin.user_edit_form', $result->toArray());
    }
    
    /**
     * Edit the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
      $message = [
        'required' => 'This field is required.'
      ];
      $rules = [
        'name' => 'required|string',
        'email' => 'required|email',
        'password' => 'nullable|string',
        'confirmpassword' => 'required_with:password|same:password',
        'level' => 'required|in:Admin,CSO'
      ];
      $validator = Validator::make($request->all(), $rules, $message);
      
      if ($validator->fails()) {
        return back()->withInput()->withErrors($validator);
      }
      
      $user = User::find($id);
      $user->name = $request->name;
      $user->email = $request->email;
      $user->level = $request->level;
      if (!empty($request->password)) {
        $user->password = bcrypt($request->password);
      }
      $user->save();
      
      return redirect()->route($this->baseRouteName)->with('message', 'User Updated');
    }
    
    /**
     * Delete the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
      User::destroy($id);
      
      return redirect()->route($this->baseRouteName)->with('message', 'User Deleted');
    }
}