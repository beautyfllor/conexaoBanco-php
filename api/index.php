<?php
    /*********************************************************
     * Objetivo: Arquivo principal da API que irá receber a url requisitada e redirecionar para as APIs. (router)
     * Autora: Florbela
     * Data: 19/05/2022
     * Versão: 1.0
     *********************************************************/
    
    //Ativa quais endereços de sites poderão fazer requisições na API (* = todos os sites)
    header('Access-Control-Allow-Origin: *');
    //Ativa os métodos do protocolo HTTP que irão requisitar a API
    header('Access-Control-Allow-Methods: GET, DELETE, POST, PUT, OPTIONS');
    //Ativa o Content-Type  (Formato de dados que será usado (JSON, XML..)) das requisições
    header('Access-Control-Allow-Header: Content-Type');
    //Libera quais Content-type serão usados na API
    header('Content-Type: application/json');

    //Recebe a url digitada na requisição
    $urlHTTP = (string) $_GET['url'];

    //Converte a url requisitada em um array para dividir as opções de busca, que é separada pela barra
    $url = explode('/', $urlHTTP);

    //Verifica qual a API será encaminhada a requisição (contatos, estados, etc)
    switch (strtoupper($url[0])) {
        case 'CONTATOS':
            require_once('contatosAPI/index.php');
            break;
            
        case 'ESTADOS':
            require_once('contatosAPI/index.php');
            break;
    }

?>