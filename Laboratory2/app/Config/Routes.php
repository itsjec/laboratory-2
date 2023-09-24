<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('test','MainController::test');
$routes->post('music/upload', 'MainController::upload');
$routes->get('music', 'MainController::index');
$routes->post('addplaylist', 'MainController::addplaylist');
$routes->post('search', 'MainController::search');
$routes->post('add-to-playlist', 'MainController::addToPlaylist');
$routes->get('playlist/(:num)', 'MainController::viewPlaylist/$1');
$routes->post('addToPlaylist/(:num)/(:num)', 'MainController::addToPlaylist/$1/$2');

