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
            <h4 class="mb-sm-0">Sub Category List</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Sub Category Master</a></li>
                    <li class="breadcrumb-item active"> Sub Categorys list </li>
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
                        <h5 class="card-title mb-0">Sub Category List</h5>
                    </div>

                    <div class="col-sm-auto ms-auto">
                        <div class="list-grid-nav gap-1">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#basicModal" class="btn btn-success btn-sm"><i class="ri-add-fill me-1 align-bottom"></i> Add Sub Category</a>
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
                            <th>Location</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $value)
                        <tr>
                            <td>{{$loop->index+1}} </td>
                            <td>{{$value->sub_cat_name}}</td>
                            <td>{{$value->category_name}}</td>

                            <td>
                                @if ($value->flag == 1)
                                <button class="btn btn-sm btn-success" data-bs-target="#deletepopup" data-bs-toggle="modal" onclick="deletepopup('{{ $value->id }}','subcategory','activate')"><i class="ri-checkbox-circle-line"></i></button>
                                @else
                                <button class="btn btn-sm btn-danger" data-bs-target="#deletepopup" data-bs-toggle="modal" onclick="deletepopup('{{ $value->id }}','subcategory','deactivate')"><i class="ri-alert-line"></i></button>
                                @endif
                                &nbsp;&nbsp;

                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-fill align-middle"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item edit-item-btn" data-bs-toggle="modal" data-bs-target="#editmodal" onclick="showeditmodulediv('{{$value->id}}')"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                        @if($value->flag == 1)
                                        <li><a class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#deletepopup" data-bs-toggle="modal" onclick="deletepopup('{{ $value->id }}','subcategory','activate')" aria-controls="editmodal"><i class="ri-checkbox-circle-line me-2"></i></i>Active</a></li>
                                        @else
                                        <li><a class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deletepopup" data-bs-toggle="modal" onclick="deletepopup('{{ $value->id }}','subcategory','deactivate')" aria-controls="editmodal"><i class="ri-alert-line me-2 align-middle"></i>Deactive</a></li>
                                        @endif
                                        <li>
                                            <a class="dropdown-item" data-bs-target="#deletepopup" data-bs-toggle="modal" onclick="deletepopup('{{ $value->id }}','subcategory','delete')" href="javascript:void(0);">
                                                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        @endforeach()
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end col-->
</div>
<!--end row-->


<!-- add modal -->
<!-- Modal -->
<div class="modal fade" id="basicModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Sub Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <form class="form-valide-with-icon needs-validation" action="{{url('admin/sub-category/store')}}" id="categoryadd" name="categoryform" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3 mt-3">
                        <div class="input-group">
                            <label class="text-label form-label" for="validationCustomUsername"> Select Category</label>
                            <div class="input-group">
                                <select class="form-control mb-3" name="location">
                                    <option value="">Select Category</option>
                                    @foreach($ocategories as $ocat)
                                    <option value="{{$ocat->id}}">{{$ocat->category_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="location_error" class="text-danger error"> </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-label form-label" for="validationCustomUsername"> Sub Category Name</label>
                        <div class="input-group">
                            <span class="input-group-text"> <i class="ri-hospital-line"></i> </span>
                            <input type="text" class="form-control" id="hotelcat" name="name" placeholder=" Enter  Category">
                        </div>
                        <div id="name_error" class="text-danger error"> </div>
                    </div>




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <input type="submit" id="addcate" class="btn btn-success" value="Add">
                </div>
            </form>

        </div>
    </div>
</div>


<div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header p-3">
                <h5 class="modal-title" id="myModalLabel">Edit Sub Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="editblogdetails">
                    @csrf()
                    <div id="editstaffdiv"></div>

                    <div class="row">
                        <div class="col-lg-12">
                            <span class="hstackloader"></span>
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Edit Sub Category</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


@include('admin.deactivate')

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
    function showeditmodulediv(id) {
        $.ajax({
            url: "{{url('admin/sub-category/edit')}}" + '/' + id,
            type: 'GET',
            cache: true,
            contentType: false,
            processData: false,
            success: function(result) {
                $("#editstaffdiv").html(result);
            },
        })
    }


    $(document).on('submit', '#editblogdetails', function(ev) {
        $('.error').html('');

        ev.preventDefault(); // Prevent browers default submit.
        var formData = new FormData(this);
        var error = false;

        if (error == false) {
            $.ajax({
                url: "{{ url('admin/sub-category/update') }} ",
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
                    } else {
                        $.each(result.message, function(prefix, val) {
                            $('#' + prefix + '_error2').text(val[0]);
                        });
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