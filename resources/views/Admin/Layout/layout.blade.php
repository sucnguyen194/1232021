<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset(optional($setting)->favicon)}}">

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    @yield('css')
    <link href="/admin/assets/libs/spinkit/spinkit.css" rel="stylesheet" type="text/css" >
    <!-- Jquery Toast css -->
    <link href="/admin/assets/libs/jquery-toast/jquery.toast.min.css" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{asset('admin/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
    <link href="{{asset('admin/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/css/app.min.css')}}" rel="stylesheet" type="text/css"  id="app-stylesheet" />

    <!-- Cpanel css -->
    <link href="{{asset('admin/css/cpanel.css')}}" rel="stylesheet" type="text/css">
    <script src="/admin/ckeditor/ckeditor.js"></script>
    <script src="/admin/ckfinder/ckfinder.js"></script>
    <!-- Vendor js -->
    <script src="{{asset('admin/assets/js/vendor.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>
    <!-- Cpanel -->
    <script src="{{asset('admin/js/cpanel.js')}}"></script>
</head>

<body>

<!-- Begin page -->
<div id="wrapper">

    <!-- Topbar Start -->
    <div class="navbar-custom">
        <ul class="list-unstyled topnav-menu float-right mb-0">
            @php
                $contact = \App\Models\Contact::where('status', \App\Enums\ActiveDisable::DISABLE)->take(10)->get();
                $langs = \App\Models\Lang::all();
            @endphp
            <li class="redirect-website"><a href="{{route('home')}}" class="nav-link dropdown-toggle mr-0 waves-effect waves-light" target="_blank">Website</a> </li>
            <li class="dropdown notification-list dropdown d-lg-inline-block ml-2"> <a class="nav-link dropdown-toggle mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{implode($langs->where('value',Session::get('lang'))->pluck('name')->toArray())}} </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    @foreach($langs->where('value','<>',Session::get('lang')) as $item)
                    <!-- item-->
                    <a href="{{route('admin.change.lang',$item->value)}}" class="dropdown-item notify-item"><span
                            class="align-middle">{{$item->name}}</span> </a>
                        @endforeach
                  </div>
            </li>

            <li class="dropdown notification-list"> <a class="nav-link dropdown-toggle  waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false"><i class="dripicons-bell noti-icon"></i> <span class="badge badge-pink rounded-circle noti-icon-badge">{{$contact->count()}}</span></a>

                <div class="dropdown-menu dropdown-menu-right dropdown-lg">
                    <div class="dropdown-header noti-title">
                        <h5 class="text-overflow m-0"><span class="float-right"> <span class="badge badge-danger float-right">{{$contact->count()}}</span> </span>Thông báo</h5>
                    </div>
                    @if($contact->count())
                    <div class="slimscroll noti-scroll">
                        @foreach($contact as $item)
                        <a href="{{route('admin.contact.show',$item)}}" class="dropdown-item notify-item">
                            <div class="notify-icon bg-success"><i class="mdi mdi-comment-account-outline"></i></div>
                            <p class="notify-details">{{$item->note ? str_limit($item->note) : 'Khách hàng yêu cầu nhận thông tin'}}<small class="text-muted">{{$item->created_at->diffForHumans()}}</small></p>
                        </a>
                        <!-- item-->
                        @endforeach

                       </div>
                        @endif
                    <!-- All-->
                    <a href="{{route('admin.contact.index')}}" class="dropdown-item text-center text-primary notify-item notify-all"> Xem tất cả <i class="fi-arrow-right"></i> </a> </div>
            </li>

            <li class="dropdown notification-list"> <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false"> <img src="{{asset('admin/assets/images/users/avatar-1.jpg')}}" alt="user-image" class="rounded-circle"> <span class="pro-user-name ml-1"> {{Auth::user()->name}} <i class="mdi mdi-chevron-down"></i> </span> </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Xin chào !</h6>
                    </div>

                    <!-- item-->
                    <a href="{{route('admin.user.edit',Auth::id())}}" class="dropdown-item notify-item"> <i class="fe-user"></i> <span>Tài khoản</span> </a>

                    <!-- item-->
{{--                    <a href="javascript:void(0);" class="dropdown-item notify-item"> <i class="fe-settings"></i> <span>Settings</span> </a>--}}

                    <!-- item-->
{{--                    <a href="javascript:void(0);" class="dropdown-item notify-item"> <i class="fe-lock"></i> <span>Lock Screen</span> </a>--}}
{{--                    <div class="dropdown-divider"></div>--}}

                    <!-- item-->
                    <a href="{{route('admin.logout')}}" class="dropdown-item notify-item"> <i class="fe-log-out"></i> <span>Thoát</span> </a> </div>
            </li>

        </ul>

        <!-- LOGO -->
        <div class="logo-box"> <a href="" class="logo text-center"> <span class="logo-lg"> <img src="{{asset('admin/assets/images/logo-light.png')}}" alt="" height="25">
                    <!-- <span class="logo-lg-text-light">UBold</span> -->
      </span> <span class="logo-sm">
      <!-- <span class="logo-sm-text-dark">U</span> -->
      <img src="{{asset('admin/assets/images/logo-sm.png')}}" alt="" height="28"> </span> </a> </div>
        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
                <button class="button-menu-mobile waves-effect waves-light"> <i class="fe-menu"></i> </button>
            </li>
{{--            <li class="d-none d-sm-block">--}}
{{--                <form class="app-search" method="get" action="">--}}
{{--                    <div class="app-search-box">--}}
{{--                        <div class="input-group">--}}
{{--                            <input type="text" class="form-control" placeholder="Search...">--}}
{{--                            <div class="input-group-append">--}}
{{--                                <button class="btn" type="submit"> <i class="fe-search"></i> </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </li>--}}
        </ul>
    </div>
    <!-- end Topbar -->

    <!-- ========== Left Sidebar Start ========== -->
    <div class="left-side-menu">
        <div class="slimscroll-menu">

            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <ul class="metismenu" id="side-menu">
                    @php
                        $type = \App\Models\UserModuleSystems::where('user_id', Auth::id())->pluck('type');
                        $nav = \App\Models\SystemsModule::whereIn('type',$type)->orderby('sort','asc')->get();
                        $module = \App\Models\Modules::get();
                        if(Auth::user()->lever == \App\Enums\LeverUser::SUPPERADMIN)
                            $nav = \App\Models\SystemsModule::orderby('sort','asc')->get();
                    @endphp

                    @foreach($nav->where('parent_id', 0)->where('position',0) as $item)
                        <li>
                            <a href="{{$item->route ? route($item->route) : "javascript:void(0)"}}">
                                <i class="{{$item->icon}}"></i>
                                <span>  {{$item->name}} </span>
                                @if($nav->where('parent_id', $item->id)->count()) <span class="menu-arrow"></span> @endif
                            </a>
                            @if($nav->where('parent_id', $item->id)->count())
                                <ul class="nav-second-level" aria-expanded="false">
                                    @foreach($nav->where('parent_id', $item->id) as $sub)
                                        <li><a href="{{$sub->route ? route($sub->route) : "javascript:void(0)"}}">{{$sub->name}}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                    <li class="menu-title" style="{{$nav->where('parent_id', 0)->where('position',1) ? "" : "display:none"}}">Nội dung</li>
                    @foreach($nav->where('parent_id', 0)->where('position',1) as $item)
                         <li>
                                <a href="{{$item->route ? route($item->route) : "javascript:void(0)"}}">
                                    <i class="{{$item->icon}}"></i>
                                    <span>  {{$item->name}} </span>
                                   @if($nav->where('parent_id', $item->id)->count()) <span class="menu-arrow"></span> @endif
                                </a>
                                @if($nav->where('parent_id', $item->id)->count())
                                <ul class="nav-second-level" aria-expanded="false">
                                   @foreach($nav->where('parent_id', $item->id) as $sub)
                                    <li><a href="{{$sub->route ? route($sub->route) : "javascript:void(0)"}}">{{$sub->name}}</a></li>
                                    @endforeach
                                </ul>
                                @endif
                            </li>
                    @endforeach
                    <li class="menu-title" style="{{$nav->where('parent_id', 0)->where('position',2) ? "" : "display:none"}}">Bán hàng</li>
                    @foreach($nav->where('parent_id', 0)->where('position',2) as $item)
                        <li>
                            <a href="{{$item->route ? route($item->route) : "javascript:void(0)"}}">
                                <i class="{{$item->icon}}"></i>
                                <span>  {{$item->name}} </span>
                                @if($nav->where('parent_id', $item->id)->count()) <span class="menu-arrow"></span> @endif
                            </a>
                            @if($nav->where('parent_id', $item->id)->count())
                                <ul class="nav-second-level" aria-expanded="false">
                                    @foreach($nav->where('parent_id', $item->id) as $sub)
                                        <li><a href="{{$sub->route ? route($sub->route) : "javascript:void(0)"}}">{{$sub->name}}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                    <li class="menu-title" style="{{$nav->where('parent_id', 0)->where('position',3)->count() ? "" : "display:none"}}">Cấu hình</li>
                    @foreach($nav->where('parent_id', 0)->where('position',3) as $item)
                        <li>
                            <a href="{{$item->route ? route($item->route) : "javascript:void(0)"}}">
                                <i class="{{$item->icon}}"></i>
                                <span>  {{$item->name}} </span>
                                @if($nav->where('parent_id', $item->id)->count()) <span class="menu-arrow"></span> @endif
                            </a>
                            @if($nav->where('parent_id', $item->id)->count())
                                <ul class="nav-second-level" aria-expanded="false">
                                    @foreach($nav->where('parent_id', $item->id) as $sub)
                                        <li><a href="{{$sub->route ? route($sub->route) : "javascript:void(0)"}}">{{$sub->name}}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                    @if(in_array(\App\Enums\SystemsModuleType::CONFIG_MODULE, $type->toArray()) || Auth::user()->lever == \App\Enums\LeverUser::SUPPERADMIN)
                        <li>
                            <a href="javascript: void(0);">
                                <i class="pe-7s-settings"></i>
                                <span> Action Modules </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                @foreach($module as $sub)
                                    <li><a href="{{route('admin.action.module.index',$sub->table)}}">{{$sub->name}}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
            <!-- End Sidebar -->

            <div class="clearfix"></div>
        </div>
        <!-- Sidebar -left -->

    </div>
    <!-- Left Sidebar End -->
    <style>
        #sidebar-menu>ul>li>a i {
            font-size: 1.3rem;
        }
    </style>
    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            @yield('content')
            <!-- end container-fluid -->
        </div>
        <!-- end content -->

        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12"> Admin Cpanel</div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->
</div>
<!-- END wrapper -->
<style>
    .loading {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0, 0.9);
        z-index: 9999;
    }
    .loading .sk-cube-grid {
        position: absolute;
        top: 40%;
        left: 0;
        right: 0;
        bottom: 0;
    }
