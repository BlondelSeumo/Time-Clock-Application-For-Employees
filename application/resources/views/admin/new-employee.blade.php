@extends('layouts.default')

    @section('meta')
        <title>New Employee | Workday Time Clock</title>
        <meta name="description" content="Workday add new employee, delete employee, edit employee">
    @endsection

    @section('styles')
        <link href="{{ asset('/assets/vendor/air-datepicker/dist/css/datepicker.min.css') }}" rel="stylesheet">
    @endsection

    @section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title">{{ __('Employee Profile') }}</h2>
            </div>    
        </div>

        <div class="row">
            <div class="col-md-12">
            @if ($errors->any())
            <div class="ui error message">
                <i class="close icon"></i>
                <div class="header">{{ __('There were some errors with your submission') }}</div>
                <ul class="list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            </div>
            <form id="add_employee_form" action="{{ url('employee/add') }}" class="ui form custom" method="post" accept-charset="utf-8" enctype="multipart/form-data">
            @csrf
                <div class="col-md-6 float-left">
                    <div class="box box-success">
                        <div class="box-header with-border">{{ __('Personal Information') }}</div>
                        <div class="box-body">
                            <div class="two fields">
                                <div class="field">
                                    <label>{{ __('First Name') }}</label>
                                    <input type="text" class="uppercase" name="firstname" value="">
                                </div>
                                <div class="field">
                                    <label>{{ __('Middle Name') }}</label>
                                    <input type="text" class="uppercase" name="mi" value="">
                                </div>
                            </div>
                            <div class="field">
                                <label>{{ __('Last Name') }}</label>
                                <input type="text" class="uppercase" name="lastname" value="">
                            </div>
                            <div class="field">
                                <label>{{ __('Gender') }}</label>
                                <select name="gender" class="ui dropdown uppercase">
                                    <option value="">Select Gender</option>
                                    <option value="MALE">MALE</option>
                                    <option value="FEMALE">FEMALE</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>{{ __('Civil Status') }}</label>
                                <select name="civilstatus" class="ui dropdown uppercase">
                                    <option value="">Select Civil Status</option>
                                    <option value="SINGLE">SINGLE</option>
                                    <option value="MARRIED">MARRIED</option>
                                    <option value="ANULLED">ANULLED</option>
                                    <option value="WIDOWED">WIDOWED</option>
                                    <option value="LEGALLY SEPARATED">LEGALLY SEPARATED</option>
                                </select>
                            </div>
                            <div class="two fields">
                                <div class="field">
                                    <label>{{ __('Height') }} <span class="help">(cm)</span></label>
                                    <input type="number" name="height" value="" placeholder="000">
                                </div>
                                <div class="field">
                                    <label>{{ __('Weight') }} <span class="help">(pounds)</span></label>
                                    <input type="number" name="weight" value="" placeholder="000">
                                </div>
                            </div>
                            <div class="two fields">
                            <div class="field">
                                <label>{{ __('Email Address (Personal)') }}</label>
                                <input type="email" name="emailaddress" value="" class="lowercase">
                            </div>
                            <div class="field">
                                <label>{{ __('Mobile Number') }}</label>
                                <input type="text" class="" name="mobileno" value="">
                            </div>
                            </div>
                            <div class="two fields">
                                <div class="field">
                                    <label>{{ __('Age') }}</label>
                                    <input type="number" name="age" value="" placeholder="00">
                                </div>
                                <div class="field">
                                    <label>{{ __('Date of Birth') }}</label>
                                    <input type="text" name="birthday" value="" class="airdatepicker" data-position="top right" placeholder="Date"> 
                                </div>
                            </div>
                            <div class="field">
                                <label>{{ __('National ID') }}</label>
                                <input type="text" class="uppercase" name="nationalid" value="" placeholder="">
                            </div>
                            <div class="field">
                                <label>{{ __('Place of Birth') }}</label>
                                <input type="text" class="uppercase" name="birthplace" value="" placeholder="City, Province, Country">
                            </div>
                            <div class="field">
                                <label>{{ __('Home Address') }}</label>
                                <input type="text" class="uppercase" name="homeaddress" value="" placeholder="House/Unit Number, Building, Street, City, Province, Country">
                            </div>
                            <div class="field">
                                <label>{{ __('Upload Profile photo') }}</label>
                                <input class="ui file upload" value="" id="imagefile" name="image" type="file" accept="image/png, image/jpeg, image/jpg" onchange="validateFile()">
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 float-left">
                    <div class="box box-success">
                        <div class="box-header with-border">{{ __('Employee Details') }}</div>
                        <div class="box-body">
                            <h4 class="ui dividing header">{{ __('Designation') }}</h4>
                            <div class="field">
                                <label>{{ __('Company') }}</label>
                                <select name="company" class="ui search dropdown uppercase">
                                    <option value="">Select Company</option>
                                    @isset($company)
                                        @foreach ($company as $data)
                                            <option value="{{ $data->company }}"> {{ $data->company }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="field">
                                <label>{{ __('Department') }}</label>
                                <select name="department" class="ui search dropdown uppercase department">
                                    <option value="">Select Department</option>
                                    @isset($department)
                                        @foreach ($department as $data)
                                            <option value="{{ $data->department }}"> {{ $data->department }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="field">
                                <label>{{ __('Job Title / Position') }}</label>
                                <div class="ui search dropdown selection uppercase jobposition">
                                    <input type="hidden" name="jobposition">
                                    <i class="dropdown icon" tabindex="1"></i>
                                    <div class="default text">Select Job Title</div>
                                    <div class="menu">
                                    @isset($jobtitle)
                                        @isset($department)
                                            @foreach ($jobtitle as $data)
                                                @foreach ($department as $dept)
                                                    @if($dept->id == $data->dept_code)
                                                        <div class="item" data-value="{{ $data->jobtitle }}" data-dept="{{ $dept->department }}">{{ $data->jobtitle }}</div>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endisset
                                    @endisset
                                    </div>
                                </div>
                            </div>
                            <div class="field">
                                <label>{{ __('ID Number') }}</label>
                                <input type="text" class="uppercase" name="idno" value="">
                            </div>
                            <div class="field">
                                <label>{{ __('Email Address (Company)') }}</label>
                                <input type="email" name="companyemail" value="" class="lowercase">
                            </div>
                            <div class="field">
                                <label>{{ __('Leave Group') }}</label>
                                <select name="leaveprivilege" class="ui dropdown uppercase">
                                    <option value="">Select Leave Privilege</option>
                                    @isset($leavegroup) 
                                        @foreach($leavegroup as $lg)
                                            <option value="{{ $lg->id }}">{{ $lg->leavegroup }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <h4 class="ui dividing header">{{ __('Employment Information') }}</h4>
                            <div class="field">
                                <label>{{ __('Employment Type') }}</label>
                                <select name="employmenttype" class="ui dropdown uppercase">
                                    <option value="">Select Type</option>
                                    <option value="Regular">Regular</option>
                                    <option value="Trainee">Trainee</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>{{ __('Employment Status') }}</label>
                                <select name="employmentstatus" class="ui dropdown uppercase">
                                    <option value="">Select Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Archived">Archived</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>{{ __('Official Start Date') }}</label>
                                <input type="text" name="startdate" value="" class="airdatepicker uppercase" data-position="top right" placeholder="Date">
                            </div>
                            <div class="field">
                                <label>{{ __('Date Regularized') }}</label>
                                <input type="text" name="dateregularized" value="" class="airdatepicker uppercase" data-position="top right" placeholder="Date">
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 float-left">
                    <div class="ui error message">
                        <i class="close icon"></i>
                        <div class="header"></div>
                        <ul class="list">
                            <li class=""></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-12 float-left">
                    <div class="action align-right">
                        <button type="submit" name="submit" class="ui green button small"><i class="ui checkmark icon"></i>{{ __('Save') }}</button>
                        <a href="{{ url('employees') }}" class="ui grey button small"><i class="ui times icon"></i>{{ __('Cancel') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endsection

    @section('scripts')
    <script src="{{ asset('/assets/vendor/air-datepicker/dist/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/air-datepicker/dist/js/i18n/datepicker.en.js') }}"></script>
    <script type="text/javascript">
    $('.airdatepicker').datepicker({ language: 'en', dateFormat: 'yyyy-mm-dd', autoClose: true });
    
    $('.ui.dropdown.department').dropdown({ onChange: function(value, text, $selectedItem) {
        $('.jobposition .menu .item').addClass('hide disabled');
        $('.jobposition .text').text('');
        $('input[name="jobposition"]').val('');
        $('.jobposition .menu .item').each(function() {
            var dept = $(this).attr('data-dept');
            if(dept == value) {$(this).removeClass('hide disabled');};
        });
    }});

    function validateFile() {
        var f = document.getElementById("imagefile").value;
        var d = f.lastIndexOf(".") + 1;
        var ext = f.substr(d, f.length).toLowerCase();
        if (ext == "jpg" || ext == "jpeg" || ext == "png") { } else {
            document.getElementById("imagefile").value="";
            $.notify({
            icon: 'ui icon times',
            message: "Please upload only jpg/jpeg and png image formats."},
            {type: 'danger',timer: 400});
        }
    }
    </script>
    @endsection