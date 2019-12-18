<?php
//Código do menu para validar o acesso
$codigoMenu = 10;
require_once('verifica_session.php');

//Recuperando o código do usuário logado
$codigouser = $_SESSION['USER_ID'];
	
$display = "none";
$msg_result = "";

$mes_atual = date('d-m-Y');

//---------CONSULTA PARAMETROS--------------		
$queryparametros = "SELECT 
						P.EMPPADRAO,E.NM_EMPRESA,
						P.QTD_MAX_PAUSA,P.MINUTOS,
						P.TMP_MIN_PAUSE 
					FROM 
						PARAMETROS AS P
					INNER JOIN 
						USERS AS U
					ON
						P.PARAMETRO_ID = U.PARAMETRO_ID 
					INNER JOIN 
						EMPRESAS AS E
					ON
						P.EMPPADRAO = E.EMPRESA_ID
					WHERE 
						U.ID='$codigouser'
					LIMIT 1";  
$exec_queryparametros = mysqli_query($conn,$queryparametros);
$registrosparametroempresa = mysqli_fetch_assoc($exec_queryparametros);

$empresapadrao = $registrosparametroempresa["EMPPADRAO"];
//----------FIM CONSULTA PARAMETROS--------------

//----------CONSULTA TODAS AS EMPRESAS EMPRESAS------------
$queryempresas = "SELECT * FROM EMPRESAS";
$exec_queryempresas = mysqli_query($conn,$queryempresas);	
//----------FIM CONSULTA EMPRESAS------------------

