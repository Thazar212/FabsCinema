<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;

class MoviesController extends Controller
{
    /**
     * Retrieve list of owned 4k movies
     *
     * @param  Request  $request
     * @return Response
     */
    public function MyMovies4k(Request $request)
    {
		$status = 1;
		$fourK = 1;
		$steelbook = 0;
		$aMovies = $this->showMovies($request, $status, $fourK, $steelbook);
    	return view('myMovies', compact('aMovies'));    
    }

	/**
     * Retrieve list of owned blu rays movies
     *
     * @param  Request  $request
     * @return Response
     */
    public function MyMoviesBR(Request $request)
    {
		$status = 1;
		$fourK = 0;
		$steelbook = 0;
		$aMovies = $this->showMovies($request, $status, $fourK, $steelbook);
    	return view('myMovies', compact('aMovies'));    
    }

	/**
     * Retrieve list of owned Steelbook movies
     *
     * @param  Request  $request
     * @return Response
     */
    public function MyMoviesSteel(Request $request)
    {
		$status = 1;
		$fourK = 1;
		$steelbook = 1;
		$aMovies = $this->showMovies($request, $status, $fourK, $steelbook);
    	return view('myMovies', compact('aMovies'));    
    }

	/**
     * Retrieve list of wanted 4k movies
     *
     * @param  Request  $request
     * @return Response
     */
    public function Wanted4k(Request $request)
    {
		$status = 0;
		$fourK = 1;
		$steelbook = 0;
		$aMovies = $this->showMovies($request, $status, $fourK, $steelbook);
    	return view('myMovies', compact('aMovies'));    
    }

	/**
     * Retrieve list of wanted blu ray movies
     *
     * @param  Request  $request
     * @return Response
     */
    public function WantedBR(Request $request)
    {
		$status = 0;
		$fourK = 0;
		$steelbook = 0;
		$aMovies = $this->showMovies($request, $status, $fourK, $steelbook);
    	return view('myMovies', compact('aMovies'));    
    }

    private function showMovies (Request $request, $status = 0, $fourK = 1, $steelbook = 0) {
	    $n = 1;
	    $movies_q = DB::table('movies as m')
		    ->select('m.tmdb_id', 'm.status','m.4k as fourK', 'm.steelbook', 'md.*', 'm.id as movie_id')
		    ->leftJoin('movieDetails as md', 'md.id', '=', 'm.id');
	    $search = '';
		$movies_q = $movies_q->where('status', '=', $status);
	    if (isset($request['search']) && !empty($request['search'])) {
		    $search = urldecode($request['search']);
		    $search_string = '%' . $search .'%';
		    $movies_q = $movies_q->where(function (Builder $query) {
				$query->where('title', 'like', $search_string )
			   ->orWhere(DB::raw('convert(cast_members using latin1)'), 'like', $search_string)
		   	   ->orWhere('director', 'like', $search_string);
			});
	    } elseif ($steelbook == 1) {
		    $movies_q = $movies_q->where('steelbook', '=', 1);
	    } else {
			$movies_q = $movies_q->where('4k', '=', $fourK);
		}
	    $genre = '';
	    if (isset($request['genre']) && !empty($request['genre'])) {
		$movies_q = $movies_q->leftJoin('movie_genre as mg', 'mg.movie_id', '=', 'm.id')
		       ->where('mg.genre_id', $request['genre']);
	       $genre = $request['genre'];	
	    }
	    $list = '';
	    if (isset($request['list']) && !empty($request['list'])) {
		$movies_q = $movies_q->leftJoin('movie_list as ml', 'ml.movie_id', '=', 'm.tmdb_id')
		       ->where('ml.list_id', $request['list']);
	       $list = $request['list'];	
	    }
	    $orderBy = 'rd_asc';
            if (isset($request['orderBy']) && !empty($request['orderBy'])) {
	       $orderBy = $request['orderBy'];	
	    }
	    switch ($orderBy) {
		case 'title':
			$movies_q = $movies_q->orderBy('md.title', 'asc');
			break;
		case 'rating':
			$movies_q = $movies_q->orderBy('md.rating', 'desc');
			break;
		case 'rd_desc':
			$movies_q = $movies_q->orderBy('md.release_date', 'desc');
			break;
		case 'rd_asc':
		default:
			$movies_q = $movies_q->orderBy('md.release_date', 'asc');
			break;
	    }
	    $movies = $movies_q->get();
	    $movie_list = [];
	    foreach ($movies as $movie) {
		   if (empty($movie->title)) {
		     $this->StoreMovies($movie->movie_id, $movie->tmdb_id);	   
		     $tmdb_id = $movie->tmdb_id;
		     $movie_details = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/' . $tmdb_id . '?api_key=4b71bb38fff24b54a79276a70dd07af3'));
		     $movie->title = $movie_details->title;
		     $movie->poster_path = $movie_details->poster_path;
		     $movie->rating = $movie_details->vote_average * 10;
		   }
		   $movie_list[] = ['id' => $n,
                           'title' => $movie->title,
			   'poster' 	=> 'https://image.tmdb.org/t/p/w500' . $movie->poster_path,
			   'rating' 	=> $movie->rating,
			   'status' 	=> $movie->status,
			   '4k' 		=> $movie->fourK,
			   'steelbook' 	=> $movie->steelbook,
			   'fc_id' 		=> $movie->movie_id,
		   ];
		   $n++;
	    }

	    $genres = DB::table('genres as g')
		    ->select('g.*') 
		    ->join('movie_genre as mg', 'mg.genre_id', '=', 'g.id')
		    ->groupBy('g.id')
	            ->orderBy('g.genre_name', 'asc')
		    ->get();
	    $lists = DB:: table('lists as l')->select('l.*')->orderBy('list_name', 'asc')->get();
	    $aMovies = [
		    'genres' => $genres, 
		    'lists' => $lists,
		    'movie_list' => $movie_list, 
		    'genre' => $genre, 
		    'list' => $list,
		    'orderBy' => $orderBy,
		    'search' => $search,
	    ];
	    return $aMovies;
    }
	    
