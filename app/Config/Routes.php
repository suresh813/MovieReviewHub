<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

/* Home page */
$routes->get('/', 'Auth::home');

/* Authentication routes */
$routes->get('/login',        'Auth::login');
$routes->post('/checkLogin',  'Auth::checkLogin');
$routes->get('/register',     'Auth::register');
$routes->post('/saveUser',    'Auth::saveUser');
$routes->get('/logout',       'Auth::logout');

/* Movies routes */
$routes->get('/movies',                     'Movies::index');
$routes->get('/addmovie',                   'Movies::add');
$routes->post('/savemovie',                 'Movies::save');
$routes->get('/movies/details/(:num)',      'Movies::details/$1');
$routes->post('/movies/search',             'Movies::search');
$routes->post('/movies/delete/(:num)',      'Movies::delete/$1');

/* Reviews */
$routes->post('/reviews/add',               'Reviews::add');
$routes->post('/reviews/delete/(:num)',     'Reviews::delete/$1');
$routes->get('/reviews/history',            'Reviews::history');
