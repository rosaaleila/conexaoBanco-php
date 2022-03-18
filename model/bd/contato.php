<?php
    /***********************************************************************
     * Objetivo: Arquivo responsável por manipular os dados dentro do BD
     *      (insert, update, select e delete).
     * Autora: Leila
     * Data: 11/03/2022
     * Versão: 1.0
     ***********************************************************************/

     //Import do arquivo que estabelece a conexão com o BD
     require_once('conexaoMysql.php');

     //Função para realizar o insert no BD
     function insertContato($dadosContato)
     {
        //Abre a conexão com o banco de dados
        $conexao = conexaoMysql();

        //Monta o script para enviar para o BD
        $sql = "insert into tblcontatos
                    (nome, 
                    telefone, 
                    celular, 
                    email, 
                    obs)
                values
                    ('".$dadosContato['nome']."', 
                    '".$dadosContato['telefone']."', 
                    '".$dadosContato['celular']."',
                    '".$dadosContato['email']."', 
                    '".$dadosContato['obs']."');";

        //Executa um script no BD -> Dentro dos (quem é o BD, o que vc quer que eu mande para o BD)
            // validação para verificar se o script sql está correto
        if (mysqli_query($conexao, $sql))
        {
            // validacao para verificar se uma linha foi acrescentada no BD
            if(mysqli_affected_rows($conexao))
                return true;
            else
                return false;
        }
        else
            return false;

     }

     //Função para realizar o update no BD
     function updateContato() {
         
    }
    //Função para excluir no BD
    function deleteContato() {
         
    }

    //Função para listar todos os contatos do BD
    function selectALllContatos()
    {

        // abre a conexao com o BD
        $conexao = conexaoMysql();

        // script para listar todos os dados do BD
        $sql = "select * from tblcontatos";

        // executa o script sql no BD e guarda o retorno dos dados (se houver)
        $result = mysqli_query($conexao, $sql);

        // valida se o BD retornou registros 
        if ($result)
        {

            $cont = 0;

            // mysqil_fetch_assoc() - permite converter os dados do BD em um array para manipulacao no PHP
            while ($rsDados = mysqli_fetch_assoc($result)) // é o mesmo que criar um cont, converter para array e guardar a qtd de itens
            {
                // cria um array com os dados do BD
                $arrayDados[$cont] = array (
                    "nome"      =>  $rsDados['nome'],
                    "telefone"  =>  $rsDados['telefone'],
                    "celular"   =>  $rsDados['celular'],
                    "email"     =>  $rsDados['email'],
                    "obs"       =>  $rsDados['obs']
                );
                $cont++;
            }

            return $arrayDados;
        
        }

        // quando enviamos pro bd um script do tipo insert, update e o delete, ele apenas retorna se ocorreu tudo certo
        // scripts como select esperam o retorno do bd
         
    }
?>