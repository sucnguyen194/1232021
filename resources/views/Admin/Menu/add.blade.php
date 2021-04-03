@extends('Admin.Layout.layout')
@section('title')
Thêm mới
@stop
@section('content')

    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Bảng điều khiển</a></li>
                            <li class="breadcrumb-item"><a href="{{route('admin.menus.index')}}">Danh sách menu</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Thêm mới</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <a href="{{route('admin.menus.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                    <button type="submit" class="btn btn-primary waves-effect width-md waves-light float-right" onclick="document.querySelector('#form-store').submit()" name="send" value="save"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card-box">
                            <h4 class="header-title"><b>Thêm nhanh</b></h4>
                            <p class="sub-header mb-0">
                                * Phía dưới gồm các danh mục sản phẩm, bài viết, page... Click vào tên danh mục, bài viết... sẽ được thêm trực tiếp vào menu.
                            </p>
                        </div>

                        <div class="card-box">
                            <h4 class="header-title mb-3"><b>Danh mục sản phẩm</b></h4>
                            <div class="form-group mb-0">
                                @foreach($categories->where('parent_id', 0)->where('type',\App\Enums\SystemsModuleType::PRODUCT_CATEGORY) as $item)
                                    <label class="w-100"><a href="javascript:void(0)" class="addmenu text-secondary"  title='Thêm ::::::{{$item->name}}:::::: vào menu' charset=""  data-name="{{$item->name}}" data-url="{{$item->alias}}" data-image="{{$item->image}}" data-thumb="{{$item->thumb}}"><span class=""><i class="fe-plus pr-1"></i>  {{$item->name}}</span></a></label>
                                    {{sub_menu_category_checkbox($categories,$item->id)}}
                                @endforeach
                            </div>
                        </div>

                        <div class="card-box">
                            <h4 class="header-title mb-3"><b>Danh mục Blog</b></h4>
                            <div class="form-group mb-0">
                                @foreach($categories->where('parent_id', 0)->where('type',\App\Enums\SystemsModuleType::POST_CATEGORY) as $item)
                                    <label class="w-100"><a href="javascript:void(0)" class="addmenu text-secondary"  title='Thêm ::::::{{$item->title}}:::::: vào menu'charset=""  data-name="{{$item->title}}" data-url="{{$item->alias}}" data-image="{{$item->image}}" data-thumb="{{$item->thumb}}"><span class=""><i class="fe-plus pr-1"></i>  {{$item->title}}</span></a></label>
                                    {{sub_menu_category_checkbox($categories,$item->id)}}
                                @endforeach
                            </div>
                        </div>

                        <div class="card-box">
                            <h4 class="header-title mb-3"><b>Pages</b></h4>
                            <div class="form-group mb-0">
                                @foreach($pages as $item)
                                    <label class="w-100"><a href="javascript:void(0)" class="addmenu text-secondary"  title='Thêm ::::::{{$item->title}}:::::: vào menu'charset=""  data-name="{{$item->title}}" data-url="{{$item->alias}}" data-image="{{$item->image}}" data-thumb="{{$item->thumb}}"><span class=""><i class="fe-plus pr-1"></i>  {{$item->title}}</span></a></label>
                                @endforeach
                            </div>
                        </div>


                    </div>
                    <div class="col-md-4">
                        <div class="card-box">
                            <h4 class="header-title"><b>Danh sách menu</b></h4>
                            <p class="sub-header mb-0">
                                * Danh sách menu tùy thuộc vào "vị trí" hiển thị tại thời điển hiện tại (top, bottom, left,right,home).
                            </p>
                        </div>
                            <div class="card-box">
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
                                                        <button type="submit" onclick="return confirm('Bạn chắc chắn muốn xóa?')" class="btn btn-warning waves-effect waves-light"><i class="fe-x"></i></button>
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

                    <div class="col-md-4">

                        <form method="post" action="{{route('admin.menus.store')}}" id="form-store" enctype="multipart/form-data">
                            @csrf
                        <div class="card-box">
                                <div class="form-group">
                                    <label>Vị trí</label>
                                    <select id="position" class="form-control" data-toggle="select2">
                                        <option value="top" @if(Session::get('menu_position') == 'top') selected @endif class="form-control">MENU TOP</option>
                                        <option value="home" @if(Session::get('menu_position') == 'home') selected @endif class="form-control">MENU HOME</option>
                                        <option value="left" @if(Session::get('menu_position') == 'left') selected @endif class="form-control">MEN LEFT</option>
                                        <option value="right" @if(Session::get('menu_position') == 'right') selected @endif class="form-control">MENU RIGHT</option>
                                        <option value="bottom" @if(Session::get('menu_position') == 'bottom') selected @endif class="form-control">MENU BOTTOM</option>
                                    </select>
                                    <textarea id="nestable-output" name="menuval" style="display: none;"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Tiêu đề</label>
                                    <input id="title" class="form-control" onkeyup="ChangeToSlug()" name="data[name]" type="text">
                                </div>
                                <div class="form-group">
                                    <label>Đường dẫn</label>
                                    <input id="alias" class="form-control" name="data[url]" type="text">
                                </div>

                                <div class="form-group">
                                    <label>Danh mục cha</label>
                                    <select id="parent_id" name="data[parent_id]" class="form-control" data-toggle="select2">
                                        <option value="0">-----</option>
                                        @foreach($menus->where('parent_id', 0) as $items)
                                          <option value="{{$items->id}}">{{$items->name}}</option>
                                           {{sub_add_menu($menus,$items->id)}}
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <textarea name="description" id="summernote" rows="6" class="form-control summernote"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Target</label>

                                    <select id="target" name="data[target]" class="form-control" data-toggle="select2">
                                        <option value="_self">-----</option>
                                        <option value="_parent">_parent</option>
                                        <option value="_top">_top</option>
                                        <option value="_blank">_blank</option>
                                        <option value="_self">_self</option>
                                    </select>

                                </div>

                                <div class="form-group">
                                    <label>Kiểu menu</label>
                                    <select id="type" name="data[type]" class="form-control" data-toggle="select2">
                                        <option value="default">Default</option>
                                        <option value="mega">Mega Menu</option>
                                    </select>
                                </div>
                            <div class="position-relative box-action-image">
                                <label>Icon</label>
                                <div class="position-absolute font-weight-normal text-primary" id="box-input" style="right:0;top:0">
                                    <label class="item-input">
                                        <input type="file" name="image" class="d-none" id="fileUpload"> Chọn ảnh
                                    </label>
                                </div>
                                <p class="font-13">* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif</p>
                                <div class="dropzone p-2 text-center image-holder" id="image-holder">
                                    <label for="fileUpload" class="w-100 mb-0">
                                        <div class="icon-dropzone pt-2">
                                            <i class="h1 text-muted dripicons-cloud-upload"></i>
                                        </div>
                                        <span class="text-muted font-13">Sử dụng nút <strong>Chọn ảnh</strong> để thêm ảnh</span>
                                    </label>
                                </div>
                                <div class="box-position btn btn-default waves-effect waves-light text-left show-box">
                                    <div class="checkbox checkbox-unlink-image">
                                        <input id="checkbox_unlink" class="unlink-image" type="checkbox" name="unlink">
                                        <label for="checkbox_unlink" class="mb-0">Xóa ảnh</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="">
                                <a href="{{route('admin.menus.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                                <button type="submit" class="btn btn-primary waves-effect width-md waves-light float-right" name="send" value="save"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                            </div>
                        </form>
                    </div>
                </div> <!-- end row -->
            </div> <!-- end col -->
        </div>
        <!-- end Row -->

    </div> <!-- end container-fluid -->
    <script>
        CKEDITOR.replace('summernote',{
            height:150
        })
    </script>
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
{{--    <link href="{{asset('admin/assets/libs/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css" />--}}
    <!-- Plugins css -->
    <link href="/admin/assets/libs/nestable2/jquery.nestable.min.css" rel="stylesheet" type="text/css" />
@stop
@section('javascript')

    <script type="text/javascript">
        $(document).ready(function(){
            $('.addmenu').click(function(){
                name = $(this).attr('data-name');
                url = $(this).attr('data-url');
                _token = $('input[name="_token"]').val();
                local = '{{route('admin.ajax.add.menu')}}';
                $.ajax({
                    url:local,
                    type:'post',
                    cache:false,
                    data:{
                        'name':name,'url':url,'_token':_token,
                    },
                    success:function(result){
                        //obj = JSON.parse(result);
                        $('#result_data').append(result);
                        $('.dd-empty').remove();
                        flash({'message':'Thêm mới thành công!', 'type': 'success'});
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
                    success:function(result){
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

{{--    <!-- Summernote js -->--}}
{{--    <script src="{{asset('admin/assets/libs/summernote/summernote-bs4.min.js')}}"></script>--}}

{{--    <!-- Init js -->--}}
{{--    <script src="{{asset('admin/assets/js/pages/form-summernote.init.js')}}"></script>--}}

    <!-- Plugins js-->
    <script src="/admin/assets/libs/nestable2/jquery.nestable.min.js"></script>

    <!-- Nestable init-->
    {{--    <script src="/admin/assets/js/pages/nestable.init.js"></script>--}}
@stop
