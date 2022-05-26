<?php

/***********************************************************************
 * Objetivo: Arquivo responsável pela manipulação de dados de contatos.
 *  Obs:. Este arquivo fará a ponte entre a View e a Model
 * Autora: Leila
 * Data: 04/03/2022
 * Versão: 1.7
 ***********************************************************************/

 if(strpos(getcwd(), 'api')) {
    require_once(SRC . '/modulo/config.php');
    define('consumoAPI', true);
} else {
    require_once('./modulo/config.php');
    define('consumoAPI', false);
 }

//Função para receber dados da Wiew e encaminhar para a Model (inserir)
function inserirContato($dadosContato)
{

    $nomeFoto = (string) null;
    
    //Validação para verificar se o objeto está vazio
    if (!empty($dadosContato)) {

        // recebe a imagem encaminhada dentro do array
        $file = $dadosContato['file'];
        
        //Validação de caixa vazia dos elementos nome, celular e email, pois são obrigatórios no banco de dados
        if (!empty($dadosContato[0]['nome']) && !empty($dadosContato[0]['celular']) && !empty($dadosContato[0]['email']) && !empty($dadosContato[0]['estado'])) {/*O que fica no colchete é o 'name' da input*/

            // validacao para identificar se chegou um arquivo para upload
            if ($file['foto']['name'] != null) {

                //import da funcao uploadfile
                require_once(SRC . 'modulo/upload.php');

                // chama a funcao de upload
                $nomeFoto = uploadFile($file['foto']);

                // se a variavel for do tipo array, ela ira conter o erro retornado por uploadFile
                if (is_array($nomeFoto)) {
                    return $nomeFoto;
                }
            }

            //Criação de um array de dados que será encaminhado a model para inserir no BD, é importante criar este array conforme as necessidades de manipulação do BD
            //OBS: criar as chaves do array conforme os nomes dos atributos do BD.
            $arrayDados = array(
                "nome"     => $dadosContato[0]['nome'],
                "telefone" => $dadosContato[0]['telefone'],
                "celular"  => $dadosContato[0]['celular'],
                "email"    => $dadosContato[0]['email'],
                "obs"      => $dadosContato[0]['obs'],
                "foto"     => $nomeFoto,
                "idestado" => $dadosContato[0]['estado']
            );

            //Import do arquivo contato para manipular o bd
            require_once(SRC . 'model/bd/contato.php');
            //Chamando a função insertContato (essa função está na model)
            if (insertContato($arrayDados))
                return true;
            else
                return array('idErro' => 1, 'message' => 'Não foi possivel inserir os dados no Banco de Dados');
        } else {
            return array('idErro' => 2, 'message' => 'Existem campos obrigatórios que não foram preenchidos.');
        }
    }
}

