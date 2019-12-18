<?php
//Código do menu para validar o acesso
$codigoMenu = 2;
require_once('verifica_session.php');

$idUser = $_SESSION['USER_ID'];

//Recupera o nome da empresa a qual o usuário tem como empresa padrão
$query_parametros = "SELECT 	
						E.NM_EMPRESA AS nome_empresa
					FROM 
						USERS AS U
					INNER JOIN 
						PARAMETROS AS P
					ON 
						U.PARAMETRO_ID = P.PARAMETRO_ID
					INNER JOIN 
						EMPRESAS AS E
					ON 
						P.EMPPADRAO = E.EMPRESA_ID
					WHERE 
						U.ID = '$idUser'
					LIMIT 1";
$exec_query_parametros = mysqli_query($conn,$query_parametros);
$registro_parametro = mysqli_fetch_array($exec_query_parametros);
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title>Pausas/Intervalos Ativos :: Break | Controle de Ponto Eletrônico</title>
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
	<link href="../css/estilo.css" rel="stylesheet" type="text/css">
	
	<script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="../js/script.js"></script>
	<script src="../js/bootstrap.min.js"></script>

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
		<script type="text/javascript" src="../js/jquery-1.12.4.min.js"></script>
		
		<link rel="stylesheet" href="../css/bootstrap-3.3.7.min.css">
		<link rel="stylesheet" href="../css/bootstrap-theme-3.3.7.min.css">
		<script src="../js/bootstrap-3.3.7.min.js"></script>
		
	<![endif]-->
	
	<script>
	function update(){
		$.ajax({
			url:'consultas/crelatorio_em_pausa.php',
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
	});
	</script>

</head>
<body>	
	<?php include("include/browserdeprecated.html"); ?>
	<div class="container col-md-9" >

		<div class="form">
			<a class="btn btn-info" href="index.php" style="margin-left: 12px">Inicio</a>
			<a class="btn btn-dark" href="index.php" style="margin-left: 12px">Voltar</a>
			<a href="sair_admin.php" class="btn btn-secondary btn-sm" style="margin-left: 12px" >Sair</a>					  
		</div>

		<div class="relatorio imprimir table-responsive;" style="text-align:center">

			<h3><?=$registro_parametro["nome_empresa"]?></h3>
			<h4>Funcionários em pausa</h4>
			<hr>

			<div id="return_record" style="text-align:left;margin: 0 auto;"></div>

		</div><!-- div relatorio -->
	</div><!-- div container -->
</body>
</html>
<?php
mysqli_free_result($exec_query_parametros);