<?php 
if (!isset($_SESSION)) @session_start();
if (!isset($_SESSION['LOGIN']) || !isset($_SESSION['USER_ID']) ||  ($_SESSION['USER_ATIVO']==0)) 
{ ?>
	<!doctype html>
	<html lang="pt-br">
		<head>
		<title>Break | Controle de Ponto Eletrônico</title>
		
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
		<link href="../css/estilo.css" rel="stylesheet">
		
		<script src="../js/jquery-3.3.1.min.js"></script>
		<script src="../js/script.js"></script>
		<script src="../js/bootstrap.min.js"></script>

		<!-- Estilos customizados para esse template -->
		<link href="../signin.css" rel="stylesheet">

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script type="text/javascript" src="../js/jquery-1.12.4.min.js"></script>
			
			<link rel="stylesheet" href="../css/bootstrap-3.3.7.min.css">
			<link rel="stylesheet" href="../css/bootstrap-theme-3.3.7.min.css">
			<script src="../js/bootstrap-3.3.7.min.js"></script>			
		<![endif]-->

		</head>

		<body class="text-center">			
			<?php include("include/browserdeprecated.html"); ?>				 
			<div class="mask-carregando" id="carregando">
				<figure>
					<img src="../images/ajax-loading.gif" alt="carregando...">
					<figcaption class="loading-text"></figcaption>
				</figure>
			</div>
			<form class="form-signin" name="form-login" id="form-login" method="post" action="login-validacao.php">
				<h1 class="h3 mb-3 font-weight-normal">Entrar - Painel</h1>
				<img class="mb-4" src="../images/logo-empresa.jpg" alt="Portal de Pausas e Intervalos">

				<?php
				if(isset($_SESSION["MSG_LOGIN_INVALIDO"]) && $_SESSION["MSG_LOGIN_INVALIDO"]!= "" ) 
				{ ?>
					<div class="alert alert-danger msg_login" style="height:60px">

						<div class= "msg_login_img">
							<img src="../images/action_error.png">
						</div>

						<div>
							<?php 
							echo $_SESSION["MSG_LOGIN_INVALIDO"];
							unset( $_SESSION["MSG_LOGIN_INVALIDO"] );
							?>
						</div>
					</div>					
				<?php } ?>
					
				<input type="text" id="inputLogin" name="login" class="form-control" placeholder="Login" 
				<?php if(isset($_SESSION['LOGIN_FORM']) && $_SESSION['LOGIN_FORM']!="") {echo 'value = "'.$_SESSION["LOGIN_FORM"].'"';} 
				else { echo "autofocus";}?> title="Digite seu Login" maxlength="20" size="20" required>

				<label for="inputCodigo" class="sr-only">Seu código</label>

				<input type="password" id="inputPassword" name="senha" class="form-control" placeholder="senha" 
				<?php if(isset($_SESSION['LOGIN_FORM'])) echo 'autofocus'; ?> title="Digite sua senha" 
				maxlength="20" size="20" required>

				<div class="checkbox mb-3">
				</div>
				<button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
				<p class="mt-5 mb-3 text-muted">&copy; 2017-2018</p>
			</form>
		</body>
	</html>
<?php
}else{
	@header("Location: adm_menu.php");
}