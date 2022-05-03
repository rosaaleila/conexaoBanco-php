<?php

/***********************************************************************
 * Objetivo: Arquivo responsável pela manipulação de dados de contatos.
 *  Obs:. Este arquivo fará a ponte entre a View e a Model
 * Autora: Leila
 * Data: 04/03/2022
 * Versão: 1.4
 ***********************************************************************/

//Função para receber dados da Wiew e encaminhar para a Model (inserir)
function inserirContato($dadosContato, $file)
{

    $nomeFoto = (string) null;

    //Validação para verificar se o objeto está vazio
    if (!empty($dadosContato)) {
        //Validação de caixa vazia dos elementos nome, celular e email, pois são obrigatórios no banco de dados
        if (!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])) {/*O que fica no colchete é o 'name' da input*/

            // validacao para identificar se chegou um arquivo para upload
            if($file['fleFoto']['name'] != null) {

                //import da funcao uploadfile
                require_once('modulo/upload.php');
                
                // chama a funcao de upload
                $nomeFoto = uploadFile($file['fleFoto']);
                
                // se a variavel for do tipo array, ela ira conter o erro retornado por uploadFile
                if(is_array($nomeFoto)) {
                    return $nomeFoto; 
                } 
            }

            //Criação de um array de dados que será encaminhado a model para inserir no BD, é importante criar este array conforme as necessidades de manipulação do BD
            //OBS: criar as chaves do array conforme os nomes dos atributos do BD.
            $arrayDados = array(
                "nome"     => $dadosContato['txtNome'],
                "telefone" => $dadosContato['txtTelefone'],
                "celular"  => $dadosContato['txtCelular'],
                "email"    => $dadosContato['txtEmail'],
                "obs"      => $dadosContato['txtObs'],
                "foto"     => $nomeFoto
            );

            //Import do arquivo contato para manipular o bd
            require_once('model/bd/contato.php');
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

    //Validação para verificar se o objeto está vazio
    if (!empty($dadosContato)) {
        //Validação de caixa vazia dos elementos nome, celular e email, pois são obrigatórios no banco de dados
        if (!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])) {/*O que fica no colchete é o 'name' da input*/
            // validação para garantir que o id seja válido
            if (!empty($id) && is_numeric($id) && $id != 0) {
                
                // validacao para identificar se sera enviado ao servidor uma nova foto 
                if($file['fleFoto']['name'] != null) {

                    // import da funcao de upload
                    require_once('modulo/upload.php');

                    // chama a funcao de upload para enviar a nova foto ao servidor
                    $novaFoto = uploadFile($file['fleFoto']);

                } else {

                    // permanece a mesma foto no BD
                    $novaFoto = $foto;
                
                }
                
                //Criação de um array de dados que será encaminhado a model para editar no BD, é importante criar este array conforme as necessidades de manipulação do BD
                //OBS: criar as chaves do array conforme os nomes dos atributos do BD.
                
                $arrayDados = array(
                    "id"       => $id,
                    "nome"     => $dadosContato['txtNome'],
                    "telefone" => $dadosContato['txtTelefone'],
                    "celular"  => $dadosContato['txtCelular'],
                    "email"    => $dadosContato['txtEmail'],
                    "foto"     => $novaFoto,
                    "obs"      => $dadosContato['txtObs']
                );

                //Import do arquivo contato para manipular o bd
                require_once('model/bd/contato.php');

                //Chamando a função updateContato (essa função está na model)
                if (updateContato($arrayDados)) {
                    unlink(DIRETORIO_FILE_UPLOAD.$foto);
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
        require_once('model/bd/contato.php');
        require_once('modulo/config.php');

        // chama a função da model e valida se o retorno foi true ou false
        if (deleteContato($id))
        {

            // unlink() - funcao para apagar um arquivo de um diretorio
            // permite apagar a foto fisicamente do diretorio no servidor

            if($foto != null)
            {
                if(unlink(DIRETORIO_FILE_UPLOAD.$foto))
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
    // import do arquivo que vai buscar os dados
    require_once('model/bd/contato.php');

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
        require_once('model/bd/contato.php');

        // chama a funcao que vai buscar os dados no BD
        $dados = selectByIdContato($id);

        // valida se existem dados para serem devolvidos
        if (!empty($dados))
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
