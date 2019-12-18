<?php 
require_once('verifica_session_front.php');
include 'class/mobile-class.php';

$codigo = $_SESSION['CODIGO_FUNC'];
$mes_atual = date('m');
$ano_atual = date("Y");

$query_func = "	SELECT 
					EMP.EMPRESA_ID,EMP.NM_EMPRESA, FUNC.* 
				FROM 
					FUNCIONARIO AS FUNC
				INNER JOIN 
					EMPRESAS AS EMP ON EMP.EMPRESA_ID = FUNC.EMPRESA
				WHERE 
					FUNC.CODIGO = '$codigo'
				LIMIT 1";  
$exec_query_func = mysqli_query($conn,$query_func);
$total_func = mysqli_num_rows($exec_query_func);
$registro_func = mysqli_fetch_assoc($exec_query_func);
$empresa = $registro_func['EMPRESA'];
mysqli_close($conn);
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
		<link rel="icon" sizes="192x192" href="images/favicons/icon-192x192.png">
		<link rel="apple-touch-icon" sizes="152x152" href="images/favicons/apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="120x120" href="images/favicons/apple-touch-icon-120x120.png">
		
		<!-- Principal CSS do Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/estilo.css" rel="stylesheet">
		
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script type="text/javascript" src="js/jquery-1.12.4.min.js"></script>
			
			<link rel="stylesheet" href=".css/bootstrap-3.3.7.min.css">
			<link rel="stylesheet" href="css/bootstrap-theme-3.3.7.min.css">
			<script src="js/bootstrap-3.3.7.min.js"></script>			
		<![endif]-->

		<script>
			$(document).ready(function() { 

				(function(){
					var d = new Date();
					<?php if(!isset($_SESSION['select_mes'])) {?>
						var mes =  d.getMonth()+1; 
					<?php }else{?>
						var mes =  <?php echo $_SESSION['select_mes']; ?>;
					<?php } ?>
					var ano = d.getFullYear(); 
					var maxPaginaGeraRelatorioemployee = $("option:selected","#maxPaginaGeraRelatorioemployee").val();

					var vurl = $(location).attr('href');
					var ind = vurl.indexOf('page=');
					pag = vurl.substr(ind + 5);

					$.ajax({
						url:'consultas/crelatorio.php',
						type:'POST',
						dataType:'html',
						data:{vmes: mes, vano: ano,vmaxPaginaGeraRelatorioemployee: maxPaginaGeraRelatorioemployee, page:pag},
						success: function(data){
							$('#retorno-consulta').html(data);
						},
					});
					
				})();

				$('#select_ano').change(function() {
					$("#select_mes").val(-1);
				});

				$('#select_mes').change(function() {

					var mes = $("option:selected", "#select_mes").val();
					var ano = $("option:selected","#select_ano").val();
					var maxPaginaGeraRelatorioemployee = $("option:selected","#maxPaginaGeraRelatorioemployee").val();
					
					if(mes >0){
						$.ajax({
							url:'consultas/crelatorio.php',
							type:'POST',
							dataType:'html',
							data:{vmes: mes, vano: ano, vmaxPaginaGeraRelatorioemployee: maxPaginaGeraRelatorioemployee},
							success: function(data){
								$('#retorno-consulta').html(data);
							},
						});	
					}				
				});
			});
		</script>	

	</head>

	<body>
		<?php include("include/browserdeprecated.html"); ?>	
		<div class="container col-md-9" >
			<div class="form">
				<form class="form-inline" method="POST" action="relatorio.php">
					<select name="select_ano" class="custom-select my-1 mr-sm-2" id="select_ano"> 	
						<?php 
						for($ano_cont=2018;$ano_cont<=$ano_atual;$ano_cont++){

							if($ano_atual==$ano_cont){
								echo "<option value='$ano_cont' selected>$ano_cont</option>";
							}else{
								echo "<option value='$ano_cont'>$ano_cont</option>";
							}							
						}
						?>
					</select>

					<select class="custom-select my-1 mr-sm-2" id="select_mes" name="select_mes">
						<?php if(!isset($_SESSION['select_mes'])){ ?>
							<option value="-1">--Selecione um mês--</option>
							<option value="01" <?php if($mes_atual==1) echo "selected";?>>Janeiro</option>
							<option value="02" <?php if($mes_atual==2) echo "selected";?>>Fevereiro</option>
							<option value="03" <?php if($mes_atual==3) echo "selected";?>>Março</option>
							<option value="04" <?php if($mes_atual==4) echo "selected";?>>Abril</option>
							<option value="05" <?php if($mes_atual==5) echo "selected";?>>Maio</option>
							<option value="06" <?php if($mes_atual==6) echo "selected";?>>Junho</option>
							<option value="07" <?php if($mes_atual==7) echo "selected";?>>Julho</option>
							<option value="08" <?php if($mes_atual==8) echo "selected";?>>Agosto</option>
							<option value="09" <?php if($mes_atual==9) echo "selected";?>>Setembro</option>
							<option value="10" <?php if($mes_atual==10) echo "selected";?>>Outubro</option>
							<option value="11" <?php if($mes_atual==11) echo "selected";?>>Novembro</option>
							<option value="12" <?php if($mes_atual==12) echo "selected";?>>Dezembro</option>
						<?php }else{ ?>
							<option value="-1">--Selecione um mês--</option>
							<option value="01" <?php if($_SESSION['select_mes']==1) echo "selected";?>>Janeiro</option>
							<option value="02" <?php if($_SESSION['select_mes']==2) echo "selected";?>>Fevereiro</option>
							<option value="03" <?php if($_SESSION['select_mes']==3) echo "selected";?>>Março</option>
							<option value="04" <?php if($_SESSION['select_mes']==4) echo "selected";?>>Abril</option>
							<option value="05" <?php if($_SESSION['select_mes']==5) echo "selected";?>>Maio</option>
							<option value="06" <?php if($_SESSION['select_mes']==6) echo "selected";?>>Junho</option>
							<option value="07" <?php if($_SESSION['select_mes']==7) echo "selected";?>>Julho</option>
							<option value="08" <?php if($_SESSION['select_mes']==8) echo "selected";?>>Agosto</option>
							<option value="09" <?php if($_SESSION['select_mes']==9) echo "selected";?>>Setembro</option>
							<option value="10" <?php if($_SESSION['select_mes']==10) echo "selected";?>>Outubro</option>
							<option value="11" <?php if($_SESSION['select_mes']==11) echo "selected";?>>Novembro</option>
							<option value="12" <?php if($_SESSION['select_mes']==12) echo "selected";?>>Dezembro</option>
					
						<?php } ?>
					</select>
					<div>
						<button type="submit" onClick="window.print()" class="btn btn-info my-1">Imprimir</button></a>&nbsp;&nbsp;&nbsp;
						<a class="btn btn-dark" href="index.php" style="margin-left: 12px">Voltar</a>
						<a href="sair.php" class="btn btn-secondary btn-sm" style="margin-left: 12px" >Sair</a>
					</div>
				</form>
			</div>

			<div class="relatorio imprimir table-responsive">
				<div style="margin: 0 auto;text-align:center">
					<h3><?=$registro_func['NM_EMPRESA']?></h3>
					<h4>Relatorio de Pausas/Intervalos</h4>					
				</div>
				
				<div class="divsubmenu">
					
				</div>	
			</div>
			<hr>
			<br>
			<div id = "retorno-consulta"></div>
		</div>
	</body>
</html>
<?php 
	mysqli_free_result($exec_query_func); 	
?>