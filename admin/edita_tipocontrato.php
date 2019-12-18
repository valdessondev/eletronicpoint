<?php 
//Código do menu para validar o acesso
$codigoMenu = 7;
require_once('verifica_session.php');

$display = "none";
$msg_result = "";

$codigo_tipocontrato = isset($_POST["code_tipocontrato"]) ? $_POST["code_tipocontrato"] : "";

$query_user = "SELECT * FROM TIPOCONTRATO WHERE TIPOCONTRATO_ID = '$codigo_tipocontrato'";  
$exec_query = mysqli_query($conn, $query_user);
$total = mysqli_num_rows($exec_query);
$registros = mysqli_fetch_assoc($exec_query);

if((isset($codigo_tipocontrato)) && (!empty($codigo_tipocontrato)) && $total > 0){
	
	if(isset($_POST["saveFormEditTipocontrato"]) && $_POST['saveFormEditTipocontrato'] == "sucess"){
		
		$codigo_tipocontrato = $_POST["code_tipocontrato"];
		$NM_TIPOCONTRATO = isset($_POST["description_tipocontrato"]) ? $_POST["description_tipocontrato"] : "";
		$tipocontrato_ativo = $_POST["tipocontrato_ativo"];
		$qtd_registros = $_POST["qtd_registros"];
		$relatoriopausaativa = isset($_POST["relatoriopausaativa"])? 1 : 0 ;
		
		$CadastroAlterado = 0;
			
			
	//-----------------Consulta as descrições de todos os tipos de contratos-----------------------
		$query_tipocontrato = "SELECT NM_TIPOCONTRATO FROM TIPOCONTRATO WHERE NM_TIPOCONTRATO = '$NM_TIPOCONTRATO'";  
		$exec_query_all = mysqli_query($conn, $query_tipocontrato);
		$total_tipocontrato = mysqli_num_rows($exec_query_all);
	//----------------------------------------------------------------------------------
		
	if(!isset($NM_TIPOCONTRATO) || empty($NM_TIPOCONTRATO)){
		$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>-> Preencha todos os campos.</div>";
		$display = "block";

	}elseif(!preg_match("/^[^\/\s]+[a-zA-ZÀ-ú\/\s0-9]{4,50}$/",$NM_TIPOCONTRATO)){
		$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>
		-> Digite o nome do tipo de contrato(Min 5 Max 50 letras e/ou numeros)</div>";
		$display = "block";
	
	}elseif($_POST["tipocontrato_ativo"]!=1 && $registros["TIPOCONTRATO_ATIVO"]==0){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>-> Tipo de Contrato Desativado</div>";
			$display = "block";
			
	}elseif($NM_TIPOCONTRATO != $registros['NM_TIPOCONTRATO'] && $total_tipocontrato > 0){
		$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>-> Este Tipo de Contrato já Existe</div>";
		$display = "block";
		
	}elseif($registros["NM_TIPOCONTRATO"] != $NM_TIPOCONTRATO || $registros["TIPOCONTRATO_ATIVO"]!= $tipocontrato_ativo ||
		$registros["QTDREGISTROS"]!= $qtd_registros || $registros["RELATORIOPAUSAATIVA"] != $relatoriopausaativa){	
		
		$edittipocontrato = "UPDATE TIPOCONTRATO SET NM_TIPOCONTRATO = '$NM_TIPOCONTRATO', TIPOCONTRATO_ATIVO = '$tipocontrato_ativo',
		QTDREGISTROS = '$qtd_registros',RELATORIOPAUSAATIVA = '$relatoriopausaativa', DT_ALTERACAO = CURRENT_TIMESTAMP WHERE TIPOCONTRATO_ID = '$codigo_tipocontrato'";
		$executarEditTipocontrato = mysqli_query($conn, $edittipocontrato);

		if($executarEditTipocontrato){
			
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
	$query_tipocontrato = "SELECT * FROM TIPOCONTRATO WHERE TIPOCONTRATO_ID = '$codigo_tipocontrato'";  
	$exec_query = mysqli_query($conn, $query_tipocontrato);
	$total = mysqli_num_rows($exec_query);
	$registros = mysqli_fetch_assoc($exec_query);
	?>
	
	<!DOCTYPE html>
	<html>
		<head>
			<title>Editar Cadastro Tipos de Contrato :: Break | Controle de Ponto Eletrônico</title>
			
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
				<a class="btn btn-dark" href="lista_tipocontrato.php" style="margin-left: 12px">Voltar</a>
				
				<a href="sair_admin.php" class="btn btn-secondary btn-sm" style="margin-left: 12px" >Sair</a>
				
				<h2>Edita Cadastro - Tipo de Contrato</h2>	
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
				
				<?php if($registros["TIPOCONTRATO_ATIVO"]==0){ ?>
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
			
				<form method="post" action="edita_tipocontrato.php" id="FormEditTipocontrato">
				
					<div class="form-group">

						<div class="form-group">
							<label for="Código do Tipo de Contrato">Código:</label>
							<?=$registros['TIPOCONTRATO_ID']?>
						</div>

						<div class="form-group">
						<label for="Descrição do Tipo de Contrato">Descrição</label>
						<input type="text" name="description_tipocontrato" class="form-control" id="description_tipocontrato"  
						value = "<?php if (isset($_POST['description_tipocontrato']))echo $_POST['description_tipocontrato']; else echo $registros['NM_TIPOCONTRATO'];?>"
						placeholder="Nome do tipo de contrato" minlength="5" maxlength= "50" pattern="^[^/\s]+[a-zA-ZÀ-ú\/\s0-9]{4,50}$" size="50" 
						title="Digite o nome do tipo de contrato(Min 5 Max 50 letras e/ou numeros)" required>
						
						<input type="hidden" name="code_tipocontrato" value = "<?=$registros['TIPOCONTRATO_ID']?>">
						</div>	
						
					<div class="form-group">
						<label for="Quantidade de Registros/Batidas">Quantidade de Registros/Batidas:</label>
						<select name="qtd_registros" class="form-control" id="qtd_registros" required>
							<option value="2" <?php if($registros['QTDREGISTROS'] == 2) echo "selected";?>>2</option>
							<option value="4" <?php if($registros['QTDREGISTROS'] == 4) echo "selected";?>>4</option>
							<option value="6" <?php if($registros['QTDREGISTROS'] == 6) echo "selected";?>>6</option>						  
						</select>
					</div>
					
						<div class="form-group">
						<label for="Situação do Cadastro">Situacao do Cadastro:</label>
						<select name="tipocontrato_ativo" class="form-control" id="tipocontrato_ativo" required>
							<option value="1" <?php if($registros['TIPOCONTRATO_ATIVO'] == 1) echo "selected" ?>>ATIVO</option>
							<option value="0" <?php if($registros['TIPOCONTRATO_ATIVO'] == 0) echo "selected" ?>>DESATIVADO</option> 
						</select>
						</div>
						
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="defaultChecked2" name="relatoriopausaativa" 
						<?php if($registros["RELATORIOPAUSAATIVA"]== 1 ) echo "checked"; ?>>
						<label class="custom-control-label" for="defaultChecked2"> Painel Pausa/Intervalo Ativo</label>
					</div>
					
						<div class="form-group" style="margin-top:10px;text-align:center">
						<input type="hidden" name="codigo_tipocontrato" value="<?=$registros['TIPOCONTRATO_ID']?>">
						<input type="hidden" name="saveFormEditTipocontrato" value="sucess">
						<button type="submit" class="btn btn-success my-1">Salvar</button>
						</div>
					</div>  
				</form>
				
				
			</div>

		</body>
	</html>
<?php
}else{echo '<script type="text/javascript">window.location.href="lista_tipocontrato.php";</script>';}
//http://www.richardbarros.com.br/blog/css/css-truques-para-dominar-a-propriedade-float