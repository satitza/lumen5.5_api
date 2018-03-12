<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/hotel', 'middleware' => 'auth'], function () use ($router) {
    $router->get('all_hotels', 'HotelsController@GetAllHotels');
    $router->get('hotel_id/{id}', 'HotelsController@GetHotelId');
    $router->get('hotel_name/{name}', 'HotelsController@GetHotelName');
});

$router->group(['prefix' => 'api/restaurant', 'middleware' => 'auth'], function () use ($router){
    $router->get('all_restaurants', 'RestaurantsController@GetAllRestaurants');
    $router->get('restaurant_id/{id}', 'RestaurantsController@GetRestaurantId');
    $router->get('restaurant_name/{name}', 'RestaurantsController@GetRestaurantName');

    $router->get('all_restaurant_hotel_id/{id}', 'RestaurantsController@GetAllRestaurantHotelId');
    $router->get('all_restaurant_hotel_name/{name}', 'RestaurantsController@GetAllRestaurantHotelName');
});

$router->group(['prefix' => 'api/menu', 'middleware' => 'auth'], function () use($router){
    $router->get('all_menus', 'SetMenusController@GetAllMenus');
    $router->get('menu_id/{id}', 'SetMenusController@GetMenuId');
    $router->get('menu_name/{name}', 'SetMenusController@GetMenuName');

    $router->get('all_menu_hotel_id/{id}', 'SetMenusController@GetAllMenuHotelId');
    $router->get('all_menu_hotel_name/{name}', 'SetMenusController@GetAllMenuHotelName');

    $router->get('all_menu_restaurant_id/{id}', 'SetMenusController@GetAllMenuRestaurantId');
    $router->get('all_menu_restaurant_name/{name}', 'SetMenusController@GetAllMenuRestaurantName');
});

