<?php 
//Código do menu para validar o acesso
$codigoMenu = 4;
require_once('verifica_session.php');
require('../class/mobile-class.php');

//Recuperando o código do usuário logado
$codigouser = $_SESSION['USER_ID'];

$msg_semregistros = '<tr>
						<td colspan="7">
							<div class="alert alert-danger" role="alert">
								Nenhum registro encontrado..
							</div>
						</td>
					</tr>';

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
						U.ID='$codigouser'";  
$exec_queryparametros = mysqli_query($conn,$queryparametros);
$registro_parametro = mysqli_fetch_assoc($exec_queryparametros);

$empresapadrao = $registro_parametro["EMPPADRAO"];
//----------FIM CONSULTA PARAMETROS--------------

//---------SELEÇÃO SITUACAO DO USUARIO-------------
if(!isset($_SESSION['situacaoCadastroUsuarios'])) {
	$situacaoCadatro = $_SESSION["situacaoCadastroUsuarios"] = 1;
	
}elseif (isset($_POST['situacaoCadastroUsuarios']) && ($_SESSION["situacaoCadastroUsuarios"] !=$_POST['situacaoCadastroUsuarios'])){
	$situacaoCadatro = $_SESSION['situacaoCadastroUsuarios'] = isset($_POST['situacaoCadastroUsuarios']) ? $_POST['situacaoCadastroUsuarios'] : "null";
}else{
	$situacaoCadatro = $_SESSION["situacaoCadastroUsuarios"];
}	
//--------FIM SELECAO SIT USUARIO------------------

//------------INICIO PAGINAÇÃO---------------------

if(!isset($_SESSION["maxPaginaListaUsers"]) && isset($_POST["maxPaginaListaUsers"])){
	$_SESSION["maxPaginaListaUsers"] = $_POST["maxPaginaListaUsers"];
	
}elseif(isset($_POST["maxPaginaListaUsers"]) && ($_POST["maxPaginaListaUsers"] != $_SESSION["maxPaginaListaUsers"])){
	$_SESSION["maxPaginaListaUsers"] = $_POST["maxPaginaListaUsers"];
	$_GET["page"] = 1 ;
}

//Definir o número de itens por página
$itensPorPagina = (isset($_SESSION["maxPaginaListaUsers"]) && $_SESSION["maxPaginaListaUsers"] != "") ? $_SESSION["maxPaginaListaUsers"]: 20;

//pegar a página atual
$paginaAtual = (!isset($_GET["page"]) || $_GET["page"] <= 0) ? 1 : intval($_GET["page"]);

//De onde irá iniciar cada pagina-----------------------------
$inicioExibir = ($itensPorPagina * $paginaAtual) - $itensPorPagina;

//------------FINAL PAGINAÇÃO-------------------------

//----------INICIO CONSULTA PARA POVOAR TABELA-----
$querytotal = "SELECT * FROM USERS WHERE USER_ATIVO = ".$situacaoCadatro." AND ID != 1";

$exec_query_total = mysqli_query($conn,$querytotal);
$totalNumRows = mysqli_num_rows($exec_query_total);
$totalPaginas = ceil($totalNumRows/$itensPorPagina);

$query = $querytotal." ORDER BY NOME ASC LIMIT $inicioExibir,$itensPorPagina";  
	
$exec_query = mysqli_query($conn, $query);
$total = mysqli_num_rows($exec_query);
$registros = mysqli_fetch_assoc($exec_query);
//----FIM CONSULTA POVOAR TABELA--------------------------

//CLASSE DO BOTÃO QUE IRÁ HABILIAR O BOTÃO DE DESATIVAR OU ATIVAR FUNCIONARIO
if ($_SESSION["situacaoCadastroUsuarios"] ==1) {
	$class_btn = "btn-desabled";
	$acao = 'desabled';
	
}elseif($_SESSION["situacaoCadastroUsuarios"] ==0){
	$class_btn = "btn-active";
	$acao = 'active';
}	
?>


