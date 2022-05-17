<?php

Route::get('/', 'ContatoController@index');

Route::get('/cadastrar','ContatoController@create');

Route::post('/contato','ContatoController@store');

Route::get('/contato','ContatoController@listar');

Route::get('/contato/editar/{id}','ContatoController@edit');

Route::post('/contato/editar/{id}','ContatoController@update');

Route::get('/contato/remover/{id}','ContatoController@destroy');

Route::get('/dashboard', 'DashboardControlador@index');
