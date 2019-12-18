<?php 
//Código do menu para validar o acesso
$codigoMenu = 6;
require_once('verifica_session.php');

	$display = "none";
	$msg_result = "";
	
	$codigo_empresa = isset($_POST["code_company"]) ? $_POST["code_company"] : "";
	
	$query_user = "SELECT * FROM EMPRESAS WHERE EMPRESA_ID = '$codigo_empresa'";  
	$exec_query = mysqli_query($conn, $query_user);
	$total = mysqli_num_rows($exec_query);
	$registros = mysqli_fetch_assoc($exec_query);
	
	if((isset($codigo_empresa)) && (!empty($codigo_empresa)) && $total > 0){
		
		if(isset($_POST["saveFormEditCompany"]) && $_POST['saveFormEditCompany'] == "sucess"){
			
			$codigo_empresa = $_POST["code_company"];
			$NM_EMPRESA = isset($_POST["description_company"]) ? $_POST["description_company"] : "";
			$company_ativo = $_POST["company_ativo"];
			
			$CadastroAlterado = 0;
				
				
		//-----------------Consulta as descrições de todas as empresas-----------------------
			$query_company = "SELECT NM_EMPRESA FROM EMPRESAS WHERE NM_EMPRESA = '$NM_EMPRESA'";  
			$exec_query_all = mysqli_query($conn, $query_company);
			$total_company = mysqli_num_rows($exec_query_all);
		//----------------------------------------------------------------------------------
			
			if(!isset($NM_EMPRESA) || empty($NM_EMPRESA)){
				$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br
				-> Preencha todos os campos.</div>";
				$display = "block";

			}elseif(!preg_match("/^[^\/\s]+[a-zA-ZÀ-ú\/\s0-9]{4,50}$/",$NM_EMPRESA)){
				$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>
				-> Digite o nome da empresa(Min 5 Max 50 letras e/ou numeros)</div>";
				$display = "block";
			
			}elseif($_POST["company_ativo"]!=1 && $registros["EMPRESA_ATIVA"]==0){
				$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>
				-> Empresa Desativada</div>";
				$display = "block";
				
			}elseif($NM_EMPRESA != $registros['NM_EMPRESA'] && $total_company > 0){
				$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>
				-> Esta empresa Já Existe</div>";
				$display = "block";
				
			}elseif($registros["NM_EMPRESA"] != $NM_EMPRESA || $registros["EMPRESA_ATIVA"]!= $company_ativo){			
				$editcompany = "UPDATE EMPRESAS SET NM_EMPRESA = '$NM_EMPRESA', EMPRESA_ATIVA = '$company_ativo' 
								,DT_ALTERACAO = CURRENT_TIMESTAMP WHERE EMPRESA_ID = '$codigo_empresa'";
				$executarEditCompany = mysqli_query($conn, $editcompany);

				if($executarEditCompany){
					
					$msg_result = "<div class='alert alert-success'>Salvo com sucesso.</div>";
					$display = "block";
				}
				$CadastroAlterado = 1;	
				
			}elseif($CadastroAlterado == 0){
				
				$msg_result = "<div class='alert alert-success'>Salvo com sucesso.
				<br> -> Nenhuma alteração realizada. </div>";
				$display = "block";
				
			}else {
				
				$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. Contate o administrador.</div>";
				$display = "block";		
			}
		}
		$query_company = "SELECT * FROM EMPRESAS WHERE EMPRESA_ID = '$codigo_empresa'";  
		$exec_query = mysqli_query($conn, $query_company);
		$total = mysqli_num_rows($exec_query);
		$registros = mysqli_fetch_assoc($exec_query);
		?>
		
		<!DOCTYPE html>
		<html>
			<head>
				<title>Editar Cadastro de Empresas :: Break | Controle de Ponto Eletrônico</title>
				
				<meta charset="utf-8">
				<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
				<meta name="description" content="">
				<meta name="author" content="">
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
				<meta http-equiv="x-ua-compatible" content="IE=edge, chrome=1">
				<link rel="icon" href="../favicon.ico">
				
				<!-- Principal CSS do Bootstrap -->
				<link href="../css/bootstrap.min.css" rel="stylesheet">
				<link href="../css/estilo.css" rel="stylesheet">
				
				<script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
				<script type="text/javascript" src="../js/script.js"></script>
				<script type="text/javascript" src="../js/bootstrap.min.js"></script>
				
				<script type="text/javascript">
					$(document).ready(function(){
						$('div#result').fadeIn(5000);
						//$('div#result').fadeOut(10000);
					});
				</script>

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
				<div class="container col-md-9" style="margin-top: 30px;">

				  <div class="col-md-4">
				  <a class="btn btn-info" href="index.php" style="margin-left: 12px">Inicio</a>
				  <a class="btn btn-dark" href="lista_empresas.php" style="margin-left: 12px">Voltar</a>
				  
					<a href="sair_admin.php" class="btn btn-secondary btn-sm" style="margin-left: 12px" >Sair</a>
					
					<h2>Edita Cadastro - Empresa</h2>	
					<br>
					
					<div id="InformacoesAlteracao">
						<div class="form-group h6">
							Cadastro:
							<?=date("d/m/Y H:i:s", strtotime($registros['DT_CADASTRO']))?><br>
						</div>
						<?php if ($registros['DT_ALTERACAO'] != null){?>
							<div class="form-group h6"> Alteração: <?=date("d/m/Y H:i:s", strtotime($registros['DT_ALTERACAO']))?></div>
						<?php } ?>
					</div>
					
					<?php if($registros["EMPRESA_ATIVA"]==0){ ?>
						<div class="aviso-proibido-alteracao alert alert-warning" role="alert">
							<div id="divimg-aviso-proibido-alteracao">
								<img src="../images/alert.png"/>
							</div>
							<div id="msg-aviso-proibido-alteracao">
								Não é permitido editar cadastro desativado<br>
								Ative-o antes.
							</div>
							<div class = "clear-both"></div>
						</div>
					<?php }?>
					
					<div id ="result" style="display:<?=$display?>;position:relative;"><?=$msg_result?></div>
				
					<form method="post" action="edita_empresa.php" id="FormEditCompany">
					
					  <div class="form-group">
						  
						<div class="form-group">
							<label for="Código da Empresa">Código:</label>
							<?=$registros['EMPRESA_ID']?>
						</div>

						  <div class="form-group">
							<label for="Descrição da Empresa">Descrição</label>
							<input type="text" name="description_company" class="form-control"
							 id="description_company" placeholder="Nome da empresa" 
							 value = "<?php if (isset($_POST['description_company']))echo $_POST['description_company']; else echo $registros['NM_EMPRESA'];?>"
							 minlength="5" maxlength= "50" pattern="^[^/\s]+[a-zA-ZÀ-ú\/\s0-9]{4,50}$" size="50" 
							 title="Digite o nome da empresa(Min 5 Max 50 letras e/ou numeros)" required>
							
							<input type="hidden" name="code_function" value = "<?=$registros['EMPRESA_ID']?>">
						  </div>	
						  
						  <div class="form-group">
							<label for="Situação do Cadastro">Situacao do Cadastro:</label>
							<select name="company_ativo" class="form-control" id="company_ativo" required>
							  
							  <option value="1" <?php if($registros['EMPRESA_ATIVA'] == 1) echo "selected" ?>>ATIVO</option>
							  <option value="0" <?php if($registros['EMPRESA_ATIVA'] == 0) echo "selected" ?>>DESATIVADO</option> 
							</select>
						 </div>
					  
						  <div class="form-group">
							<input type="hidden" name="code_company" value="<?=$registros['EMPRESA_ID']?>">
							<input type="hidden" name="saveFormEditCompany" value="sucess">
							<button type="submit" class="btn btn-success my-1">Salvar</button>
						  </div>
					  </div>  
					</form>
					
				 
				</div>

			</body>
		</html>
	<?php
	}else{echo '<script type="text/javascript">window.location.href="lista_empresas.php";</script>';}

//<!--http://www.richardbarros.com.br/blog/css/css-truques-para-dominar-a-propriedade-float-->