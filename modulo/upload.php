<?php
    /***********************************************************************
     * Objetivo: Arquivo responsável por realizar uploads de arquivos
     * Autora: Florbela
     * Data: 25/04/2022
     * Versão: 1.0
     ***********************************************************************/

     //Função para realizar upload de imagens
     function uploadFile($arrayFile) {

        //Import do arquivo de configurações do projeto
        require_once('modulo/config.php');
        
        $arquivo = $arrayFile;
        $sizeFile = (int) 0;
        $typeFile = (string) null;
        $nameFile = (string) null;
        $tempFile = (string) null;

        //Validação para identificar se existe um arquivo arquivo válido (Tamanho maior que 0 e uma extensão)
        if($arquivo['size'] > 0 && $arquivo['type'] != "") {

            //Recupera o tamanho do arquivo que é em bytes e converte para kb ( /1024 )
            $sizeFile = $arquivo['size']/1024; 

            //Recupera o tipo do arquivo
            $typeFile = $arquivo['type'];

            //Recupera o nome do arquivo
            $nameFile = $arquivo['name'];

            //Recupera o caminho do diretório temporário que está o arquivo
            $tempFile = $arquivo['tmp_name'];

            //Validação para permitir upload apenas de arquivos de no máximo 5MB
            if($sizeFile <= MAX_FILE_UPLOAD) {

                //Validação para permitir somente as extensões válidas
                if(in_array($typeFile, EXT_FILE_UPLOAD)) {

                    //Separa somente o nome do arquivo sem a extensão (Temos apenas o nome do arquivo)
                    $nome = pathInfo($nameFile, PATHINFO_FILENAME);

                    // Separa somente a extensão do arquivo, sem o nome (Temos apenas a extensão do arquivo)
                    $extensao = pathInfo($nameFile, PATHINFO_EXTENSION);

                    //Existem diversos algoritmos para criptografia de dados. No PHP tem:
                        //md5()
                        //sha1()
                        //hash()

                    //md5() - Gera uma criptografia de dados;
                    //uniqid - Gera uma sequência numérica diferente, tendo como base as configurações da máquina;
                    //time - Pega a hora:minuto:segundo que está sendo feito o upload do arquivo
                    $nomeCripty = md5($nome.uniqid(time()));

                    //Montando novamento o nome do arquivo com a extensão 
                    $foto = $nomeCripty.".".$extensao;

                    if(move_uploaded_file($tempFile, DIRETORIO_FILE_UPLOAD.$foto)) {
                        return $foto;
                    } else {
                        return array('idErro' => 13, 
                                    'message' => 'Não foi possível mover o arquivo para o servidor.'
                                );
                    }

                } else {
                    return array('idErro' => 12, 
                                'message' => 'A extensão do arquivo selecionado não é permitida no upload.'
                    );
                }

            } else {
                return array('idErro' => 10, 
                            'message' => 'Tamanho de arquivo inválido no upload.'
                );
            }

        } else {
            return array('idErro' => 11, 
                        'message' => 'Não é possível realizar o upload sem um arquivo selecionado.'
            );
        }
     }
?>