<div class="offcanvas-body profile-offcanvas p-0">
    <div class="team-cover">
        <img src="{{asset('admin/images/small/img-9.jpg')}}" alt="" class="img-fluid" />
    </div>
    <div class="p-3">
        <div class="team-settings">
            <div class="row">
                <div class="col">
                </div>
                <div class="col text-end dropdown">
                    <a href="javascript:void(0);" id="dropdownMenuLink14" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-more-fill fs-17"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink14">
                        <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-eye-line me-2 align-middle"></i>View</a></li>
                        <li><a class="dropdown-item" data-bs-target="#deletepopup" data-bs-toggle="modal" onclick="deletepopup(<?= $id ?>,'vendor','delete')" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle"></i>Delete</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <div class="p-3 text-center">

        @if(!empty($vendor_staff_image))
        <img src="{{asset('uploads/staff/'.$vendor_staff_image)}}" alt="" class="avatar-lg img-thumbnail rounded-circle mx-auto" />
        @else
        <img src="{{asset('admin/images/users/avatar-1.jpg')}}" alt="" class="avatar-lg img-thumbnail rounded-circle mx-auto" />
        @endif

        @php if (!empty($vendor_name)) { @endphp
        <div class="mt-3">
            <h5 class="fs-15"><a href="javascript:void(0);" class="link-primary">{{ $vendor_name}}</a></h5>
            <p class="text-muted">{{ $vendor_staff_role}}</p>
        </div>
        @php } @endphp
    </div>

    <!--end row-->
    <div class="p-3">
        <h5 class="fs-15 mb-3">Personal Details</h5>
        @php if (!empty($vendor_phone)) { @endphp
            <div class="mb-3">
                <p class="text-muted text-uppercase fw-semibold fs-12 mb-2">Number</p>
                <h6>{{$vendor_phone}}</h6>
            </div>
        @php } @endphp

        @php if (!empty($vendor_email)) { @endphp
            <div class="mb-3">
                <p class="text-muted text-uppercase fw-semibold fs-12 mb-2">Email</p>
                <h6> {{$vendor_email}} </h6>
            </div>
        @php } @endphp

    </div>
    <div class="p-3 border-top">
        <h5 class="fs-15 mb-4">Role & Permissions</h5>
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Read</th>
                    <th>Write</th>
                </tr>
            </thead>

            <tbody>
            @php
            if(!empty($modules)) {
            foreach($modules as $value) { 
                $read = $value['read'];
                $write = $value['write_permission'];
            @endphp

            <tr>
                <th class="text-muted mb-0">
                    {{$value['module']}}
                </th>
                <td>
                    <div class="text-muted">
                        <input type="checkbox" class="text-muted mb-0" @if($read == '1') checked @endif >
                    </div>
                </td>
                <td>
                    <div class="text-muted">
                        <input type="checkbox" class="text-muted mb-0" @if($write == '1') checked @endif >
                    </div>
                </td>
            </tr>
            @php } } @endphp
            </tbody>
        </table>
    </div>

</div>
</div>
<!--end offcanvas-body-->