<div class="ui modal medium add">
    <div class="header">{{ __("Add New User") }}</div>
    <div class="content">
        <form id="add_user_form" action="{{ url('users/register') }}" class="ui form add-user" method="post" accept-charset="utf-8">
            @csrf
            <div class="field">
                <label>{{ __("Employee") }}</label>
                <select class="ui search dropdown getemail uppercase" name="name">
                    <option value="">Select Employee</option>
                    @isset($employees)
                        @foreach ($employees as $data)
                        <option value="{{ $data->lastname }}, {{ $data->firstname }}" data-e="{{ $data->emailaddress }}"
                            data-ref="{{ $data->id }}">{{ $data->lastname }}, {{ $data->firstname }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="field">
                <label>{{ __("Email") }}</label>
                <input type="text" name="email" class="readonly lowercase" value="" readonly>
            </div>
            <div class="grouped fields opt-radio">
                <label>{{ __("Choose Account type") }} </label>
                <div class="field">
                    <div class="ui radio checkbox">
                        <input type="radio" name="acc_type" value="1">
                        <label>{{ __("Employee") }}</label>
                    </div>
                </div>
                <div class="field" style="padding:0px!important">
                    <div class="ui radio checkbox">
                        <input type="radio" name="acc_type" value="2">
                        <label>{{ __("Admin") }}</label>
                    </div>
                </div>
            </div>
            <div class="fields">
                <div class="sixteen wide field role">
                    <label for="">{{ __("Role") }}</label>
                    <select class="ui dropdown uppercase" name="role_id">
                        <option value="">Select Role</option>
                        @isset($roles)
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
            </div>
            <div class="fields">
                <div class="sixteen wide field">
                    <label>{{ __("Status") }}</label>
                    <select class="ui dropdown uppercase" name="status">
                        <option value="">Select Status</option>
                        <option value="1">Enabled</option>
                        <option value="0">Disabled</option>
                    </select>
                </div>
            </div>
            <div class="two fields">
                <div class="field">
                    <label for="">{{ __("Password") }}</label>
                    <input type="password" name="password" class="">
                </div>
                <div class="field">
                    <label for="">{{ __("Confirm Password") }}</label>
                    <input type="password" name="password_confirmation" class="">
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
    <div class="actions">
        <input type="hidden" value="" name="ref">
        <button class="ui positive approve small button" type="submit" name="submit"><i class="ui checkmark icon"></i> {{ __("Register") }}</button>
        <button class="ui grey cancel small button" type="button"><i class="ui times icon"></i> {{ __("Cancel") }}</button>
    </div>
    </form>
</div>