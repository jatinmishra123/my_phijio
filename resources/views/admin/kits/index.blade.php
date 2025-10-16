@extends('admin.layout')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Kits List</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Kits Master</a></li>
                    <li class="breadcrumb-item active">Kits List</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Kits List</h5>
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#basicModal" class="btn btn-success btn-sm">
                    <i class="ri-add-fill me-1 align-bottom"></i> Add Kits
                </a>
            </div>

            <div class="card-body">
                <table id="kitsTable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Banner</th>
                            <th>Kit Name</th>
                            <th>Description</th>
                            <th>Price (₹)</th>
                            <th>Benefits</th>
                            <th>Terms and Conditions</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($physiotherapist_kits as $kit)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ $kit->poster_image }}"
                                    alt="Kit Banner"
                                    style="width: 120px; height: 80px; object-fit: cover; border-radius: 4px;">
                            </td>
                            <td>{{ $kit->kit_name }}</td>
                            <td>{{ $kit->description }}</td>
                            <td>₹{{ number_format($kit->price, 2) }}</td>
                            <td>
                                <ul class="mb-0">
                                    @foreach(json_decode($kit->benefits) as $benefit)
                                        <li>{{ $benefit }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="mb-0">
                                    @foreach(json_decode($kit->terms_and_conditions) as $term)
                                        <li>{{ $term }}</li>
                                    @endforeach
                                </ul>
                            </td>
                           

                            <td>
                               <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-fill align-middle"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">

                                        <li>
                                        <a class="dropdown-item" data-bs-target="#deletepopup" data-bs-toggle="modal" onclick="deletepopup('{{ $kit->id }}','physiotherapist_kits','delete')" href="javascript:void(0);">
                                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                        </a>
                                        </li>
                                    </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('admin.deactivate')

<!-- Add Kit Modal -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
    <form id="kitForm" method="POST" action="{{ url('/admin/kits/store') }}" enctype="multipart/form-data">
    @csrf
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Add Kits</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <!-- Loader placeholder -->
            <div id="ajax-loader" class="text-center mb-2" style="display:none;">
                <div class="spinner-border text-success" role="status"></div>
            </div>

            <div class="mb-3">
                <label>Kit Name</label>
                <input type="text" class="form-control" name="kit_name" required>
                <small class="text-danger error-kit_name"></small>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <input type="text" class="form-control" name="description" required>
                <small class="text-danger error-description"></small>
            </div>
            <div class="mb-3">
                <label>Benefits (comma-separated)</label>
                <input type="text" class="form-control" name="benefits" required>
                <small class="text-danger error-benefits"></small>
            </div>
            <div class="mb-3">
                <label>Price (INR)</label>
                <input type="number" class="form-control" name="price" required>
                <small class="text-danger error-price"></small>
            </div>
            <div class="mb-3">
                <label>Poster Image</label>
                <input type="file" class="form-control" name="poster_image" required>
                <small class="text-danger error-poster_image"></small>
            </div>
            <div class="mb-3">
                <label>Terms And Conditons (comma-separated)</label>
                <input type="text" class="form-control" name="terms_and_conditions" required>
                <small class="text-danger error-terms_and_conditions"></small>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">Add Kit</button>
        </div>
    </div>
</form>

    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script>

$(document).ready(function () {
    $('#kitsTable').DataTable();

    $('#kitForm').submit(function (e) {
        e.preventDefault();
        $('.text-danger').text('');
        $('#ajax-loader').show();

        const form = $(this);
        const formData = new FormData(this);

        $.ajax({
            url: "{{ url('admin/kits/store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (result) {
                $('#ajax-loader').hide();

                if (result.code === 200) {
                    swal(result.message, '', 'success');
                    $('#basicModal').modal('hide');
                    form[0].reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    $.each(result.message, function (key, val) {
                        $('.error-' + key).text(val[0]);
                    });
                    swal("Validation Error", '', 'error');
                }
            },

            error: function (xhr) {
                $('#ajax-loader').hide();
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, val) {
                        $('.error-' + key).text(val[0]);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong.'
                    });
                }
            }
        });
    });
});
</script>

@endsection
