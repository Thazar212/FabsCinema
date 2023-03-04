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
	return redirect()->route('nowShowing');
});
$router->get('/now_showing[/{page}]', ['as' => 'nowShowing', 'uses' => 'MoviesController@MyMovies']);
$router->get('/coming_soon[/{page}]', ['as' => 'comingSoon', 'uses' => 'MoviesController@ComingSoon']);
$router->get('/movie_detail/{movie_id}', ['as' => 'movieDetail', 'uses' => 'MoviesController@MovieDetail']);
//$router->get('/genres', 'MoviesController@Genres');
//$router->get('/store_movies', 'MoviesController@StoreMovies');
//$router->get('/update_lists', 'MoviesController@UpdateLists');
