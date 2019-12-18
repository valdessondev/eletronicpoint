	<table id="tabelaUsuariosCadastrados" class="table table-bordered table-hover table-condensed">
	  <thead class="thead-dark">
		<tr>						  
			<th scope="col">NOME	</th>
			<th scope="col">LOGIN</th>
			<th scope="col" style="padding-right:0;text-align:center" class="no-print">EDITAR</th>
			<th scope="col" style="padding-right:0;text-align:center" class="no-print">DESATIVAR</th>
		</tr>
	  </thead>
	  <tbody>


		<?php				
			if ($total > 0) {
				do {
					?>
					<tr <?php if ($registros["USER_ATIVO"]==0) echo 'class="table-dark"'?>>
					<td><?=$registros['NOME']?></td>
					<td><?=$registros['LOGIN']?></td>
					<td class="no-print">
						<form class="form-inline" method="POST" action="edita_usuario.php" id="form_dados" style = "text-align:center"> 								
							<input type="hidden" value="<?=$registros['ID']?>" name="codigo_user">									
							<input type="image" src="../images/btn_edit_profile.png" name="Editar">
						</form>	
					</td>
					<td class = "no-print" style="text-align:center">
					
						<?php if($_SESSION['LOGIN'] != $registros['LOGIN']){?>
						<a href="#" class="<?=$class_btn?>" onclick="registrationStatus(<?=$registros['ID']?>,'lista_usuarios','<?=$acao?>');">
								<img src="../images/<?=$class_btn?>.png">	
						</a>
						<?php } ?>
					</td>
					</tr>

					<?php
				} while ($registros = mysqli_fetch_assoc($exec_query));
				}else echo $msg_semregistros;
	
		?>

	</tbody>
	</table>
