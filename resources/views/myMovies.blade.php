<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Fab's Cinema</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="/css/style.css" type="text/css" media="all" />
<script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/js/jquery-func.js"></script>
<!--[if IE 6]><link rel="stylesheet" href="/css/ie6.css" type="text/css" media="all" /><![endif]-->
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">
</head>
<body>
<div id="shell">
  <div id="header">
    <h1 id="logo"><a href="#">FAB'S CINEMA</a></h1>
    <div id="navigation">
      <ul>
	@if (isset($aMovies['movie_list'][0]) and $aMovies['movie_list'][0]['status'] == 1)
            <li><a class="active" href="/now_showing">NOW SHOWING</a></li>
    	    <li><a href="/coming_soon">COMING SOON</a></li>
        @else
	    <li><a href="/now_showing">NOW SHOWING</a></li>
            <li><a class="active" href="/coming_soon">COMING SOON</a></li>
        @endif 
      </ul>
    </div>
    <div id="sub-navigation">
	<select name='genre'id='genre'>
        <option value=''>Genres</option>
	<option value=''>All</option>
	@foreach ($aMovies['genres'] as $genre)
	<option 
	@if ($aMovies['genre'] == $genre->id)
	  selected
        @endif
        value='{{ $genre->id }}'>{{ $genre->genre_name }}</option> 
	@endforeach
	</select>
        <select name='list'id='list'>
        <option value=''>Lists</option>
	<option value=''>None</option>
	@foreach ($aMovies['lists'] as $list)
	<option 
	@if ($aMovies['list'] == $list->id)
	  selected
        @endif
        value='{{ $list->id }}'>{{ $list->list_name }}</option> 
	@endforeach
	</select>

	<input type='text' placeholder='Search Titles, Cast Members or Director' name='search_box' id='search_box' style='width:250px;' value='{{ $aMovies['search'] }}'>
        <input type=button name='search' value='Search' id='search' style='width:60px;'> 
        <select name='orderBy' id='orderBy'>
	<option value='title' 
        @if ($aMovies['orderBy'] == 'title')
	  selected
        @endif
>Order By Title</option>
	<option value='rd_asc' 
        @if ($aMovies['orderBy'] == 'rd_asc')
	  selected
        @endif
>Order By Release Date ASC</option>
        <option value='rd_desc' 
        @if ($aMovies['orderBy'] == 'rd_desc')
	  selected
        @endif
>Order By Release Date DESC</option>
        <option value='rating' 
        @if ($aMovies['orderBy'] == 'rating')
	  selected
        @endif
>Order By Rating</option>
	</select>
    </div>
  </div>
  <div id="main">
    <div id="content">
      <div class="box">
        <div class="head">
	</div>
	@foreach ($aMovies['movie_list'] as $movie)
		@if ($movie['id'] % 6 == 0)
		    <div class="movie last">
		@else
		    <div class="movie">
		@endif

		  <div class="movie-image"> 
		    <a href="/movie_detail/{{ $movie['fc_id'] }}"><img src="{{ $movie['poster'] }}" alt="" /></a> 
                  </div>
           	  <div class="rating">
		    <p>RATING</p>
                    <div class="stars">
                    <div class="stars-in" style="width:{{ round(60 * ($movie['rating'] / 100)) }}px;" title="{{ round($movie['rating'] / 20, 1) }}" > </div> </div>
            	  </div>
		</div>
		@if ($movie['id'] % 6 == 0) 
		    <div class="cl">&nbsp;</div>
		    </div>
		    <div class="box">
		    <div class="head"></div>
		@endif
	@endforeach
        <div class="cl">&nbsp;</div>
      </div> 
    </div>
  </div>
  <div id="footer">
    <p class="lf">Copyright &copy; 2022 <a href="#">W4P</a> - All Rights Reserved</p>
    <div style="clear:both;"></div>
  </div>
</div>
<script>
$(genre).change(function() {
	var genre =  $(this).find(':selected').val();
	window.location.href = '?genre=' + genre;
        });
$(list).change(function() {
	var list =  $(this).find(':selected').val();
	window.location.href = '?list=' + list;
        });
$(orderBy).change(function() {
	var orderBy =  $(this).find(':selected').val();
	window.location.href = '?orderBy=' + orderBy;
        });
$(search).click(function() {
	var search =  $(search_box).val();
	window.location.href = '?search=' + search;
        });
</script>
</body>
</html>
