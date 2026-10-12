<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\BrandController;
use App\Controllers\CategoryController;
use App\Controllers\AttributeController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\FavoritesController;
use App\Controllers\OrderController;

return [
    //method:/route => [controller::class, function]
    'POST:/login'          => [AuthController::class, 'login'],
    'POST:/logout'         => [AuthController::class, 'logout'],
    'POST:/register'       => [AuthController::class, 'register'],
    'GET:/user'            => [UserController::class, 'getUser'],
    'GET:/user/orders'     => [UserController::class, 'getOrders'],

    'POST:/user'           => [UserController::class, 'updateUser'],
    'GET:/user/counts'     => [UserController::class, 'getCountHandledItems'],
    'GET:/user/handled-items' => [UserController::class, 'getCountHandledItems'],
    'GET:/countHandledItems'  => [UserController::class, 'getCountHandledItems'],

    'GET:/brands'          => [BrandController::class, 'getAll'],
    'POST:/brands'         => [BrandController::class, 'handle'],
    'GET:/brand'           => [BrandController::class, 'getById'],

    'GET:/categories'      => [CategoryController::class, 'getAll'],
    'POST:/categories'     => [CategoryController::class, 'handle'],
    'GET:/category'        => [CategoryController::class, 'getById'],

    'GET:/attributes'      => [AttributeController::class, 'getAll'],
    'POST:/attributes'     => [AttributeController::class, 'handle'],
    'GET:/attribute'       => [AttributeController::class, 'getById'],

    'GET:/catalog'         => [ProductController::class, 'getCatalog'],
    'POST:/catalog'        => [ProductController::class, 'getCatalog'],
    'GET:/catalog/filters' => [ProductController::class, 'getFilters'],
    'POST:/catalog/filters'=> [ProductController::class, 'getFilters'],
    'GET:/catalog_filters' => [ProductController::class, 'getFilters'],
    'POST:/catalog_filters'=> [ProductController::class, 'getFilters'],
    'GET:/product'         => [ProductController::class, 'getProduct'],
    'POST:/product'        => [ProductController::class, 'handle'],

    'GET:/cart'            => [CartController::class, 'getCart'],
    'POST:/cart'           => [CartController::class, 'handle'],
    'POST:/cart/update'    => [CartController::class, 'updateQuantity'],
    'POST:/cart/remove'    => [CartController::class, 'removeItem'],
    'POST:/cart/add'       => [CartController::class, 'addItem'],

    'GET:/favorites'       => [FavoritesController::class, 'getFavorites'],
    'POST:/favorites/add'    => [FavoritesController::class, 'addItem'],
    'POST:/favorites/remove' => [FavoritesController::class, 'removeItem'],
    'POST:/favorites'      => [FavoritesController::class, 'handle'],

    'GET:/orders'          => [OrderController::class, 'getOrders'],
    'POST:/orders'         => [OrderController::class, 'createOrder'],
];
