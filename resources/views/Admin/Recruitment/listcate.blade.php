@extends('admin.layout.layout')
@section('title')
Danh mục tuyển dụng
@stop
@section('content')
<form name="formbk" method="post" action="">
  <div class="">
    <div class="form-group"> <a class="btn btn-success" href="{{url('admin/add-cate-recruitment')}}"><span class="icon-button"><i class="fa fa-plus-circle"></i></span> Thêm mới</a>
      <button class="btn btn-danger" type="submit" name="delall" onclick="return confirm('Bạn chắc chắn muốn xóa!')"><span class="icon-button"><i class="fa fa-trash-o"></i></span> Xóa tất cả </button>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12"> @include('errors.note')
        <div class="x_panel">
          <div class="x_title">
            <h2>Danh mục tuyển dụng</small></h2>
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
                <?php $disabled = count($list_cate) == 0 ? "disabled" : ""; ?>
                <th><input type="checkbox" name="checkAll" id="check-all" {{$disabled}} class="input-checked"></th>  
                <th style="width:3%">ID</th>
                <th>Sắp xếp</th>
                <th>Tên danh mục</th>
                <!-- <th>Ảnh mô tả</th> -->
                <th>Danh mục cha</th>
                <th>Trạng thái</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($list_cate as $items)
              @if($items->id > 1 && $items->parent_id == 0)
              <tr>
                <th><input type="checkbox" class="check_del flat" name="check_del[]" value="{{$items->id}}"></th>
                <td>{{$items->id}}</td>
                <td><input style="width:50px;" type="text" name="sort" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" id_cate="{{$items->id}}" value="{{$items->sort}}"> <span id="change-sort-success_{{$items->id}}"></span></td>
                <td><strong>{{$items->title}}</strong></td>
                <!--                 <td><img style="width:100px" class="img-thumbnail" src="@if($items->thumb =="") {{url('public/images/noimg.jpg')}} @else {{url($items->thumb)}} @endif"></td> -->

                <td></td>
                <td><div class="form-group">
                  <label class="label-control">
                    <input type="checkbox" name="hot" class="input-checked recruit_cate_hot" id_cate="{{$items->id}}" title="Nổi bật" @if($items->
                    hot == 1) checked @endif> Danh mục nổi bật</label>
                  </div></td>
                  <td><a href="{{domain}}admin/edit-cate-recruitment/{{$items->id}}" class="btn btn-warning btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a href="{{domain}}admin/del-cate-recruitment/{{$items->id}}" onclick="return confirm('Bạn chắc chắn muốn xóa!')" class="btn btn-danger btn-xs"><i class="fa fa-times" aria-hidden="true"></i></a>
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
                        <a href="{{url('admin')}}/edit-cate-recruitment/{{Helper::get_post_lang($items->id,$type,$item->value)}}" class="btn btn-warning"><span class="icon-button"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> Edit {{$item->name}}</a>
                        @else
                        <a href="{{url('admin')}}/add-cate-recruitment/{{$item->value}}/{{$items->id}}" class="btn btn-primary"><span class="icon-button"><i class="fa fa-plus-circle"></i></span> Add {{$item->name}}</a>
                        @endif
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>
              {{Helper::sub_list_recruitmentcate($list_cate,$items->id,Session::get('user')->id)}}
              @endif
              @endforeach
            </tbody>
          </table>
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
<script type="text/javascript">
</script> 
{{csrf_field()}}
</form>
<script type="text/javascript">
  $(document).ready(function(){
    $('input[name=sort]').keyup(function(){
      url = "{{url('admin/ajax/recruit_cate_sort')}}/";
      id = $(this).attr('id_cate');
      num = $(this).val();
      _token = $('input[name=_token]').val();
      $.ajax({
        url:url+id+'/'+num,
        type:'GET',
        cache:false,
        data:{'_token':_token,'id':id,'num':num},
        success:function(data){
          $('#change-sort-success_'+id+'').html('<i class="fa fa-check text-success" aria-hidden="true"></i>');
          $('#change-sort-success_'+id+'').fadeIn(1000);
          $('#change-sort-success_'+id+'').fadeOut(5000);
        }
      });
    });
    $('.recruit_cate_hot').click(function(){
      url = "{{url('admin/ajax/recruit_cate_hot')}}/";
      id = $(this).attr('id_cate');
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
  });
</script> 
@stop