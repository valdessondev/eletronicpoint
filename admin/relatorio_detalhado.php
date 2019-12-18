<?php 
//Código do menu para validar o acesso
$codigoMenu = 11;
//Verificando se a sessão é válida
require_once('verifica_session.php');
require('../class/mobile-class.php');

//Recuperando o código do usuário logado
$codigouser = $_SESSION['USER_ID'];

//Iniciando variáveis
$data_atual = date('d-m-Y');

$msg_semregistros = '<tr>
						<td colspan="8">
							<div class="alert alert-danger" role="alert">
								Nenhum registro encontrado, tente novamente..
							</div>
						</td>
					</tr>';
$msgRegraNegocio = "";

//------------INICIO PAGINAÇÃO---------------------	
if(!isset($_SESSION["maxPaginaRelatorioAdm2"]) && isset($_POST["maxPaginaRelatorioAdm2"])){
	$_SESSION["maxPaginaRelatorioAdm2"] = $_POST["maxPaginaRelatorioAdm2"];
	
}elseif(isset($_POST["maxPaginaRelatorioAdm2"]) && ($_POST["maxPaginaRelatorioAdm2"] != $_SESSION["maxPaginaRelatorioAdm2"])){
	$_SESSION["maxPaginaRelatorioAdm2"] = $_POST["maxPaginaRelatorioAdm2"];
}
		
//Definir o número de itens por página
$itensPorPagina = (isset($_SESSION["maxPaginaRelatorioAdm2"]) && $_SESSION["maxPaginaRelatorioAdm2"] != "") ? $_SESSION["maxPaginaRelatorioAdm2"]: 31;

//pegar a página atual
$paginaAtual = (!isset($_GET["page"]) || $_GET["page"] <= 0) ? 1 : intval($_GET["page"]);

//De onde irá iniciar cada pagina-----------------------------
$inicioExibir = ($itensPorPagina * $paginaAtual) - $itensPorPagina;

//FINAL PAGINAÇÃO------------------------------------

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

//------CONSULTA TIPO DE CONTRATO----------------------
$querytipocontrato = "SELECT TIPOCONTRATO_ID, NM_TIPOCONTRATO FROM TIPOCONTRATO WHERE TIPOCONTRATO_ATIVO = 1";  
$exec_tipocontrato = mysqli_query($conn,$querytipocontrato);
//------FIM CONSULTA TIPOS DE CONTRATO-----------------

//------CONSULTA FUNÇÕES----------------------
$queryfuncoes = "SELECT FUNCAO_ID, NM_FUNCAO FROM FUNCOES WHERE FUNCAO_ATIVA = 1";  
$exec_funcoes = mysqli_query($conn,$queryfuncoes);
//------FIM CONSULTA FUNÇÕES-----------------

