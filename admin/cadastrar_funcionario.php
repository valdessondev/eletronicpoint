<?php 
//Código do menu para validar o acesso
$codigoMenu = 3;
require_once('verifica_session.php');

$display = "none";
$msg_result = "";

//----CONSULTA DE TODAS AS FUNÇÕES ATIVAS---------
$queryfuncoes = "SELECT * FROM FUNCOES WHERE FUNCAO_ATIVA = 1";  
$exec_queryfuncoes = mysqli_query($conn, $queryfuncoes);
//----FIM FUNCOES ATIVAS-----------------------------

//----CONSULTA DE TODAS AS EMPRESAS ATIVAS---------
$queryempresas= "SELECT * FROM EMPRESAS WHERE EMPRESA_ATIVA = 1";  
$exec_queryempresas = mysqli_query($conn, $queryempresas);
//----FIM EMPRESAS ATIVAS-----------------------------

//----CONSULTA DE TODOS OS TIPOS DE CONTRATOS---------
$querytiposcontrato = "SELECT * FROM TIPOCONTRATO WHERE TIPOCONTRATO_ATIVO = 1";  
$exec_querytiposcontrato = mysqli_query($conn, $querytiposcontrato);
//----FIM TIPOS DE CONTRATO ATIVOS-----------------------------

if(isset($_POST["saveAddEditFunc"]) && $_POST['saveAddEditFunc'] == "sucess"){
	
	$nome_func = isset($_POST["nome"]) ? $_POST["nome"] : "";
	$funcao_func = isset($_POST["funcao"]) ? $_POST["funcao"] : "";
	$empresa_func = isset($_POST["empresa"]) ? $_POST["empresa"] : "";
	$tipo_func = isset($_POST["tipo_funcionario"]) ? $_POST["tipo_funcionario"] : "";
	$turno_func = isset($_POST["turno"]) ? $_POST["turno"] : "" ;
	$codigo_funcionario = isset($_POST["codigo_info1"]) ? $_POST["codigo_info1"] : "";
	
	if(!isset($nome_func) || empty($nome_func) || !isset($funcao_func) || empty($funcao_func)
	|| !isset($empresa_func) || empty($empresa_func) || !isset($tipo_func) || empty($tipo_func)
	|| !isset($codigo_funcionario) || empty($codigo_funcionario)){

		$msg_result = "<div class='alert alert-danger'> - Falha ao inserir cadastro.
		<br>- Preencha todos os campos.</div>";
		$display = "block";
			
	}elseif(!preg_match("/^[^\/\s0-9]+[a-zA-ZÀ-ú\/\s]{5,79}$/",$nome_func)){

		$msg_result = "<div class='alert alert-danger'>- Falha ao inserir cadastro. <br>- Digite o nome do funcionario(5-79 letras).</div>";
		$display = "block";

	 }elseif(!preg_match("/^[^0]+[0-9]+$/",$codigo_funcionario)){

		$msg_result = "<div class='alert alert-danger'>- Falha ao inserir cadastro. <br>- Digite um codigo(4-9 números, exceto 0 no inicio).</div>";
		$display = "block";
	
	 }else{
	
		$addfunc = "INSERT INTO FUNCIONARIO (CODIGO,NOME,FUNCAO,EMPRESA,TIPO_FUNCIONARIO,TURNO) 
		VALUES ('$codigo_funcionario','$nome_func','$funcao_func','$empresa_func','$tipo_func','$turno_func')";
		$executarAdd = mysqli_query($conn, $addfunc);

		if($executarAdd){
			$msg_result = "<div class='alert alert-success'>Cadastro realizado com sucesso.
							<a href=''>[CADASTRAR NOVO]</a>
							</div>";
			$display = "block";
			
		}else {
			$msg_result = "<div class='alert alert-danger'> - Falha ao inserir cadastro.
			<br>- Verifique se o código já existe</div>";
			$display = "block";
		}
	}
}
?>
	
