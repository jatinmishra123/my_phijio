@extends('admin.layout')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">



<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">User Details</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">User</a></li>
                    <li class="breadcrumb-item active">User Details</li>
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
                <h4 class="card-title mb-0">Doctor - {{ $user->name ?? ''}}</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="row">
                    <div class="col-5 text-center">
                        @if(!empty($user->image))
                        <img src="{{asset('uploads/doctor/image/'.$user->image)}}" style="width:100%; height: 380px; object-fit: contain; object-position: top" alt="Doctor Image">
                        @else
                        <img src="{{asset('admin/images/users/avatar-1.jpg')}}" style="width:100%; height: 380px; object-fit: contain; object-position: top" alt="Doctor Image">
                        @endif
                    </div>
                    <div class="col-7">
                        <table class="table">
                            <tr>
                                <td>Name</td>
                                <td>{{ $item->title ?? '' }} {{ $item->first_name ?? '' }} {{ $item->middle_name ?? '' }} {{ $item->last_name ?? '' }} </td>
                            </tr>
                            <tr>
                                <td>Mobile Number</td>
                                <td>{{ $item->mobile ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>VIP</td>
                                <td>{{$item->vip_or_not}}</td>
                            </tr>
                            <tr>
                                <td>Date of Birth</td>
                                <td>{{ $item->dob ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>Age</td>
                                <td>{{ $item->age ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>Gender</td>
                                <td>{{ $item->gender ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>Marital Status</td>
                                <td>{{ $item->marital_status ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>Mother's Name</td>
                                <td>{{ $item->mother_name ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>Father's Name</td>
                                <td>{{ $item->father_name ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>E-mail Address</td>
                                <td>{{ $item->email ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>Alternate Number</td>
                                <td>{{ $item->alternate_number ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>Blood Group</td>
                                <td>{{ $item->blood_group ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>Image</td>
                                <td><a href="{{asset('uploads/users/documents/profile/'.$item->image)}}" target="_blank">View</a></td>
                            </tr>
                        </table>

                    </div>
                    
                </div>
                
                
                <div class="row">
                    <div class="col-md-12 mt-3 mb-3">
                        <center><h3><u>Address Details</u></h3></center>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label>Address : </label> {{ $item->house ?? '' }}
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>House No./Flat No. : </label> {{ $item->house ?? '' }}
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Street : </label> {{ $item->street ?? '' }}
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Area : </label> {{ $item->area ?? '' }}
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Country : </label> {{ $item->country ?? '' }}
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>State : </label> {{ $item->state ?? '' }}
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>City : </label> {{ $item->city ?? '' }}
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>PinCode : </label> {{ $item->pincode ?? '' }}
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mt-3 mb-3">
                        <center><h3><u>Payer Selection</u></h3></center>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Payer Selection Type : </label> {{ $item->payer_type ?? '' }}
                    </div>
        
                    <div class="col-md-3 mb-3">
                        <label>Room Category : </label> {{ $item->room_category ?? '' }}
                    </div>
        
                    <div class="col-md-3 mb-3">
                        <label>Room Rent : </label> {{ $item->room_rent ?? '' }}
                    </div>
        
                    <div class="col-md-3 mb-3">
                        <label>CoPay (%) : </label> {{ $item->co_pay ?? '' }}
                    </div>
                </div>
                
                
                <div class="row">
                    <div class="col-md-12 mt-3 mb-3">
                        <center><h3><u>Kin Details</u></h3></center>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Name : </label> {{ $item->kin_name ?? '' }}
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Number : </label> {{ $item->kin_number ?? '' }}
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Relationship : </label> {{ $item->kin_relationship ?? '' }}
                    </div>
                </div>
                
                
                <div class="row">
                    <div class="col-md-12 mt-3 mb-3">
                        <center><h3><u>Remarks</u></h3></center>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Insurance : </label>
                        @if(!empty($items->insurance))
                        <a href="{{asset('uploads/users/documents/insaurance/'.$items->insurance)}}" target="_blank">View</a>
                        @endif
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Investigation : </label>
                        @if(!empty($items->investigation))
                        <a href="{{asset('uploads/users/documents/investigation/'.$items->investigation)}}" target="_blank">View</a>
                        @endif
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Aadhar card : </label>
                        @if(!empty($items->adharcard))
                        <a href="{{asset('uploads/users/documents/adharcards/'.$items->adharcard)}}" target="_blank">View</a>
                        @endif
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Pan Card : </label>
                        @if(!empty($items->pancard))
                        <a href="{{asset('uploads/users/documents/pancards/'.$items->pancard)}}" target="_blank">View</a>
                        @endif
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Other : </label>
                        @if(!empty($items->others))
                        <a href="{{asset('uploads/users/documents/others/'.$items->others)}}" target="_blank">View</a>
                        @endif
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