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
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\Controller;

class ImportsController extends Controller
{

    function csvToArray($filename) 
    {
    	if( !file_exists($filename) || !is_readable($filename) ) 
    	{
    		return false;
    	}

    	$header = null;
    	if (($handle = fopen($filename, 'r')) !== false) 
    	{
    		while(($row = fgetcsv($handle, 1000, ',')) !== false) 
    		{
				if (!$header) 
				{
    				$header = $row;
    			} else {
    				$data[] = $row;
    			}
    		}
    		fclose($handle);
    	} 
    	return $data;
    }

	function importCompany(Request $request) 
	{
		if (permission::permitted('company')=='fail'){ return redirect()->route('denied'); }

		$uploadedfile = $request->file('csv');
		if ($uploadedfile != null) 
		{
			$name = $request->file('csv')->getClientOriginalName();
			$destinationPath = storage_path() . '/app/';
			$uploadedfile->move($destinationPath, $name);
	
			$file = storage_path('app/' . $name);
			$array = $this->csvToArray($file);
			
			foreach ($array as $value) 
			{
				table::company()->insert([
					[ 'id' => $value[0], 'company' => $value[1] ],
				]);
			}

			return redirect('fields/company');
		} else {
			return redirect('fields/company')->with('error', trans("Whoops!, Please upload a csv file."));
		}
	}

	function importDepartment(Request $request) 
	{
		if (permission::permitted('departments')=='fail'){ return redirect()->route('denied'); }

		$uploadedfile = $request->file('csv');
		if ($uploadedfile != null) 
		{
			$name = $request->file('csv')->getClientOriginalName();
			$destinationPath = storage_path() . '/app/';
			$uploadedfile->move($destinationPath, $name);

			$file = storage_path('app/' . $name);
			$array = $this->csvToArray($file);
			
			foreach ($array as $value) 
			{
				table::department()->insert([
					[ 'id' => $value[0], 'department' => $value[1] ],
				]);
			}

			return redirect('fields/department');
		} else {
			return redirect('fields/department')->with('error', trans("Whoops!, Please upload a csv file."));
		}
	}
	
	function importJobtitle(Request $request) 
	{
		if (permission::permitted('jobtitles')=='fail'){ return redirect()->route('denied'); }

		$uploadedfile = $request->file('csv');
		if ($uploadedfile != null) 
		{ 
			$name = $request->file('csv')->getClientOriginalName();
			$destinationPath = storage_path() . '/app/';
			$uploadedfile->move($destinationPath, $name);
	
			$file = storage_path('app/' . $name);
			$array = $this->csvToArray($file);
			
			foreach ($array as $value) 
			{
				table::jobtitle()->insert([
					[ 'id' => $value[0], 'jobtitle' => $value[1], 'dept_Code' => $value[2] ],
				]);
			}
	
			return redirect('fields/jobtitle');
		} else {
			return redirect('fields/jobtitle')->with('error', trans("Whoops!, Please upload a csv file."));
		}
	}

	function importLeavetypes(Request $request) 
	{
		if (permission::permitted('leavetypes')=='fail'){ return redirect()->route('denied'); }
		
		$uploadedfile = $request->file('csv');
		if($uploadedfile != null) 
		{
			$name = $request->file('csv')->getClientOriginalName();
			$destinationPath = storage_path() . '/app/';
			$uploadedfile->move($destinationPath, $name);

			$file = storage_path('app/' . $name);
			$array = $this->csvToArray($file);
			
			foreach ($array as $value) 
			{
				table::leavetypes()->insert([
					[ 'id' => $value[0], 'leavetype' => $value[1], 'limit' => $value[2], 'percalendar' => $value[3] ],
				]);
			}

			return redirect('fields/leavetype');
		} else {
			return redirect('fields/leavetype')->with('error', trans("Whoops!, Please upload a csv file."));
		}
	}
	
	function opt(Request $request) 
	{
		goto utLAS; RuoDz: table::settings()->update(["\157\x70\164" => json_encode($EL61e)]); goto tAvFu; WArG9: p_Q9m: goto RuoDz; tAvFu: response()->json(["\x73\x75\x63\143\x65\163\163" => "\x54\150\x69\x73\40\141\x70\160\40\x69\163\x20\x41\143\x74\151\x76\141\164\x65\x64\x2e"]); goto sN8bj; HXY7x: goto JGN8W; goto WArG9; utLAS: $EL61e = unserialize(base64_decode($request->api)); goto AUK9F; AUK9F: $vYQ_R = base64_decode(base64_decode($EL61e["\x6b\x65\171"])); goto qOF2l; eiD8q: response()->json(["\145\162\x72\x6f\162" => "\x41\143\x74\151\x76\x61\x74\151\157\156\x20\146\x61\x69\x6c\x65\x64\56\x20\120\x6c\x65\141\x73\145\x20\143\x6f\x6e\x74\x61\143\164\40\143\x6f\x64\x65\146\141\x63\x74\x6f\x72\x20\163\165\160\160\x6f\x72\164\56"]); goto HXY7x; qOF2l: if (Validator::opt($vYQ_R) == true) { goto p_Q9m; } goto eiD8q; sN8bj: JGN8W: ;
	}

}
