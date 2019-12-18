<?php 
//Código do menu para validar o acesso
$codigoMenu = 6;
require_once('verifica_session.php');
require('../class/mobile-class.php');

$msg_semregistros = '<tr>
						<td colspan="7">
							<div class="alert alert-danger" role="alert">
								Nenhum registro encontrado, tente novamente...
							</div>
						</td>
					</tr>';

//---------SELEÇÃO SITUACAO DA EMPRESA-------------
if(!isset($_SESSION['situacaoCadastroEmpresas'])) {
	$situacaoCadatro = $_SESSION["situacaoCadastroEmpresas"] = 1;
	
}elseif (isset($_POST['situacaoCadastroEmpresas']) && ($_SESSION["situacaoCadastroEmpresas"] !=$_POST['situacaoCadastroEmpresas'])){
	$situacaoCadatro = $_SESSION['situacaoCadastroEmpresas'] = isset($_POST['situacaoCadastroEmpresas']) ? $_POST['situacaoCadastroEmpresas'] : "null";
}else{
	$situacaoCadatro = $_SESSION["situacaoCadastroEmpresas"];
}	
//--------FIM SELECAO SIT EMPRESA------------------

//------------INICIO PAGINAÇÃO---------------------

if(!isset($_SESSION["maxPaginaListaCompany"]) && isset($_POST["maxPaginaListaCompany"])){
	$_SESSION["maxPaginaListaCompany"] = $_POST["maxPaginaListaCompany"];
	
}elseif(isset($_POST["maxPaginaListaCompany"]) && ($_POST["maxPaginaListaCompany"] != $_SESSION["maxPaginaListaCompany"])){
	$_SESSION["maxPaginaListaCompany"] = $_POST["maxPaginaListaCompany"];
	$_GET["page"] = 1 ;
}

//Definir o número de itens por página
$itensPorPagina = (isset($_SESSION["maxPaginaListaCompany"]) && $_SESSION["maxPaginaListaCompany"] != "") ? $_SESSION["maxPaginaListaCompany"]: 20;

//pegar a página atual
$paginaAtual = (!isset($_GET["page"]) || $_GET["page"] <= 0) ? 1 : intval($_GET["page"]);

//De onde irá iniciar cada pagina-----------------------------
$inicioExibir = ($itensPorPagina * $paginaAtual) - $itensPorPagina;

//------------FINAL PAGINAÇÃO-------------------------

//----------INICIO CONSULTA PARA POVOAR TABELA-----
$querytotal = "SELECT * FROM EMPRESAS WHERE EMPRESA_ATIVA = ".$situacaoCadatro."";

$exec_query_total = mysqli_query($conn,$querytotal);
$totalNumRows = mysqli_num_rows($exec_query_total);
$totalPaginas = ceil($totalNumRows/$itensPorPagina);
	
$query = "".$querytotal." ORDER BY NM_EMPRESA ASC LIMIT $inicioExibir,$itensPorPagina";  
	
$exec_query = mysqli_query($conn, $query);
$total = mysqli_num_rows($exec_query);
$registros = mysqli_fetch_assoc($exec_query);
//----FIM CONSULTA POVOAR TABELA--------------------------

//CLASSE DO BOTÃO QUE IRÁ HABILIAR O BOTÃO DE DESATIVAR OU ATIVAR FUNCIONARIO
if ($_SESSION["situacaoCadastroEmpresas"] ==1) {
	$class_btn = "btn-desabled";
	$acao = 'desabled';
	
}elseif($_SESSION["situacaoCadastroEmpresas"] ==0){
	$class_btn = "btn-active";
	$acao = 'active';
}	
?>


<!DOCTYPE html>
<html>
<head>
	<title>Cadastro de Empresas :: Break | Controle de Ponto Eletrônico</title>

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
		<form class="form-inline" method="POST" action="lista_empresas.php">
			
			<select name="situacaoCadastroEmpresas" class="custom-select my-1 mr-sm-2" id="situacaoCadastroEmpresas" onChange='submit();'>
				<option value="1" <?php if ($_SESSION["situacaoCadastroEmpresas"] == 1) echo "selected"; ?>>Empresas Ativas </option>
				<option value="0" <?php if ($_SESSION["situacaoCadastroEmpresas"] == 0) echo "selected"; ?>>Empresas Inativas</option>
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
		<center>
			<h3><?//=$registro_parametro["NM_EMPRESA"]?></h3>
			<h4>Cadastro de Empresas
				<?php if ($_SESSION["situacaoCadastroEmpresas"]==0)echo " Desativadas"; ?>
			</h4>
			
		</center>
		
		<div class="divsubmenu">
			<div class = "btn_add_profile no-print">
				<a href="cadastrar_empresa.php">
					<img src="../images/btn_add_funcao.png" title="Cadastrar Empresa">
					Cadastrar
				</a>
			</div>
							
			<div class="select_maxPagina form-check form-check-inline">
				<form method='post' action='#' id="FormMaxPagina">						
					<select name="maxPaginaListaCompany" class="custom-select custom-select-sm" id="maxPaginaListaCompany" onChange='submit();'>
						<option value="2" <?php if(isset($_SESSION["maxPaginaListaCompany"]) && $_SESSION["maxPaginaListaCompany"]==2) echo "selected";?>>2 Registros</option>
						<option value="5" <?php if(isset($_SESSION["maxPaginaListaCompany"]) && $_SESSION["maxPaginaListaCompany"]==5) echo "selected";?>>5 Registros</option>
						<option value="10" <?php if(isset($_SESSION["maxPaginaListaCompany"]) && $_SESSION["maxPaginaListaCompany"]==10) echo "selected";?>>10 Registros</option>
						<option value="20" <?php if(isset($_SESSION["maxPaginaListaCompany"]) && $_SESSION["maxPaginaListaCompany"]==20){echo "selected";}elseif(!isset($_SESSION["maxPaginaListaCompany"])){echo "selected";} ?>>20 Registros</option>
						<option value="30" <?php if(isset($_SESSION["maxPaginaListaCompany"]) && $_SESSION["maxPaginaListaCompany"]==30) echo "selected";?>>30 Registros</option>
						<option value="40" <?php if(isset($_SESSION["maxPaginaListaCompany"]) && $_SESSION["maxPaginaListaCompany"]==40) echo "selected";?>>40 Registros</option>
						<option value="50" <?php if(isset($_SESSION["maxPaginaListaCompany"]) && $_SESSION["maxPaginaListaCompany"]==50) echo "selected";?>>50 Registros</option>
						<option value="100"<?php if(isset($_SESSION["maxPaginaListaCompany"]) && $_SESSION["maxPaginaListaCompany"]==100) echo "selected";?>>100 Registros</option>
					</select>
				</form>
			</div>
		</div>
		
		<hr>
		
		<div class="form-group input-group no-print">
			<input name="q" id="q" placeholder="Pesquisar Empresas" type="text" class="input-search" onkeyup="busca();">
			<input type="hidden" name="pagina_search" id="pagina_search" value="empresas">
		</div>
		
		<div id="resultado" style="overflow:auto;"></div>
		
		<div id="tabInclude">
			<?php require_once "include/empresas.php";
				require_once("../include/pagination.php");?>
		</div>	

	</div>
	</div>
	
</body>
</html>
<!--https://www.devmedia.com.br/quicksearch-e-bootstrap-adicione-buscas-em-paginas-web/37629-->
