@extends('layouts.default')

    @section('meta')
        <title>Edit User | Workday Time Clock</title>
        <meta name="description" content="Workday edit user.">
    @endsection 

    @section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title">{{ __("Edit User") }}</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-content">
                        @if ($errors->any())
                        <div class="ui error message">
                            <i class="close icon"></i>
                            <div class="header">{{ __("There were some errors with your submission") }}</div>
                            <ul class="list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form id="edit_user_form" action="{{ url('users/update/user') }}" class="ui form add-user" method="post" accept-charset="utf-8">
                            @csrf
                            <div class="field">
                                <label>{{ __("Employee") }}</label>
                                <input type="text" name="employee" value="@isset($u->name){{ $u->name }}@endisset" class="readonly uppercase" readonly>
                            </div>
                            <div class="field">
                                <label>{{ __("Email") }}</label>
                                <input type="text" name="email" value="@isset($u->email){{ $u->email }}@endisset" class="readonly lowercase" readonly>
                            </div>
                            <div class="grouped fields opt-radio">
                                <label class="">{{ __("Choose Account type") }}</label>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        <input type="radio" name="acc_type" value="1" @isset($u->acc_type) @if($u->acc_type == 1) checked @endif @endisset>
                                        <label>{{ __("Employee") }}</label>
                                    </div>
                                </div>
                                <div class="field" style="padding:0px!important">
                                    <div class="ui radio checkbox">
                                        <input type="radio" name="acc_type" value="2" @isset($u->acc_type) @if($u->acc_type == 2) checked @endif @endisset>
                                        <label>{{ __("Admin") }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="fields">
                                <div class="sixteen wide field role">
                                    <label for="">{{ __("Role") }}</label>
                                    <select class="ui dropdown uppercase" name="role_id">
                                        <option value="">Select Role</option>
                                        @isset($r)
                                            @foreach ($r as $role)
                                                <option value="{{ $role->id }}" @if($role->id == $u->role_id) selected @endif>{{ $role->role_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="field">
                                <label>{{ __("Status") }}</label>
                                <select class="ui dropdown uppercase" name="status">
                                    <option value="">Select Status</option>
                                    <option value="1" @isset($u->status) @if($u->status == 1) selected @endif @endisset>Enabled</option>
                                    <option value="0" @isset($u->status) @if($u->status == 0) selected @endif @endisset>Disabled</option>
                                </select>
                            </div>
                            <div class="two fields">
                                <div class="field">
                                    <label for="">{{ __("New Password") }}</label>
                                    <input type="password" name="password" class="" placeholder="********">
                                </div>
                                <div class="field">
                                    <label for="">{{ __("Confirm New Password") }}</label>
                                    <input type="password" name="password_confirmation" class="" placeholder="********">
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui error message">
                                    <i class="close icon"></i>
                                    <div class="header"></div>
                                    <ul class="list">
                                        <li class=""></li>
                                    </ul>
                                </div>
                            </div>
                    </div>

                    <div class="box-footer">
                        <input type="hidden" value="@isset($e_id){{ $e_id }}@endisset" name="ref">
                        <button class="ui positive approve small button" type="submit" name="submit"><i class="ui checkmark icon"></i> {{ __("Update") }}</button>
                        <a href="{{ url('users') }}" class="ui black grey small button"><i class="ui times icon"></i> {{ __("Cancel") }}</a>
                    </div>

                    </form>

                </div>
            </div>
        </div>

        @endsection

        @section('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                $('.opt-radio .checkbox').first().checkbox({
                    ischecked: function () {
                        $('.role, .role .ui.dropdown').addClass('disabled');
                        $('select[name="role_id"]').addClass('disabled').val('');
                    }
                });
            });
        </script>
        @endsection