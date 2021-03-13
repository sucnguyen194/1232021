@extends('Layouts.layout')
@section('title') {!!$gallery->title!!} @stop
@section('url') {{url($gallery->alias)}} @stop
@section('description') {!!$gallery->title!!} @stop
@section('keywords') {!!$gallery->title!!} @stop
@section('content')
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.6.11/css/lightgallery.css">
<style>

	.demo-gallery > ul {
		margin-bottom: 0;
		padding-left: 0px;
	}

	.demo-gallery > ul > li {
		margin-bottom: 5px;
		width: 100%;
		display: inline-block;
		margin-right: 15px;
		list-style: outside none none;
	}

	.demo-gallery > ul > li a {
		border: 3px solid #FFF;
		border-radius: 3px;
		display: block;
		overflow: hidden;
		position: relative;
		float: left;
		width: 100%;
	}

	.demo-gallery > ul > li a > img {
		-webkit-transition: -webkit-transform 0.15s ease 0s;
		-moz-transition: -moz-transform 0.15s ease 0s;
		-o-transition: -o-transform 0.15s ease 0s;
		transition: transform 0.15s ease 0s;
		-webkit-transform: scale3d(1, 1, 1);
		transform: scale3d(1, 1, 1);
		margin: auto;
	}

	.demo-gallery > ul > li a:hover > img {
		-webkit-transform: scale3d(1.1, 1.1, 1.1);
		transform: scale3d(1.1, 1.1, 1.1);
	}

	.demo-gallery > ul > li a:hover .demo-gallery-poster > img {
		opacity: 1;
	}

	.demo-gallery > ul > li a .demo-gallery-poster {
		background-color: rgba(0, 0, 0, 0.1);
		bottom: 0;
		left: 0;
		position: absolute;
		right: 0;
		top: 0;
		-webkit-transition: background-color 0.15s ease 0s;
		-o-transition: background-color 0.15s ease 0s;
		transition: background-color 0.15s ease 0s;
	}

	.demo-gallery > ul > li a .demo-gallery-poster > img {
		left: 50%;
		margin-left: -10px;
		margin-top: -10px;
		opacity: 0;
		position: absolute;
		top: 50%;
		-webkit-transition: opacity 0.3s ease 0s;
		-o-transition: opacity 0.3s ease 0s;
		transition: opacity 0.3s ease 0s;
	}

	.demo-gallery > ul > li a:hover .demo-gallery-poster {
		background-color: rgba(0, 0, 0, 0.5);
	}

	.demo-gallery .justified-gallery > a > img {
		-webkit-transition: -webkit-transform 0.15s ease 0s;
		-moz-transition: -moz-transform 0.15s ease 0s;
		-o-transition: -o-transform 0.15s ease 0s;
		transition: transform 0.15s ease 0s;
		-webkit-transform: scale3d(1, 1, 1);
		transform: scale3d(1, 1, 1);
		height: 100%;
		width: 100%;
	}

	.demo-gallery .justified-gallery > a:hover > img {
		-webkit-transform: scale3d(1.1, 1.1, 1.1);
		transform: scale3d(1.1, 1.1, 1.1);
	}

	.demo-gallery .justified-gallery > a:hover .demo-gallery-poster > img {
		opacity: 1;
	}

	.demo-gallery .justified-gallery > a .demo-gallery-poster {
		background-color: rgba(0, 0, 0, 0.1);
		bottom: 0;
		left: 0;
		position: absolute;
		right: 0;
		top: 0;
		-webkit-transition: background-color 0.15s ease 0s;
		-o-transition: background-color 0.15s ease 0s;
		transition: background-color 0.15s ease 0s;
	}

	.demo-gallery .justified-gallery > a .demo-gallery-poster > img {
		left: 50%;
		margin-left: -10px;
		margin-top: -10px;
		opacity: 0;
		position: absolute;
		top: 50%;
		-webkit-transition: opacity 0.3s ease 0s;
		-o-transition: opacity 0.3s ease 0s;
		transition: opacity 0.3s ease 0s;
	}

	.demo-gallery .justified-gallery > a:hover .demo-gallery-poster {
		background-color: rgba(0, 0, 0, 0.5);
	}

	.demo-gallery .video .demo-gallery-poster img {
		height: 48px;
		margin-left: -24px;
		margin-top: -24px;
		opacity: 0.8;
		width: 48px;
	}

	.demo-gallery.dark > ul > li a {
		border: 3px solid #04070a;
	}
</style>
<main>
	<section class="breadcrumb-all position-relative" style="background-image: url(assets/images/bg-side.jpg)">
		<div class="bg-color-breadcrumb">
			<div class="set-position-breadcrums">
				<div class="container">
					<div class="row">
						<div class="col-md-5 col-xs-12">
							<div class="title-left text-left">Thư viện hình ảnh</div>
						</div>
						<div class="col-md-57 col-xs-12 text-right breadcrumb-right">
							<a href="{{url()}}">Trang chủ</a>
							<span>/</span>
							<a href="{{url('gallery.html')}}">Thư viện hình ảnh</a>
							<span>/</span>
							<span>{{$gallery->title}}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="content-side mt-30">
		<div class="container">
			<div class="title-side mb-30"><h1>{{$gallery->title}}</h1><div class="line"></div></div>
			<div class="detail-item-side mb-30">
				{!!$gallery->description!!}
			</div>
			<div class="list-photo">
				<div class="demo-gallery">
					<ul id="lightgallery" class="list-unstyled">
						@foreach($photo as $item)
						<li class="item" data-responsive="{{$item->link}} 375, {{$item->link}} 480, {{$item->link}} 800" data-src="{{$item->link}}" data-sub-html="{{$item->title}}">
							<a href="javascript:void(0)">
								<img class="img-responsive" alt="{{$item->title}}" src="{{$item->link}}">
							</a>
						</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</section>
</main>
<style>
	#lightgallery .item {
		margin-bottom: 15px;
	}

</style>
<script type="text/javascript">
	$( document ).ready( function () {
		$( '#lightgallery' ).lightGallery();
	} );
</script>
<script src="https://cdn.jsdelivr.net/picturefill/2.3.1/picturefill.min.js"></script>
<script src="http://kientrucnoithatdep.vn/public/frontend/js/lightgallery-all.min.js"></script>
<script src="http://kientrucnoithatdep.vn/public/frontend/js/jquery.mousewheel.min.js"></script>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<input type="hidden" id="getDataLang" data-id="{{$gallery->id}}" data-type="gallery">
@stop
