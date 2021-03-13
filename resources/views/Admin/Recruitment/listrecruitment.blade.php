@extends('admin.layout.layout')
@section('title') Danh sách tuyển dụng @stop
@section('content')
<form name="formbk" method="post" action="">
  <div class="">
    <div class="form-group"><a class="btn btn-success" href="{{url('admin/add-recruitment')}}"><span class="icon-button"><i class="fa fa-plus-circle"></i></span> Thêm mới</a>
      <button class="btn btn-danger" type="submit" name="delall"  onclick="return confirm('Bạn chắc chắn muốn xóa!')"><span class="icon-button"><i class="fa fa-trash-o"></i></span> Xóa tất cả </button>
   </div>
    <div class="clearfix"></div>
    @include('errors.note')
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2> Danh sách tuyển dụng</small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
              <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Settings 1</a> </li>
                  <li><a href="#">Settings 2</a> </li>
                </ul>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a> </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content table-responsive">
            <table id="datatable" class="table table-striped table-bordered bulk_action dataTable no-footer">
              <thead>
                <tr>
                  <?php $disabled = count($list_recruitment) == 0 ? "disabled" : ""; ?>
                  <th><input type="checkbox" name="checkAll" id="check-all" {{$disabled}} class="input-checked"></th>  
                  <th style="width:3%">ID</th>
                  <th>Sắp xếp</th>
                  <th>Vị trí</th>
                  <th>Danh mục</th>
                  <th>Hạn nộp hồ sơ</th>
                  <th>Hiển thị</th>
                  <th>Ngày đăng</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($list_recruitment as $items)
                <tr>
                  <th><input type="checkbox" class="check_del flat" name="check_del[]" value="{{$items->id}}"></th>
                  <td>{{$items->id}}</td>
                  <td><input style="width:50px;" type="text" id_news="{{$items->id}}" name="sort" value="{{$items->sort}}"></td>
                  <td>{{$items->title}}</td>
                  <td>@if($items->category_id > 0) {{$items->cate_title}} @endif</td>
                  <td>{{$items->time_out}}</td>
                  <td><div class="form-group">
                    <label class="label-control">
                      <input type="checkbox" name="home" id_news="{{$items->id}}" class="input-checked" @if($items->
                      home == 1) checked @endif title="Trang chủ"> Tin nổi bật</label>
                      <label class="label-primary col-md-12 col-xs-12 col-sm-12" style="color:#fff; display:none">Nổi bật
                        <input type="checkbox" name="hot" id_news="{{$items->id}}" title="Hot" @if($items->
                        hot == 1) checked @endif></label>
                        <div class="clearfix"></div>
                        <label class="label-warning col-md-12 col-xs-12 col-sm-12" style="color:#fff ; display:none">Tiêu điểm
                          <input type="checkbox" name="focus" id_news="{{$items->id}}" title="Nổi bật" @if($items->
                          focus == 1) checked @endif> </label>
                        </div></td>
                        <td>{{date('d-m-Y',$items->time+7*3600)}}</td>

                        <td><a href="{{url('admin/edit-recruitment/'.$items->id)}}" class="btn btn-warning btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a href="{{url('admin/del-recruitment/'.$items->id)}}" onclick="return confirm('Bạn chắc chắn muốn xóa!')" class="btn btn-danger btn-xs"><i class="fa fa-times" aria-hidden="true"></i></a>
                       <a href="#modal-language-{{$items->id}}" data-toggle="modal" title="Chỉnh sửa ngôn ngữ" class="btn btn-info btn-xs"><i class="fa fa-language" aria-hidden="true"></i></a>
                  </td>
                </tr>
                <div class="modal fade" id="modal-language-{{$items->id}}">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Ngôn ngữ: #{{$items->title}}</h4>
                      </div>
                      <div class="modal-body text-center">
                       <?php $getLang = DB::table('lang')->select('*')->where('value','<>',Session::get('lang'))->get();
                          $type = 'page';
                          ?>
                          @foreach($getLang as $item)
                          @if(Helper::check_post_lang($items->id,$type,$item->value))
                          <a href="{{url('admin')}}/edit-recruitment/{{Helper::get_post_lang($items->id,$type,$item->value)}}" class="btn btn-warning"><span class="icon-button"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> Edit {{$item->name}}</a>
                          @else
                          <a href="{{url('admin')}}/add-recruitment/{{$item->value}}/{{$items->id}}" class="btn btn-primary"><span class="icon-button"><i class="fa fa-plus-circle"></i></span> Add {{$item->name}}</a>
                          @endif
                          @endforeach
                      </div>
                    </div>
                  </div>
                </div>
                    
                      @endforeach
                    </tbody>
                    
                  </table>
                  <div class="paging"> {!!str_replace('/?','?',$list_recruitment->appends(['gid' => @$_GET['gid']])->render())!!} </div>
                  <div class="text-center loading" style="display: none;"> <img src="{{url('public/images/spinner.gif')}}" style="max-height: 80px;"> </div>
                  <script type="text/javascript">
                    function DoCheck(status,FormName,from_)
                    {
                     var alen=eval('document.'+FormName+'.elements.length');
                     alen=(alen>1)?eval('document.'+FormName+'.checkone.length'):0;
                     if (alen>0)
                     {
                      for(var i=0;i<alen;i++)
                       eval('document.'+FormName+'.checkone[i].checked=status');
                   }
                   else
                   {
                    eval('document.'+FormName+'.checkone.checked=status');
                  }
                  if(from_>0)
                    eval('document.'+FormName+'.checkall.checked=status');
                }
              </script> 
            </div>
          </div>
        </div>
      </div>
    </div>
    {{csrf_field()}}
  </form>
  <script type="text/javascript">
   $(document).ready(function(){
    $('input[name=home]').click(function(){
     url = "{{url('admin/ajax/recruitment_home')}}/";
     id = $(this).attr('id_news');
     _token = $('input[name=_token]').val();
     $.ajax({
      url:url+id,
      type:'GET',
      cache:false,
      data:{'_token':_token,'id':id},
      success:function(data){
      }
    });
   });
    $('input[name=hot]').click(function(){
     url = "{{url('admin/ajax/recruitment_hot')}}/";
     id = $(this).attr('id_news');
     _token = $('input[name=_token]').val();
     $.ajax({
      url:url+id,
      type:'GET',
      cache:false,
      data:{'_token':_token,'id':id},
      success:function(data){
      }
    });
   });
    $('input[name=focus]').click(function(){
     url = "{{url('admin/ajax/recruitment_focus')}}/";
     id = $(this).attr('id_news');
     _token = $('input[name=_token]').val();
     $.ajax({
      url:url+id,
      type:'GET',
      cache:false,
      data:{'_token':_token,'id':id},
      success:function(data){
      }
    });
   });
    $('input[name=sort]').keyup(function(){
     url = "{{url('admin/ajax/recruitment_sort')}}/";
     id = $(this).attr('id_news');
     num = $(this).val();
     _token = $('input[name=_token]').val();
     $.ajax({
      url:url+id+'/'+num,
      type:'GET',
      cache:false,
      data:{'_token':_token,'id':id,'num':num},
      success:function(data){
      }
    });
   });
  })
</script> 
@stop