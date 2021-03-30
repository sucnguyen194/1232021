@extends('Admin.Layout.layout')
@section('title')
Danh sách menu
@stop
@section('content')

<div class="container-fluid">
        @csrf
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Bảng điều khiển</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Menu</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="text-left" id="nestable_list_menu">
                    <div class="action-datatable text-right mb-2">
                        <a href="{{route('admin.menus.create')}}" class="btn btn-primary waves-effect width-md waves-light">
                            <span class="icon-button"><i class="fe-plus pr-1"></i></span> Thêm mới</a>
                        <textarea id="nestable-output" name="menuval" style="display: none;"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <!-- End row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-box">
                            <h4 class="header-title">Thêm nhanh</h4>
                            <p class="sub-header mb-0">
                                * Phía dưới gồm các danh mục sản phẩm, bài viết, page... Click vào tên danh mục, bài viết... sẽ được thêm trực tiếp vào menu.
                            </p>
                        </div>
                            <div class="card-box">
                                <h4 class="header-title mb-3"><b>Danh mục sản phẩm</b></h4>
                                <div class="form-group">
                                    @foreach($product_category->where('parent_id', 0) as $item)
                                        <label class="w-100"><a href="javascript:void(0)" class="addmenu text-secondary"  title='Thêm ::::::{{$item->name}}:::::: vào menu'charset=""  data-name="{{$item->name}}" data-url="{{$item->alias}}" data-image="{{$item->image}}" data-thumb="{{$item->thumb}}"><span class=""><i class="fe-plus pr-1"></i>  {{$item->name}}</span></a></label>
                                        {{sub_menu_category_checkbox($product_category,$item->id)}}
                                    @endforeach
                                </div>
                            </div>

                            <div class="card-box">
                                <h4 class="header-title mb-3"><b>Danh mục Blog</b></h4>
                                <div class="form-group">
                                    @foreach($news_category->where('parent_id', 0) as $item)
                                       <label class="w-100"><a href="javascript:void(0)" class="addmenu text-secondary"  title='Thêm ::::::{{$item->title}}:::::: vào menu'charset=""  data-name="{{$item->title}}" data-url="{{$item->alias}}" data-image="{{$item->image}}" data-thumb="{{$item->thumb}}"><span class=""><i class="fe-plus pr-1"></i>  {{$item->title}}</span></a></label>
                                        {{sub_menu_category_checkbox($news_category,$item->id)}}
                                    @endforeach
                                </div>
                            </div>

                            <div class="card-box">
                                <h4 class="header-title mb-3"><b>Pages</b></h4>
                                <div class="form-group">
                                    @foreach($pages as $item)
                                        <label class="w-100"><a href="javascript:void(0)" class="addmenu text-secondary"  title='Thêm ::::::{{$item->title}}:::::: vào menu'charset=""  data-name="{{$item->title}}" data-url="{{$item->alias}}" data-image="{{$item->image}}" data-thumb="{{$item->thumb}}"><span class=""><i class="fe-plus pr-1"></i>  {{$item->title}}</span></a></label>
                                    @endforeach
                                </div>
                            </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-box">
                            <h4 class="header-title"><b>Danh sách menu</b></h4>
                            <p class="sub-header">
                                * Danh sách menu tùy thuộc vào "vị trí" hiển thị tại thời điển hiện tại (top, bottom, left,right,home).
                            </p>

                            <div class="form-group">
                                <label><strong class="text-tranform">VI TRÍ</strong></label>
                                <select id="position" class="form-control" data-toggle="select2">
                                    <option value="top" @if(Session::get('menu_position') == 'top') selected @endif class="form-control">MENU TOP</option>
                                    <option value="home" @if(Session::get('menu_position') == 'home') selected @endif class="form-control">MENU HOME</option>
                                    <option value="left" @if(Session::get('menu_position') == 'left') selected @endif class="form-control">MEN LEFT</option>
                                    <option value="right" @if(Session::get('menu_position') == 'right') selected @endif class="form-control">MENU RIGHT</option>
                                    <option value="bottom" @if(Session::get('menu_position') == 'bottom') selected @endif class="form-control">MENU BOTTOM</option>
                                </select>
                            </div>

                            <div class="custom-dd dd" id="nestable">
                                <ol class="dd-list" id="result_data">
                                    @foreach($menus->where('parent_id', 0) as $items)
                                    <li class="dd-item" data-id="{{$items->id}}">
                                        <div class="dd-handle">
                                            <i class="fa fa-star pr-1" aria-hidden="true"></i> {{$items->name}}
                                        </div>

                                           <div class="menu_action">
                                               <a href="{{route('admin.menus.edit',$items)}}" title="Sửa" class="btn btn-primary waves-effect waves-light"><i class="fe-edit-2"></i></a>
                                               <form method="post" action="{{route('admin.menus.destroy',$items)}}" class="d-inline-block">
                                                   @method('DELETE')
                                                   @csrf
                                                   <button type="submit" onclick="return confirm('Bạn chắc chắn muốn xóa?')" class="btn btn-primary waves-effect waves-light"><i class="fe-x"></i></button>
                                               </form>
                                           </div>

                                        <ol class="dd-list">
                                            {{admin_menu_sub($menus, $items->id)}}
                                        </ol>

                                    </li>
                                    @endforeach

                                </ol>
                            </div>

                        </div><!-- end col -->
                    </div>
                </div> <!-- end row -->
            </div> <!-- end col -->
        </div>
        <!-- end Row -->

    </div> <!-- end container-fluid -->

