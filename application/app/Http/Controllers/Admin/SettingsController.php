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
use Auth;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function index(Request $request) 
    {
        if (permission::permitted('settings')=='fail'){ return redirect()->route('denied'); }
        
        $data = table::settings()->where('id', 1)->first();
        
    	return view('admin.settings', compact('data'));
    }

    public function update(Request $request) 
    {
        if (permission::permitted('settings-update')=='fail'){ return redirect()->route('denied'); }
        
        $v = $request->validate([
            'country' => 'required|max:100',
            'timezone' => 'required|timezone|max:100',
            'iprestriction' => 'max:1600',
            'time_format' => 'max:1',
            'clock_comment' => 'max:2',
            'rfid' => 'max:2',
        ]);

        $country = $request->country;
        $timezone = $request->timezone;
        $clock_comment = $request->clock_comment;
        $iprestriction = $request->iprestriction;
        $time_format = $request->time_format;
        $rfid = $request->rfid;
        
        if ($timezone != null) 
        {
            $t = table::settings()->where('id', 1)->value('timezone');
            $path = base_path('.env');
            
            if(file_exists($path)) 
            {
                file_put_contents($path, str_replace('APP_TIMEZONE='.$t, 'APP_TIMEZONE='.$timezone, file_get_contents($path)));
            }
        }

        table::settings()
        ->where('id', 1)
        ->update([
                'country' => $country,
                'timezone' => $timezone,
                'clock_comment' => $clock_comment,
                'iprestriction' => $iprestriction,
                'time_format' => $time_format,
                'rfid' => $rfid,
        ]);
        
        return redirect('settings')->with('success', trans("Settings is up to date. Please try re-login for the new settings to take effect."));
    }

}
