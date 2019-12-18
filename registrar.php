<?php

require_once("config/conn.php");

$saida_primeira_pausa = date("H:i");
$volta_primeira_pausa = date("H:i");
$saida_intervalo = date("H:i");
$volta_intervalo = date("H:i");
$saida_ultima_pausa = date("H:i");
$saida_volta_ultima_pausa = date("H:i");
$data = date("Y/m/d");
$hora_atual = date("H:i");
$codigo = $_POST['codigo'];
$qtdBatidas = $_POST['quantidadeDeBatidas'];

//limpa os campos sinalizando que o usuário não está mais em pausa, faz varredura nos ultimos 7 registros
$zerapausaativa = "UPDATE
						REGISTROS 
					SET 
						PAUSA_ATIVA = 0, HORA_PAUSA_ATIVA = NULL, DESCRICAO_PAUSA_ATIVA = NULL 
					WHERE 
						CODIGO = '$codigo' 
					ORDER BY ID DESC LIMIT 7";

//verificação de preenchimento
$query_consulta = "SELECT 
						HORA_ENTRADA, HORA_SAIDA_INTERVALO,
						HORA_RETORNO_INTERVALO,HORA_SAIDA,
						HORA_SAIDA_PAUSA,HORA_VOLTA_PAUSA
					FROM 
						REGISTROS 
					WHERE 
						DATA = '$data' 
					AND 
						CODIGO = '$codigo'
					LIMIT 1";
$executa_query_consulta = mysqli_query($conn,$query_consulta);
$registro = mysqli_fetch_assoc($executa_query_consulta);

