<?php 
$codigoMenu = 5;
require_once('verifica_session.php');

$display = "none";
$msg_result = "";

$codigo_funcao = isset($_POST["code_function"]) ? $_POST["code_function"] : "";

$query_user = "SELECT * FROM FUNCOES WHERE FUNCAO_ID = '$codigo_funcao'";  
$exec_query = mysqli_query($conn, $query_user);
$total = mysqli_num_rows($exec_query);
$registros = mysqli_fetch_assoc($exec_query);
	
if((isset($codigo_funcao)) && (!empty($codigo_funcao)) && $total > 0){
	
	if(isset($_POST["saveFormEditFunction"]) && $_POST['saveFormEditFunction'] == "sucess"){
		
		$codigo_funcao = $_POST["code_function"];
		$NM_FUNCAO = $_POST["description_function"] = isset($_POST["description_function"]) ? $_POST["description_function"]: "";
		$function_ativo = $_POST["function_ativo"];
		
		$CadastroAlterado = 0;
			
			
	//-----------------Consulta as descrições de todas as funções-----------------------
		$query_user_funtion = "SELECT NM_FUNCAO FROM FUNCOES WHERE NM_FUNCAO = '$NM_FUNCAO'";  
		$exec_query_all = mysqli_query($conn, $query_user_funtion);
		$total_function = mysqli_num_rows($exec_query_all);
	//----------------------------------------------------------------------------------
		
		if(!isset($NM_FUNCAO) || empty($NM_FUNCAO)){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>-> Preencha todos os campos.</div>";
			$display = "block";

		}elseif(!preg_match("/^[^\/\s]+[a-zA-ZÀ-ú\/\s0-9]{4,50}$/",$NM_FUNCAO)){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>
			-> Digite o nome da função(Min 5 Max 50 letras e/ou numeros)</div>";
			$display = "block";
		
		}elseif($_POST["function_ativo"]!=1 && $registros["FUNCAO_ATIVA"]==0){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>
			-> Função Desativada</div>";
			$display = "block";
			
		}elseif($NM_FUNCAO != $registros['NM_FUNCAO'] && $total_function > 0){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>
			-> Esta função Já Existe</div>";
			$display = "block";
			
		}elseif($registros["NM_FUNCAO"] != $NM_FUNCAO || $registros["FUNCAO_ATIVA"]!= $function_ativo){			
			$editfunction = "UPDATE FUNCOES SET NM_FUNCAO = '$NM_FUNCAO', FUNCAO_ATIVA = '$function_ativo' 
							,DT_ALTERACAO = CURRENT_TIMESTAMP WHERE FUNCAO_ID = '$codigo_funcao'";
			$executarEditFunction = mysqli_query($conn, $editfunction);

			if($executarEditFunction){
				
				$msg_result = "<div class='alert alert-success'>Salvo com sucesso.</div>";
				$display = "block";
			}
			$CadastroAlterado = 1;	
			
		}elseif($CadastroAlterado == 0){
			
			$msg_result = "<div class='alert alert-success'>Salvo com sucesso.
			<br> -> Nenhuma alteração realizada. </div>";
			$display = "block";
			
		}else {
			
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. Contate o administrador.</div>";
			$display = "block";		
		}
	}
	$query_user = "SELECT * FROM FUNCOES WHERE FUNCAO_ID = '$codigo_funcao'";  
	$exec_query = mysqli_query($conn, $query_user);
	$total = mysqli_num_rows($exec_query);
	$registros = mysqli_fetch_assoc($exec_query);
	?>
	
	<!DOCTYPE html>
	<html>
		<head>
			<title>Editar Cadastro de Funções :: Break | Controle de Ponto Eletrônico</title>
			
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			<meta name="description" content="">
			<meta name="author" content="">
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<meta http-equiv="x-ua-compatible" content="IE=edge, chrome=1">
			<link rel="icon" href="../favicon.ico">
			
			<!-- Principal CSS do Bootstrap -->
			<link href="../css/bootstrap.min.css" rel="stylesheet">
			<link href="../css/estilo.css" rel="stylesheet">
			
			<script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
			<script type="text/javascript" src="../js/script.js"></script>
			<script type="text/javascript" src="../js/bootstrap.min.js"></script>
			
			<script type="text/javascript">
				$(document).ready(function(){
					$('div#result').fadeIn(5000);
					//$('div#result').fadeOut(10000);
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
				<a class="btn btn-dark" href="lista_funcoes.php" style="margin-left: 12px">Voltar</a>
				
				<a href="sair_admin.php" class="btn btn-secondary btn-sm" style="margin-left: 12px" >Sair</a>
				
				<h2>Edita Cadastro - Função</h2>	
				<br>
				
				<div id="InformacoesAlteracao">
					<div class="form-group h6">
						Cadastro:
						<?=date("d/m/Y H:i:s", strtotime($registros['DT_CADASTRO']))?><br>
					</div>
					<?php if ($registros['DT_ALTERACAO'] != null){?>
						<div class="form-group h6"> Alteração: <?=date("d/m/Y H:i:s", strtotime($registros['DT_ALTERACAO']))?></div>
					<?php } ?>
				</div>
				
				<?php if($registros["FUNCAO_ATIVA"]==0){ ?>
					<div class="aviso-proibido-alteracao alert alert-warning" role="alert">
						<div id="divimg-aviso-proibido-alteracao">
							<img src="../images/alert.png"/>
						</div>
						<div id="msg-aviso-proibido-alteracao">
							Não é permitido editar cadastro desativado<br>
							Ative-o antes.
						</div>
						<div class = "clear-both"></div>
					</div>
				<?php }?>
				
				<div id ="result" style="display:<?=$display?>;position:relative;"><?=$msg_result?></div>
			
				<form method="post" action="edita_funcao.php" id="FormEditFunction">
				
					<div class="form-group">
					
					<div class="form-group">
						<label for="Código da Função">Código:</label>
						<?=$registros['FUNCAO_ID']?>
					</div>

						<div class="form-group">
						<label for="Descrição da Função">Descrição</label>
						<input type="text" name="description_function" class="form-control" 
						id="description_function" placeholder="Nome da função" 
						value = "<?php if (isset($_POST['description_function']))echo $_POST['description_function']; else echo $registros['NM_FUNCAO'];?>" 
						minlength="5" maxlength= "50" pattern="^[^/\s]+[a-zA-ZÀ-ú\/\s0-9]{4,50}$" size="50" 
						title="Digite o nome da função(Min 5 Max 50 letras e/ou numeros)" required>
						<input type="hidden" name="code_function" value = "<?=$registros['FUNCAO_ID']?>">
						</div>	
						
						<div class="form-group">
						<label for="Situação do Cadastro">Situacao do Cadastro:</label>
						<select name="function_ativo" class="form-control" id="function_ativo" required>
							
							<option value="1" <?php if($registros['FUNCAO_ATIVA'] == 1) echo "selected" ?>>ATIVO</option>
							<option value="0" <?php if($registros['FUNCAO_ATIVA'] == 0) echo "selected" ?>>DESATIVADO</option> 
						</select>
						</div>
					
						<div class="form-group">
						<input type="hidden" name="codigo_function" value="<?=$registros['FUNCAO_ID']?>">
						<input type="hidden" name="saveFormEditFunction" value="sucess">
						<button type="submit" class="btn btn-success my-1">Salvar</button>
						</div>
					</div>  
				</form>
				
				
			</div>

		</body>
	</html>
<?php
}else{echo '<script type="text/javascript">window.location.href="lista_funcoes.php";</script>';}

//http://www.richardbarros.com.br/blog/css/css-truques-para-dominar-a-propriedade-float