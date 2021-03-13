@extends('Admin.Layout.layout')
@section('title')
Cập nhật thông tin #{{$user->id}}
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
                            <li class="breadcrumb-item"><a href="{{route('admin.user.index')}}">Danh sách thành viên</a></li>
                            <li class="breadcrumb-item">Cập nhật thông tin</li>
                            <li class="breadcrumb-item active">#{{$user->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Cập nhật thông tin #{{$user->id}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Clickable Wizard -->
        <div class="row">
            <div class="col-md-12">
                <form action="{{route('admin.user.update',$user)}}" method="post" novalidate enctype="multipart/form-data">
                    <div class="card-box" id="wizard-clickable" >
                        @csrf
                        @method('PATCH')
                        <fieldset title="1">
                            <legend>Thông tin đăng nhập</legend>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account">Tài khoản <span class="required">*</span></label>
                                        <input type="text" class="form-control" name="account" id="account" value="{{$user->account ?? old('account')}}" required placeholder="user">
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Tài khoản email <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="email" name="email"  value="{{$user->email ?? old('email')}}" placeholder="nguyenvan@gmail.com">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Mật khẩu </label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="******">
                                    </div>

                                    <div class="form-group">
                                        <label for="re_password">Xác nhận mật khẩu </label>
                                        <input type="password" class="form-control" id="re_password" name="re_password" placeholder="******">
                                    </div>
                                </div>
                            </div>

                        </fieldset>
                        <fieldset title="2">
                            <legend>Thông tin cá nhân</legend>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Họ và tên</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{$user->name ?? old('name')}}" placeholder="Nguyễn Văn A">
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Số điện thoại</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{$user->phone}}" placeholder="0965 688 533">
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="note">Địa chỉ</label>
                                        <textarea name="address" id="address" cols="30" rows="5" class="form-control" placeholder="Số 30, ngõ 19, Hà Đông, Hà Nội">{!! $user->address !!}</textarea>
                                    </div>

                                </div>
                            </div>
                        </fieldset>
                        <fieldset title="3">
                            <legend>Phân quyền module</legend>

                            <div class="row mt-3">
                                @if(Auth::user()->lever <= \App\Enums\LeverUser::ADMIN)
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Quyền quản trị</label>
                                            <div class="input-group">
                                                <select id="level" name="lever" data-toggle="select2" class="form-control" >
                                                    <option value="0">Chọn quyền quản trị</option>
                                                    @if(Auth::user()->lever == \App\Enums\LeverUser::SUPPERADMIN)
                                                        <option value="1" @if($user->lever == \App\Enums\LeverUser::SUPPERADMIN) selected @endif>Administrator</option>
                                                    @endif
                                                    <option value="2" @if($user->lever == \App\Enums\LeverUser::ADMIN) selected @endif>Admin</option>
                                                    <option value="3" @if($user->lever == \App\Enums\LeverUser::EDITER) selected @endif>Editer</option>
                                                    <option value="4" @if($user->lever == \App\Enums\LeverUser::USER) selected @endif>User</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(Auth::user()->lever <= \App\Enums\LeverUser::ADMIN)
                                    <div class="col-md-12">
                                        <div class="form-group"><label class="font-weight-bold text-uppercase">Phân quyền module</label></div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="font-weight-bold text-uppercase">Điều hướng</label>
                                                @foreach($systems->where('parent_id',0)->where('position',0) as $key => $item)
                                                    <div class="item-systems">
                                                        <div class="mb-1 checkbox checkbox-primary">
                                                            <input id="checkbox{{$item->id}}"  {{checked($item->type, $user->systemsModule->pluck('type')->toArray())}}  type="checkbox" name="type[]" value="{{$item->type}}">
                                                            <label for="checkbox{{$item->id}}"><strong>{{$item->name}}</strong> </label>@if($systems->where('parent_id', $item->id)->count())<span class="sub-header" target="#{{$item->id}}"><i class="fe-chevron-down"></i></span> @endif
                                                        </div>
                                                        <div class="sub-systems" id="{{$item->id}}">
                                                            @foreach($systems->where('parent_id', $item->id) as $sub)
                                                                <div class="mb-1 checkbox checkbox-primary">
                                                                    <input id="checkbox{{$sub->id}}" class="prop-checked" {{checked($sub->type, $user->systemsModule->pluck('type')->toArray())}} type="checkbox" name="type[]" value="{{$sub->type}}">
                                                                    <label for="checkbox{{$sub->id}}">
                                                                        <span class="tree-sub"></span>
                                                                        <span>{{$sub->name}}</span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                @endforeach
                                            </div>
                                            <div class="col-md-3">
                                                <label class="font-weight-bold text-uppercase">Bán hàng</label>
                                                @foreach($systems->where('parent_id',0)->where('position',2) as $key => $item)
                                                    <div class="item-systems">
                                                        <div class="mb-1 checkbox checkbox-primary">
                                                            <input id="checkbox{{$item->id}}"  {{checked($item->type, $user->systemsModule->pluck('type')->toArray())}}  type="checkbox" name="type[]" value="{{$item->type}}">
                                                            <label for="checkbox{{$item->id}}"><strong>{{$item->name}}</strong> </label>@if($systems->where('parent_id', $item->id)->count())<span class="sub-header" target="#{{$item->id}}"><i class="fe-chevron-down"></i></span> @endif
                                                        </div>
                                                        <div class="sub-systems" id="{{$item->id}}">
                                                            @foreach($systems->where('parent_id', $item->id) as $sub)
                                                                <div class="mb-1 checkbox checkbox-primary">
                                                                    <input id="checkbox{{$sub->id}}" class="prop-checked" {{checked($sub->type, $user->systemsModule->pluck('type')->toArray())}} type="checkbox" name="type[]" value="{{$sub->type}}">
                                                                    <label for="checkbox{{$sub->id}}">
                                                                        <span class="tree-sub"></span>
                                                                        <span>{{$sub->name}}</span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                @endforeach
                                            </div>
                                            <div class="col-md-3">
                                                <label class="font-weight-bold text-uppercase">Nội dung</label>
                                                @foreach($systems->where('parent_id',0)->where('position',1) as $key => $item)
                                                    <div class="item-systems">
                                                        <div class="mb-1 checkbox checkbox-primary">
                                                            <input id="checkbox{{$item->id}}"  {{checked($item->type, $user->systemsModule->pluck('type')->toArray())}}  type="checkbox" name="type[]" value="{{$item->type}}">
                                                            <label for="checkbox{{$item->id}}"><strong>{{$item->name}}</strong> </label>@if($systems->where('parent_id', $item->id)->count())<span class="sub-header" target="#{{$item->id}}"><i class="fe-chevron-down"></i></span> @endif
                                                        </div>
                                                        <div class="sub-systems" id="{{$item->id}}">
                                                            @foreach($systems->where('parent_id', $item->id) as $sub)
                                                                <div class="mb-1 checkbox checkbox-primary">
                                                                    <input id="checkbox{{$sub->id}}" class="prop-checked" {{checked($sub->type, $user->systemsModule->pluck('type')->toArray())}} type="checkbox" name="type[]" value="{{$sub->type}}">
                                                                    <label for="checkbox{{$sub->id}}">
                                                                        <span class="tree-sub"></span>
                                                                        <span>{{$sub->name}}</span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                @endforeach
                                            </div>

                                            <div class="col-md-3">
                                                <label class="font-weight-bold text-uppercase">Cấu hình</label>
                                                @foreach($systems->where('parent_id',0)->where('position',3) as $key => $item)
                                                    <div class="item-systems">
                                                        <div class="mb-1 checkbox checkbox-primary">
                                                            <input id="checkbox{{$item->id}}"  {{checked($item->type, $user->systemsModule->pluck('type')->toArray())}}  type="checkbox" name="type[]" value="{{$item->type}}">
                                                            <label for="checkbox{{$item->id}}"><strong>{{$item->name}}</strong></label>
                                                            @if($systems->where('parent_id', $item->id)->count())<span class="sub-header" target="#{{$item->id}}"><i class="fe-chevron-down"></i></span> @endif
                                                        </div>
                                                        <div class="sub-systems" id="{{$item->id}}">
                                                            @foreach($systems->where('parent_id', $item->id) as $sub)
                                                                <div class="mb-1 checkbox checkbox-primary">
                                                                    <input id="checkbox{{$sub->id}}" class="prop-checked" {{checked($sub->type, $user->systemsModule->pluck('type')->toArray())}} type="checkbox" name="type[]" value="{{$sub->type}}">
                                                                    <label for="checkbox{{$sub->id}}">
                                                                        <span class="tree-sub"></span>
                                                                        <span>{{$sub->name}}</span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                @endif
                            </div>
                        </fieldset>
                        <button type="submit" class="btn btn-primary stepy-finish"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lai</button>
                    </div>
                    <div class="text-center">
                        <div class="card-box">
                            <a href="{{route('admin.user.index')}}" class="btn btn-purple waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                            <button type="submit" class="btn btn-primary waves-effect width-md waves-light" name="send" value="update"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End row -->
    </div>
    <style>
        .sub-systems {
            display: none;
        }
    </style>
    <script>
        $(document).on('click','.sub-header',function(){
            let target = $(this).attr('target');
            $(target).slideToggle();
        })
    </script>
        @stop

        @section('javascript')
        <!--Form Wizard-->
    <script src="{{asset('admin/assets/libs/stepy/jquery.stepy.js')}}"></script>

    <!-- Validation init js-->
    <script src="{{asset('admin/assets/js/pages/wizard.init.js')}}"></script>

    <!-- Tree view js -->
    <script src="{{asset('admin/assets/libs/treeview/jstree.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/pages/treeview.init.js')}}"></script>

    <script src="https://coderthemes.com/adminox/layouts/vertical/assets/libs/select2/select2.min.js"></script>

    <!-- Init js-->
    <script src="{{asset('admin/assets/js/pages/form-advanced.init.js')}}"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('body').on('change','#lever', function(){
                var lever = $(this).val();
                if(lever > 0 && lever <=2){
                    $('.sub-systems .prop-checked').prop('checked',true);
                }else{
                    $('.sub-systems .prop-checked').prop('checked',false);
                }
            });
        });
    </script>
@stop

@section('css')
    <link href="{{asset('admin/assets/libs/treeview/style.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />

    <style>
        .tree-sub {
            background-image: url(https://coderthemes.com/adminox/layouts/vertical/assets/images/plugins/jstree.png);
            background-position: -132px -4px;
            padding-left: 30px;
            width: 24px;
            height: 28px;
            line-height: 28px;
            font-size: 15px;
            overflow: hidden;
        }
    </style>
@stop
