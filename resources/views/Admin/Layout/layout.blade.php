<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>@yield('title') - {{setting()->name}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset(setting()->favicon)}}">
    @yield('css')

    <!-- Jquery Toast css -->
    <link href="/admin/assets/libs/jquery-toast/jquery.toast.min.css" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{asset('admin/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
    <link href="{{asset('admin/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/css/app.min.css')}}" rel="stylesheet" type="text/css"  id="app-stylesheet" />

    <!-- Cpanel css -->
    <link href="{{asset('admin/css/cpanel.css')}}" rel="stylesheet" type="text/css">
    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="/admin/ckeditor/ckeditor.js"></script>
    <script src="/admin/ckfinder/ckfinder.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
                $contact = \App\Models\Contact::whereRepId(0)->oldest('status')->orderByDesc('created_at')->get();
                $langs = \App\Models\Lang::all();
            @endphp
            <li class="redirect-website"><a href="{{route('home')}}" class="nav-link dropdown-toggle mr-0 waves-effect waves-light" target="_blank"><i class="fas fa-home h3 text-white"></i></a> </li>
            <li class="dropdown notification-list dropdown d-lg-inline-block"> <a class="nav-link dropdown-toggle mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{implode($langs->where('value',Session::get('lang'))->pluck('name')->toArray())}} </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    @foreach($langs->where('value','<>',Session::get('lang')) as $item)
                    <!-- item-->
                    <a href="{{route('admin.change.lang',$item->value)}}" class="dropdown-item notify-item"><span
                            class="align-middle">{{$item->name}}</span> </a>
                        @endforeach
                  </div>
            </li>

            <li class="dropdown notification-list"> <a class="nav-link dropdown-toggle  waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false"><i class="dripicons-bell noti-icon"></i> <span class="badge badge-pink rounded-circle noti-icon-badge">{{$contact->where('status',0)->count()}}</span></a>

                <div class="dropdown-menu dropdown-menu-right dropdown-lg">
                    <div class="dropdown-header noti-title">
                        <h5 class="text-overflow m-0"><span class="float-right"> <span class="badge badge-danger float-right">{{$contact->where('status',0)->count()}}</span> </span>Th??ng b??o</h5>
                    </div>
                    @if($contact->count())
                    <div class="slimscroll noti-scroll">
                        @foreach($contact->take(10) as $item)
                        <a href="{{route('admin.contacts.show',$item)}}" class="dropdown-item notify-item">
                            <div class="notify-icon rounded-circle"><img src="{{$item->avatar}}" class="rounded-circle"></div>
                            <p class="notify-details">
                                @if($item->status == 0)
                                <strong class="bg-danger pl-1 pr-1 text-white rounded-circle">!</strong>
                                @endif
                                {{$item->note ? str_limit($item->note) : 'Kh??ch h??ng y??u c???u nh???n th??ng tin'}}<small class="text-muted">{{$item->created_at->diffForHumans()}}</small></p>
                        </a>
                        <!-- item-->
                        @endforeach

                       </div>
                        @endif
                    <!-- All-->
                    <a href="{{route('admin.contacts.index')}}" class="dropdown-item text-center text-primary notify-item notify-all"> Xem t???t c??? <i class="fi-arrow-right"></i> </a> </div>
            </li>

            <li class="dropdown notification-list"> <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false"> <img src="{{Auth::user()->gravatar}}" alt="user-image" class="rounded-circle"> <span class="pro-user-name ml-1"> {{Auth::user()->name}} <i class="mdi mdi-chevron-down"></i> </span> </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Xin ch??o !</h6>
                    </div>

                    <!-- item-->
                    <a href="{{route('admin.users.edit',Auth::id())}}" class="dropdown-item notify-item"> <i class="fe-user"></i> <span>T??i kho???n</span> </a>

                    <!-- item-->
                    <a href="{{route('admin.settings')}}" class="dropdown-item notify-item"> <i class="fe-settings"></i> <span>Settings</span> </a>

                    <!-- item-->
{{--                    <a href="javascript:void(0);" class="dropdown-item notify-item"> <i class="fe-lock"></i> <span>Lock Screen</span> </a>--}}
                    <div class="dropdown-divider"></div>

                    <!-- item-->
                    <a href="{{route('admin.logout')}}" class="dropdown-item notify-item"> <i class="fe-log-out"></i> <span>Tho??t</span> </a> </div>
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
{{--                            <input type="text" class="form-control bg-white text-secondary" placeholder="Search...">--}}
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
                        $post = new \App\Models\Post();
                        $products = new \App\Models\Product();
                        $nav = \App\Models\System::whereHas('users',function($q){
                            $q->whereUserId(auth()->id());
                        })->oldest('sort')->get();
                        $module = \App\Models\Modules::get();
                        $comments = \App\Models\Comment::whereStatus(0)->get();
                        if(Auth::user()->lever == \App\Enums\LeverUser::SUPPERADMIN)
                            $nav = \App\Models\System::oldest('sort')->get();
                    @endphp

                    @foreach($nav->where('parent_id', 0)->where('position',0) as $item)
                        <li>
                            <a href="{{!$nav->where('parent_id', $item->id)->count() ? route($item->route) : "javascript:void(0)"}}">
                                <i class="{{$item->icon}}"></i>
                                <span>{{$item->name}}</span>
                                @if($item->route == 'admin.comments.index')
                                    <span class="badge badge-danger badge-pill">{{$comments->count()}}</span>
                                @endif

                                @if($nav->where('parent_id', $item->id)->count()) <span class="menu-arrow"></span> @endif
                            </a>
                            @if($nav->where('parent_id', $item->id)->count())
                                <ul class="nav-second-level" aria-expanded="false">
                                    @foreach($nav->where('parent_id', $item->id) as $sub)
                                        <li><a href="{{$sub->route ? route($sub->route, $sub->var ?? null) : "javascript:void(0)"}}">{{$sub->name}}
                                                @if($sub->route == 'admin.comments.list' && $sub->var == 'posts')
                                                    <span class="badge badge-danger badge-pill float-right">{{$comments->where('comment_type',get_class($post))->count()}}</span>
                                                @elseif($sub->route == 'admin.comments.list' && $sub->var == 'products')
                                                    <span class="badge badge-danger badge-pill float-right">{{$comments->where('comment_type',get_class($products))->count()}}</span>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                    <li class="menu-title" style="{{$nav->where('parent_id', 0)->where('position',1)->count() ? "" : "display:none"}}">N???i dung</li>
                    @foreach($nav->where('parent_id', 0)->where('position',1) as $item)
                         <li>
                                <a href="{{$item->route ? route($item->route) : "javascript:void(0)"}}">
                                    <i class="{{$item->icon}}"></i>
                                    <span>  {{$item->name}} </span>
                                    @if($item->route == 'admin.comments.index')
                                        <span class="badge badge-danger badge-pill float-right">{{$comments}}</span>
                                    @endif
                                   @if($nav->where('parent_id', $item->id)->count()) <span class="menu-arrow"></span> @endif
                                </a>
                                @if($nav->where('parent_id', $item->id)->count())
                                <ul class="nav-second-level" aria-expanded="false">
                                   @foreach($nav->where('parent_id', $item->id) as $sub)
                                    <li><a href="{{$sub->route ? route($sub->route) : "javascript:void(0)"}}" >{{$sub->name}}</a></li>
                                    @endforeach
                                </ul>
                                @endif
                            </li>
                    @endforeach
                    <li class="menu-title" style="{{$nav->where('parent_id', 0)->where('position',2)->count() ? "" : "display:none"}}">B??n h??ng</li>
                    @foreach($nav->where('parent_id', 0)->where('position',2) as $item)
                        <li>
                            <a href="{{$item->route ? route($item->route) : "javascript:void(0)"}}">
                                <i class="{{$item->icon}}"></i>
                                <span>  {{$item->name}} </span>
                                @if($item->route == 'admin.comments.index')
                                    <span class="badge badge-danger badge-pill float-right">{{$comments}}</span>
                                @endif
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
                    <li class="menu-title" style="{{$nav->where('parent_id', 0)->where('position',3)->count() ? "" : "display:none"}}">C???u h??nh</li>
                    @foreach($nav->where('parent_id', 0)->where('position',3) as $item)
                        <li>
                            <a href="{{$item->route ? route($item->route) : "javascript:void(0)"}}">
                                <i class="{{$item->icon}}"></i>
                                <span>  {{$item->name}} </span>
                                @if($item->route == 'admin.comments.index')
                                    <span class="badge badge-danger badge-pill float-right">{{$comments}}</span>
                                @endif
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
                    @if((in_array(\App\Enums\SystemsModuleType::CONFIG_MODULE, auth()->user()->systems()->pluck('type')->toArray()) && $module->count()) || Auth::user()->lever == \App\Enums\LeverUser::SUPPERADMIN)
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
<!-- Vendor js -->
<script src="{{asset('admin/assets/js/vendor.min.js')}}"></script>
@yield('javascript')
<!-- App js -->
<script src="{{asset('admin/assets/js/app.min.js')}}"></script>
<!-- javascript -->
<link href="/admin/assets/libs/spinkit/spinkit.css" rel="stylesheet" type="text/css" >
<!-- Tost-->
<script src="/admin/assets/libs/jquery-toast/jquery.toast.min.js"></script>
<!-- toastr init js-->
{{--<script src="/admin/assets/js/pages/toastr.init.js"></script>--}}
@include('Errors.note')

<script type="text/javascript">
    function initEvents(){
        $('.tooltip-hover').each(function(){
           $(this).tooltipster();
        })
    }

    initEvents();
    // CKEDITOR.replace( 'summernote' ,{
    //     height:150
    // });
    // CKEDITOR.replace( 'summerbody' ,{
    //     height:300
    // });
</script>

<script type="text/javascript">
    $(window).on('load',function() {
        $('.loading').fadeOut();
    });
</script>

<script type="text/javascript">
    var url = "{{url('/')}}";
    function ChangeToSlug()
    {
        var title, slug;
        //L???y text t??? th??? input title
        title = document.getElementById("title").value;
        //?????i ch??? hoa th??nh ch??? th?????ng
        slug = title.toLowerCase();
        //?????i k?? t??? c?? d???u th??nh kh??ng d???u
        slug = slug.replace(/??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???/gi, 'a');
        slug = slug.replace(/??|??|???|???|???|??|???|???|???|???|???/gi, 'e');
        slug = slug.replace(/i|??|??|???|??|???/gi, 'i');
        slug = slug.replace(/??|??|???|??|???|??|???|???|???|???|???|??|???|???|???|???|???/gi, 'o');
        slug = slug.replace(/??|??|???|??|???|??|???|???|???|???|???/gi, 'u');
        slug = slug.replace(/??|???|???|???|???/gi, 'y');
        slug = slug.replace(/??/gi, 'd');
        //X??a c??c k?? t??? ?????t bi???t
        slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
        //?????i kho???ng tr???ng th??nh k?? t??? g???ch ngang
        slug = slug.replace(/ /gi, "-");
        //?????i nhi???u k?? t??? g???ch ngang li??n ti???p th??nh 1 k?? t??? g???ch ngang
        //Ph??ng tr?????ng h???p ng?????i nh???p v??o qu?? nhi???u k?? t??? tr???ng
        slug = slug.replace(/\-\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-/gi, '-');
        slug = slug.replace(/\-\-/gi, '-');
        //X??a c??c k?? t??? g???ch ngang ??? ?????u v?? cu???i
        slug = '@' + slug + '@';
        slug = slug.replace(/\@\-|\-\@|\@/gi, '');
        //In slug ra textbox c?? id ???slug???
        document.getElementById('alias').value = slug;
        document.getElementById('alias_seo').innerText = url + slug + '.html';
    }
    jQuery(document).ready(function($) {

        var alias = $('.alias');
        var title = $('input[name="title"]');
        var name = $('input[name="name"]');
        var title_seo = $('input[name="title_seo"]');
        var description_seo = $('textarea[name="description_seo"]');
        var data_title_seo = $('input[name="data[title_seo]"]');
        var data_description_seo = $('textarea[name="data[description_seo]"]');

        data_title_seo.keyup(function() {
            /* Act on the event */
            $('.title-seo').html($(this).val());
            return false;
        });

        data_description_seo.keyup(function() {
            /* Act on the event */
            $('.description-seo').html($(this).val());
            return false;
        });
        title_seo.keyup(function() {
            /* Act on the event */
            $('.title-seo').html($(this).val());
            return false;
        });

        description_seo.keyup(function() {
            /* Act on the event */
            $('.description-seo').html($(this).val());
            return false;
        });
        title.on('keyup change',function(){
            var url = "{{route('home')}}/";

            $('.alias-seo').text(url + $(this).val() + '.html');
        });
        name.on('keyup change',function(){
            var url = "{{route('home')}}/";
            $('.alias-seo').text(url + $(this).val() + '.html');
        });
        alias.on('keyup change',function(){
            var url = "{{route('home')}}/";
            $('.alias-seo').text(url + $(this).val() + '.html');
        })

    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('input[name=sort]').keyup(function(){
            url = "{{route('admin.ajax.data.sort')}}";
            id = $(this).attr('data-id');
            num = $(this).val();
            type = $('input.type').val();
            _token = $('input[name=_token]').val();
            $.ajax({
                url:url,
                type:'GET',
                cache:false,
                data:{'_token':_token,'id':id,'num':num,'type':type},
                success:function(data){
                    flash({'message': 'C???p nh???t th??nh c??ng', 'type': 'success'})
                }
            });
        });

        $('.data_status').click(function(){
            url = "{{route('admin.ajax.data.status')}}";
            id = $(this).attr('data-id');
            _token = $('input[name=_token]').val();
            type = $('input.type').val();
            $.ajax({
                url:url,
                type:'GET',
                cache:false,
                data:{'_token':_token,'id':id,'type':type},
                success:function(data){
                    flash({'message': 'C???p nh???t th??nh c??ng', 'type': 'success'})
                }
            });
        });

        $('.data_public').click(function(){
            url = "{{route('admin.ajax.data.public')}}";
            id = $(this).attr('data-id');
            _token = $('input[name=_token]').val();
            type = $('input.type').val();
            $.ajax({
                url:url,
                type:'GET',
                cache:false,
                data:{'_token':_token,'id':id,'type':type},
                success:function(data){
                    flash({'message': 'C???p nh???t th??nh c??ng', 'type': 'success'})
                }
            });
        });
    })
</script>
</body>

</html>
