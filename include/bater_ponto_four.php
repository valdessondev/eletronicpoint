<?php
	if ($hora_atual >= "00:00" && $tb_registro['HORA_ENTRADA'] == "") {
		//Entrada no Traabalho
		echo $botao_inicio_expediente;
		
	}elseif ($tb_registro['HORA_ENTRADA'] != "" && $tb_registro['HORA_SAIDA_INTERVALO'] == "") {
		//Saida do Almoço		
			echo $botao_saida_almoco;
		
	}elseif ($tb_registro['HORA_SAIDA_INTERVALO'] != "" && $tb_registro['HORA_RETORNO_INTERVALO'] == "") {
		//Volta do Almoço
		if($tempoEmPausa < 60) echo $msg_pausa_ativa;
		else echo $botao_volta_almoco;
		
			
	}elseif ($tb_registro['HORA_RETORNO_INTERVALO'] != "" && $tb_registro['HORA_SAIDA'] == "") {
		//Fim do Expediente
		echo  $botao_fim_expediente;		
			
	}else {
		echo "<div class='alert alert-info' role='alert'>Você não pode registrar ponto nesse horário.</div>";
	}
	?>
	
	