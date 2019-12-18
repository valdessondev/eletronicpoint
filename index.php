<?php
if (!isset($_SESSION)) @session_start();
		
	if(!isset($_SESSION['CODIGO_FUNC']) || (!isset($_SESSION['FUNC_ATIVO'])) ){ ?>

		<!doctype html>
		<html lang="pt-br">
		  <head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			<meta name="description" content="">
			<meta name="author" content="">
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<meta http-equiv="x-ua-compatible" content="IE=edge, chrome=1">
			<link rel="icon" sizes="192x192" href="images/favicons/icon-192x192.png">
			<link rel="apple-touch-icon" sizes="152x152" href="images/favicons/apple-touch-icon-152x152.png">
			<link rel="apple-touch-icon" sizes="120x120" href="images/favicons/apple-touch-icon-120x120.png">
			
			<title>Break | Controle de Ponto Eletrônico</title>

			<!-- Principal CSS do Bootstrap -->
			<link href="css/bootstrap.min.css" rel="stylesheet">

			<script src="js/jquery-3.3.1.min.js"></script>
			<script src="js/script.js"></script>
			<script src="js/bootstrap.min.js"></script>

			<!-- Estilos customizados para esse template -->
			<link href="signin.css" rel="stylesheet">
			<link href="css/estilo.css" rel="stylesheet">

			<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
			<!--[if lt IE 9]-->
				<script type="text/javascript" src="s/jquery-1.12.4.min.js"></script>
				
				<link rel="stylesheet" href=".css/bootstrap-3.3.7.min.css">
				<link rel="stylesheet" href="css/bootstrap-theme-3.3.7.min.css">
				<script src="js/bootstrap-3.3.7.min.js"></script>			
			<!--[endif]-->
		  </head>
		
		  <body class="text-center">

			<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
			<!--[if lt IE 9]>
			<?php include("include/browserdeprecated.html"); ?>			
			<![endif]-->

			<div class="mask-carregando" id="carregando">
					<figure>
						<img src="images/ajax-loading.gif" alt="carregando...">
						<figcaption class="loading-text"></figcaption>
					</figure>
			</div>
			<form class="form-signin" action="login-validacao-front.php" name="form-login" method="post" id="form-login" >
			  <h1 class="h3 mb-3 font-weight-normal">Entrar</h1>
			  <img class="mb-4" src="images/logo-empresa.jpg" alt="Portal de Pausas e Intervalos">

				<?php
				if(isset($_SESSION["MSG_LOGIN"]) && $_SESSION["MSG_LOGIN"] != "" ) 
				{ ?>
					<div class="alert alert-danger msg_login">

						<div class= "msg_login_img">
							<img src="images/action_error.png">
						</div>

						<div>
							<?php 
							echo $_SESSION["MSG_LOGIN"];
							unset( $_SESSION["MSG_LOGIN"] );
							?>
						</div>
					</div>
					
				<?php }
				elseif(isset($_SESSION["MSG_REGISTER"]) && $_SESSION["MSG_REGISTER"]!="")
				{?>	
					<?=$_SESSION["NOME_FUNC"]?>
					<div class="alert alert-success msg_login">

						<div class= "msg_login_img">
							<img src="images/action_success.png" style="margin-top:50%">
						</div>
						<div>
							<?php 
								
								echo $_SESSION["MSG_REGISTER"];

								unset( $_SESSION["NOME_FUNC"] );
								unset( $_SESSION["MSG_REGISTER"] );
							?>
						</div>
					</div>
				<?php }
				?> 			

			  <label for="Digite seu Login: " class="sr-only">Seu código</label>
			  <input type="password" id="inputcodigo" name="inputcodigo" class="form-control" placeholder="Digite seu código"
			   maxlength= "9" size="9" title="Digite seu Código" style="margin-top:10px" autofocus required>
			  <div class="checkbox mb-3">
			  </div>
			  <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
			  <p class="mt-5 mb-3 text-muted">&copy; <?=date('Y')?></p>
			</form>
		  </body>
		</html>

<?php }else {
		@header("Location: bater_ponto.php");
}?>