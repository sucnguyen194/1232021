@extends('Layouts.layout')
@section('title') Thông tin tài khoản @stop
@section('url') {{url('register')}} @stop
@section('description') {!!$site_option->description_seo!!} @stop
@section('keyword') {!!$site_option->keyword_seo!!} @stop
@section('image') {{$image}} @stop
@section('content')
<?php
use App\ model\ FHomeModel;
$user = new FHomeModel();
?>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<main>
	<section class="breadcrumb-all position-relative" style="background-image: url(assets/images/bg-side.jpg)">
		<div class="bg-color-breadcrumb">
			<div class="set-position-breadcrums">
				<div class="container">
					<div class="row">
						<div class="col-md-5 col-xs-12">
							<div class="title-left text-left">Thông tin tài khoản</div>
						</div>
						<div class="col-md-7 col-xs-12 text-right breadcrumb-right">
							<a href="{{url()}}">Trang chủ</a>
							<span>/</span>
							<span>Thông tin tài khoản</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="content-side mt-30">
		<div class="container">
			<div class="title-side mb-30">
				<h1>Thông tin tài khoản</h1>
				<div class="line"></div>
			</div>
			<div class="row">
				<div class="form-log mb-30 col-md-4 col-md-offset-4">
					<form method="post" action="">
						<div class="form-group">
							<label>Tài khoản <span class="required">*</span></label>
							<input type="text" name="account" id="name" value="{{Session::get('user')->account}}" required class="form-control">
						</div>
						<div class="form-group">
							<label>Mật khẩu<span class="required">*</span></label>
							<input type="password" name="password" id="password" placeholder="*********" class="form-control">
						</div>
						<div class="form-group">
							<label> Nhập lại mật khẩu</label>
							<input type="password" name="re_password" id="re_password" placeholder="*********" class="form-control">
						</div>
						<div class="form-group">
							<label>Email <span class="required">*</span></label>
							<input type="email" name="email" value="{{Session::get('user')->email}}" required="required" class="form-control">
						</div>
						<div class="form-group">
							<label>Họ & tên</label>
							<input type="text" name="name" value="{{Session::get('user')->name}}" class="form-control">
						</div>
						<div class="form-group">
							<label>Số điện thoại</label>
							<input type="text" name="phone" value="{{Session::get('user')->phone}}" class="form-control">
						</div>
						<div class="form-group">
							<label>Địa chỉ</label>
							<input type="text" name="address" value="{{Session::get('user')->address}}" class="form-control">
						</div>
						<div class="form-group text-right">
							<input type="submit" name="submit" value="Sửa thông tin" class="btn btn-warning">
						</div>
						{{csrf_field()}}
					</form>

				</div>

			</div>
		</div>
	</section>
</main>
<script type="text/javascript">

</script>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
@stop




