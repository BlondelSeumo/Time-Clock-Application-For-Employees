<?php
/*
* Workday - A time clock application for employees
* Email: official.codefactor@gmail.com
* Version: 1.1
* Author: Brian Luna
* Copyright 2020 Codefactor
*/
namespace App\Http\Controllers\admin;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;


class UsersController extends Controller
{
    public function index()
    {
        if (permission::permitted('users')=='fail'){ return redirect()->route('denied'); }

        $users_roles = table::users()->join('users_roles', 'users.role_id', '=', 'users_roles.id')->select('users.*', 'users_roles.role_name')->get();
        $users = table::users()->get();
        $roles = table::roles()->get();
        $employees = table::people()->get();

        return view('admin.users', compact('users', 'roles', 'employees', 'users_roles'));
    }

    public function register(Request $request)
    {
        if (permission::permitted('users-add')=='fail'){ return redirect()->route('denied'); }

        $v = $request->validate([
            'ref' => 'required|max:100',
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'role_id' => 'required|digits_between:1,99|max:2',
            'acc_type' => 'required|digits_between:1,99|max:2',
            'password' => 'required|min:8|max:100',
            'password_confirmation' => 'required|min:8|max:100',
            'status' => 'required|boolean|max:1',
        ]);

        $ref = $request->ref;
        $name = $request->name;
    	$email = $request->email;
		$role_id = $request->role_id;
		$acc_type = $request->acc_type;
		$password = $request->password;
		$password_confirmation = $request->password_confirmation;
		$status = $request->status;

        if ($password != $password_confirmation) 
        {
            return redirect('users')->with('error', trans("Whoops! Password confirmation does not match!"));
        }

        $is_user_exist = table::users()->where('email', $email)->count();

        if($is_user_exist >= 1) 
        {
            return redirect('users')->with('error', trans("Whoops! this user already exist"));
        }

        $idno = table::companydata()->where('reference', $ref)->value('idno');

    	table::users()->insert([
    		[
                'reference' => $ref,
                'idno' => $idno,
				'name' => $name,
				'email' => $email,
				'role_id' => $role_id,
				'acc_type' => $acc_type,
				'password' => Hash::make($password),
				'status' => $status,
            ],
    	]);

    	return redirect('/users')->with('success', trans("New user has been added."));
    }

    public function edit($id) 
    {
        if (permission::permitted('users-edit')=='fail'){ return redirect()->route('denied'); }
        
        $u = table::users()->where('id', $id)->first();
        $r = table::roles()->get();
        $e_id = ($u->reference == null) ? 0 : Crypt::encryptString($u->reference) ;

        return view('admin.edits.edit-user', compact('u', 'r', 'e_id'));
    }

    public function update(Request $request) 
    {
        if (permission::permitted('users-edit')=='fail'){ return redirect()->route('denied'); }

        $v = $request->validate([
            'ref' => 'required|max:200',
            'role_id' => 'required|digits_between:1,99|max:2',
            'acc_type' => 'required|digits_between:1,99|max:2',
            'status' => 'required|boolean|max:1',
        ]);

        $ref = Crypt::decryptString($request->ref);
		$role_id = $request->role_id;
		$acc_type = $request->acc_type;
        $password = $request->password;
        $password_confirmation = $request->password_confirmation;
        $status = $request->status;

        if ($password !== null && $password_confirmation !== null) 
        {
            $v = $request->validate([
                'password' => 'required|min:8|max:100',
                'password_confirmation' => 'required|min:8|max:100',
            ]);

            if ($password != $password_confirmation) 
            {
                return redirect('users')->with('error', trans("Whoops! Password confirmation does not match!"));
            }

            table::users()->where('reference', $ref)->update([
                'role_id' => $role_id,
                'acc_type' => $acc_type,
                'status' => $status,
                'password' => Hash::make($password),
            ]);
        } else {
            table::users()->where('reference', $ref)->update([
                'role_id' => $role_id,
                'acc_type' => $acc_type,
                'status' => $status,
            ]);
        }

    	return redirect('users')->with('success', trans("User Account has been updated!"));       
    }

    public function delete($id, Request $request)
    {
        if (permission::permitted('users-delete')=='fail'){ return redirect()->route('denied'); }

    	table::users()->where('id', $id)->delete();
    	
        return redirect('users')->with('success', trans("User Account has been deleted!"));
    }
}
