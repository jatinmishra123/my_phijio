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
            <h4 class="mb-sm-0">Plans List</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Plans Master</a></li>
                    <li class="breadcrumb-item active">Plans List</li>
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
                <h5 class="card-title mb-0">Plans List</h5>
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#basicModal" class="btn btn-success btn-sm">
                    <i class="ri-add-fill me-1 align-bottom"></i> Add Plan
                </a>
            </div>

            <div class="card-body">
                <table id="plansTable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Duration</th>
                            <th>Price (₹)</th>
                            <th>Benefits</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plans as $plan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $plan->title }}</td>
                            <td>{{ $plan->description ?? '' }}</td>
                            <td>
                                @if($plan->type == 'standard')
                                    <span class="badge bg-primary">Standard</span>
                                @else
                                    <span class="badge bg-warning text-dark">Premium</span>
                                @endif
                            </td>
                            <td>{{ $plan->duration }} {{ ucfirst($plan->duration_type) }}{{ $plan->duration > 1 ? 's' : '' }}</td>
                            <td>₹{{ number_format($plan->price, 2) }}</td>
                            <td>
                                <ul class="mb-0">
                                    @foreach(json_decode($plan->benefits) as $benefit)
                                        <li>{{ $benefit }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                @if($plan->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                               <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-fill align-middle"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        
                                        <li>
                                        <a class="dropdown-item" data-bs-target="#deletepopup" data-bs-toggle="modal" onclick="deletepopup('{{ $plan->id }}','plans','delete')" href="javascript:void(0);">
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

<!-- Add Plan Modal -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
     <form id="planForm" method="POST">
    @csrf
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Add Plan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <!-- Loader placeholder -->
            <div id="ajax-loader" class="text-center mb-2" style="display:none;">
                <div class="spinner-border text-success" role="status"></div>
            </div>

            <div class="mb-3">
                <label>Title</label>
                <input type="text" class="form-control" name="title" required>
                <small class="text-danger error-title"></small>
            </div>
              <div class="mb-3">
                <label>Description</label>
                <input type="text" class="form-control" name="description" required>
                <small class="text-danger error-description"></small>
            </div>

            <div class="mb-3">
                <label>Plan Type</label>
                <select name="type" class="form-control" required>
                    <option value="standard">Standard</option>
                    <option value="premium">Premium</option>
                </select>
                <small class="text-danger error-type"></small>
            </div>

            <div class="mb-3">
                <label>Duration</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="duration" min="1" required>
                    <select name="duration_type" class="form-select" required>
                        <option value="month">Month(s)</option>
                        <option value="year">Year(s)</option>
                    </select>
                </div>
                <small class="text-danger error-duration"></small>
                <small class="text-danger error-duration_type"></small>
            </div>

            <div class="mb-3">
                <label>Price (INR)</label>
                <input type="number" class="form-control" name="price" required>
                <small class="text-danger error-price"></small>
            </div>

            <div class="mb-3">
                <label>Benefits (comma-separated)</label>
                <input type="text" class="form-control" name="benefits" required>
                <small class="text-danger error-benefits"></small>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">Add Plan</button>
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
        $('#plansTable').DataTable();
    });
    
        $('#planForm').submit(function(e) {
        e.preventDefault();
        $('.text-danger').text('');
        $('#ajax-loader').show();

        const form = $(this);
        const formData = new FormData(this);

        $.ajax({
            url: "{{ url('admin/plans/store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function(result) {
                $('#ajax-loader').hide();

                 if (result.code == 200) {
                        swal(result.message, ' ', 'success');
                        reloadpage();
                    } else {
                        $.each(result.message, function(prefix, val) {
                            $('#' + prefix + '_error2').text(val[0]);
                        });
                        swal(result.message, ' ', 'error');
                    }
                $('#basicModal').modal('hide');
                form[0].reset();
                setTimeout(() => {
                    location.reload(); // or you can refresh just the table via AJAX
                }, 1600);
            },

            error: function(xhr) {
                $('#ajax-loader').hide();
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, val) {
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

</script>
@endsection
