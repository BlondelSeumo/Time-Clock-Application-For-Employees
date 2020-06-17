<div class="ui add modal small">
    <div class="header">{{ __("Add New Role") }}</div>
    <div class="content">
    <form id="add_role_form" action="{{ url('users/roles/add') }}" class="ui form" method="post" accept-charset="utf-8">
        @csrf
        <div class="field">
            <label>{{ __("Role Name") }}</label>
            <input class="uppercase" name="role_name" value="" type="text">
        </div>
        <div class="field">
            <label>{{ __("Status") }}</label>
            <select name="state" class="ui dropdown uppercase">
                <option value="">Select Status</option>
                <option value="Active">Active</option>
                <option value="Disabled">Disabled</option>
            </select>
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
        <button class="ui positive small button" type="submit" name="submit"><i class="ui checkmark icon"></i> {{ __("Save") }}</button>
        <button class="ui grey cancel small button" type="button"><i class="ui times icon"></i> {{ __("Cancel") }}</button>
    </div>
    </form>  
</div>
