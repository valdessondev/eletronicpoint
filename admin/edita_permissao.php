<?php 
//Código do menu para validar o acesso
$codigoMenu = 9;
require_once('verifica_session.php');
require_once('../class/array_ordem-class.php');

$display = "none";
$msg_result = "";
$comp = array();

//Instanciando objeto para utilizar a função de ordenar array
$UserArray = new array_ordem();

$code_permissao = isset($_POST["code_permissao"]) ? $_POST["code_permissao"] : "";

$query_user = "SELECT PERMISSOES_ID,NM_PERMISSOES,PERMISSOES_ATIVA, DT_CADASTRO, DT_ALTERACAO
				FROM PERMISSOES WHERE PERMISSOES_ID = '$code_permissao'";  
$exec_query = mysqli_query($conn, $query_user);
$total = mysqli_num_rows($exec_query);
$registros = mysqli_fetch_assoc($exec_query);


//-----------------Consulta todas os menus------------------------------------
$query_menu = "SELECT MENU_ID, NM_MENU FROM MENU WHERE MENU_ATIVO = 1 ORDER BY NM_MENU ASC";  
$exec_query_menu = mysqli_query($conn, $query_menu);

$query_menu1 = $query_menu;  
$exec_query_menu1 = mysqli_query($conn, $query_menu1);

while($registros_ps = mysqli_fetch_array($exec_query_menu1)){//Captura TODOS os menus
	$ps[] = $registros_ps["MENU_ID"] ;
}
//----------------------------------------------------------------------------------

//-----Consulta todos os menus DA PERMISSÃO e cria um array com o resultado-----------

$query_menu_permissoes = "SELECT MENU_ID FROM PERMISSOES_MENU WHERE PERMISSOES_ID = $code_permissao"; 
$exec_query_menu_permissoes = mysqli_query($conn, $query_menu_permissoes);

while($registros_pg = mysqli_fetch_array($exec_query_menu_permissoes)){
	$gp[] = $registros_pg["MENU_ID"] ;
}	
//----------------------------------------------------------------------------------


