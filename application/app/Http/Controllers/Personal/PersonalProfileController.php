<?php
/*
* Workday - A time clock application for employees
* Email: official.codefactor@gmail.com
* Version: 1.1
* Author: Brian Luna
* Copyright 2020 Codefactor
*/
namespace App\Http\Controllers\personal;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class PersonalProfileController extends Controller
{
	public function index() 
	{
        $id = \Auth::user()->reference;
		$profile = table::people()->where('id', $id)->first();
		$company_data = table::companydata()->where('reference', $id)->first();
		$profile_photo = table::people()->select('avatar')->where('id', $id)->value('avatar');
		$leavetype = table::leavetypes()->get();
		$leavegroup = table::leavegroup()->get();

        return view('personal.personal-profile-view', compact('profile', 'company_data', 'profile_photo', 'leavetype', 'leavegroup'));
    }
	   
	public function profileEdit() 
	{
		$id = \Auth::user()->reference;
		$person_details = table::people()->where('id', $id)->first();

		return view('personal.edits.personal-profile-edit', compact('person_details'));
	}

	public function profileUpdate(Request $request) 
	{
		$v = $request->validate([
			'lastname' => 'required|alpha_dash_space|max:155',
			'firstname' => 'required|alpha_dash_space|max:155',
			'mi' => 'required|alpha_dash_space|max:155',
			'age' => 'required|digits_between:0,199|max:3',
			'gender' => 'required|alpha|max:155',
			'emailaddress' => 'required|email|max:155',
			'civilstatus' => 'required|alpha|max:155',
			'height' => 'required|digits_between:0,299|max:3',
			'weight' => 'required|digits_between:0,999|max:3',
			'mobileno' => 'required|max:155',
			'birthday' => 'required|date|max:155',
			// 'nationalid' => 'required|max:155',
			'birthplace' => 'required|max:255',
			'homeaddress' => 'required|max:255',
		]);

		$id = \Auth::user()->reference;

		$lastname = mb_strtoupper($request->lastname);
		$firstname = mb_strtoupper($request->firstname);
		$mi = mb_strtoupper($request->mi);
		$age = $request->age;
		$gender = mb_strtoupper($request->gender);
		$emailaddress = mb_strtolower($request->emailaddress);
		$civilstatus = mb_strtoupper($request->civilstatus);
		$height = $request->height;
		$weight = $request->weight;
		$mobileno = $request->mobileno;
		$birthday = date("Y-m-d", strtotime($request->birthday));
		$birthplace = mb_strtoupper($request->birthplace);
		$homeaddress = mb_strtoupper($request->homeaddress);

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
			'homeaddress' => $homeaddress,
		]);

    	return redirect('personal/profile/view')->with('success', trans("Updated!"));
	}

}

