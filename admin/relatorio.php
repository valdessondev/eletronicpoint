<?php 
//Código do menu para validar o acesso
$codigoMenu = 1;
require_once('verifica_session.php');
require('../class/mobile-class.php');

//Recuperando o código do usuário logado
$codigouser = $_SESSION['USER_ID'];
	
//------------INICIO PAGINAÇÃO---------------------	
if(!isset($_SESSION["maxPaginaRelatorioAdm"]) && isset($_POST["maxPaginaRelatorioAdm"])){
	$_SESSION["maxPaginaRelatorioAdm"] = $_POST["maxPaginaRelatorioAdm"];
	
}elseif(isset($_POST["maxPaginaRelatorioAdm"]) && ($_POST["maxPaginaRelatorioAdm"] != $_SESSION["maxPaginaRelatorioAdm"])){
	$_SESSION["maxPaginaRelatorioAdm"] = $_POST["maxPaginaRelatorioAdm"];
	$_GET["page"] = 1 ;
}
		
//Definir o número de itens por página
$itensPorPagina = (isset($_SESSION["maxPaginaRelatorioAdm"]) && $_SESSION["maxPaginaRelatorioAdm"] != "") ? $_SESSION["maxPaginaRelatorioAdm"]: 31;

//pegar a página atual
$paginaAtual = (!isset($_GET["page"]) || $_GET["page"] <= 0) ? 1 : intval($_GET["page"]);

//De onde irá iniciar cada pagina-----------------------------
$inicioExibir = ($itensPorPagina * $paginaAtual) - $itensPorPagina;

//FINAL PAGINAÇÃO------------------------------------

$msg_semregistros = '<tr>
						<td colspan="8">
							<div class="alert alert-danger" role="alert">
								Nenhum registro encontrado, tente novamente..
							</div>
						</td>
					</tr>';
$msgRegraNegocio = "";
					
$ano_atual = date('Y');
$mes_atual = date('m');	
$empSelect = "Geral - Todas as Empresas";
$totalPaginas = 0;
$totalNumRows = 0;
$ordenacao = "ORDER BY REGISTROS.ID ASC LIMIT $inicioExibir,$itensPorPagina";

//---------CONSULTA PARAMETROS--------------		
$queryparametros = "SELECT 
						P.EMPPADRAO 
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

//-------CONSULTA EMPRESAS--------------------------
$queryempresas = "SELECT EMPRESA_ID, NM_EMPRESA FROM EMPRESAS WHERE EMPRESA_ATIVA = 1";  
$exec_queryempresas = mysqli_query($conn,$queryempresas);
//-------FIM CONSULTA EMPRESAS----------------------

//-----------INICIO SESSION PARA GRAVAR A SELEÇÃO---------------------
if(!isset($_SESSION["ano"]) || !isset($_SESSION["select_mes"]) || !isset($_SESSION["select_func"]) ||
 !isset($_SESSION["select_empresa"])){
	 
	$ano = $_SESSION["ano"] = isset($_POST['select_ano']) ? $_POST['select_ano'] : $ano_atual;
	$mes = $_SESSION["select_mes"] = isset($_POST['select_mes']) ? $_POST['select_mes'] : $mes_atual;
	$funcionario = $_SESSION["select_func"] = isset($_POST['select_func']) ? $_POST['select_func'] : "null";
	$empresa = $_SESSION["select_empresa"] = isset($_POST['select_empresa']) ? $_POST['select_empresa'] : $empresapadrao;
	
}elseif((isset($_POST['select_empresa']) && $_SESSION["select_empresa"] != $_POST['select_empresa']) || (isset($_POST['select_ano']) && $_SESSION["ano"] !=$_POST['select_ano']) 
		|| (isset($_POST['select_mes']) && $_SESSION["select_mes"] != $_POST['select_mes']) || (isset($_POST['select_func']) && $_SESSION["select_func"] != $_POST['select_func'])){
		
	$ano = $_SESSION["ano"] = isset($_POST['select_ano']) ? $_POST['select_ano'] : "null";
	$mes = $_SESSION["select_mes"] = isset($_POST['select_mes']) ? $_POST['select_mes'] : "null";
	$funcionario = $_SESSION["select_func"] = isset($_POST['select_func']) ? $_POST['select_func'] : "null";
	$empresa = $_SESSION["select_empresa"] = isset($_POST['select_empresa']) ? $_POST['select_empresa'] : "null";
	
}else{
	$ano = $_SESSION["ano"];
	$mes = $_SESSION["select_mes"];
	$funcionario = $_SESSION["select_func"];
	$empresa = $_SESSION["select_empresa"];
}
//--------FIM SESSION PARA GRAVAR A SELECAO-------------------


