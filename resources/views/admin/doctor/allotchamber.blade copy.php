@extends('admin.layout')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">



<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Set Availibility</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Set Availibility</a></li>
                    <li class="breadcrumb-item active">Set Availibility</li>
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
                <h4 class="card-title mb-0">Set Availibility</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <form id="addstaff" method="post">
                    @csrf()
                    <div class="accordion custom-accordionwithicon" id="accordionWithicon">
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="accordionwithiconExample1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_iconExamplecollapse1" aria-expanded="true" aria-controls="accor_iconExamplecollapse1">
                                    Set Availibility
                                </button>
                            </h2>
                            <div id="accor_iconExamplecollapse1" class="accordion-collapse collapse show" aria-labelledby="accordionwithiconExample1" data-bs-parent="#accordionWithicon">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="mt-3">
                                                <input type="hidden" name="doctor_id" value="{{Request::segment(4)}}">
                                                <label class="control-label" for="validationCustom02">Doctor Name</label>
                                                <input type="text" class="form-control" name="doctor_name" placeholder="Doctor Name" value="{{$items->name ?? ''}}" readonly>
                                                <span id="doctor_name_error" class="error"></span>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-6 mt-3">
                                            <label class="control-label" for="validationCustom02">Name of sitting chamber</label>
                                            <select class="form-control" name="chamber" required>
                                                <option selected disabled>Select</option>
                                                @if(!empty($chamber))
                                                @foreach($chamber as $value)
                                                <option value='{{$value->id}}' @if(!empty($details->chamberId)) @if($details->chamberId == $value->id) {{'selected'}} @endif @endif >{{$value->name}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <span id="chamber_error" class="error"></span>
                                        </div> -->
                                        <div class="col-md-6 mt-3">
                                            <label class="control-label" for="validationCustom02">Total patient capacity</label>
                                            <input type="text" class="form-control" name="total_patient_capacity" value="{{ $details->patient ?? '' }}" placeholder="Total patient capacity">
                                            <span id="total_patient_capacity_error" class="error"></span>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label class="control-label" for="validationCustom02">Fee</label>
                                            <input type="text" class="form-control" name="fee" placeholder="Fee" value="{{ $details->fee ?? '' }}">
                                            <span id="fee_error" class="error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="accordionwithiconExample1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_iconExamplecollapse1" aria-expanded="true" aria-controls="accor_iconExamplecollapse1">
                                    Schedule
                                </button>
                            </h2>
                            <div id="accor_iconExamplecollapse1" class="accordion-collapse collapse show" aria-labelledby="accordionwithiconExample1" data-bs-parent="#accordionWithicon">
                                <div class="accordion-body">
                                    <div class="row">

                                        <div class="col-md-4 mt-3" style="padding: 0px 26px;">
                                            <label class="control-label" for="validationCustom02">Working Months</label><br>
                                            @php if(!empty($details->working_months)) { $selmonths = explode(",",$details->working_months); } @endphp

                                            @if(!empty($months))
                                            @foreach($months as $value)
                                            <input type="checkbox" name="working_month[]" id="{{ strtolower($value)}}" value="{{ $value}}" @if(!empty($selmonths)) @if(in_array($value,$selmonths)) {{"checked"}} @endif @endif>
                                            <label for="{{ strtolower($value)}}">{{ $value}}</label><br>
                                            @endforeach
                                            @endif
                                        </div>
                                        <div class="col-md-4 mt-3" style="padding: 0px 26px;">
                                            <label class="control-label" for="validationCustom02">Working Week</label><br>
                                            @php if(!empty($details->working_weeks)) $selweeks = explode(",",$details->working_weeks); @endphp

                                            @if(!empty($weeks))
                                            @foreach($weeks as $value)
                                            <input type="checkbox" name="working_week[]" id="{{ str_replace(' ','',strtolower($value)) }}" value="{{ $value}}" @if(!empty($selweeks)) @if(in_array($value,$selweeks)) {{"checked"}} @endif @endif>
                                            <label for="{{ str_replace(' ','',strtolower($value)) }}">{{ $value}}</label><br>
                                            @endforeach
                                            @endif
                                        </div>
                                        <div class="col-md-4 mt-3" style="padding: 0px 26px;">
                                            <label class="control-label" for="validationCustom02">Working Days</label><br>
                                            @php if(!empty($details->working_days)) $seldays = explode(",",$details->working_days); @endphp

                                            @if(!empty($days))
                                            @foreach($days as $value)
                                            <input type="checkbox" name="working_days[]" id="{{ strtolower($value) }}" value="{{ $value}}" @if(!empty($seldays)) @if(in_array($value,$seldays)) {{"checked"}} @endif @endif>
                                            <label for="{{ strtolower($value) }}">{{ $value}}</label><br>
                                            @endforeach
                                            @endif
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label class="control-label" for="validationCustom02"> Morning time Schedule</label>
                                            <input type="text" class="form-control" name="morning_time_schedule" value="{{ $details->morning_schedule ?? '' }}" placeholder="For eg. 10:00 AM - 12:00 PM">
                                            <span id="morning_time_schedule_error" class="error"></span>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label class="control-label" for="validationCustom02">Afternoon time Schedule</label>
                                            <input type="text" class="form-control" name="afternoon_time_schedule" value="{{ $details->afternoon_schedule ?? '' }}" placeholder="For eg. 12:00 AM - 2:00 PM">
                                            <span id="afternoon_time_schedule_error" class="error"></span>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label class="control-label" for="validationCustom02">Evening time Schedule</label>
                                            <input type="text" class="form-control" name="evening_time_schedule" value="{{ $details->evening_schedule ?? '' }}" placeholder="For eg. 5:00 AM - 7:00 PM">
                                            <span id="evening_time_schedule_error" class="error"></span>
                                        </div>
                                    </div>
                                </div>
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