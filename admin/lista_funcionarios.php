<?php 
//Código do menu para validar o acesso
$codigoMenu = 3;
require_once('verifica_session.php');
require('../class/mobile-class.php');

//Recuperando o código do usuário logado
$codigouser = $_SESSION['USER_ID'];

$msg_semregistros = '<tr>
						<td colspan="7">
							<div class="alert alert-danger" role="alert">
								Nenhum registro encontrado, tente novamente...
							</div>
						</td>
					</tr>';
$empSelect = "Geral - Todas as Empresas";
//---------CONSULTA PARAMETROS--------------
$queryparametros = "SELECT 
						EMPPADRAO,NM_EMPRESA 
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
$registro_parametro = mysqli_fetch_assoc($exec_queryparametros);

$empresapadrao = (isset($registro_parametro["EMPPADRAO"]))? $registro_parametro["EMPPADRAO"]:0 ;
//----------FIM CONSULTA PARAMETROS--------------

//-------CONSULTA EMPRESAS--------------------------
$queryempresas = "SELECT EMPRESA_ID, NM_EMPRESA, EMPRESA_ATIVA FROM EMPRESAS";  
$exec_queryempresas = mysqli_query($conn,$queryempresas);
//-------FIM CONSULTA EMPRESAS----------------------

//-------CONSULTA TIPOS DE CONTRATO--------------------------
$querytipocontrato = "SELECT TIPOCONTRATO_ID, NM_TIPOCONTRATO,TIPOCONTRATO_ATIVO FROM TIPOCONTRATO";  
$exec_querytipocontrato = mysqli_query($conn,$querytipocontrato);
//-------FIM CONSULTA TIPOS DE CONTRATO----------------------

//-------CONSULTA FUNCOES--------------------------
$queryfuncao = "SELECT FUNCAO_ID, NM_FUNCAO, FUNCAO_ATIVA FROM FUNCOES";  
$exec_queryfuncao = mysqli_query($conn,$queryfuncao);
//-------FIM CONSULTA TIPOS DE CONTRATO----------------------

//--------SELEÇÃO DA EMPRESA-----------------------
if(!isset($_SESSION["select_empresa"])){
	$empresa = $_SESSION["select_empresa"] = $empresapadrao;
	
}elseif((isset($_POST['select_empresa']) && $_SESSION["select_empresa"] !=$_POST['select_empresa'])){
	$empresa = $_SESSION["select_empresa"] = isset($_POST['select_empresa']) ? $_POST['select_empresa'] : "null";
}else{
	$empresa = $_SESSION["select_empresa"];
}
//----------FIM SELEÇÃO DA EMPRESA---------------------

//--------SELEÇÃO DO TIPO DE CONTRATO-----------------------
if(!isset($_SESSION["select_tipocontrato"])){
	$tipocontrato = $_SESSION["select_tipocontrato"] = "all";
	
}elseif((isset($_POST['select_tipocontrato']) && $_SESSION["select_tipocontrato"] !=$_POST['select_tipocontrato'])){
	$tipocontrato = $_SESSION["select_tipocontrato"] = isset($_POST['select_tipocontrato']) ? $_POST['select_tipocontrato'] : "null";
}else{
	$tipocontrato = $_SESSION["select_tipocontrato"];
}
//----------FIM SELEÇÃO DO TIPO DE COONTRATO---------------------

//--------SELEÇÃO DA FUNÇÃO-----------------------
if(!isset($_SESSION["select_funcao"])){
	$funcao = $_SESSION["select_funcao"] = "all";
	
}elseif((isset($_POST['select_funcao']) && $_SESSION["select_funcao"] !=$_POST['select_funcao'])){
	$funcao = $_SESSION["select_funcao"] = isset($_POST['select_funcao']) ? $_POST['select_funcao'] : "null";
}else{
	$funcao = $_SESSION["select_funcao"];
}
//----------FIM SELEÇÃO DO TIPO DE COONTRATO---------------------

