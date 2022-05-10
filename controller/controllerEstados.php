<?php
    /****************************************************************************
     * Objetivo: Arquivo responsável pela manipulação de dados de estados.
     *  Obs:. Este arquivo fará a ponte entre a View e a Model. -  Aciona a model
     * Autora: Florbela
     * Data: 10/05/2022
     * Versão: 1.0
     ****************************************************************************/

     //Import do arquivo de configuração
     require_once('modulo/config.php');

     //Função para solicitar os dados da model e encaminhar a lista de contatos para a View
    function listarEstado () {
        
        //Import do arquivo que vai buscar os dados
        require_once('model/bd/estado.php');

        //Chama a função que vai listar os dados no BD
        $dados = selectAllEstados();

        //Verifica se os contatos retornados pela função 'selectAllContatos' estão vazios
        if(!empty($dados))
            return $dados;
        else
            return false;
    }
?>