//Função para receber dados da Wiew e encaminhar para a Model (atualizar)
function atualizarContato($dadosContato, $arrayDados)
{

    // recebe os itens dentro do objeto arraydados
    $id = $arrayDados['id'];
    $foto = $arrayDados['foto'];
    $file = $arrayDados['file'];
    $statusUpload = (bool) false;

    //Validação para verificar se o objeto está vazio
    if (!empty($dadosContato)) {
        //Validação de caixa vazia dos elementos nome, celular e email, pois são obrigatórios no banco de dados
        if (!empty($dadosContato['nome']) && !empty($dadosContato['celular']) && !empty($dadosContato['email']) && !empty($dadosContato['estado'])) {/*O que fica no colchete é o 'name' da input*/
            // validação para garantir que o id seja válido
            if (!empty($id) && is_numeric($id) && $id != 0) {

                // validacao para identificar se sera enviado ao servidor uma nova foto 
                if ($file['fleFoto']['name'] != null) {

                    // import da funcao de upload
                    require_once('modulo/upload.php');

                    // chama a funcao de upload para enviar a nova foto ao servidor
                    $novaFoto = uploadFile($file['fleFoto']);
                    $statusUpload = true;
                } else {

                    // permanece a mesma foto no BD
                    $novaFoto = $foto;
                }

                //Criação de um array de dados que será encaminhado a model para editar no BD, é importante criar este array conforme as necessidades de manipulação do BD
                //OBS: criar as chaves do array conforme os nomes dos atributos do BD.

                $arrayDados = array(
                    "id"       => $id,
                    "nome"     => $dadosContato['nome'],
                    "telefone" => $dadosContato['telefone'],
                    "celular"  => $dadosContato['celular'],
                    "email"    => $dadosContato['email'],
                    "foto"     => $novaFoto,
                    "idestado" => $dadosContato['estado'],
                    "obs"      => $dadosContato['obs']
                );

                //Import do arquivo contato para manipular o bd
                require_once('model/bd/contato.php');

                //Chamando a função updateContato (essa função está na model)
                if (updateContato($arrayDados)) {
                    // validando se devemos apagar a foto
                    // essa variavel foi ativada em true na verificao do conteudo file
                    if ($statusUpload) {
                        unlink(DIRETORIO_FILE_UPLOAD . $foto);
                    }
                    return true;
                } else
                    return array(
                        'idErro' => 1,
                        'message' => 'Não foi possivel atualizar os dados no Banco de Dados.'
                    );
            } else
                return array(
                    'idErro'    => 4,
                    'message'   => 'Não é possível editar um registro com ID inválido.'
                );
        } else {
            return array(
                'idErro' => 2,
                'message' => 'Existem campos obrigatórios que não foram preenchidos.'
            );
        }
    }
}

//Função para realizar a exclusão de um contato
function excluirContato($dadosContato)
{

    // recebe o id do registro que sera excluido
    $id = $dadosContato['id'];

    // recebe o nome da foto que sera excluida
    $foto = $dadosContato['foto'];

    // if para verificar se o id é diferente de 0, se não está vazio e se é um número
    if ($id != 0 && !empty($id) && is_numeric($id)) {

        //import do arquivo de contato
        require_once(SRC . 'model/bd/contato.php');

        // chama a função da model e valida se o retorno foi true ou false
        if (deleteContato($id)) {

            // unlink() - funcao para apagar um arquivo de um diretorio
            // permite apagar a foto fisicamente do diretorio no servidor

            if ($foto != null) {
                if (unlink(SRC . DIRETORIO_FILE_UPLOAD . $foto))
                    return true;
                else
                    return array(
                        'idErro'    => 5,
                        'message'   => "A imagem não foi excluida."
                    );
            } else
                return true;
        }
    } else
        return array(
            'idErro'   => 4,
            'message'   => 'Não é possível excluir um registro sem informar um ID válido.'
        );
}

//Função para solicitar os dados da model e encaminhar a lista de contatos para a View
function listarContato()
{
    if (consumoAPI == true) {
        echo('dentro da api');
    } else {
        echo('fora da api');
    }
    
    // import do arquivo que vai buscar os dados
    require_once(SRC .'model/bd/contato.php');

    // chama a funcao que vai buscar os dados no BD
    $dados = selectAllContatos();

    // valida se existem dados para serem devolvidos
    if (!empty($dados))
        return $dados;
    else
        return false;
}

// função para buscar um contato atraves do id do registro
function buscarContato($id)
{

    // if para verificar se o id é diferente de 0, se não está vazio e se é um número
    if ($id != 0 && !empty($id) && is_numeric($id)) {

        //import do arquivo de contato
        require_once(SRC . 'model/bd/contato.php');

        // chama a funcao que vai buscar os dados no BD
        $dados = selectByIdContato($id);

        // valida se existem dados para serem devolvidos
        if (!empty($dados) && !is_bool($dados))
            return $dados;
        else
            return false;
    } else {
        return array(
            'idErro'   => 4,
            'message'   => 'Não é possível buscar um registro sem informar um ID válido.'
        );
    }
}
