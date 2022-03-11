<?php
    /***********************************************************************
     * Objetivo: Arquivo responsável pela manipulação de dados de contatos.
     *  Obs:. Este arquivo fará a ponte entre a View e a Model
     * Autora: Florbela
     * Data: 04/03/2022
     * Versão: 1.0
     ***********************************************************************/

     //Função para receber dados da Wiew e encaminhar para a Model (inserir)
     function inserirContato ($dadosContato) {
        //Validação para verificar se o objeto está vazio
        if(!empty($dadosContato)){
            //Validação de caixa vazia dos elementos nome, celular e email, pois são obrigatórios no banco de dados
        if(!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])){/*O que fica no colchete é o 'name' da input*/
            //Criação de um array de dados que será encaminhado a model para inserir no BD, é importante criar este array conforme as necessidades de manipulação do BD
            //OBS: criar as chaves do array conforme os nomes dos atributos do BD.
            $arrayDados = array (
                "nome"     => $dadosContato['txtNome'],
                "telefone" => $dadosContato['txtTelefone'],
                "celular"  => $dadosContato['txtCelular'],
                "email"    => $dadosContato['txtEmail'],
                "obs"      => $dadosContato['txtObs']
            );
            //Import do arquivo contato
            require_once('model/bd/contato.php');
            //Chamando a função insertContato
            insertContato($arrayDados);
        }else {
            echo('Dados incompletos');
            }
        }
    }

     //Função para receber dados da Wiew e encaminhar para a Model (atualizar)
     function atualizarContato () {

    }

    //Função para realizar a exclusão de um contato
    function excluirContato () {

    }

    //Função para solicitar os dados da model e encaminhar a lista de contatos para a View
    function listarContato () {

    }
?>