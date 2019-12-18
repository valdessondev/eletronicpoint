<?php 
//Código do menu para validar o acesso
$codigoMenu = 8;
require_once('verifica_session.php');
require_once('../class/array_ordem-class.php');

$display = "none";
$msg_result = "";
$comp = array();

//Instanciando objeto para utilizar a função de ordenar array
$UserArray = new array_ordem();

$codigo_grupoacesso = isset($_POST["code_grupoacesso"]) ? $_POST["code_grupoacesso"] : "";

$query_group = "SELECT * FROM GRUPOACESSO WHERE GRUPOACESSO_ID = '$codigo_grupoacesso'";  
$exec_query = mysqli_query($conn, $query_group);
$total = mysqli_num_rows($exec_query);
$registros = mysqli_fetch_assoc($exec_query);


//-----------------Consulta todas as permissões------------------------------------
$query_permissoes = "SELECT PERMISSOES_ID, NM_PERMISSOES FROM PERMISSOES WHERE PERMISSOES_ATIVA = 1 ORDER BY NM_PERMISSOES ASC";  
$exec_query_permissoes = mysqli_query($conn, $query_permissoes);

$query_permissoes1 = $query_permissoes;  
$exec_query_permissoes1 = mysqli_query($conn, $query_permissoes1);

while($registros_ps = mysqli_fetch_array($exec_query_permissoes1)){//Captura TODAS as PERMISSÕES
	$ps[] = $registros_ps["PERMISSOES_ID"] ;
}
//----------------------------------------------------------------------------------

//-----Consulta todas as permissões DO GRUPO DE ACESSO e cria um array com o resultado------------

$query_permissoes_grupos = "SELECT GRUPO_ID,PERMISSOES_ID FROM PERMISSOES_GRUPOS WHERE GRUPO_ID = $codigo_grupoacesso"; 
$exec_query_permissoes_grupos = mysqli_query($conn, $query_permissoes_grupos);

while($registros_pg = mysqli_fetch_array($exec_query_permissoes_grupos)){
	$gp[] = $registros_pg["PERMISSOES_ID"] ;
}	
//----------------------------------------------------------------------------------