//-----------INICIO SESSION PARA GRAVAR A SELEÇÃO---------------------
if(!isset($_SESSION["select_func2"]) || !isset($_SESSION["select_empresa2"]) || 
	!isset($_SESSION["data_inicial"]) || !isset($_SESSION["data_final"]) || 
	!isset($_SESSION["select_tipocontrato2"]) || !isset($_SESSION["select_tipocontrato2"]) ||
	!isset($_SESSION["select_atrasos"])){

	$funcionario = $_SESSION["select_func2"] = isset($_POST['select_func']) ? $_POST['select_func'] : 'null';
	$empresa = $_SESSION["select_empresa2"] = isset($_POST['select_empresa2']) ? $_POST['select_empresa2'] : $empresapadrao;
	$data_inicial = $_SESSION["data_inicial"] = isset($_POST['data_inicial']) ? $_POST['data_inicial'] : "01/".date('m/Y');
	$data_final = $_SESSION["data_final"] = isset($_POST['data_final']) ? $_POST['data_final'] : date('d/m/Y');
	$tipocontrato = $_SESSION["select_tipocontrato2"] = isset($_POST['select_tipocontrato2']) ? $_POST['select_tipocontrato2'] : 'null';
	$funcao = $_SESSION["select_funcao2"] = isset($_POST['select_funcao2']) ? $_POST['select_funcao2'] : 'null';
	$atrasos = $_SESSION["select_atrasos"] = isset($_POST['select_atrasos']) ? (int)$_POST['select_atrasos'] : 1;

}elseif((isset($_POST['select_empresa2']) && $_SESSION["select_empresa2"] != $_POST['select_empresa2']) ||
		(isset($_POST['select_func']) && $_SESSION["select_func2"] != $_POST['select_func']) ||
		(isset($_POST['data_inicial']) && $_SESSION["data_inicial"] != $_POST['data_inicial']) ||
		(isset($_POST['data_final']) && $_SESSION["data_final"] != $_POST['data_final']) ||
		(isset($_POST['select_tipocontrato2']) && $_SESSION["select_tipocontrato2"] != $_POST['select_tipocontrato2']) ||
		(isset($_POST['select_funcao2']) && $_SESSION["select_funcao2"] != $_POST['select_funcao2']) ||
		(isset($_POST['select_atrasos']) && $_SESSION["select_atrasos"] != $_POST['select_atrasos'])){
		
	$funcionario = $_SESSION["select_func2"] = isset($_POST['select_func']) ? $_POST['select_func'] : 'null';
	$empresa = $_SESSION["select_empresa2"] = isset($_POST['select_empresa2']) ? $_POST['select_empresa2'] : 'null';
	$data_inicial = $_SESSION["data_inicial"] = isset($_POST['data_inicial']) ? $_POST['data_inicial'] : 'null';
	$data_final = $_SESSION["data_final"] = isset($_POST['data_final']) ? $_POST['data_final'] : 'null';
	$tipocontrato = $_SESSION["select_tipocontrato2"] = isset($_POST['select_tipocontrato2']) ? $_POST['select_tipocontrato2'] : 'null';
	$funcao = $_SESSION["select_funcao2"] = isset($_POST['select_funcao2']) ? $_POST['select_funcao2'] : 'null';
	$atrasos = $_SESSION["select_atrasos"] = isset($_POST['select_atrasos']) ? (int)$_POST['select_atrasos'] : 1;

}else{
	$funcionario = $_SESSION["select_func2"];
	$empresa = $_SESSION["select_empresa2"];
	$tipocontrato = $_SESSION["select_tipocontrato2"];
	$data_inicial = $_SESSION["data_inicial"];
	$data_final = $_SESSION["data_final"];
	$funcao = $_SESSION["select_funcao2"];
	$atrasos = (int)$_SESSION["select_atrasos"];
}
//--------FIM SESSION PARA GRAVAR A SELECAO-------------------

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

//CONSULTA REGISTROS PARA POVOAR A TABELA
$query_registros = "SELECT 
						NOME, CODIGO, QTDREGISTROS,
						TIPOCONTRATO_ID, NM_TIPOCONTRATO, EMPRESA_ID, 
                        NM_EMPRESA, FUNCAO_ID, NM_FUNCAO,
						DATA, ALTERADO, SAIDA_1PAUSA,
						VOLTA_1PAUSA, DURACAO_1PAUSA, SAIDA_INTERVALOT6,
						VOLTA_INTERVALOT6, DURACAO_INTERVALOT6, SAIDA_2PAUSA,
						VOLTA_2PAUSA, DURACAO_2PAUSA, SAIDA_ALMOCO,
						VOLTA_ALMOCO, DURACAO_ALMOCO, SAIDA_INTERVALOT2,
						VOLTA_INTERVALOT2,DURACAO_INTERVALOT2
					FROM 
						VWRELATORIO_DETALHADO
					WHERE 
						DATA = '2019-11-10'";
$exec_queryRegistros = mysqli_query($conn,$query_registros);

//FIM CONSULTA REGISTROS PARA POVOAR A TABELA 
					
?>

<!DOCTYPE html>
<html>
<head>
	<title>Relatório Detalhado:: Break | Controle de Ponto Eletrônico</title>

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
	<link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="../css/estilo.css" rel="stylesheet" type="text/css" media="screen">
	
	<script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="../js/jquery.maskedinput.js"></script>
	<script type="text/javascript" src="../js/script.js"></script>
	<script src="../js/bootstrap.min.js"></script>

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lte IE 9]>
		<script type="text/javascript" src="../js/jquery-1.12.4.min.js"></script>		
		<link rel="stylesheet" href="../css/bootstrap-3.3.7.min.css">
		<link rel="stylesheet" href="../css/bootstrap-theme-3.3.7.min.css">
		<script src="../js/bootstrap-3.3.7.min.js"></script>			
	<![endif]-->

    <script>
        $(document).ready(function(){
			//Função para mostrar/ocultar DIV dos detalhes
			$(".details").click(function () {

				var code = $(this).attr("code");

				$("#detailscode"+code).toggle();

            });

			(function(){
				//Recupera nome da empresa selecionada
				var empresa = $("option:selected","#select_empresa2").text();
				//exibe o nome da empresa selecionada na pagina
				$('#empresa').html('<h3>'+empresa+'</h3>');
			})();

			//Filtro para a data
            $(".data").mask("99/99/9999", {
            completed: function() {
                console.log('complete')
                var value = $(this).val().split('/');
                var maximos = [31, 12, 2100];
                var novoValor = value.map(function(parcela, i) {
                    if (parseInt(parcela, 10) > maximos[i]) return maximos[i];
                    return parcela;
                });
                if (novoValor.toString() != value.toString()) $(this).val(novoValor.join('/')).focus();
                }
            });
        });
    </script>