if ($registro['HORA_ENTRADA'] == NULL) {
		
		switch($qtdBatidas){
			
			case 2: //Saída para o intervalo
				$insert = 				
				"INSERT INTO 
					REGISTROS(CODIGO, DATA, HORA_ENTRADA,HORA_RETORNO_INTERVALO,HORA_SAIDA,HORA_SAIDA_PAUSA,
					HORA_VOLTA_PAUSA, PAUSA_ATIVA, HORA_PAUSA_ATIVA,DESCRICAO_PAUSA_ATIVA)
				VALUES 
					('$codigo','$data','$hora_atual','xxx','xxx','xxx','xxx',1,'$hora_atual', 'INTERVALO')";
				$descricaopausa = "SAIDA INTERVALO";
				break;
			
			case 4: //Inicio do expediente 
				$insert = 
					"INSERT INTO 
						REGISTROS(CODIGO, DATA, HORA_ENTRADA, HORA_SAIDA_PAUSA, HORA_VOLTA_PAUSA)
					VALUES 
						('$codigo','$data','$hora_atual','xxx','xxx')";
					$descricaopausa = "ENTRADA";
				break;
			
			case 6: //Primeira pausa
				$insert = 
					"INSERT INTO 
						REGISTROS(CODIGO, DATA, HORA_ENTRADA, PAUSA_ATIVA, HORA_PAUSA_ATIVA, DESCRICAO_PAUSA_ATIVA)
					VALUES 
						('$codigo','$data','$hora_atual',1,'$hora_atual','PRIMEIRA PAUSA')";
				$descricaopausa = "SAIDA PRIMEIRA PAUSA";
				break;
		}

		$executar = mysqli_query($conn, $insert) or die('Erro inesperado: Contate administrador.<br>Codigo: 130 ->' . mysqli_error($conn));

		//Exibindo mensagem de confirmação
		if($executar){
			$_SESSION["MSG_REGISTER"] = $descricaopausa."<br>Registrado(a) com sucesso as<br>".$hora_atual;
			@header("Location: sair.php");

		}

	}elseif ($registro['HORA_ENTRADA'] != "" && $registro['HORA_SAIDA_INTERVALO'] == "") {
			switch($qtdBatidas){
				
				case 2://Volta do intervalo
					$update = "UPDATE REGISTROS SET HORA_SAIDA_INTERVALO = '$hora_atual', HORA_PAUSA_ATIVA = NULL, 
					DESCRICAO_PAUSA_ATIVA = NULL  WHERE DATA = '$data' AND CODIGO = '$codigo'";
					$descricaopausa = "VOLTA INTERVALO";
					break;
				
				case 4: //Saida para almoco
					$update = "UPDATE REGISTROS SET HORA_SAIDA_INTERVALO = '$hora_atual', HORA_PAUSA_ATIVA = '$hora_atual',
					DESCRICAO_PAUSA_ATIVA = 'SAIDA ALMOÇO', PAUSA_ATIVA = 1  WHERE DATA = '$data' AND CODIGO = '$codigo'";
					$descricaopausa = "SAIDA ALMOÇO";
					break;
				
				case 6: //Volta do intervalo
					$update = "UPDATE REGISTROS SET HORA_SAIDA_INTERVALO = '$hora_atual', HORA_PAUSA_INATIVA = '$hora_atual'
					WHERE DATA = '$data' AND CODIGO = '$codigo'";
					$descricaopausa = "VOLTA PRIMEIRA PAUSA";
					break;
					
			}
			//Limpando os campos da pausa
			if ($qtdBatida != 4) mysqli_query($conn,$zerapausaativa);
			//Executadno o update
			$executar = mysqli_query($conn, $update) or die('Erro inesperado: Contate administrador.<br>Codigo: 131 ->' . mysqli_error($conn));
			
		//Exibindo mensagem de confirmação
		if($executar){
			$_SESSION["MSG_REGISTER"] = $descricaopausa."<br>Registrado(a) com sucesso as<br>".$hora_atual;
			@header("Location: sair.php");
	
		}

	}elseif ($registro['HORA_SAIDA_INTERVALO'] != "" && $registro['HORA_RETORNO_INTERVALO'] == "") {
		
			switch($qtdBatidas){
				
				case 4: //Volta do Almoço
					$update = "UPDATE REGISTROS SET HORA_RETORNO_INTERVALO = '$hora_atual' WHERE DATA = '$data' AND CODIGO = '$codigo'";
					$descricaopausa = "VOLTA ALMOCO";
					break;
					
				case 6://Saída para o intervalo
					$update = "UPDATE REGISTROS SET HORA_RETORNO_INTERVALO = '$hora_atual', PAUSA_ATIVA = 1,
					HORA_PAUSA_ATIVA = '$hora_atual', DESCRICAO_PAUSA_ATIVA = 'INTERVALO' WHERE DATA = '$data' AND CODIGO = '$codigo'";
					$descricaopausa = "SAIDA INTERVALO";
					break;
			}
			//Limpando os campos de pausa 
			if ($qtdBatidas == 4) mysqli_query($conn,$zerapausaativa);

			//Executando o update
			$executar = mysqli_query($conn, $update) or die('Erro inesperado: Contate administrador.<br>Codigo: 132 ->' . mysqli_error($conn));
			
			//Exibindo mensagem de confirmação
			if($executar){
				$_SESSION["MSG_REGISTER"] = $descricaopausa."<br>Registrado(a) com sucesso as<br>".$hora_atual;
				@header("Location: sair.php");
		
			}

	} elseif ($registro['HORA_RETORNO_INTERVALO'] != "" && $registro['HORA_SAIDA'] == "") {
		
		switch($qtdBatidas){
				
				case 4: //Encerramento do expediente
					$update = "UPDATE REGISTROS SET HORA_SAIDA = '$hora_atual' WHERE DATA = '$data' AND CODIGO = '$codigo'";
					$descricaopausa = "SAIDA";
					break;				
				case 6://Volta do intervalo
					$update = "UPDATE REGISTROS SET HORA_SAIDA = '$hora_atual', HORA_PAUSA_INATIVA = '$hora_atual' WHERE DATA = '$data' AND CODIGO = '$codigo'";
					$descricaopausa = "VOLTA INTERVALO";
					break;
		}
		//Executando o update
		$executar = mysqli_query($conn, $update) or die('Erro inesperado: Contate administrador.<br>Codigo: 133 ->' . mysqli_error($conn));
		//Limpando os campos da pausa
		mysqli_query($conn,$zerapausaativa);

		//Exibindo mensagem de confirmação
		if($executar){
			$_SESSION["MSG_REGISTER"] = $descricaopausa."<br>Registrado(a) com sucesso as<br>".$hora_atual;
			@header("Location: sair.php");
	
		}

	}elseif ($registro['HORA_SAIDA'] != "" && $registro['HORA_SAIDA_PAUSA'] == "") {
		
		switch($qtdBatidas){
			
			case 6://Saída para última pausa
				$update = "UPDATE REGISTROS SET HORA_SAIDA_PAUSA = '$hora_atual', PAUSA_ATIVA = 1,
				HORA_PAUSA_ATIVA = '$hora_atual', DESCRICAO_PAUSA_ATIVA = 'ULTIMA PAUSA' 
				WHERE DATA = '$data' AND CODIGO = '$codigo'";
				$descricaopausa = "SAIDA ULTIMA PAUSA";
				break;
			}

		//Executadno o update
		$executar = mysqli_query($conn, $update) or die('Erro inesperado: Contate administrador.<br>Codigo: 134 ->' . mysqli_error($conn));

		//Exibindo mensagem de confirmação
		if($executar){
			$_SESSION["MSG_REGISTER"] = $descricaopausa."<br>Registrado(a) com sucesso as<br>".$hora_atual;
			@header("Location: sair.php");
	
		}

	}elseif ($registro['HORA_SAIDA_PAUSA'] != "" && $registro['HORA_VOLTA_PAUSA'] == "") {

			switch($qtdBatidas){
				
				case 6: //Volta da ultima pausa
					$update = "UPDATE REGISTROS SET HORA_VOLTA_PAUSA = '$hora_atual', HORA_PAUSA_INATIVA = '$hora_atual' 
					WHERE DATA = '$data' AND CODIGO = '$codigo'";				
					$descricaopausa = "VOLTA ULTIMA PAUSA";
					break;
			}

			//Limpando os campos da pausa
			mysqli_query($conn,$zerapausaativa);
			//Executando o update
			$executar = mysqli_query($conn, $update) or die('Erro inesperado: Contate administrador.<br>Codigo: 135 ->' . mysqli_error($conn));

			//Exibindo mensagem de confirmação
			if($executar){
				$_SESSION["MSG_REGISTER"] = $descricaopausa."<br>Registrado(a) com sucesso as<br>".$hora_atual;
				@header("Location: sair.php");
		
			}
	}else {
		echo "<br>Erro! Favor informar o adiministrador do sistema.";
	}

?>