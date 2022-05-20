<?php

    /*
     *  $request    - Recebe dados do corpo da requisição (JSON, FORM/DATA, XML, etc..).
     *  $response   - Envia dados de retorno da API.
     *  $args       - Permite receber dados de atributos na API.
     */

    //Import do arquivo autoload, que fará as instâncias do slim
    require_once('vendor/autoload.php');

    //Criando um objeto do slim chamado app, para configurar os Endpoints
    $app = new \Slim\App();

    //EndPoint: Requisição para listar todos os contatos
    $app->get('/contatos', function($request, $response, $args){

        //Import do arquivo de configuração do projeto, para que a constante 'SRC' exista na controller
        require_once('../modulo/config.php');

        //Import da controller de contatos, que fará a busca de dados
        require_once('../controller/controllerContatos.php');

        //Solicita os dados para a controller
        if($dados = listarContato()) {
            //Realiza a conversão do array de dados em formato JSON
            if($dadosJSON = createJSON($dados)){
                //Caso exista dados a serem retornados, informamos o statusCode 200 e enviamos um JSON com todos os dados encontrados
                return $response    ->withStatus(200) //Status code da requisição
                                    ->withHeader('Content-Type', 'application/json') //Content-Type da requisição
                                    ->write($dadosJSON); //O que será encaminhado
            }
        } else {
            //Retorna um statusCode que significa que a requisição foi aceita, porém sem conteúdo de retorno
            return $response    ->withStatus(204);
        }
    });

    //EndPoint: Requisição para listar contatos pelo id
    $app->get('/contatos/{id}', function($request, $response, $args){

        $id = $args['id'];

        //Import do arquivo de configuração do projeto, para que a constante 'SRC' exista na controller
        require_once('../modulo/config.php');

        //Import da controller de contatos, que fará a busca de dados
        require_once('../controller/controllerContatos.php');

        //Solicita os dados para a controller
        if($dados = buscarContato($id)) {
            //Realiza a conversão do array de dados em formato JSON
            if($dadosJSON = createJSON($dados)){
                //Caso exista dados a serem retornados, informamos o statusCode 200 e enviamos um JSON com todos os dados encontrados
                return $response    ->withStatus(200) //Status code da requisição
                                    ->withHeader('Content-Type', 'application/json') //Content-Type da requisição
                                    ->write($dadosJSON); //O que será encaminhado
            }
        } else {
            //Retorna um statusCode que significa que a requisição foi aceita, porém sem conteúdo de retorno
            return $response    ->withStatus(204);
        }
    });

    //EndPoint: Requisição para inserir um novo contato
    $app->post('/contatos', function($request, $response, $args){

    });

    //Executa todos os Endpoints
    $app->run();

?>