</head>
<body>
	<?php include("include/browserdeprecated.html"); ?>				
	<div class="container col-md-9" >
	<!-- Menu com opções-->
		<div>		
			<a class="btn btn-info" href="index.php" style="margin-left: 12px">Inicio</a>
			<button type="submit" onClick="window.print()" class="btn btn-info my-1">Imprimir</button></a>&nbsp;&nbsp;&nbsp;
			<a class="btn btn-dark" href="index.php" style="margin-left: 12px">Voltar</a>
			<a href="sair_admin.php" class="btn btn-secondary btn-sm" style="margin-left: 12px" >Sair</a>
		</div>	
	
		<div class="form">
			<form class="form-inline" method="POST" action="relatorio_detalhado.php">	
				<!--Select para exibir as empresas-->
				<div class="col-auto my-1" >
					<label class="mr-sm-2" for="inlineFormCustomSelect">Empresa</label>		
					<select name="select_empresa2" class="custom-select my-1 mr-sm-2" id="select_empresa2" onChange='submit();'>			
						<option value="all"> - Todas - </option>	
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
				</div>
				<!--Data Inicial do Filtro-->
				<div class="col-auto my-1">
						<label class="mr-sm-2" for="inlineFormCustomSelect">Data Inicial</label>					
						<input class="form-control data" type="text" value="<?=$data_inicial?>" name = "data_inicial" id="data_inicial">					
				</div>
				<!--Data Final do Filtro-->
				<div class="col-auto my-1">
						<label class="mr-sm-2" for="inlineFormCustomSelect">Data Final</label>					
						<input class="form-control data" type="text" value="<?=$data_final?>" name ="data_final" id="data_final">					
				</div>

				<!-- Select para exibir os funcionários-->
				<div class="col-auto my-1">
					<label class="mr-sm-2" for="inlineFormCustomSelect">Funcionários</label>	
					<select name="select_func" class="custom-select my-1 mr-sm-2" id="select_func" onChange='submit();'>
						<option value="null"> - Todos - </option>					
						<?php					
						//Option dos funcionarios conforme a EMPRESA
						while($row_func = mysqli_fetch_assoc($exec_queryFunc)) {?>
							<option value="<?=$row_func['CODIGO']?>"
							<?php if($funcionario != null && $funcionario == $row_func['CODIGO']){echo "selected";}?>>
							<?=$row_func['NOME']?></option>;
						<?php }	?>					
					</select>
				</div>				
				<!--Select para exibir os tipos de contratos-->
				<div class="col-auto my-1" >
					<label class="mr-sm-2" for="inlineFormCustomSelect">Tipos de Contrato</label>
					<select name="select_tipocontrato2" class="custom-select my-1 mr-sm-2" id="select_tipocontrato2" onChange='submit();'>
						<option value="null"> - Todos - </option>					
						<?php					
						//Option dos tipos de contrato 
						while($row = mysqli_fetch_assoc($exec_tipocontrato)) {?>
							<option value="<?=$row['TIPOCONTRATO_ID']?>"
							<?php if($tipocontrato != null && $tipocontrato == $row['TIPOCONTRATO_ID']){echo "selected";}?>>
							<?=$row['NM_TIPOCONTRATO']?></option>;
						<?php }	?>					
					</select>
				</div>

				<!--Select para exibir as funcoes-->
				<div class="col-auto my-1" >
					<label class="mr-sm-2" for="inlineFormCustomSelect">Funções</label>
					<select name="select_funcao2" class="custom-select my-1 mr-sm-2" id="select_funcao2" onChange='submit();'>
						<option value="null"> - Todas - </option>					
						<?php					
						//Option das funções 
						while($row = mysqli_fetch_assoc($exec_funcoes)) {?>
							<option value="<?=$row['FUNCAO_ID']?>"
							<?php if($funcao != null && $funcao == $row['FUNCAO_ID']){echo "selected";}?>>
							<?=$row['NM_FUNCAO']?></option>;
						<?php }	?>					
					</select>
				</div>
				<!--Option para selecionar se deseja exibir também o que não estão atrasados-->
				<div class="col-auto my-1">
					<label class="mr-sm-2" for="inlineFormCustomSelect">Somente Atrasos</label>
					<select class="custom-select mr-sm-2" id="select_atrasos" name="select_atrasos" onChange='submit();'>
						<option value="1" <?php if($atrasos == 1) echo "selected";?>>SIM</option>
						<option value="2" <?php if($atrasos == 2) echo "selected";?>>NÃO</option>
					</select>
				</div>
				<!--Botão para exibir os resultados-->
				<div class="col-auto my-1">
					<button type="button" class="btn btn-primary btn-sm">Buscar</button>
				</div>
			</form>
		</div>
		<div class="relatorio imprimir table-responsive;" style="text-align:center">

			<div style="margin: 0 auto;text-align:center">
			<div id="empresa"></div>
			<h4>Relatório Detalhado</h4>
			</div>

			<div class="divsubmenu">
				<div>
					Expandir Tudo
				</div>
				<div class="select_maxPagina form-check form-check-inline">
					<form method='post' action='#' id="FormMaxPagina">
						<select name="maxPaginaRelatorioAdm2" class="custom-select custom-select-sm" id="maxPaginaRelatorioAdm2" onChange='submit();'>
						<option value="" <?php if(!isset($_SESSION["maxPaginaRelatorioAdm2"])) echo "selected";?>>Exibir por página</option>
						<option value="10" <?php if(isset($_SESSION["maxPaginaRelatorioAdm2"]) && $_SESSION["maxPaginaRelatorioAdm2"]==10) echo "selected";?>>10</option>
						<option value="20" <?php if(isset($_SESSION["maxPaginaRelatorioAdm2"]) && $_SESSION["maxPaginaRelatorioAdm2"]==20) echo "selected";?>>20</option>
						<option value="30" <?php if(isset($_SESSION["maxPaginaRelatorioAdm2"]) && $_SESSION["maxPaginaRelatorioAdm2"]==30) echo "selected";?>>30</option>
						<option value="31" <?php if(isset($_SESSION["maxPaginaRelatorioAdm2"]) && $_SESSION["maxPaginaRelatorioAdm2"]==31) echo "selected";?>>31</option>
						<option value="40" <?php if(isset($_SESSION["maxPaginaRelatorioAdm2"]) && $_SESSION["maxPaginaRelatorioAdm2"]==40) echo "selected";?>>40</option>
						<option value="50" <?php if(isset($_SESSION["maxPaginaRelatorioAdm2"]) && $_SESSION["maxPaginaRelatorioAdm2"]==50) echo "selected";?>>50</option>
						<option value="100"<?php if(isset($_SESSION["maxPaginaRelatorioAdm2"]) && $_SESSION["maxPaginaRelatorioAdm2"]==100) echo "selected";?>>100</option>
					</select>
					</form>
				</div>
			</div>
			
			<hr>
			<table class="table table-bordered table-hover table-condensed">
				<thead class="thead-dark">
				<tr>						  
					<th scope="col">Data</th>
					<th scope="col">Nome</th>
					<th scope="col">TIPO</th>
					<th scope="col">FUNCAO</th>					
					
					<?php if($empresa == "all"){?>
					<th scope="col">EMPRESA</th>
					<?php } ?>
					
					<th scope="col">D1P</th>
					<th scope="col">DI/A</th>
					<th scope="col">D2P</th>
					<th scope="col">>></th>
				</tr>
				</thead>
				<tbody>
					<?php while($registros = mysqli_fetch_assoc($exec_queryRegistros)){
						//Vai exibir as informações conforme a quantidade de pausas
							if($registros['QTDREGISTROS'] == 6){?>	

							<tr style="line-height: 1.2;">
								<th scope="row"><?=date('d/m/Y', strtotime($registros['DATA'])); if($registros['ALTERADO']) echo "*";?></th>
								<td><?=$registros['NOME']?></td>							
								<td><?=$registros['NM_TIPOCONTRATO']?></td>
								<td><?=$registros['NM_FUNCAO']?></td>

								<?php if($empresa == "all"){?>
									<td id = "n"><?=$registros['NM_EMPRESA']?></td>
								<?php } ?>

								<td><?=$registros['DURACAO_1PAUSA']?></td>
								<td><?=$registros['DURACAO_INTERVALOT6']?></td>
								<td><?=$registros['DURACAO_2PAUSA']?></td>
								<th code="<?=$registros['CODIGO']?>" class="details">
									<img src="images/icon-details.png" alt="Mais detalhes">
								</th>
							</tr>
							<tr class="contentDetails" id="detailscode<?=$registros['CODIGO']?>">
								<td colspan="8">
									<div class = "contentPause">
										<div class = "contentPausaDetailsOne">
											Saída 1º Pausa: <?=$registros['SAIDA_1PAUSA']?>
										</div>
										<div class = "contentPausaDetailsTwo">
											Volta 1º Pausa: <?=$registros['VOLTA_1PAUSA']?>
										</div>
									</div>
									<div class = "contentPause">
										<div class = "contentPausaDetailsOne">
											Saída Intervalo: <?=$registros['SAIDA_INTERVALOT6']?>
										</div>
										<div class = "contentPausaDetailsTwo">
											Volta Intervalo: <?=$registros['VOLTA_INTERVALOT6']?>
										</div>
									</div>
									<div class = "contentPause">
										<div class = "contentPausaDetailsOne">
											Saída 2º Pausa: <?=$registros['SAIDA_2PAUSA']?>
										</div>
										<div class = "contentPausaDetailsTwo">
											Volta 2º Pausa: <?=$registros['VOLTA_2PAUSA']?>
										</div>
									</div>						
								</td>
							</tr>
					
					<?php }elseif($registros['QTDREGISTROS'] == 4){ ?>
							
						<tr style="line-height: 1.2;">
								<th scope="row"><?=date('d/m/Y', strtotime($registros['DATA'])); if($registros['ALTERADO']) echo "*";?></th>
								<td><?=$registros['NOME']?></td>							
								<td><?=$registros['NM_TIPOCONTRATO']?></td>
								<td><?=$registros['NM_FUNCAO']?></td>

								<?php if($empresa == "all"){?>
									<td id = "n"><?=$registros['NM_EMPRESA']?></td>
								<?php } ?>

								<td> - </td>
								<td><?=$registros['DURACAO_ALMOCO']?></td>
								<td> - </td>
								<th code="<?=$registros['CODIGO']?>" class="details">
									<img src="images/icon-details.png" alt="Mais detalhes">
								</th>
							</tr>
							<tr class="contentDetails" id="detailscode<?=$registros['CODIGO']?>">
								<td colspan="8">		
									<div class = "contentPause">
										<div class = "contentPausaDetailsOne">
											Saída Almoço: <?=$registros['SAIDA_ALMOCO']?>
										</div>
										<div class = "contentPausaDetailsTwo">
											Volta Intervalo: <?=$registros['VOLTA_ALMOCO']?>
										</div>
									</div>						
								</td>
							</tr>
						<?php }elseif($registros['QTDREGISTROS'] == 2){ ?>
							<tr style="line-height: 1.2;">
								<th scope="row"><?=date('d/m/Y', strtotime($registros['DATA'])); if($registros['ALTERADO']) echo "*";?></th>
								<td><?=$registros['NOME']?></td>							
								<td><?=$registros['NM_TIPOCONTRATO']?></td>
								<td><?=$registros['NM_FUNCAO']?></td>

								<?php if($empresa == "all"){?>
									<td id = "n"><?=$registros['NM_EMPRESA']?></td>
								<?php } ?>

								<td> - </td>
								<td><?=$registros['DURACAO_INTERVALOT2']?></td>
								<td> - </td>
								<th code="<?=$registros['CODIGO']?>" class="details">
									<img src="images/icon-details.png" alt="Mais detalhes">
								</th>
							</tr>
							<tr class="contentDetails" id="detailscode<?=$registros['CODIGO']?>">
								<td colspan="8">		
									<div class = "contentPause">
										<div class = "contentPausaDetailsOne">
											Saída Intervalo: <?=$registros['SAIDA_INTERVALOT2']?>
										</div>
										<div class = "contentPausaDetailsTwo">
											Volta Intervalo: <?=$registros['VOLTA_INTERVALOT2']?>
										</div>
									</div>						
								</td>
							</tr>
						<?php }
						} ?>
				</tbody>
			</table>

		</div><!-- div relatorio -->		
	</div><!-- div container -->
</body>
</html>