<!DOCTYPE html>
<html>
<head>
	<title>Cadastro de Usuários :: Break | Controle de Ponto Eletrônico</title>

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
			<form class="form-inline" method="POST" action="lista_usuarios.php">
				
				<select name="situacaoCadastroUsuarios" class="custom-select my-1 mr-sm-2" id="situacaoCadastroUsuarios" onChange='submit();'>
					<option value="1" <?php if ($_SESSION["situacaoCadastroUsuarios"] == 1) echo "selected"; ?>>Usuários Ativos </option>
					<option value="0" <?php if ($_SESSION["situacaoCadastroUsuarios"] == 0) echo "selected"; ?>>Usuários Inativos</option>
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
				<h3><?=$registro_parametro["NM_EMPRESA"]?></h3>
				<h4>Cadastro de Usuários
					<?php if ($_SESSION["situacaoCadastroUsuarios"]==0)echo " Desativados"; ?>
				</h4>
				
			</center>
			
			<div class="divsubmenu">
				<div class = "btn_add_profile no-print">
					<a href="cadastrar_usuario.php">
						<img src="../images/btn_add_profile.png">
						Cadastrar
					</a>
				</div>
								
				<div class="select_maxPagina form-check form-check-inline">
					<form method='post' action='#' id="FormMaxPagina">
						<select name="maxPaginaListaUsers" class="custom-select custom-select-sm" id="maxPaginaListaUsers" onChange='submit();'>
							<option value="2" <?php if(isset($_SESSION["maxPaginaListaUsers"]) && $_SESSION["maxPaginaListaUsers"]==2) echo "selected";?>>2 Registros</option>
							<option value="5" <?php if(isset($_SESSION["maxPaginaListaUsers"]) && $_SESSION["maxPaginaListaUsers"]==5) echo "selected";?>>5 Registros</option>
							<option value="10" <?php if(isset($_SESSION["maxPaginaListaUsers"]) && $_SESSION["maxPaginaListaUsers"]==10) echo "selected";?>>10 Registros</option>
							<option value="20" <?php if(isset($_SESSION["maxPaginaListaUsers"]) && $_SESSION["maxPaginaListaUsers"]==20){echo "selected";}elseif(!isset($_SESSION["maxPaginaListaUsers"])){echo "selected";} ?>>20 Registros</option>
							<option value="30" <?php if(isset($_SESSION["maxPaginaListaUsers"]) && $_SESSION["maxPaginaListaUsers"]==30) echo "selected";?>>30 Registros</option>
							<option value="40" <?php if(isset($_SESSION["maxPaginaListaUsers"]) && $_SESSION["maxPaginaListaUsers"]==40) echo "selected";?>>40 Registros</option>
							<option value="50" <?php if(isset($_SESSION["maxPaginaListaUsers"]) && $_SESSION["maxPaginaListaUsers"]==50) echo "selected";?>>50 Registros</option>
							<option value="100"<?php if(isset($_SESSION["maxPaginaListaUsers"]) && $_SESSION["maxPaginaListaUsers"]==100) echo "selected";?>>100 Registros</option>
						</select>
					</form>
				</div>
			</div>
			
			<hr>
			
			<div class="form-group input-group no-print">
				<input name="q" id="q" placeholder="Pesquisar Usuários" type="text" class="input-search" onkeyup="busca();">
				<input type="hidden" name="pagina_search" id="pagina_search" value="usuarios">
			</div>
			
			<div id="resultado" style="overflow:auto;"></div>
			
			<div id="tabInclude">
				<?php require_once "include/usuarios.php";
					require_once("../include/pagination.php");?>
			</div>	

		</div>
		</div>
	
	</body>
</html>

<!--https://www.devmedia.com.br/quicksearch-e-bootstrap-adicione-buscas-em-paginas-web/37629-->