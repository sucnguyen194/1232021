<header id="banner" class="header">
	<div class="banner-wrap">
		<div class="swiper-container">
			<div class="swiper-wrapper">
				@foreach($slider as $item)
				
				<div class="swiper-slide" onclick="javascript:location.href='{{$item->url}}'">
					<div class="banner-img"> <img src="{{$item->link}}"
						alt="{{$item->title}}"> </div>
						<div class="container">
							<div class="banner-txt">
								<h2 class="title">{{$item->title}}</h2>
								<p>{!!Helper::getDescription($item->description,4000)!!}</p>
							</div>
						</div>
					</div>
					
					@endforeach
				</div>
				<div class="swiper-pagination"></div>
			</div>
		</div>
	</header>