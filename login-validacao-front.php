<?php
require_once('config/conn.php');
require_once("class/anti_injection-class.php");

 //Verifica se o codigo foi preenchido
 if(isset($_POST['inputcodigo']) && $_POST['inputcodigo'] !="")
 {
	//Instanciando o objeto da classe anti_injection
	$valida = new anti_injection();

	//Recupera o codigo
	$codigo = $valida->anti_injection_exec($_POST['inputcodigo']);

	//Consulta no Banco de Dados se existe o login
	$query_consulta = "SELECT 
							F.CODIGO,F.EMPRESA,F.TIPO_FUNCIONARIO,
							F.TURNO,F.FUNC_ATIVO,F.NOME, E.EMPRESA_ATIVA,
							FF.FUNCAO_ATIVA, TC.TIPOCONTRATO_ATIVO
						 FROM 
						 	FUNCIONARIO AS F
						INNER JOIN 
							EMPRESAS AS E
						ON 
							F.EMPRESA = E.EMPRESA_ID
						INNER JOIN 
							FUNCOES AS FF
						ON 
							F.FUNCAO = FF.FUNCAO_ID
						INNER JOIN 
							TIPOCONTRATO AS TC
						ON
							F.TIPO_FUNCIONARIO = TC.TIPOCONTRATO_ID
						WHERE 
							CODIGO = '$codigo' 
						LIMIT 1";
	$executa_query_consulta = mysqli_query($conn,$query_consulta) or die('Consulta funcionario falhou: ' . mysqli_error($conn));
	$funcionario = mysqli_fetch_assoc($executa_query_consulta);

	if($funcionario > 0)
	{
		//Verificando se o funcionário está ativo ou não
		if($funcionario['FUNC_ATIVO'] == 1  && $funcionario['EMPRESA_ATIVA'] == 1
		&& $funcionario['FUNCAO_ATIVA'] == 1 && $funcionario['TIPOCONTRATO_ATIVO'] == 1){

			//sessão do tempo para expirar começa aqui..
			$tempolimite = 1800; //equivale a 30 minutos 1800
			$_SESSION['TIME_REGISTER_FUNC'] = time();
			$_SESSION['TIME_LIMITE_FUNC'] = $tempolimite;

			$_SESSION['CODIGO_FUNC'] = $funcionario['CODIGO'];
			$_SESSION['NOME_FUNC'] = $funcionario['NOME'];
			$_SESSION['EMPRESA'] = $funcionario['EMPRESA'];
			$_SESSION['TIPO_FUNCIONARIO'] = $funcionario['TIPO_FUNCIONARIO'];
			$_SESSION['TURNO'] = $funcionario['TURNO'];
			$_SESSION['FUNC_ATIVO'] = $funcionario['FUNC_ATIVO'];

			//Destruindo o codigo do fomulário e mensagem
			unset ($_SESSION['CODIGO_FORM']) ;
			unset( $_SESSION["MSG_LOGIN"] );
			
			@header("Location: bater_ponto.php");

		}elseif($funcionario['FUNC_ATIVO'] == 0){//Se o funcionário não estiver ativo ele emite uma mensagem
			$_SESSION["MSG_LOGIN"] = "Cadastro Desativado/Bloqueado.<br>Contate o administrador.";
			$_SESSION["CODIGO_FORM"] = $_POST['inputcodigo'];
	
			@header("Location: index.php");
		}elseif($funcionario['EMPRESA_ATIVA'] == 0){ //Verificando se a empresa está ativa

			$_SESSION["MSG_LOGIN"] = "Empresa Desativada/Bloqueada<br>Contate o administrador.";
			$_SESSION["CODIGO_FORM"] = $_POST['inputcodigo'];
	
			@header("Location: index.php");

		}elseif($funcionario['FUNCAO_ATIVA'] == 0){//Verificando se a empresa está ativa
			$_SESSION["MSG_LOGIN"] = "Função Desativada/Bloqueada.<br>Contate o administrador.";
			$_SESSION["CODIGO_FORM"] = $_POST['inputcodigo'];
	
			@header("Location: index.php");

		}elseif($funcionario['TIPOCONTRATO_ATIVO'] == 0){ //Verificando se o tipo de contrato está ativo
			$_SESSION["MSG_LOGIN"] = "Tipo de Contrato Desativado/Bloqueado.<br>Contate o administrador.";
			$_SESSION["CODIGO_FORM"] = $_POST['inputcodigo'];
	
			@header("Location: index.php");

		}else{
			$_SESSION["MSG_LOGIN"] = "Ocorreu um erro inesperado!<br>Contate o administrador.";
			$_SESSION["CODIGO_FORM"] = $_POST['inputcodigo'];
	
			@header("Location: index.php");
		}		

	}
	else//Se não existir funcionário com aquele código ele emite mensagem
	{
		$_SESSION["MSG_LOGIN"] = "Código Inválido, Tente novamente.";
		$_SESSION["CODIGO_FORM"] = $_POST['inputcodigo'];

		@header("Location: index.php");
	}

}else{
        $_SESSION["MSG_LOGIN"] = "Preenchimento obrigatório: Código.";
        $_SESSION["CODIGO_FORM_FRONT"] = $_POST['inputcodigo'];

        @header("Location: index.php");
}

?>