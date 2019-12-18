<?php
require_once('config/conn.php');

if(!isset($_SESSION['CODIGO_FUNC']) || (!isset($_SESSION['FUNC_ATIVO'])) ){

    $_SESSION["MSG_LOGIN"] = "Sessão Expirada. Logue novamente..".
    @header("Location: sair.php");

}

if(isset( $_SESSION['TIME_REGISTER_FUNC']))
{
    $minutos = time() - $_SESSION['TIME_REGISTER_FUNC'];
}

if($minutos > $_SESSION['TIME_LIMITE_FUNC'])
{
    $_SESSION["MSG_LOGIN"] = "Sessão Expirada. Logue novamente..".
    @header("Location: sair.php");

}else
{
    $_SESSION['TIME_REGISTER_FUNC'] = time();
}