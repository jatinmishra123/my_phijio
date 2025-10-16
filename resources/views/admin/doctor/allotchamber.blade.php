@extends('admin.layout')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">



<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">View Availibility</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">View Availibility</a></li>
                    <li class="breadcrumb-item active">View Availibility</li>
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
                        <h5 class="card-title mb-0">View Availibility</h5>
                    </div>

                    {{-- <div class="col-sm-auto ms-auto">
                        <div class="list-grid-nav gap-1">
                            <a href="{{ url('admin/doctors/add')}}" class="btn btn-success btn-sm"><i class="ri-add-fill me-1 align-bottom"></i> Add Doctor</a>
                        </div>
                    </div> --}}
                </div>
            </div>
           <div class="card-body">
    <div class="table-responsive">
        <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle w-100">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>IsBooked</th>
                    <th>Booked Date</th>
                    <th style="min-width: 250px;">Address of Patient / User</th>
                    <th>Is Service Delivered</th>
                    <th>Service Delivered Date</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @php if(!empty($timeslots)) { $i = 0; foreach($timeslots as $value) { @endphp
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $value->date ?? '' }}</td>
                    <td>{{ $value->start_time }}</td>
                    <td>{{ $value->end_time }}</td>
                    <td>
                        @php
                            $bgcolor = $value->isBooked ? "success" : "info";
                            $message = $value->isBooked ? "Booked" : "Not Booked Yet";
                        @endphp
                        <span class="badge bg-{{ $bgcolor }}">{{ $message }}</span>
                    </td>
                    <td>{{ $value->booked_date ?? '' }}</td>
                    <td>
                        @if(!empty($value->address_id))
                            <span style="word-wrap: break-word;">
                                {{ $value->type ?? '' }} | {{ $value->state ?? '' }} {{ $value->city ?? '' }} <br>
                                {{ $value->full_address ?? '' }} {{ $value->zip_code ?? '' }}
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($value->isBooked)
                            @php
                                $bgcolor = $value->iscompleted ? "success" : "info";
                                $message = $value->iscompleted ? "Service Delivered" : "Service Not Delivered";
                            @endphp
                            <span class="badge bg-{{ $bgcolor }}">{{ $message }}</span>
                        @endif
                    </td>
                    <td>{{ $value->completed_at ?? '' }}</td>
                    <td>{{ $value->created_at }}</td>
                </tr>
                @php } } @endphp
            </tbody>
        </table>
    </div>
</div>

        </div>
    </div>
    <!--end col-->
</div>
</div>
<!-- container-fluid -->
</div>
<!-- End Page-content -->


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
    //  add modal
    $(document).on('submit', '#addstaff', function(ev) {
        $('.error').html('');

        ev.preventDefault(); // Prevent browers default submit.
        var formData = new FormData(this);
        var error = false;

        if (error == false) {
            $.ajax({
                url: "{{ url('admin/doctors/updatechamber') }}",
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