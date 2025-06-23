<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */



// Halaman utama
$routes->get('/', 'Xplorea::index');
$routes->get('xplorea', 'Xplorea::index');

// Auth routes
$routes->post('auth/upgradeToArtist', 'Auth::upgradeToArtist');
$routes->post('auth/login', 'Auth::login');
$routes->get('logout', 'Auth::logout');
$routes->post('auth/signup', 'Auth::signup');
$routes->get('xplorea/login', 'Xplorea::login');
$routes->get('xplorea/signup', 'Xplorea::signup');
$routes->group('artist', function ($routes) {
    $routes->get('products', 'Artist::products');
    $routes->get('create-product', 'Artist::createProduct');
    $routes->post('save-product', 'Artist::saveProduct');

    $routes->get('product/(:num)', 'Artist::viewProduct/$1');
    $routes->get('product/(:num)/edit', 'Artist::editProduct/$1');
    $routes->post('product/(:num)/update', 'Artist::updateProduct/$1');
    $routes->post('product/(:num)/delete', 'Artist::deleteProduct/$1');
});

$routes->get('/cart', 'CartController::cart');         
$routes->post('/cart/add/(:num)', 'CartController::add/$1'); 
$routes->get('/cart/show', 'CartController::show');    
$routes->get('/cart/remove/(:num)', 'CartController::remove/$1'); 
$routes->get('/checkout', 'CartController::checkout');

$routes->post('/checkout/place-order', 'CheckoutController::placeOrder');
$routes->post('checkout/process', 'CheckoutController::process');
$routes->get('checkout/success', 'CheckoutController::success');


$routes->get('xplorea/modal', 'Xplorea::modal');
$routes->get('xplorea/marketplace', 'Xplorea::marketplace');
$routes->get('xplorea/paintings', 'Xplorea::paintings');
$routes->get('xplorea/drawings', 'Xplorea::drawings');
$routes->get('xplorea/digital', 'Xplorea::digital');
$routes->get('xplorea/becomeartist', 'Xplorea::becomeartist');
$routes->get('xplorea/profile', 'Xplorea::profile');
$routes->get('xplorea/events', 'PublicEventController::events');


// Admin routes - all protected by admin filter
$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    // Dashboard
    $routes->get('/', 'AdminController::dashboard');
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('dashboard/chart-data', 'AdminController::getChartData');

    // Profile
    $routes->get('profile', 'AdminController::profile');
    $routes->post('profile/update', 'AdminController::updateProfile');

    // Artist management
    $routes->group('artists', function ($routes) {
        $routes->get('/', 'AdminController::artists');
        $routes->get('approvals', 'AdminController::artistApprovals');
        $routes->get('view/(:num)', 'AdminController::viewArtist/$1');
        $routes->post('approve/(:num)', 'AdminController::approveArtist/$1');
        $routes->post('reject/(:num)', 'AdminController::rejectArtist/$1');
        $routes->get('revoke/(:num)', 'AdminController::revokeArtist/$1');
    });

    // Artworks/Products management
    $routes->get('artworks', 'AdminController::artworks');
    $routes->get('artworks/approve/(:num)', 'AdminController::approveArtwork/$1');
    $routes->get('artworks/reject/(:num)', 'AdminController::rejectArtwork/$1');
    $routes->get('artworks/edit/(:num)', 'AdminController::editArtwork/$1');
    $routes->post('artworks/update/(:num)', 'AdminController::updateArtwork/$1');
    $routes->get('artworks/delete/(:num)', 'AdminController::deleteArtwork/$1');

    // User management
    $routes->group('users', function ($routes) {
        $routes->get('/', 'AdminController::users');
        $routes->get('view/(:num)', 'AdminController::viewUser/$1');
        $routes->post('add', 'AdminController::addUser');
        $routes->get('edit/(:num)', 'AdminController::editUser/$1');
        $routes->post('update/(:num)', 'AdminController::updateUser/$1');
        $routes->post('delete/(:num)', 'AdminController::deleteUser/$1');
        $routes->post('activate/(:num)', 'AdminController::activateUser/$1');
        $routes->post('deactivate/(:num)', 'AdminController::deactivateUser/$1');
        $routes->post('toggle-status', 'AdminController::toggleUserStatus');
    });

    // Settings
    $routes->get('settings', 'AdminController::settings');
    $routes->post('settings', 'AdminController::updateSettings');

    // Events
    $routes->get('events', 'AdminEventController::index');
    $routes->get('events/create', 'AdminEventController::create');
    $routes->post('events/store', 'AdminEventController::store');
    $routes->get('events/(:num)', 'AdminEventController::show/$1');
    $routes->get('events/toggle/(:num)', 'AdminEventController::toggleStatus/$1');
    $routes->get('events/delete/(:num)', 'AdminEventController::delete/$1');
});

$routes->get('xplorea/community', 'CommunityController::index');
$routes->get('xplorea/community/new-thread', 'CommunityController::newThread');
$routes->post('xplorea/community/create-thread', 'CommunityController::createThread');
$routes->get('xplorea/community/thread/(:num)', 'CommunityController::viewThread/$1');
$routes->post('xplorea/community/thread/(:num)/reply', 'CommunityController::addReply/$1');
$routes->get('xplorea/community/search', 'CommunityController::search');

$routes->post('cart/update/(:num)', 'CartController::updateQuantity/$1');

// Artist Event Routes
$routes->group('artist', ['filter' => 'auth'], function($routes) {
    $routes->get('events', 'EventController::myEvents');
    $routes->get('events/create', 'EventController::create');
    $routes->post('events', 'EventController::store');
    $routes->get('events/edit/(:num)', 'EventController::edit/$1');
    $routes->post('events/update/(:num)', 'EventController::update/$1');
    $routes->get('events/delete/(:num)', 'EventController::delete/$1');
    $routes->get('events/attendees/(:num)', 'EventController::attendees/$1');
});

// Public Event Routes
$routes->get('events', 'PublicEventController::events');
$routes->get('events/(:num)', 'PublicEventController::show/$1');
$routes->post('events/book/(:num)', 'PublicEventController::book/$1');
$routes->get('my-bookings', 'PublicEventController::myBookings');

// Order History Routes
$routes->get('order-history', 'CheckoutController::orderHistory');

$routes->post('xplorea/profile/update', 'Xplorea::updateProfile');

$routes->get('menu', 'Xplorea::menu');
$routes->get('menu/(:segment)', 'Xplorea::menu/$1');

$routes->get('support/(:segment)', 'Xplorea::support/$1');

$routes->post('admin/users/delete/(:num)', 'AdminController::deleteUser/$1');

$routes->get('artist/orders', 'ArtistOrderController::index');
$routes->get('artist/orders/accept/(:num)', 'ArtistOrderController::accept/$1');
$routes->get('artist/orders/reject/(:num)', 'ArtistOrderController::reject/$1');