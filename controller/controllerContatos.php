<?php
    /****************************************************************************
     * Objetivo: Arquivo responsável pela manipulação de dados de contatos.
     *  Obs:. Este arquivo fará a ponte entre a View e a Model. -  Aciona a model
     * Autora: Florbela
     * Data: 04/03/2022
     * Versão: 1.0
     ****************************************************************************/

     //Import do arquivo de configuração
     require_once('modulo/config.php');

     //Função para receber dados da Wiew e encaminhar para a Model (inserir)
     function inserirContato ($dadosContato, $file) {

        $nomeFoto = (string) null;

        //Validação para verificar se o objeto está vazio
        if(!empty($dadosContato)){
            //Validação de caixa vazia dos elementos nome, celular e email, pois são obrigatórios no banco de dados
            if(!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])){/*O que fica no colchete é o 'name' da input*/
                //Validação para identificar se chegou um arquivo para upload
                if($file['fleFoto']['name'] != null) {
                    //Import da função de upload
                    require_once('modulo/upload.php');

                    //Chama a função de upload
                    $nomeFoto = uploadFile($file['fleFoto']);
                    
                    if(is_array($nomeFoto)) {
                        /*Caso aconteça um erro no upload, a função irá retornar um array com a mensagem de erro. 
                        Esse array será retornado para a router e ela exibirá o resultado para o usuário*/
                        return $nomeFoto;
                    }
                }
                //Criação de um array de dados que será encaminhado a model para inserir no BD, 
                //é importante criar este array conforme as necessidades de manipulação do BD
                //OBS: criar as chaves do array conforme os nomes dos atributos do BD.
                $arrayDados = array (
                    "nome"     => $dadosContato['txtNome'],
                    "telefone" => $dadosContato['txtTelefone'],
                    "celular"  => $dadosContato['txtCelular'],
                    "email"    => $dadosContato['txtEmail'],
                    "obs"      => $dadosContato['txtObs'],
                    "foto"     => $nomeFoto
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
     function atualizarContato ($dadosContato, $arrayDados) {

        $statusUpload = (boolean) false;

        //Recebe o id enviado pelo arrayDados
        $id = $arrayDados['id'];

        //Recebe a foto enviada pelo arrayDados (nome da foto já existente no BD)
        $foto = $arrayDados['foto'];

        //Recebe o objeto de array referente a nova foto que poderá ser enviada ao servidor
        $file = $arrayDados['file'];

        //Validação para verificar se o objeto está vazio
        if(!empty($dadosContato)){
            //Validação de caixa vazia dos elementos nome, celular e email, pois são obrigatórios no banco de dados
            if(!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])){/*O que fica no colchete é o 'name' da input*/

                //Validação para garantir que o id seja válido
                if(!empty($id) && $id != 0 && is_numeric($id)) {

                    //Validação para identificar se será enviado ao servidor uma nova foto
                    if($file['fleFoto']['name'] != null) {
                        //Import da função de upload
                        require_once('modulo/upload.php');

                        //Chama a função de upload para enviar a nova foto ao servidor
                        $novaFoto = uploadFile($file['fleFoto']);

                        $statusUpload = true;
                    } else {
                        //Permanece a mesma foto no BD
                        $novaFoto = $foto;
                    }

                    //Criação de um array de dados que será encaminhado a model para inserir no BD, é importante criar este array conforme as necessidades de manipulação do BD
                    //OBS: criar as chaves do array conforme os nomes dos atributos do BD.
                    $arrayDados = array (
                        "id"       => $id,
                        "nome"     => $dadosContato['txtNome'],
                        "telefone" => $dadosContato['txtTelefone'],
                        "celular"  => $dadosContato['txtCelular'],
                        "email"    => $dadosContato['txtEmail'],
                        "obs"      => $dadosContato['txtObs'],
                        "foto"     => $novaFoto
                    );
                    //Import do arquivo de modelagem para manipular o BD
                    require_once('model/bd/contato.php');

                    //Chamando a função que fará o insert no BD (esta função está na model)
                    if(updateContato($arrayDados)) {
                        /* *Validação para verificar se será necessário apagar a foto antiga
                        * Esta variável foi ativada true na linha 90, quando realizamos 
                        o upload em uma nova foto no servidor*/
                        if($statusUpload) {
                            //Apaga a foto antiga da pasta do servidor
                            unlink(DIRETORIO_FILE_UPLOAD.$foto);
                        }
                        return true;
                    } else
                        return array('idErro' => 1, 
                                    'message' => 'Não foi possível atualizar os dados no Banco de Dados.'
                        );
                } else
                    return array('idErro' => 4, 
                                "message" => "Não é possível editar um registro sem informar um id válido."        
                    );
            } else 
                return array('idErro' => 2, 
                            'message' => 'Existem campos obrigatórios que não foram preenchidos.'
                );
        }
    }

    //Função para realizar a exclusão de um contato
    function excluirContato ($arrayDados) {

        //Recebe o id do registro que será excluído
        $id = $arrayDados['id'];

        //Recebe o nome da da foto que será excluída da pasta do servidor
        $foto = $arrayDados['foto'];

        //Validação para verificar se o id é um núemro válido
        if($id != 0 && !empty($id) && is_numeric($id)) {

            //Import do arquivo de contato - model
            require_once('model/bd/contato.php');

            //Import do arquivo de configurações do projeto
            require_once('modulo/config.php');

            //Chama a função da model e valida se o retorno foi true ou false
            if(deleteContato($id)) {

                if($foto != null) {
                     /*unlink() - função para excluir um arquivo físico de um diretório*/
                    /*Permite apagar a foto fisicamente do diretório do servidor*/
                    if(unlink(DIRETORIO_FILE_UPLOAD.$foto))
                        return true;
                    else
                        return array( 'idErro' => 5, 
                                'message' => 'O registro foi excluído, mas a imagem não'
                        );
                    
                }
                return true;
            }else
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
                        "message" => "Não é possível buscar um registro sem informar um id válido.");
        }
    }
?>