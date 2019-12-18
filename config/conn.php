<?php 
if (!isset($_SESSION)) @session_start();
date_default_timezone_set('America/Fortaleza');

$server = "localhost";
$user = "root";
$pass = "";
$dbname = "bdponto";

@$conn = mysqli_connect($server, $user, $pass, $dbname);

if(!$conn){
    echo "Erro: Nenhuma Conexao realizada ao MySQL.<br>" . PHP_EOL;
    echo "Numero do Erro: " . mysqli_connect_errno() ."<br>". PHP_EOL;
    echo "Motivo do erro: " . mysqli_connect_error() ."<br>". PHP_EOL;
    exit; 
}

define('IP_INTERNET1', 'localhost');
define('IP_INTERNET2', '192.168.10.18');

?>