//---REALIZANDO VERIFICAÇÕES E CONSULTAS PARA POVOAR A TABELA----				
if ($ano == "null" && $mes == "null" && $funcionario == "null" && $empresa=="null") {
	$msgRegraNegocio = '<div class="alert alert-danger" role="alert">
							Selecione ao menos o ANO e a EMPRESA e tente novamente!
							</div>';						

}elseif ($ano == "null") {	
	$msgRegraNegocio = '<div class="alert alert-danger" role="alert">
						Selecione TAMBÉM o ANO e tente novamente!
						</div>';						

}elseif($empresa == "all" && $ano != "null" && $mes != "null" && $funcionario == "null"){
	
	$querytotal = "SELECT EMPRESAS.NM_EMPRESA, REGISTROS.*, FUNCIONARIO.NOME FROM REGISTROS
	INNER JOIN FUNCIONARIO ON REGISTROS.CODIGO = FUNCIONARIO.CODIGO
	INNER JOIN EMPRESAS ON EMPRESAS.EMPRESA_ID = FUNCIONARIO.EMPRESA
	WHERE YEAR(DATA) = '$ano' AND MONTH(DATA) = '$mes' 
	AND FUNCIONARIO.FUNC_ATIVO = 1";						
	
	$query = "".$querytotal." ".$ordenacao."";  
	
}elseif($empresa == "all" && $ano != "null" && $mes == "null" && $funcionario == "null"){
	
	$querytotal = "SELECT EMPRESAS.NM_EMPRESA, REGISTROS.*, FUNCIONARIO.NOME FROM REGISTROS
	INNER JOIN FUNCIONARIO ON REGISTROS.CODIGO = FUNCIONARIO.CODIGO
	INNER JOIN EMPRESAS ON EMPRESAS.EMPRESA_ID = FUNCIONARIO.EMPRESA
	WHERE YEAR(DATA) = '$ano' AND FUNCIONARIO.FUNC_ATIVO = 1";						
	
	$query = "".$querytotal." ".$ordenacao."";
	
}elseif ($ano != "null" && $empresa!= "null" && $mes == "null" && $funcionario == "null") {
	
	$querytotal = "SELECT * FROM REGISTROS
	INNER JOIN FUNCIONARIO ON REGISTROS.CODIGO = FUNCIONARIO.CODIGO
	WHERE YEAR(DATA) = '$ano' AND EMPRESA = '$empresa' AND FUNCIONARIO.FUNC_ATIVO = 1";
	
	$query = "".$querytotal." ".$ordenacao."";

} elseif ($ano != "null" && $empresa!= "null" && $mes != "null" && $funcionario == "null") {
	
	$querytotal = "SELECT * FROM REGISTROS INNER JOIN FUNCIONARIO ON
	REGISTROS.CODIGO = FUNCIONARIO.CODIGO WHERE YEAR(DATA) = '$ano'
	AND MONTH(DATA) = '$mes' AND EMPRESA = '$empresa' AND FUNCIONARIO.FUNC_ATIVO = 1";
	
	$query = "".$querytotal." ".$ordenacao."";

} elseif ($ano != "null" && $empresa!= "null" && $mes != "null" && $funcionario != "null") {

	$querytotal = "SELECT * FROM REGISTROS INNER JOIN FUNCIONARIO ON
	REGISTROS.CODIGO = FUNCIONARIO.CODIGO WHERE YEAR(DATA) = '$ano'
	AND MONTH(DATA) = '$mes' AND FUNCIONARIO.CODIGO = '$funcionario'
	AND EMPRESA = '$empresa' AND FUNCIONARIO.FUNC_ATIVO = 1";  
	
	$query = "".$querytotal." ".$ordenacao."";

}elseif ($ano != "null" && $empresa!= "null" && $mes == "null" && $funcionario != "null") {
							
	$querytotal = "SELECT * FROM REGISTROS INNER JOIN FUNCIONARIO ON
	REGISTROS.CODIGO = FUNCIONARIO.CODIGO WHERE YEAR(DATA) = '$ano' 
	AND EMPRESA = '$empresa' AND FUNCIONARIO.CODIGO = '$funcionario'
	AND FUNCIONARIO.FUNC_ATIVO = 1"; 
	
	$query = "".$querytotal." ".$ordenacao."";

}else {
	$msgRegraNegocio =  '<div class="alert alert-danger" role="alert">
							Ocorreu um erro, entre em contato com o adminstrador!
							</div>';

} 
//----FIM VERIFICACOES E CONSULTAS PARA POVOAR TABELA--//

//------CONSULTA FUNCIONARIOS PARA POVOAR O OPTION---
if($empresa == "all"){
	$query_Func = "SELECT 
						CODIGO, NOME 
					FROM 
						FUNCIONARIO 
					WHERE 
						FUNC_ATIVO = 1";  
	
}else{
	$query_Func = "SELECT 
						CODIGO, NOME 
					FROM 
						FUNCIONARIO 
					WHERE 
						EMPRESA = '$empresa' AND FUNC_ATIVO = 1";
}
$exec_queryFunc = mysqli_query($conn,$query_Func);	
//----FIM CONSULTA FUNCIONARIOS PARA POVOAR O OPTION
?>


