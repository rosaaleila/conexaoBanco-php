<?php

/***********************************************************************
 * Objetivo: Arquivo responsável por manipular os dados dentro do BD
 *      (insert, update, select e delete).
 * Autora: Leila
 * Data: 11/03/2022
 * Versão: 1.4
 ***********************************************************************/

//Import do arquivo que estabelece a conexão com o BD
require_once('conexaoMysql.php');

//Função para realizar o insert no BD
function insertContato($dadosContato)
{

    // declaracao da variavel que armazena o status de erro e é utilizada no return  
    $status = (bool) false;

    //Abre a conexão com o banco de dados
    $conexao = conexaoMysql();

    //Monta o script para enviar para o BD
    $sql = "insert into tblcontatos
                    (nome, 
                    telefone, 
                    celular, 
                    email, 
                    obs, foto)
                values
                    ('" . $dadosContato['nome'] . "', 
                    '" . $dadosContato['telefone'] . "', 
                    '" . $dadosContato['celular'] . "',
                    '" . $dadosContato['email'] . "', 
                    '" . $dadosContato['obs'] . "',
                    '" . $dadosContato['foto'] . "')"; 

    //Executa um script no BD -> Dentro dos (quem é o BD, o que vc quer que eu mande para o BD)
    // validação para verificar se o script sql está correto
    if (mysqli_query($conexao, $sql)) {
        // validacao para verificar se uma linha foi acrescentada no BD
        if (mysqli_affected_rows($conexao))
            $status = true;
    }

    // fecha a conexao com o BD
    fecharConexaoMysql($conexao);
    return $status;
}

//Função para realizar o update no BD
function updateContato($dadosContato)
{

    // declaracao da variavel que armazena o status de erro e é utilizada no return  
    $status = (bool) false;

    //Abre a conexão com o banco de dados
    $conexao = conexaoMysql();

    //Monta o script para enviar para o BD
    $sql = "update tblcontatos set
                    nome = '" . $dadosContato['nome'] . "', 
                    telefone = '" . $dadosContato['telefone'] . "', 
                    celular = '" . $dadosContato['celular'] . "', 
                    email = '" . $dadosContato['email'] . "', 
                    foto = '" . $dadosContato['foto'] . "', 
                    obs = '" . $dadosContato['obs'] . "'
                    where idcontato =" . $dadosContato['id'];

    //Executa um script no BD -> Dentro dos (quem é o BD, o que vc quer que eu mande para o BD)
    // validação para verificar se o script sql está correto
    if (mysqli_query($conexao, $sql)) {
        // validacao para verificar se uma linha foi acrescentada no BD
        if (mysqli_affected_rows($conexao))
            $status = true;
    }

    // fecha a conexao com o BD
    fecharConexaoMysql($conexao);
    return $status;
}
//Função para excluir no BD
function deleteContato($id)
{

    // abre a conexao com o BD
    $conexao = conexaoMySql();

    // declaração de variavel para utilizarmos no return da função
    $status = (bool) false;

    // script para deletar um registro do BD
    $sql = "delete from tblcontatos where idcontato =" . $id;

    // validação para verificar se o script sql está correto para executá-lo
    if (mysqli_query($conexao, $sql)) {
        // validacao para verificar se uma linha foi acrescentada no BD
        if (mysqli_affected_rows($conexao))
            $status = true;
    }

    // fecha a conexao com o BD
    fecharConexaoMysql($conexao);
    return $status;
}

//Função para listar todos os contatos do BD
function selectAllContatos()
{

    // abre a conexao com o BD
    $conexao = conexaoMysql();

    // script para listar todos os dados do BD ** em ordem decrescente (do mais novo ao mais velho)
    $sql = "select * from tblcontatos order by idcontato desc";
    // desc - descendente | asc - ascendente

    // executa o script sql no BD e guarda o retorno dos dados (se houver)
    $result = mysqli_query($conexao, $sql);

    // valida se o BD retornou registros 
    if ($result) {

        $cont = 0;

        // mysqil_fetch_assoc() - permite converter os dados do BD em um array para manipulacao no PHP
        while ($rsDados = mysqli_fetch_assoc($result)) // é o mesmo que criar um cont, converter para array e guardar a qtd de itens
        {
            // cria um array com os dados do BD
            $arrayDados[$cont] = array(
                "id"        =>  $rsDados['idcontato'],
                "nome"      =>  $rsDados['nome'],
                "telefone"  =>  $rsDados['telefone'],
                "celular"   =>  $rsDados['celular'],
                "email"     =>  $rsDados['email'],
                "foto"      =>  $rsDados['foto'],
                "obs"       =>  $rsDados['obs']
            );
            $cont++;
        }

        // solicita o fechamento da conexao com o BD
        fecharConexaoMysql($conexao);

        return $arrayDados;
    }

    // quando enviamos pro bd um script do tipo insert, update e o delete, ele apenas retorna se ocorreu tudo certo
    // scripts como select esperam o retorno do bd

}

// função para buscar um contato no BD através do id do registros
function selectByIdContato($id)
{

    // abre a conexao com o BD
    $conexao = conexaoMysql();

    // script para listar todos os dados do BD ** em ordem decrescente (do mais novo ao mais velho)
    $sql = "select * from tblcontatos where idcontato = " . $id;
    // desc - descendente | asc - ascendente

    // executa o script sql no BD e guarda o retorno dos dados (se houver)
    $result = mysqli_query($conexao, $sql);

    // valida se o BD retornou registros 
    if ($result) {

        // mysqil_fetch_assoc() - permite converter os dados do BD em um array para manipulacao no PHP
        if ($rsDados = mysqli_fetch_assoc($result)) // se houverem dados...
        {
            // cria um array com os dados do BD
            $arrayDados = array(
                "id"        =>  $rsDados['idcontato'],
                "nome"      =>  $rsDados['nome'],
                "telefone"  =>  $rsDados['telefone'],
                "celular"   =>  $rsDados['celular'],
                "email"     =>  $rsDados['email'],
                "foto"      =>  $rsDados['foto'],
                "obs"       =>  $rsDados['obs']
            );
        }

        // solicita o fechamento da conexao com o BD
        fecharConexaoMysql($conexao);

        return $arrayDados;
    }
}
