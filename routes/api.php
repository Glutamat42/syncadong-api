<?php

use

$router->get('/', function () use ($router) {
    return $router->app->version();
});

    //Customers
$router->GET('/customers', 'CustomerController@getCustomers');
$router->POST('/customers', 'CustomerController@createCustomer');
$router->PUT('/customers', 'CustomerController@updateCustomer');
$router->DELETE('/customers/{customer_id}', 'CustomerController@deleteCustomer');
