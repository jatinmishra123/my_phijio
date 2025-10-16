@extends('admin.layout')

@section('content')

<style>
    .proof-img {
        transition: transform 0.3s ease-in-out;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .proof-img:hover {
        transform: scale(1.05);
    }
    .profile-section {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    .section-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 15px;
        border-bottom: 2px solid #ddd;
        padding-bottom: 5px;
        color: #444;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">👨‍⚕️ Doctor Profile</h4>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">← Back</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="row">
                <!-- Profile Image & Name -->
                <div class="col-lg-4 text-center mb-4">
                    <img src="{{ $user_table->image ? asset('uploads/doctor/' . $user_table->image) : asset('admin/images/users/avatar-1.jpg') }}"
                        class="img-fluid rounded-4 shadow-sm" style="max-height: 280px; object-fit: contain;">
                    <h5 class="mt-3 fw-bold">{{ $user_table->name ?? '-' }}</h5>
                    <span class="badge bg-primary-subtle text-dark rounded-pill px-3 py-2 mt-2">{{ $user_table->genre ?? 'User' }}</span>
                    <p class="text-muted mt-2 mb-0"><i class="ri-mail-line"></i> {{ $user_table->email ?? '-' }}</p>
                    <p class="text-muted"><i class="ri-phone-line"></i> {{ $user_table->phone ?? '-' }}</p>
                </div>

                <!-- Main Details -->
                <div class="col-lg-8">

                    @php $doctor = $user_table->doctor; @endphp

                    <!-- Basic Info -->
                    <div class="profile-section">
                        <div class="section-title">📝 Basic Information</div>
                        <div class="row">
                            <div class="col-md-6"><strong>Email:</strong> {{ $user_table->email ?? '-' }}</div>
                            <div class="col-md-6"><strong>Phone:</strong> {{ $user_table->phone ?? '-' }}</div>
                            <div class="col-md-6"><strong>Registered On:</strong> {{ \Carbon\Carbon::parse($user_table->created_at)->format('d M Y') }}</div>
                        </div>
                    </div>

                    @if($doctor)
                    <!-- Doctor Details -->
                    <div class="profile-section">
                        <div class="section-title">🏥 Doctor Details</div>
                        <div class="row">
                            <div class="col-md-6"><strong>Unique ID:</strong> {{ $doctor->unique_id ?? '-' }}</div>
                            <div class="col-md-6"><strong>Gender:</strong> {{ $doctor->gender ?? '-' }}</div>
                            <div class="col-md-6"><strong>Date of Birth:</strong> {{ $doctor->dob ?? '-' }}</div>
                            <div class="col-md-6"><strong>Degree:</strong> {{ $doctor->degree ?? '-' }}</div>
                            <div class="col-md-6"><strong>College:</strong> {{ $doctor->college ?? '-' }}</div>
                            <div class="col-md-6"><strong>Completion Year:</strong> {{ $doctor->completion_year ?? '-' }}</div>
                            <div class="col-md-6"><strong>Experience:</strong> {{ $doctor->experience_year ?? '-' }} years</div>
                            <div class="col-md-6"><strong>Expertise:</strong> {{ $doctor->area_of_expertise ?? '-' }}</div>
                            <div class="col-md-6"><strong>Current Workplace:</strong> {{ $doctor->current_workplace ?? '-' }}</div>
                            <div class="col-md-6"><strong>Previous Org:</strong> {{ $doctor->previous_orgnisation ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="profile-section">
                        <div class="section-title">📍 Address</div>
                        <div class="row">
                            <div class="col-md-6"><strong>Address:</strong> {{ $doctor->address_line_1 ?? '-' }}</div>
                            <div class="col-md-6"><strong>City:</strong> {{ $doctor->city ?? '-' }}</div>
                            <div class="col-md-6"><strong>State:</strong> {{ $doctor->state ?? '-' }}</div>
                            <div class="col-md-6"><strong>Country:</strong> {{ $doctor->country ?? '-' }}</div>
                            <div class="col-md-6"><strong>Zipcode:</strong> {{ $doctor->zipcode ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- Emergency -->
                    <div class="profile-section">
                        <div class="section-title">🚨 Emergency Contact</div>
                        <div class="row">
                            <div class="col-md-6"><strong>Contact Number:</strong> {{ $doctor->emergency ?? '-' }}</div>
                            <div class="col-md-6"><strong>Relation:</strong> {{ $doctor->relation ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- Bank Info -->
                    <div class="profile-section">
                        <div class="section-title">🏦 Bank Details</div>
                        <div class="row">
                            <div class="col-md-6"><strong>Bank Name:</strong> {{ $doctor->bank_name ?? '-' }}</div>
                            <div class="col-md-6"><strong>Holder Name:</strong> {{ $doctor->holder_name ?? '-' }}</div>
                            <div class="col-md-6"><strong>Account No:</strong> {{ $doctor->account_number ?? '-' }}</div>
                            <div class="col-md-6"><strong>IFSC:</strong> {{ $doctor->ifsc_code ?? '-' }}</div>
                            <div class="col-md-6"><strong>UPI:</strong> {{ $doctor->upi_id ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- Proof Documents -->
                    <div class="profile-section">
                        <div class="section-title">📂 Uploaded Documents</div>
                        <div class="row">
                            @php
                                $proofs = [
                                    'Degree Proof' => $doctor->degree_proof ?? null,
                                    'PAN Card' => $doctor->pan_proof ?? null,
                                    'Aadhar Card' => $doctor->adhar_proof ?? null,
                                    'Registration Certificate' => $doctor->registration_proof ?? null,
                                    'Cheque' => $doctor->cheque ?? null,
                                    'Experience Proof' => $doctor->experience_proof ?? null,
                                    'Police Verification' => $doctor->police_verification_proof ?? null,
                                    'Signature' => $doctor->signature ?? null,
                                    'Additional Certificate' => $doctor->additional_certificate ?? null,
                                ];
                            @endphp

                            @forelse($proofs as $label => $url)
                                @if(!empty($url))
                                <div class="col-md-3 col-sm-4 col-6 mb-4 text-center">
                                    <div class="fw-semibold mb-2">{{ $label }}</div>
                                    <a href="{{ $url }}" target="_blank">
                                        <img src="{{ $url }}" alt="{{ $label }}" class="img-fluid proof-img" style="height:120px; object-fit:cover;">
                                    </a>
                                </div>
                                @endif
                            @empty
                                <div class="text-muted">No proof documents uploaded.</div>
                            @endforelse
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
