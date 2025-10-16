@extends('admin.layout')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">



<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Register New Patient</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Registeration</a></li>
                    <li class="breadcrumb-item active">Register New Patient</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->


<div class="card">
    <div class="row">
        <div class="col-md-3">
            <div id="list-example" class="list-group">
                <a class="list-group-item list-group-item-action" href="#demographics">Demographic Details</a>
                <a class="list-group-item list-group-item-action active" href="#address">Address Details</a>
                <a class="list-group-item list-group-item-action" href="#payer_selection">Payer Selection</a>
                <a class="list-group-item list-group-item-action" href="#payer">Payer Details</a>
                <a class="list-group-item list-group-item-action" href="#kin">Kin Details</a>
                <a class="list-group-item list-group-item-action" href="#other">Other Details</a>
                <a class="list-group-item list-group-item-action" href="#remarks">Remarks</a>
            </div>
        </div>
        <div class="col-md-9" style="padding: 5px 20px 0px 5px;">
            <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-offset="0" class="scrollspy-example mt-0">
                <div class="text-muted">
                    <form method="post" action={{route('userregisteration','admin')}} enctype="multipart/form-data">
                        @csrf()

                        <div id="demographics" class="mb-3">
                            <div class="accordion custom-accordionwithicon" id="accordionWithicon">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="accordionwithiconExample1">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_iconExamplecollapse1" aria-expanded="true" aria-controls="accor_iconExamplecollapse1">
                                            <i class="ri-global-line"></i> Demographics Details
                                        </button>
                                    </h2>
                                    <div id="accor_iconExamplecollapse1" class="accordion-collapse collapse show" aria-labelledby="accordionwithiconExample1" data-bs-parent="#accordionWithicon">
                                        <div class="accordion-body">

                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label>Title</label>
                                                    <select class="form-control" name="title">
                                                        <option>Mr.</option>
                                                        <option>Mrs.</option>
                                                        <option>Miss.</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>First Name</label>
                                                    <input type="text" class="form-control" name="first_name" placeholder="Enter First Name" required>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Middle Name</label>
                                                    <input type="text" class="form-control" name="middle_name" placeholder="Enter Middle Name">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Last Name</label>
                                                    <input type="text" class="form-control" name="last_name" placeholder="Enter Last Name">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Mobile Number</label>
                                                    <input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type="number" maxlength="10" class="form-control" name="mobile" placeholder="Enter Mobile Number" required>
                                                </div>

                                                <!--<div class="col-md-3 mb-3 m-auto">-->
                                                <!--    <label>VIP</label></br>-->
                                                <!--    <input type="checkbox" name="vip_or_not" style="zoom: 1.5;">-->
                                                <!--</div>-->

                                                <div class="col-md-3 mb-3">
                                                    <label>Date of Birth</label>
                                                    <input type="date" class="form-control" onchange="calculateage(this.value)" name="dob" placeholder="Enter Date of Birth" required>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label>Age</label>
                                                    <input type="text" class="form-control" id="ageval" name="age" placeholder="Enter Age" disabled>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Gender</label>
                                                    <select class="form-control" name="gender" required>
                                                        <option selected disabled value>Select Gender</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Marital Status</label>
                                                    <select class="form-control" name="marital_status" required>
                                                        <option value selected disabled>Marital Status</option>
                                                        <option value="Married">Married</option>
                                                        <option value="Unmarried">Unmarried</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Mother's Name</label>
                                                    <input type="text" class="form-control" name="mother_name" required placeholder="Enter Mother's Name">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Father's Name</label>
                                                    <input type="text" class="form-control" name="father_name" placeholder="Enter Father's Name" required>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>E-mail Address</label>
                                                    <input type="text" class="form-control" name="email" placeholder="Enter E-mail Address" required>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Alternate Number</label>
                                                    <input type="text" class="form-control" name="alternate_number" placeholder="Enter Alternate Number" required>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Blood Group</label>
                                                    <input type="text" class="form-control" name="blood_group" placeholder="Enter Blood Group" required>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Profile Picture</label>
                                                    <input type="file" class="form-control" name="image">
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="address" class="mb-3">
                            <div class="accordion custom-accordionwithicon" id="accordionWithicon">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="accordionwithiconExample1">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_iconExamplecollapse1" aria-expanded="true" aria-controls="accor_iconExamplecollapse1">
                                            <i class="ri-global-line"></i> Address Details
                                        </button>
                                    </h2>
                                    <div id="accor_iconExamplecollapse1" class="accordion-collapse collapse show" aria-labelledby="accordionwithiconExample1" data-bs-parent="#accordionWithicon">
                                        <div class="accordion-body">

                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label>Search Address</label>
                                                    <input type="text" class="form-control" name="house" placeholder="Enter Address">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>House No./Flat No.</label>
                                                    <input type="text" class="form-control" name="house" placeholder="Enter House No./Flat No.">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Street</label>
                                                    <input type="text" class="form-control" name="street" placeholder="Enter Street">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Area</label>
                                                    <input type="text" class="form-control" name="area" placeholder="Enter Area">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Country</label>
                                                    <input type="text" class="form-control" name="country" placeholder="Enter Country" value="India">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>State</label>
                                                    <select class="form-control" name="state">
                                                        <option selected disabled value>Select</option>
                                                        @if(!empty($states))
                                                        @foreach($states as $value)
                                                        <option value="{{ $value->state_name }}">{{ $value->state_name }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>City</label>
                                                    <select class="form-control" name="city">
                                                        <option selected disabled value>Select</option>
                                                        @if(!empty($city))
                                                        @foreach($city as $value)
                                                        <option value="{{ $value->city_name }}">{{ $value->city_name }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>PinCode</label>
                                                    <input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type="number" maxlength="6" class="form-control" name="pincode" placeholder="Enter PinCode">
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Accordions with Icons -->

                        </div>

                        <div id="payer_selection" class="mb-3">
                            <div class="accordion custom-accordionwithicon" id="accordionWithicon">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="accordionwithiconExample1">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_iconExamplecollapse1" aria-expanded="true" aria-controls="accor_iconExamplecollapse1">
                                            <i class="ri-global-line"></i> Payer Selection
                                        </button>
                                    </h2>
                                    <div id="accor_iconExamplecollapse1" class="accordion-collapse collapse show" aria-labelledby="accordionwithiconExample1" data-bs-parent="#accordionWithicon">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label>Payer Selection Type</label>
                                                    <select class="form-control" name="payer_type">
                                                        <option selected disabled value>Select</option>
                                                        <option value="Cash">Cash</option>
                                                        <option value="EMI">EMI</option>
                                                        <option value="Insurance">Insurance</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Room Category</label>
                                                    <input type="text" class="form-control" name="room_category">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Room Rent</label>
                                                    <input type="text" class="form-control" name="room_rent">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>CoPay (%)</label>
                                                    <input type="number" class="form-control" name="co_pay" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div id="kin" class="mb-3">
                            <div class="accordion custom-accordionwithicon" id="accordionWithicon">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="accordionwithiconExample1">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_iconExamplecollapse1" aria-expanded="true" aria-controls="accor_iconExamplecollapse1">
                                            <i class="ri-global-line"></i> Kin Details
                                        </button>
                                    </h2>
                                    <div id="accor_iconExamplecollapse1" class="accordion-collapse collapse show" aria-labelledby="accordionwithiconExample1" data-bs-parent="#accordionWithicon">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label>Name</label>
                                                    <input type="text" class="form-control" name="kin_name">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Number</label>
                                                    <input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type="number" maxlength="10" class="form-control" name="kin_number">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Relationship</label>
                                                    <input type="text" class="form-control" name="kin_relationship">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="remarks" class="mb-3">
                            <div class="accordion custom-accordionwithicon" id="accordionWithicon">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="accordionwithiconExample1">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_iconExamplecollapse1" aria-expanded="true" aria-controls="accor_iconExamplecollapse1">
                                            <i class="ri-global-line"></i> Remarks
                                        </button>
                                    </h2>
                                    <br>

                                    <div class="row p-3">


                                        <div class="col-md-6 mb-3">
                                            <label>Insurance</label>
                                            <input type="file" class="form-control" name="insurance">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Investigation</label>
                                            <input type="file" class="form-control" name="investigation">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label>Aadhar card</label>
                                            <input type="file" class="form-control" name="adharcard">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label>Pan Card</label>
                                            <input type="file" class="form-control" name="pancard">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label>Other</label>
                                            <input type="file" class="form-control" name="others">
                                        </div>

                                    </div>


                                    <div id="accor_iconExamplecollapse1" class="accordion-collapse collapse show" aria-labelledby="accordionwithiconExample1" data-bs-parent="#accordionWithicon">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label>Remarks</label>
                                                    <textarea class="form-control" name="remarks" rows="3" cols="20"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" value="submit" class="btn btn-primary">
                            <!-- Accordions with Icons -->

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": false,
        "duration": 1000,
        "showEasing": "swing",
        "positionClass": "toast-top-right",
    }
</script>

@if(session()->has('message'))
<script>
    toastr.success("{{session()->get('message')}}");
</script>
@elseif(session()->has('error'))
<script>
    toastr.info("{{session()->get('error')}}");
</script>
@endif()


<script>
    function calculateage(value) {
        if (value != '') {
            var new_birth_date = value.split('-');
            var year = new_birth_date[0];
            var month = new_birth_date[1];
            var day = new_birth_date[2];



            if (month <= 3) {
                new_year = new Date().getFullYear() - year;
                new_day = 31 - day;
                new_month = 3 - month;
            } else {
                new_month = 15 - month;
                new_year = new Date().getFullYear() - year;
                new_day = 31 - day;
            }
            var age = new_year + " Years ";
            $('#ageval').val(age);

        }

    }
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

<script src="{{ asset('admin/js/pages/datatables.init.js') }}"></script>

@endsection()