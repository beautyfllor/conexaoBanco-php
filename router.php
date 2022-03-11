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

                //Verificando o que foi passado para o action
                if($action == 'INSERIR')
                    //Enviando o objeto POST para a função inserirContato
                    inserirContato($_POST);
                    
                break;
        }

    }
?>
