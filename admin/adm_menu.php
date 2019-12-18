<?php
//Código do menu para validar o acesso
$codigoMenu = 50;
require_once('verifica_session.php');?>

<!DOCTYPE html>
<html>
<head>
	<title>Painel :: Break | Controle de Ponto Eletrônico</title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="x-ua-compatible" content="IE=edge, chrome=1">
	<link rel="icon" sizes="192x192" href="../images/favicons/icon-192x192.png">
	<link rel="apple-touch-icon" sizes="152x152" href="../images/favicons/apple-touch-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="120x120" href="../images/favicons/apple-touch-icon-120x120.png">

	<!-- Principal CSS do Bootstrap -->
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/temporario.css" rel="stylesheet">
	<link href="../css/estilo.css" rel="stylesheet">

	<script src="../js/jquery-3.3.1.min.js"></script>
	<script src="../js/script.js"></script>
	<script src="../js/bootstrap.min.js"></script>

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
		<script type="text/javascript" src="../js/jquery-1.12.4.min.js"></script>
		
		<link rel="stylesheet" href="../css/bootstrap-3.3.7.min.css">
		<link rel="stylesheet" href="../css/bootstrap-theme-3.3.7.min.css">
		<script src="../js/bootstrap-3.3.7.min.js"></script>			
	<![endif]-->
	
</head>
<body>
	<?php include("include/browserdeprecated.html"); ?>
	<div id="container">
		<div class="mask-carregando" id="carregando">
			<figure>
				<img src="../images/ajax-loading.gif" alt="carregando...">
				<figcaption class="loading-text"></figcaption>
			</figure>
		</div>
		<div id="header" title="System Pausas e Intervalos">
			<h3><span style="color:#ffffff">Break - Central de Orientação</span></h3>
			Seja Bem-Vindo(a), <?=$_SESSION['NM_USER']?>.
		</div>
		<div id="contents" style = "font-size:20px;font-family: Arial, Helvetica, sans-serif;">
			<div class="menu" id="menu">
				<a href="relatorio.php" class="menu_link">Relatorio de Ponto</a><br>
				<a href="relatorio_detalhado.php" class="menu_link">Relatorio Detalhado</a><br>
				<a href="relatorio_em_pausa.php" class="menu_link">Pausa/Intervalo Ativo</a><br>
				<a href="lista_funcionarios.php" class="menu_link">Cadastro de Funcionários</a><br>
				<a href="lista_usuarios.php" class="menu_link">Cadastro de Usuários</a><br>
				<a href="lista_funcoes.php" class="menu_link">Cadastro de Funções</a><br>
				<a href="lista_empresas.php" class="menu_link">Cadastro de Empresas</a><br>
				<a href="lista_tipocontrato.php" class="menu_link">Tipos de Contrato</a><br>
				<a href="lista_grupoacesso.php" class="menu_link">Grupo de Acesso</a><br>
				<a href="lista_permissoes.php" class="menu_link">Permissões</a><br>
				<a href="parametros.php" class="menu_link">Parametros</a><br>
				<a href="sair_admin.php" class="menu_link">Logout</a><br>
			</div>
			<div>
				<img src="../images/tecnologia-informacao.jpg" style="width:50%;height:20%; float:right">
			</div>
		</div><!--contents-->

		<div id="footer">
			Copyright &copy; Break | Controle de Ponto Eletrônico <?=date("Y")?>
		</div><!--footer-->
	</div><!--container-->
</body>
</html>