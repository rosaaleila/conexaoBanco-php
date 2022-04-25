<?php

/***********************************************************************
 * Objetivo: Arquivo responsável pela manipulação de dados de contatos.
 *  Obs:. Este arquivo fará a ponte entre a View e a Model
 * Autora: Leila
 * Data: 04/03/2022
 * Versão: 1.2
 ***********************************************************************/

//Função para receber dados da Wiew e encaminhar para a Model (inserir)
function inserirContato($dadosContato, $file)
{
    //Validação para verificar se o objeto está vazio
    if (!empty($dadosContato)) {
        //Validação de caixa vazia dos elementos nome, celular e email, pois são obrigatórios no banco de dados
        if (!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])) {/*O que fica no colchete é o 'name' da input*/

            if($file != null) {
                require_once('modulo/upload.php');
                $resultado = uploadFile($file['fleFoto']);
                echo($resultado);
                die;
            }

            //Criação de um array de dados que será encaminhado a model para inserir no BD, é importante criar este array conforme as necessidades de manipulação do BD
            //OBS: criar as chaves do array conforme os nomes dos atributos do BD.
            $arrayDados = array(
                "nome"     => $dadosContato['txtNome'],
                "telefone" => $dadosContato['txtTelefone'],
                "celular"  => $dadosContato['txtCelular'],
                "email"    => $dadosContato['txtEmail'],
                "obs"      => $dadosContato['txtObs']
            );

            //Import do arquivo contato para manipular o bd
            require_once('model/bd/contato.php');
            //Chamando a função insertContato (essa funcção está na model)
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
function atualizarContato($dadosContato, $id)
{
    //Validação para verificar se o objeto está vazio
    if (!empty($dadosContato)) {
        //Validação de caixa vazia dos elementos nome, celular e email, pois são obrigatórios no banco de dados
        if (!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])) {/*O que fica no colchete é o 'name' da input*/
            // validação para garantir que o id seja válido
            if (!empty($id) && is_numeric($id) && $id != 0) {
                //Criação de um array de dados que será encaminhado a model para editar no BD, é importante criar este array conforme as necessidades de manipulação do BD
                //OBS: criar as chaves do array conforme os nomes dos atributos do BD.
                $arrayDados = array(
                    "id"       => $id,
                    "nome"     => $dadosContato['txtNome'],
                    "telefone" => $dadosContato['txtTelefone'],
                    "celular"  => $dadosContato['txtCelular'],
                    "email"    => $dadosContato['txtEmail'],
                    "obs"      => $dadosContato['txtObs']
                );

                //Import do arquivo contato para manipular o bd
                require_once('model/bd/contato.php');

                //Chamando a função updateContato (essa função está na model)
                if (updateContato($arrayDados))
                    return true;
                else
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
function excluirContato($id)
{
    // if para verificar se o id é diferente de 0, se não está vazio e se é um número
    if ($id != 0 && !empty($id) && is_numeric($id)) {

        //import do arquivo de contato
        require_once('model/bd/contato.php');

        // chama a função da model e valida se o retorno foi true ou false
        if (deleteContato($id))
            return true;
        else
            return array(
                'idErro'   => 3,
                'message'   => 'O banco de dados não pode excluir o registro.'
            );
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