<!DOCTYPE html>
<html>
<head>
	<title>Relatório :: Break | Controle de Ponto Eletrônico</title>

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
	<!--[if lte IE 9]>
		<script type="text/javascript" src="../js/jquery-1.12.4.min.js"></script>		
		<link rel="stylesheet" href="../css/bootstrap-3.3.7.min.css">
		<link rel="stylesheet" href="../css/bootstrap-theme-3.3.7.min.css">
		<script src="../js/bootstrap-3.3.7.min.js"></script>			
	<![endif]-->
</head>

<body>
	<?php include("include/browserdeprecated.html"); ?>		
	<div class="container col-md-9" >

		<div class="form">
			<form class="form-inline" method="POST" action="relatorio.php">
			
			<select name="select_empresa" class="custom-select my-1 mr-sm-2" id="select_empresa" onChange='submit();'>			<option value="all"> - Todas - </option>	
				<?php while ($registro_empresa = mysqli_fetch_array($exec_queryempresas)){ ?>
						<option value = "<?=$registro_empresa["EMPRESA_ID"]?>"
							<?php if($empresa == $registro_empresa["EMPRESA_ID"]){
									echo "selected"; 
									$empSelect = $registro_empresa["NM_EMPRESA"];
								}
							?>>
							<?=$registro_empresa["NM_EMPRESA"]?>
						</option>
				<?php } ?>						
			</select>


				<select name="select_ano" class="custom-select my-1 mr-sm-2" id="select_ano" onChange='submit();'>
				<option value="null">selecione o Ano</option>
				<?php 
				for($ano_cont=2018;$ano_cont<=$ano_atual;$ano_cont++){
					if($ano==$ano_cont){
						echo "<option value='$ano_cont' selected>$ano_cont</option>";
					}else{
						echo "<option value='$ano_cont'>$ano_cont</option>";
					}							
				}
				?>
				</select>
			
				<select name="select_mes" class="custom-select my-1 mr-sm-2" id="select_mes" onChange='submit();'>
					<option value="null">- Selecione um mês -</option>
					<option value="01" <?php if($mes==1) echo "selected";?>>Janeiro</option>
					<option value="02" <?php if($mes==2) echo "selected";?>>Fevereiro</option>
					<option value="03" <?php if($mes==3) echo "selected";?>>Março</option>
					<option value="04" <?php if($mes==4) echo "selected";?>>Abril</option>
					<option value="05" <?php if($mes==5) echo "selected";?>>Maio</option>
					<option value="06" <?php if($mes==6) echo "selected";?>>Junho</option>
					<option value="07" <?php if($mes==7) echo "selected";?>>Julho</option>
					<option value="08" <?php if($mes==8) echo "selected";?>>Agosto</option>
					<option value="09" <?php if($mes==9) echo "selected";?>>Setembro</option>
					<option value="10" <?php if($mes==10) echo "selected";?>>Outubro</option>
					<option value="11" <?php if($mes==11) echo "selected";?>>Novembro</option>
					<option value="12" <?php if($mes==12) echo "selected";?>>Dezembro</option>
				</select>
				
				<select name="select_func" class="custom-select my-1 mr-sm-2" id="select_func" onChange='submit();'>
					<option value="null">- Funcionário -</option>
					
					<?php					
					//Option dos funcionarios conforme a EMPRESA
					while($row_func = mysqli_fetch_assoc($exec_queryFunc)) {?>
						<option value="<?=$row_func['CODIGO']?>"
						<?php if($funcionario != null && $funcionario == $row_func['CODIGO']){echo "selected";}?>>
						<?=$row_func['NOME']?></option>;
					<?php }	?>
					
				</select>

					<a class="btn btn-info" href="index.php" style="margin-left: 12px">Inicio</a>
					<button type="submit" onClick="window.print()" class="btn btn-info my-1">Imprimir</button></a>&nbsp;&nbsp;&nbsp;
					<a class="btn btn-dark" href="index.php" style="margin-left: 12px">Voltar</a>
					<a href="sair_admin.php" class="btn btn-secondary btn-sm" style="margin-left: 12px" >Sair</a>
				
			</form>
		</div>


		<div class="relatorio imprimir table-responsive">
			<span style="text-align:center">
				<h3><?=$empSelect?></h3>
				<h4>Relatório de Pausas e Intervalos</h4>
			</span>
			
			<div class="divsubmenu">
				<div class="select_maxPagina form-check form-check-inline">
					<form method='post' action='#' id="FormMaxPagina">
						<select name="maxPaginaRelatorioAdm" class="custom-select custom-select-sm" id="maxPaginaRelatorioAdm" onChange='submit();'>
						<option value="" <?php if(!isset($_SESSION["maxPaginaRelatorioAdm"])) echo "selected";?>>Exibir por página</option>
						<option value="10" <?php if(isset($_SESSION["maxPaginaRelatorioAdm"]) && $_SESSION["maxPaginaRelatorioAdm"]==10) echo "selected";?>>10</option>
						<option value="20" <?php if(isset($_SESSION["maxPaginaRelatorioAdm"]) && $_SESSION["maxPaginaRelatorioAdm"]==20) echo "selected";?>>20</option>
						<option value="30" <?php if(isset($_SESSION["maxPaginaRelatorioAdm"]) && $_SESSION["maxPaginaRelatorioAdm"]==30) echo "selected";?>>30</option>
						<option value="31" <?php if(isset($_SESSION["maxPaginaRelatorioAdm"]) && $_SESSION["maxPaginaRelatorioAdm"]==31) echo "selected";?>>31</option>
						<option value="40" <?php if(isset($_SESSION["maxPaginaRelatorioAdm"]) && $_SESSION["maxPaginaRelatorioAdm"]==40) echo "selected";?>>40</option>
						<option value="50" <?php if(isset($_SESSION["maxPaginaRelatorioAdm"]) && $_SESSION["maxPaginaRelatorioAdm"]==50) echo "selected";?>>50</option>
						<option value="100"<?php if(isset($_SESSION["maxPaginaRelatorioAdm"]) && $_SESSION["maxPaginaRelatorioAdm"]==100) echo "selected";?>>100</option>
					</select>
					</form>
				</div>
			</div>
			
			<hr>
			<br>

			<table class="table table-bordered table-hover table-condensed">
				<thead class="thead-dark">
				<tr>						  
					<th scope="col" style="width:150px">Data</th>
					<th scope="col">Nome</th>
					
					<?php if($empresa == "all"){?>
					<th scope="col">EMPRESA</th>
					<?php } ?>
					
					<th scope="col">S.P1</th>
					<th scope="col">V.P1</th>
					<th scope="col">S.I</th>
					<th scope="col">V.I</th>
					<th scope="col">S.P2</th>
					<th scope="col">V.P2</th>
				</tr>
				</thead>
				<tbody><?php
					if(isset($querytotal) && isset($query)){
							$exec_query_total = mysqli_query($conn,$querytotal);
							$totalNumRows = mysqli_num_rows($exec_query_total);
							
							$exec_query = mysqli_query($conn,$query);
							$registros = mysqli_fetch_assoc($exec_query);
							
							$totalPaginas = ceil($totalNumRows/$itensPorPagina);
							
							if ($totalNumRows > 0) {
							do {?>
								<tr id="rel">
									<th id = "n" scope="row" style="width:150px"><?=date('d/m/Y', strtotime($registros['DATA'])); if($registros['ALTERADO']) echo "*";?></th>
									<td id = "n"><?=$registros['NOME']?></td>
									
									<?php if($empresa == "all"){?>
									<td id = "n"><?=$registros['NM_EMPRESA']?></td>
									<?php } ?>
									
									<td id = "<?=$registros['CODIGO']?>" data = "<?=$registros['DATA']?>" colum1 = "HORA_ENTRADA"><?=$registros['HORA_ENTRADA']?></td>
									<td id = "<?=$registros['CODIGO']?>" data = "<?=$registros['DATA']?>" colum1 = "HORA_SAIDA_INTERVALO"><?=$registros['HORA_SAIDA_INTERVALO']?></td>
									<td id = "<?=$registros['CODIGO']?>" data = "<?=$registros['DATA']?>" colum1 = "HORA_RETORNO_INTERVALO"><?=$registros['HORA_RETORNO_INTERVALO']?></td>
									<td id = "<?=$registros['CODIGO']?>" data = "<?=$registros['DATA']?>" colum1 = "HORA_SAIDA"><?=$registros['HORA_SAIDA']?></td>
									<td id = "<?=$registros['CODIGO']?>" data = "<?=$registros['DATA']?>" colum1 = "HORA_SAIDA_PAUSA"><?=$registros['HORA_SAIDA_PAUSA']?></td>
									<td id = "<?=$registros['CODIGO']?>" data = "<?=$registros['DATA']?>" colum1 = "HORA_VOLTA_PAUSA"><?=$registros['HORA_VOLTA_PAUSA']?></td>
								</tr>								
						<?php	} while ($registros = mysqli_fetch_assoc($exec_query));
							}else echo $msg_semregistros;
					}else{ echo $msgRegraNegocio; } ?>
				</tbody>
			</table>
			<?php include("../include/pagination.php");?>
		</div>
		</div>
</body>
</html>