</style>

<div class="loading">
    <div class="sk-cube-grid">
        <div class="sk-cube sk-cube1"></div>
        <div class="sk-cube sk-cube2"></div>
        <div class="sk-cube sk-cube3"></div>
        <div class="sk-cube sk-cube4"></div>
        <div class="sk-cube sk-cube5"></div>
        <div class="sk-cube sk-cube6"></div>
        <div class="sk-cube sk-cube7"></div>
        <div class="sk-cube sk-cube8"></div>
        <div class="sk-cube sk-cube9"></div>
    </div>
</div>
<!-- javascript -->
@yield('javascript')


<!-- Tost-->
<script src="/admin/assets/libs/jquery-toast/jquery.toast.min.js"></script>
<!-- toastr init js-->
{{--<script src="/admin/assets/js/pages/toastr.init.js"></script>--}}
<!-- App js -->
<script src="{{asset('admin/assets/js/app.min.js')}}"></script>
@include('Errors.note')
<script type="text/javascript">
    CKEDITOR.replace( 'summernote' ,{
        height:250
    });
    CKEDITOR.replace( 'summerbody' ,{
        height:500
    });
</script>

<script type="text/javascript">
    $(window).on('load',function() {
        $('.loading').fadeOut();
    });
</script>

</body>

</html>