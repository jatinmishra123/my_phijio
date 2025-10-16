<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">

<head>
    <meta charset="utf-8" />
    <title> Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('admin/images/favicon.ico')}}">
    <script src="{{asset('admin/js/layout.js')}}"></script>
    <link href="{{asset('admin/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/css/custom.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

</head>

<body>

    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <!-- <div>
                                <a href="index-2.html" class="d-inline-block auth-logo">
                                    <img src="/assets/logo.png" alt="" height="40">
                                </a>
                            </div> -->
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <p class="mt-3 fs-15 fw-medium"> <img src="{{asset('admin/images/myphsio.png')}}"
                                            alt="" height="17" style="width: 100px; height: 80px; "></p>

                                    <p class="text-muted">Sign in to continue to Myphsio.</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <form method="post" action="{{url('auth')}}">
                                        @csrf()
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" value="{{old('email')}}"
                                                name="email" placeholder="Enter Email">
                                            @error('email')
                                                <br>
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="password-input">Password</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password" class="form-control pe-5"
                                                    placeholder="Enter password" value="" name="password"
                                                    id="password-input">
                                                <span class="error" id="passworderror"></span>
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                                                    type="button" id="password-addon"><i
                                                        class="ri-eye-fill align-middle"></i></button>
                                            </div>
                                            @error('password')
                                                <br>
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                                            <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                        </div> -->

                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" type="submit">Sign In</button>
                                        </div>
                                        @if(Session::has('message'))
                                            <br>
                                            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">
                                                {{ Session::get('message') }}
                                            </p>
                                        @endif

                                    </form>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>
                                    if (new Date().getFullYear() == 2022) {
                                        document.write(new Date().getFullYear());
                                    } else {
                                        document.write(new Date().getFullYear()) + document.write('-') + document.write(new Date().getFullYear() + 1);
                                    }
                                </script> {{ getwebdetail('title') ?? '' }}. Developed by <a target="__blank"
                                    href="https://www.wayone.co.in/">Wayone It Solutuons</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="{{asset('admin/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('admin/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('admin/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('admin/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('admin/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
    <script src="{{asset('admin/js/plugins.js')}}"></script>

    <!-- particles js -->
    <script src="{{asset('admin/libs/particles.js/particles.js')}}"></script>
    <!-- particles app js -->
    <script src="{{asset('admin/js/pages/particles.app.js')}}"></script>
    <!-- password-addon init -->
    <script src="{{asset('admin/js/pages/password-addon.init.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

</body>

</html>