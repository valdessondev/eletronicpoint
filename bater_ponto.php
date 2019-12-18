<?php
require_once('verifica_session_front.php'); 
?>

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
		
		<!-- Principal CSS do Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/estilo.css" rel="stylesheet">
		
		<!-- Estilos customizados para esse template -->
		<link href="signin.css" rel="stylesheet">
		
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>

		
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script type="text/javascript" src="js/jquery-1.12.4.min.js"></script>
			
			<link rel="stylesheet" href="css/bootstrap-3.3.7.min.css">
			<link rel="stylesheet" href="css/bootstrap-theme-3.3.7.min.css">
			<script src="js/bootstrap-3.3.7.min.js"></script>			
		<![endif]-->

		<title>Registro :: Break | Controle de Ponto Eletrônico</title>
		
		<script type="text/javascript">

			var tempo = "<?=time()-(3600*3);?>";
			function _hora(){
				var texto = "<b>Horário: </b> ";
				var segundos = parseInt(tempo % 60);
				var minutos = parseInt(tempo / 60 % 60);
				var horas = parseInt(tempo / 3600 % 24);		
				
				// Horas.	
				if (horas > 0){ texto += ((horas < 10)? "0" : "") + horas + ":"; }
				// Minutos.	
				if (minutos > 0){ texto += ((minutos < 10) ? "0" : "") + minutos + ":"; }	
				// Segundos.	
				texto += ((segundos < 10) ? "0" : "") + segundos;
				// Escrever.	
				$("#hora").html(texto);	
				window.setTimeout(_hora, 1000);
				tempo++;
			}


			function update(){

				$.ajax({
					url:'consultas/cregistrar-ponto.php',
					type: 'POST',
					dataType:'html',					
					success:function(data){
						$("#return_record").html(data);
						window.setTimeout(update, 5000);				
					},
					error: function(data) {
						$("#return_record").html("Ocorreu um erro inesperado. Contate o administrador.");
					},
				});
			}

			$(document).ready(function(){

				update();
				_hora();
			});

		</script>

		<!--<style type="text/css">
			#corteIframe {
				position: absolute;
				clip: rect(657px,960px,1500px,400px); 
				width: 960px; /* pode colocar o mesmo valor do segundo parâmetro */
				background:#4B0082;
				margin:-600px 0;
			}
		</style>-->

	</head>

	<body>
		<?php include("include/browserdeprecated.html"); ?>		
		<div style="margin: 0 auto;width:400px">
				<div id="hora" style="text-align:left;margin: 0 auto;"></div>		
				<div id="return_record" style="text-align:left;margin: 0 auto;"></div>
				
		</div>
		
	</body>
</html>

