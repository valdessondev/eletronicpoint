<?php 
//Código do menu para validar o acesso
$codigoMenu = 7;
require_once('verifica_session.php');

$display = "none";
$msg_result = "";
if(isset($_POST["saveFormAddTipoContrato"]) && $_POST['saveFormAddTipoContrato'] == "sucess"){
	
	$description_tipocontrato = isset($_POST["description_tipocontrato"]) ? $_POST["description_tipocontrato"] : "";
	$qtd_registros = $_POST["qtd_registros"];
	$relatoriopausaativa = isset($_POST["relatoriopausaativa"])? 1 : 0 ;
	
	//-----------------Consulta as descrições de todos os tipos de contrato-----------------------
	$query_tipocontrato = "SELECT NM_TIPOCONTRATO FROM TIPOCONTRATO WHERE NM_TIPOCONTRATO = '$description_tipocontrato'";  
	$exec_query_all = mysqli_query($conn, $query_tipocontrato);
	$total_tipocontrato = mysqli_num_rows($exec_query_all);
	//----------------------------------------------------------------------------------
	
	if(!isset($description_tipocontrato) || empty($description_tipocontrato)){
		$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>
		-> Preencha todos os campos.</div>";
		$display = "block";

	}elseif(!preg_match("/^[^\/\s]+[a-zA-ZÀ-ú\/\s0-9]{4,50}$/",$description_tipocontrato)){
		$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>
		-> Digite o nome do tipo de contrato(Min 5 Max 50 letras e/ou numeros)</div>";
		$display = "block";
	
	}elseif($total_tipocontrato > 0){
			$msg_result = "<div class='alert alert-danger'>- Erro ao atualizar cadastro. 
			<br>- Este tipo já existe</div>";
			$display = "block";
			
	}else{
	
		$addtipocontrato = "INSERT INTO TIPOCONTRATO (NM_TIPOCONTRATO, QTDREGISTROS, RELATORIOPAUSAATIVA) 
							VALUES('$description_tipocontrato','$qtd_registros', $relatoriopausaativa)";
		$executAddTipoContrato = mysqli_query($conn, $addtipocontrato);

		if($executAddTipoContrato){
			
			$msg_result = "<div class='alert alert-success'>Salvo com sucesso.
							<br><a href='cadastrar_tipocontrato.php'>[CADASTRAR NOVO]</a>
						</div>";
			$display = "block";
			
		} else {
			
			$msg_result = "<div class='alert alert-danger'>- Erro ao atualizar cadastro. Contate o administrador.";
			$display = "block";		
		}
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Cadastrar Tipos de Contrato :: Break | Controle de Ponto Eletrônico</title>

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
		
		<script type="text/javascript">
			$(document).ready(function(){
				$('#result').fadeIn(5000);
				//$('#result').fadeOut(10000);
			});
		</script>

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
		<div class="container col-md-9" style="margin-top: 30px;">

			<div class="col-md-4">
			<a class="btn btn-info" href="index.php" style="margin-left: 12px">Inicio</a>
			<a class="btn btn-dark" href="lista_tipocontrato.php" style="margin-left: 12px">Voltar</a>
			<a href="sair_admin.php" class="btn btn-secondary btn-sm" style="margin-left: 12px" >Sair</a>
			
			<h2>Cadastrar Tipo de Contrato</h2>	
			<br>
			<div id ="result" style="display:<?=$display?>"><?=$msg_result?></div>
		
			<form method="post" action="cadastrar_tipocontrato.php" id="FormAddTipocontrato">
				
				<div class="form-group">
					<label for="Nome do Tipo de Contrato">Descrição</label>
					<input type="text" name="description_tipocontrato" class="form-control" id="description_tipocontrato" 
					value="<?php if(isset($_POST['description_tipocontrato'])) echo $_POST['description_tipocontrato'] ?>" 
					placeholder="Nome do tipo de contrato" minlength="5" maxlength= "50" pattern="^[^/\s]+[a-zA-ZÀ-ú\/\s0-9]{4,50}$" size="50" 
					title="Digite o nome do tipo de contrato(Min 5 Max 50 letras e/ou numeros)" required>
				</div>  
				
				<div class="form-group">
					<label for="Quantidade de Registros/Batidas">Quantidade de Registros:</label>
					<select name="qtd_registros" class="form-control" id="qtd_registros" required>
						<option value="2" select>2</option>
						<option value="4">4</option>
						<option value="6">6</option>						  
					</select>
					</div>
				
				<div class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" id="defaultChecked2" name="relatoriopausaativa" checked>
					<label class="custom-control-label" for="defaultChecked2"> Painel Pausa/Intervalo Ativo</label>
				</div>
				
				<div class="form-group" style="margin-top:10px;text-align:center">
					<input type="hidden" name="saveFormAddTipoContrato" value="sucess">
					<button type="submit" class="btn btn-success my-1">Salvar</button>
				</div>
				
			</form>
		</div>
		</div>

	</body>
</html>
