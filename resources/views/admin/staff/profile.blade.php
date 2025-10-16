@extends('admin.layout')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">



<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Staff Details</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Staff</a></li>
                    <li class="breadcrumb-item active">Staff Details</li>
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
                <h4 class="card-title mb-0">Staff - {{ $user_table->name ?? ''}}</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="row">
                    <div class="col-5 text-center">
                        @if(!empty($user_table->image))
                        <img src="{{asset('uploads/doctor/image/'.$user_table->image)}}" style="width:100%; height: 380px; object-fit: contain; object-position: top" alt="Doctor Image">
                        @else
                        <img src="{{asset('admin/images/users/avatar-1.jpg')}}" style="width:100%; height: 380px; object-fit: contain; object-position: top" alt="Doctor Image">
                        @endif
                    </div>
                    <div class="col-7">
                        <table class="table table-bordered mt-3" style="border: 1px solid #80808038;">
                            <tr>
                                <th>Name</th>
                                <td>{{ $user_table->name ?? ''}}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $user_table->phone ?? ''}}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user_table->email ?? ''}}</td>
                            </tr>
                        </table>
                        <a href="{{url('admin/staff/edit/'.$user_table->id)}}" style="
    float: right;
" class="btn btn-primary  ">Edit</a>
                    </div>
                    
                </div>
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end col -->
</div>
<!-- end row -->

</div>
<!-- container-fluid -->
</div>
<!-- End Page-content -->

@endsection()