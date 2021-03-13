@extends('Layouts.layout')
 @section('title') {{lang('Tìm kiếm','Search')}} @stop
 @section('content')
 <?php use App\model\FHomeModel;
 $user = new FHomeModel();
 ?>
 <!-- main -->
 <div id="main">

 	<div class="container mt-3">
 		<div class="row">
 			<div class="col-md-12 breadcrumb">
 				<small>
 					<a href="{{url()}}" title="Trang chủ"><i class="fas fa-home"></i></a>
 					<i class="fas fa-angle-right"></i>

 					<a href="javascript:void(0)" title="Tìm kiếm">Tìm kiếm</a>
 				</small>
 			</div>
 		</div>
 	</div>

 	<div class="container">
 		<div class="row">
 			<div class="col-md-9">
 				<h3 class="title">Tìm kiếm</h3>

 				<div class="row mt-5">
 					@foreach($product as $item)
 					<div class="col-md-4 col-6 mb-3">
 						@include('include.item-product')
 					</div>
 					@endforeach
 					<div class="col-md-12 col-12 paging">{!!str_replace('/?','?',$product->render())!!}</div>
 				</div>

 			</div>
 			<div class="col-md-3">
 				@include('include.right')
 			</div>
 		</div>
 	</div>
 	@include('include.footer')
 </div>
</div>
</div>
<!-- end main -->
{{getDataLang(0,'tags')}}
@end
