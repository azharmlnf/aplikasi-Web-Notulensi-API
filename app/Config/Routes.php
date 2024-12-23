<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

service('auth')->routes($routes); //routes shield

// $routes->resource('departemens');

//API ROUTES
$routes->group("api", ["namespace" => 'App\Controllers\Api'], function ($routes) {
    $routes->get("invalid-access", "AuthController::accesDenied");

    //POST
    $routes->post("register", "AuthController::register");
    $routes->post('login', 'AuthController::login');
    //get
    $routes->get("profile", "AuthController::profile", ["filter" => "apiauth"]);
    $routes->get('logout', 'AuthController::logout', ["filter" => "apiauth"]);

    //Agenda
    $routes->post('add-agenda', 'AgendaController::addAgenda');
    $routes->get('list-agenda', 'AgendaController::listAgenda');
    $routes->put('update-agenda/(:num)', 'AgendaController::updateAgenda/$1',);
    $routes->delete('delete-agenda/(:num)', 'AgendaController::deleteAgenda/$1');
    
    //dashboard
    $routes->get('dashboard-list-agenda', 'AgendaController::groupedAgendas');



    // Departemen
    $routes->post('add-departemen', 'DepartemenController::addDepartemen');
    $routes->get('list-departemen', 'DepartemenController::listDepartemen');
    $routes->put('update-departemen/(:num)', 'DepartemenController::updateDepartemen/$1');
    $routes->delete('delete-departemen/(:num)', 'DepartemenController::deleteDepartemen/$1');

    // AgendaUser
    $routes->post('add-personil', 'AgendaUserController::addPersonil');
    $routes->get('list-personil/(:num)', 'AgendaUserController::listPersonil/$1');
    $routes->put('update-personil/(:num)', 'AgendaUserController::updatePersonil/$1');
    $routes->delete('delete-personil/(:num)', 'AgendaUserController::deletePersonil/$1');

    // Notulen
    $routes->post('add-notulen', 'NotulenController::addNotulen');
    $routes->get('list-notulen', 'NotulenController::listNotulen');      
     $routes->put('update-notulen/(:num)', 'NotulenController::updateNotulen/$1');
    $routes->delete('delete-notulen/(:num)', 'NotulenController::deleteNotulen/$1');
});
