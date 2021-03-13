  <div class="other-wrap">
<Style>
	@media(max-width:768px){
		.image-comment {
			width: 200px!important;
			height: 200px;
			margin: auto
		}
		.info-comment {
			text-align: center!important;
			margin-top: 20px;
		}
	}
	  </Style>
        <div class="container">
          <div class="row">
            <div class="col-sm-12 category-client">
              <h4 class="title">&Yacute; kiến kh&aacute;ch h&agrave;ng, đối t&aacute;c</h4>
              <div class="commentHome owl-carousel" id="commentHome">
                @foreach($customer as $item)
                <div class="txt item">
                <div class="media">

                  <div class="media-left">
					  <div class="row">
					  	<div class="col-md-4 col-xs-12">
						   <img class="img-circle image-comment" src="{{$item->image}}" alt="{{$item->title}}" width="96" height="96">
						 </div>
						  <div class="col-md-8 col-xs-12 info-comment">
					  <p class="media-heading"><a href="javascript:void(0)" title="{{$item->title}}">{{$item->title}}</a> </p>
                    <p>{{$item->job}}</p>
					  </div>
					  </div>

					  </div>
                  <div class="media-body">

                  </div>
                </div>
                <div class="sumary" id="sumary-{{$item->id}}">{!!$item->content!!}</div>
				<div class="view-more" id="viewMoreComment" data-id="{{$item->id}}">Xem thêm</div>
              </div>
              @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>

<style>
	.sumary {
		height: 100px;
		overflow: hidden;
		transition:  all 1s;
		-webkit-transition:  all 1s;
		-moz-transition:  all 1s;
		-ms-transition:  all 1s;
		-o-transition:  all 1s;
	}
	.height-auto {
		height: auto;
		overflow: visible;
	}
	.view-more {
		cursor: pointer;
	}
</style>
