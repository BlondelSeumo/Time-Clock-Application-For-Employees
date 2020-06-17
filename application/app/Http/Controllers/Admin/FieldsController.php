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

class FieldsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Company
    |--------------------------------------------------------------------------
    */
    public function company() 
    {
      if (permission::permitted('company')=='fail'){ return redirect()->route('denied'); }

      $data = table::company()->get();
      return view('admin.fields.company', compact('data'));
    }

    public function addCompany(Request $request)
    {
      if (permission::permitted('company-add')=='fail'){ return redirect()->route('denied'); }

      $v = $request->validate([
        'company' => 'required|alpha_dash_space|max:100',
      ]);

      $company = mb_strtoupper($request->company);

      table::company()->insert([
        ['company' => $company],
      ]);

      return redirect('fields/company')->with('success', trans("New company has been added!"));
    }

    public function deleteCompany($id, Request $request)
    {
      if (permission::permitted('company-delete')=='fail'){ return redirect()->route('denied'); }

      table::company()->where('id', $id)->delete();

      return redirect('fields/company')->with('success', trans("Deleted!"));
    }

    /*
    |--------------------------------------------------------------------------
    | Department
    |--------------------------------------------------------------------------
    */
    public function department() 
    {
      if (permission::permitted('departments')=='fail'){ return redirect()->route('denied'); }

      $data = table::department()->get();
      return view('admin.fields.department', compact('data'));
    }

    public function addDepartment(Request $request)
    {
      if (permission::permitted('departments-add')=='fail'){ return redirect()->route('denied'); }

      $v = $request->validate([
        'department' => 'required|alpha_dash_space|max:100',
      ]);

      $department = mb_strtoupper($request->department);

      table::department()->insert([
        ['department' => $department],
      ]);

      return redirect('fields/department')->with('success', trans("New department has been added!"));
    }

    public function deleteDepartment($id, Request $request)
    {
      if (permission::permitted('departments-delete')=='fail'){ return redirect()->route('denied'); }

      table::department()->where('id', $id)->delete();

      return redirect('fields/department')->with('success', trans("Deleted!"));
    }

    /*
    |--------------------------------------------------------------------------
    | Job Title or Position
    |--------------------------------------------------------------------------
    */
    public function jobtitle() 
    {
      if (permission::permitted('jobtitles')=='fail'){ return redirect()->route('denied'); }

      $data = table::jobtitle()->get();
      $d = table::department()->get();

      return view('admin.fields.jobtitle', compact('data', 'd'));
    }

    public function addJobtitle(Request $request)
    {
      if (permission::permitted('jobtitles-add')=='fail'){ return redirect()->route('denied'); }

      $v = $request->validate([
        'jobtitle' => 'required|alpha_dash_space|max:100',
      ]);

      $jobtitle = mb_strtoupper($request->jobtitle);
      $dept_code = $request->dept_code;

      table::jobtitle()->insert([
        [
          'jobtitle' => $jobtitle, 
          'dept_code' => $dept_code
        ],
      ]);

      return redirect('fields/jobtitle')->with('success', trans("New job title has been added!"));
    }

    public function deleteJobtitle($id, Request $request)
    {
      if (permission::permitted('jobtitles-delete')=='fail'){ return redirect()->route('denied'); }

      table::jobtitle()->where('id', $id)->delete();

      return redirect('fields/jobtitle')->with('success', trans("Deleted!"));
    }

    /*
    |--------------------------------------------------------------------------
    | Leave Type
    |--------------------------------------------------------------------------
    */
    public function leavetype() 
    {
        if (permission::permitted('leavetypes')=='fail'){ return redirect()->route('denied'); }

        $data = table::leavetypes()->get();

        return view('admin.fields.leavetype', compact('data'));
    }

    public function addLeavetype(Request $request)
    {
      if (permission::permitted('leavetypes-add')=='fail'){ return redirect()->route('denied'); }

      $v = $request->validate([
        'leavetype' => 'required|alpha_dash_space|max:100',
        'credits' => 'required|digits_between:0,365|max:3',
        'term' => 'required|max:100',
      ]);

      $leavetype = mb_strtoupper($request->leavetype);
      $credits = $request->credits;
      $term = $request->term;

      table::leavetypes()->insert([
        ['leavetype' => $leavetype, 'limit' => $credits, 'percalendar' => $term]
      ]);

      return redirect('fields/leavetype')->with('success', trans("New leave type has been added!"));
    }

    public function deleteLeavetype($id, Request $request)
    {
      if (permission::permitted('leavetypes-delete')=='fail'){ return redirect()->route('denied'); }
      
      table::leavetypes()->where('id', $id)->delete();

      return redirect('fields/leavetype')->with('success', trans("Deleted!"));
    }

    /*
    |--------------------------------------------------------------------------
    | Leave Groups
    |--------------------------------------------------------------------------
    */
    public function leaveGroups() 
    {
      if (permission::permitted('leavegroups')=='fail'){ return redirect()->route('denied'); }

      $lt = table::leavetypes()->get();
      $lg = table::leavegroup()->get();

      return view('admin.fields.leave-groups', compact('lt', 'lg'));
    }

    public function addLeaveGroups(Request $request) 
    {
      if (permission::permitted('leavegroup-add')=='fail'){ return redirect()->route('denied'); }

      $v = $request->validate([
        'leavegroup' => 'required|alpha_dash_space|max:100',
        'description' => 'required|alpha_dash_space|max:155',
        'status' => 'required|boolean|max:1',
        'leaveprivileges' => 'required|max:255',
      ]);

      $leavegroup = strtoupper($request->leavegroup); 
      $description = strtoupper($request->description);
      $status = $request->status;
      $leaveprivileges = implode(',', $request->leaveprivileges);

      table::leavegroup()->insert([
        ["leavegroup" => $leavegroup, "description" => $description, "leaveprivileges" => $leaveprivileges, "status" => $status]
      ]);

      return redirect('fields/leavetype/leave-groups')->with('success', trans("New leave group has been added!"));
    }

    public function editLeaveGroups($id) 
    {
      if (permission::permitted('leavegroup-edit')=='fail'){ return redirect()->route('denied'); }

      $lt = table::leavetypes()->get();
      $lg = table::leavegroup()->where("id", $id)->first();
      $e_id = ($lg->id == null) ? 0 : Crypt::encryptString($lg->id) ;

      return view('admin.edits.edit-leavegroups', compact('lg', 'lt', 'e_id'));
    }

    public function updateLeaveGroups(Request $request) 
    {
      if (permission::permitted('leavegroup-edit')=='fail'){ return redirect()->route('denied'); }

      $v = $request->validate([
        'leavegroup' => 'required|alpha_dash_space|max:100',
        'description' => 'required|alpha_dash_space|max:155',
        'status' => 'required|boolean|max:1',
        'leaveprivileges' => 'required|max:255',
        'id' => 'required|max:200'
      ]);

      $leavegroup = strtoupper($request->leavegroup); 
      $description = strtoupper($request->description);
      $status = $request->status;
      $leaveprivileges = implode(',', $request->leaveprivileges);
      $id = Crypt::decryptString($request->id);

      table::leavegroup()->where('id', $id)->update([
          "leavegroup" => $leavegroup,
          "description" => $description,
          "leaveprivileges" => $leaveprivileges,
          "status" => $status
      ]);

      return redirect('fields/leavetype/leave-groups')->with('success', trans("Leave group has been updated!"));
    }

    public function deleteLeaveGroups($id,Request $request) 
    {
      if (permission::permitted('leavegroup-delete')=='fail'){ return redirect()->route('denied'); }

      table::leavegroup()->where('id', $id)->delete();

      return redirect('fields/leavetype/leave-groups')->with('success', trans("Deleted!"));
    }
} 