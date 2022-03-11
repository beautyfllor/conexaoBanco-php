<?php
    /***********************************************************************
     * Objetivo: Arquivo responsável por manipular os dados dentro do BD
     *      (insert, update, select e delete).
     * Autora: Florbela
     * Data: 11/03/2022
     * Versão: 1.0
     ***********************************************************************/

     //Import do arquivo que estabelece a conexão com o BD
     require_once('conexaoMysql.php');

     //Função para realizar o insert no BD
     function insertContato($dadosContato) {
        //Abre a conexão com o banco de dados
        $conexao = conexaoMysql();

        //Monta o script para enviar para o BD
        $sql = "insert into tblcontatos
                    (nome, 
                    telefone, 
                    celular, 
                    email, 
                    obs)
                values
                    ('".$dadosContato['nome']."', 
                    '".$dadosContato['telefone']."', 
                    '".$dadosContato['celular']."',
                    '".$dadosContato['email']."', 
                    '".$dadosContato['obs']."');";

        //Executa um script no BD -> Dentro dos (quem é o BD, o que vc quer que eu mande para o BD)
        mysqli_query($conexao, $sql); 
     }
     //Função para realizar o update no BD
     function updateContato() {
         
    }
    //Função para excluir no BD
    function deleteContato() {
         
    }
    //Função para listar todos os contatos do BD
    function selectALllContatos() {
         
    }
?>