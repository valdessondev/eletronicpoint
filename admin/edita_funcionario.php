<?php 
//Código do menu para validar o acesso
$codigoMenu = 3;
require_once('verifica_session.php');

$display = "none";
$msg_result = "";

if(isset($_POST["saveFormEditFunc"]) && $_POST['saveFormEditFunc'] == "sucess"){
	
	$codigo_funcionario = isset($_POST["codigo_info_old"]) ? $_POST["codigo_info_old"] : "";
	$codigo_func_old = $codigo_funcionario;//Antigo pra comparar se sofreu alteração
	
	$codigo_func_current = isset($_POST["codigo_info1"]) ? $_POST["codigo_info1"]: $codigo_funcionario;//Novo
		
}else{
	
	$codigo_funcionario = isset($_POST["codigo_info"]) ? $_POST["codigo_info"] : "";
	$codigo_func_current = $codigo_funcionario;//Novo Código para comparar
}
//---CONSULTA DE TODAS AS FUNCOES------------------
$queryfuncoes = "SELECT * FROM FUNCOES";  
$exec_queryfuncoes = mysqli_query($conn, $queryfuncoes);
//----FIM CONSULTA FUNCOES-------------------------

//---CONSULTA DE TODAS AS EMPRESAS------------------
$queryempresas = "SELECT * FROM EMPRESAS";  
$exec_queryempresas = mysqli_query($conn, $queryempresas);
//----FIM CONSULTA EMPRESAS-------------------------

//---CONSULTA DE TODAS TIPOS DE CONTRATO------------------
$querytipocontrato = "SELECT * FROM TIPOCONTRATO";  
$exec_querytipocontrato = mysqli_query($conn, $querytipocontrato);
//----FIM CONSULTA TIPOS DE CONTRATO-------------------------

$query = "SELECT * FROM FUNCIONARIO WHERE CODIGO = '$codigo_funcionario'";  
$exec_query = mysqli_query($conn, $query);
$total = mysqli_num_rows($exec_query);
$registros = mysqli_fetch_assoc($exec_query);