//---------SELEÇÃO SITUACAO DO FUNCIONARIO-------------
if(!isset($_SESSION['situacaoCadastroFuncionarios'])) {
	$situacaoCadatro = $_SESSION["situacaoCadastroFuncionarios"] = 1;
	
}elseif (isset($_POST['situacaoCadastroFuncionarios']) && ($_SESSION["situacaoCadastroFuncionarios"] !=$_POST['situacaoCadastroFuncionarios'])){
	$situacaoCadatro = $_SESSION['situacaoCadastroFuncionarios'] = isset($_POST['situacaoCadastroFuncionarios']) ? $_POST['situacaoCadastroFuncionarios'] : "null";
}else{
	$situacaoCadatro = $_SESSION["situacaoCadastroFuncionarios"];
}

//--------FIM SELECAO SIT FUNCIONARIO------------------

//------------INICIO PAGINAÇÃO---------------------

if(!isset($_SESSION["maxPaginaListaFunc"]) && isset($_POST["maxPaginaListaFunc"])){
	$_SESSION["maxPaginaListaFunc"] = $_POST["maxPaginaListaFunc"];
	
}elseif(isset($_POST["maxPaginaListaFunc"]) && ($_POST["maxPaginaListaFunc"] != $_SESSION["maxPaginaListaFunc"])){
	$_SESSION["maxPaginaListaFunc"] = $_POST["maxPaginaListaFunc"];
	$_GET["page"] = 1 ;
}

//Definir o número de itens por página
$itensPorPagina = (isset($_SESSION["maxPaginaListaFunc"]) && $_SESSION["maxPaginaListaFunc"] != "") ? $_SESSION["maxPaginaListaFunc"]: 20;

//pegar a página atual
$paginaAtual = (!isset($_GET["page"]) || $_GET["page"] <= 0) ? 1 : intval($_GET["page"]);

//De onde irá iniciar cada pagina-----------------------------
$inicioExibir = ($itensPorPagina * $paginaAtual) - $itensPorPagina;

//------------FINAL PAGINAÇÃO-------------------------

//----------INICIO CONSULTA PARA POVOAR TABELA-----
//Caso o usuário selecione a empresa o sistema irá especificar com a variavel abaixo
$selecaoEmpresa = ($empresa != "all") ?  "WHERE EMPRESA_ID = $empresa" : "";

//Caso o usuário selecione o tipo de contrato o sistema irá especificar com a variavel abaixo
$selecaoTipocontrato = ($tipocontrato != "all") ?  "WHERE TIPOCONTRATO_ID = $tipocontrato" : "";

//Caso o usuário selecione a função o sistema irá especificar com a variavel abaixo
$selecaofuncao = ($funcao != "all") ?  "WHERE FUNCAO_ID = $funcao" : "";

$querytotal = "SELECT FC.NM_FUNCAO, EMP.NM_EMPRESA,TC.NM_TIPOCONTRATO, FUNCIONARIO.* FROM FUNCIONARIO 
INNER JOIN FUNCOES AS FC ON FC.FUNCAO_ID = FUNCIONARIO.FUNCAO
INNER JOIN EMPRESAS AS EMP ON EMP.EMPRESA_ID = FUNCIONARIO.EMPRESA
INNER JOIN TIPOCONTRATO AS TC ON TC.TIPOCONTRATO_ID = FUNCIONARIO.TIPO_FUNCIONARIO
WHERE FUNCIONARIO.FUNC_ATIVO = ".$situacaoCadatro." AND FUNCIONARIO.EMPRESA 
IN (SELECT EMPRESA_ID FROM EMPRESAS $selecaoEmpresa) AND FUNCIONARIO.TIPO_FUNCIONARIO
IN (SELECT TIPOCONTRATO_ID FROM TIPOCONTRATO $selecaoTipocontrato) AND FUNCIONARIO.FUNCAO
IN (SELECT FUNCAO_ID FROM FUNCOES $selecaofuncao)";
				
