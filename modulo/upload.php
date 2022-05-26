<?php

/**************************************************************************************
 * Objetivo: arquivo responsavel em realizar uploads de arquivos
 * Autora: Leila
 * Data: 25/04/2022
 * Versão: 1.0
 **************************************************************************************/

 /* funcao para realizar o upload de imagens */
 function uploadFile($arrayFile)
 { 

    // import do arquivo de configuracao do projeto
    require_once(SRC . 'modulo/config.php');

    $arquivo = $arrayFile;
    $sizeFile = (int) 0;
    $typeFile = (string) null;
    $nameFile = (string) null; 
    $tempFile = (string) null;

    // validacao para identificar se existe um arquivo valido
    // (maior que 0 e que tenha uma extensão)
    if ($arquivo['size'] > 0 && $arquivo['type'] != "")
    {

        // recupera o tamanho do arquivo em bytes e converte para kb (/1024)
        $sizeFile = $arquivo['size'] / 1024;

        // recupera o tipo do arquivo
        $typeFile = $arquivo['type'];

        // recupera o nome do arquivo
        $nameFile = $arquivo['name'];

        // recupera o caminho do diretorio temporario que esta o arquivo
        $tempFile =  $arquivo['tmp_name'];

        // validacao para permitir o upload apenas arquivos de no maximo 5MB
        if ($sizeFile <= MAX_FILE_UPLOAD)
        {
            if(in_array($typeFile, EXT_FILE_UPLOAD))
            {

                // separa somente o nome do arquivo sem a sua extensao
                $nome = pathinfo($nameFile, PATHINFO_FILENAME);

                // separa somente a extencao do arquivo sem o nome
                $extensao = pathinfo($nameFile, PATHINFO_EXTENSION);

                // existem diversos algoritmos para criptografia de dados
                // md5()
                // sha1()
                // hash()

                // md5() - gerando uma criptografia de dados
                // uniqid() - gerando uma sequencia numerica diferente tendo como base configuracoes da maquina
                // time() - pega a hora, minuto e segundo que esta sendo feito o upload da foto
                $nomeCripty = md5($nome.uniqid(time()));

                // montamos novamente o nome do arquivo com a extensao
                $foto = $nomeCripty . "." . $extensao;

                // envia o arqivo da pasta temporaria (criada pelo apache) para a pasta criada no projeto
                if (move_uploaded_file($tempFile, SRC . DIRETORIO_FILE_UPLOAD.$foto)) {
                    return $foto;
                } else {
                    return array(
                        'idErro' => 13, 
                        'message' => 'Não foi possível mover o arquivo para o servidor.'
                    );
                }

            } else {
                return array(
                    'idErro' => 12, 
                    'message' => 'A extensão do arquivo selecionado não é permitida no upload.'
                );    
            }

        } else {
            return array(
                'idErro' => 10, 
                'message' => 'Tamanho de arquivo inválido no upload.'
            );
        }

    } else {
        return array(
            'idErro' => 11, 
            'message' => 'Não é possível realizar o upload sem um arquivo selecionado.'
        );
    }

 }
