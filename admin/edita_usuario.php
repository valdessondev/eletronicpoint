<?php 
//Código do menu para validar o acesso
$codigoMenu = 4;
require_once('verifica_session.php');

$display = "none";
$msg_result = "";

$codigo_user = isset($_POST["codigo_user"]) ? $_POST["codigo_user"] : "";

//---------CONSULTA TODOS GRUPOS DE ACESSO-------------
$querygrupoacesso = "SELECT GRUPOACESSO_ID,NM_GRUPOACESSO,GRUPOACESSO_ATIVO FROM GRUPOACESSO WHERE GRUPOACESSO_ID != 1";
$exec_querygrupoacesso = mysqli_query($conn, $querygrupoacesso);

//-------FIM CONSULTA GRUPOS DE ACESSO-----------------

//-------CONSULTA OS DADOS DO USUÁRIO-----------------------
$query_user = "SELECT * FROM USERS WHERE ID = '$codigo_user'";  
$exec_query = mysqli_query($conn, $query_user);
$total = mysqli_num_rows($exec_query);
$registros = mysqli_fetch_assoc($exec_query);
//----FIM CONSULTA DADOS DO USUÁRIO--------------------------

if((isset($codigo_user)) && (!empty($codigo_user)) && $total > 0){
	
	if(isset($_POST["saveFormEditUser"]) && $_POST['saveFormEditUser'] == "sucess"){
		//Recuperando o login atual do banco de dados
		$login_user_old = $_POST["login_user_old"];

		$name_user = isset($_POST["nome_user"]) ? $_POST["nome_user"] : "";
		$grupoacesso_user = isset($_POST["grupoacesso"]) ? $_POST["grupoacesso"] : "";
		
		//Senha Alterada por padrão é False para dizer que a senha não foi alterada a principio
		$senhaAlterada = false;

		//Se o login da sessao foi igual ao login atual, quer dizer que é o mesmo usuário
		if($_SESSION['LOGIN']==$login_user_old){

			$login_user_new = $login_user_old;
			$user_ativo = $registros["USER_ATIVO"];

		}else{
			$login_user_new = isset($_POST["login_user_new"]) ? $_POST["login_user_new"]: "";
			$user_ativo = isset($_POST["user_ativo"]) ? $_POST["user_ativo"] : $registros["USER_ATIVO"];
		}

		//Se o usuário tiver preenchido a senha, o sistema vai sinalizar e verificar se a senha é a mesma ou não
		if (isset($_POST["password_user"]) && !empty($_POST["password_user"])) {

			$password_user = $_POST["password_user"]; 
			$senhaAlterada = !password_verify($password_user, $registros["PASSWORD"]);
		}

		$CadastroAlterado = 0;
		
		if ($_SESSION['LOGIN'] == $registros['LOGIN'])	$user_ativo = $_SESSION['USER_ATIVO'];
			
		//-----------------Consulta todos os logins-----------------------
		$query_user_all = "SELECT LOGIN FROM USERS WHERE LOGIN = '$login_user_new'";  
		$exec_query_all = mysqli_query($conn, $query_user_all);
		$total_login = mysqli_num_rows($exec_query_all);
		//---------------------------------------------------------------

		//Consulta para verificar se o grupo de acesso está ativo ou não
		$queryverificagrupoacesso = "SELECT 
										GRUPOACESSO_ID 
									FROM 
										GRUPOACESSO 
									WHERE 
										GRUPOACESSO_ID = '$grupoacesso_user' 
									AND 
										GRUPOACESSO_ATIVO = 0
									LIMIT 1";  
		$exec_queryverificagrupoacesso = mysqli_query($conn, $queryverificagrupoacesso);
		$grupoacesso_inativo_total = mysqli_num_rows($exec_queryverificagrupoacesso);
		
		if(empty($name_user) || empty($login_user_new)){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>- Preencha todos os campos.</div>";
			$display = "block";

		}elseif(isset($password_user) && !empty($password_user) && !preg_match("/^[^\/\s]+.{4,20}$/",$password_user)){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. 
			<br>- Digite uma senha (MIN 5  - MAX 20 caracteres).</div>";
			$display = "block";
			
		}elseif(!preg_match("/^[^\/\s0-9]+[a-zA-ZÀ-ú\/\s]{5,79}$/",$name_user)){

			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. 
			<br>- Digite o nome do usuario(5-79 letras).</div>";
			$display = "block";

		 }elseif(!preg_match("/^[^\/\s]+[a-zA-ZÀ-ú\/\s0-9]{2,20}$/",$login_user_new) || !empty(stristr($login_user_new, " ")) ){

			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro.
			 <br>- Digite o login do usuario(3-20 letras e/ou numeros).
			 <br>- O login não pode conter espaços.</div>";
			$display = "block";

		 }elseif(isset($_POST["user_ativo"]) && $_POST["user_ativo"]!=1 && $registros["USER_ATIVO"]==0){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>- Usuário desativado</div>";
			$display = "block";
			
		}elseif($login_user_new != $login_user_old && $total_login > 0){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>- Este login Já Existe: '$login_user_new'</div>";
			$display = "block";
			
		}elseif(($grupoacesso_inativo_total > 0) && ($grupoacesso_user != $registros["GRUPOACESSO"])){
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. <br>- Grupo de Acesso desativado.</div>";
			$display = "block";
			
		}
		elseif($registros["LOGIN"] != $login_user_new || $senhaAlterada
				|| $registros["NOME"] != $name_user || $registros["USER_ATIVO"] !=$user_ativo
				|| $registros["GRUPOACESSO"] != $grupoacesso_user){
			
			//Atualizando as informações do cadastro, exceto a senha que será verificada se mudou
			$edituser = "UPDATE USERS SET LOGIN = '$login_user_new',NOME = '$name_user',
			GRUPOACESSO = '$grupoacesso_user', USER_ATIVO = '$user_ativo', DT_ALTERACAO = CURRENT_TIMESTAMP
			WHERE ID = '$codigo_user'";
			$executarEditUser = mysqli_query($conn, $edituser);
			
			//Se a senha estiver sido alterada, irá ser atualizada no Banco de Dados
			if($senhaAlterada) {
				$password_user = password_hash($password_user, PASSWORD_DEFAULT);
				
				$edituser_password = "UPDATE USERS SET PASSWORD = '$password_user' WHERE ID = '$codigo_user'";
				mysqli_query($conn, $edituser_password);

				$senhaAlterada = false;
			}
			
			$CadastroAlterado = 1;			

			if($executarEditUser && $CadastroAlterado == 1){
				
				$msg_result = "<div class='alert alert-success'>Salvo com sucesso.</div>";
				$display = "block";
			}
			
		}elseif($CadastroAlterado == 0){
			$msg_result = "<div class='alert alert-success'>Salvo com sucesso.
			<br> -> Nenhuma alteração realizada. </div>";
			$display = "block";
			
		} else {
			
			$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. 
						<br>Contate o administrador.</div>";
			$display = "block";		
		}
	}
	$query_user = "SELECT * FROM USERS WHERE ID = '$codigo_user' LIMIT 1";  
	$exec_query = mysqli_query($conn, $query_user);
	$total = mysqli_num_rows($exec_query);
	$registros = mysqli_fetch_assoc($exec_query);
				
	//Se for o mesmo usuário e tiver alterado nome da pessoa, a sessão contendo o nome será alterado
	if(isset($_POST["login_user_old"]) && $_SESSION['LOGIN']==$login_user_old){
		$_SESSION['NM_USER'] = ($_SESSION['NM_USER'] != $registros['NOME'])?$registros['NOME']:$_SESSION['NM_USER'] ;
	} 

	?>
	
	<!DOCTYPE html>
	<html>
		<head>
			<title>Editar Cadastro de Usuário :: Break | Controle de Ponto Eletrônico</title>
			
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
			<link href="../css/bootstrap.min.css" rel="stylesheet">
			<link href="../css/estilo.css" rel="stylesheet">
			
			<script src="../js/jquery-3.3.1.min.js"></script>
			<script type="text/javascript" src="../js/script.js"></script>
			<script src="../js/bootstrap.min.js"></script>
			
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
				<a class="btn btn-dark" href="lista_usuarios.php" style="margin-left: 12px">Voltar</a>
				
				<a href="sair_admin.php" class="btn btn-secondary btn-sm" style="margin-left: 12px" >Sair</a>
				
				<h2>Edita Cadastro - Usuários</h2>	
				<br>
				
				<?php if($registros["USER_ATIVO"]==0){ ?>
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
				
				<div id ="result" style="display:<?=$display?>;position:relative;	"><?=$msg_result?></div>
				
				<div id="InformacoesAlteracao">
					<div class="form-group h6">
						Cadastro:
						<?=date("d/m/Y H:i:s", strtotime($registros['DT_CADASTRO']))?><br>
					</div>
					<?php if ($registros['DT_ALTERACAO'] != null){?>
						<div class="form-group h6"> Alteração: <?=date("d/m/Y H:i:s", strtotime($registros['DT_ALTERACAO']))?></div>
					<?php } ?>
				</div>
				
				<form method="post" action="edita_usuario.php" id="FormEditUser">
				
					<div class="form-group">
					<label for="Nome">Nome*</label>
					<input type="text" name="nome_user" class="form-control" id="nome_user" placeholder="Nome do usuário"
					value = "<?=$registros['NOME']?>"  minlength="5" maxlength="79" title="Digite o nome do usuario(5-79 Letras)." 
					pattern="^[^/\s0-9][a-zA-ZÀ-ú/\s]{5,79}$" required>
					</div>
					
					<div class="form-group">
					<label for="Login">Login* (Min 3 Max 20 caracteres) </label>
					<input type="text" name="login_user_new" class="form-control" id="login_user_new" placeholder="Login"
					value = "<?=$registros['LOGIN']?>"  minlength="3" maxlength= "20" size="20" 
					title="Digite o nome do usuario(3-20 letras e/ou numeros)." pattern="^[^/\s]+[a-zA-ZÀ-ú/\s0-9]{2,20}$"
					<?php if ($_SESSION['LOGIN'] == $registros['LOGIN']) echo "disabled";?> required>
					
					<input type="hidden" name="login_user_old" value = "<?=$registros['LOGIN']?>">
					</div>
				
					<div class="form-group">
					<label for="Senha">Senha(Min 5 Max 20 dígitos) - Letras e/ou números:</label>
					<input type="password" name="password_user" class="form-control" id="Senha" 
					minlength="5" maxlength= "20" size="20" placeholder="*************" 
					title="Digite uma senha (MIN 5  - MAX 20 digitos" patter="^[^/\s]+.{4,20}$"> 						
					</div>	

				<div class="form-group">
					<label for="Grupo de Acesso">Grupo de Acesso*</label>
					<select name="grupoacesso" class="form-control" id="grupoacesso" required>
						<!-- GRUPO DE ACESSO -->
						<?php while($registro_grupoacesso = mysqli_fetch_array($exec_querygrupoacesso)){ ?>
						<option value="<?=$registro_grupoacesso['GRUPOACESSO_ID']?>" 
						<?php if($registro_grupoacesso['GRUPOACESSO_ATIVO']==0) echo 'class="table-dark"';?>
						<?php if($registros['GRUPOACESSO'] == $registro_grupoacesso['GRUPOACESSO_ID']) echo "selected"; ?>>
						<?=$registro_grupoacesso['NM_GRUPOACESSO']?></option>
						
					<?php }?> 
					</select>
				</div>
					
					<div class="form-group">
					<label for="Situação do Cadastro">Situacao do Cadastro*:</label>
					<select name="user_ativo" class="form-control" id="user_ativo" 
					<?php if ($_SESSION['LOGIN'] == $registros['LOGIN']) echo "disabled";?> required>
						
						<option value="1" <?php if($registros['USER_ATIVO'] == 1) echo "selected" ?>>ATIVO</option>
						<option value="0" <?php if($registros['USER_ATIVO'] == 0) echo "selected" ?>>DESATIVADO</option> 
					</select>
					</div>
				
					<div class="form-group">
					<input type="hidden" name="codigo_user" value="<?=$registros['ID']?>">
					<input type="hidden" name="saveFormEditUser" value="sucess">
					<button type="submit" class="btn btn-success my-1">Salvar</button>
					</div>
					
				</form>
				
				</div>
			</div>

		</body>
	</html>
<?php
}else{echo '<script type="text/javascript">window.location.href="lista_usuarios.php";</script>';}
//http://www.richardbarros.com.br/blog/css/css-truques-para-dominar-a-propriedade-float