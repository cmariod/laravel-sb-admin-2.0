<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use Session;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private $tableName = 'users';
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
    public function index()
    {
      $users = DB::table($this->tableName)->select('id', 'name', 'email')->get();
      return view('admin.user_list', ['users' => json_decode(json_encode($users), true)]);
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
      
      DB::table($this->tableName)->insert([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => bcrypt($request->input('password')),
        'level' => $request->input('level'),
      ]);
      
      return redirect()->route($this->baseRouteName)->with('message', 'User Created');
    }
    
    /**
     * Show edit user form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showEditForm($id)
    {
      $result = DB::table($this->tableName)->where('id', '=', $id)->get();
      
      return view('admin.user_edit_form', json_decode(json_encode($result[0]), true));
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
      
      $updateFields = [
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'level' => $request->input('level'),
      ];
      
      if (!empty($request->input('password'))) {
        $updateFields['password'] = bcrypt($request->input('password'));
      }
      
      DB::table($this->tableName)->where('id', '=', $id)->update($updateFields);
      
      return redirect()->route($this->baseRouteName)->with('message', 'User Updated');
    }
    
    /**
     * Delete the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
      DB::table($this->tableName)->where('id', '=', $id)->delete();
      
      return redirect()->route($this->baseRouteName)->with('message', 'User Deleted');
    }
}