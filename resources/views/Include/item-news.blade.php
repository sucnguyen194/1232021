<style>
	@media screen and (min-width: 750px) { 
		#ArticleImage-33362411631 {
			max-width: 345px;
			max-height: 172.5px;
		}
		#ArticleImageWrapper-33362411631 {
			max-width: 345px;
			max-height: 172.5px;
		}
	} 



	@media screen and (max-width: 749px) {
		#ArticleImage-33362411631 {
			max-width: 750px;
			max-height: 750px;
		}
		#ArticleImageWrapper-33362411631 {
			max-width: 750px;
		}
	}

</style>
<?php $tags = $user->getData('tags',['id_news'=>$item->id],'id','asc'); ?>
<div id="ArticleImageWrapper-33362411631" class="article__grid-image-wrapper js"> <a href="{{$item->alias}}" class="article__grid-image-container" style="padding-top:50.0%;"> <img id="ArticleImage-33362411631"
	class="article__grid-image lazyload"
	src="{{$item->thumb}}"
	data-src="{{$item->thumb}}"
	data-widths="[180, 360, 540, 720, 900, 1080, 1296, 1512, 1728, 2048]"
	data-aspectratio="2.0"
	data-sizes="auto"
	alt="{{$item->title}}"> </a> </div>
	<noscript>
		<a href="{{$item->alias}}" class="article__grid-image-wrapper"> <img src="{{$item->thumb}}" alt="{{$item->title}}" class="article__grid-image" /> </a>
	</noscript>
	<div class="article__grid-meta article__grid-meta--has-image">
		<h2 class="h3 article__title"> <a href="{{$item->alias}}">{{$item->title}}</a> </h2>
		<div class="rte article__grid-excerpt">{{Helper::getDescription($item->description,40)}}</div>

		<div class="article__tags rte"> 
			@foreach($tags as $tag)
			<a href="{{url('tags')}}/{{$tag->alias}}" class="article__grid-tag">{{$tag->name}}</a>
			@endforeach
			</div>
		<ul class="list--inline article__meta-buttons">
			<li> <a href="{{$item->alias}}" class="btn btn--secondary btn--small"> Read more </a> </li>
		</ul>
	</div>