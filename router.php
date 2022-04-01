<?php

    /**************************************************************************************
     * Objetivo: Arquivo de rota, para segmentar as ações encaminhadas pela Wiew
     *     (Dados de um form, listagem de dados, ação de excluir ou atualizar).
     *      Esse arquivo será responsável por encaminhar as solicitações para a Controller.
     * Autora: Florbela
     * Data: 04/03/2022
     * Versão: 1.0
     **************************************************************************************/

    $action = (string) null;
    $component = (string) null;

    //Validação para verificar se a requisição é um POST 
    if($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET') {

        //Recebendo dados via URL para saber quem está solicitando e qual ação será realizada
        $component = strtoupper($_GET['component']);
        $action = strtoupper($_GET['action']);

        //Estrutura condicional para validar quem está solicitando algo para o Router
        switch ($component) {
            case 'CONTATOS';

                //Import da controller Contatos
                require_once('controller/controllerContatos.php');

                //Verificando o tipo de ação
                if($action == 'INSERIR') {
                    //Chama a função de inserir na controller e envia o objeto POST para a função inserirContato 
                    $resposta = inserirContato($_POST);
                    //Valida o tipo de dados que a controller retornou 
                    if(is_bool($resposta)) { //Se for booleano
                        //Verificar se o retorno foi verdadeiro
                        if($resposta) {   
                        //window.location.href -> Forçar ir para a index.
                        echo("<script>alert('Registro inserido com sucesso!'); window.location.href = 'index.php' </script>");
                        }
                    //Se um retorno for um array significa que houve erro no processo de inserção 
                    } else if(is_array($resposta))
                            echo("<script>alert('". $resposta["message"] ."'); window.location.href = 'index.php' </script>");
                } elseif($action == 'DELETAR'){
                    /*Recebe o id do registro que deverá ser excluído, que foi enviado 
                    pela url no link da imagem do excluir que foi acionado na index*/
                    $idContato = $_GET['id'];

                    //Chama a função de excluir na controller
                    $resposta = excluirContato($idContato);

                    if(is_bool($resposta)){
                        if($resposta) {
                            echo("<script>alert('Registro excluído com sucesso!'); window.location.href = 'index.php' </script>");
                        }
                    }elseif(is_array($resposta)){
                        echo("<script>alert('". $resposta["message"] ."'); window.location.href = 'index.php' </script>");
                    }
                } elseif ($action == 'BUSCAR') {
                    /*Recebe o id do registro que deverá ser editado, que foi enviado 
                    pela url no link da imagem do editar que foi acionado na index*/
                    $idContato = $_GET['id'];

                    //Chama a função de buscar na controller
                    $dados = buscarContato($idContato);

                    /* Variável de sessão: mantém o valor ativo, independente das
                    transições de página, só é desativada quando o navegador é fechado*/
                    //A variável de sessão vem por padrão desabilitada

                    //Ativa a utilização de variáveis de sessão no servidor
                    session_start();

                    //Guarda em uma  variável de sessão os dados que o BD retornou para a busca do id
                    /*Obs:. Essa variável de sessão será utilizada na index.php, para colocar 
                    os dados mnas caixas de texto*/ 
                    $_SESSION['dadosContato'] = $dados;

                    /*Utilizando o header também poderemos chamar a index.php, porém haverá uma ação 
                    de carregamento no navegador (piscando a tela novamente)*/
                    // header('location: index.php');

                    /*Utilizando o require iremos apenas importar a tela da index, 
                    assim não havendo um novo carregamento da página*/
                    require_once('index.php');
                }
            break;
        }

    }
?>
