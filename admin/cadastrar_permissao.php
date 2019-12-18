<?php 
//Código do menu para validar o acesso
$codigoMenu = 9;
require_once('verifica_session.php');

$display = "none";
$msg_result = "";
if(isset($_POST["saveFormAddPerssion"]) && $_POST['saveFormAddPerssion'] == "sucess"){
	
	$description_permisson = isset($_POST["description_permisson"]) ? $_POST["description_permisson"] : "";
	
	//-----------------Consulta as descrições de todas as permissoes-----------------------
	$query_permissoes = "SELECT NM_PERMISSOES FROM PERMISSOES WHERE NM_PERMISSOES = '$description_permisson'";  
	$exec_query_all = mysqli_query($conn, $query_permissoes);
	$total_permissoes = mysqli_num_rows($exec_query_all);
	//----------------------------------------------------------------------------------
	
	if(!isset($description_permisson) || empty($description_permisson)){
		$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>
		-> Preencha todos os campos.</div>";
		$display = "block";

	}elseif(!preg_match("/^[^\/\s]+[a-zA-ZÀ-ú\/\s0-9]{4,50}$/",$description_permisson)){
		$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>
		-> Digite o nome da permissão(Min 5 Max 50 letras e/ou numeros)</div>";
		$display = "block";
	
	}elseif($total_permissoes > 0){
			$msg_result = "<div class='alert alert-danger'>- Erro ao atualizar cadastro. 
			<br>- Esta permissão já existe</div>";
			$display = "block";
			
	}else{
	
		$addpermissao = "INSERT INTO PERMISSOES (NM_PERMISSOES) VALUES('$description_permisson')";
		$executAddPermissoes = mysqli_query($conn, $addpermissao);

		if($executAddPermissoes){
			
			$msg_result = "<div class='alert alert-success'>Salvo com sucesso.
							<br><a href=''>[CADASTRAR NOVA]</a>
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
		<title>Cadastrar Permissões :: Break | Controle de Ponto Eletrônico</title>

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
			<a class="btn btn-dark" href="lista_permissoes.php" style="margin-left: 12px">Voltar</a>
			<a href="sair_admin.php" class="btn btn-secondary btn-sm" style="margin-left: 12px" >Sair</a>
			
			<h2>Cadastrar Permissão</h2>	
			<br>
			<div id ="result" style="display:<?=$display?>"><?=$msg_result?></div>
		
			<form method="post" action="cadastrar_permissao.php" id="FormAddPermission">
				
				<div class="form-group">
					<label for="Nome da Função">Descrição</label>
					<input type="text" name="description_permisson" class="form-control" id="description_permisson"  
					value="<?php if(isset($_POST['description_permisson'])) echo $_POST['description_permisson'] ?>" 
					placeholder="Nome da permissão" minlength="5" maxlength= "50" pattern="^[^/\s]+[a-zA-ZÀ-ú\/\s0-9]{4,50}$" size="50" 
					title="Digite o nome da permissao(Min 5 Max 50 letras e/ou numeros)." required>
				</div> 
				<div class="form-group" style="background:#fffdd0">
					<span style="color:red;font-family: 'Times New Roman', Times, serif;font-size: 14px;">
						**Após o cadastro, edite os menus da permissão.
					</span>
				</div>  
				
				<div class="form-group">
					<input type="hidden" name="saveFormAddPerssion" value="sucess">
					<button type="submit" class="btn btn-success my-1">Salvar</button>
				</div>
				
			</form>
		</div>
		</div>

	</body>
</html>
