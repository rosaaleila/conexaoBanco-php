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
                               ->write('{"idErro": "404", "message": "Não foi possivel encontrar registros."}');
        }
    });

    // endpoint: requisicao para listar contatos pelo id
    $app->get('/contatos/{id}', function($request, $response, $args) {

        // pegando o id dos argumentos
        $id = $args['id'];

        // import do arquivo de configuracao
        require_once('../modulo/config.php');
        // import da controller de contatos, que fara a busca de dados
        require_once('../controller/controllerContatos.php');

        // verificacao se a busca foi bem-sucedida
        if ($dados = buscarContato($id)) {
            if (!isset($dados['idErro'])) {
                if ($dadosJSON = createJSON($dados)) {
                    // retorno com tudo válido
                    return $response    ->withStatus(200)
                                        ->withHeader('Content-Type', 'application/json')
                                        ->write($dadosJSON);
                }
            } else {
                $dadosJSON = createJSON($dados);
                // retorno com o erro do nosso sistema, erro no banco
                return $response       ->withStatus(404)
                                       ->withHeader('Content-Type', 'application/json')
                                       ->write('{"message": "Dados inválidos", "erro": '.$dadosJSON.'}');
            }
        } else {
            // retorno com o erro de id invalido
            return $response       ->withStatus(404)
                                   ->withHeader('Content-Type', 'application/json')
                                   ->write('{"idErro": 404, "message": "Não foi possível encontrar um registro com o ID informado."}');
        }

    });

    // endpoint: requisicao para inserir um novo contato
    $app->post('/contatos', function($request, $response, $args) {

        // recebe o content type
        $headerLine = $request->getHeaderLine('Content-Type');
        
        // cria um array com o content type dividido
        $contentType = explode(';', $headerLine);

        switch ($contentType[0]) {
            case 'multipart/form-data':

                // recebe os dados comuns do body da requisicoa
                $dadosBody = $request->getParsedBody();
                
                // recebe a imagem enviada pela requisicao
                $uploadFiles = $request->getUploadedFiles();

                // cria um array com todos os dados que chegaram na requisicao (os dados chegam protegidos e acabamos tendo que utilizar metodos para recuperacao)
                $arrayFoto = array(
                    "name" => $uploadFiles['foto']->getClientFileName(),
                    "type" => $uploadFiles['foto']->getClientMediaType(),
                    "size" => $uploadFiles['foto']->getSize(),
                    "tmp_name" => $uploadFiles['foto']->file
                );

                // criando uma chave foto para armazenar o objeto
                $file = array('foto' => $arrayFoto);

                // cria o array para enviar para a controller
                $arrayDados = array(
                    $dadosBody,
                    "file" => $file
                );

                
                // import do arquivo de configuracao
                require_once('../modulo/config.php');
                // import da controller de contatos, que fara a busca de dados
                require_once('../controller/controllerContatos.php');

                $resposta = inserirContato($arrayDados);

                if (is_bool($resposta) && $resposta == true) {
                    return $response    ->withStatus(201)
                                        ->withHeader('Content-Type', 'application/json')
                                        ->write('{"message": "Registro inserido com sucesso."}');
                } elseif(is_array($resposta) && isset($resposta['idErro'])) {
                    $dadosJSON = createJSON($resposta);

                    return $response    ->withStatus(404)
                                        ->withHeader('Content-Type', 'application/json')
                                        ->write('{"message": "Dados inválidos", "erro": '.$dadosJSON.'}');
                }

                var_dump($dados);

                return $response    ->withStatus(200)
                                    ->withHeader('Content-Type', 'application/json')
                                    ->write('{"message": "O formato escolhido foi Form-data."}');
                break;

            case 'application/json':
                
                $dadosBody = $request->getParsedBody();
                var_dump($dadosBody);
                die;

                return $response    ->withStatus(200)
                                    ->withHeader('Content-Type', 'application/json')
                                    ->write('{"message": "O formato escolhido foi Jason."}');
                break;

            default:
                return $response    ->withStatus(400)
                                    ->withHeader('Content-Type', 'application/json')
                                    ->write('{"message": "O formato escolhido é inválido.", "formatos aceitos": "JSON e FORM-DATA."}');
                break;
            
        }



    });

    $app->delete('/contatos/{id}', function($request, $response, $args) {

        // se o id for numerico realizamos a operacao
        if (is_numeric($args['id'])) {
            $id = $args['id'];
        
            // importa do arquivo de configuracao
            require_once('../modulo/config.php');
            // import da controller de contatos, que fara a busca de dados
            require_once('../controller/controllerContatos.php');
        
            // verificando se a busca deu certo
            if($contato = buscarContato($id)) {
                $foto = $contato['foto'];
                
                $dadosContato = array(
                    "id"    => $id,
                    "foto"  => $foto
                );
                
                $resposta = excluirContato($dadosContato);

                // verificando se o delete foi bem-sucedido
                if (is_bool($resposta) && $resposta == true) {
                    
                        if ($dadosJSON = createJSON($resposta)) {
                            // retorno com tudo válido
                            return $response    ->withStatus(200)
                                                ->withHeader('Content-Type', 'application/json')
                                                ->write('{"message": "Registro excluído com sucesso."}');
                        }
                    
                } elseif(is_array($resposta) && isset($resposta['idErro'])) {
                    $dadosJSON = createJSON($resposta);
                    // retorno com o erro do nosso sistema, erro no banco
                    return $response        ->withStatus(404)
                                            ->withHeader('Content-Type', 'application/json')
                                            ->write('{"message": "Dados inválidos", "erro": '.$dadosJSON.'}');
                }
            } else {
                // retorno caso o registro nao exista
                return $response            ->withStatus(404)
                                            ->withHeader('Content-Type', 'application/json')
                                            ->write('{"idErro": 404, "message": "Não foi possível encontrar um registro com o ID informado."}');
            }
        } else {
            // retorno com o erro de id invalido, por nao ser numerico
            return $response       ->withStatus(404)
                                    ->withHeader('Content-Type', 'application/json')
                                    ->write('{"idErro": 404, "message": "Não é possível deletar o registro sem um ID válido (númerico)."}'); 
        }

    });

    // executa todos os endpoints
    $app->run();

?>