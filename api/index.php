<?php
/***************************************************************************
 * Objetivo: arquivo principal da api que irá receber a url requisitada e 
 * redirecionar para as APIs (papel semelhante ao router)
 * Data: 19/05/22
 * Autor: Leila Rosa
 * Versão: 1.0
****************************************************************************/

    // permite ativar quais enderecos de sites que poderao fazer requisições na api (* = todos)
    header('Access-Control-Allow-Origin: *');

    // permite definir quais metodos serao aceitos pela api 
    header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS');
    
    // permite ativar o content-type (formato de dados que sera utilizado (JSON, XML, FORM/DATA...)) das requisicoes 
    header('Access-Control-Allow-Header: Content-Type');
    
    // permite definir quais os tipos de content type que serao aceitos 
    header('Content-Type: application/json');

    // recebe a url digitada na requisição
    $urlHTTP = (string) $_GET['url'];

    // converte a url requisitada em um array, com opções separado pelas '/'
    $url = explode('/', $urlHTTP);

    // verifica qual a api sera encaminhada a requisicao (contatos, estados, etc)
    switch (strtolower($url[0])) {
        case 'contatos':
            require_once('contatosAPI/index.php');
            break;
        case 'estados':
            require_once('estadosAPI/index.php');
            break;
        
    }

?>