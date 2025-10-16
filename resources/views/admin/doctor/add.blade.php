@extends('admin.layout')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">



<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Add Doctor</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Doctor</a></li>
                    <li class="breadcrumb-item active">Add Doctor</li>
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
                <h4 class="card-title mb-0">Add Doctor</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <form id="addstaff" method="post">
                    @csrf()
                    <div class="accordion custom-accordionwithicon" id="accordionWithicon">
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="accordionwithiconExample1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_iconExamplecollapse1" aria-expanded="true" aria-controls="accor_iconExamplecollapse1">
                                    Login Information
                                </button>
                            </h2>
                            <div id="accor_iconExamplecollapse1" class="accordion-collapse collapse show" aria-labelledby="accordionwithiconExample1" data-bs-parent="#accordionWithicon">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-4 mt-3">
                                            <label class="control-label" for="validationCustom02">Email</label>
                                            <input type="text" class="form-control" id="validationCustom02" name="email" placeholder="Email" required>
                                            <div class="text-danger error" id="email_error"></div>

                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label class="control-label" for="validationCustom02">Password</label>
                                            <input type="password" class="form-control" id="validationCustom02" name="password" placeholder="Password" required>
                                            <div class="text-danger error" id="password_error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="accordionwithiconExample1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_iconExamplecollapse1" aria-expanded="true" aria-controls="accor_iconExamplecollapse1">
                                    Personal Information
                                </button>
                            </h2>
                            <div id="accor_iconExamplecollapse1" class="accordion-collapse collapse show" aria-labelledby="accordionwithiconExample1" data-bs-parent="#accordionWithicon">
                                <div class="accordion-body">
                                    <div class="row">

                                        <div class="col-md-4 mt-3">
                                            <input type="hidden" name="type" value="add-doctor">
                                            <label class="control-label" for="validationCustom02">Name</label>
                                            <input type="text" class="form-control" id="validationCustom02" name="name" placeholder="Name" required>
                                            <div class="text-danger error" id="name_error"></div>

                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label class="control-label" for="validationCustom02">Phone</label>
                                            <input class="form-control" type="number" name="phone" placeholder="Phone" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 10) ? null : event.charCode >= 48 && event.charCode <= 57" maxlength="10" minlength="10" required>
                                            <div class="text-danger error" id="phone_error"></div>

                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label class="control-label" for="validationCustom02">Gender</label>
                                            <select class="form-control" name="gender" required>
                                                <option selected disabled>Select</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label class="control-label" for="validationCustom02">D.O.B</label>
                                            <input type="date" class="form-control" id="validationCustom02" name="dob" required>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label class="control-label" for="validationCustom02">Website</label>
                                            <input type="text" class="form-control" id="validationCustom02" name="website" placeholder="Website">
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label class="control-label" for="validationCustom02">Personal chamber (If any)</label>
                                            <input type="text" class="form-control" id="validationCustom02" name="personal_chamber" placeholder="Personal chamber (If any)">
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label class="control-label" for="validationCustom02">Image <span style="font-size:10px">(Image should be less than 1Mb & size -51mm X 51mm)</span></label>
                                            <input type="file" class="form-control" id="validationCustom02" name="image" accept="image/*">
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label class="control-label" for="validationCustom02">Signature <span style="font-size:10px">(Image should be less than 1Mb & size -51mm X 51mm)</span></label>
                                            <input type="file" class="form-control" id="validationCustom02" name="signature" accept="image/*" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion custom-accordionwithicon" id="accordionWithicon">
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="accordionwithiconExample1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_iconExamplecollapse1" aria-expanded="true" aria-controls="accor_iconExamplecollapse1">
                                        Educational Information
                                    </button>
                                </h2>
                                <div id="accor_iconExamplecollapse1" class="accordion-collapse collapse show" aria-labelledby="accordionwithiconExample1" data-bs-parent="#accordionWithicon">
                                    <div class="accordion-body">
                                        <div class="row">

                                            <div class="col-md-4 mt-3">
                                                <label class="control-label" for="validationCustom02">Degree</label>
                                                <input type="text" class="form-control" id="validationCustom02" name="degree" placeholder="Degree" required>
                                            </div>
                                            <div class="col-md-4 mt-3">
                                                <label class="control-label" for="validationCustom02">Respective college/ University</label>
                                                <input type="text" class="form-control" id="validationCustom02" name="college" placeholder="Respective college/ University">
                                            </div>
                                            <div class="col-md-4 mt-3">
                                                <label class="control-label" for="validationCustom02"> Year of completion </label>
                                                <input type="text" class="form-control" id="validationCustom02" name="completion_year" placeholder=" Year of completion">
                                            </div>
                                            <div class="col-md-4 mt-3">
                                                <label class="control-label" for="validationCustom02"> Registration Number </label>
                                                <input type="text" class="form-control" id="validationCustom02" name="registration_no" placeholder="Registration Number" required>
                                            </div>
                                            <div class="col-md-4 mt-3">
                                                <label class="control-label" for="validationCustom02">Document</label>
                                                <input type="file" class="form-control" id="validationCustom02" name="document" accept="image/*">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion custom-accordionwithicon" id="accordionWithicon">
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="accordionwithiconExample1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_iconExamplecollapse1" aria-expanded="true" aria-controls="accor_iconExamplecollapse1">
                                        Professional Information
                                    </button>
                                </h2>
                                <div id="accor_iconExamplecollapse1" class="accordion-collapse collapse show" aria-labelledby="accordionwithiconExample1" data-bs-parent="#accordionWithicon">
                                    <div class="accordion-body">
                                        <div class="row">

                                            <div class="col-md-6 mt-3">
                                                <label class="control-label" for="validationCustom02">Specialization</label>
                                                <select class="form-control" name="specialization" required>
                                                    <option value="" disabled selected>Select</option>
                                                    @if(!empty($category))
                                                    @foreach($category as $value)
                                                    <option value='{{ $value->id }}'>{{$value->category_name}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="control-label" for="validationCustom02">Experience in year</label>
                                                <input type="text" class="form-control" id="validationCustom02" name="experience_year" placeholder="Experience in year">
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="control-label" for="validationCustom02">Achievement</label>
                                                <textarea type="text" class="form-control" id="validationCustom02" name="achievement" placeholder="Achievement"></textarea>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="control-label" for="validationCustom02"> Experience in brief</label>
                                                <textarea type="text" class="form-control" id="validationCustom02" name="experience_brief" placeholder="Experience  in brief"></textarea>
                                            </div>

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
                url: "{{ url('admin/doctors/adddoctor') }}",
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