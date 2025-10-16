@extends('admin.layout')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">



<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Center/Clinic List</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Center Master</a></li>
                    <li class="breadcrumb-item active">Center/Clinic List</li>
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
                        <h5 class="card-title mb-0">Center List</h5>
                    </div>

                    <div class="col-sm-auto ms-auto">
                        <div class="list-grid-nav gap-1">
                            <a href="{{ url('admin/hospital/add')}}" class="btn btn-success btn-sm"><i class="ri-add-fill me-1 align-bottom"></i> Add Center</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th data-ordering="false">Unique Id</th>
                            <th data-ordering="false">Center Name</th>
                            <th data-ordering="false">Location</th>
                            <th data-ordering="false">Address</th>
                            <th data-ordering="false">Mobile</th>
                            <th>Doctor List</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php if(!empty($hospitals)) { $i =0; foreach($hospitals as $value) { @endphp
                        <tr>
                            <td>{{ ++$i }} </td>
                            <td> @if(!empty($value->image)) <img src="{{asset('uploads/chamber/'.$value->image)}}" style='width:80px'> @endif {{ $value->unique_id }} </td>
                            <td>{{ $value->name }} </td>
                            <td>{{ $value->location_name }} </td>
                            <td>{{ $value->address }} </td>
                            <td>{{ $value->mobile }} </td>
                            <td> </td>
                            <td>
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-fill align-middle"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item edit-item-btn" href="{{ url('admin/hospital/edit/'.$value->id)}}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>

                                        @if($value->flag == 1)
                                        <li><a class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#deletepopup" data-bs-toggle="modal" onclick="deletepopup('{{ $value->id }}','chamber','activate')" aria-controls="editmodal"><i class="ri-checkbox-circle-line me-2"></i></i>Active</a></li>
                                        @else
                                        <li><a class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deletepopup" data-bs-toggle="modal" onclick="deletepopup('{{ $value->id }}','chamber','deactivate')" aria-controls="editmodal"><i class="ri-alert-line me-2 align-middle"></i>Deactive</a></li>
                                        @endif

                                        <li><a class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deletepopup" data-bs-toggle="modal" onclick="deletepopup('{{ $value->id }}','chamber','delete')" aria-controls="editmodal"><i class="ri-delete-bin-fill align-bottom me-2"></i> Delete </a></li>
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

@endsection()