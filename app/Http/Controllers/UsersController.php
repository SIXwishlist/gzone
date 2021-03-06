<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;


class UsersController extends Controller
{
    protected $user_password;

    // Only authenticated users can access these services
    public function __construct() 
    {
    	$this->middleware('auth');
    }

    // Main Page listing all the users
    public function index() 
    {
    	return view('users.index');
    }

    public function create() 
    {
      $roles = Role::pluck('display_name', 'id');
      
      return view('users.create', compact('roles'));
    }

    public function store(Request $req) 
    {        
        $this->validate($req, [
            'name'    => 'required|string|min:4|max:100',
            'email'   => 'required|string|email|max:255|unique:users|confirmed',            
            'role_id' => 'required|exists:roles,id'
        ]);

        // $vfrom = Carbon::createFromFormat("m/d/Y h:i a",$req['active_from']);
        // $vtill = Carbon::createFromFormat('m/d/Y h:i a',$req['active_till']);        
        
        $this->user_password="";
        $this->user_password="TestPassword@2017";
        $user = User::create([
            'name'      => $req['name'],
            'email'     => $req['email'],
            'password'  => bcrypt($this->user_password)
        ]);       
        
        // Update relationtables
        $user->updateRelation($req['role_id'],$user->id);    
        
        // $project = Project::create($pdata);        
        // $project->users()->attach($req['user_id'], ['active_from' => $vfrom, 'active_till' => $vtill]);
        return redirect(route('users_index'));
    }

    public function edit(User $user) 
    {
        $roles = Role::pluck('display_name', 'id');
        return view('users.edit', compact('user','roles'));
    }

    public function update(User $user, Request $req) 
    {
        $this->validate($req,[
            'name'    => 'required|string|min:4|max:100',            
            'role_id' => 'required|exists:roles,id'
        ]);      
        $user->updateRelation($req['role_id'],$user->id);
        return redirect(route('users_index'));
    }

    // Ajax call handler for Users table data
    public function uData() 
    {
    	$users = User::select(['id', 'name', 'email']);

        return DataTables::of($users)                
                ->addColumn('action', function($user){
                    $link = "<a href='". route('user_edit',$user->id)."' class='btn btn-xs btn-primary'>Edit</a>";                     
                    return $link;
                })                               
                ->addColumn('permission', function($user){
                    return $user->getAllRoles();
                })
                ->make(true);                  	
    }

}
