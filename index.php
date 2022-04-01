<?php

$nome = null;
$email = null;
$celular = null;
$telefone = null;
$obs = null;

// valida se a utilizacao de variaveis de sessao esta ativa no servidor
if (session_status())
    // valida se a variavel de sessao dadoscontato nao esta vazia
    if (!empty($_SESSION['dadosContato'])) {

        $id = $_SESSION['dadosContato']['id'];
        $nome = $_SESSION['dadosContato']['nome'];
        $email = $_SESSION['dadosContato']['email'];
        $celular = $_SESSION['dadosContato']['celular'];
        $telefone = $_SESSION['dadosContato']['telefone'];
        $obs = $_SESSION['dadosContato']['obs'];
    
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
            <form action="router.php?component=contatos&action=inserir" name="frmCadastro" method="post">
                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Nome: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <input type="text" name="txtNome" value="<?=$nome?>" placeholder="Digite seu Nome" maxlength="100">
                    </div>
                </div>

                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Telefone: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <input type="tel" name="txtTelefone" value="<?=$telefone?>">
                    </div>
                </div>
                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Celular: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <input type="tel" name="txtCelular" value="<?=$celular?>">
                    </div>
                </div>


                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Email: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <input type="email" name="txtEmail" value="<?=$email?>">
                    </div>
                </div>
                <div class="campos">
                    <div class="cadastroInformacoesPessoais">
                        <label> Observações: </label>
                    </div>
                    <div class="cadastroEntradaDeDados">
                        <textarea name="txtObs" cols="50" rows="7"><?=$obs?></textarea>
                    </div>
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
                <td class="tblColunas destaque"> Opções </td>
            </tr>

            <?php

            // import do arquivo da controller para solicitar a listagem de dados
            require_once('controller/controllerContatos.php');
            // chama a função que vai retornar os dados de contatos
            $listContato = listarContato();

            // estrutura de repeticao para retornar os dados do array e imprimir na tela
            foreach ($listContato as $item) {
            ?>

                <tr id="tblLinhas">
                    <td class="tblColunas registros"><?= $item['nome'] ?></td>
                    <td class="tblColunas registros"><?= $item['celular'] ?></td>
                    <td class="tblColunas registros"><?= $item['email'] ?></td>

                    <td class="tblColunas registros">
                        <a href="router.php?component=contatos&action=buscar&id=<?= $item['id'] ?>">
                            <img src="img/edit.png" alt="Editar" title="Editar" class="editar">
                        </a>
                        <a onclick="return window.confirm('Deseja realmente excluir este contato?')" href="router.php?component=contatos&action=deletar&id=<?= $item['id'] ?>">
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