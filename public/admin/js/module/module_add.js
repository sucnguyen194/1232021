$(document).ready(function(){
    $('#column').change(function(){
      num = $(this).val();
      data = '';
      for(i=1;i<=num;i++){

        data += '<div class="col-lg-4">';
        data += '<div class="card-box">';
        data += '<h4 class="header-title mb-2">Cột '+i+'</h4>';

        data += '<div class="form-group">';
          data += '<label>Tên hiển thị <span class="required">*</span></label>';
          data += '<input type="text" name="display_name'+i+'" class="form-control" required>';

          data += '</div>';

          data += '<div class="form-group">';
          data += ' <label>Tên cột <span class="required">*</span></label>';
          data += '<input type="text" name="name'+i+'" class="form-control" required>';
          data += '</div>';

          data += '<div class="form-group">';
          data += '<label>Kiểu hiển thị</label>';
          data += '<select class="form-control display_type" data-toggle="select2" name="display_type'+i+'" target="#select_options'+i+'">';
          data += '<option value="0">Text</option>';
          data += '<option value="1">Checkbox</option>';
          data += '<option value="2">Number</option>';
          data += '<option value="3">Radio</option>';
          data += '<option value="4">Select</option>';
          data += '<option value="5">File</option>';
          data += '<option value="6">Textarea</option>';
          data += '</select>';
          data += '</div>';

          data += '<div class="form-group">';
          data += '<div id="option'+i+'"></div>';
          data += '</div>';

          data += '<div class="form-group" id="select_options'+i+'" style="display: none">';
          data += '<label>Options</label>';
          data += '<input type="text" name="select_option'+i+'" target="#option'+i+'" class="form-control select_option">';
          data += '</div>';

          data += '<div class="form-group">';
          data += '<label>Kiểu dữ liệu</label>';
          data += '<select class="form-control" data-toggle="select2" name="type'+i+'">';
          data += '<option value="0">----</option>';
          data += '<option value="1">Integer</option>';
          data += '<option value="2">Varchar</option>';
          data += '<option value="3">Text</option>';
          data += '<option value="4">Date</option>';
          data += '</select>';
          data += '</div>';

          data += '<div class="form-group">';
          data += '<label>Độ dài</label>';
          data += '<input class="form-control" name="length'+i+'" type="number" min="11" value="11">';
          data += ' </div>';

          data += ' </div>';
          data += ' </div>';

      }
      $("#column_list").html(data).append("<script>$('[data-toggle=\"select2\"]').select2()</script>");

      $(".display_type").change(function(){
        type = $(this).val();
        if(type == 4){
          target = $(this).attr('target');
          $(target).show();
          str = "";
        }else{
          target = $(this).attr('target');
          $(target).hide();
        }
      })
      str = "";

       $(document).on('change', '.select_option', function(){
           val = $(this).val();
           target = $(this).attr('target');
          // str = $(target).html();
           str += '<div class="form-group">';
           str += '<div class="input-group">'
           str += '<input class="form-control" name="'+target.replace('#','')+'[]" type="text" value="'+val+'" readonly>';
           str += '<div class="input-group-append rm_options" style="cursor:pointer;"><span class="btn waves-effect waves-light btn-primary"><i class="pe-7s-close"></i></span></div>';
           str += '</div>';
           str += '</div>';
           $(target).append(str);
           str = "";

           $('.rm_options').click(function(){
               $(this).parent().parent().remove();
           })
       })
    });
  })
