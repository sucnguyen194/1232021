<div class="attribute-coll">
  <ul class="ul-attribute-coll">
    @foreach($list_cate_attr as $item)
    <li class="li-attribute-coll"><a href="javascript:void(0)" class="href-attribute-coll">{{$item->value}}</a>
      <ul class="sub-ul-attribute-coll">
        @foreach($list_attr as $sub)
        @if($item->id == $sub->group_attr)
        <!-- ACTIVE ATTRIBUTE -->
        <?php 
        $del = NULL;
        $checked = NULL;
        if(isset($_GET['attribute']) && $_GET['attribute']  != NULL ) {
          $attribute = $_GET['attribute'];
          $array = explode(',',$attribute);
          /* url attribute */
          if(in_array($sub->value,$array)){
            $url = url($cate_current->alias)."?attribute=".$attribute;
          }else {
           $url =  url($cate_current->alias)."?attribute=".$attribute.",".$sub->value;
         }
         /*url delete attribute */
         $int = array_search($sub->value,$array); /* key array cần xóa */
         unset($array[$int]); /* xóa value theo key vừa tìm */
         $explodeUrl = implode(',',$array); /* chuyển array thành chuỗi */
         $delUrl = url($cate_current->alias)."?attribute=".$explodeUrl; /* url backlink */
         $array = explode(',',$attribute); /* khởi tạo lại array attribue */         
         /* kiểm tra giá trị có tồn tại trong $attribute hay không */
         if(in_array($sub->value,$array)){
          $del = "<a href='".$delUrl."'><i class='fa fa-times' aria-hidden='true'></i></a></span></a>";
          $checked = "attribute-checked";
        }


    $array = explode(',',$attribute);
    $implodeAttr = implode(',',$array);
    //print_r($implodeAttr);
    $sql = DB::table('attribute')->select('attribute.id')->whereIn('value',$array)->get();
    foreach ($sql as $key => $value) {
      $result[$key] = $value->id;
    }
    //print_r($result);
    $implodeResult = implode(',',$result);
    $attr_sql = DB::table('product_to_attribute')->select('product_to_attribute.product_id')->where('attr_id',$implodeResult)->get();
    //print_r($attr_sql);
    //$implodeAttr = implode(',',$attr_sql);
    //$array1 = explode(',',$implodeAttr);
     
    // $toAttr = [];
    // foreach($attr_sql as $k => $v){
    //   $toAttr[$k] = $v->product_id;
    // }

    // $uniqueAttr = array_unique($toAttr);
    // $implodeAttr = implode(',',$uniqueAttr);
    // $id = explode(',',$implodeAttr);

      }else{
        $url = url($cate_current->alias)."?attribute=".$sub->value;
      }

      ?>
      <!-- END ATTRIBUTE -->
      <li class="sub-li-attribute-coll {{@$checked}}"><a href="{{$url}}" class="sub-href-attribute-coll">{{$sub->value}}</a> <span class='delete-attribute-coll'>{!!@$del!!}</span></li>
      @endif
      @endforeach
      <!-- end list attribute -->
    </ul>
  </li>
  @endforeach  
  <!-- end group attribute --> 
</ul>
</div>
<style type="text/css">
  .attribute-coll .ul-attribute-coll {
    margin-left: 0;
  }
  .attribute-coll .ul-attribute-coll li {
    list-style: none;
    padding: 5px 10px;
  }
  .li-attribute-coll {
    margin-bottom: 50px;
    border-bottom: 2px dotted #c2c2c2c2;
  }
  .attribute-coll .ul-attribute-coll .li-attribute-coll .href-attribute-coll {
    font-size: 23px;
    margin-top: 30px;
    color: #f15e2c;
    font-weight: 600;
    text-transform: uppercase;
  }
  .attribute-coll .sub-ul-attribute-coll  {
   margin-left: 10px;
 }
 .delete-attribute-coll {
  float: right;
}
.attribute-checked {
  background: #f1f1f1
}
.attribute-checked > .sub-href-attribute-coll {
  font-weight: 600;
  color:000;
}
.sub-li-attribute-coll {
  margin-bottom: .6em;
}
.delete-attribute-coll i {
  font-weight: 400
}
.sub-href-attribute-coll:hover {
  color: #f15e2c;
}
</style>