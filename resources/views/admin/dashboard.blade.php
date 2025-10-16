@extends('admin.layout')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Dashboard</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col">

            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="flex-grow-1">
                                <h4 class="fs-16 mb-1 mt-4">Hi, Admin !</h4>
                                <p class="text-muted mb-0">Your business is set for success today. Stay informed, stay
                                    ahead! 🚀"







                                    .</p>
                            </div>
                        </div><!-- end card header -->
                    </div>
                    <!--end col-->
                </div>
                <div class="row">
                    <div class="col">

                        <div class="h-100">

                            <div class="row">
                                <div class="col-xl-3 col-md-6">
                                    <!-- card -->
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total
                                                        Doctors</p>
                                                </div>

                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value"
                                                            data-target="{{$doctors}}">{{$doctors}}</span> </h4>
                                                    <a href="{{url('/admin/role/doctor')}}"
                                                        class="text-decoration-underline">View Doctors</a>
                                                </div>
                                                <!-- <div class="avatar-sm flex-shrink-0">
                                                            <span class="avatar-title bg-soft-success rounded fs-3">
                                                                <i class="bx bx-dollar-circle text-success"></i>
                                                            </span>
                                                        </div> -->
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->

                                <!--<div class="col-xl-3 col-md-6">-->
                                <!--    <div class="card card-animate">-->
                                <!--        <div class="card-body">-->
                                <!--            <div class="d-flex align-items-center">-->
                                <!--                <div class="flex-grow-1 overflow-hidden">-->
                                <!--                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Nurses</p>-->
                                <!--                </div>-->

                                <!--            </div>-->
                                <!--            <div class="d-flex align-items-end justify-content-between mt-4">-->
                                <!--                <div>-->
                                <!--                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{$nurse}}">{{$nurse}}</span></h4>-->
                                <!--                    <a href="{{url('/admin/role/nurse')}}" class="text-decoration-underline">View Nurses</a>-->
                                <!--                </div>-->
                                <!--                <div class="avatar-sm flex-shrink-0">-->
                                <!--                    <span class="avatar-title bg-soft-info rounded fs-3">-->
                                <!--                        <i class="bx bx-shopping-bag text-info"></i>-->
                                <!--                    </span>-->
                                <!--                </div>-->
                                <!--            </div>-->
                                <!--        </div>-->
                                <!--    </div>-->
                                <!--</div>-->

                                <div class="col-xl-3 col-md-6">
                                    <!-- card -->
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total
                                                        Users</p>
                                                </div>

                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value" data-target="{{$users}}">{{$users}}</span>
                                                    </h4>
                                                    <a href="{{url('admin/registration')}}"
                                                        class="text-decoration-underline">View all</a>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-soft-warning rounded fs-3">
                                                        <i class="bx bx-user-circle text-warning"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->

                                <!--<div class="col-xl-3 col-md-6">-->
                                <!-- card -->
                                <!--    <div class="card card-animate">-->
                                <!--        <div class="card-body">-->
                                <!--            <div class="d-flex align-items-center">-->
                                <!--                <div class="flex-grow-1 overflow-hidden">-->
                                <!--                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Centers</p>-->
                                <!--                </div>-->

                                <!--            </div>-->
                                <!--            <div class="d-flex align-items-end justify-content-between mt-4">-->
                                <!--                <div>-->
                                <!--                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{$chamber}}">{{$chamber}}</span> </h4>-->
                                <!--                    <a href="{{url('admin/hospital')}}" class="text-decoration-underline">View All</a>-->
                                <!--                </div>-->
                                <!--                <div class="avatar-sm flex-shrink-0">-->
                                <!--                    <span class="avatar-title bg-soft-primary rounded fs-3">-->
                                <!--                        <i class="bx bx-wallet text-primary"></i>-->
                                <!--                    </span>-->
                                <!--                </div>-->
                                <!--            </div>-->
                                <!--        </div><!-- end card body -->
                                <!--    </div><!-- end card -->
                                <!--</div><!-- end col -->
                                <div class="col-xl-3 col-md-6">
                                    <!-- card -->
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total
                                                        Appointment</p>
                                                </div>

                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value" data-target="{{$all}}">{{$all}}</span>
                                                    </h4>
                                                    <a href="{{ url('admin/appointments/all')}}"
                                                        class="text-decoration-underline">View All</a>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-soft-primary rounded fs-3">
                                                        <i class="bx bx-wallet text-primary"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->
                                <div class="col-xl-3 col-md-6">
                                    <!-- card -->
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                        Pending Appointment</p>
                                                </div>

                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value"
                                                            data-target="{{$pending}}">{{$pending}}</span> </h4>
                                                    <a href="{{ url('admin/appointments/pending')}}"
                                                        class="text-decoration-underline">View All</a>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-soft-primary rounded fs-3">
                                                        <i class="bx bx-wallet text-primary"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->
                                <div class="col-xl-3 col-md-6">
                                    <!-- card -->
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                        Completed Appointment</p>
                                                </div>

                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                            class="counter-value"
                                                            data-target="{{$completed}}">{{$completed}}</span> </h4>
                                                    <a href="{{ url('admin/appointments/completed')}}"
                                                        class="text-decoration-underline">View All</a>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-soft-primary rounded fs-3">
                                                        <i class="bx bx-wallet text-primary"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->
                            </div> <!-- end row-->
                            <!--end row-->


                        </div> <!-- end .h-100-->

                    </div> <!-- end col -->

                </div>
            </div>

@endsection()