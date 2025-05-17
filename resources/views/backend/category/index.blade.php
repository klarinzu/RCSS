@extends('adminlte::page')

@section('title', 'Add Category')

@section('content_header')

    <div class="container-fluid">
        <div class="row ">
            <div class="col-sm-6">
                <h1 class="m-0">All Categories</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Category</li>
                </ol>
            </div>
        </div>
    </div>

@stop

@section('content')

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

    <div class="container-fluid">
        <div class="row  justify-content-between">

            <div class="col-md-12 ">
                <!-- <h5><a href="{{ route('category.create') }}" class="btn btn-primary mb-1"><i class="fas fa-fw fa-plus "></i>
                        Add New</a>
                </h5> -->
                <div class="card p-2">

                    <div id="" class="card-body p-0">
                        <table id="myTable" class="table table-striped projects">
                            <thead>
                                <tr>
                                    <th style="width: 1%">
                                        #
                                    </th>
                                    <th style="width: 20%">
                                        Name
                                    </th>
                                    <th style="width: 20%">
                                        Slug
                                    </th>
                                    <th style="width: 15%" class="text-center">
                                        Service Count
                                    </th>
                                    <th style="width: 7%" class="text-center">
                                        Status
                                    </th>
                                    <th style="width: 25%" class="text-center">Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td>
                                            <a>
                                                {{ $category->title }}
                                            </a>

                                        </td>
                                        <td>
                                            <a>{{ $category->slug }}</a>
                                        </td>
                                        {{-- <td>

                                            {{ $category->posts->count() }}
                                        </td> --}}
                                        <td class="text-center">
                                            {{ $category->services->count() }}
                                        </td>
                                        <td class="text-center">
                                            @if ($category->status)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">In active</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                            <div>
                                                <a class="btn btn-info btn-sm ml-2"
                                                    href="{{ route('category.edit', $category->id) }}">
                                                    <i class="fas fa-pencil-alt">
                                                    </i>
                                                    Edit
                                                </a>
                                            </div>
                                            <div>
                                                <form action="{{ route('category.destroy', $category->id) }}"
                                                    method="POST" class="archive-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                            type="button" 
                                                            class="btn btn-danger btn-sm ml-2 archive-btn"
                                                            data-category-id="{{ $category->id }}"
                                                            data-category-title="{{ $category->title }}">
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
        </div>
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
                Are you sure you want to archive <span id="archiveCategoryTitle"></span>?
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
    <script>
        $('#title').on("change keyup paste click", function() {
            var Text = $(this).val().trim();
            Text = Text.toLowerCase();
            Text = Text.replace(/[^a-zA-Z0-9]+/g, '-');
            $('#slug').val(Text);
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                responsive: true
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".alert").delay(6000).slideUp(300);
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

    {{-- Archive Modal Script --}}
    <script>
        $(document).ready(function() {
            let formToSubmit = null;
            
            $('.archive-btn').click(function() {
                const categoryId = $(this).data('category-id');
                const categoryTitle = $(this).data('category-title');
                formToSubmit = $(this).closest('form');
                
                $('#archiveCategoryTitle').text(categoryTitle);
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
@stop
