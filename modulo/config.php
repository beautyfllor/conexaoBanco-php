<?php
    /*********************************************************************************
     * Objetivo: Arquivo responsável pela criação de variáveis e constantes do projeto
     * Autora: Florbela
     * Data: 25/04/2022
     * Versão: 1.0
     *********************************************************************************/

     /******************** VARIÁVEIS E CONSTANTES GLOBAIS DO PROJETO *******************/

     //Limitação de 5MB para upload de imagens
     const MAX_FILE_UPLOAD = 5120;
     const EXT_FILE_UPLOAD = array("image/jpg", "image/png", "image/jpeg", "image/gif");
     const DIRETORIO_FILE_UPLOAD = "arquivos/";

     define('SRC', $_SERVER['DOCUMENT_ROOT'].'/Florbela/conexaoBancoPhp/');

    /******************** FUNÇÕES GLOBAIS PARA O PROJETO *******************/

    //Função para converter um array em um formato JSON
    function createJSON($arrayDados) {
        //Validação para tratar array sem dados
        if(!empty($arrayDados)) {
            //Configura o padrão da conversão para formato JSON
            header('Content-Type: application/json');

            $dadosJSON = json_encode($arrayDados);

            //json_encode(); - converte um array para JSON
            //json_decode(); - converte um JSON para array

            return $dadosJSON;
        } else {
            return false;
        }
    }

?>