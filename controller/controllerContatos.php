<?php
    /****************************************************************************
     * Objetivo: Arquivo responsável pela manipulação de dados de contatos.
     *  Obs:. Este arquivo fará a ponte entre a View e a Model. -  Aciona a model
     * Autora: Florbela
     * Data: 04/03/2022
     * Versão: 1.0
     ****************************************************************************/

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
            //Import do arquivo de modelagem para manipular o BD
            require_once('model/bd/contato.php');
                //Chamando a função que fará o insert no BD (esta função está na model)
                if(insertContato($arrayDados))
                    return true;
                else
                    return array('idErro' => 1, 
                                'message' => 'Não foi possível inserir os dados no Banco de Dados.'
                    );
            } else 
                return array('idErro' => 2, 
                            'message' => 'Existem campos obrigatórios que não foram preenchidos.'
                );
        }
    }

     //Função para receber dados da Wiew e encaminhar para a Model (atualizar)
     function atualizarContato () {

    }

    //Função para realizar a exclusão de um contato
    function excluirContato ($id) {
        //Validação para verificar se o id é um núemro válido
        if($id != 0 && !empty($id) && is_numeric($id)) {

            //Import do arquivo de contato - model
            require_once('model/bd/contato.php');

            //Chama a função da model e valida se o retorno foi true ou false
            if(deleteContato($id))
                return true;
            else
                return array('idErro' => 3, 
                            "message" => "O banco de dados não pode excluir o registro."
                );
        } else
            return array('idErro' => 4, 
                        "message" => "Não é possível excluir um registro sem informar um id válido."        
            );
    }

    //Função para solicitar os dados da model e encaminhar a lista de contatos para a View
    function listarContato () {
        //Import do arquivo que vai buscar os dados
        require_once('model/bd/contato.php');
        //Chama a função que vai listar os dados no BD
        $dados = selectAllContatos();

        //Verifica se os contatos retornados pela função 'selectAllContatos' estão vazios
        if(!empty($dados))
            return $dados;
        else
            return false;
    }

    //Função para buscar um contato através do id do registro
    function buscarContato ($id) {
        //Validação para verificar se o id é um núemro válido
        if($id != 0 && !empty($id) && is_numeric($id)) {

            //Import do arquivo de contato - model
            require_once('model/bd/contato.php');

            //Chama a função na model que vai buscar no BD
            $dados = selectByIdContato($id);

            //Valida se existe dados para serem devolvidos
            if(!empty($dados))
                return $dados;
            else
                return false;

        } else {
            return array('idErro' => 4, 
            "message" => "Não é possível buscar um registro sem informar um id válido."        
)           ;
        }
    }
?>