if((isset($code_permissao)) && (!empty($code_permissao)) && $total > 0){
	
	if(isset($_POST["saveFormEditPermission"]) && $_POST['saveFormEditPermission'] == "sucess"){
		
		//-----------Identifica os menus marcados---EX DESATIVADO
		/**if(isset($_POST['ckbMenu'])){
			$qt = count($_POST['ckbMenu']);
			$k = 1;				
			foreach($_POST['ckbMenu'] as $menu){
				$v="";
				if($k < $qt){
					$v = ", ";
				}
				$comp.= $menu.$v;
				$k++;
			}
		}else{
			$comp = null;
		}*/
		//------------------------Fim-------------------------------
		
		$codigo_permissao = $_POST["code_permissao"];
		$NM_PERMISSOES = isset($_POST["description_permissao"]) ? $_POST["description_permissao"] : "";
		$permissoes_ativa = $_POST["permissoes_ativa"];
		
		$CadastroAlterado = 0;

		/**
			$com1 = Array recuperado do formulário, incluindo os que já estavam
			$ps = Array de todas os menus 
			$gp = Array dos menus daquela permissão
		**/
		//-----Recuperando os menus daquela permissão, se não tiver nenhum, atribui array vazio
		$gp = !empty($gp) ? $gp : array();

		//Identificando os menus que foram SELECIONADAS---------------------------------
		$com1 = isset($_POST['ckbmenu']) ? $_POST['ckbmenu'] : array();

		//*****************************************************************************************
		//Identificando os menus que não foram selecionados para exclusão
		$menunaoselec = array_diff($ps, $com1);
		//Identificando os menus não selecionados que estavam no BD que agora serão excluídas
		$menudelet = !empty(array_intersect($menunaoselec, $gp))? array_intersect($menunaoselec, $gp) : array();			
		//Informações prontas para deletar ORGANIZADAS
		$arrayparadetelar = $UserArray->organiza($menudelet);
		//******************************************************************************************

		//******************************************************************************************
		//Identificando a intercessão que tem no BD e no FORM
		$menuselec = array_intersect($com1, $gp);

		//Verificando os dados selecionados no Formulário que ainda não estão no BD
		$arrayparaincluir = array_diff($com1, array_intersect($menuselec, $gp));

		//******************************************************************************************
			
		//-----Consulta as descrições de todas as permissões para fazer a validação se tem nome repetido------
		$query_permissoes = "SELECT NM_PERMISSOES FROM PERMISSOES WHERE NM_PERMISSOES = '$NM_PERMISSOES'";  
		$exec_query_all = mysqli_query($conn, $query_permissoes);
		$total_permissoes = mysqli_num_rows($exec_query_all);
		//----------------------------------------------------------------------------------------------------------
		
		if(!isset($NM_PERMISSOES) || empty($NM_PERMISSOES)){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>
			-> Preencha todos os campos.</div>";
			$display = "block";
	
		}elseif(!preg_match("/^[^\/\s]+[a-zA-ZÀ-ú\/\s0-9]{4,50}$/",$NM_PERMISSOES)){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>
			-> Digite o nome da permissão(Min 5 Max 50 letras e/ou numeros)</div>";
			$display = "block";
		
		}elseif($_POST["permissoes_ativa"]!=1 && $registros["PERMISSOES_ATIVA"]==0){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>-> Permissão Desativada</div>";
			$display = "block";
			
		}elseif($NM_PERMISSOES != $registros['NM_PERMISSOES'] && $total_permissoes > 0){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>-> Esta Permissão já Existe</div>";
			$display = "block";
			
		}elseif($registros["NM_PERMISSOES"] != $NM_PERMISSOES || $registros["PERMISSOES_ATIVA"]!= $permissoes_ativa 
		|| !empty($arrayparadetelar) || !empty($arrayparaincluir)){	

			$editpermissoes = "UPDATE PERMISSOES SET NM_PERMISSOES = '$NM_PERMISSOES', PERMISSOES_ATIVA = '$permissoes_ativa',DT_ALTERACAO = CURRENT_TIMESTAMP WHERE PERMISSOES_ID = '$codigo_permissao'";
			$executarEditPermissao = mysqli_query($conn, $editpermissoes);

			//Se o array para DELETAR ou array para INCLUIR NÃO estiver vazio ele vai realizar o procedimento
			if(!empty($arrayparadetelar) || !empty($arrayparaincluir)){

				if(!empty($arrayparadetelar)){

					//Realizando o DELETE
					$deletamenu = "DELETE FROM PERMISSOES_MENU WHERE PERMISSOES_ID = '$codigo_permissao' AND 
									MENU_ID IN($arrayparadetelar)";

					mysqli_query($conn, $deletamenu) or die('Exclusão Falhou!: ' . mysqli_error($conn));

				}
				if(!empty($arrayparaincluir)){

					//Realizando a INCLUSÃO DOS menus selecionados no formulário
					foreach ($arrayparaincluir as $menu) {

						$inseremenu = "INSERT INTO PERMISSOES_MENU(PERMISSOES_ID,MENU_ID)
												VALUES('$codigo_permissao', '$menu')";
						mysqli_query($conn, $inseremenu) or die('Inserção Falhou!: ' .mysqli_error($conn));
					}
				}

				//Esvaziando o array para não repetir na consulta
				$gp = array();

				//Consultando novamente para exibir o GP - Array atualizado
				$query_permissoes_menus = "SELECT MENU_ID FROM PERMISSOES_MENU WHERE PERMISSOES_ID = $codigo_permissao"; 
				$exec_query_permissoes_menu = mysqli_query($conn, $query_permissoes_menus);

				//Populando o array atualizado
				while($registros_pg = mysqli_fetch_array($exec_query_permissoes_menu)){
					$gp[] = $registros_pg["MENU_ID"] ;
				}
			}

			if($executarEditPermissao){
				
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
	
	$query_permisson = "SELECT PERMISSOES_ID,NM_PERMISSOES,PERMISSOES_ATIVA, DT_CADASTRO, DT_ALTERACAO
				FROM PERMISSOES WHERE PERMISSOES_ID = '$code_permissao'";
	$exec_query = mysqli_query($conn, $query_permisson);
	$total = mysqli_num_rows($exec_query);
	$registros = mysqli_fetch_assoc($exec_query);
	
	?>

	<!DOCTYPE html>
	<html>
		<head>
			<title>Editar Cadastro Permissões :: Break | Controle de Ponto Eletrônico</title>
			
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
					<a class="btn btn-dark" href="lista_permissoes.php" style="margin-left: 12px">Voltar</a>
					
					<a href="sair_admin.php" class="btn btn-secondary btn-sm" style="margin-left: 12px" >Sair</a>
					
					<h2>Edita Cadastro - Permissões</h2>	
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
					
					<?php if($registros["PERMISSOES_ATIVA"]==0){ ?>
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
				
					<form method="post" action="edita_permissao.php" id="FormEditPermission">
					
						<div class="form-group">
							<label for="Código da permissao">Código:</label>
							<?=$registros['PERMISSOES_ID']?>
						</div>

						<div class="form-group">
							<label for="Descrição da permissão">Descrição</label>
							<input type="text" name="description_permissao" class="form-control" id="description_permissao" 
							value = "<?php if (isset($_POST['description_permissao']))echo $_POST['description_permissao']; else echo $registros['NM_PERMISSOES'];?>"  
							placeholder="Nome da permissão" minlength="5" maxlength= "50" pattern="^[^/\s]+[a-zA-ZÀ-ú\/\s0-9]{4,50}$" size="50" 
							title="Digite o nome da permissao(Min 5 Max 50 letras e/ou numeros)." required>

							<input type="hidden" name="code_permissao" value = "<?=$registros['PERMISSOES_ID']?>">
						</div>	

						<!--Formulário de Permissões-->
						<div class="form-perm-group">
							<div class="form-perm-group-desc">Menus do Sistema</div>
							
							<?php while($registros_menu = mysqli_fetch_array($exec_query_menu)){ ?>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="<?=$registros_menu['MENU_ID']?>" 
									name="ckbmenu[]" id="<?=$registros_menu['MENU_ID']?>"

									<?php if(isset($gp) && in_array($registros_menu['MENU_ID'], $gp)) echo "checked"; ?>>	

									<label class="form-check-label" for="Permissão">
										<?=$registros_menu["NM_MENU"]?>
									</label>

								</div>
							<?php } ?>

						</div>
						<!--FIM Formulário de Permissões-->

						<div class="form-group">
							<label for="Situação do Cadastro">Situacao do Cadastro:</label>
							<select name="permissoes_ativa" class="form-control" id="permissoes_ativa" required>

							<option value="1" <?php if($registros['PERMISSOES_ATIVA'] == 1) echo "selected" ?>>ATIVO</option>
							<option value="0" <?php if($registros['PERMISSOES_ATIVA'] == 0) echo "selected" ?>>DESATIVADO</option> 
							</select>
						</div>

						<div class="form-group">
							<input type="hidden" name="codigo_permissao" value="<?=$registros['PERMISSOES_ID']?>">
							<input type="hidden" name="saveFormEditPermission" value="sucess">
							<button type="submit" class="btn btn-success my-1">Salvar</button>
						</div> 
					</form>		
					
				</div>

			</div>

		</body>
</html>
<?php
}else{echo '<script type="text/javascript">window.location.href="lista_permissoes.php";</script>';}
//http://www.richardbarros.com.br/blog/css/css-truques-para-dominar-a-propriedade-float