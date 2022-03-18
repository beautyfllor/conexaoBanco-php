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
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        //Recebendo dados via URL para saber quem está solicitando e qual ação será realizada
        $component = strtoupper($_GET['component']);
        $action = strtoupper($_GET['action']);

        //Estrutura condicional para validar quem está solicitando algo para o Router
        switch ($component) {
            case 'CONTATOS';
                //import da controller Contatos
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
                            echo("<script>alert('não foi salvo'); window.location.href = 'index.php' </script>");
                    }
            break;
        }

    }
?>