$query = "".$querytotal." ORDER BY FUNCIONARIO.NOME ASC LIMIT $inicioExibir,$itensPorPagina"; 		

$exec_query_total = mysqli_query($conn,$querytotal);
$totalNumRows = mysqli_num_rows($exec_query_total);

$totalPaginas = ceil($totalNumRows/$itensPorPagina);

$exec_query = mysqli_query($conn,$query);
$registros = mysqli_fetch_assoc($exec_query);
//----FIM CONSULTA POVOAR TABELA--------------------------

//CLASSE DO BOTÃO QUE IRÁ HABILIAR O BOTÃO DE DESATIVAR OU ATIVAR FUNCIONARIO
if ($_SESSION["situacaoCadastroFuncionarios"] ==1) {
	$class_btn = "btn-desabled";
	$acao = 'desabled';
	
}elseif($_SESSION["situacaoCadastroFuncionarios"] ==0){
	$class_btn = "btn-active";
	$acao = 'active';
}	

?>

<!DOCTYPE html>
<html>
<head>
	<title>Cadastro de Funcionários :: Break | Controle de Ponto Eletrônico</title>
	
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
	<link rel="stylesheet" href="../css/bootstrap.min.css">		
	<link href="../css/estilo.css" rel="stylesheet">
	
	<script src="../js/jquery-3.3.1.min.js"></script>
	<script src="../js/bootstrap.min.js"></script> 
	<script src="../js/script.js"></script>
	<script src="../js/search.js"></script>

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

		<div class="form">
			<form class="form-inline" method="POST" action="lista_funcionarios.php">
			
				<select name="select_empresa" class="custom-select my-1 mr-sm-2" id="select_empresa" onChange='submit();'>
							<option value="all"> - Todas Empresas- </option>		
					<?php while ($registro_empresa = mysqli_fetch_array($exec_queryempresas)){ ?>
							<option value = "<?=$registro_empresa["EMPRESA_ID"]?>"
								<?php if($registro_empresa['EMPRESA_ATIVA']==0) echo 'class="table-dark"';?>
								<?php if($empresa == $registro_empresa["EMPRESA_ID"]){
										echo "selected"; 
										$empSelect = $registro_empresa["NM_EMPRESA"];
									}
								?>>
								<?=$registro_empresa["NM_EMPRESA"]?>
							</option>
					<?php } ?>						
				</select>
				<select name="select_tipocontrato" class="custom-select my-1 mr-sm-2" id="select_tipocontrato" onChange='submit();'>			
							<option value="all">- Todos os Tipos - </option>		
					<?php while ($registro_tipocontrato = mysqli_fetch_array($exec_querytipocontrato)){ ?>
							<option value = "<?=$registro_tipocontrato["TIPOCONTRATO_ID"]?>"
								<?php if($registro_tipocontrato['TIPOCONTRATO_ATIVO']==0) echo 'class="table-dark"';?>
								<?php if($tipocontrato == $registro_tipocontrato["TIPOCONTRATO_ID"]){
										echo "selected"; 
										$tipocontratoSelect = $registro_tipocontrato["NM_TIPOCONTRATO"];
									}
								?>>
								<?=$registro_tipocontrato["NM_TIPOCONTRATO"]?>
							</option>
					<?php } ?>						
				</select>
				
				<select name="select_funcao" class="custom-select my-1 mr-sm-2" id="select_funcao" onChange='submit();'>
							<option value="all">- Todas as Funções - </option>		
					<?php while ($registro_funcao = mysqli_fetch_array($exec_queryfuncao)){ ?>
							<option value = "<?=$registro_funcao["FUNCAO_ID"]?>"
								<?php if($registro_funcao['FUNCAO_ATIVA']==0) echo 'class="table-dark"';?>
								<?php if($funcao == $registro_funcao["FUNCAO_ID"]){
										echo "selected"; 
										$funcaoSelect = $registro_funcao["NM_FUNCAO"];
									}
								?>>
								<?=$registro_funcao["NM_FUNCAO"]?>
							</option>
					<?php } ?>						
				</select>
				
				<select name="situacaoCadastroFuncionarios" class="custom-select my-1 mr-sm-2" id="situacaoCadastroFuncionarios" onChange='submit();'>
					<option value="1" <?php if ($_SESSION["situacaoCadastroFuncionarios"] ==1) echo "selected"; ?>>Funcionarios Ativos </option>
					<option value="0" <?php if ($_SESSION["situacaoCadastroFuncionarios"] ==0) echo "selected"; ?>>Funcionarios Desativados</option>
				</select>
			
					<div>
						<button type="submit" onClick="window.print()" class="btn btn-info my-1">Imprimir</button></a>&nbsp;&nbsp;&nbsp;
						<a class="btn btn-info" href="index.php" style="margin-left: 12px">Inicio</a>
						<a class="btn btn-dark" href="index.php" style="margin-left: 12px">Voltar</a>
						<a href="sair_admin.php" class="btn btn-secondary btn-sm" style="margin-left: 12px" >Sair</a>
				</div>
								
			</form>
		</div>

		<div class="relatorio imprimir table-responsive">
			<span style="text-align:center">
				<h3><?=$empSelect?></h3>
				<h4>Cadastro de Funcionários
					<?php if ($_SESSION["situacaoCadastroFuncionarios"]==0)echo " Desativados"; ?>
				</h4>
			</span>
			
			<div class="divsubmenu">
				<div class = "btn_add_profile no-print">
					<a href="cadastrar_funcionario.php">
						<img src="../images/btn_add_profile.png">
						Cadastrar
					</a>
				</div>

				<div class="select_maxPagina form-check form-check-inline">
					<form method='post' action='#' id="FormMaxPagina">
						<select name="maxPaginaListaFunc" class="custom-select custom-select-sm" id="maxPaginaListaFunc" onChange='submit();'>
							<option value="10" <?php if(isset($_SESSION["maxPaginaListaFunc"]) && $_SESSION["maxPaginaListaFunc"]==10) echo "selected";?>>10 Registros</option>
							<option value="20" <?php if(isset($_SESSION["maxPaginaListaFunc"]) && $_SESSION["maxPaginaListaFunc"]==20){echo "selected";}elseif(!isset($_SESSION["maxPaginaListaFunc"])){echo "selected";} ?>>20 Registros</option>
							<option value="30" <?php if(isset($_SESSION["maxPaginaListaFunc"]) && $_SESSION["maxPaginaListaFunc"]==30) echo "selected";?>>30 Registros</option>
							<option value="40" <?php if(isset($_SESSION["maxPaginaListaFunc"]) && $_SESSION["maxPaginaListaFunc"]==40) echo "selected";?>>40 Registros</option>
							<option value="50" <?php if(isset($_SESSION["maxPaginaListaFunc"]) && $_SESSION["maxPaginaListaFunc"]==50) echo "selected";?>>50 Registros</option>
							<option value="100"<?php if(isset($_SESSION["maxPaginaListaFunc"]) && $_SESSION["maxPaginaListaFunc"]==100) echo "selected";?>>100 Registros</option>
						</select>
					</form>
				</div>
			</div>
			
			<hr>
			
			<div class="form-group input-group no-print">
				<input name="q" id="q" placeholder="Pesquisar Funcionário" type="search" class="input-search" onkeyup="busca();">
				<input type="hidden" name="pagina_search" id="pagina_search" value="funcionarios">
			</div>
			
			<div id="resultado" style="overflow:auto;"></div>
			
			<div id="tabInclude">
				<?php require_once "include/funcionarios.php";
					require_once("../include/pagination.php");?>
			</div>		
			
			
		</div>
		</div>
</body>
</html>
