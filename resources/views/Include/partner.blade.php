    <div class="customer-main">      <div class="container">        <div class="row">          <div class="col-xs-12">            <h4 class="title" style="text-align: center"> KHÁCH HÀNG TIÊU BIỂU </h4>          </div>          <div class="col-xs-12">            <div class="slider-customer-of-us owl-carousel">            @foreach($partner as $item)              <div class="item"> <a target="_blank" href="{{$item->url}}"><img src="{{$item->link}}" alt="{{$item->title}}" class="img-responsive"></a> </div>              @endforeach            </div>          </div>          <div class="col-xs-12 title-customer-of-us-read-more"> <!-- <a href="khach-hang-tieu-bieu.html">Xem danh sách khách hàng tiêu biểu</a> --> </div>        </div>      </div>    </div>