//-----VERIFICAÇÕES E ATUALIZAÇÕES----------
if(isset($_POST["saveParameter"]) && $_POST['saveParameter'] == "sucess"){
	
	$codigo_parametro = $_POST["codeparameter"];
	$vmaxpessoas = isset($_POST["vmaxpessoas"]) ? $_POST["vmaxpessoas"] : "";
	$vminutos = isset($_POST["vminutos"]) ? $_POST["vminutos"] : "";
	$vempresapadrao = $_POST["vempresapadrao"];
	$tmp_min_pause = $_POST['tmp_min_pause'];
	
	$CadastroAlterado = 0;

	if(!isset($vmaxpessoas) || !isset($vminutos) || !isset($tmp_min_pause) ){
		$msg_result = "<div class='alert alert-warning'>Impossível Atualização<br>
		Preencha todos os campos.</div>";
		$display = "block";

	}elseif($vmaxpessoas < 0 || $vminutos < 0 || $tmp_min_pause < 0){
		$msg_result = "<div class='alert alert-warning'>Impossível Atualização (Valor Inválido!)<br>
		->Intervalo Permitido: 0 a 400</div>";
		$display = "block";
		
	}elseif($vmaxpessoas > 400 || $vminutos > 400 || $tmp_min_pause > 400){
		$msg_result = "<div class='alert alert-warning'>Impossível Atualização (Valor Inválido!)<br>
			->Intervalo Permitido: 0 a 400</div>";
		$display = "block";
		
	}elseif(($registrosparametroempresa["QTD_MAX_PAUSA"] != $vmaxpessoas) || ($registrosparametroempresa["MINUTOS"] != $vminutos) 
	|| ($registrosparametroempresa["EMPPADRAO"] != $vempresapadrao) || $registrosparametroempresa["TMP_MIN_PAUSE"] != $tmp_min_pause){
		
		$editparameter = "UPDATE PARAMETROS SET QTD_MAX_PAUSA = '$vmaxpessoas', MINUTOS = '$vminutos', 
		TMP_MIN_PAUSE = '$tmp_min_pause',EMPPADRAO = '$vempresapadrao', DT_ALTERACAO = CURRENT_TIMESTAMP 
		WHERE PARAMETRO_ID = '$codigo_parametro'";		
		$executarEditParameter = mysqli_query($conn, $editparameter);
		
		
		if($executarEditParameter){
			
			$msg_result = "<div class='alert alert-success'>Salvo com sucesso.</div>";
			$display = "block";
		}else{
			
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. Contate o administrador.</div>";
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
//------FIM ATUALIZAÇÕES--------------------

//---Consulta Atualizada---------------
$queryparametros = "SELECT 
					P.EMPPADRAO,E.NM_EMPRESA,
					P.QTD_MAX_PAUSA,P.MINUTOS,
					P.TMP_MIN_PAUSE, P.DT_CADASTRO,
					P.DT_ALTERACAO, P.PARAMETRO_ID 
					FROM 
					PARAMETROS AS P
					INNER JOIN 
					USERS AS U
					ON
					P.PARAMETRO_ID = U.PARAMETRO_ID 
					INNER JOIN 
					EMPRESAS AS E
					ON
					P.EMPPADRAO = E.EMPRESA_ID
					WHERE 
					U.ID='$codigouser'
					LIMIT 1";  
$exec_queryparametros = mysqli_query($conn,$queryparametros);
$registros = mysqli_fetch_assoc($exec_queryparametros);

$empresapadrao = $registros["EMPPADRAO"];
//---Fim Consulta Atualizada---------------
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title>Parametros do Sistema :: Break | Controle de Ponto Eletrônico</title>
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
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
	
	<style type="text/css">
		
	</style>
	
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
	<div class="container col-md-9" >
		<div id="botoes">
		<a class="btn btn-info" href="index.php" style="margin-left: 12px">Inicio</a>
		<a class="btn btn-dark" href="index.php" style="margin-left: 12px">Voltar</a>
		<a href="sair_admin.php" class="btn btn-secondary btn-sm" style="margin-left: 12px" >Sair</a>	
		<div>
	
		<div class="relatorio imprimir">
			<span style="text-align: center">
				<h3><?=$registrosparametroempresa["NM_EMPRESA"]?></h3>
				<h2>Parâmetros do Sistema</h2>
			</span>
			<hr>
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
			
			<div id ="result" style="display:<?=$display?>;position:relative;width:500px"><?=$msg_result?></div>
								
			<form id="formparametros" method="post" action="parametros.php" name="formparametros">	
								
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="inputGroup-sizing-default">Máximo de Pessoas em Pausa:</span>
					</div>
					<input type="number" class="col-sm-2 col-form-label" style="width:200px;"
					name="vmaxpessoas" id ="vmaxpessoas" value = "<?=$registros['QTD_MAX_PAUSA']?>" 
					placeholder="Quantidade" title="Digite a quantidade máxima de pessoas em pausa." required>
				</div>
				
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="inputGroup-sizing-default">Tempo de  Verificação (Min.):</span>
					</div>
					<input type="number" name="vminutos" id="vminutos" placeholder="Minutos" value = "<?=$registros['MINUTOS']?>" class="col-sm-2 col-form-label" required>
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="inputGroup-sizing-default">Tempo Min. Entre Pausas(Min.):</span>
					</div>
					<input type="number" name="tmp_min_pause" id="tmp_min_pause" placeholder="Tempo Minimo Entre Pausas" value = "<?=$registros['TMP_MIN_PAUSE']?>" class="col-sm-2 col-form-label" required>
				</div>
				
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<label class="input-group-text" for="Empresa Padrao">Empresa Padrão</label>
					</div>
					<select name="vempresapadrao" class="col-sm-3 col-form-label" id="vempresapadrao">
						<?php while($registroempresa = mysqli_fetch_assoc($exec_queryempresas)){?>
							
								<option value="<?=$registroempresa["EMPRESA_ID"]?>" 
									<?php if($registroempresa["EMPRESA_ID"] == $empresapadrao) echo "selected";?>>
									<?=$registroempresa["NM_EMPRESA"]?>
								</option>							
						<?php } ?>
					</select>
				</div>

				<input type="hidden" name="codeparameter" value="<?=$registros['PARAMETRO_ID']?>">
				<button type="submit" name="saveParameter" value = "sucess" class="btn btn-primary">Salvar Alterações</button>
			</form>
			
			
		</div><!-- div parametros -->
	</div><!-- div container -->
	</body>
</html>

<!--https://forum.imasters.com.br/topic/447752-resolvido%C2%A0jquery-esconder-div-depois-de-um-certo-tempo/
//https://www.youtube.com/watch?v=kdPjP1nyQxk
//https://www.youtube.com/watch?v=wVl_iK4Dmo4 Como enviar formulários em Ajax e jQuery-->
