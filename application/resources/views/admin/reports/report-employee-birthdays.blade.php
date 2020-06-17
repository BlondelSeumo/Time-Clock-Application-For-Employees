@extends('layouts.default')

    @section('meta')
        <title>Reports | Workday Time Clock</title>
        <meta name="description" content="Workday reports, view reports, and export or download reports.">
    @endsection
    
    @section('content')
    
    <div class="container-fluid">
        <div class="row">
            <h2 class="page-title">{{ __("Employee Birthdays") }}
                <a href="{{ url('export/report/birthdays') }}" class="ui basic button mini offsettop5 btn-export float-right"><i class="ui icon download"></i>{{ __("Export to CSV") }}</a>
                <a href="{{ url('reports') }}" class="ui basic blue button mini offsettop5 float-right"><i class="ui icon chevron left"></i>{{ __("Return") }}</a>
            </h2>   
        </div>

        <div class="row">
            <div class="box box-success">
                <div class="box-body">
                    <table width="100%" class="table table-striped table-hover" id="dataTables-example" data-order='[[ 0, "asc" ]]'>
                        <thead>
                            <tr>
                                <th>{{ __("Employee Name") }}</th>
                                <th>{{ __("Department") }}</th>
                                <th>{{ __("Position") }}</th>
                                <th>{{ __("Birthday") }}</th>
                                <th>{{ __("Contact Number") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($empBday)
                                @foreach ($empBday as $v)
                                    <tr>
                                        <td>{{ $v->lastname }}, {{ $v->firstname }} {{ $v->mi }}</td>
                                        <td>{{ $v->department }}</td>
                                        <td>{{ $v->jobposition }}</td>
                                        <td>
                                        @php  
                                            if($v->birthday != null) {
                                                echo e(date("D, M d Y", strtotime($v->birthday)));
                                            }
                                        @endphp
                                        </td>
                                        <td>{{ $v->mobileno }}</td>
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
    <script type="text/javascript">
    $('#dataTables-example').DataTable({responsive: true,pageLength: 15,lengthChange: false,searching: false,ordering: true});
    </script>
    @endsection 