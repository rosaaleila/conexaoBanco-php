<?php

// import do arquivo de configuracoes
require_once('modulo/config.php');

/* 
solucoes para variavel indefinida ao abrir o navegador.

uma alternativa é adicionar um @ antes do nome da
variavel (nada recomendado). 
    o @ omite a mensagem de erro no navegador.

uma outra alternativa é utilizar um if ternario dentro do
html (está sendo utilizada)

outra alternativa:

$nome = null;
$email = null;
$celular = null;
$telefone = null;
$obs = null; 
*/

// variavel criada para diferenciar no action do formulário qual acao
// deveria ser levada para a router (inserir ou editar)
// nas condicoes abaixo, mudamos o action dessa variavel para a ação de editar
$form = (string) "router.php?component=contatos&action=inserir";

// variavel para carregar o nome da foto do BD
$foto = (string) null;

// valida se a utilizacao de variaveis de sessao esta ativa no servidor
if (session_status())
    // valida se a variavel de sessao dadoscontato nao esta vazia
    if (!empty($_SESSION['dadosContato'])) {

        $id = $_SESSION['dadosContato']['id'];
        $nome = $_SESSION['dadosContato']['nome'];
        $email = $_SESSION['dadosContato']['email'];
        $celular = $_SESSION['dadosContato']['celular'];
        $telefone = $_SESSION['dadosContato']['telefone'];
        $foto = $_SESSION['dadosContato']['foto'];
        $obs = $_SESSION['dadosContato']['obs'];

        // mudamos a action para editar o registro no botao salvar
        $form = "router.php?component=contatos&action=editar&id=" . $id."&foto=".$foto;

        // destroi a variavel apagando-a da memoria
        unset($_SESSION['$dadosContato']);
    }


?>

<!DOCTYPE>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title> Cadastro </title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="./js/main.js" defer></script>
</head>

<body>

    <div id="cadastro">
        <div id="cadastroTitulo">
            <h1> Cadastro de Contatos </h1>

        </div>
        <div id="cadastroInformacoes">
            <!--
                enctype="multipart/form-data" - esta opção é obrigatória
                para enviar arquivos do form em html para o servidor
            -->
            <form action="<?= $form ?>" name="frmCadastro" method="post" enctype="multipart/form-data">
                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Nome: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <input type="text" name="txtNome" value="<?= isset($nome) ? $nome : null ?>" placeholder="Digite seu Nome" maxlength="100">
                    </div>
                </div>

                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Telefone: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <input type="tel" name="txtTelefone" value="<?= isset($telefone) ? $telefone : null ?>">
                    </div>
                </div>
                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Celular: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <input type="tel" name="txtCelular" value="<?= isset($celular) ? $celular : null ?>">
                    </div>
                </div>


                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Email: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <input type="email" name="txtEmail" value="<?= isset($email) ? $email : null ?>">
                    </div>
                </div>
                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Escolha um arquivo: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <input type="file" name="fleFoto" accept=".jpg, .png, .jpeg, .gif">
                         <!-- seleção de arquivo -->
                    </div>
                </div>
                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Observações: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <textarea name="txtObs" cols="50" rows="7"><?= isset($obs) ? $obs : null ?></textarea>
                    </div>
                </div>

                <div class="campos">
                    <img src="<?= DIRETORIO_FILE_UPLOAD.$foto ?>">
                </div>

                <div class="enviar">
                    <div class="enviar">
                        <input type="submit" name="btnEnviar" value="Salvar">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="consultaDeDados">
        <table id="tblConsulta">
            <tr>
                <td id="tblTitulo" colspan="6">
                    <h1> Consulta de Dados.</h1>
                </td>
            </tr>
            <tr id="tblLinhas">
                <td class="tblColunas destaque"> Nome </td>
                <td class="tblColunas destaque"> Celular </td>
                <td class="tblColunas destaque"> Email </td>
                <td class="tblColunas destaque"> Imagem </td>
                <td class="tblColunas destaque"> Opções </td>
            </tr>

            <?php

            // import do arquivo da controller para solicitar a listagem de dados
            require_once('controller/controllerContatos.php');
            // chama a função que vai retornar os dados de contatos
            $listContato = listarContato();

            // estrutura de repeticao para retornar os dados do array e imprimir na tela
            foreach ($listContato as $item)
            {
                // variavel para carregar a foto do bd
                $foto = $item['foto'];
            
            ?>

                <tr id="tblLinhas">
                    <td class="tblColunas registros"><?= $item['nome'] ?></td>
                    <td class="tblColunas registros"><?= $item['celular'] ?></td>
                    <td class="tblColunas registros"><?= $item['email'] ?></td>
                    <td class="tblColunas registros"><img src="<?= DIRETORIO_FILE_UPLOAD.$foto ?>" alt="Imagem escolhida"></td>

                    <td class="tblColunas registros">
                        <a href="router.php?component=contatos&action=buscar&id=<?= $item['id'] ?>">
                            <img src="img/edit.png" alt="Editar" title="Editar" class="editar">
                        </a>
                        <!-- criando janela de confirmacao antes de excluir um contato -->
                        <a onclick="return window.confirm('Deseja realmente excluir este contato?')" href="router.php?component=contatos&action=deletar&id=<?= $item['id'] ?>&foto=<?= $foto ?>">
                            <img src="img/trash.png" alt="Excluir" title="Excluir" class="excluir">
                        </a>
                        <img src="img/search.png" alt="Visualizar" title="Visualizar" class="pesquisar">
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</body>

</html>