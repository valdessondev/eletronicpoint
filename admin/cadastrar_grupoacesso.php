<?php 
//Código do menu para validar o acesso
$codigoMenu = 8;
require_once("verifica_session.php");

$display = "none";
$msg_result = "";
if(isset($_POST["saveFormAddGroupAccess"]) && $_POST['saveFormAddGroupAccess'] == "sucess"){
	
	$description_groupaccess = isset($_POST["description_groupaccess"]) ? $_POST["description_groupaccess"] : "";
	
	//-----------------Consulta as descrições de todos os grupos de acesso-----------------------
		$query_grupoacesso = "SELECT NM_GRUPOACESSO FROM GRUPOACESSO WHERE NM_GRUPOACESSO = '$description_groupaccess'";  
		$exec_query_all = mysqli_query($conn, $query_grupoacesso);
		$total_grupoacesso = mysqli_num_rows($exec_query_all);
	//----------------------------------------------------------------------------------
	
	if(!isset($description_groupaccess) || empty($description_groupaccess)){
		$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>
		-> Preencha todos os campos.</div>";
		$display = "block";

	}elseif(!preg_match("/^[^\/\s]+[a-zA-ZÀ-ú\/\s0-9]{4,50}$/",$description_groupaccess)){
		$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>
		-> Digite o nome do grupo de acesso(Min 5 Max 50 letras e/ou numeros)</div>";
		$display = "block";
	
	}elseif($total_grupoacesso > 0){
			$msg_result = "<div class='alert alert-danger'>- Erro ao atualizar cadastro. 
			<br>- Este grupo já existe</div>";
			$display = "block";
			
	}else{
	
		$addgrupoacesso = "INSERT INTO GRUPOACESSO (NM_GRUPOACESSO) 
							VALUES('$description_groupaccess')";
		$executAddGrupoAcesso = mysqli_query($conn, $addgrupoacesso);

		if($executAddGrupoAcesso){
			
			$msg_result = "<div class='alert alert-success'>Salvo com sucesso.
							<br><a href=''>[CADASTRAR NOVO]</a>
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
		<title>Cadastrar Grupos de Acesso :: Break | Controle de Ponto Eletrônico</title>

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
			<a class="btn btn-dark" href="lista_grupoacesso.php" style="margin-left: 12px">Voltar</a>
			<a href="sair_admin.php" class="btn btn-secondary btn-sm" style="margin-left: 12px" >Sair</a>
			
			<h2>Cadastrar Grupos de Acesso</h2>	
			<br>
			<div id ="result" style="display:<?=$display?>"><?=$msg_result?></div>
		
			<form method="post" action="cadastrar_grupoacesso.php" id="FormAddGroupAccess">
				
				<div class="form-group">
					<label for="Nome do grupo">Descrição</label>
					<input type="text" name="description_groupaccess" class="form-control" id="description_groupaccess"  
					value="<?php if(isset($_POST['description_groupaccess'])) echo $_POST['description_groupaccess'] ?>" 
					placeholder="Nome do grupo de acesso" minlength="5" maxlength= "50" pattern="^[^/\s]+[a-zA-ZÀ-ú\/\s0-9]{4,50}$" size="50" 
					title="Digite o nome do grupo de acesso(Min 5 Max 50 letras e/ou numeros)" required>
				</div>

				<div class="form-group" style="background:#fffdd0">
					<span style="color:red;font-family: 'Times New Roman', Times, serif;font-size: 14px;">
						**Após o cadastro, edite as permissões do grupo.
					</span>
				</div>  
				
				<div class="form-group">
					<input type="hidden" name="saveFormAddGroupAccess" value="sucess">
					<button type="submit" class="btn btn-success my-1">Salvar</button>
				</div>
				
			</form>
		</div>
		</div>

	</body>
</html>