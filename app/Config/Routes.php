<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
/*
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);
*/

$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes->get('/', 'Home::index');

$routes->get('/test', 'App\Controllers\Api\UserController::index');

$routes->post('login', 'App\Controllers\Api\LoginController::index');

$routes->group("/api", ["namespace" => "App\Controllers\Api", 'filter' => 'auth'] , function($routes){
    
    $routes->group("user", function($routes){
       $routes->get("list", "UserController::index");
       $routes->get("single/(:num)", "UserController::show/$1");
       //$routes->get("test", "UserController::auth");
    });

    $routes->group("centro", function($routes){
        $routes->get("list", "CentroController::index");
        $routes->get("single/(:num)", "CentroController::show/$1");
    });

    $routes->group("ubicacion", function($routes){
        $routes->get("list", "UbicacionController::index");
        $routes->get("single/(:num)", "UbicacionController::show/$1");
    });

    $routes->group("maquina", function($routes){
        $routes->get("list", "MaquinaController::index");
        $routes->get("single/(:num)", "MaquinaController::show/$1");
    });

    $routes->group("producto", function($routes){
        $routes->get("list", "ProductoController::index");
        $routes->get("single/(:num)", "ProductoController::show/$1");
        $routes->get("code/(:any)", "ProductoController::showByCode/$1");
        $routes->get("description/(:any)", "ProductoController::showByDescription/$1");
    });

    $routes->group("instalacion", function($routes){
        $routes->get("list", "InstalacionController::index");
        $routes->get("single/(:num)", "InstalacionController::show/$1");
        $routes->get("code/(:any)", "InstalacionController::showByCode/$1");
        $routes->get("description/(:any)", "InstalacionController::showByDescription/$1");
        $routes->get("centro/(:any)", "InstalacionController::showByCentro/$1");
    });
     
    $routes->group("inventario", function($routes){
        $routes->get("list", "InventarioController::index");
        $routes->get("single/(:num)", "InventarioController::show/$1");
        $routes->get("producto/(:any)", "InventarioController::showByProducto/$1");
        $routes->post("add", "InventarioController::add");
        $routes->post("substract", "InventarioController::substract");
    });

    $routes->group("falta", function($routes){
        $routes->get("list", "FaltaController::index");
        $routes->get("single/(:num)", "FaltaController::show/$1");
        $routes->get("date/(:any)", "FaltaController::showByFecha/$1");
        $routes->put("update/(:num)", "FaltaController::update/$1");
        $routes->delete("delete/(:num)", "FaltaController::delete/$1");
        $routes->get("producto/list/(:num)", "FaltaController::showProductos/$1");
        $routes->post("producto/add", "FaltaController::addProducto");
        $routes->put("producto/update", "FaltaController::updateProducto");
        $routes->delete("producto/delete/(:num)", "FaltaController::deleteProducto/$1");
    });

});


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */

if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

