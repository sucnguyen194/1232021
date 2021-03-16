@extends('Layouts.layout')
@section('title') {{$product->title_seo}} @stop
@section('url') {{route('alias',$product->alias)}} @stop
@section('description') {{$product->description_seo}} @stop
@section('keywords') {{$product->keyword_seo}} @stop
@section('site_name') {{$product->title_seo}} @stop
@section('image') {{asset($product->image ?? $setting->og_image)}} @stop
@section('content')
{{redirect_lang($product->alias)}}
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<style type="text/css">

</style>
<div class="container">
    @include('include.comment')
</div>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
    $('.box-comment').hide();
    function openComment(id, name){
        $('.box-comment').hide();
        let box = $('.box-comment[target="'+id+'"]');
        box.show();
        box.find('textarea').val('@'+name+': ');
    }
</script>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
@stop
