	
	<?php	
	if ($hora_atual >= "00:00" && $tb_registro['HORA_ENTRADA'] == "") {
		
		if($qtd_max_em_pausa > 0) {
			echo $msg_limite_pausa;
		}else{
			echo $botao_saida_primeira_pausa;
		}

	}elseif ($tb_registro['HORA_ENTRADA'] != "" && $tb_registro['HORA_SAIDA_INTERVALO'] == "") {

		//Se o tempo que o usuário estiver em pausa for menor que o tempo minimo da pausa ele não poderá bater o ponto
		if($tempoEmPausa < 10) {echo $msg_pausa_ativa;}
		//Senão exibe o botão para registrar a volta
		else {echo $botao_volta_primeira_pausa;}
	}
	elseif ($tb_registro['HORA_SAIDA_INTERVALO'] != "" && $tb_registro['HORA_RETORNO_INTERVALO'] == "") {
		
		if($qtd_max_em_pausa > 0) {echo $msg_limite_pausa;}
		//Se o tempo que o usuário estiver em pausa for menor que o tempo mínimo ENTRE as pausas, ele não poderá bater o ponto
		elseif($tempoDesdeAUltimaPausa < $tmpMinEntrePausas) {echo $msg_tmp_min_pausas;}
		//Senão exibe o botão para registro do intervalo
		else{echo $botao_saida_intervalo;}
	}
	elseif ($tb_registro['HORA_RETORNO_INTERVALO'] != "" && $tb_registro['HORA_SAIDA'] == "") {
			if($tempoEmPausa < 15) echo $msg_pausa_ativa;
			else echo $botao_volta_intervalo;
	}
	elseif ($tb_registro['HORA_SAIDA'] != "" && $tb_registro['HORA_SAIDA_PAUSA'] == ""){
		
		if($qtd_max_em_pausa > 0) {echo $msg_limite_pausa;}
		//Se o tempo que o usuário estiver em pausa for menor que o tempo mínimo ENTRE as pausas, ele não poderá bater o ponto
		elseif($tempoDesdeAUltimaPausa < $tmpMinEntrePausas) {echo $msg_tmp_min_pausas;}
		//Senão exibe o botão para registro do intervalo
		else{echo $botao_saida_ultima_pausa;}
	}
	elseif ($tb_registro['HORA_SAIDA_PAUSA'] != "" && $tb_registro['HORA_VOLTA_PAUSA'] == ""){
		if($tempoEmPausa < 10) echo $msg_pausa_ativa;
		else echo $botao_volta_ultima_pausa;
	}
	else {
		echo "<div class='alert alert-info' role='alert'>Você não pode registrar ponto nesse horário.</div>";
	}
	?>
	
	