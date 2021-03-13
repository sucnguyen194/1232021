@extends('Admin.Layout.layout')
@section('title')
Thay đổi mật khẩu
@stop
@section('content')
<div class="">
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_content">
          <form class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
          </p>
          <span class="section">Thay đổi mật khẩu</span>
          @include('errors.note')
          <div class="col-md-8 col-xs-12">
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Mật khẩu cũ</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input class="form-control col-md-7 col-xs-12" name="oldpassword" type="password" required>
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Mật khẩu mới<span class="required">*</span></label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="password" id="password" name="password" class="form-control col-md-7 col-xs-12" required>
              </div>
            </div>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="re_password">Nhập lại mật khẩu <span class="required">*</span></label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="password" id="re-password" name="re_password" class="form-control col-md-7 col-xs-12" required>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="ln_solid"></div>
          <div class="form-group text-center">
            <div class="col-md-6 col-md-offset-3">
              <button id="submit" name="submit" type="submit" class="btn btn-success"/><span class="icon-button"><i class="fa fa-refresh"></i><span>  Cập nhật</button>
              </div>
            </div>
            {{csrf_field()}}
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
