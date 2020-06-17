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
use App\Http\Controllers\Controller;

class LeavesController extends Controller
{
    public function index() 
    {
        if (permission::permitted('leaves')=='fail'){ return redirect()->route('denied'); }

        $employee = table::people()->join('tbl_company_data', 'tbl_people.id', '=', 'tbl_company_data.reference')->get();
        $leaves = table::leaves()->get();
        $leave_types = table::leavetypes()->get();

        return view('admin.leaves', compact('employee', 'leaves', 'leave_types'));
    }

    public function edit($id, Request $request) 
    {
        if (permission::permitted('leaves-edit')=='fail'){ return redirect()->route('denied'); }

        $l = table::leaves()->where('id', $id)->first();
        $l->leavefrom = date('M d, Y', strtotime($l->leavefrom));
        $l->leaveto = date('M d, Y', strtotime($l->leaveto));
        $l->returndate = date('M d, Y', strtotime($l->returndate));
        $leave_types = table::leavetypes()->get();
        $e_id = ($l->id == null) ? 0 : Crypt::encryptString($l->id) ;

        return view('admin.edits.edit-leaves', compact('l', 'leave_types', 'e_id'));
    }

    public function update(Request $request)
    {
        if (permission::permitted('leaves-edit')=='fail'){ return redirect()->route('denied'); }

        $v = $request->validate([
            'id' => 'required|max:200',
            'status' => 'required|max:100',
            'comment' => 'max:255',
        ]);

        $id = Crypt::decryptString($request->id);
        $status = $request->status;
        $comment = mb_strtoupper($request->comment);

        table::leaves()
        ->where('id', $id)
        ->update([
                    'status' => $status,
                    'comment' => $comment
        ]);

        return redirect('/leaves')->with('success', trans("Employee leave has been updated!"));
    }


    public function delete($id, Request $request)
    {
        if (permission::permitted('leaves-delete')=='fail'){ return redirect()->route('denied'); }

        table::leaves()->where('id', $id)->delete();

        return redirect('leaves')->with('success', trans("Deleted!"));
    }

}
