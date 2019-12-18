
	<?php
	
	if ($hora_atual >= "00:00" && $tb_registro['HORA_ENTRADA'] == "") {
			if($qtd_max_em_pausa > 0) {
				echo $msg_limite_pausa;
				
			}else{
				echo $botao_saida_intervalo;
			}

	}elseif ($tb_registro['HORA_ENTRADA'] != "" && $tb_registro['HORA_SAIDA_INTERVALO'] == "") {

		if($tempoEmPausa < 15) echo $msg_pausa_ativa;
		else echo $botao_volta_intervalo;

	}else {
		echo "<div class='alert alert-info' role='alert'>Você não pode registrar ponto nesse horário.</div>";
	}
	?>
	
	