@stop
@section('css')
    <style>
        .dd {
            max-width: 100%!important;
        }
    </style>
    <style type="text/css" media="screen">
        .list__menu{
            position: relative;
        }
        .list__menu label.control-label, .list__menu label a {
            display: block;

        }
        .list__menu .text-right{
            float: right;
            cursor: pointer;
            position: absolute;
            right: 15px;
        }
        .tab__menu{
            margin-left: 15px;

        }
        .box-shadow {
            box-shadow: 2px 2px 2px 2px #d58512
        }
    </style>

    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('admin/assets/libs/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />

    <!-- Summernote css -->
    <link href="{{asset('admin/assets/libs/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css" />
    <!-- Plugins css -->
    <link href="/admin/assets/libs/nestable2/jquery.nestable.min.css" rel="stylesheet" type="text/css" />
@stop
@section('javascript')

    <script type="text/javascript">
        $(document).ready(function(){
            $('.addmenu').click(function(){
                name = $(this).attr('data-name');
                url = $(this).attr('data-url');
                image = $(this).attr('data-image');
                thumb = $(this).attr('data-thumb');
                _token = $('input[name="_token"]').val();
                local = '{{route('admin.ajax.add.menu')}}';
                $.ajax({
                    url:local,
                    type:'post',
                    cache:false,
                    data:{
                        'name':name,'url':url,'image':image,'thumb':thumb,'_token':_token,
                    },
                    success:function(result){
                        //obj = JSON.parse(result);
                        $('#result_data').append(result);
                        $('.dd-empty').remove();
                        flash('success','Thêm mới thành công');
                    }
                })
            })


            $('#position').change(function(){
                position = $(this).val();
                url = "{{route('admin.change.position.menu',":position")}}";
                url = url.replace(':position', position);

                window.location.href = url;
            });

            var updateOutput = function(e)
            {
                var list   = e.length ? e : $(e.target),
                    output = list.data('output');
                if (window.JSON) {
                    output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
                } else {
                    output.val('JSON browser support required for this demo.');
                }
            };
            // activate Nestable for list 1
            $('#nestable').nestable({
                group: 1,
                maxDepth:10
            })
                .on('change', updateOutput);
            // output initial serialised data
            updateOutput($('#nestable').data('output', $('#nestable-output')));

            $('#nestable').change(function(){
                val = $('#nestable-output').val();
                _token = $('input[name="_token"]').val();
                url = "{{route('admin.ajax.menu.sort')}}";
                $.ajax({
                    url:url,
                    type:"get",
                    data:{"val":val,'_token':_token},
                    cache:false,
                    success:function(result, status){
                        // flash('success','Cập nhật thành công');
                    }
                });
            });
        });
    </script>

    <script src="{{asset('admin/assets/libs/switchery/switchery.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}"></script>
    <script src="https://coderthemes.com/adminox/layouts/vertical/assets/libs/select2/select2.min.js"></script>
{{--    <script src="{{asset('admin/assets/libs/jquery-mockjax/jquery.mockjax.min.js')}}"></script>--}}
    <script src="{{asset('admin/assets/libs/autocomplete/jquery.autocomplete.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-filestyle2/bootstrap-filestyle.min.js')}}"></script>

    <!-- Init js-->
    <script src="{{asset('admin/assets/js/pages/form-advanced.init.js')}}"></script>

    <!-- Summernote js -->
    <script src="{{asset('admin/assets/libs/summernote/summernote-bs4.min.js')}}"></script>

    <!-- Init js -->
    <script src="{{asset('admin/assets/js/pages/form-summernote.init.js')}}"></script>

    <!-- Plugins js-->
    <script src="/admin/assets/libs/nestable2/jquery.nestable.min.js"></script>

    <!-- Nestable init-->
{{--    <script src="/admin/assets/js/pages/nestable.init.js"></script>--}}
@stop