<!DOCTYPE html>
<html>
	<head>
		<title>Cadastrar Funcionario :: Break | Controle de Ponto Eletrônico</title>

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
			
			<h2>Cadastrar Funcionário</h2>	
			<br>
			<div id ="result" style="display:<?=$display?>"><?=$msg_result?></div>
			
			<form method="post" action="cadastrar_funcionario.php" id="FormEditFuncionario">
			
				<div class="form-group">
				<label for="Nome do Funcionário">Nome</label>
				<input type="text" name="nome" class="form-control" id="nome" placeholder="Nome do funcionário"
				maxlength="79" value="<?php if(isset($_POST["nome"])) echo $_POST["nome"]?>" 
				pattern="^[^/\s0-9][a-zA-ZÀ-ú/\s]{5,79}$" title="Digite o nome do funcionario(5-79 Caracteres)." required>
				</div>
				
				<div class="form-group">
					<label for="Função do Funcionário">Função</label>
					<select name="funcao" class="form-control" id="funcao" required>
					<option value="">--------- Selecione---------- </option>
					
					<?php while($registrosfuncoes = mysqli_fetch_array($exec_queryfuncoes)){ ?>
						<option value="<?=$registrosfuncoes['FUNCAO_ID']?>" 
						<?php if(isset($_POST["funcao"]) && $_POST["funcao"]==$registrosfuncoes['FUNCAO_ID'])
						echo "selected"; ?>><?=$registrosfuncoes['NM_FUNCAO']?></option>
					<?php }?>	
					
					</select>
				</div>
				
				<div class="form-group">
				<label for="Código do Funcionáro">Código (Min 4 Max 9 dígitos)</label>
				<input type="text" name="codigo_info1" class="form-control" id="codigo_info1" 
				placeholder="123456789" value="<?php if(isset($_POST["codigo_info1"])) echo $_POST["codigo_info1"]?>"
				pattern="[^0]+[0-9]+$" minlength="4" maxlength= "9" size="9" 
				title="Digite um codigo(4-9 números, exceto 0 no inicio)." required>
							
				</div>
				
				<div class="form-group">
				<label for="Empresa do Funcionário">Empresa</label>
				<select name="empresa" class="form-control" id="empresa" required>
					<!-- NOME EMPRESA -->
					<option value="">--------- Selecione---------- </option>
					<?php while($registrosempresas = mysqli_fetch_array($exec_queryempresas)){ ?>
					<option value="<?=$registrosempresas['EMPRESA_ID']?>"
					 <?php if(isset($_POST["empresa"]) && $_POST["empresa"]==$registrosempresas['EMPRESA_ID']) 
					 echo "selected"; ?>><?=$registrosempresas['NM_EMPRESA']?></option>
					<?php }?> 
				</select>
				</div>
				
				<div class="form-group">
				<label for="Tipo do Funcionário">Tipo Funcionario</label>
				<select name="tipo_funcionario" class="form-control" id="tipo_funcionario" required> 
					<option value="">--------- Selecione---------- </option>
					<?php while($registrostiposcontrato = mysqli_fetch_array($exec_querytiposcontrato)){ ?>
					<option value="<?=$registrostiposcontrato['TIPOCONTRATO_ID']?>"
					<?php if(isset($_POST["tipo_funcionario"]) && $_POST["tipo_funcionario"]==$registrostiposcontrato['TIPOCONTRATO_ID']) 
					echo "selected"; ?>>
						<?=$registrostiposcontrato['NM_TIPOCONTRATO']?>
					</option>
					<?php }?>
				</select>
				</div>	  
				
				<div class="form-group">
				<label for="Turno do funcionário">Turno</label>
				<select name="turno" class="form-control" id="turno" required>
					<option value="I" selected>INTEGRAL</option>
					<!--<option value="M">MATUTINO</option>-->
					<!--<option value="V">VESPERTINO</option>-->
				</select>
				</div>	  
				
				<div class="form-group">
				<input type="hidden" name="saveAddEditFunc" value="sucess">
				<button type="submit" class="btn btn-success my-1">Cadastrar</button>
				</div>
				
			</form>
		</div>

	</body>
</html>