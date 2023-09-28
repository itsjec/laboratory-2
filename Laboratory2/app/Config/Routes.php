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
$routes->get('playlist/(:num)', 'MainController::viewPlaylist/$1');
$routes->get('/search', 'MainController::search');
$routes->post('addToPlaylist', 'MainController::addToPlaylist');
$routes->get('/removeFromPlaylist/(:num)', 'MainController::removeFromPlaylist/$1');







