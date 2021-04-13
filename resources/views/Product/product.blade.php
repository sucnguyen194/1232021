@extends('Layouts.layout')
@section('title') {{$product->title_seo}} @stop
@section('url') {{route('alias',$product->alias)}} @stop
@section('description') {{$product->description_seo}} @stop
@section('keywords') {{$product->keyword_seo}} @stop
@section('site_name') {{$product->title_seo}} @stop
@section('image') {{asset($product->image ?? $setting->og_image)}} @stop
@section('lang') {{redirect_lang($product->alias)}} @stop
@section('content')
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<div class="container">
    @include('include.comment')
</div>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
@stop
