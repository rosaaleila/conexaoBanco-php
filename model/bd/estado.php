<?php

/***********************************************************************
 * Objetivo: Arquivo responsável por manipular os dados dentro do BD
 *      (select).
 * Autora: Leila
 * Data: 10/05/2022
 * Versão: 1.0
 ***********************************************************************/


//Import do arquivo que estabelece a conexão com o BD
require_once('conexaoMysql.php');


//Função para listar todos os contatos do BD
function selectAllEstados()
{

    // abre a conexao com o BD
    $conexao = conexaoMysql();

    // script para listar todos os dados do BD
    $sql = "select * from tblestados order by nome asc";

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
                "idestado"  =>  $rsDados['idestado'],
                "nome"      =>  $rsDados['nome'],
                "sigla"     =>  $rsDados['sigla']
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

function selectByIdEstado($id)
{

    // abre a conexao com o BD
    $conexao = conexaoMysql();

    // script para listar todos os dados do BD ** em ordem decrescente (do mais novo ao mais velho)
    $sql = "select * from tblestados where idestado = " . $id;
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
                "id"        =>  $rsDados['idestado'],
                "nome"      =>  $rsDados['nome'],
                "sigla"     =>  $rsDados['sigla']
            );
        }

        // solicita o fechamento da conexao com o BD
        fecharConexaoMysql($conexao);

        return $arrayDados;
    }
}
