<?php 
use Gac\Routing\Exceptions\CallbackNotFound;
use Gac\Routing\Exceptions\RouteNotFoundException;
use Gac\Routing\Request;
use Gac\Routing\Response;
use Gac\Routing\Routes;

$routes = new Routes();
try {
    
    $routes->add(
        '/welcome',
        [ \App\Controllers\WelcomeController::class, 'Welcome' ],
        Routes::GET 
    );
    $routes->handle();
} catch ( RouteNotFoundException $ex ) {
    $routes->request->status(404, 'Route not found')
    ->header("Content-Type", "application/json")
    ->send([ 'error' => [ 'message' => $ex->getMessage() ] ]);
} catch ( CallbackNotFound $ex ) {
    $routes->request->status(404, 'Callback method not found')
        ->header("Content-Type", "application/json")
        ->send([ 'error' => [ 'message' => $ex->getMessage() ] ]);
} catch ( Exception $ex ) {
    $code = $ex->getCode() ?? 500;
    $routes->request->status($code)
    ->header("Content-Type", "application/json")
    ->send([ 'error' => [ 'message' => $ex->getMessage() ] ]);
}