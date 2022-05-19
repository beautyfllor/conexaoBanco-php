<?php

    //Import do arquivo autoload, que fará as instâncias do slim
    require_once('vendor/autoload.php');

    //Criando um objeto do slim chamado app, para configurar os Endpoints
    $app = new \Slim\App();

    //EndPoint: Requisição para listar todos os contatos
    $app->get('/contatos', function($request, $response, $args){
        $response->write('Testando a API pelo get');
    });

    //EndPoint: Requisição para listar contatos pelo id
    $app->get('/contatos/{id}', function($request, $response, $args){

    });

    //EndPoint: Requisição para inserir um novo contato
    $app->get('/contatos', function($request, $response, $args){

    });

?>