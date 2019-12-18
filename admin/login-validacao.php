<?php
    require_once('../config/conn.php');
    require_once("../class/anti_injection-class.php");

    //Verifica se tem login e senha preenchidos
    if(isset($_POST['login']) && $_POST['login'] !="" && isset($_POST['senha']) && $_POST['senha'] !=""){
        //Instanciando o objeto da classe anti_injection
        $valida = new anti_injection();

        //Recupera login e senha
        $login = $valida->anti_injection_exec($_POST['login']);
        $senha = $valida->anti_injection_exec($_POST['senha']);

        $_SESSION['USER_ATIVO'] = 0;
        
        //Consulta no Banco de Dados se existe usuário com aquele login
        $query_consulta = "SELECT 
                                ID, LOGIN, PASSWORD, USER_ATIVO, NOME 
                            FROM 
                                USERS 
                            WHERE 
                                LOGIN = '$login' 
                            LIMIT 1";
        $executa_query_consulta = mysqli_query($conn,$query_consulta) or die('Consulta usuario falhou: ' . mysqli_error($conn));
        $usuarios = mysqli_fetch_assoc($executa_query_consulta);
        
        //testa para ver se existe login válido
        if($usuarios > 0)
        {
            if($usuarios['USER_ATIVO'] ==1){ //Verifica se o usuário está ativo
            //Verifica se a senha informada é válida,
                if(password_verify($senha, $usuarios['PASSWORD'])){

                    //sessão do tempo para expirar começa aqui..
                    $tempolimite = 36000; //equivale a 60 minutos
                    $_SESSION['TIME_REGISTER'] = time();
                    $_SESSION['TIME_LIMITE'] = $tempolimite;

                    $_SESSION['USER_ID'] = $usuarios['ID'];
                    $_SESSION['LOGIN'] = $usuarios['LOGIN'];
                    $_SESSION['NM_USER'] = $usuarios['NOME'];
                    $_SESSION['USER_ATIVO'] = $usuarios["USER_ATIVO"];

                    //Destruindo o login do fomulário e mensagem
                    unset ($_SESSION['LOGIN_FORM']) ;
                    unset( $_SESSION["MSG_LOGIN_INVALIDO"] );
                    
                    @header("Location: adm_menu.php");
                    
                }else{//Caso a senha seja inválida, já redicionada

                    $_SESSION["MSG_LOGIN_INVALIDO"] = "Usuário e/ou Senha Inválido(s)";
                    $_SESSION["LOGIN_FORM"] = $login;

                    @header("Location: index.php");
                }

            }else{//Se o usuário estiver desativado, emite mensagem e redireciona
                $_SESSION["MSG_LOGIN_INVALIDO"] = "Usuário Desativado/Bloqueado.<br> Contato o administrador.";
                $_SESSION["LOGIN_FORM"] = $login;

                @header("Location: index.php"); 
            }
            
        }
        else
        {  //Redireciona, pois verificou que o login é inválido 
            $_SESSION["MSG_LOGIN_INVALIDO"] = "Usuário e/ou Senha Inválido(s)";
            $_SESSION["LOGIN_FORM"] = $login;

            @header("Location: index.php");
        }
    }else{
        $_SESSION["MSG_LOGIN_INVALIDO"] = "Preenchimento obrigatório: Login e Senha.";
        $_SESSION["LOGIN_FORM"] = $_POST['login'];

        @header("Location: index.php");
    }



?>