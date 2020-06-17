@extends('layouts.default')

    @section('meta')
        <title>Employee Roles | Workday Time Clock</title>
        <meta name="description" content="Workday roles, view all employee roles, add roles, edit roles, and delete roles.">
    @endsection
    
    @section('content')
    @include('admin.modals.modal-add-roles')
    @include('admin.modals.modal-edit-role')

    <div class="container-fluid">
        <div class="row">
            <h2 class="page-title">{{ __("User Roles") }}
                <button class="ui positive button mini offsettop5 btn-add float-right"><i class="ui icon plus"></i>{{ __("Add") }}</button>
                <a href="{{ url('users') }}" class="ui basic blue button mini offsettop5 float-right"><i class="ui icon chevron left"></i>{{ __("Return") }}</a>
            </h2>
        </div>

        <div class="row">
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
        </div>

        <div class="row">
            <div class="box box-success">
                <div class="box-body">
                    <table width="100%" class="table table-striped table-hover" id="dataTables-example" data-order='[[ 0, "asc" ]]'>
                        <thead>
                            <tr>
                                <th>{{ __("Role Name") }}</th>
                                <th>{{ __("Status") }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($roles)
                                @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->role_name }}</td>
                                    <td>{{ $role->state }}</td>
                                    <td class="align-right">
                                        <a href="{{ url('/users/roles/permissions/edit') }}/{{ $role->id }}" class="ui circular basic icon button tiny">
                                        <i class="ui icon tasks"></i></a>
                                        <button type="button" class="ui circular basic icon button tiny btn-edit" data-id="{{ $role->id }}"><i class="icon edit outline"></i></button>
                                        <a href="{{ url('/users/roles/delete/'.$role->id) }}" class="ui circular basic icon button tiny"><i class="icon trash alternate outline"></i></a>
                                    </td>
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
    $('#dataTables-example').DataTable({responsive: true,pageLength: 15,lengthChange: false,searching: true,ordering: true});
    
    $('.btn-edit').click(function(event) {
        var id = $(this).attr('data-id');
        var url = $("#_url").val();
        $.ajax({
            url: url + '/user/roles/get/',type: 'get',dataType: 'json',data: {id: id},headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                $state = response['state'];
                $('.edit input[name="id"]').val(response['id']);
                $('.edit input[name="role_name"]').val(response['role_name']);
                if ($state == 'Active') {
                    $('.ui.dropdown.state').dropdown({values: [{name: 'Active',value: 'Active', selected : true},{name: 'Disabled',value: 'Disabled'}]});
                } else if($state == 'Disabled') {
                    $('.ui.dropdown.state').dropdown({values: [{name: 'Active',value: 'Active'},{name: 'Disabled',value: 'Disabled', selected : true}]});
                } 
                $('ui.modal.edit').modal('show');
            }
        })
    });
    </script>
    
    @endsection