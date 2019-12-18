<?php
require_once('../config/conn.php');
require_once('../class/ckbAcesso-class.php');

//Instancia o objeto para realização da verificação de acesso
$ckbAcesso = new ckbAcesso();
$permissaoAcesso = $ckbAcesso->ckbPermissao($_SESSION['USER_ID'],$codigoMenu,$conn);

if (!isset($_SESSION['LOGIN']) || !isset($_SESSION['USER_ID']) ||  ($_SESSION['USER_ATIVO']==0)) 
{
    @header("Location: sair_admin.php");
   
}elseif(!$permissaoAcesso)
{
    echo '<script type="text/javascript">alert("Voce nao tem permissao para acessar esta página."); window.location.href="adm_menu.php";</script>';
}

if(isset( $_SESSION['TIME_REGISTER']))
{
    $minutos = time() - $_SESSION['TIME_REGISTER'];
}

if($minutos > $_SESSION['TIME_LIMITE'])
{
    $_SESSION["MSG_LOGIN_INVALIDO"] = "Sessão Expirada. Relize novo login..";
    @header("Location: sair_admin.php");

}else
{
    $_SESSION['TIME_REGISTER'] = time();
}
