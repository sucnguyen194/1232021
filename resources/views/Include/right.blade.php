       <aside id="secondary" class="widget-area" role="complementary" aria-label="Blog Sidebar">
          <section id="search-2" class="widget widget_search">
            <h2 class="widget-title">Tìm Kiếm</h2>
            <form role="search" method="get" class="search-form" action="{{url('search')}}">
              <label for="search-form-1"> <span class="screen-reader-text">Tìm kiếm:</span> </label>
              <input type="search" id="search-form-1" class="search-field" placeholder="Tìm kiếm &hellip;" value="" name="key" />
              <button type="submit" class="search-submit">
              <svg class="icon icon-search" aria-hidden="true" role="img">
                <use href="#icon-search" xlink:href="#icon-search"></use>
              </svg>
              <span class="screen-reader-text">Tìm kiếm</span></button>
            </form>
          </section>
          <section id="recent-posts-2" class="widget widget_recent_entries">
            <h2 class="widget-title">Bài Viết Mới</h2>
            <ul>
             @foreach($news_desc as $item)
              <li> <a href="{{$item->alias}}">{{$item->title}}</a> </li>
              @endforeach
            </ul>
          </section>
        </aside>