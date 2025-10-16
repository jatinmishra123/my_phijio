@extends('admin.layout')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">



<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Edit Center</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Center Master</a></li>
                    <li class="breadcrumb-item active">Edit Center</li>
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
                <h4 class="card-title mb-0">Edit Center</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <form id="editstaff" method="post">
                    @csrf()
                    <input type="hidden" name="rowid" value="{{ $items->id ?? '' }}">

                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <input type="hidden" name="type" value="add-hospital">
                            <label class="control-label" for="validationCustom02"> Center / Clinic Name <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" id="validationCustom02" name="chamber_name" value="{{ $items->name ?? '' }}" placeholder="Please write Center / Clinic Name here....">
                            <span id="chamber_name_error" class="error"></span>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label class="control-label" for="validationCustom02">Contact Number <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="contact_number" value="{{ $items->mobile ?? '' }}" placeholder="Contact Number" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 10) ? null : event.charCode >= 48 && event.charCode <= 57" maxlength="10" minlength="10">
                            <span id="contact_number_error" class="error"></span>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label class="control-label" for="validationCustom02">Location</label>
                            <select class="form-control" name="location">
                                <option selected disabled>Select Location</option>
                                @if(!empty($locations))
                                @foreach($locations as $value)
                                <option value='{{ $value->id }}' @php if(!empty($items->location_id)) { if($items->location_id == $value->id) { echo 'selected'; } } @endphp >{{ $value->location_name }}</option>
                                @endforeach
                                @endif
                            </select>
                            <span id="location_error" class="error"></span>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label class="control-label" for="validationCustom02"> Consultant Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="validationCustom02" value="{{ $items->name ?? '' }}" name="consultant_name" placeholder="Please write Consultant Name here....">
                            <span id="consultant_name_error" class="error"></span>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label class="control-label" for="validationCustom02"> Clinic Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="validationCustom02" value="{{ $items->address ?? '' }}" name="clinic_address" placeholder="Please write Clinic Address here....">
                            <span id="clinic_address_error" class="error"></span>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label class="control-label" for="validationCustom02">Latitude <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="latitute" value="{{ $items->latitute ?? '' }}" name="latitude" placeholder="Please write Latitude here...">
                            <span id="latitude_error" class="error"></span>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label class="control-label" for="validationCustom02">Longitude <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="longitude" value="{{ $items->longitude ?? '' }}" name="longitude" placeholder="Please write Longitude here....">
                            <span id="longitude_error" class="error"></span>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label class="control-label" for="validationCustom02">Image <span style="font-size:10px">(Image should be less than 1Mb & size -51mm X 51mm)</span></label>
                            <input type="file" class="form-control" id="validationCustom02" name="image" accept="image/*">
                            <!-- @if($items->image)
                                <img src="{{ asset('uploads/chamber/'.$items->image) }}" style="width: 200px">
                            @endif -->
                            <span id="image_error" class="error"></span>

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
    getLocation();
    var x = document.getElementById("locationdemo");

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function showPosition(position) {
        $("#latitute").val(position.coords.latitude)
        $("#longitude").val(position.coords.longitude)
    }


    //  add modal
    $(document).on('submit', '#editstaff', function(ev) {
        $('.error').html('');

        ev.preventDefault(); // Prevent browers default submit.
        var formData = new FormData(this);
        var error = false;

        if (error == false) {
            $.ajax({
                url: "{{ url('admin/hospital/update') }}",
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