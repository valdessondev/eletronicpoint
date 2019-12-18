	<table id="tabelapermissoes" class="table table-bordered table-hover table-condensed">
	  <thead class="thead-dark">
		<tr>
			<th scope="col">ID</th>
			<th scope="col">DESCRIÇÃO</th>
			<th scope="col" style="padding-right:0;text-align:center" class="no-print">EDITAR</th>
			<th scope="col" style="padding-right:0;text-align:center" class="no-print">DESATIVAR</th>
		</tr>
	  </thead>
	  <tbody>


		<?php				
			if ($total > 0) {
				do {
					?>
					<tr <?php if ($registros["PERMISSOES_ATIVA"]==0) echo 'class="table-dark"'?>>
					<td><?=$registros['PERMISSOES_ID']?></td>
					<td><?=$registros['NM_PERMISSOES']?></td>
					<td class="no-print">
						<form class="form-inline" method="POST" action="edita_permissao.php" id="form_dados" style = "text-align:center"> 								
							<input type="hidden" value="<?=$registros['PERMISSOES_ID']?>" name="code_permissao">									
							<input type="image" src="../images/btn_edit_profile.png" name="Editar">
						</form>	
					</td>
					<td class = "no-print" style="text-align:center">
							<a href="#" class="<?=$class_btn?>" onclick="registrationStatus(<?=$registros['PERMISSOES_ID']?>,'lista_permissoes','<?=$acao?>');">
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
