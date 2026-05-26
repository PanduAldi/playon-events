<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/email-test', 'Home::emailTest');
$routes->post('/email-test/send', 'Home::sendEmailTest');

$routes->get('/event/(:segment)', 'EventController::detail/$1');
$routes->get('/event/(:segment)/checkout', 'EventController::checkout/$1');
$routes->post('/event/(:segment)/checkout', 'EventController::processCheckout/$1', ['filter' => 'honeypot']);
$routes->get('/event/(:segment)/community', 'EventController::communityRegister/$1');
$routes->post('/event/(:segment)/community', 'EventController::processCommunityRegistration/$1', ['filter' => 'honeypot']);
$routes->get('/event/(:segment)/community/success', 'EventController::communitySuccess/$1');
$routes->get('/event/(:segment)/success', 'EventController::success/$1');

$routes->get('/login', 'AdminController::login');
$routes->post('/login', 'AdminController::processLogin');
$routes->get('/logout', 'AdminController::logout');

$routes->group('admin', ['filter' => 'admin'], function($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('participants', 'AdminController::participants');
    $routes->get('participants/export', 'AdminController::exportParticipants');
    $routes->post('participants/update-status', 'AdminController::updateStatus');
    $routes->get('participants/view/(:num)', 'AdminController::viewParticipant/$1');
    $routes->get('scanner', 'AdminController::scanner');
    $routes->post('scanner/process', 'AdminController::processScan');
    
    // Event CRUD
    $routes->get('events', 'AdminController::events');
    $routes->get('events/new', 'AdminController::newEvent');
    $routes->post('events/create', 'AdminController::createEvent');
    $routes->get('events/edit/(:num)', 'AdminController::editEvent/$1');
    $routes->post('events/update/(:num)', 'AdminController::updateEvent/$1');
    $routes->get('events/delete/(:num)', 'AdminController::deleteEvent/$1');

    // Category CRUD
    $routes->post('categories/create', 'AdminController::createCategory');
    $routes->post('categories/update/(:num)', 'AdminController::updateCategory/$1');
    $routes->get('categories/delete/(:num)', 'AdminController::deleteCategory/$1');
});

