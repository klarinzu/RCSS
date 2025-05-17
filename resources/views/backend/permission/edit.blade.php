@extends('adminlte::page')

@section('title', 'Edit Role')

@section('content_header')
    <div class="row mb-2 pl-md-2">
        <div class="col-sm-6">
            <div class="pull-left">
                <h2>Edit Role & Permission</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('permission.index') }}"> Back</a>
            </div>

        </div>
    </div>
@stop

@section('content')
    <div class="pl-md-2">
        @if (count($errors) > 0)
            <div class="alert alert-dismissable alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Whoops!</strong> There were some problems with your input.<br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <strong class="h4 bg-warning px-2 rounded text-capitalize">Role: {{ $role->name }}</strong>

                        </div>
                    </div>
                    <div class="col-md-8">
                        <form action="{{ route('permission.update',$role->id) }}" method="post" id="updateRoleForm">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <p class="h5 pb-2">Permissions:</p>

                                <div class="row">
                                    @foreach ($permissions as $permission)
                                    <div class="col-md-3">
                                        <label class="text-capitalize ">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                            {{ ($roleName === 'admin' || $roleName === 'subscriber') ? 'disabled' : '' }}>
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                @endforeach
                                </div>
                                <button type="button" onclick="showUpdateConfirmation()" class="btn btn-danger mt-2">Submit</button>
                            </div>
                        </form>

                    </div>

                </div>



            </div>
        </div>
    </div>

    <!-- Update Confirmation Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Confirm Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to update this role?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmUpdate">Update</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        function showUpdateConfirmation() {
            const updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
            updateModal.show();
            
            document.getElementById('confirmUpdate').addEventListener('click', function() {
                document.getElementById('updateRoleForm').submit();
                updateModal.hide();
            });
        }

        function DeleteAlert() {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
            
            document.getElementById('confirmDelete').addEventListener('click', function() {
                // code to delete data goes here
                console.log("Data has been deleted.");
                deleteModal.hide();
            });
        }
    </script>
@stop