if((isset($codigo_grupoacesso)) && (!empty($codigo_grupoacesso)) && $total > 0){
	
	if(isset($_POST["saveFormEditGroupAccess"]) && $_POST['saveFormEditGroupAccess'] == "sucess"){
		
		//-----------Identifica os grupos marcados---EX DESATIVADO
		/**if(isset($_POST['ckbPermissao'])){
			$qt = count($_POST['ckbPermissao']);
			$k = 1;				
			foreach($_POST['ckbPermissao'] as $permissao){
				$v="";
				if($k < $qt){
					$v = ", ";
				}
				$comp.= $permissao.$v;
				$k++;
			}
		}else{
			$comp = null;
		}*/
		//------------------------Fim-------------------------------
		
		$codigo_grupoacesso = $_POST["code_grupoacesso"];
		$NM_GRUPOACESSO = isset($_POST["description_grupoacesso"]) ? $_POST["description_grupoacesso"] : "";
		
		$CadastroAlterado = 0;

		/**
			$com1 = Array recuperado do formulário, incluindo os que já estavam
			$ps = Array de todas as permissões
			$gp = Array das permissoes daquele grupo
		**/
		//---Inicializando o array com VAZIO para garantir que não vai dar erro se não tiver informação
		$gp = !empty($gp) ? $gp : array();
		//----------------Fim inicialização-----------------------------------------

		//Identificando as permissões que foram SELECIONADAS
		$com1 = isset($_POST['ckbPermissao'])?$_POST['ckbPermissao']:array();

		//*****************************************************************************************
		//Identificando as permissões que não foram selecionadas para exclusão
		$permisnaoselec = array_diff($ps, $com1);
		//Identificando as permissoes não selecionadas que estavam no BD que agora serão excluídas
		$permisdelet = !empty(array_intersect($permisnaoselec, $gp))? array_intersect($permisnaoselec, $gp) : array();			
		//Informações prontas para deletar ORGANIZADAS
		$arrayparadetelar = $UserArray->organiza($permisdelet);
		//******************************************************************************************

		//******************************************************************************************
		//Identificando a intercessão que tem no BD e no FORM
		$permisselec = array_intersect($com1, $gp);

		//Verificando os dados selecionados no Formulário que ainda não estão no BD
		$arrayparaincluir = array_diff($com1, array_intersect($permisselec, $gp));

		//******************************************************************************************
			
		//--------Consulta as descrições de todos os grupos de acesso para fazer a validação se tem nome repetido---------
		$query_grupoacesso = "SELECT NM_GRUPOACESSO FROM GRUPOACESSO WHERE NM_GRUPOACESSO = '$NM_GRUPOACESSO'";  
		$exec_query_all = mysqli_query($conn, $query_grupoacesso);
		$total_grupoacesso = mysqli_num_rows($exec_query_all);
		//------------------------------------------------------------------------------------------------------------
		
		if(!isset($NM_GRUPOACESSO) || empty($NM_GRUPOACESSO)){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>
			-> Preencha todos os campos.</div>";
			$display = "block";
	
		}elseif(!preg_match("/^[^\/\s]+[a-zA-ZÀ-ú\/\s0-9]{4,50}$/",$NM_GRUPOACESSO)){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>
			-> Digite o nome do grupo de acesso(Min 5 Max 50 letras e/ou numeros)</div>";
			$display = "block";
		
		}elseif($NM_GRUPOACESSO != $registros['NM_GRUPOACESSO'] && $total_grupoacesso > 0){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>-> Este Grupo já Existe</div>";
			$display = "block";
			
		}elseif($registros["NM_GRUPOACESSO"] != $NM_GRUPOACESSO || !empty($arrayparadetelar) || !empty($arrayparaincluir)){	

			$editgrupoacesso = "UPDATE GRUPOACESSO SET NM_GRUPOACESSO = '$NM_GRUPOACESSO', DT_ALTERACAO = CURRENT_TIMESTAMP WHERE GRUPOACESSO_ID = '$codigo_grupoacesso'";
			$executarEditGrupoacesso = mysqli_query($conn, $editgrupoacesso);

			//Se o array para DELETAR ou array para INCLUIR NÃO estiver vazio ele vai realizar o procedimento
			if(!empty($arrayparadetelar) || !empty($arrayparaincluir)){

				if(!empty($arrayparadetelar)){

					//Realizando o DELETE
					$deletapermissoes = "DELETE FROM PERMISSOES_GRUPOS WHERE GRUPO_ID = '$codigo_grupoacesso' AND 
										PERMISSOES_ID IN($arrayparadetelar)";

					mysqli_query($conn, $deletapermissoes) or die('Exclusão Falhou!: ' . mysql_error($conn));

				}
				if(!empty($arrayparaincluir)){

					//Realizando a INCLUSÃO DAS permissões selecionadas no formulário
					foreach ($arrayparaincluir as $permissao) {

						$inserepermissoes = "INSERT INTO PERMISSOES_GRUPOS(GRUPO_ID,PERMISSOES_ID)
												VALUES('$codigo_grupoacesso', '$permissao')";
						mysqli_query($conn, $inserepermissoes) or die('Inserção Falhou!: ' .mysqli_error($conn));;
					}
				}

				//Esvaziando o array para não repetir na consulta
				$gp = array();

				//Consultando novamente para exibir o GP - Array atualizado
				$query_permissoes_grupos = "SELECT * FROM PERMISSOES_GRUPOS WHERE GRUPO_ID = $codigo_grupoacesso"; 
				$exec_query_permissoes_grupos = mysqli_query($conn, $query_permissoes_grupos);
				//Populando o array atualizado
				while($registros_pg = mysqli_fetch_array($exec_query_permissoes_grupos)){
					$gp[] = $registros_pg["PERMISSOES_ID"] ;
				}
			}

			if($executarEditGrupoacesso){
				
				$msg_result = "<div class='alert alert-success'>Salvo com sucesso.</div>";
				$display = "block";
			}
			$CadastroAlterado = 1;	
			
		}elseif(empty($CadastroAlterado)){
			
			$msg_result = "<div class='alert alert-success'>Salvo com sucesso.
			<br> -> Nenhuma alteração realizada. </div>";
			$display = "block";
			
		}else {
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. Contate o administrador.</div>";
			$display = "block";		
		}
	}
	
	$query_grupoacesso = "SELECT * FROM GRUPOACESSO WHERE GRUPOACESSO_ID = '$codigo_grupoacesso'";  
	$exec_query = mysqli_query($conn, $query_grupoacesso);
	$total = mysqli_num_rows($exec_query);
	$registros = mysqli_fetch_assoc($exec_query);
	
	?>

	<!DOCTYPE html>
	<html>
		<head>
			<title>Editar Cadastro Grupos de Acesso :: Break | Controle de Ponto Eletrônico</title>
			
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
					<a class="btn btn-dark" href="lista_grupoacesso.php" style="margin-left: 12px">Voltar</a>
					
					<a href="sair_admin.php" class="btn btn-secondary btn-sm" style="margin-left: 12px" >Sair</a>
					
					<h2>Edita Cadastro - Grupo de Acesso</h2>	
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
										
					<div id ="result" style="display:<?=$display?>;position:relative;"><?=$msg_result?></div>
				
					<form method="post" action="edita_grupoacesso.php" id="FormEditGroupAccess">
					
						<div class="form-group">
							<label for="Código do Grupo de Acesso">Código:</label>
							<?=$registros['GRUPOACESSO_ID']?>
						</div>

						<div class="form-group">
							<label for="Descrição do Grupo de Acesso">Descrição</label>
							<input type="text" name="description_grupoacesso" class="form-control" id="description_grupoacesso" 
							value = "<?php if (isset($_POST['description_grupoacesso']))echo $_POST['description_grupoacesso']; else echo $registros['NM_GRUPOACESSO'];?>" 
							placeholder="Nome do grupo de acesso" minlength="5" maxlength= "50" pattern="^[^/\s]+[a-zA-ZÀ-ú\/\s0-9]{4,50}$" size="50" 
							title="Digite o nome do grupo de acesso(Min 5 Max 50 letras e/ou numeros)" required>

							<input type="hidden" name="code_grupoacesso" value = "<?=$registros['GRUPOACESSO_ID']?>">
						</div>	

						<!--Formulário de Permissões-->
						<div class="form-perm-group">
							<div class="form-perm-group-desc">Permissões</div>
							
							<?php while($registros_permissao = mysqli_fetch_array($exec_query_permissoes)){ ?>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="<?=$registros_permissao['PERMISSOES_ID']?>" 
									name="ckbPermissao[]" id="<?=$registros_permissao['PERMISSOES_ID']?>"

									<?php if(isset($gp) && in_array($registros_permissao['PERMISSOES_ID'], $gp)) echo "checked"; ?>>	

									<label class="form-check-label" for="Permissão">
										<?=$registros_permissao["NM_PERMISSOES"]?>
									</label>

								</div>
							<?php } ?>

						</div>
						<!--FIM Formulário de Permissões-->

						<div class="form-group">
							<input type="hidden" name="codigo_grupoacesso" value="<?=$registros['GRUPOACESSO_ID']?>">
							<input type="hidden" name="saveFormEditGroupAccess" value="sucess">
							<button type="submit" class="btn btn-success my-1">Salvar</button>
						</div> 
					</form>		
					
				</div>

			</div>

		</body>
</html>
<?php
}else{echo '<script type="text/javascript">window.location.href="lista_grupoacesso.php";</script>';}
