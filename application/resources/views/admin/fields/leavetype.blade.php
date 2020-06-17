@extends('layouts.default')

    @section('meta')
        <title>Leave Types | Workday Time Clock</title>
        <meta name="description" content="Workday leave type, view leave types, add or edit leave types and export or download leave types.">
    @endsection

    @section('content')
    @include('admin.modals.modal-import-leavetypes')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title uppercase">{{ __("Add Leave Types") }}
                    <a href="{{ url('fields/leavetype/leave-groups') }}" class="ui primary mini button offsettop5 float-right"><i class="icon calendar check outline"></i>{{ __("Leave Groups") }}</a>
                    <button class="ui basic button mini offsettop5 btn-import float-right"><i class="ui icon upload"></i>{{ __("Import") }}</button>
                    <a href="{{ url('export/fields/leavetypes' )}}" class="ui basic button mini offsettop5 btm-export float-right"><i class="ui icon download"></i>{{ __("Export") }}</a>
                </h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-body">
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
                        <form id="add_leavetype_form" action="{{ url('fields/leavetype/add') }}" class="ui form" method="post" accept-charset="utf-8">
                            @csrf
                            <div class="field">
                                <label>{{ __("Leave Name") }} <span class="help">e.g. "Vacation Leave, Sick Leave"</span></label>
                                <input class="uppercase" name="leavetype" value="" type="text">
                            </div>
                            <div class="field">
                                <label>{{ __("Credits") }} <span class="help">e.g. "15" (days)</span></label>
                                <input class="" name="credits" value="" type="text">
                            </div>
                            <div class="grouped fields opt-radio">
                                <label class="">{{ __("Choose Term") }}</label>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        <input type="radio" name="term" value="Monthly" checked="checked">
                                        <label>{{ __("Monthly") }}</label>
                                    </div>
                                </div>
                                <div class="field" style="padding:0px!important">
                                    <div class="ui radio checkbox">
                                        <input type="radio" name="term" value="Yearly">
                                        <label>{{ __("Yearly") }}</label>
                                    </div>
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
                            <div class="actions">
                                <button type="submit" class="ui positive button small"><i class="ui icon check"></i> {{ __("Save") }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="box box-success">
                    <div class="box-body">
                        <table width="100%" class="table table-striped table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>{{ __("Description") }}</th>
                                    <th>{{ __("Credits") }}</th>
                                    <th>{{ __("Term") }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($data)
                                @foreach ($data as $leavetype)
                                <tr>
                                    <td>{{ $leavetype->leavetype }}</td>
                                    <td>{{ $leavetype->limit }}</td>
                                    <td>{{ $leavetype->percalendar }}</td>
                                    <td class="align-right"><a href="{{ url('fields/leavetype/delete/'.$leavetype->id) }}" class="ui circular basic icon button tiny"><i class="icon trash alternate outline"></i></a></td>
                                </tr>
                                @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('scripts')
    <script type="text/javascript">
    $('#dataTables-example').DataTable({responsive: true,pageLength: 15,lengthChange: false,searching: true,ordering: true});

    function validateFile() {
        var f = document.getElementById("csvfile").value;
        var d = f.lastIndexOf(".") + 1;
        var ext = f.substr(d, f.length).toLowerCase();
        if (ext == "csv") {} else {
            document.getElementById("csvfile").value = "";
            $.notify({
                icon: 'ui icon times',
                message: "Please upload only CSV file format."
            }, {
                type: 'danger',
                timer: 400
            });
        }
    }
    </script>
    @endsection