if((isset($codigo_funcionario)) && (!empty($codigo_funcionario)) && $total > 0){
	
	if(isset($_POST["saveFormEditFunc"]) && $_POST['saveFormEditFunc'] == "sucess"){
		
		$nome_func = $_POST["nome"];
		$funcao_func = $_POST["funcao"];
		$empresa_func = $_POST["empresa"];
		$tipo_func = $_POST["tipo_funcionario"];
		$turno_func = $_POST["turno"];
		$func_ativo = $_POST["func_ativo"];
		
		//Consulta para verificar se a função está ativa ou não
		$queryverificafuncao = "SELECT FUNCAO_ID FROM FUNCOES WHERE FUNCAO_ID = '$funcao_func' AND FUNCAO_ATIVA = 0";  
		$exec_queryverificafuncao = mysqli_query($conn, $queryverificafuncao);
		$funcao_inativa_total = mysqli_num_rows($exec_queryverificafuncao);
		
		//Consulta para verificar se a empresa está ativa ou não
		$queryverificaempresa = "SELECT EMPRESA_ID FROM EMPRESAS WHERE EMPRESA_ID = '$empresa_func' AND EMPRESA_ATIVA = 0";  
		$exec_queryverificaempresa = mysqli_query($conn, $queryverificaempresa);
		$empresa_inativa_total = mysqli_num_rows($exec_queryverificaempresa);
		
		//Consulta para verificar se o tipo de contrato está ativa ou não
		$queryverificatipocontrato = "SELECT TIPOCONTRATO_ID FROM TIPOCONTRATO WHERE TIPOCONTRATO_ID = '$tipo_func' AND TIPOCONTRATO_ATIVO = 0";  
		$exec_queryverificatipocontrato = mysqli_query($conn, $queryverificatipocontrato);
		$tipocontrato_inativo_total = mysqli_num_rows($exec_queryverificatipocontrato);

		if(!preg_match("/^[^\/\s0-9]+[a-zA-ZÀ-ú\/\s]{5,79}$/",$nome_func)){

			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>- Digite o nome do funcionario(5-79 letras).</div>";
			$display = "block";

		 }elseif(!preg_match("/^[^0]+[0-9]+$/",$codigo_func_current)){

			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>- Digite um codigo(4-9 números, exceto 0 no inicio).</div>";
			$display = "block";
		
		 }elseif(!isset($nome_func) || $nome_func=="" || !isset($codigo_func_current) || $codigo_func_current==""){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>- Preencha todos os campos.</div>";
			$display = "block";

		}elseif($_POST["func_ativo"]!=1 && $registros["FUNC_ATIVO"]==0) {
				
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>- Funcionário desativado</div>";
			$display = "block";
			
		}elseif($funcao_inativa_total > 0 && $funcao_func != $registros["FUNCAO"]){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>- Função Desativada, escolha outra.</div>";
			$display = "block";
			
		}elseif ($empresa_inativa_total > 0 && $empresa_func != $registros["EMPRESA"]){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>- Empresa Desativada, escolha outra.</div>";
			$display = "block";
		
		}elseif ($tipocontrato_inativo_total > 0 && $tipo_func != $registros["TIPO_FUNCIONARIO"]){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>- Tipo Desativado, escolha outro.</div>";
			$display = "block";
		
		}elseif($codigo_func_current < 1000){
			$msg_result = "<div class='alert alert-danger'>
			- Erro ao atualizar cadastro.
			<br> - Código $codigo_func_current inválido</div>";
			$display = "block";
			
			$codigo_func_current = $codigo_func_old;
			
		}else{
		
			//O sistema irá verificar que o codigo do fucionário mudou e selecionará o update apropriado
			if($codigo_func_current != $codigo_func_old){
			
				$editafunc = "UPDATE FUNCIONARIO SET CODIGO = '$codigo_func_current', NOME = '$nome_func',
				FUNCAO = '$funcao_func', EMPRESA = '$empresa_func', TIPO_FUNCIONARIO = '$tipo_func',
				TURNO = '$turno_func', FUNC_ATIVO = '$func_ativo', DT_ALTERACAO = CURRENT_TIMESTAMP
				WHERE CODIGO = '$codigo_func_old'";
				$executarEdit = mysqli_query($conn, $editafunc);
				
				if($executarEdit){
					$updateQtdAlt = "UPDATE FUNCIONARIO SET QTD_ALT_COD = QTD_ALT_COD + 1 WHERE CODIGO = '$codigo_func_current'";
					mysqli_query($conn, $updateQtdAlt);
				}
				
				$CadastroAlterado = 1;
				
			}elseif($registros["NOME"]!=$nome_func || $registros["FUNCAO"]!=$funcao_func ||
				$registros["EMPRESA"]!=$empresa_func || $registros["TIPO_FUNCIONARIO"]!=$tipo_func ||
				$registros["TURNO"] !=$turno_func || $registros["FUNC_ATIVO"]!=$func_ativo){
					
				$editafunc = "UPDATE FUNCIONARIO SET NOME = '$nome_func', FUNCAO = '$funcao_func',
				EMPRESA = '$empresa_func', TIPO_FUNCIONARIO = '$tipo_func', TURNO = '$turno_func'
				,FUNC_ATIVO = '$func_ativo',DT_ALTERACAO = CURRENT_TIMESTAMP WHERE CODIGO = '$codigo_func_old'";
				$executarEdit = mysqli_query($conn, $editafunc);
				
				$CadastroAlterado = 1;
			}else{
				$CadastroAlterado = 0;
				$executarEdit = 0;
			}

			if($executarEdit && $CadastroAlterado==1){
				
				$msg_result = "<div class='alert alert-success'>Salvo com sucesso.</div>";
				$display = "block";
				
			} elseif($CadastroAlterado==0) {
				
				$msg_result = "<div class='alert alert-success'>Salvo com sucesso.
				<br> -> Nenhuma alteração realizada</div>";
				$display = "block";
				
				$codigo_func_current = $codigo_func_old;
			}else{
				
				$msg_result = "<div class='alert alert-danger'>- Erro ao atualizar cadastro.
				<br> - Verifique se o código já existe tente novamente.
				<br> - Caso a falha persista, contate o administrador</div>";
				$display = "block";
				
				$codigo_func_current = $codigo_func_old;
			}
		}
	}

	//Se padrão obedecido recebe novo código, senão recebe o antigo
	$codigo_funcionario = preg_match("/^[^0]+[0-9]+$/",$codigo_func_current)?$codigo_func_current:$codigo_funcionario;

	$query = "SELECT * FROM FUNCIONARIO WHERE CODIGO = '$codigo_funcionario'";  
	$exec_query = mysqli_query($conn, $query);
	$total = mysqli_num_rows($exec_query);
	$registros = mysqli_fetch_assoc($exec_query);
	?>
	
	<!DOCTYPE html>
	<html>
		<head>
			<title>Editar Cadastro de funcionario :: Break | Controle de Ponto Eletrônico</title>
			
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
			
			<script src="../js/jquery-3.3.1.min.js"></script>
			<script type="text/javascript" src="..\js\script.js"></script>
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
				<a class="btn btn-dark" href="lista_funcionarios.php" style="margin-left: 12px">Voltar</a>
				
				<a href="sair_admin.php" class="btn btn-secondary btn-sm" style="margin-left: 12px" >Sair</a>
				
				<h2>Edita Cadastro - Funcionário</h2>	
				<br>
				
				<?php if($registros["FUNC_ATIVO"]==0){ ?>
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
				
				<div id ="result" style="display:<?=$display?>"><?=$msg_result?></div>
				
				<div id="InformacoesAlteracao">
					<div class="form-group h6">
						Cadastro:
						<?=date("d/m/Y H:i:s", strtotime($registros['DT_CADASTRO']))?><br>
					</div>
					<?php if ($registros['DT_ALTERACAO'] != null){?>
						<div class="form-group h6"> Alteração: <?=date("d/m/Y H:i:s", strtotime($registros['DT_ALTERACAO']))?></div>
					<?php } ?>
				</div>
			
				<form method="post" action="edita_funcionario.php" id="FormEditFuncionario">
				
					<div class="form-group">
					<label for="Nome">Nome</label>
					<input type="text" name="nome" class="form-control" id="nome" placeholder="Nome do funcionário"
					value = "<?=$registros['NOME']?>"  minlength="5" maxlength="79" title="Digite o nome do funcionario(5-79 Caracteres)." 
					pattern="^[^/\s0-9][a-zA-ZÀ-ú/\s]{5,79}$" required>
					</div>
					
					<div class="form-group">
					<label for="Funcao">Função</label>
					<select name="funcao" class="form-control" id="funcao">
					
					<?php while($registrosfuncoes = mysqli_fetch_array($exec_queryfuncoes)){ ?>
						<option value="<?=$registrosfuncoes['FUNCAO_ID']?>" 
						<?php if($registrosfuncoes['FUNCAO_ATIVA']==0) echo 'class="table-dark"';?>
						<?php if($registros['FUNCAO'] == $registrosfuncoes['FUNCAO_ID']) echo "selected"; ?>>
						<?=$registrosfuncoes['NM_FUNCAO']?></option>
						
					<?php }?>	
					
					</select>
					</div>
					
					<div class="form-group">
					<label for="Altecoes no código">Código(Min 4 Max 9 dígitos)<br> Alterações: <?=$registros['QTD_ALT_COD']?> de 3</label>
					<input type="text" name="codigo_info1" class="form-control" id="codigo_info1" placeholder="123456789" 
					value = "<?php if (isset($_POST['codigo_info1']))echo $_POST['codigo_info1']; else echo $registros['CODIGO'];?>" 
					required <?php if ($registros['QTD_ALT_COD'] >= 3) echo "disabled";?> 
					pattern="[^0]+[0-9]+$" minlength="4" maxlength= "9" size="9" title="Digite um codigo(4-9 números, exceto 0 no inicio).">
					<input type="hidden" name="codigo_info_old" value = "<?=$registros['CODIGO']?>">
					
					<?php if ($registros['QTD_ALT_COD'] >= 2) ?><input type="hidden" name="codigo_info" value="<?=$registros['CODIGO']?>"> 						
					</div>
					
					<div class="form-group">
					<label for="Empresa">Empresa</label>
					<select name="empresa" class="form-control" id="empresa" required>
						<!-- NOME EMPRESA -->
						<?php while($registrosempresas = mysqli_fetch_array($exec_queryempresas)){ ?>
						<option value="<?=$registrosempresas['EMPRESA_ID']?>" 
						<?php if($registrosempresas['EMPRESA_ATIVA']==0) echo 'class="table-dark"';?>
						<?php if($registros['EMPRESA'] == $registrosempresas['EMPRESA_ID']) echo "selected"; ?>>
						<?=$registrosempresas['NM_EMPRESA']?></option>
						
					<?php }?> 
					</select>
					</div>
					
					<div class="form-group">
					<label for="Tipo de Funcionário">Tipo Funcionario</label>
					<select name="tipo_funcionario" class="form-control" id="tipo_funcionario" required>
					
					<?php while($registrostipocontrato = mysqli_fetch_array($exec_querytipocontrato)){ ?>
						<option value="<?=$registrostipocontrato['TIPOCONTRATO_ID']?>" 
						<?php if($registrostipocontrato['TIPOCONTRATO_ATIVO']==0) echo 'class="table-dark"';?>
						<?php if($registros['TIPO_FUNCIONARIO'] == $registrostipocontrato['TIPOCONTRATO_ID']) echo "selected"; ?>>
						<?=$registrostipocontrato['NM_TIPOCONTRATO']?></option>
						
					<?php }?>	
					
					</select>
					</div>	  
					
					<div class="form-group">
					<label for="Tuno">Turno</label>
					<select name="turno" class="form-control" id="turno" required>
						<option value="I" <?php if($registros['TURNO'] == "I") echo "selected" ?>>INTEGRAL</option>
						<!--<option value="M" <?php //if($registros['TURNO'] == "M") echo "selected" ?>>MATUTINO</option>-->
						<!--<option value="V" <?php //if($registros['TURNO'] == "V") echo "selected" ?>>VESPERTINO</option>-->
						
					</select>
					</div>	

				<div class="form-group">
					<label for="Situacao do Cadastro">Situacao do Cadastro:</label>
					<select name="func_ativo" class="form-control" id="func_ativo" required>
						<option value="1" <?php if($registros['FUNC_ATIVO'] == 1) echo "selected" ?>>ATIVO</option>
						<option value="0" <?php if($registros['FUNC_ATIVO'] == 0) echo "selected" ?>>DESATIVADO</option> 
					</select>
					</div>
					
					<div class="form-group">
					<input type="hidden" name="saveFormEditFunc" value="sucess">
					<button type="submit" class="btn btn-success my-1">Salvar</button>
					</div>
					
				</form>
				
				</div>
			</div>

		</body>
	</html>
	<?php
	}else{echo '<script type="text/javascript">window.location.href="lista_funcionarios.php";</script>';}