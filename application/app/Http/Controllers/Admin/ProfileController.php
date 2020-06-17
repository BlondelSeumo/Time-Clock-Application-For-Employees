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
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{

    public function view($id, Request $request)
    {
		if (permission::permitted('employees-view')=='fail'){ return redirect()->route('denied'); }
		
		$p = table::people()->where('id', $id)->first();
		$c = table::companydata()->where('reference', $id)->first();
		$i = table::people()->select('avatar')->where('id', $id)->value('avatar');
		$leavetype = table::leavetypes()->get();
		$leavegroup = table::leavegroup()->get();

        return view('admin.profile-view', compact('p', 'c', 'i', 'leavetype', 'leavegroup'));
    }

   	public function delete($id, Request $request)
    {
		if (permission::permitted('employees-delete')=='fail'){ return redirect()->route('denied'); }

		return view('admin.delete-employee', compact('id'));
   	}

	public function clear(Request $request) 
	{
		if (permission::permitted('employees-delete')=='fail'){ return redirect()->route('denied'); }
		
		$id = $request->id;
		table::people()->where('id', $id)->delete();
		table::companydata()->where('reference', $id)->delete();
		table::attendance()->where('reference', $id)->delete();
		table::schedules()->where('reference', $id)->delete();
		table::leaves()->where('reference', $id)->delete();
		table::users()->where('reference', $id)->delete();

		return redirect('employees')->with('success', trans("Employee information has been deleted!"));
	}

   	public function archive($id, Request $request)
    {
		if (permission::permitted('employees-archive')=='fail'){ return redirect()->route('denied'); }

		$id = $request->id;
		table::people()->where('id', $id)->update(['employmentstatus' => 'Archived']);
		table::users()->where('reference', $id)->update(['status' => '0']);

    	return redirect('employees')->with('success', trans("Employee information has been archived!"));
   	}

	public function editPerson($id)
    {
		if (permission::permitted('employees-edit')=='fail'){ return redirect()->route('denied'); }

		$company_details = table::companydata()->where('id', $id)->first();
		$person_details = table::people()->where('id', $id)->first();
		$company = table::company()->get();
		$department = table::department()->get();
		$jobtitle = table::jobtitle()->get();
		$leavegroup = table::leavegroup()->get();
		$e_id = ($person_details->id == null) ? 0 : Crypt::encryptString($person_details->id) ;

        return view('admin.edits.edit-personal-info', compact('company_details', 'person_details', 'company', 'department', 'jobtitle', 'leavegroup', 'e_id'));
    }

    public function updatePerson(Request $request)
    {
		if (permission::permitted('employees-edit')=='fail'){ return redirect()->route('denied'); }

		$v = $request->validate([
			'id' => 'required|max:200',
			'lastname' => 'required|alpha_dash_space|max:155',
			'firstname' => 'required|alpha_dash_space|max:155',
			// 'mi' => 'required|alpha_dash_space|max:155',
			// 'age' => 'required|digits_between:0,199|max:3',
			// 'gender' => 'required|alpha|max:155',
			'emailaddress' => 'required|email|max:155',
			// 'civilstatus' => 'required|alpha|max:155',
			// 'height' => 'required|digits_between:0,299|max:3',
			// 'weight' => 'required|digits_between:0,999|max:3',
			// 'mobileno' => 'required|max:155',
			// 'birthday' => 'required|date|max:155',
			// 'nationalid' => 'required|max:155',
			// 'birthplace' => 'required|max:255',
			// 'homeaddress' => 'required|max:255',
			// 'company' => 'required|alpha_dash_space|max:100',
			// 'department' => 'required|alpha_dash_space|max:100',
			// 'jobposition' => 'required|alpha_dash_space|max:100',
			// 'companyemail' => 'required|email|max:155',
			// 'leaveprivilege' => 'required|max:155',
			'idno' => 'required|max:155',
			// 'employmenttype' => 'required|alpha_dash_space|max:155',
			'employmentstatus' => 'required|alpha_dash_space|max:155',
			// 'startdate' => 'required|date|max:155',
			// 'dateregularized' => 'required|date|max:155'
		]);

		$id = Crypt::decryptString($request->id);
		$lastname = mb_strtoupper($request->lastname);
		$firstname = mb_strtoupper($request->firstname);
		$mi = mb_strtoupper($request->mi);
		$age = $request->age;
		$gender = mb_strtoupper($request->gender);
		$emailaddress =  mb_strtolower($request->emailaddress);
		$civilstatus = mb_strtoupper($request->civilstatus);
		$height = $request->height;
		$weight = $request->weight;
		$mobileno = $request->mobileno;
		$birthday = date("Y-m-d", strtotime($request->birthday));
		$nationalid = mb_strtoupper($request->nationalid);
		$birthplace = mb_strtoupper($request->birthplace);
		$homeaddress = mb_strtoupper($request->homeaddress);
		$company = mb_strtoupper($request->company);
		$department = mb_strtoupper($request->department);
		$jobposition = mb_strtoupper($request->jobposition);
		$companyemail = mb_strtolower($request->companyemail);
		$leaveprivilege = $request->leaveprivilege;
		$idno = mb_strtoupper($request->idno);
		$employmenttype = $request->employmenttype;
		$employmentstatus = $request->employmentstatus;
		$startdate = date("Y-m-d", strtotime($request->startdate));
		$dateregularized = date("Y-m-d", strtotime($request->dateregularized));

		$file = $request->file('image');
		if ($file != null) 
		{
			$name = $request->file('image')->getClientOriginalName();
			$destinationPath = public_path() . '/assets/faces/';
			$file->move($destinationPath, $name);
		} else {
			$name = table::people()->where('id', $id)->value('avatar');
		}
		
		table::people()->where('id', $id)->update([
			'lastname' => $lastname,
			'firstname' => $firstname,
			'mi' => $mi,
			'age' => $age,
			'gender' => $gender,
			'emailaddress' => $emailaddress,
			'civilstatus' => $civilstatus,
			'height' => $height,
			'weight' => $weight,
			'mobileno' => $mobileno,
			'birthday' => $birthday,
			'birthplace' => $birthplace,
			'nationalid' => $nationalid,
			'homeaddress' => $homeaddress,
			'employmenttype' => $employmenttype,
			'employmentstatus' => $employmentstatus,
			'avatar' => $name,
		]);

		table::companydata()->where('reference', $id)->update([
			'company' => $company,
			'department' => $department,
			'jobposition' => $jobposition,
			'companyemail' => $companyemail,
			'leaveprivilege' => $leaveprivilege,
			'idno' => $idno,
			'startdate' => $startdate,
			'dateregularized' => $dateregularized,
		]);
		
    	return redirect('profile/edit/'.$id)->with('success', trans("Employee information has been updated!"));
   	}

	public function viewProfile(Request $request) 
	{
		$id = \Auth::user()->id;
		$myuser = table::users()->where('id', $id)->first();
		$myrole = table::roles()->where('id', $myuser->role_id)->value('role_name');

		return view('admin.update-profile', compact('myuser', 'myrole'));
	}

	public function viewPassword() 
	{
		return view('admin.update-password');
	}

	public function updateUser(Request $request) 
	{

		$v = $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
		]);
		
		$id = \Auth::id();
		$name = mb_strtoupper($request->name);
		$email = mb_strtolower($request->email);

		if($id == null) 
        {
            return redirect('personal/update-user')->with('error', trans("Whoops! Please fill the form completely."));
		}
		
		table::users()->where('id', $id)->update([
			'name' => $name,
			'email' => $email,
		]);

		return redirect('update-profile')->with('success', trans("Updated!"));
	}

	public function updatePassword(Request $request) 
	{

		$v = $request->validate([
            'currentpassword' => 'required|max:100',
            'newpassword' => 'required|min:8|max:100',
            'confirmpassword' => 'required|min:8|max:100',
		]);

		$id = \Auth::id();
		$p = \Auth::user()->password;
		$c_password = $request->currentpassword;
		$n_password = $request->newpassword;
		$c_p_password = $request->confirmpassword;

		if($id == null) 
        {
            return redirect('personal/update-user')->with('error', trans("Whoops! Please fill the form completely."));
		}

		if($n_password != $c_p_password) 
		{
			return redirect('update-password')->with('error', trans("New password does not match!"));
		}

		if(Hash::check($c_password, $p)) 
		{
			table::users()->where('id', $id)->update([
				'password' => Hash::make($n_password),
			]);

			return redirect('update-password')->with('success', trans("Updated!"));
		} else {
			return redirect('update-password')->with('error', trans("Oops! current password does not match."));
		}
	}


} 
