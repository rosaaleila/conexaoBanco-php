<?php

    // import do arquivo autoload, que fara as intancias do slim
    require_once('vendor/autoload.php');

    // criando um objeto do slim chamado app, para configurar os endpoints
    $app = new \Slim\App();

    // endpoint: requisicao para listar todos os contatos
    $app->get('/contatos', function($request, $response, $args) {
        $response->write('Testando a API pelo get');
    });

    // endpoint: requisicao para listar contatos pelo id
    $app->get('/contatos/{id}', function($request, $response, $args) {

    });

    // endpoint: requisicao para inserir um novo contato
    $app->post('/contatos', function($request, $response, $args) {

    });

?>