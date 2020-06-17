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


class PersonalSchedulesController extends Controller
{
    public function index() 
    {
        $i = \Auth::user()->idno;
        $s = table::schedules()->where('idno', $i)->get();
        $tf = table::settings()->value("time_format");

        return view('personal.personal-schedules-view', compact('s', 'tf'));
    }

    public function getPS(Request $request) 
    {
        $id = \Auth::user()->idno;
        $datefrom = $request->datefrom;
		$dateto = $request->dateto;
		
        if($datefrom == null || $dateto == null ) 
        {
            $data = table::schedules()
            ->select('intime', 'outime', 'datefrom', 'dateto', 'hours', 'restday', 'archive')
            ->where('idno', $id)
            ->get();
            
            return response()->json($data);

		} elseif ($datefrom !== null AND $dateto !== null) {
            $data = table::schedules()
            ->select('intime', 'outime', 'datefrom', 'dateto', 'hours', 'restday', 'archive')
            ->where('idno', $id)
            ->whereBetween('datefrom', [$datefrom, $dateto])
            ->get();
            
            return response()->json($data);
        } 
    }
}

