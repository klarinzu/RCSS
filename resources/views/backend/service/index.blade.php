@extends('adminlte::page')

@section('title', 'All Services')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>All Services</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <!-- <li class="breadcrumb-item"><a href="{{ route('service.create') }}">+ Add New</a> |</li> -->
                <li class=""> &nbsp; <a href="{{ route('service.trash') }}"><i class="fas fa-archive"></i> View Archive</a></li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="">
        <!-- Content Header (Page header) -->
        <!-- Content Header (Page header) -->
    
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (count($errors) > 0)
                <div class="alert alert-dismissable alert-danger mt-3">
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
            @if (session('success'))
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="card py-2 px-2">

                            <div class="card-body p-0">
                                <table id="myTable" class="table table-striped projects ">
                                    <thead>
                                        <tr>
                                            <th style="width: 1%">
                                                #
                                            </th>
                                            <th style="width: 20%">
                                                Title
                                            </th>
                                            <th style="width: 10%">
                                                Image
                                            </th>
                                            <th style="width: 10%">
                                                Category
                                            </th>
                                            {{-- <th style="width: 10%">
                                                Featured
                                            </th> --}}
                                            <th style="width: 10%" class="text-center">
                                                Status
                                            </th>
                                            <th style="width: 8%" class="text-center">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($services as $service)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    <a>
                                                        {{ $service->title }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if ($service->image)
                                                        <img style="width:75px;"
                                                            src="{{ asset('uploads/images/service/' . $service->image) }}"
                                                            alt="">
                                                    @else
                                                        <img style="width:75px;"
                                                            src="{{ asset('uploads/images/no-image.jpg') }}"
                                                            alt="">
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $service->category->title ?? 'NA' }}
                                                </td>
                                                {{-- <td>
                                                    @if ($service->featured)
                                                        Yes
                                                    @else
                                                        No
                                                    @endif
                                                </td> --}}

                                                <td class="text-center">
                                                    @if ($service->status)
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-danger">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center">
                                                        <div>
                                                            <a class="btn btn-info btn-sm ml-2"
                                                                href="{{ route('service.edit', $service->id) }}">
                                                                <i class="fas fa-pencil-alt">
                                                                </i>
                                                                Edit
                                                            </a>
                                                        </div>
                                                        <div>
                                                            <form action="{{ route('service.destroy', $service->id) }}"
                                                                method="post" class="archive-form">
                                                                @csrf
                                                                @method('delete')
                                                                <button
                                                                    type="button" 
                                                                    class="btn btn-danger btn-sm ml-2 archive-btn"
                                                                    data-service-id="{{ $service->id }}"
                                                                    data-service-title="{{ $service->title }}">
                                                                    <i class="fas fa-archive">
                                                                    </i>
                                                                    Archive
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                    <!-- /.col -->

                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
    </div>

    <!-- Archive Confirmation Modal -->
    <div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="archiveModalLabel">Confirm Archive</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to archive <span id="archiveServiceTitle"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmArchive">Archive</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')

    {{-- hide notifcation --}}
    <script>
        $(document).ready(function() {
            $(".alert").delay(6000).slideUp(300);
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                responsive: true
            });
        });
    </script>

    {{-- Disable buttons after click --}}
    <script>
        $(document).ready(function() {
            // Handle all form submissions
            $('form').on('submit', function() {
                // Disable all buttons within the form
                $(this).find('button[type="submit"]').prop('disabled', true);
                // Add loading state
                $(this).find('button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Processing...');
            });

            // Handle all anchor tags with onclick confirm
            $('a[onclick*="confirm"]').on('click', function() {
                if (confirm($(this).attr('onclick').match(/confirm\('([^']+)'\)/)[1])) {
                    $(this).prop('disabled', true);
                    $(this).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
                    return true;
                }
                return false;
            });
        });
    </script>

    {{-- Sucess and error notification alert --}}
    <script>
        $(document).ready(function() {
        // show error message
        @if ($errors->any())
            //var errorMessage = @json($errors->any()); // Get the first validation error message
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5500
            });

            Toast.fire({
                icon: 'error',
                title: 'There are form validation errors. Please fix them.'
            });
        @endif

        // success message
        @if (session('success'))
            var successMessage = @json(session('success')); // Get the first sucess message
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5500
            });

            Toast.fire({
                icon: 'success',
                title: successMessage
            });
        @endif

        });
    </script>

    {{-- Archive Modal Script --}}
    <script>
        $(document).ready(function() {
            let formToSubmit = null;
            
            $('.archive-btn').click(function() {
                const serviceId = $(this).data('service-id');
                const serviceTitle = $(this).data('service-title');
                formToSubmit = $(this).closest('form');
                
                $('#archiveServiceTitle').text(serviceTitle);
                $('#archiveModal').modal('show');
            });
            
            $('#confirmArchive').click(function() {
                if (formToSubmit) {
                    formToSubmit.submit();
                }
                $('#archiveModal').modal('hide');
            });
        });
    </script>
@endsection
