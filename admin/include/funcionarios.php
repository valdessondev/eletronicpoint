	<table id="tabelaFuncionariosCadastrados" class="table table-bordered table-hover table-condensed">
	  <thead class="thead-dark">
		<tr>						  
			<th scope="col">CODIGO</th>
			<th scope="col">NOME</th>
			<th scope="col">FUNCAO</th>			
			<th scope="col">EMPRESA</th>
			<th scope="col">TIPO</th>
			<th scope="col" style="padding-right:0;text-align:center" class="no-print">EDITAR</th>
			<th scope="col" style="padding-right:0;text-align:center" class="no-print">DESATIVAR</th>
		</tr>
		</thead>
		<tbody>

		<?php			
			
		if ($totalNumRows > 0) {
			do {
				?>
				<tr <?php if ($registros["FUNC_ATIVO"]==0) echo 'class="table-dark"'?>>
					<td><?=$registros['CODIGO']?></td>
					<td><?=$registros['NOME']?></td>							
					<td><span><?=$registros['NM_FUNCAO']?></span></td>
					<td><span><?=$registros['NM_EMPRESA']?></span></td>					
					<td><?=$registros['NM_TIPOCONTRATO']?></td>
					<td class="no-print">
						<form class="form-inline" method="POST" action="edita_funcionario.php" id="form_dados" style = "text-align:center"> 								
							<input type="hidden" value="<?=$registros['CODIGO']?>" name="codigo_info">									
							<input type="image" src="../images/btn_edit_profile.png" name="editar">
						</form>	
					</td>
					<td class = "no-print">
						<a href="#" class="<?=$class_btn?>" onclick="registrationStatus(<?=$registros['CODIGO']?>,'lista_funcionarios','<?=$acao?>');">
							<img src="../images/<?=$class_btn?>.png">	
						</a>
					</td>
				</tr>

				<?php
			} while ($registros = mysqli_fetch_assoc($exec_query));
			}else echo $msg_semregistros;
	
			?>
		</tbody>
	</table>
