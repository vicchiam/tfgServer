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

$method = (isset($_SERVER['REQUEST_METHOD'])?$_SERVER['REQUEST_METHOD']:"");
if($method == "OPTIONS"){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: *");   
    die();
}
else{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: *");   
}

$routes->get('/test', 'App\Controllers\Api\UserController::index');

$routes->post('/login', 'App\Controllers\Api\LoginController::index');

$routes->group("/api", ["namespace" => "App\Controllers\Api", 'filter' => 'auth' ] , function($routes){
    
    $routes->group("user", function($routes){
       $routes->get("list", "UserController::index");
       $routes->get("single/(:num)", "UserController::show/$1");       
    });

    $routes->group("centro", function($routes){
        $routes->get("list", "CentroController::index");
        $routes->get("single/(:num)", "CentroController::show/$1");
    });

    $routes->group("ubicacion", function($routes){
        $routes->get("list", "UbicacionController::index");
        $routes->get("single/(:num)", "UbicacionController::show/$1");
        $routes->get("list/centro/(:num)", "UbicacionController::showByCentro/$1");
        $routes->get("list/descripcion/centro/(:any)/(:any)", "UbicacionController::showByDescriptionCentro/$1/$2");
    });

    $routes->group("maquina", function($routes){
        $routes->get("list", "MaquinaController::index");
        $routes->get("single/(:num)", "MaquinaController::show/$1");
        $routes->get("list/ubicacion/(:num)", "MaquinaController::showByUbicacion/$1");
        $routes->get("list/descripcion/centro/(:any)/(:any)", "MaquinaController::showByDescriptionUbicacion/$1/$2");
    });

    $routes->group("producto", function($routes){
        $routes->get("list", "ProductoController::index");
        $routes->get("list/invent", "ProductoController::showWithInventario");
        $routes->get("single/(:num)", "ProductoController::show/$1");
        $routes->get("code/(:any)", "ProductoController::showByCode/$1");
        $routes->get("description/(:any)", "ProductoController::showByDescription/$1");
    });

    $routes->group("instalacion", function($routes){
        $routes->get("list", "InstalacionController::index");
        $routes->get("single/(:num)", "InstalacionController::show/$1");
        $routes->get("list/centro/(:any)", "InstalacionController::showByCentro/$1");
        $routes->get("code/(:any)", "InstalacionController::showByCode/$1");
        $routes->get("description/(:any)", "InstalacionController::showByDescription/$1");
        
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
        $routes->post("filter", "FaltaController::filter"); 
        $routes->get("date/(:any)", "FaltaController::showByFecha/$1");
        $routes->post("save", "FaltaController::save");
        $routes->put("update/(:num)", "FaltaController::update/$1");
        $routes->delete("delete/(:num)", "FaltaController::delete/$1");
        $routes->get("producto/list/(:num)", "FaltaController::showProductos/$1");
        $routes->post("producto/add", "FaltaController::addProducto");
        $routes->put("producto/update", "FaltaController::updateProducto");
        $routes->delete("producto/delete/(:num)", "FaltaController::deleteProducto/$1");
    });

    $routes->group("orden", function($routes){
        $routes->get("list", "OrdenController::index");
        $routes->get("single/(:num)", "OrdenController::show/$1");   
        $routes->post("filter", "OrdenController::filter");  
        $routes->post('add',"OrdenController::create");
        $routes->put('update/(:num)',"OrdenController::update/$1");
        $routes->put('estado/(:num)',"OrdenController::updateEstado/$1");
        $routes->get("tecnicos/list/(:num)", "OrdenController::showTecnicos/$1");        
        $routes->post("tecnicos/add","OrdenController::addTecnico");
        $routes->put("tecnicos/update/(:num)","OrdenController::updateTecnico/$1");
        $routes->delete("tecnicos/delete/(:num)","OrdenController::deleteTecnico/$1");
        $routes->get("productos/list/(:num)", "OrdenController::showProductos/$1");
        $routes->post("productos/add","OrdenController::addProducto");
        $routes->put("productos/update/(:num)","OrdenController::updateProducto/$1");
        $routes->delete("productos/delete/(:num)","OrdenController::deleteProducto/$1");        
    });

    $routes->group("info", function($routes){        
        $routes->get("single/(:num)", "InfoController::show/$1");
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

