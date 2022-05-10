<?php
    /****************************************************************************
     * Objetivo: Arquivo responsável por manipular os dados dentro do BD.
     *  (select)
     * Autora: Florbela
     * Data: 10/05/2022
     * Versão: 1.0
     ****************************************************************************/

     //Import do arquivo que estabelece a conexão com o BD
     require_once('conexaoMysql.php');

     function selectAllEstados() {
        //Abre a conexao com o BD
         $conexao = conexaoMysql();

        //Script para listar todos os dados do BD
        $sql = "select * from tblestados order by nome asc";
        //desc - descendente -> asc - ascendente

        /*Quando enviamos um script para o banco do tipo 
        insert, delete ou update, eles não retornam nada,
        já o select retorna os dados*/

        //Executa o script sql no BD e guarda o retorno dos dados, se houver
        $result = mysqli_query($conexao, $sql);

        //Valida se o BD retornou registros
         if($result){

             /*mysqli_fetch_assoc() - permite converter os 
             dados do BD em um array para manipulação no PHP*/

             /*Nesta repetição, estamos convertendo os dados 
             do BD em um array ($rsDados), além de o próprio while conseguir 
             gerenciar a qtde de vezes que deverá ser feita a repetição */

             $cont = 0;
             while($rsDados = mysqli_fetch_assoc($result)){
                 //Cria um array com os dados do BD
                $arrayDados[$cont] = array(
                    "idestado"   => $rsDados['idestado'],
                    "nome"       => $rsDados['nome'],
                    "sigla"      => $rsDados['sigla']
                );
                $cont++;
             }

             //Solicita o fechamento da conexão com o BD
             fecharConexaoMysql($conexao);

             return $arrayDados;
         }
    }
?>