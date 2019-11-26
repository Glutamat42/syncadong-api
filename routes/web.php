<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});

    //Customers
$router->GET('/customers/transactions', 'CustomerController@getTransactionLog');
$router->GET('/customers', 'CustomerController@getCustomers');
$router->GET('/customers/{customer_id}', 'CustomerController@getCustomer');
$router->POST('/customers', 'CustomerController@createCustomer');
$router->PUT('/customers', 'CustomerController@updateCustomer');
$router->DELETE('/customers/{customer_id}', 'CustomerController@deleteCustomer');
