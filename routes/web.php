<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () {
	return redirect()->route('4k');
});
$router->get('/now_showing', function () {
	return redirect()->route('4k');
});
$router->get('/coming_soon', function () {
	return redirect()->route('wanted4k');
});
$router->get('/4k', ['as' => '4k', 'uses' => 'MoviesController@MyMovies4k']);
$router->get('/blu-rays', ['as' => 'br', 'uses' => 'MoviesController@MyMoviesBR']);
$router->get('/steelbooks', ['as' => 'steel', 'uses' => 'MoviesController@MyMoviesSteel']);
$router->get('/wanted-4k', ['as' => 'wanted4k', 'uses' => 'MoviesController@Wanted4k']);
$router->get('/wanted-blurays', ['as' => 'wantedbr', 'uses' => 'MoviesController@WantedBR']);

$router->get('/movie_detail/{movie_id}', ['as' => 'movieDetail', 'uses' => 'MoviesController@MovieDetail']);
//$router->get('/genres', 'MoviesController@Genres');
//$router->get('/store_movies', 'MoviesController@StoreMovies');
//$router->get('/update_lists', 'MoviesController@UpdateLists');
