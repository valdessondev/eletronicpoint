<?php 
//Código do menu para validar o acesso
$codigoMenu = 4;
require_once('verifica_session.php');

//---------CONSULTA TODOS GRUPOS DE ACESSO-------------
$querygrupoacesso = "SELECT 
						GRUPOACESSO_ID,NM_GRUPOACESSO,GRUPOACESSO_ATIVO 
					FROM 
						GRUPOACESSO
					 WHERE 
					 	GRUPOACESSO_ID != 1 
					AND 
						GRUPOACESSO_ATIVO = 1";
$exec_querygrupoacesso = mysqli_query($conn, $querygrupoacesso);
//-------FIM CONSULTA GRUPOS DE ACESSO-----------------

$display = "none";
$msg_result = "";
	
if(isset($_POST["saveFormAddUser"]) && $_POST['saveFormAddUser'] == "sucess"){
	
	$login_user = isset($_POST["login_user"]) ? $_POST["login_user"] : "";
	$name_user = isset($_POST["nome_user"]) ? $_POST["nome_user"] : "";
	$password_user = isset($_POST["password_user"]) ? $_POST["password_user"] : "";
	$grupoacesso_user = isset($_POST["grupoacesso"]) ? $_POST["grupoacesso"] : "";
	
	//-----------------Consulta todos os logins-----------------------
	$query_user_all = "SELECT LOGIN FROM USERS WHERE LOGIN = '$login_user'";  
	$exec_query_all = mysqli_query($conn, $query_user_all);
	$total_login = mysqli_num_rows($exec_query_all);
	//---------------------------------------------------------------

	if(!isset($login_user) || empty($login_user) || !isset($name_user) || empty($name_user)
	|| !isset($password_user) || empty($password_user) || !isset($grupoacesso_user) || empty($grupoacesso_user)){
		$msg_result = "<div class='alert alert-danger'>- Erro ao inserir cadastro. 
			<br>- Preecha todos os campos.</div>";
			$display = "block";


	}elseif(!preg_match("/^[^\/\s0-9]+[a-zA-ZÀ-ú\/\s]{5,79}$/",$name_user)){

		$msg_result = "<div class='alert alert-danger'>- Falha ao inserir cadastro. 
		<br>- Digite o nome do usuario(5-79 letras).</div>";
		$display = "block";

	 }elseif(!preg_match("/^[^\/\s]+[a-zA-ZÀ-ú\/\s0-9]{2,20}$/",$login_user) ||!empty(stristr($login_user, " "))){

		$msg_result = "<div class='alert alert-danger'>- Falha ao inserir cadastro.
		 <br>- Digite o login do usuario(3-20 letras e/ou numeros).
		 <br>- O Login não pode conter espaços.</div>";
		$display = "block";

	 }elseif(!preg_match("/^[^\/\s]+.{4,20}$/",$password_user)){
		$msg_result = "<div class='alert alert-danger'>- Falha ao atualizar cadastro. 
		<br>- Digite uma senha (MIN 5  - MAX 20 caracteres).</div>";
		$display = "block";
		
	}elseif($total_login > 0){
			$msg_result = "<div class='alert alert-danger'>- Erro ao inserir cadastro. 
			<br>- Este login já existe</div>";
			$display = "block";
			
	}else{
		
		//Criptografando a senha
		$password_user = password_hash($password_user, PASSWORD_DEFAULT);

		//Adicionando cadastro no Banco de Dados
		$addtuser = "INSERT INTO USERS (LOGIN, PASSWORD, NOME, GRUPOACESSO) VALUES('$login_user','$password_user','$name_user','$grupoacesso_user')";
		$executAddUser = mysqli_query($conn, $addtuser);

		if($executAddUser){
			
			$msg_result = "<div class='alert alert-success'>Salvo com sucesso.
							<br><a href='cadastrar_usuario.php'>[CADASTRAR NOVO]</a>
						</div>";
			$display = "block";
			
		} else {
			$msg_result = "<div class='alert alert-danger'>- Erro ao inserir cadastro. 
							<br>Contate o administrador.
						</div>";
			$display = "block";		
		}
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Cadastrar Usuário :: Break | Controle de Ponto Eletrônico</title>

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
		<link href="../css/estilo.css" rel="stylesheet" type="text/css">
		
		<script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="../js/script.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		
		<script type="text/javascript">
			$(document).ready(function(){
				$('#result').fadeIn(5000);
				//$('#result').fadeOut(10000);
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
			
			<h2>Cadastrar Usuários</h2>	
			<br>
			<div id ="result" style="display:<?=$display?>"><?=$msg_result?></div>
		
			<form method="post" action="cadastrar_usuario.php" id="FormAddtUser">
			
				<div class="form-group">
				<label for="Nome do Usuário">Nome</label>
				<input type="text" name="nome_user" class="form-control" id="nome_user" 
				placeholder="Nome do usuário" maxlength="50" 
				value="<?php if(isset($_POST['nome_user'])) echo $_POST['nome_user']; ?>" 
				pattern="^[^/\s0-9][a-zA-ZÀ-ú/\s]{5,79}$" title="Digite o nome do usuario(5-79 Letras)." required>
				</div>
				
				<div class="form-group">
					<label for="Login do Usuário">Login </label>
					<input type="text" name="login_user" class="form-control" id="login_user"
					placeholder="Login" maxlength="20" size="20"
					value="<?php if(isset($_POST['login_user'])) echo $_POST['login_user'] ?>" 
					title="Digite o nome do usuario(3-20 letras e/ou numeros)." 
					pattern="^[^/\s][a-zA-ZÀ-ú/\s0-9]{2,20}$" required>
				</div>
				
				<div class="form-group">
					<label for="Senha do Usuário">Senha( Min 5 Max 20 dígitos) - Letras e/ou números:</label>
					<input type="password" name="password_user" class="form-control" id="password_user" minlength="5"
					maxlength= "20" size="20" title="Digite uma senha (MIN 5 - MAX 20 digitos" 
					patter="^[^/\s]+.{4,20}$" placeholder="*****************" 
					value="<?php if(isset($_POST['password_user'])) echo $_POST['password_user'] ?>" required> 		
				</div>	  
				
			<div class="form-group">
				<label for="Grupo de Acesso">Grupo de Acesso</label>
				<select name="grupoacesso" class="form-control" id="grupoacesso">
					<!-- GRUPO DE ACESSO -->
					<?php while($registro_grupoacesso = mysqli_fetch_array($exec_querygrupoacesso)){ ?>
					<option value="<?=$registro_grupoacesso['GRUPOACESSO_ID']?>" 
					<?php if($registro_grupoacesso['GRUPOACESSO_ATIVO']==0) echo 'class="table-dark"';?>
					<?php if($registro_grupoacesso['NM_GRUPOACESSO'] == "PADRAO") echo "selected"; ?>>
					<?=$registro_grupoacesso['NM_GRUPOACESSO']?></option>
					
				<?php }?> 
				</select>
				</div>
					
				<div class="form-group">
				<input type="hidden" name="saveFormAddUser" value="sucess">
				<button type="submit" class="btn btn-success my-1">Salvar</button>
				</div>
				
			</form>
		</div>
		</div>

	</body>
</html>
<?php
