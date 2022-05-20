<?php

/**************************************************************************************
 * $request     - recebe dados do corpo da requisicao (json, form/data, xml, etc)
 * $response    - envia dados de retorno da api
 * $args        - permite receber dados de atributos na api
***************************************************************************************/


    // import do arquivo autoload, que fara as intancias do slim
    require_once('vendor/autoload.php');

    // criando um objeto do slim chamado app, para configurar os endpoints
    $app = new \Slim\App();

    // endpoint: requisicao para listar todos os contatos
    $app->get('/contatos', function($request, $response, $args) {
        // importa do arquivo de configuracao
        require_once('../modulo/config.php');
        // import da controller de contatos, que fara a busca de dados
        require_once('../controller/controllerContatos.php');

        // solicita os dados para a controller
        if ($dados = listarContato()) {
            // realiza a conversao do array de dados em formato json
            if ($dadosJSON = createJSON($dados)) {
                // caso exista dados, retornamos o status code e enviamos os dados em json
                return $response   ->withStatus(200)
                                   ->withHeader('Content-Type', 'application/json')
                                   ->write($dadosJSON);
            }
        } else {
            // retorna um status code caso a solicitacao dê errado
            return $response   ->withStatus(404)
                               ->withHeader('Content-Type', 'application/json')
                               ->write('{"id-erro": "404", "message": "Não foi possivel encontrar registros."}');
        }
    });

    // endpoint: requisicao para listar contatos pelo id
    $app->get('/contatos/{id}', function($request, $response, $args) {

        $id = $args['id'];

        require_once('../modulo/config.php');
        require_once('../controller/controllerContatos.php');

        if ($dados = buscarContato($id)) {
            if ($dadosJSON = createJSON($dados)) {
                return $response   ->withStatus(200)
                                   ->withHeader('Content-Type', 'application/json')
                                   ->write($dadosJSON);
            }
        } else {
            return $response       ->withStatus(404)
                                   ->withHeader('Content-Type', 'application/json')
                                   ->write('{"id-erro": "404", "message": "Não foi possível encontrar um registro com o ID informado."}');
        }

    });

    // endpoint: requisicao para inserir um novo contato
    $app->post('/contatos', function($request, $response, $args) {

    });

    // executa todos os endpoints
    $app->run();

?>