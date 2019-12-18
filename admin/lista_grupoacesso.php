<?php 
//Código do menu para validar o acesso
$codigoMenu = 8;
require_once('verifica_session.php');
require('../class/mobile-class.php');
	
$msg_semregistros = '<tr>
						<td colspan="7">
							<div class="alert alert-danger" role="alert">
								Nenhum registro encontrado, tente novamente..
							</div>
						</td>
					</tr>';


//------------INICIO PAGINAÇÃO---------------------

if(!isset($_SESSION["maxPaginaListaGroupAccess"]) && isset($_POST["maxPaginaListaGroupAccess"])){
	$_SESSION["maxPaginaListaGroupAccess"] = $_POST["maxPaginaListaGroupAccess"];
	
}elseif(isset($_POST["maxPaginaListaGroupAccess"]) && ($_POST["maxPaginaListaGroupAccess"] != $_SESSION["maxPaginaListaGroupAccess"])){
	$_SESSION["maxPaginaListaGroupAccess"] = $_POST["maxPaginaListaGroupAccess"];
	$_GET["page"] = 1 ;
}

//Definir o número de itens por página
$itensPorPagina = (isset($_SESSION["maxPaginaListaGroupAccess"]) && $_SESSION["maxPaginaListaGroupAccess"] != "") ? $_SESSION["maxPaginaListaGroupAccess"]: 20;

//pegar a página atual
$paginaAtual = (!isset($_GET["page"]) || $_GET["page"] <= 0) ? 1 : intval($_GET["page"]);

//De onde irá iniciar cada pagina-----------------------------
$inicioExibir = ($itensPorPagina * $paginaAtual) - $itensPorPagina;

//------------FINAL PAGINAÇÃO-------------------------

//----------INICIO CONSULTA PARA POVOAR TABELA-----
$querytotal = "SELECT 
					GRUPOACESSO_ID, NM_GRUPOACESSO, GRUPOACESSO_ATIVO
				FROM
					GRUPOACESSO
				WHERE 
					GRUPOACESSO_ATIVO = 1 
				AND 
					GRUPOACESSO_ID != 1";

$exec_query_total = mysqli_query($conn,$querytotal);
$totalNumRows = mysqli_num_rows($exec_query_total);
$totalPaginas = ceil($totalNumRows/$itensPorPagina);

$query = "".$querytotal." ORDER BY NM_GRUPOACESSO ASC LIMIT $inicioExibir,$itensPorPagina";  
	
$exec_query = mysqli_query($conn, $query);
$total = mysqli_num_rows($exec_query);
$registros = mysqli_fetch_assoc($exec_query);
//----FIM CONSULTA POVOAR TABELA--------------------------
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Cadastro de Grupos de Acesso :: Break | Controle de Ponto Eletrônico</title>

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
			<form class="form-inline" method="POST" action="lista_grupoacesso.php">
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
				<h4>Cadastro de Grupos de Acesso</h4>
				
			</center>
			
			<div class="divsubmenu">
				<div class = "btn_add_profile no-print">
					<a href="cadastrar_grupoacesso.php">
						<img src="../images/btn_add_funcao.png" title="Cadastrar Grupo de Acesso">
						Cadastrar
					</a>
				</div>
								
				<div class="select_maxPagina form-check form-check-inline">
					<form method='post' action='#' id="FormMaxPagina">						
						<select name="maxPaginaListaGroupAccess" class="custom-select custom-select-sm" id="maxPaginaListaGroupAccess" onChange='submit();'>
							<option value="5" <?php if(isset($_SESSION["maxPaginaListaGroupAccess"]) && $_SESSION["maxPaginaListaGroupAccess"]==5) echo "selected";?>>5 Registros</option>
							<option value="10" <?php if(isset($_SESSION["maxPaginaListaGroupAccess"]) && $_SESSION["maxPaginaListaGroupAccess"]==10) echo "selected";?>>10 Registros</option>
							<option value="20" <?php if(isset($_SESSION["maxPaginaListaGroupAccess"]) && $_SESSION["maxPaginaListaGroupAccess"]==20){echo "selected";}elseif(!isset($_SESSION["maxPaginaListaGroupAccess"])){echo "selected";} ?>>20 Registros</option>
							<option value="30" <?php if(isset($_SESSION["maxPaginaListaGroupAccess"]) && $_SESSION["maxPaginaListaGroupAccess"]==30) echo "selected";?>>30 Registros</option>
							<option value="40" <?php if(isset($_SESSION["maxPaginaListaGroupAccess"]) && $_SESSION["maxPaginaListaGroupAccess"]==40) echo "selected";?>>40 Registros</option>
							<option value="50" <?php if(isset($_SESSION["maxPaginaListaGroupAccess"]) && $_SESSION["maxPaginaListaGroupAccess"]==50) echo "selected";?>>50 Registros</option>
							<option value="100"<?php if(isset($_SESSION["maxPaginaListaGroupAccess"]) && $_SESSION["maxPaginaListaGroupAccess"]==100) echo "selected";?>>100 Registros</option>
						</select>
					</form>
				</div>
			</div>
			
			<hr>
			
			<div class="form-group input-group no-print">
				<input name="q" id="q" placeholder="Pesquisar Grupo" type="text" class="input-search" onkeyup="busca();">
				<input type="hidden" name="pagina_search" id="pagina_search" value="grupoacesso">
			</div>
			
			<div id="resultado" style="overflow:auto;"></div>
			
			<div id="tabInclude">
				<?php 
				require_once ("include/grupoacesso.php");
				require_once("../include/pagination.php");
				?>
			</div>	

		</div>
		</div>	
</body>
</html>