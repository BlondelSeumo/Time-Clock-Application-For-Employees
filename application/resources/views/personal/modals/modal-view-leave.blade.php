<div class="ui modal medium view">
    <div class="header">{{ __("My Leave") }}</div>
    <div class="content">
        <form action="" class="ui form" method="post" accept-charset="utf-8">
        <div class="field">
            <label>{{ __("Employee") }}</label>
            <input type="text" class="employee uppercase readonly" value="" readonly="">
        </div>
        <div class="field">
            <label>{{ __("Leave Type") }}</label>
            <input type="text" class="leavetype uppercase readonly" value="" readonly="">
        </div>
        <div class="two fields">
            <div class="field">
                <label for="">{{ __("Leave From") }}</label>
                <input type="text" class="leavefrom readonly" value="" readonly="" />
            </div>
            <div class="field">
                <label for="">{{ __("Leave To") }}</label>
                <input type="text" class="leaveto readonly" value="" readonly=""/>
            </div>
        </div>
        <div class="field">
            <label for="">{{ __("Return Date") }}</label>
            <input type="text" class="returndate readonly" value="" readonly=""/>
        </div>
        <div class="field">
            <label>{{ __("Reason") }}</label>
            <textarea rows="5" class="reason uppercase readonly" value="" readonly=""></textarea>
        </div>
        <div class="field">
            <label for="">{{ __("Status") }}</label>
            <input type="text" class="status readonly" value="" readonly=""/>
        </div>
    </div>
    <div class="actions">
        <button class="ui grey small button cancel" type="button"><i class="ui times icon"></i> {{ __("Close") }}</button>
    </div>
    </form>  
</div>
    