<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Đăng ký tài khoản</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset($setting->favicon)}}">
    <!-- Jquery Toast css -->
    <link href="/admin/assets/libs/jquery-toast/jquery.toast.min.css" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{asset('admin/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
    <link href="{{asset('admin/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/css/app.min.css')}}" rel="stylesheet" type="text/css"  id="app-stylesheet" />

</head>

<body class="authentication-bg bg-primary authentication-bg-pattern d-flex align-items-center pb-0 vh-100">

<div class="home-btn d-none d-sm-block">
    <a href="{{route('home')}}"><i class="fas fa-home h2 text-white"></i></a>
</div>

<div class="account-pages w-100 mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card mb-0">
                    <div class="card-body p-4">
                        <div class="account-box">
                            <div class="account-logo-box">
                                <div class="text-center">
                                    <a href="/">
                                        <img src="{{asset($setting->logo)}}" alt="" height="30">
                                    </a>
                                </div>
                                <h5 class="text-uppercase mb-1 mt-4">Đăng ký</h5>
                                <p class="mb-0">Nhập thông tin đăng ký dưới đây</p>
                            </div>

                            <div class="account-content mt-4">
                                <form class="form-horizontal" method="post" action="">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label for="username">Họ & tên</label>
                                            <input class="form-control" type="text" id="username" name="name" required="" placeholder="Nguyễn Văn A">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label for="emailaddress">Email</label>
                                            <input class="form-control" type="email" id="emailaddress" name="email" required="" placeholder="email@email.com">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <a href="{{route('user.forget')}}" class="text-muted float-right"><small>Quên mật khẩu?</small></a>
                                            <label for="password">Mật khẩu</label>
                                            <input class="form-control" type="password" required="" name="password" id="password" placeholder="********">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label for="re_password">Nhập lại mật khẩu</label>
                                            <input class="form-control" type="password" name="re_password" required="" id="re_password" placeholder="********">
                                        </div>
                                    </div>

{{--                                    <div class="form-group row">--}}
{{--                                        <div class="col-12">--}}
{{--                                            <div class="checkbox checkbox-success">--}}
{{--                                                <input id="remember" type="checkbox" checked="">--}}
{{--                                                <label for="remember">--}}
{{--                                                    I accept <a href="#">Terms and Conditions</a>--}}
{{--                                                </label>--}}
{{--                                            </div>--}}

{{--                                        </div>--}}
{{--                                    </div>--}}

                                    <div class="form-group row text-center mt-2">
                                        <div class="col-12">
                                            <button class="btn btn-md btn-block btn-primary waves-effect waves-light" type="submit">Đăng ký</button>
                                        </div>
                                    </div>

                                </form>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="text-center">
                                            <button type="button" class="btn mr-1 btn-facebook waves-effect waves-light">
                                                <i class="fab fa-facebook-f"></i>
                                            </button>
                                            <button type="button" class="btn mr-1 btn-googleplus waves-effect waves-light">
                                                <i class="fab fa-google"></i>
                                            </button>
                                            <button type="button" class="btn mr-1 btn-twitter waves-effect waves-light">
                                                <i class="fab fa-twitter"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4 pt-2">
                                    <div class="col-sm-12 text-center">
                                        <p class="text-muted">Bạn đã có tài khoản?  <a href="{{route('user.login')}}" class="text-dark ml-1"><b>Đăng nhập</b></a></p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <!-- end card-body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end page -->

<!-- Vendor js -->
<script src="{{asset('admin/assets/js/vendor.min.js')}}"></script>
<!-- Tost-->
<script src="/admin/assets/libs/jquery-toast/jquery.toast.min.js"></script>

<!-- toastr init js-->
{{--<script src="/admin/assets/js/pages/toastr.init.js"></script>--}}
<!-- App js -->
<script src="{{asset('admin/assets/js/app.min.js')}}"></script>
@include('Errors.note')
</body>
</html>













