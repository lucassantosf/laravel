<?php

$router->group(['prefix' => 'api'], function () use ($router){

    $router->post('register', 'AuthController@register'); 
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh'); 

    $router->group(['middleware' => ['auth','check_permission']], function () use ($router){         
        $router->group(['prefix'=> 'usuario', 'as'=>'usuario'], function () use ($router) {
            $router->get('me', ['as'=>'me','uses'=> 'AuthController@me']);           
        }); 
        
        $router->group(['prefix'=> 'role', 'as'=>'role'], function () use ($router) {
            $router->get('', ['as'=>'index','uses'=> 'RoleController@index']);            
        });
    });
});