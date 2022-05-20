<?php

/**************************************************************************************
 * Objetivo: arquivo responsavel pela criacao de variaveis e constantes do projeto
 * Autora: Leila
 * Data: 25/04/2022
 * Versão: 1.0
 **************************************************************************************/

 /********************** VARIAVEIS E CONSTANTES GLOBAIS DO PROJETO ********************/

 // limitacao de 5MB para upload de imagens
 const MAX_FILE_UPLOAD = 5120;
 
 // tipos de imagem permitidos
 const EXT_FILE_UPLOAD = array("image/jpg", "image/png", "image/jpeg", "image/gif");
 
 // diretorio para upload
 const DIRETORIO_FILE_UPLOAD = 'arquivos/';

 // diretorio raiz do projeto
 define('SRC', $_SERVER['DOCUMENT_ROOT'].'/leila/conexaoBancoPhp/');

 /*************************** FUNCOES GLOBAIS DO PROJETO *******************************/

 // funcao para converter um array em formato json
 function createJSON($arrayDados)
 {
     // validacao para tratar array sem dados
    if(!empty($arrayDados)) {    
        // json_encode converte array para json
        // json_decode faz o inverso
        
        // configura o padrao da conversao para formato json
        header('Content-Type: application/json');

        return json_encode($arrayDados);
    } else {
        return false;
    } 
}
