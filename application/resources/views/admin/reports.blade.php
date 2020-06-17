@extends('layouts.default')
    @section('meta')
        <title>Reports | Workday Time Clock</title>
        <meta name="description" content="Workday reports, view reports, and export or download reports.">
    @endsection 

    @section('content')
    
    <div class="container-fluid">
        <div class="row">
            <h2 class="page-title">{{ __('Reports') }}</h2>
        </div>

        <div class="row">
            <div class="box box-success">
                <div class="box-body">
                <table width="100%" class="reports-table table table-striped table-hover" id="dataTables-example" data-order='[[ 0, "asc" ]]'>
                    <thead>
                        <tr>
                            <th>{{ __('Report name') }}</th>
                            <th class="odd">{{ __('Last Viewed') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><a href="{{ url('reports/employee-list') }}"><i class="ui icon users"></i> {{ __('Employee List Report') }}</a></td>
                            <td class="odd">
                                @isset($lastviews)
                                    @foreach ($lastviews as $views)
                                        @if($views->report_id == 1)
                                            {{ $views->last_viewed }}
                                        @endif
                                    @endforeach
                                @endisset
                            </td>
                        </tr>
                        <tr>
                            <td><a href="{{ url('reports/employee-attendance') }}"><i class="ui icon clock"></i> {{ __('Employee Attendance Report') }}</a></td>
                            <td class="odd">
                                @isset($lastviews)
                                    @foreach ($lastviews as $views)
                                        @if($views->report_id == 2)
                                            {{ $views->last_viewed }}
                                        @endif
                                    @endforeach
                                @endisset
                            </td>
                        </tr>
                        <tr>
                            <td><a href="{{ url('reports/employee-leaves') }}"><i class="ui icon calendar plus"></i> {{ __('Employee Leaves Report') }}</a></td>
                            <td class="odd">
                                @isset($lastviews)
                                    @foreach ($lastviews as $views)
                                        @if($views->report_id == 3)
                                            {{ $views->last_viewed }}
                                        @endif
                                    @endforeach
                                @endisset
                            </td>
                        </tr>
                        <tr>
                            <td><a href="{{ url('reports/employee-schedule') }}"><i class="ui icon calendar alternate outline"></i> {{ __('Employee Schedule Report') }}</a></td>
                            <td class="odd">
                                @isset($lastviews)
                                    @foreach ($lastviews as $views)
                                        @if($views->report_id == 4)
                                            {{ $views->last_viewed }}
                                        @endif
                                    @endforeach
                                @endisset
                            </td>
                        </tr>
                        <tr>
                            <td><a href="{{ url('reports/organization-profile') }}"><i class="ui icon chart pie"></i> {{ __("Organizational Profile") }}</a></td>
                            <td class="odd">
                                @isset($lastviews)
                                    @foreach ($lastviews as $views)
                                        @if($views->report_id == 5)
                                            {{ $views->last_viewed }}
                                        @endif
                                    @endforeach
                                @endisset
                            </td>
                        </tr>
                        <tr>
                            <td><a href="{{ url('reports/employee-birthdays') }}"><i class="ui icon birthday cake"></i> {{ __('Employee Birthdays') }}</a></td>
                            <td class="odd">
                                @isset($lastviews)
                                    @foreach ($lastviews as $views)
                                        @if($views->report_id == 7)
                                            {{ $views->last_viewed }}
                                        @endif
                                    @endforeach
                                @endisset
                            </td>
                        </tr>
                        <tr>
                            <td><a href="{{ url('reports/user-accounts') }}"><i class="ui icon address book outline"></i> {{ __('User Accounts Report') }}</a></td>
                            <td class="odd">
                                @isset($lastviews)
                                    @foreach ($lastviews as $views)
                                        @if($views->report_id == 6)
                                            {{ $views->last_viewed }}
                                        @endif
                                    @endforeach
                                @endisset
                            </td>
                        </tr>
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