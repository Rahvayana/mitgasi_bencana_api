<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$router->group(['prefix'=>'apps'],function() use ($router){
    $router->post('/register',[ApiController::class, 'register']);
    $router->post('/login',[ApiController::class, 'login']);
    $router->post('/user',[ApiController::class, 'users']);
    $router->post('/addPengaduan',[ApiController::class, 'addPengaduan']);
    $router->post('/history',[ApiController::class, 'history']);
    $router->get('/tips',[ApiController::class, 'tips']);
    $router->get('/produk',[ApiController::class, 'produk']);
    $router->get('/tentang',[ApiController::class, 'tentang']);
    $router->get('/goldar/{id}',[ApiController::class, 'goldar']);
    $router->get('/getPost',[ApiController::class, 'getPost']);

});