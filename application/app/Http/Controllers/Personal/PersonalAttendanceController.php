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

class PersonalAttendanceController extends Controller
{
    public function index() 
    {
        $i = \Auth::user()->idno;
        $a = table::attendance()->where('idno', $i)->get();
        $tf = table::settings()->value("time_format");

        return view('personal.personal-attendance-view', compact('a', 'tf'));
    }

    public function getPA(Request $request) 
    {
		$id = \Auth::user()->idno;
		$datefrom = $request->datefrom;
		$dateto = $request->dateto;
		
        if($datefrom == '' || $dateto == '' ) 
        {
             $data = table::attendance()
             ->select('date', 'timein', 'timeout', 'totalhours', 'status_timein', 'status_timeout')
             ->where('idno', $id)
             ->get();
             
			return response()->json($data);

		} elseif ($datefrom !== '' AND $dateto !== '') {
            $data = table::attendance()
            ->select('date', 'timein', 'timeout', 'totalhours', 'status_timein', 'status_timeout')
            ->where('idno', $id)
            ->whereBetween('date', [$datefrom, $dateto])
            ->get();

			return response()->json($data);
        }
	}
}

