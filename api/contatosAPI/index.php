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

        //Recebe o id do registro que devrá ser retornado pela API
        //Obs:. Esse id está chegando pela variável criada no endpoint
        $id = $args['id'];

        //Import do arquivo de configuração do projeto, para que a constante 'SRC' exista na controller
        require_once('../modulo/config.php');

        //Import da controller de contatos, que fará a busca de dados
        require_once('../controller/controllerContatos.php');

        //Solicita os dados para a controller
        if($dados = buscarContato($id)) {
            //Verifica se houve algum tipo de erro no retorno dos dados da controller
            if(!isset($dados['idErro'])) {
                //Realiza a conversão do array de dados em formato JSON
                if($dadosJSON = createJSON($dados)){
                    //Caso exista dados a serem retornados, informamos o statusCode 200 e enviamos um JSON com todos os dados encontrados
                    return $response    ->withStatus(200) 
                                        ->withHeader('Content-Type', 'application/json') 
                                        ->write($dadosJSON); 
                }
            } else {
                //Converte para JSON o erro, pois a controller retorna um array
                $dadosJSON = createJSON($dados);

                //Retorna um erro que significa que o cliente passou dados errados
                return $response    ->withStatus(404)
                                    ->withHeader('Content-Type', 'application/json')
                                    ->write('{"message": "Dados inválidos",
                                              "Erro": '.$dadosJSON.'
                                              }');
            }
        } else {
            //Retorna um statusCode que significa que a requisição foi aceita, porém sem conteúdo de retorno
            return $response    ->withStatus(204);
        }
    });

    //EndPoint: Requisição para deletar contatos pelo id
    $app->delete('/contatos/{id}', function($request, $response, $args) {

        if(is_numeric($args['id'])) {

            //Recebe o id enviado no EndPoint através da variável id
            $id = $args['id'];

            //Import do arquivo de configuração do projeto, para que a constante 'SRC' exista na controller
            require_once('../modulo/config.php');

            //Import da controller de contatos, que fará a busca de dados
            require_once('../controller/controllerContatos.php');

            //Busca o nome da foto para ser excluída na controller
            if($dados = buscarContato($id)) {
                
                //Recebe o nome da foto que a controller retornou
                $foto = $dados['foto'];

                //Cria um array com o id e o nome da foto a ser enviada para a controller excluir o registro
                $arrayDados = array (
                    "id"    => $id,
                    "foto"  => $foto
                );

                //Chama a função de excluir contato, encaminhando o array com o id e a foto
                $resposta = excluirContato($arrayDados);

                if(is_bool($resposta) && $resposta == true) {

                    //Retorna uma mensagem que deu certo
                    return $response    ->withStatus(200) 
                                        ->withHeader('Content-Type', 'application/json') 
                                        ->write('{"message": "Registro excluído com sucesso!"}');

                } elseif(is_array($resposta) && isset($resposta['idErro'])) {

                    //Validação referente ao erro 5, que significa que o registro foi excluído do BD e a imagem não existia no servidor
                    if($resposta['idErro'] == 5) {

                        //Retorna uma mensagem 
                        return $response    ->withStatus(200) 
                                            ->withHeader('Content-Type', 'application/json') 
                                            ->write('{"message": "Registro excluído com sucesso, porém houve um problema na exclusão da imagem na pasta do servidor."}');
                    } else {
                        //Converte para JSON o erro, pois a controller retorna um array
                         $dadosJSON = createJSON($resposta);

                        //Retorna um erro que significa que o cliente passou dados errados
                        return $response    ->withStatus(404)
                                            ->withHeader('Content-Type', 'application/json')
                                            ->write('{"message": "Houve um problema no processo de excluir",
                                                    "Erro": '.$dadosJSON.'
                                                    }');
                    }
                }
            } else {
                //Retorna um erro que significa que o cliente informou um id inválido
                return $response    ->withStatus(404) 
                                    ->withHeader('Content-Type', 'application/json') 
                                    ->write('{"message": "O id informado não existe na base de dados."}'); 
            }
        } else {
            //Retorna um erro que significa que o cliente passou dados errados
            return $response    ->withStatus(404) 
                                ->withHeader('Content-Type', 'application/json') 
                                ->write('{"message": "É obtigatório informar um id com formato válido (número)."}'); 
        }
    });

    //EndPoint: Requisição para inserir um novo contato
    $app->post('/contatos', function($request, $response, $args){

        //Recebe o header da requisição e qual será o Content-Type
        $contentTypeHeader = $request->getHeaderLine('Content-Type');

        //Cria um array,pois dependendo do content-type temos mais informações separadas pelo (;)
        $contentType = explode(";", $contentTypeHeader);

        switch ($contentType[0]) {
            case 'multipart/form-data':

                //Recebe os dados comuns enviado pelo corpo da requisição
                $dadosBody = $request->getParsedBody();
                
                //Recebe uma imagem enviada pelo corpo da requisição
                $uploadFiles = $request->getUploadedFiles();

                /*Cria um array com todos os dados que chegaram pela requisição, devido aos dados 
                serem protegidos, criamos um array e recuperamos os dados pelos métodos do objeto*/
                $arrayFoto = array(    "name"      => $uploadFiles['foto']->getClientFileName(),
                                        "type"      => $uploadFiles['foto']->getClientMediaType(),
                                        "size"      => $uploadFiles['foto']->getSize(),
                                        "tmp_name"  => $uploadFiles['foto']->file
                );

                //Cria uma chave chamada "foto" para colocar todos os dados do objeto, conforme é gerado em form HTML
                $file = array("foto" => $arrayFoto);

                //Cria um array com todos os dados comuns do arquivo que será enviado para o servidor
                $arrayDados = array(    $dadosBody,
                                        "file" => $file
                );

                //Import do arquivo de configuração do projeto, para que a constante 'SRC' exista na controller
                require_once('../modulo/config.php');

                //Import da controller de contatos, que fará a busca de dados
                require_once('../controller/controllerContatos.php');

                //Chama a função da controller para inserir os dados
                $resposta = inserirContato($arrayDados);

                if(is_bool($resposta) && $resposta == true) {

                    return $response    ->withStatus(201) 
                                        ->withHeader('Content-Type', 'application/json') 
                                        ->write('{"message": "Registro inserido com sucesso."}');
                } elseif(is_array($resposta) && $resposta['idErro']) {

                    //Cria o JSON dos dados do erro
                    $dadosJSON = createJSON($resposta);

                    return $response    ->withStatus(400)
                                        ->withHeader('Content-Type', 'application/json')
                                        ->write('{"message": "Houve um problema no processo de inserir",
                                                "Erro": '.$dadosJSON.'
                                                }');
                }

                break;
            case 'application/json':

                $dadosBody = $request->getParsedBody();
                var_dump($dadosBody);
                die;

                return $response    ->withStatus(200) 
                                    ->withHeader('Content-Type', 'application/json') 
                                    ->write('{"message": "Formato selecionado foi JSON"}');
                break;
            default:
            return $response    ->withStatus(400) 
                                ->withHeader('Content-Type', 'application/json') 
                                ->write('{"message": "Formato do Content-Type não é válido para essa requisição"}');
                break;
        }
    });

    //Executa todos os Endpoints
    $app->run();

?>