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
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class PersonalLeavesController extends Controller
{
    public function index() 
    {
        $i = \Auth::user()->idno;
        $ref = \Auth::user()->reference;

        $l = table::leaves()->where('idno', $i)->get();
        $lp = table::companydata()->where('reference', $ref)->value('leaveprivilege');
        $r = table::leavegroup()->where('id', $lp)->value('leaveprivileges');
        $rights = explode(",", $r);
        
        $lt = table::leavetypes()->get();
        $lg = table::leavegroup()->get();
        
        return view('personal.personal-leaves-view', compact('l', 'lt', 'lg', 'lp', 'rights'));
    }

    public function requestL(Request $request) 
    {

        $v = $request->validate([
            'type' => 'required|max:100',
            'typeid' => 'required|digits_between:0,999|max:100',
            'leavefrom' => 'required|date|max:15',
            'leaveto' => 'required|date|max:15',
            'returndate' => 'required|date|max:15',
            'reason' => 'required|max:255',
        ]);

        $typeid = $request->typeid;
        $type = mb_strtoupper($request->type);
        $reason = mb_strtoupper($request->reason);
        $leavefrom = date("Y-m-d", strtotime($request->leavefrom));
        $leaveto = date("Y-m-d", strtotime($request->leaveto));
        $returndate = date("Y-m-d", strtotime($request->returndate));

        $id = \Auth::user()->reference;
        $idno = \Auth::user()->idno;
        $q = table::people()->where('id', $id)->select('firstname', 'mi', 'lastname')->first();
        
        table::leaves()->insert([
            'reference' => $id,
            'idno' => $idno,
            'employee' => $q->lastname.', '.$q->firstname,
            'type' => $type,
            'typeid' => $typeid,
            'leavefrom' => $leavefrom,
            'leaveto' => $leaveto,
            'returndate' => $returndate,
            'reason' => $reason,
            'status' => 'Pending',
        ]);

        return redirect('personal/leaves/view')->with('success', trans("Leave request sent!"));
    }

    public function getPL(Request $request) 
    {
        $id = \Auth::user()->reference;
        $datefrom = date("Y-m-d", strtotime($request->datefrom));
        $dateto = date("Y-m-d", strtotime($request->dateto));

        if($datefrom == null || $dateto == null ) {
            $data = table::leaves()->where('reference', $id)->get();

            return response()->json($data);
        } 
        
        if ($datefrom !== null AND $dateto !== null) {
            $data = table::leaves()
                                    ->where('reference', $id)
                                    ->whereDate('leavefrom', '<=', $dateto)
                                    ->whereDate('leavefrom', '>=', $datefrom)
                                    ->get();

            return response()->json($data);
        }
    }

    public function viewPL(Request $request) 
    {
        $id = $request->id;
        $view = table::leaves()->where('id', $id)->first();
        $view->leavefrom = date('M d, Y', strtotime($view->leavefrom));
        $view->leaveto = date('M d, Y', strtotime($view->leaveto));
        $view->returndate = date('M d, Y', strtotime($view->returndate));

        return response()->json($view);
    }

    public function edit($id, Request $request) 
    {
        $l = table::leaves()->where('id', $id)->first();
        $lt = table::leavetypes()->get();
        $type = $l->type;
        $e_id = ($l->id == null) ? 0 : Crypt::encryptString($l->id) ;

        return view('personal.edits.personal-leaves-edit', compact('l', 'lt', 'type', 'e_id'));
    }

    public function update(Request $request)
    {
        $v = $request->validate([
            'id' => 'required|max:200',
            'type' => 'required|max:100',
            'leavefrom' => 'required|date|max:15',
            'leaveto' => 'required|date|max:15',
            'returndate' => 'required|date|max:15',
            'reason' => 'required|max:255',
        ]);

        $id = Crypt::decryptString($request->id);
        $type = mb_strtoupper($request->type);
        $leavefrom = $request->leavefrom;
        $leaveto = $request->leaveto;
        $returndate = $request->returndate;
        $reason = mb_strtoupper($request->reason);

        table::leaves()
        ->where('id', $id)
        ->update([
                    'type' => $type,
                    'leavefrom' => $leavefrom,
                    'leaveto' => $leaveto,
                    'reason' => $reason
                ]);

        return redirect('personal/leaves/view')->with('success', trans("Leave is up to date!"));
    }

    public function delete($id, Request $request)
    {
        
        table::leaves()->where('id', $id)->delete();

        return redirect('personal/leaves/view')->with('success', trans("Leave has been deleted!"));
    }

}

