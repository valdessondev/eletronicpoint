<?php include '../config/conn.php';
	
	$url = isset($_POST['vurl']) ? $_POST['vurl']: "";
	
	$save = new salvar_dados();
				
	switch($url){
		case "relatorios":
			$novoValor = $_POST['vnovoConteudo'];
			$codigo = $_POST['vID']; 
			$data = $_POST['vdata'];
			$coluna = $_POST['vcoluna'];
			$ValorAntigo = $_POST['vconteudooriginal'];
			
			if($novoValor!="" && $novoValor != $ValorAntigo ){
				$save->atualizaColuna($novoValor,$codigo,$data,$coluna,$conn);		
				$save->organizaHorario($codigo,$data, $conn);		
			}
			break;
				
		case "lista_funcionarios":
			$acao = isset($_POST['vacao']) ? $_POST['vacao']: "";
			$ID = isset($_POST['vID']) ? $_POST['vID']: "";
			
			switch($acao){
				case "active":
					$save->ativaFuncionario($ID, $conn);
					break;
				case "desabled":
					$save->desativaFuncionario($ID, $conn);
					break;
			}
			break;
		case "lista_usuarios":
			$acao = isset($_POST['vacao']) ? $_POST['vacao']: "";
			$ID = isset($_POST['vID']) ? $_POST['vID']: "";
			
			switch($acao){
				case "active":
					$save->ativaUser($ID, $conn);
					break;
				case "desabled":
					$save->desativaUser($ID, $conn);
					break;
			}
			break;
		case "lista_funcoes":
			$acao = isset($_POST['vacao']) ? $_POST['vacao']: "";
			$ID = isset($_POST['vID']) ? $_POST['vID']: "";
			
			switch($acao){
				case "active":
					$save->ativaFuncao($ID, $conn);
					break;
				case "desabled":
					$save->desativaFuncao($ID, $conn);
					break;
			}
			break;
		case "lista_empresas":
			$acao = isset($_POST['vacao']) ? $_POST['vacao']: "";
			$ID = isset($_POST['vID']) ? $_POST['vID']: "";
			
			switch($acao){
				case "active":
					$save->ativaEmpresa($ID, $conn);
					break;
				case "desabled":
					$save->desativaEmpresa($ID, $conn);
					break;
			}
			break;
		case "lista_tipocontrato":
			$acao = isset($_POST['vacao']) ? $_POST['vacao']: "";
			$ID = isset($_POST['vID']) ? $_POST['vID']: "";
			
			switch($acao){
				case "active":
					$save->ativaTipocontrato($ID, $conn);
					break;
				case "desabled":
					$save->desativaTipocontrato($ID, $conn);
					break;
			}
			break;
		case "lista_grupoacesso":
			$acao = isset($_POST['vacao']) ? $_POST['vacao']: "";
			$ID = isset($_POST['vID']) ? $_POST['vID']: "";
			
			switch($acao){
				case "active":
					$save->ativaGrupoacesso($ID, $conn);
					break;
				case "desabled":
					$save->desativaGrupoacesso($ID, $conn);
					break;
			}
		break;
		case "lista_permissoes":
			$acao = isset($_POST['vacao']) ? $_POST['vacao']: "";
			$ID = isset($_POST['vID']) ? $_POST['vID']: "";
			
			switch($acao){
				case "active":
					$save->ativaPermissoes($ID, $conn);
					break;
				case "desabled":
					$save->desativaPermissoes($ID, $conn);
					break;
			}
		break;
	}
				
	class salvar_dados{

			function organizaHorario($codigo,$data, $conn){
				//selecionando as informações
				$query = "SELECT HORA_ENTRADA,HORA_SAIDA_INTERVALO,HORA_RETORNO_INTERVALO,
				HORA_SAIDA, HORA_SAIDA_PAUSA, HORA_VOLTA_PAUSA FROM REGISTROS WHERE CODIGO = '$codigo' AND DATA = '$data'";
				$exec_query = mysqli_query($conn,$query);
				$registros = mysqli_fetch_assoc($exec_query);
				$total = count($registros);
				
				sort($registros);	
				
				$atualiza = "UPDATE REGISTROS SET ALTERADO = '1', HORA_ENTRADA = '$registros[0]', HORA_SAIDA_INTERVALO = '$registros[1]',
				HORA_RETORNO_INTERVALO = '$registros[2]', HORA_SAIDA = '$registros[3]', HORA_SAIDA_PAUSA = '$registros[4]',
				HORA_VOLTA_PAUSA = '$registros[5]' WHERE CODIGO = '$codigo' AND DATA = '$data'";
				$executar = mysqli_query($conn, $atualiza);
			}
				
			function atualizaColuna($novoValor,$codigo,$data,$coluna,$conn){
				$atualiza2 = "UPDATE REGISTROS SET $coluna = '$novoValor' WHERE CODIGO = $codigo AND DATA = '$data'";
				$executar = mysqli_query($conn, $atualiza2);
			}
			
			function desativaFuncionario($ID, $conn){
				
				$desativa = "UPDATE FUNCIONARIO SET FUNC_ATIVO = 0, DT_ALTERACAO = CURRENT_TIMESTAMP WHERE CODIGO = $ID";
				$executa = mysqli_query($conn, $desativa);
			}
			
			function ativaFuncionario($ID, $conn){
			
				$reativa = "UPDATE FUNCIONARIO SET FUNC_ATIVO = 1, DT_ALTERACAO = CURRENT_TIMESTAMP WHERE CODIGO = $ID";
				$executa = mysqli_query($conn, $reativa);
			}
			
			function desativaUser($ID, $conn){
				
				$desativa = "UPDATE USERS SET USER_ATIVO = 0, DT_ALTERACAO = CURRENT_TIMESTAMP WHERE ID = $ID";
				$executa = mysqli_query($conn, $desativa);
			}
			
			function ativaUser($ID, $conn){
			
				$reativa = "UPDATE USERS SET USER_ATIVO = 1, DT_ALTERACAO = CURRENT_TIMESTAMP WHERE ID = $ID";
				$executa = mysqli_query($conn, $reativa);
			}
			function desativaFuncao($ID, $conn){
				
				$desativa = "UPDATE FUNCOES SET FUNCAO_ATIVA = 0, DT_ALTERACAO = CURRENT_TIMESTAMP WHERE FUNCAO_ID = $ID";
				$executa = mysqli_query($conn, $desativa);
			}
			
			function ativaFuncao($ID, $conn){
			
				$reativa = "UPDATE FUNCOES SET FUNCAO_ATIVA = 1, DT_ALTERACAO = CURRENT_TIMESTAMP WHERE FUNCAO_ID = $ID";
				$executa = mysqli_query($conn, $reativa);
			}
			function desativaEmpresa($ID, $conn){
				
				$desativa = "UPDATE EMPRESAS SET EMPRESA_ATIVA = 0, DT_ALTERACAO = CURRENT_TIMESTAMP WHERE EMPRESA_ID = $ID";
				$executa = mysqli_query($conn, $desativa);
			}
			function ativaEmpresa($ID, $conn){
				
				$ativa = "UPDATE EMPRESAS SET EMPRESA_ATIVA = 1, DT_ALTERACAO = CURRENT_TIMESTAMP WHERE EMPRESA_ID = $ID";
				$executa = mysqli_query($conn, $ativa);
			}
			function desativaTipocontrato($ID, $conn){
				
				$desativa = "UPDATE TIPOCONTRATO SET TIPOCONTRATO_ATIVO = 0, DT_ALTERACAO = CURRENT_TIMESTAMP WHERE TIPOCONTRATO_ID = $ID";
				$executa = mysqli_query($conn, $desativa);
			}
			function ativaTipocontrato($ID, $conn){
				
				$ativa = "UPDATE TIPOCONTRATO SET TIPOCONTRATO_ATIVO = 1, DT_ALTERACAO = CURRENT_TIMESTAMP WHERE TIPOCONTRATO_ID = $ID";
				$executa = mysqli_query($conn, $ativa);
			}
			function desativaGrupoacesso($ID, $conn){
				
				$desativa = "UPDATE GRUPOACESSO SET GRUPOACESSO_ATIVO = 0, DT_ALTERACAO = CURRENT_TIMESTAMP WHERE GRUPOACESSO_ID = $ID";
				$executa = mysqli_query($conn, $desativa);
			}
			function ativaGrupoacesso($ID, $conn){
				
				$ativa = "UPDATE GRUPOACESSO SET GRUPOACESSO_ATIVO = 1, DT_ALTERACAO = CURRENT_TIMESTAMP WHERE GRUPOACESSO_ID = $ID";
				$executa = mysqli_query($conn, $ativa);
			}
			function desativaPermissoes($ID, $conn){
				
				$desativa = "UPDATE PERMISSOES SET PERMISSOES_ATIVA = 0, DT_ALTERACAO = CURRENT_TIMESTAMP WHERE PERMISSOES_ID = $ID";
				$executa = mysqli_query($conn, $desativa);
			}
			function ativaPermissoes($ID, $conn){
				
				$ativa = "UPDATE PERMISSOES SET PERMISSOES_ATIVA = 1, DT_ALTERACAO = CURRENT_TIMESTAMP WHERE PERMISSOES_ID = $ID";
				$executa = mysqli_query($conn, $ativa);
			}

	}
//FONTES
////https://www.treinaweb.com.br/blog/conheca-os-principais-algoritmos-de-ordenacao/
//http://www.linhadecodigo.com.br/artigo/3336/algoritmo-de-ordenacao-bubble-sort-php.aspx











