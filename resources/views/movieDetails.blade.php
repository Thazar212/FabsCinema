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
	<li><a href="/now_showing" 
	@if ($movie_details->status == 1)
	  class="active"
        @endif
        >NOW SHOWING</a></li>
	<li><a href="/coming_soon" 
        @if ($movie_details->status == 0)
	  class="active"
        @endif
        >COMING SOON</a></li>
      </ul>
    </div>
    <div id="sub-navigation">
    </div>
  </div>
  <div id="main">
    <div id="content">
      <div class="content_left">
	<div class="content_title">
        <h1>{{ $movie_details->title }} ({{ substr($movie_details->release_date, 0, 4) }})</h1>
        </div>
	<div class="content_description">
        <p>{{ $movie_details->synopsys  }}</p>
	</div>
        <div class="content_description">
	<p>Runtime: {{ $movie_details->runtime }} minutes</p>
	</div>
        <div class="content_description">
	<p>Cast: {{ $movie_details->cast_members }}</p>
	</div>
        <div class="content_description">
	<p>Director: {{ $movie_details->director }}</p>
	</div>
        <div class="content_description">
	<p>Genres: {{ $movie_details->genres }}</p>
	</div> 
	<div class="content-video">
        @if ($movie_details->trailer_path)
          <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $movie_details->trailer_path }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyro        scope; picture-in-picture" allowfullscreen></iframe>
	@endif
        </div>
      </div>
      <div class="content_streaming">
      @if ($movie_details->streaming_link)
      <a href="{{ $movie_details->streaming_link }}" target="_blank"><img src="/css/images/eye.png" width="40px" alt="Where to watch?" title="Where to watch"></a> 
      @foreach ($movie_details->streaming as $stream)
        @if (in_array($stream->provider_id, [8,9,337,591])) 
          <img src="https://image.tmdb.org/t/p/original{{ $stream->logo_path }}" width="40px" alt="{{ $stream->provider_name }}" title="{{ $stream->provider_name }}"> 
        @endif	  
      @endforeach
      @endif
      </div>
      <div class="content_right">
        <div class="content_image">
          <img src="https://image.tmdb.org/t/p/w500{{ $movie_details->poster_path }}" width="100%">
        </div>
      </div>
    </div>
    <div class="cl">&nbsp;</div>
  </div>
  <div id="footer">
    <p class="lf">Copyright &copy; 2022 <a href="#">W4P</a> - All Rights Reserved</p>
    <div style="clear:both;"></div>
  </div>
</div>
</body>
</html>
