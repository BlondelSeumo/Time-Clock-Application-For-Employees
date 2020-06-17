@extends('layouts.default')
    
    @section('meta')
        <title>Edit Leave of Absence | Workday Time Clock</title>
        <meta name="description" content="Workday edit employee leave of absence.">
    @endsection 

    @section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title">{{ __("Edit Leave") }}</h2>
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
                <form id="edit_leave_form" action="{{ url('leaves/update') }}" class="ui form" method="post" accept-charset="utf-8">
                @csrf
                    <div class="field">
                        <label>{{ __("Employee") }}</label>
                        <input type="text" class="readonly" readonly="" value="@isset($l->employee){{ $l->employee }}@endisset">
                    </div>
                    <div class="field">
                        <label>{{ __("Leave Type") }}</label>
                        <input type="text" class="readonly" readonly="" value="@isset($l->type){{ $l->type }}@endisset">
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label for="">{{ __("Leave From") }}</label>
                            <input type="text" class="readonly" readonly="" value="@isset($l->leavefrom){{ $l->leavefrom }}@endisset"/>
                        </div>
                        <div class="field">
                            <label for="">{{ __("Leave To") }}</label>
                            <input type="text" class="readonly" readonly="" value="@isset($l->leaveto){{ $l->leaveto }}@endisset"/>
                        </div>
                    </div>
                    <div class="field">
                        <label for="">{{ __("Return Date") }}</label>
                        <input id="returndate" type="text" class="readonly" readonly="" value="@isset($l->returndate){{ $l->returndate }}@endisset"/>
                    </div>
                    <div class="field">
                        <label>{{ __("Reason") }}</label>
                        <textarea class="uppercase readonly" readonly="" rows="5">@isset($l->reason){{ $l->reason }}@endisset</textarea>
                    </div>
                    <div class="field">
                        <p class="ui horizontal divider tiny sub header">{{ __("Manager Privilege") }}</p>
                    </div>
                    <div class="field">
                        <label>{{ __("Status") }}</label>
                        <select class="ui dropdown uppercase" name="status">
                            <option value="Approved" @isset($l->status) @if($l->status == 'Approved') selected @endif @endisset>Approved</option>
                            <option value="Pending" @isset($l->status) @if($l->status == 'Pending') selected @endif @endisset>Pending</option>
                            <option value="Declined" @isset($l->status) @if($l->status == 'Declined') selected @endif @endisset>Declined</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>{{ __("Add Comment (Optional)") }}</label>
                        <textarea name="comment" class="uppercase" rows="5">@isset($l->comment){{ $l->comment }}@endisset</textarea>
                    </div>
                    <div class="ui error message">
                        <i class="close icon"></i>
                        <div class="header"></div>
                        <ul class="list">
                            <li class=""></li>
                        </ul>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" class="readonly" readonly="" name="id" value="@isset($e_id){{ $e_id }}@endisset">
                    <button class="ui positive small button" type="submit" name="submit"><i class="ui checkmark icon"></i> {{ __("Update") }}</button>
                    <a href="{{ url('leaves') }}" class="ui black grey small button"><i class="ui times icon"></i> {{ __("Cancel") }}</a>
                </div>
                </form>
                
                </div>
            </div>
        </div>
    </div>

    @endsection
