<?php
/**
 *    Assessment Title: AT2-POR-Pt2-MVC
 *    Cluster:          SaaS: Front-End Dev - ICT50220 (Advanced Programming)
 *    Qualification:    ICT50220 Diploma of Information Technology (Back End Web Development)
 *    Name:             Yui Migaki
 *    Student ID:       20098757
 *    Year/Semester:    2024/S2
 *
 *    MY SUMMARY OF PORTFOLIO ACTIVITY
 *    This portfolio activity involves implementing an online scratch course into a small MVC project that includes users, categories, and jokes.
 *
 *    Application Route Definitions
 *
 *    Filename:        routes.php
 *    Location:        /
 *    Project:         ym-php-mvc-jokes
 *    Date Created:    06/09/2024
 *
 *    Author:          Yui Migaki <20098757@tafe.wa.edu.au>
 */

/* ----------------------------------------------------------------------------
 * Static Page Endpoints
 */
$router->get('/', 'StaticPageController@index');
$router->get('/about', 'StaticPageController@about');
$router->get('/search', 'StaticPageController@search');

/* ----------------------------------------------------------------------------
 * Jokes Endpoints
 */
$router->get('/jokes', 'JokeController@index');
$router->get('/jokes/create', 'JokeController@create', ['auth']);
$router->get('/jokes/edit/{id}', 'JokeController@edit', ['auth']);
$router->get('/jokes/search', 'JokeController@search');
$router->get('/jokes/{id}', 'JokeController@show');

$router->post('/jokes', 'JokeController@store', ['auth']);
$router->put('/jokes/{id}', 'JokeController@update', ['auth']);
$router->delete('/jokes/{id}', 'JokeController@destroy', ['auth']);

/* ----------------------------------------------------------------------------
 * Categories Endpoints
 */
$router->get('/categories', 'CategoryController@index');
$router->get('/categories/create', 'CategoryController@create', ['auth']);
$router->get('/categories/edit/{id}', 'CategoryController@edit', ['auth']);
$router->get('/categories/search', 'CategoryController@search');
$router->get('/categories/{id}', 'CategoryController@show');

$router->post('/categories', 'CategoryController@store', ['auth']);
$router->put('/categories/{id}', 'CategoryController@update', ['auth']);
$router->delete('/categories/{id}', 'CategoryController@destroy', ['auth']);



/* ----------------------------------------------------------------------------
 * Users Endpoints
 */
$router->get('/users', 'UserController@index');
$router->get('/users/create', 'UserController@create', ['auth']);
$router->get('/users/edit/{id}', 'UserController@edit', ['auth']);
$router->get('/users/search', 'UserController@search');
$router->get('/users/{id}', 'UserController@show');

$router->post('/users', 'UserController@store', ['auth']);
$router->put('/users/{id}', 'UserController@update', ['auth']);
$router->delete('/users/{id}', 'UserController@destroy', ['auth']);


/* ----------------------------------------------------------------------------
 * User Authentication Endpoints
 */
$router->get('/auth/register', 'UserAuthController@create', ['guest']);
$router->get('/auth/login', 'UserAuthController@login', ['guest']);

$router->post('/auth/register', 'UserAuthController@store', ['guest']);
$router->post('/auth/logout', 'UserAuthController@logout', ['auth']);
$router->post('/auth/login', 'UserAuthController@authenticate', ['guest']);


$router->get('/auth/password', 'CreatePasswordController@index', ['guest']);
$router->post('/auth/password', 'CreatePasswordController@index', ['guest']);