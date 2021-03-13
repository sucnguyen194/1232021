<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Quên mật khẩu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{$setting->description_seo}}" name="description" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset($setting->favicon)}}">
    <!-- Jquery Toast css -->
    <link href="/admin/assets/libs/jquery-toast/jquery.toast.min.css" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="/admin/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
    <link href="/admin/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/css/app.min.css" rel="stylesheet" type="text/css"  id="app-stylesheet" />

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
                            <div class="text-center account-logo-box">
                                <div>
                                    <a href="{{route('home')}}">
                                        <img src="{{asset($setting->logo)}}" alt="{{$setting->name}}" height="30">
                                    </a>
                                </div>
                            </div>
                            <div class="account-content mt-4">
                                <div class="text-center">
                                    <p class="text-muted mb-0 mb-3">Nhập địa chỉ email của bạn và chúng tôi sẽ gửi cho bạn một email kèm theo hướng dẫn để đặt lại mật khẩu của bạn </p>
                                </div>
                                <form class="form-horizontal" method="post" action="">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label for="emailaddress">Email</label>
                                            <input class="form-control" name="email" type="text" id="email" required="" placeholder="user@gmail.com">
                                        </div>
                                    </div>
                                    <div class="form-group row text-center mt-2">
                                        <div class="col-12">
                                            <button class="btn btn-md btn-block btn-primary waves-effect waves-light" type="submit">Gửi yêu cầu</button>
                                        </div>
                                    </div>
                                </form>

                                <div class="clearfix"></div>
                                <div class="row mt-4">
                                    <div class="col-sm-12 text-center">
                                        <p class="text-muted mb-0">Quay lại <a href="{{route('user.login')}}" class="text-dark ml-1"><b>Đăng nhập</b></a></p>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- end card-box-->
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
<script src="/admin/assets/js/vendor.min.js"></script>
<!-- Tost-->
<script src="/admin/assets/libs/jquery-toast/jquery.toast.min.js"></script>
<!-- App js -->
<script src="/admin/assets/js/app.min.js"></script>
@include('Errors.note')
</body>
</html>
