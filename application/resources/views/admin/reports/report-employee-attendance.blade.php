@extends('layouts.default')

    @section('meta')
        <title>Reports | Workday Time Clock</title>
        <meta name="description" content="Workday reports, view reports, and export or download reports.">
    @endsection

    @section('styles')
        <link href="{{ asset('/assets/vendor/air-datepicker/dist/css/datepicker.min.css') }}" rel="stylesheet">
    @endsection

    @section('content')
    
    <div class="container-fluid">
        <div class="row">
            <h2 class="page-title">{{ __("Employee Attendance Report") }}
                <a href="{{ url('reports') }}" class="ui basic blue button mini offsettop5 float-right"><i class="ui icon chevron left"></i>{{ __("Return") }}</a>
            </h2> 
        </div>

        <div class="row">
            <div class="box box-success">
                <div class="box-body reportstable">
                    <form action="{{ url('export/report/attendance') }}" method="post" accept-charset="utf-8" class="ui small form form-filter" id="filterform">
                        @csrf
                        <div class="inline three fields">
                            <div class="three wide field">
                                <select name="employee" class="ui search dropdown getid">
                                    <option value="">{{ __("Employee") }}</option>
                                    @isset($employee)
                                        @foreach($employee as $e)
                                            <option value="{{ $e->lastname }}, {{ $e->firstname }}" data-id="{{ $e->idno }}">{{ $e->lastname }}, {{ $e->firstname }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>

                            <div class="two wide field">
                                <input id="datefrom" type="text" name="datefrom" value="" placeholder="Start Date" class="airdatepicker">
                                <i class="ui icon calendar alternate outline calendar-icon"></i>
                            </div>

                            <div class="two wide field">
                                <input id="dateto" type="text" name="dateto" value="" placeholder="End Date" class="airdatepicker">
                                <i class="ui icon calendar alternate outline calendar-icon"></i>
                            </div>

                            <input type="hidden" name="emp_id" value="">
                            <button id="btnfilter" class="ui icon button positive small inline-button"><i class="ui icon filter alternate"></i> {{ __("Filter") }}</button>
                            <button type="submit" name="submit" class="ui icon button blue small inline-button"><i class="ui icon download"></i> {{ __("Download") }}</button>
                        </div>
                    </form>

                    <table width="100%" class="table table-striped table-hover" id="dataTables-example" data-order='[[ 0, "desc" ]]'>
                        <thead>
                            <tr>
                                <th>{{ __("Date") }}</th>
                                <th>{{ __("Employee Name") }}</th>
                                <th>{{ __("Time In") }}</th>
                                <th>{{ __("Time Out") }}</th>
                                <th>{{ __("Total Hours") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($empAtten)
                            @foreach ($empAtten as $v)
                            <tr>
                                <td>{{ $v->date }}</td>
                                <td>{{ $v->employee }}</td>
                                <td>
                                    @php
                                        if($v->timein != null) {
                                            if($tf == 1) {
                                                echo e(date('h:i:s A', strtotime($v->timein)));
                                            } else {
                                                echo e(date('H:i:s', strtotime($v->timein)));
                                            }
                                        }
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        if($v->timeout != null) {
                                            if($tf == 1) {
                                                echo e(date('h:i:s A', strtotime($v->timeout)));
                                            } else {
                                                echo e(date('H:i:s', strtotime($v->timeout)));
                                            }
                                        }
                                    @endphp
                                </td>
                                <td>{{ $v->totalhours }}</td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    @endsection
    
    @section('scripts')
    <script src="{{ asset('/assets/vendor/air-datepicker/dist/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/air-datepicker/dist/js/i18n/datepicker.en.js') }}"></script>

    <script type="text/javascript">
    $('#dataTables-example').DataTable({responsive: true,pageLength: 15,lengthChange: false,searching: false,ordering: true});
    $('.airdatepicker').datepicker({ language: 'en', dateFormat: 'yyyy-mm-dd' });

    // transfer idno 
    $('.ui.dropdown.getid').dropdown({ onChange: function(value, text, $selectedItem) {
        $('select[name="employee"] option').each(function() {
            if($(this).val()==value) {var id = $(this).attr('data-id');$('input[name="emp_id"]').val(id);};
        });
    }});

    $('#btnfilter').click(function(event) {
        event.preventDefault();
        var emp_id = $('input[name="emp_id"]').val();
        var date_from = $('#datefrom').val();
        var date_to = $('#dateto').val();
        var url = $("#_url").val();
        var gtr = 0;

        $.ajax({
            url: url + '/get/employee-attendance/', type: 'get', dataType: 'json', data: {id: emp_id, datefrom: date_from, dateto: date_to}, headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                showdata(response);
                function showdata(jsonresponse) {
                    var employee = jsonresponse;
                    var tbody = $('#dataTables-example tbody');
                    
                    // clear data and destroy datatable
                    $('#dataTables-example').DataTable().destroy();
                    tbody.children('tr').remove();

                    // append table row data
                    for (var i = 0; i < employee.length; i++) {
                        gtr += +employee[i].totalhours;
                        var time_in = employee[i].timein;
                        var t_in = time_in.split(" ");
                        var time_out = employee[i].timeout;
                        var t_out = time_out.split(" ");

                        tbody.append("<tr>"+ 
                                        "<td>"+employee[i].date+"</td>" + 
                                        "<td>"+employee[i].employee+"</td>" + 
                                        "<td>"+ t_in[1]+" "+t_in[2] +"</td>" + 
                                        "<td>"+ t_out[1]+" "+t_out[2] +"</td>" + 
                                        "<td>"+employee[i].totalhours+"</td>" + 
                                    "</tr>");
                    }

                    tbody.append("<tr class='tablefooter'>"+ 
                        "<td colspan='4'><strong>TOTAL HOURS</strong></td>"+
                        "<td><strong>"+gtr.toFixed(2)+"</strong></td>"+
                        "<td class='hide'></td>"+
                        "<td class='hide'></td>"+
                        "<td class='hide'></td>"+
                        "<td class='hide'></td>"+
                        "<td class='hide'></td>"+
                    "</tr>");

                    // initialize datatable
                    $('#dataTables-example').DataTable({responsive: true,pageLength: 15,lengthChange: false,searching: false,ordering: false});
                }            
            }
        })
    });
    </script>
    @endsection 