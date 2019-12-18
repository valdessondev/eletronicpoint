	<table id="tabelagrupoacesso" class="table table-bordered table-hover table-condensed">
	  <thead class="thead-dark">
		<tr>
			<th scope="col">ID</th>
			<th scope="col">DESCRIÇÃO</th>
			<th scope="col" style="padding-right:0;text-align:center" class="no-print">EDITAR</th>
		</tr>
	  </thead>
	  <tbody>


		<?php				
			if ($total > 0) {
				do {
					?>
					<tr <?php if ($registros["GRUPOACESSO_ATIVO"]==0) echo 'class="table-dark"'?>>
						<td><?=$registros['GRUPOACESSO_ID']?></td>
						<td><?=$registros['NM_GRUPOACESSO']?></td>
						<td class="no-print">
							<form class="form-inline" method="POST" action="edita_grupoacesso.php" id="form_dados" style = "text-align:center"> 								
								<input type="hidden" value="<?=$registros['GRUPOACESSO_ID']?>" name="code_grupoacesso">									
								<input type="image" src="../images/btn_edit_profile.png" name="Editar">
							</form>	
						</td>
					</tr>

					<?php
				} while ($registros = mysqli_fetch_assoc($exec_query));
				}else echo $msg_semregistros;
	
		?>

	</tbody>
	</table>
