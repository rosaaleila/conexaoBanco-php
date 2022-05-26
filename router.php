<?php

/**************************************************************************************
 * Objetivo: Arquivo de rota, para segmentar as ações encaminhadas pela Wiew
 *     (Dados de um form, listagem de dados, ação de excluir ou atualizar).
 *      Esse arquivo será responsável por encaminhar as solicitações para a Controller.
 * Autora: Leila
 * Data: 04/03/2022
 * Versão: 1.4
 **************************************************************************************/

$action = (string) null;
$component = (string) null;

//Validação para verificar se a requisição é um POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET') {

    //Recebendo dados via URL para saber quem está solicitando e qual ação será realizada
    $component = strtoupper($_GET['component']);
    $action = strtoupper($_GET['action']);

    //Estrutura condicional para validar quem está solicitando algo para o Router
    switch ($component) {
        case 'CONTATOS';
            //import da controller Contatos
            require_once('controller/controllerContatos.php');

            //Verificando o que foi passado para o action
            if ($action == 'INSERIR') {
                //Enviando o objeto POST para a função inserirContato
                // Chama a função de inserir na controller
                // validar tipo de dados que a controller retorna

                // validacao para tratar se a imagem existe na chegada dos dados html
                if(isset($_FILES) && !empty($_FILES)) {
                    $arrayDados = array(
                        $_POST,
                        "file" => $_FILES
                    );

                    $resposta = inserirContato($arrayDados);
                } else {
                    $arrayDados = array(
                        $_POST,
                        "file" => null
                    );
                    
                    $resposta = inserirContato($arrayDados);
                }

                if (is_bool($resposta)) {
                    // verifica se o retorno é true
                    if ($resposta)
                        echo ('<script> alert("Registro Inserido com Sucesso!"); window.location.href="index.php"; </script>');
                    // o comando window.location nos permite definir para qual janela retornar
                }
                // verifica se é array, indicando que houve erro no processo de inserção
                elseif (is_array($resposta))
                    echo ('<script> alert("' . $resposta["message"] . '");  </script>');
            } elseif ($action == 'DELETAR') {
                // recebe o id do registro que deverá ser excluído, que foi enviado pela
                // url no link da imagem do excluir que foi acionado na index
                $idContato = $_GET['id'];
                $foto = $_GET['foto'];

                // criamos um array para enviar o id e foto para controller
                $arrayDados = array(
                    "id"    => $idContato,
                    "foto"  => $foto
                );

                // chama a função da controller
                $resposta = excluirContato($arrayDados);

                if (is_bool($resposta)) {
                    if ($resposta) {
                        echo ('<script> alert("Registro Deletado com Sucesso!"); window.location.href="index.php"; </script>');
                    }
                } elseif (is_array($resposta)) {
                    echo ('<script> alert("' . $resposta["message"] . '");  </script>');
                }
            } elseif ($action == 'BUSCAR') {

                // recebe o id do registro que deverá ser editado, que foi enviado pela
                // url no link da imagem do editado que foi acionado na index
                $idContato = $_GET['id'];

                // chama a função da controller
                $dados = buscarContato($idContato);

                // ativa a utilizacao de variaveis de sessao no servidor
                session_start();

                // guarda em variavel de sessao os dados que o BD retornou para a busca do id
                // obs.: essa variavel de sessao sera utilizada na index.php, para colocar
                // os dados do contato nas caixas de texto 
                $_SESSION['dadosContato'] = $dados;

                require_once('index.php');

                // o comando header abre uma nova instancia da página requisitada, porem
                // havera uma acao de carregamento no navegador (piscando a tela novamente)
                // com o require, apenas importamos a tela da index. assim, não ha novo carregamento

            } elseif ($action == 'EDITAR') {

                // recebe o id e o nome da foto que foram encaminhados no action do form pela url
                $idContato = $_GET['id'];
                $foto = $_GET['foto'];

                // cria um array contendo as infos para a controller
                $arrayDados = array(
                    "id"    => $idContato,
                    "foto"  => $foto,
                    "file"  => $_FILES
                ); 

                // Chama a função de editar na controller
                $resposta = atualizarContato($_POST, $arrayDados);

                if (is_bool($resposta)) {
                    // verifica se o retorno é true
                    if ($resposta)
                        echo ('<script> alert("Registro Atualizado com Sucesso!"); window.location.href="index.php"; </script>');
                    // o comando window.location nos permite definir para qual janela retornar
                } elseif (is_array($resposta)) // verifica se é array, indicando que houve erro no processo de edição
                    echo ('<script> alert("' . $resposta["message"] . '"); window.history.back(); </script>');
            }

            break;
    }
}
