<?php
	require_once("../../config/conn.php");
	include '../../class/mobile-class.php';
	
	$get = strip_tags($_GET['q']);
	$situacaoCadatro = $_SESSION["situacaoCadastroTipocontrato"];
	
	$msg_semregistros = '<tr>
						<td colspan="7">
							<div class="alert alert-danger" role="alert">
							  Nenhum registro encontrado, tente novamente...
							</div>
						</td>
					</tr>';
	
	//Definir o número de itens por página
	$itensPorPagina = (isset($_SESSION["maxPaginaListaTipocontrato"]) && $_SESSION["maxPaginaListaTipocontrato"] != "") ? $_SESSION["maxPaginaListaTipocontrato"]: 20;
	
	//pegar a página atual
	$paginaAtual = (!isset($_GET["page"]) || $_GET["page"] <= 0) ? 1 : intval($_GET["page"]);
	
	//De onde irá iniciar cada pagina-----------------------------
	$inicioExibir = ($itensPorPagina * $paginaAtual) - $itensPorPagina;
	
	//------------FINAL PAGINAÇÃO-------------------------	
	
	//----------INICIO CONSULTA PARA POVOAR TABELA-----
	$querytotal = "SELECT * FROM TIPOCONTRATO WHERE TIPOCONTRATO.NM_TIPOCONTRATO LIKE '%".$get."%' AND 
					TIPOCONTRATO_ATIVO = '$situacaoCadatro'";
	
	$exec_query_total = mysqli_query($conn,$querytotal);
	$totalNumRows = mysqli_num_rows($exec_query_total);
	$totalPaginas = ceil($totalNumRows/$itensPorPagina);
						
	$query = "".$querytotal." ORDER BY NM_TIPOCONTRATO ASC LIMIT $inicioExibir,$itensPorPagina";  
	 
	$exec_query = mysqli_query($conn, $query);
	$total = mysqli_num_rows($exec_query);
	$registros = mysqli_fetch_assoc($exec_query);
	//----FIM CONSULTA POVOAR TABELA--------------------------
	
	//CLASSE DO BOTÃO QUE IRÁ HABILIAR O BOTÃO DE DESATIVAR OU ATIVAR FUNCIONARIO
	if ($_SESSION["situacaoCadastroTipocontrato"] ==1) {
		$class_btn = "btn-desabled";
		$acao = 'desabled';
		
	}elseif($_SESSION["situacaoCadastroTipocontrato"] ==0){
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
				require_once( "../include/tipocontrato.php"); 
				require_once("../../include/pagination.php"); ?>
			</body>
	</html>





