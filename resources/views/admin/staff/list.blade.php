@extends('admin.layout')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">



<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Nurses List</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Nurses List</a></li>
                    <li class="breadcrumb-item active">All Nurses List</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row g-2">
                    <div class="col-sm-4">
                        <h5 class="card-title mb-0">Nurses List</h5>
                    </div>

                    <div class="col-sm-auto ms-auto">
                        <div class="list-grid-nav gap-1">
                            <a href="{{ url('admin/staff/add')}}" class="btn btn-success btn-sm"><i class="ri-add-fill me-1 align-bottom"></i> Add Nurse</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Name</th>
                            <th>Mobile No.</th>
                            <th>Email</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php if(!empty($staff)) { $i =0; foreach($staff as $value) { @endphp
                        <tr>
                            <td>{{ ++$i }} </td>
                            <td><a href="{{ url('admin/staff/profile/'.$value->id)}}">{{$value->name}}</a></td>
                            <td>{{$value->phone}}</td>
                            <td>{{$value->email}}</td>
                            <td> @if(!empty($value->image)) <img src="{{asset('uploads/staff/image/'.$value->image)}}" style='width:80px'> @endif {{ $value->unique_id }} </td>
                            <td>
                                <button class="btn btn-sm btn-success" data-bs-target="#change_password" data-bs-toggle="modal" onclick="showchangepasswordpopup('{{ $value->id }}', 'user_table')"><i class="ri-key-fill"></i></button>

                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-fill align-middle"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item edit-item-btn" href="{{ url('admin/staff/edit/'.$value->id)}}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>

                                        @if($value->flag == 1)
                                        <li><a class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#deletepopup" data-bs-toggle="modal" onclick="deletepopup('{{ $value->id }}','user_table','activate')" aria-controls="editmodal"><i class="ri-checkbox-circle-line me-2"></i></i>Active</a></li>
                                        @else
                                        <li><a class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deletepopup" data-bs-toggle="modal" onclick="deletepopup('{{ $value->id }}','user_table','deactivate')" aria-controls="editmodal"><i class="ri-alert-line me-2 align-middle"></i>Deactive</a></li>
                                        @endif

                                        <li><a class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deletepopup" data-bs-toggle="modal" onclick="deletepopup('{{ $value->id }}','user_table','delete')" aria-controls="editmodal"><i class="ri-delete-bin-fill align-bottom me-2"></i> Delete </a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        @php } } @endphp

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end col-->
</div>
<!--end row-->


@include('admin.deactivate')

<div class="modal fade zoomIn" id="change_password" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-soft-info p-3">
                <h5 class="modal-title" id="myModalLabel">Update Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn-close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="changepassworddata">
                    <span id="changepassworddiv"></span>
                    @csrf()

                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn w-sm btn-success " id="delete-record">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="{{ asset('admin/js/pages/datatables.init.js') }}"></script>


<script>
    function showchangepasswordpopup(id, table) {
        $.ajax({
            url: "{{ url('admin/staff/changepassworddiv')}}" + "/" + id + '/' + table,
            type: 'get',
            cache: true,
            contentType: false,
            processData: false,
            success: function(result) {
                $("#changepassworddiv").html(result);
            },
        })
    }


    $(document).on('submit', '#changepassworddata', function(ev) {
        $('.error').html('');

        ev.preventDefault(); // Prevent browers default submit.
        var formData = new FormData(this);
        var error = false;

        if (error == false) {
            $.ajax({
                url: "{{ url('admin/customer/updatepassword') }} ",
                type: 'post',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(".hstackloader").html('<lord-icon src="https://cdn.lordicon.com/dpinvufc.json" trigger="loop" colors="primary:#4bb543,secondary:#4bb543" style="width:50px;"> </lord-icon>');
                    $(".hstack").css('display', 'none');
                    $(".error").text('');
                },
                success: function(result) {
                    if (result.code == 200) {
                        swal(result.message, ' ', 'success');
                        reloadpage();
                    } else if (result.code == 401) {
                        $.each(result.message, function(prefix, val) {
                            $('#' + prefix + '_error').text(val[0]);
                        });
                        swal(result.message, ' ', 'error');
                    } else {
                        swal(result.message, ' ', 'error');
                    }
                },
                error: function(xhr) {
                    $(".hstack").css('display', 'flex');
                },
                complete: function() {
                    $(".hstack").css('display', 'flex');
                    $(".hstackloader").text('');
                },
            })
        }
    })
</script>

@endsection()