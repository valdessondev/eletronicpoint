<?php
	require_once("../../config/conn.php");
	include '../../class/mobile-class.php';
	
	$get = strip_tags($_GET['q']);
	$situacaoCadatro = $_SESSION["situacaoCadastroFuncionarios"];
	
	$msg_semregistros = '<tr>
						<td colspan="7">
							<div class="alert alert-danger" role="alert">
							  Nenhum registro encontrado, tente novamente...
							</div>
						</td>
					</tr>';
	
	
	//Definir o número de itens por página
	$itensPorPagina = (isset($_SESSION["maxPaginaListaFunc"]) && $_SESSION["maxPaginaListaFunc"] != "") ? $_SESSION["maxPaginaListaFunc"]: 20;
	
	//pegar a página atual
	$paginaAtual = (!isset($_GET["page"]) || $_GET["page"] <= 0) ? 1 : intval($_GET["page"]);
	
	//De onde irá iniciar cada pagina-----------------------------
	$inicioExibir = ($itensPorPagina * $paginaAtual) - $itensPorPagina;	
					
	//----------INICIO CONSULTA PARA POVOAR TABELA-----
	$empresa = $_SESSION["select_empresa"];
	$tipocontrato = $_SESSION["select_tipocontrato"];
	$funcao = $_SESSION["select_funcao"];

	 //Caso o usuário selecione a empresa o sistema irá especificar com a variavel abaixo
	$selecaoEmpresa = ($empresa <> "all") ?  "WHERE EMPRESA_ID = $empresa" : "";
	
	//Caso o usuário selecione o tipo de contrato o sistema irá especificar com a variavel abaixo
	$selecaoTipocontrato = ($tipocontrato <> "all") ?  "WHERE TIPOCONTRATO_ID = $tipocontrato" : "";
	
	//Caso o usuário selecione a funcao o sistema irá especificar com a variavel abaixo
	$selecaoFuncao = ($funcao <> "all") ?  "WHERE FUNCAO_ID = $funcao" : "";
	
	$querytotal = "SELECT FC.NM_FUNCAO, EMP.NM_EMPRESA,TC.NM_TIPOCONTRATO,FUNCIONARIO.* FROM FUNCIONARIO 
	INNER JOIN FUNCOES AS FC ON FC.FUNCAO_ID = FUNCIONARIO.FUNCAO
	INNER JOIN EMPRESAS AS EMP ON EMP.EMPRESA_ID = FUNCIONARIO.EMPRESA
	INNER JOIN TIPOCONTRATO AS TC ON TC.TIPOCONTRATO_ID = FUNCIONARIO.TIPO_FUNCIONARIO
	WHERE FUNCIONARIO.FUNC_ATIVO = ".$situacaoCadatro." AND	FUNCIONARIO.EMPRESA	
	IN (SELECT EMPRESA_ID FROM EMPRESAS $selecaoEmpresa) AND FUNCIONARIO.TIPO_FUNCIONARIO
	IN (SELECT TIPOCONTRATO_ID FROM TIPOCONTRATO $selecaoTipocontrato)
	AND FUNCIONARIO.FUNCAO IN (SELECT FUNCAO_ID FROM FUNCOES $selecaoFuncao)
	AND (FUNCIONARIO.NOME LIKE '%".$get."%' OR FC.NM_FUNCAO LIKE '%".$get."%'
	OR FUNCIONARIO.CODIGO LIKE '%".$get."%' OR TC.NM_TIPOCONTRATO LIKE '%".$get."%')";
					
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
		require_once("../include/funcionarios.php"); 
		require_once("../../include/pagination.php"); ?>
	</body>
	</html>





