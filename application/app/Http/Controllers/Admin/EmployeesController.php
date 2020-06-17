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
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class EmployeesController extends Controller
{

	public function index() 
	{
        if (permission::permitted('employees')=='fail'){ return redirect()->route('denied'); }

		$emp_typeR = table::people()
		->where('employmenttype', 'Regular')
		->where('employmentstatus', 'Active')
		->count();

		$emp_typeT = table::people()
		->where('employmenttype', 'Trainee')
		->where('employmentstatus', 'Active')
		->count();

		$emp_genderM = table::people()
		->where('gender', 'Male')
		->count();

		$emp_genderR = table::people()
		->where('gender', 'Female')
		->count();

		$emp_allActive = table::people()
		->where('employmentstatus', 'Active')
		->count();

		$emp_allArchive = table::people()
		->where('employmentstatus', 'Archive')
		->count();

		$data = table::people()
		->join('tbl_company_data', 'tbl_people.id', '=', 'tbl_company_data.reference')
		->get();

		$emp_file = table::people()->count();
		
		if($emp_allArchive != null OR $emp_allActive != null OR $emp_allArchive >= 1 OR $emp_allActive >= 1)
		{
			$number1 = $emp_allArchive / $emp_allActive * 100;
		} else {
			$number1 = null;
		}
		
	    return view('admin.employees', compact('data', 'emp_typeR', 'emp_typeT', 'emp_genderM', 'emp_genderR', 'emp_allActive', 'emp_file', 'emp_allArchive'));
	}

	public function new() 
	{
		if (permission::permitted('employees-add')=='fail'){ return redirect()->route('denied'); }
		
		$employees = table::people()->get();
		$company = table::company()->get();
		$department = table::department()->get();
		$jobtitle = table::jobtitle()->get();
		$leavegroup = table::leavegroup()->get();

	    return view('admin.new-employee', compact('company', 'department', 'jobtitle', 'employees', 'leavegroup'));
	}
	
    public function add(Request $request)
    {
		if (permission::permitted('employees-add')=='fail'){ return redirect()->route('denied'); }
		
		$v = $request->validate([
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

		$is_idno_taken = table::companydata()->where('idno', $idno)->exists();

		if ($is_idno_taken == 1) 
		{
			return redirect('employees-new')->with('error', trans("Whoops! the ID Number is already taken."));
		}

		$file = $request->file('image');

		if($file != null) 
		{
			$name = $request->file('image')->getClientOriginalName();
			$destinationPath = public_path() . '/assets/faces/';
			$file->move($destinationPath, $name);
		} else {
			$name = '';
		}
		
    	table::people()->insert([
    		[
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
            ],
    	]);

		$refId = DB::getPdo()->lastInsertId();
		
    	table::companydata()->insert([
    		[
    			'reference' => $refId,
				'company' => $company,
				'department' => $department,
				'jobposition' => $jobposition,
				'companyemail' => $companyemail,
				'leaveprivilege' => $leaveprivilege,
				'idno' => $idno,
				'startdate' => $startdate,
				'dateregularized' => $dateregularized,
            ],
    	]);

    	return redirect('employees')->with('success', trans("New employee has been added!"));
    }
}
