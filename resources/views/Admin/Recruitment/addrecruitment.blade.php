@extends('admin.layout.layout')
@section('title') Đăng tin tuyển dụng
@stop
@section('js')
<script src="{{url('public/admin/js/recruitment/recruitment.js')}}"></script>
@stop
@section('content')
<div class="x_panel">
  <div class="x_content">
    <div class="row">
      @include('errors.note')
      <form class="form-horizontal form-label-left" method="post" enctype='multipart/form-data'>
        <div class="col-md-8 col-xs-12">
          <div class="x_panel">
            <div class="x_content">
              <span class="section">Thêm mới</span>
              <div class="item form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="title">Vị trí tuyển dụng<span class="required">*</span> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Nhập tiêu đề bài viết (* required không được bỏ trống)</i>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <input id="title" class="form-control col-md-7 col-xs-12 input" data-validate-length-range="6" data-validate-words="2" name="title" required="required" type="text" value="{{old('title')}}">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="alias">Đường dẫn </label>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Mặc định "đường dẫn sẽ được lấy theo tên bài viết" ví dụ: Tên bài viết: "tin tức" -> Đường dẫn: "tin-tuc.html" hoặc có thể tự nhập đường dẫn theo nhu cầu sử dụng.</i>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <input id="alias" class="form-control col-md-7 col-xs-12 output" name="alias" type="text" value="{{old('alias')}}">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 col-xs-12">
                 <div class="item form-group">
                  <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="quantity">Số lượng<span class="required">*</span> </label>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <input id="quantity" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="quantity"  required="required" type="text" value="@if(old('quantity') != "" ) {{old('quantity')}} @else{{1}}@endif">
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-xs-12">
               <div class="item form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="salary">Mức lương<span class="required">*</span> </label>
                <div class="col-md-12 col-sm-12 col-xs-12">

                  <input id="salary" name="salary" class="form-control" value="{{old('salary')}}">
                </div>
              </div>
            </div>
              <div class="col-md-4 col-xs-12">
               <div class="item form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="salary">Thời gian làm việc</label>
                <div class="col-md-12 col-sm-12 col-xs-12">

                  <input id="time_work" name="time_work" class="form-control" value="{{old('time_work')}}">
                </div>
              </div>
            </div>
            <div class="col-md-4 col-xs-12">
             <div class="item form-group">
              <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="address">Nơi làm việc </label>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <select name="address" class="form-control">
                  <option value="0">Chọn nơi làm việc</option>
                  <?php $localtion = DB::table('address')->select('*')->where([])->orderby('title','asc')->get(); ?>
                  @foreach($localtion as $item)
                  <option value="{{$item->id}}" @if(old('address') == $item->id) selected @endif>{{$item->title}}</option>
                  @endforeach
                </select>  
              </div>
            </div>
          </div>

            <div class="col-md-4 col-xs-12">
             <div class="item form-group">
              <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="address">Nhà thầu </label>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <select name="partner" class="form-control">
                  <option value="0">Chọn nhà thầu</option>
                  <?php $partner = DB::table('image')->select('*')->where(['position'=>'Partner'])->orderby('sort','asc')->get(); ?>
                  @foreach($partner as $item)
                  <option value="{{$item->id}}" @if(old('partner') == $item->id) selected @endif>{{$item->title}}</option>
                  @endforeach
                </select>  
              </div>
            </div>
          </div>

            <div class="col-md-4 col-xs-12">
             <div class="item form-group">
              <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="address">Ngành nghề </label>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <select name="jobs" class="form-control">
                  <option value="0">Chọn ngành nghề</option>
                  <?php $jobs = DB::table('jobs')->select('*')->where([])->orderby('title','asc')->get(); ?>
                  @foreach($jobs as $item)
                  <option value="{{$item->id}}" @if(old('jobs') == $item->id) selected @endif>{{$item->title}}</option>
                  @endforeach
                </select>  
              </div>
            </div>
          </div>

        </div>
        <div class="item form-group" style="display:none">
          <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="diploma">Yêu cầu bằng cấp<span class="required">*</span> </label>
          <div class="col-md-12 col-sm-12 col-xs-12">
            <select id="diploma" name="diploma" class="form-control">
              <option value="0">Tất cả</option>
              <option value="1">Trung học</option>
              <option value="2">Trung cấp</option>
              <option value="3">Cao đẳng</option>
              <option value="4">Đại học</option>
              <option value="5">Trên đại học</option>
            </select>
          </div>
        </div>
        <div class="item form-group" style="display:none">
          <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="experience">Kinh nghiệm<span class="required">*</span> </label>
          <div class="col-md-12 col-sm-12 col-xs-12">
            <select id="experience" name="experience" class="form-control">
              <option value="0">Tất cả</option>
              <option value="1">Dưới 1 năm</option>
              <option value="2">1 năm</option>
              <option value="3">2 năm</option>
              <option value="4">5 năm</option>
              <option value="5">Trên 5 năm</option>
            </select>
          </div>
        </div>
        <div class="item form-group" style="display:none">
          <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="profile">Yêu cầu hồ sơ</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <textarea id="profile" name="profile" class="form-control">{{old('profile')}}</textarea>
          </div>
        </div>
        <div class="item form-group" style="display:none">
          <label for="password" class="control-label col-md-12 col-xs-12 text__alignleft">Mô tả</label>
          <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Mô tả về bài viết (Không giới hạn ký tự)</i>
          </div>
          <div class="col-md-12 col-sm-12 col-xs-12">
            <textarea name="description" id="description" rows="7" class="form-control">{{old('description')}}</textarea>
          </div>
        </div>
        <div class="item form-group" style="display:none">
          <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="benefit">Quyền lợi được hưởng</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <textarea id="benefit" name="benefit" class="form-control">{{old('benefit')}}</textarea>
            <script type="text/javascript">CKEDITOR.replace('benefit')</script>
          </div>
        </div>
        <div class="item form-group">
          <label for="password" class="control-label col-md-12 col-xs-12 text__alignleft">Chi tiết công việc</label>
          <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Chi tiết về bài viết (Không giới hạn ký tự)</i>
          </div>
          <div class="col-md-12 col-sm-12 col-xs-12">
            <textarea name="requirement" id="requirement" class="form-control">{{old('requirement')}}</textarea>
          </div>
        </div>
        <script type="text/javascript">
          CKEDITOR.replace( 'requirement' );
        </script>
        <div class="item form-group">
          @include('admin.seo.seo')
        </div>
        <div class="item form-group">
          <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="description_seo">Title Seo </label>
          <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Title SEO  - Mặc định nếu để trống sẽ được lấy theo tên bài viết.</i>
          </div>
          <div class="col-md-12 col-sm-12 col-xs-12">
            <input type="text" id="title_seo" name="title_seo" class="form-control col-md-7 col-xs-12" value="{{old('title_seo')}}">
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="description_seo">Description Seo </label>
          <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Description SEO  - Mặc định nếu để trống sẽ được lấy theo tên bài viết.</i>
          </div>
          <div class="col-md-12 col-sm-12 col-xs-12">
            <input type="text" id="description_seo" name="description_seo" class="form-control col-md-7 col-xs-12" value="{{old('description_seo')}}">
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="keyword_seo">Keyword Seo </label>
          <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Keyword SEO  - Mặc định nếu để trống sẽ được lấy theo tên bài viết.</i>
          </div>
          <div class="col-md-12 col-sm-12 col-xs-12">
            <input type="text" id="keyword_seo" name="keyword_seo" class="form-control col-md-7 col-xs-12" value="{{old('keyword_seo')}}">
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="ln_solid"></div>
        <div class="form-group">
          <div class="text-center">
            <a href="{{url('admin/list-recruitment')}}" class="btn btn-primary"><span class="icon-button"><i class="fa fa-undo" aria-hidden="true"></i></span> Quay lại</a>
            <button id="submit" name="submit" type="submit" class="btn btn-success"/><span class="icon-button"><i class="fa fa-floppy-o" aria-hidden="true"></i></span> Lưu lại</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4 col-xs-12">
    <div class="x_panel">
      <div class="x_content">
        <span class="section">Tin nổi bật</span>
        <div class="item">
          <select name="home" class="form-control">
            <option value="1">Active</option>
            <option value="0" selected="selected">No Active</option>
          </select>
        </div>
      </div>
    </div>
    <div class="x_panel" style="display: none">
      <div class="x_content">
        <span class="section">Tin HOT</span>
        <div class="item">
          <select name="hot" class="form-control">
            <option value="1">Active</option>
            <option value="0" selected="selected">No Active</option>
          </select>
        </div>
      </div>
    </div>
    <div class="x_panel" style="display:none">
      <div class="x_content">
        <span class="section">Tin nổi bật</span>
        <div class="item">
          <select name="focus" class="form-control">
            <option value="1">Active</option>
            <option value="0" selected="selected">No Active</option>
          </select>
        </div>
      </div>
    </div>
    <div class="x_panel">
      <div class="x_content">
        <span class="section">Hạn nộp hồ sơ</span>
        <div class='input-group date' id='myDatepicker4'>
          <?php $time = time(); ?>
          <input type='text' id="time_out" name="time_out" class="form-control col-md-7 col-xs-12" readonly="readonly"  required value="{{date('d-m-Y',$time)}}"/>
          <span class="input-group-addon">
           <span class="glyphicon glyphicon-calendar"></span>
         </span>
       </div>
     </div>
   </div>
   <div class="x_panel">
    <div class="x_content">
      <span class="section">Danh mục</span>
      <div class="item category_check">
       <?php foreach ($cate_recruitment as $key => $value): ?>
        <?php if ($value->parent_id == 0 && $value->id>1): ?>
         <label>
          <input type="checkbox" style="font-weight: 700" name="category_id[]" @if($key==0)  @endif value="{{$value->id}}" class="flat" @if(old('category_id') == $value->id) checked @endif> {{$value->title}} 
        </label><br>
        {{Helper::sub_recruitment_checkbox($cate_recruitment,$value->id)}}
      <?php endif ?>
    <?php endforeach ?>
  </div>
</div>
</div>
<div class="x_panel">
  <div class="x_content">
    <span class="section">Ảnh mô tả</span>
    <div class="item form-group">
      <input type="file" name="image" id="fileUpload" class="inputfile inputfile-6" data-multiple-caption="{count} files selected"/>
      <label for="file-7"><span><i>(* jpg, jpeg, png, gif)</i></span><strong class="btn btn-default"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> Upload image</strong></label>
      <div class="loading" style="display: none;"><img src="{{url('public/images/loading.gif')}}" style="width:100%;">
      </div>
      <div class="show-img" id="image-holder"></div>
    </div>
    <div class="box-position show-box">
      <label><input type="checkbox" name="unlink" class="input-checked" id="unlink-image"> Xóa ảnh</label>
    </div>
  </div>
</div>

<div class="hidden-lg hidden-sm hidden-md">
  <div class="ln_solid"></div>
  <div class="col-md-12 col-xs-12 text-center">
    <a href="{{url('admin/list-recruitment')}}" class="btn btn-primary">Quay lại</a>
    <input id="submit" name="submit" type="submit" class="btn btn-success" value="Lưu lại"/>
  </div>
</div>
</div>
{{csrf_field()}}
</form>
</div>
</div>
</div>
@stop
