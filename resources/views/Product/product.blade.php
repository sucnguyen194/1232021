@extends('Layouts.layout')
@section('title') {{$product->title_seo}} @stop
@section('url') {{route('alias',$product->alias)}} @stop
@section('description') {{$product->description_seo}} @stop
@section('keywords') {{$product->keyword_seo}} @stop
@section('site_name') {{$product->title_seo}} @stop
@section('image') {{asset($product->image ?? setting()->og_image)}} @stop
@section('lang') {{redirect_lang($product->alias)}} @stop
@section('content')
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card-box">
                    <label>Bộ lọc</label>
                    <hr>
                    @foreach(\App\Models\AttributeCategory::public()->oldest('sort')->get() as $attribute)
                        <div class="form-group">
                            <label>{{$attribute->name}}</label>
                            @foreach($attribute->attributes as $item)
                                <div class="item">
                                    @if(!disable($item->name, explode(',',request()->attribute)))
                                    <a href="{{route('alias',$product->alias)}}?attribute={{request()->attribute ? request()->attribute .','.$item->name : $item->name}}">{{$item->name}}</a>
                                    @else
                                        <a href="javascript:void(0)">{{$item->name}}</a>
                                    @endif
                                    <a class="small text-danger" href="{{route('alias',$product->alias)}}?attribute={{request()->attribute ? request()->attribute .','.$item->name : $item->name}}">Xóa</a>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-9">

            </div>
        </div>
</div>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
@stop
