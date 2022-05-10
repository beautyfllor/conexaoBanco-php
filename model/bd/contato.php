<?php
    /***********************************************************************
     * Objetivo: Arquivo responsável por manipular os dados dentro do BD
     *      (insert, update, select e delete). - "A model"
     * Autora: Florbela
     * Data: 11/03/2022
     * Versão: 1.0
     ***********************************************************************/

     //Import do arquivo que estabelece a conexão com o BD
     require_once('conexaoMysql.php');

     //Função para realizar o insert no BD
     function insertContato($dadosContato) {

        //Declaração de variável para utilizar no return desta função
        $statusResposta = (boolean) false;

        //Abre a conexão com o banco de dados
        $conexao = conexaoMysql();

        //Monta o script para enviar para o BD
        $sql = "insert into tblcontatos
                    (nome, 
                    telefone, 
                    celular, 
                    email, 
                    obs,
                    foto,
                    idestado)
                values
                    ('".$dadosContato['nome']."', 
                    '".$dadosContato['telefone']."', 
                    '".$dadosContato['celular']."',
                    '".$dadosContato['email']."', 
                    '".$dadosContato['obs']."',
                    '".$dadosContato['foto']."',
                    '".$dadosContato['idestado']."'
                );";

        //Executa um script no BD -> Dentro dos (quem é o BD, o que vc quer que eu mande para o BD)
            //Validação para verificar se o script 'sql' está certo
        if (mysqli_query($conexao, $sql)) {
            //Validação para verificar se uma linha foi acrescentada no BD
            if(mysqli_affected_rows($conexao))
                $statusResposta = true;
        }
        
        //Solicita o fechamento da conexão com o BD ****************
        fecharConexaoMysql($conexao);

        return $statusResposta;
     }
     //Função para realizar o update no BD
     function updateContato($dadosContato) {
         //Declaração de variável para utilizar no return desta função
        $statusResposta = (boolean) false;

        //Abre a conexão com o banco de dados
        $conexao = conexaoMysql();

        //Monta o script para atualizar no BD
        $sql = "update tblcontatos set
                    nome        = '".$dadosContato['nome']."', 
                    telefone    = '".$dadosContato['telefone']."', 
                    celular     = '".$dadosContato['celular']."', 
                    email       = '".$dadosContato['email']."', 
                    obs         = '".$dadosContato['obs']."',
                    foto        = '".$dadosContato['foto']."',
                    idestado    = '".$dadosContato['idestado']."'
                where idcontato =".$dadosContato['id'];

        //Executa um script no BD -> Dentro dos (quem é o BD, o que vc quer que eu mande para o BD)
            //Validação para verificar se o script 'sql' está certo
        if (mysqli_query($conexao, $sql)) {
            //Validação para verificar se uma linha foi acrescentada no BD
            if(mysqli_affected_rows($conexao))
                $statusResposta = true;
        }
        
        //Solicita o fechamento da conexão com o BD ****************
        fecharConexaoMysql($conexao);
        
        return $statusResposta;
    }
    //Função para excluir no BD
    function deleteContato($id) {

        //Declaração de variável para utilizar no return desta função
        $statusResposta = (boolean) false;

        //Abre a conexão com o BD
        $conexao = conexaoMysql();
        
        //Script para deletar um registro do BD
        $sql = "delete from tblcontatos where idcontato = ".$id;

        //Valida se o script está correto, sem erro de sintaxe e executa o BD
        if(mysqli_query($conexao, $sql)) {
            //Valida se o BD teve sucesso na execução do script
            if(mysqli_affected_rows($conexao))
                $statusResposta = true;
        }
        fecharConexaoMysql($conexao);
        return $statusResposta;
    }
    //Função para listar todos os contatos do BD
    function selectAllContatos() {
        //Abre a conexao com o BD
         $conexao = conexaoMysql();

        //Script para listar todos os dados do BD
        $sql = "select * from tblcontatos order by idcontato desc";
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
                    "id"         => $rsDados['idcontato'],
                    "nome"       => $rsDados['nome'],
                    "telefone"   => $rsDados['telefone'],
                    "celular"    => $rsDados['celular'],
                    "email"      => $rsDados['email'],
                    "obs"        => $rsDados['obs'],
                    "foto"       => $rsDados['foto'],
                    "idestado"       => $rsDados['idestado']
                );
                $cont++;
             }

             //Solicita o fechamento da conexão com o BD
             fecharConexaoMysql($conexao);

             //Validação para ver se o array existe - (banco vazio)
             if(isset($arrayDados))
                return $arrayDados;
            else
                return false;
         }
    }

    //Função para buscar um contato no BD, através do id do registro
    function selectByIdContato($id) {
        //Abre a conexao com o BD
        $conexao = conexaoMysql();

        //Script para listar todos os dados do BD
        $sql = "select * from tblcontatos where idcontato = ".$id;

        //Executa o script sql no BD e guarda o retorno dos dados, se houver
        $result = mysqli_query($conexao, $sql);

        //Valida se o BD retornou registros
         if($result){
            //Se houver dados... gera o array
             if($rsDados = mysqli_fetch_assoc($result)){ 
                 //Cria um array com os dados do BD
                $arrayDados = array(
                    "id"         => $rsDados['idcontato'],
                    "nome"       => $rsDados['nome'],
                    "telefone"   => $rsDados['telefone'],
                    "celular"    => $rsDados['celular'],
                    "email"      => $rsDados['email'],
                    "obs"        => $rsDados['obs'],
                    "foto"       => $rsDados['foto'],
                    "idestado"       => $rsDados['idestado']
                );
             }

             //Solicita o fechamento da conexão com o BD
             fecharConexaoMysql($conexao);

             return $arrayDados;
         }
    }
?>