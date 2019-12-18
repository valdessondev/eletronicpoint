<?php
	require_once("../../config/conn.php");
	include '../../class/mobile-class.php';
	
	$get = strip_tags($_GET['q']);
	$situacaoCadatro = $_SESSION["situacaoCadastroPermission"];
	
	$msg_semregistros = '<tr>
						<td colspan="7">
							<div class="alert alert-danger" role="alert">
							  Nenhum registro encontrado, tente novamente...
							</div>
						</td>
					</tr>';
	
	//Definir o número de itens por página
	$itensPorPagina = (isset($_SESSION["maxPaginaListaPermission"]) && $_SESSION["maxPaginaListaPermission"] != "") ? $_SESSION["maxPaginaListaPermission"]: 20;
	
	//pegar a página atual
	$paginaAtual = (!isset($_GET["page"]) || $_GET["page"] <= 0) ? 1 : intval($_GET["page"]);
	
	//De onde irá iniciar cada pagina-----------------------------
	$inicioExibir = ($itensPorPagina * $paginaAtual) - $itensPorPagina;
	
	//------------FINAL PAGINAÇÃO-------------------------	
	
	//----------INICIO CONSULTA PARA POVOAR TABELA-----
	$querytotal = "SELECT * FROM PERMISSOES WHERE PERMISSOES.NM_PERMISSOES LIKE '%".$get."%' AND 
					PERMISSOES_ATIVA = '$situacaoCadatro'";
	
	$exec_query_total = mysqli_query($conn,$querytotal);
	$totalNumRows = mysqli_num_rows($exec_query_total);
	$totalPaginas = ceil($totalNumRows/$itensPorPagina);
						
	$query = "".$querytotal." ORDER BY NM_PERMISSOES ASC LIMIT $inicioExibir,$itensPorPagina";  
	 
	$exec_query = mysqli_query($conn, $query);
	$total = mysqli_num_rows($exec_query);
	$registros = mysqli_fetch_assoc($exec_query);
	//----FIM CONSULTA POVOAR TABELA--------------------------
	
	//CLASSE DO BOTÃO QUE IRÁ HABILIAR O BOTÃO DE DESATIVAR OU ATIVAR
	if ($_SESSION["situacaoCadastroPermission"] ==1) {
		$class_btn = "btn-desabled";
		$acao = 'desabled';
		
	}elseif($_SESSION["situacaoCadastroPermission"] ==0){
		$class_btn = "btn-active";
		$acao = 'active';
	}	
	?>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
		<!-- Principal CSS do Bootstrap -->
		<link rel="stylesheet" href="../../css/bootstrap.min.css">		
		<link href="../../css/estilo.css" rel="stylesheet">
		
		<script src="../../js/jquery-3.3.1.min.js"></script>
		<script src="../../js/bootstrap.min.js"></script> 
		<script src="../../js/script.js"></script>
		<script src="../../js/search.js"></script>
		
	</head>
		<body>
		<?php 
			require_once("../include/permissoes.php"); 
			require_once("../../include/pagination.php"); ?>
		</body>
	</html>





