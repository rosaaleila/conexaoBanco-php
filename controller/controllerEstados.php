<?php


/***********************************************************************
 * Objetivo: Arquivo responsável pela manipulação de dados de contatos.
 *  Obs:. Este arquivo fará a ponte entre a View e a Model
 * Autora: Leila
 * Data: 10/05/2022
 * Versão: 1.0
 ***********************************************************************/

 require_once('modulo/config.php');

 
//Função para solicitar os dados da model e encaminhar a lista de estados para a View
function listarEstado()
{
    // import do arquivo que vai buscar os dados
    require_once('model/bd/estado.php');

    // chama a funcao que vai buscar os dados no BD
    $dados = selectAllEstados();

    // valida se existem dados para serem devolvidos
    if (!empty($dados))
        return $dados;
    else
        return false;
}
