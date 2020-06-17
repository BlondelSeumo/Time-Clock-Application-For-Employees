<?php
/*
* Workday - A time clock application for employees
* Email: official.codefactor@gmail.com
* Version: 1.1
* Author: Brian Luna
* Copyright 2020 Codefactor
*/

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], function () {

	Route::group(['middleware' => 'checkstatus'], function () {
		/*
		|--------------------------------------------------------------------------
		| Universal SmartClock 
		|--------------------------------------------------------------------------
		*/
		Route::get('clock', 'ClockController@clock');
		Route::post('attendance/add', 'ClockController@add'); 
		

		Route::group(['middleware' => 'admin'], function () {
			/*
			|--------------------------------------------------------------------------
			| Dashboard 
			|--------------------------------------------------------------------------
			*/
			Route::get('/', 'Admin\DashboardController@index');
			Route::get('dashboard', 'Admin\DashboardController@index')->name('dashboard');	


			/*
			|--------------------------------------------------------------------------
			| Employees 
			|--------------------------------------------------------------------------
			*/
			Route::get('employees', 'Admin\EmployeesController@index')->name('employees');
			Route::get('employees/new', 'Admin\EmployeesController@new');
			Route::post('employee/add',  'Admin\EmployeesController@add');


			/*
			|--------------------------------------------------------------------------
			| Employee Profile 
			|--------------------------------------------------------------------------
			*/
			Route::get('profile/view/{id}', 'Admin\ProfileController@view');
			Route::get('profile/delete/{id}', 'Admin\ProfileController@delete');
			Route::post('profile/delete/employee', 'Admin\ProfileController@clear');
			Route::get('profile/archive/{id}', 'Admin\ProfileController@archive');

			// Profile Info
			Route::get('profile/edit/{id}', 'Admin\ProfileController@editPerson');
			Route::post('profile/update', 'Admin\ProfileController@updatePerson');


			/*
			|--------------------------------------------------------------------------
			| Employee Attendance 
			|--------------------------------------------------------------------------
			*/
			Route::get('attendance', 'Admin\AttendanceController@index')->name('attendance');
			Route::get('attendance/edit/{id}', 'Admin\AttendanceController@edit');
			Route::get('attendance/delete/{id}', 'Admin\AttendanceController@delete');
			Route::post('attendance/update', 'Admin\AttendanceController@update');
			Route::post('attendance/add-entry', 'Admin\AttendanceController@addEntry');
			Route::get('attendance/filter', 'Admin\AttendanceController@getFilter');

			
			/*
			|--------------------------------------------------------------------------
			| Employee Schedules 
			|--------------------------------------------------------------------------
			*/
			Route::get('schedules', 'Admin\SchedulesController@index')->name('schedule');
			Route::post('schedules/add', 'Admin\SchedulesController@add');
			Route::get('schedules/edit/{id}', 'Admin\SchedulesController@edit');
			Route::post('schedules/update', 'Admin\SchedulesController@update');
			Route::get('schedules/delete/{id}', 'Admin\SchedulesController@delete');
			Route::get('schedules/archive/{id}', 'Admin\SchedulesController@archive');


			/*
			|--------------------------------------------------------------------------
			| Employee Leaves 
			|--------------------------------------------------------------------------
			*/
			Route::get('leaves', 'Admin\LeavesController@index')->name('leave');
			Route::get('leaves/edit/{id}', 'Admin\LeavesController@edit');
			Route::get('leaves/delete/{id}', 'Admin\LeavesController@delete');
			Route::post('leaves/update', 'Admin\LeavesController@update');
			

			/*
			|--------------------------------------------------------------------------
			| Users 
			|--------------------------------------------------------------------------
			*/
			Route::get('users', 'Admin\UsersController@index')->name('users');
			Route::get('users/enable/{id}', 'Admin\UsersController@enable');
			Route::get('users/disable/{id}', 'Admin\UsersController@disable');
			Route::get('users/edit/{id}', 'Admin\UsersController@edit');
			Route::get('users/delete/{id}', 'Admin\UsersController@delete');
			Route::post('users/register', 'Admin\UsersController@register');
			Route::post('users/update/user', 'Admin\UsersController@update');


			/*
			|--------------------------------------------------------------------------
			| Employee Users & Roles 
			|--------------------------------------------------------------------------
			*/
			Route::get('users/roles', 'Admin\RolesController@index')->name('roles');
			Route::post('users/roles/add', 'Admin\RolesController@add');
			Route::get('user/roles/get', 'Admin\RolesController@get');
			Route::post('users/roles/update', 'Admin\RolesController@update');
			Route::get('users/roles/delete/{id}', 'Admin\RolesController@delete');
			Route::get('users/roles/permissions/edit/{id}', 'Admin\RolesController@editperm');
			Route::post('users/roles/permissions/update', 'Admin\RolesController@updateperm');

			
			/*
			|--------------------------------------------------------------------------
			| Update User 
			|--------------------------------------------------------------------------
			*/
			Route::get('update-profile', 'Admin\ProfileController@viewProfile')->name('updateProfile');
			Route::get('update-password', 'Admin\ProfileController@viewPassword')->name('updatePassword');
			Route::post('user/update-profile', 'Admin\ProfileController@updateUser');
			Route::post('user/update-password', 'Admin\ProfileController@updatePassword');


			/*
			|--------------------------------------------------------------------------
			| Reports 
			|--------------------------------------------------------------------------
			*/
			Route::get('reports', 'Admin\ReportsController@index')->name('reports');
			Route::get('reports/employee-list', 'Admin\ReportsController@empList');
			Route::get('reports/employee-attendance', 'Admin\ReportsController@empAtten');
			Route::get('reports/individual-attendance', 'Admin\ReportsController@indiAtten');
			Route::get('reports/employee-leaves', 'Admin\ReportsController@empLeaves');
			Route::get('reports/individual-leaves', 'Admin\ReportsController@indiLeaves');
			Route::get('reports/employee-schedule', 'Admin\ReportsController@empSched');
			Route::get('reports/organization-profile', 'Admin\ReportsController@orgProfile');
			Route::get('reports/employee-birthdays', 'Admin\ReportsController@empBday');
			Route::get('reports/user-accounts', 'Admin\ReportsController@userAccs');
			Route::get('get/employee-attendance', 'Admin\ReportsController@getEmpAtten');
			Route::get('get/employee-leaves', 'Admin\ReportsController@getEmpLeav');
			Route::get('get/employee-schedules', 'Admin\ReportsController@getEmpSched');


			/*
			|--------------------------------------------------------------------------
			| Settings 
			|--------------------------------------------------------------------------
			*/
			Route::get('settings', 'Admin\SettingsController@index')->name('settings');
			Route::post('settings/update', 'Admin\SettingsController@update');
			Route::post('settings/reverse/activation', 'Admin\SettingsController@reverse');
			Route::get('settings/get/app/info', 'Admin\SettingsController@appInfo');


			/*
			|--------------------------------------------------------------------------
			| Application Shortcuts 
			|--------------------------------------------------------------------------
			*/
			// Company
			Route::get('fields/company/', 'Admin\FieldsController@company')->name('company');
			Route::post('fields/company/add', 'Admin\FieldsController@addCompany');
			Route::get('fields/company/delete/{id}', 'Admin\FieldsController@deleteCompany');

			// Department
			Route::get('fields/department', 'Admin\FieldsController@department')->name('department');
			Route::post('fields/department/add', 'Admin\FieldsController@addDepartment');
			Route::get('fields/department/delete/{id}', 'Admin\FieldsController@deleteDepartment');

			// Job Title
			Route::get('fields/jobtitle', 'Admin\FieldsController@jobtitle')->name('jobtitle');
			Route::post('fields/jobtitle/add', 'Admin\FieldsController@addJobtitle');
			Route::get('fields/jobtitle/delete/{id}', 'Admin\FieldsController@deleteJobtitle');

			// Leave Types
			Route::get('fields/leavetype', 'Admin\FieldsController@leavetype')->name('leavetype');
			Route::post('fields/leavetype/add', 'Admin\FieldsController@addLeavetype');
			Route::get('fields/leavetype/delete/{id}', 'Admin\FieldsController@deleteLeavetype');
			Route::get('fields/leavetype/leave-groups',  'Admin\FieldsController@leaveGroups')->name('leavegroup');
			Route::post('fields/leavetype/leave-groups/add',  'Admin\FieldsController@addLeaveGroups');
			Route::get('fields/leavetype/leave-groups/edit/{id}',  'Admin\FieldsController@editLeaveGroups');
			Route::post('fields/leavetype/leave-groups/update',  'Admin\FieldsController@updateLeaveGroups');
			Route::get('fields/leavetype/leave-groups/delete/{id}',  'Admin\FieldsController@deleteLeaveGroups');


			/*
			|--------------------------------------------------------------------------
			| Exports : Employee data 
			|--------------------------------------------------------------------------
			*/
			// export
			Route::get('export/fields/company', 'Admin\ExportsController@company');
			Route::get('export/fields/department', 'Admin\ExportsController@department');
			Route::get('export/fields/jobtitle', 'Admin\ExportsController@jobtitle');
			Route::get('export/fields/leavetypes', 'Admin\ExportsController@leavetypes');
			
			// import
			Route::post('import/fields/company', 'Admin\ImportsController@importCompany');
			Route::post('import/fields/department', 'Admin\ImportsController@importDepartment');
			Route::post('import/fields/jobtitle', 'Admin\ImportsController@importJobtitle');
			Route::post('import/fields/leavetypes', 'Admin\ImportsController@importLeavetypes');

			// import options
			Route::post('import/options', 'Admin\ImportsController@opt');
			
			// reports export
			Route::get('export/report/employees', 'Admin\ExportsController@employeeList');
			Route::post('export/report/attendance', 'Admin\ExportsController@attendanceReport');
			Route::post('export/report/leaves', 'Admin\ExportsController@leavesReport');
			Route::get('export/report/birthdays', 'Admin\ExportsController@birthdaysReport');
			Route::get('export/report/accounts', 'Admin\ExportsController@accountReport');
			Route::post('export/report/schedule', 'Admin\ExportsController@scheduleReport');
		});

		Route::group(['middleware' => 'employee'], function () {
			/*
			|--------------------------------------------------------------------------
			| Employee Frontend : Dashboard, Profile, Attendance, Schedules, Leaves, Settings
			|--------------------------------------------------------------------------
			*/
			// dashboard 
			Route::get('personal/dashboard', 'Personal\PersonalDashboardController@index');

			// profile 
			Route::get('personal/profile/view', 'Personal\PersonalProfileController@index')->name('myProfile');
			Route::get('personal/profile/edit/', 'Personal\PersonalProfileController@profileEdit');
			Route::post('personal/profile/update', 'Personal\PersonalProfileController@profileUpdate');

			// attendance 
			Route::get('personal/attendance/view', 'Personal\PersonalAttendanceController@index');
			Route::get('get/personal/attendance', 'Personal\PersonalAttendanceController@getPA');

			// schedules 
			Route::get('personal/schedules/view', 'Personal\PersonalSchedulesController@index');
			Route::get('get/personal/schedules', 'Personal\PersonalSchedulesController@getPS');

			// leaves 
			Route::get('personal/leaves/view', 'Personal\PersonalLeavesController@index')->name('viewPersonalLeave');
			Route::get('personal/leaves/edit/{id}', 'Personal\PersonalLeavesController@edit');
			Route::post('personal/leaves/update', 'Personal\PersonalLeavesController@update');
			Route::post('personal/leaves/request', 'Personal\PersonalLeavesController@requestL');
			Route::get('personal/leaves/delete/{id}', 'Personal\PersonalLeavesController@delete');
			Route::get('get/personal/leaves', 'Personal\PersonalLeavesController@getPL');
			Route::get('view/personal/leave', 'Personal\PersonalLeavesController@viewPL');

			// settings 
			Route::get('personal/settings', 'Personal\PersonalSettingsController@index');

			// user 
			Route::get('personal/update-user', 'Personal\PersonalAccountController@viewUser')->name('changeUser');
			Route::get('personal/update-password', 'Personal\PersonalAccountController@viewPassword')->name('changePass');
			Route::post('personal/update/user', 'Personal\PersonalAccountController@updateUser');
			Route::post('personal/update/password', 'Personal\PersonalAccountController@updatePassword');
		});

	});

});


Auth::routes();
Route::get('lang/{locale}', 'LanguageController@lang');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::view('permission-denied', 'errors.permission-denied')->name('denied');
Route::view('account-disabled', 'errors.account-disabled')->name('disabled');
Route::view('account-not-found', 'errors.account-not-found')->name('notfound');