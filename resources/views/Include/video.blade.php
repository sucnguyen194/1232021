<div class="row custom">
  <div class="category">
    <h2 class="cat-name"><span>Video</span></h2>
  </div>
  <div class="videos-carousel"> 
    <!-- print_responsive_video_gallery_plus_lightbox_func -->
    <style type='text/css'>
      #divSliderMain5ecf6bb8a20e0 .bx-wrapper .bx-viewport {
        background: none repeat scroll 0 0#FFFFFF ! important;
        border: 0px none !important;
        box-shadow: 0 0 0 0 !important;
        /*padding:15px !important;*/
      }
    </style>
    <div style="clear: both;"></div>
    <div style="width: auto; postion: relative" id="divSliderMain5ecf6bb8a20e0">
      <div id="thumnail_slider5ecf6bb8a20df" class="responsiveSlider" style="margin-top: 2px !important; visibility: hidden;">
        @foreach($video_home as $item)
        <div> <a rel="rel_5ecf6bb8a20e7" data-overlay="1" data-title="{{$item->title}}" class="video_lbox_5ecf6bb8a20e8" href="//www.youtube.com/embed/{{$item->video}}"> <img alt="{{$item->title}}" title="{{$item->title}}" data-src="{{$item->thumb}}" class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" />
          <noscript>
            <img    src="{{$item->thumb}}" alt="{{$item->title}}" title="{{$item->title}}" />
          </noscript>
          <span class="playbtnCss"> </span> </a> </div>
@endforeach
  
                    </div>
                  </div>
                  <script>
                   var interval_5ecf6bb8a2260 = setInterval(function() {
                     if(document.readyState === 'complete') {
                       clearInterval(interval_5ecf6bb8a2260);
                       var $n = jQuery.noConflict();
                       var uniqObj5ecf6bb8a2261=$n("a[rel^='rel_5ecf6bb8a20e7']");
                       var rand_5ecf6bb8a20e1 = $n('#divSliderMain5ecf6bb8a20e0').html();
                       $n('#thumnail_slider5ecf6bb8a20df').bxSlider({
                        slideWidth: 180,
                        minSlides: 1,
                        maxSlides: 4,
                        moveSlides: 1,
                        slideMargin:15,
                        speed:1000,
                        pause:1000,
                        controls:true,
                        pager:false,
                        useCSS:false,
                        infiniteLoop: false,
                        pager:false,
                        captions:true, 

                        onSliderLoad: function(){

                         $n("#thumnail_slider5ecf6bb8a20df").css("visibility", "visible");

                         $n(".video_lbox_5ecf6bb8a20e8").fancybox_vgl({
                          'type'    : "iframe",
                          'overlayColor':'#000000',
                          'padding': 10,
                          'autoScale': true,
                          'autoDimensions':true,
                          'uniqObj':uniqObj5ecf6bb8a2261,
                          'transitionIn': 'none',
                          'transitionOut': 'none',
                          'titlePosition': 'outside',
                          'cyclic':false,
                          'hideOnContentClick':false,
                          'width' : 650,
                          'height' : 400,
                          'titleFormat': function(title, currentArray, currentIndex, currentOpts) {

                           var currtElem = $n('#thumnail_slider5ecf6bb8a20df a[href="'+currentOpts.href+'"]');

                           var isoverlay = $n(currtElem).attr('data-overlay')

                           if(isoverlay=="1" && $n.trim(title)!=""){
                             return '<span id="fancybox_vgl-title-over">' + title  + '</span>';
                           }
                           else{
                            return '';
                          }

                        },

                      }); 

                       }                                                         


                     });



                       window.rebindthumnail_slider5ecf6bb8a20df = function() {

                        $n(".video_lbox_5ecf6bb8a20e8").fancybox_vgl({
                         'type'    : "iframe",
                         'overlayColor':'#000000',
                         'padding': 10,
                         'autoScale': true,
                         'autoDimensions':true,
                         'uniqObj':uniqObj5ecf6bb8a2261,
                         'transitionIn': 'none',
                         'transitionOut': 'none',
                         'titlePosition': 'outside',
                         'cyclic':false,
                         'hideOnContentClick':false,
                         'width' : 650,
                         'height' : 400,
                         'titleFormat': function(title, currentArray, currentIndex, currentOpts) {

                          var currtElem = $n('#thumnail_slider5ecf6bb8a20df a[href="'+currentOpts.href+'"]');

                          var isoverlay = $n(currtElem).attr('data-overlay')

                          if(isoverlay=="1" && $n.trim(title)!=""){
                            return '<span id="fancybox_vgl-title-over">' + title  + '</span>';
                          }
                          else{
                           return '';
                         }

                       },

                     }); 

                      }

                    }    
                  }, 100);




                </script><!-- end print_responsive_video_gallery_plus_lightbox_func --> </div>
              </div>