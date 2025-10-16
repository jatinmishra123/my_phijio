@extends('admin.layout')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Edit Doctor</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="#">Doctor</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Edit Doctor Profile</h4>
            </div>
            <div class="card-body">
                <form id="addstaff" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="rowid" value="{{ $items->id }}">

                    <!-- Personal Information -->
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#personalInfo">
                                Personal Information
                            </button>
                        </h2>
                        <div id="personalInfo" class="accordion-collapse collapse show">
                            <div class="accordion-body row">
                                <div class="col-md-4 mt-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $items->email }}" readonly>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label>Commission (%)</label>
                                    <input type="number" name="commission" class="form-control" value="{{ $items->commission }}">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label>Level</label>
                                    <select name="level" class="form-control">
                                        <option value="">Select</option>
                                        @foreach(['Level 1', 'Level 2', 'Level 3', 'Level 4', 'Level 5'] as $level)
                                            <option value="{{ $level }}" {{ $items->level == $level ? 'selected' : '' }}>{{ $level }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $items->name }}">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $items->phone }}" maxlength="10">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label>Gender</label>
                                    <select name="gender" class="form-control">
                                        <option value="">Select</option>
                                        <option value="Male" {{ $doctor->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $doctor->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ $doctor->gender == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label>DOB</label>
                                    <input type="date" name="dob" class="form-control" value="{{ \Carbon\Carbon::parse($doctor->dob)->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label>Emergency Contact</label>
                                    <input type="text" name="emergency" class="form-control" value="{{ $doctor->emergency }}">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label>Relation</label>
                                    <input type="text" name="relation" class="form-control" value="{{ $doctor->relation }}">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label>Address Line 1</label>
                                    <input type="text" name="address_line_1" class="form-control" value="{{ $doctor->address_line_1 }}">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label>Address Line 2</label>
                                    <input type="text" name="address_line_2" class="form-control" value="{{ $doctor->address_line_2 }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Educational Information -->
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#eduInfo">
                                Educational Information
                            </button>
                        </h2>
                        <div id="eduInfo" class="accordion-collapse collapse">
                            <div class="accordion-body row">
                                <div class="col-md-4 mt-3">
                                    <label>Degree</label>
                                    <input type="text" name="degree" class="form-control" value="{{ $doctor->degree }}">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label>College</label>
                                    <input type="text" name="college" class="form-control" value="{{ $doctor->college }}">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label>Completion Year</label>
                                    <input type="text" name="completion_year" class="form-control" value="{{ $doctor->completion_year }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Information -->
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#proInfo">
                                Professional Information
                            </button>
                        </h2>
                        <div id="proInfo" class="accordion-collapse collapse">
                            <div class="accordion-body row">
                                <div class="col-md-6 mt-3">
                                    <label>Specialization</label>
                                    <select name="specialization" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($category as $cat)
                                            <option value="{{ $cat->id }}" {{ $doctor->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label>Experience (years)</label>
                                    <input type="text" name="experience_year" class="form-control" value="{{ $doctor->experience_year }}">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label>Achievement</label>
                                    <textarea name="achievement" class="form-control">{{ $doctor->achievement }}</textarea>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label>Experience Brief</label>
                                    <textarea name="experience_brief" class="form-control">{{ $doctor->experience_brief }}</textarea>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label>Previous Organisation</label>
                                    <input type="text" name="previous_orgnisation" class="form-control" value="{{ $doctor->previous_orgnisation }}">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label>Current Workplace</label>
                                    <input type="text" name="current_workplace" class="form-control" value="{{ $doctor->current_workplace }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents and Proofs -->
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#docsInfo">
                                Documents & Proofs
                            </button>
                        </h2>
                        <div id="docsInfo" class="accordion-collapse collapse">
                            <div class="accordion-body row">
                                @php
                                    $proofs = [
                                        'adhar_proof' => 'Aadhar Card',
                                        'pan_proof' => 'PAN Card',
                                        'degree_proof' => 'Degree Certificate',
                                        'registration_proof' => 'Registration Certificate',
                                        'cheque' => 'Cancelled Cheque',
                                        'video_proof' => 'Video Proof'
                                    ];
                                @endphp
                                @foreach($proofs as $field => $label)
                                    <div class="col-md-6 mt-3">
                                        <label>{{ $label }}</label>
                                        <input type="file" name="{{ $field }}" class="form-control" accept="{{ $field == 'video_proof' ? 'video/*' : 'image/*' }}">
                                        @if(!empty($doctor->$field))
                                            @if($field == 'video_proof')
                                                <video controls style="width: 100%; max-height: 300px">
                                                    <source src="{{ $doctor->$field }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @else
                                                <img src="{{ $doctor->$field }}" style="max-width: 200px; max-height: 200px;" class="img-thumbnail mt-2" />
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">Update Doctor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $('#addstaff').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: "{{ url('admin/doctors/update') }}",
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('button[type="submit"]').prop('disabled', true).text('Updating...');
            },
            success: function (response) {
                if (response.code === 200) {
                    Swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors;
                if (errors) {
                    for (const field in errors) {
                        $(`[name="${field}"]`).after(`<span class="text-danger">${errors[field][0]}</span>`);
                    }
                } else {
                Swal.fire('Error', 'Something went wrong.', 'error');
                }
            },
            complete: function () {
                $('button[type="submit"]').prop('disabled', false).text('Update Doctor');
            }
        });
    });
</script>
@endsection
