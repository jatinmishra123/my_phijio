@extends('admin.layout')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Add New Appointment</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Appointment Master</a></li>
                    <li class="breadcrumb-item active">Add New Appointment</li>
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
                <h4 class="card-title mb-0">Add Appointment</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <form id="addstaff" method="post">
                    @csrf()
                    <div class="row">

                        <div class="col-md-6">
                            <div class="mt-3 container1">

                                <label class="control-label" for="validationCustom02">Patient Name</label>
                                <input type="text" id="search"   placeholder="Patient Name" class="form-control typeahead" />
                                <input type="hidden" name="user_id" id="user_id">
                                <!-- <select class="form-control" onchange="getinfo(this.value)" name="user_id" required>
                                    <option value="">Select</option>
                                    @foreach($users as $list)
                                    <option value="{{$list->id}}">{{$list->first_name}}{{$list->middle_name}}{{$list->last_name}}</option>
                                    @endforeach()
                                </select> -->
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="control-label" for="validationCustom02">Gender</label>
                            <input type='text' class='form-control' id='showgender' disabled placeholder='Gender' required>

                        </div>
                        <div class="col-md-6 mt-3">
                            <label class='control-label' for='validationCustom02'>Date of Birth</label>
                            <input type='date' class='form-control' id='dob' disabled required>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="control-label" for="validationCustom02"> Phone Number</label>
                            <input class="form-control" type="number" id='mobile' placeholder="Phone Number" disabled required>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="control-label" for="validationCustom02">Age</label>
                            <input type='text' class='form-control' id='age' name='age' placeholder='Age' required >
                            <span>(as on 31st March, {{date('Y')+1}})</span>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="control-label" for="validationCustom02"> Appointment Date</label>
                            <input type="date" class="form-control" id="validationCustom02" name="appointment_date" placeholder="Appointment Date" required>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="control-label" for="validationCustom02"> Appointment Time</label>
                            <input type="time" class="form-control" id="validationCustom02" name="appointment_time" placeholder="Appointment Date" required>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="control-label" for="validationCustom02">Centers</label>
                            <!-- <input type="text" id="chamber"   placeholder="Clinic Name" class="form-control" />
                            <input type="hidden" name="clinic_id" id="clinic_id" /> -->

                            <select class="form-control" onchange="GetAllocatedDoctor(this.value)" name="clinic_id" id="clinic" required>
                                <option selected disabled>Select</option>
                                @if(!empty($chamber))
                                @foreach($chamber as $value)
                                <option value='{{$value->id}}'>{{$value->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="control-label" for="validationCustom02">Doctor</label>
                            <!-- <input type="text" id="doctors" placeholder="Doctors" class="form-control typeahead" />
                            <input type="hidden" name="doctor_id" id="doctor_id"> -->

                            <select class="form-control" name="doctor_id" onchange="getdoctorinfo(this.value)" id="clinicdoctors" required>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="control-label" for="validationCustom02">Payment</label>
                            <select class="form-control" name="payment_mode" required>
                                <option value="">Select</option>
                                <option value="Walk In">Walk In</option>
                                <option value="Online">Online</option>
                            </select>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="control-label" for="validationCustom02">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" placeholder="Amount" required>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="hstackloader"></div>
                            <div class="hstack gap-2 justify-content-end">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </div>

                </form>
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

<script type="text/javascript">


    var route = "{{ url('autocomplete-search') }}";
    $('#search').typeahead({
        source: function(query, process) {
            return $.get(route, {
                query: query
            }, function(data) {
                return process(data);
            });
        },
        afterSelect: function (item) {
            $.ajax({
            url: "{{ url('admin/appointment/userinfo') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                username: item
            },
            success: function(result) {
                $('#showgender').val(result.gender);
                $('#dob').val(result.dob);
                $('#mobile').val(result.mobile);
                $('#age').val(result.age);
                $('#user_id').val(result.id);

            }

        })
    }
    });

    var route1 = "{{ url('autocomplete-chamber') }}";
    $('#chamber').typeahead({
        source: function(query, process) {
            return $.get(route1, {
                query: query
            }, function(data) {
                return process(data);
            });
        },
        afterSelect: function (item) {
            $.ajax({
            url: "{{ url('admin/appointment/chamberinfo') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                chambername: item
            },
            success: function(result) {
                // $('#showgender').val(result.gender);
                // $('#dob').val(result.dob);
                // $('#mobile').val(result.mobile);
                // $('#age').val(result.age);
                $('#clinic_id').val(result.chamberid);

            }

        })
    }
    });

    // get only specific doctor
    var route2 = "{{ url('autocomplete-doctor') }}";
    $('#doctors').typeahead({
        source: function(query, process) {
            return $.get(route2, {
                query: query,
                id : $('#clinic_id').val()
            }, function(data) {
                return process(data);
            });
        },
        afterSelect: function (item) {
            $.ajax({
            url: "{{ url('admin/appointment/doctorinfo') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                doctorname: item
            },
            success: function(result) {
                $('#doctor_id').val(result.id);
                $('#amount').val(result.fee);
               

            }

        })
    }
    });
</script>

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
    //  add modal
    $(document).on('submit', '#addstaff', function(ev) {
        $('.error').html('');

        ev.preventDefault(); // Prevent browers default submit.
        var formData = new FormData(this);
        var error = false;

        if (error == false) {
            $.ajax({
                url: "{{ url('admin/appointment/store') }}",
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

    function GetAllocatedDoctor(value) {
        $.ajax({
            url: "{{ url('admin/appointment/alloteddoctor') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                chamberid: value
            },
            success: function(result) {
                $('#clinicdoctors').html(result.data);

            }

        })
    }
</script>


@endsection()