    public function MovieDetail (Request $request, $movie_id) 
    {
	    $movie = DB::table('movies as m')
		    ->select('m.*', 'md.*', DB::raw('group_concat(genre_name) as genres'))
		    ->leftJoin('movieDetails as md', 'md.id', '=', 'm.id')
	    	    ->leftJoin('movie_genre as mg', 'mg.movie_id', '=', 'm.id')
	    	    ->leftJoin('genres as g', 'g.id', '=', 'mg.genre_id')
		    ->where('m.id', $movie_id)
		    ->first();

	    $movie->streaming = NULL;
	    $movie->streaming_link = NULL;
	    if ($movie->status == 0) {
	        $streaming = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/' . $movie->tmdb_id . '/watch/providers?api_key=4b71bb38fff24b54a79276a70dd07af3'));
			if (isset($streaming->results) && isset($streaming->results->GB)) {
				if (isset( $streaming->results->GB->link)) {
					$movie->streaming_link = $streaming->results->GB->link;
				}
				if (isset( $streaming->results->GB->flatrate)) {
					$movie->streaming = $streaming->results->GB->flatrate;
				}
			}
	    }
	    $movie_details = $movie;
	    return view('movieDetails', compact('movie_details'));

    } 

    public function Genres ()
    {
	    $genres =  json_decode(file_get_contents('https://api.themoviedb.org/3/genre/movie/list?api_key=4b71bb38fff24b54a79276a70dd07af3'));
	    foreach ($genres->genres as $genre) {
		    DB::table('genres')->insert([
			    'id' => $genre->id,
		    	     'genre_name' => $genre->name, 
		    ]);
	    }
    }

    public function StoreMovies ($movie_id, $tmdb_id) {
	      $movieDB = DB::table('movieDetails')->select('id')->where('id', $movie_id)->first();
	      if (empty($movieDB)){
	 	$movie_details = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/' . $tmdb_id . '?api_key=4b71bb38fff24b54a79276a70dd07af3'));
                $videos = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/' . $tmdb_id . '/videos?api_key=4b71bb38fff24b54a79276a70dd07af3'));
	        $credits = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/' . $tmdb_id . '/credits?api_key=4b71bb38fff24b54a79276a70dd07af3'));
	        $n = 0;
	        $movie_details->cast = "";
	        $movie_details->director = "";
	        foreach ($credits->cast as $cast) {
		  if ($cast->popularity > 10) {   
		    if ($n > 0) {
		      $movie_details->cast .= ", ";
		    }
		    $movie_details->cast .= $cast->name;
                    $n++;
		  }
	        }
                foreach ($credits->crew as $crew) {
		  if ($crew->job == 'Director') {   
		    $movie_details->director = $crew->name;
		  }
	        }
	        $movie_details->video = NULL;
	        $video_size = 0;
	        foreach ($videos->results as $video) {
		    if ($video->site == 'YouTube' 
			&& $video->iso_639_1 == 'en' 
			&& $video->type == 'Trailer'
			&& $video->official == 1
			&& $video->size > $video_size
			) { 
			    $video_size = $video->size;	
			    $movie_details->video = $video->key;
	            } 
		}
	        DB::table('movieDetails')->insert([
		   'id' => $movie_id,
		   'title' => $movie_details->title,
		   'rating' => $movie_details->vote_average * 10, 
		   'release_date' => $movie_details->release_date,
		   'poster_path' => $movie_details->poster_path,
		   'synopsys' => $movie_details->overview,
		   'cast_members' => $movie_details->cast,
		   'director' => $movie_details->director,
		   'trailer_path' => $movie_details->video,
		   'runtime' => $movie_details->runtime,
	   ]);	
		foreach ($movie_details->genres as $genre)
		{
			DB::table('movie_genre')->insert([
				'movie_id' => $movie_id,
				'genre_id' => $genre->id,
			]);
		}
	      }
	      

    }
    public function UpdateLists ()
    {
	$lists = DB::table('lists')->select('id')->get();
	foreach ($lists as $list) {
	    $list_text = json_decode(file_get_contents('https://api.themoviedb.org/3/list/' . $list->id . '?api_key=4b71bb38fff24b54a79276a70dd07af3'));
  	    foreach ($list_text->items as $item) {
		DB::table('movie_list')->insertOrIgnore([
		    'movie_id' => $item->id,
		    'list_id' => $list->id,
		]);		
	    } 
	}
    }

}
