
<div class = "paginacao">
	<div class="record_total hide-mobile">
		Total 
		<strong class="record-total-quantity"><?=$totalNumRows?></strong>
		Registro(s)
	</div>

		<ul class="pagination pagination-list no-print" style="float:right">
			<!--INICIO ANTERIOR-->
			<?php if($paginaAtual==1){$estilo_disabled = "page-item disabled";}else{$estilo_disabled = "page-item";}?>
			
			<li class="page <?=$estilo_disabled?> desktop-hide">
				<a class="page-link" href="?page=<?php echo $paginaAtual-1;?>" tabindex="-1"><img src="images/icon-left.png"></a>
			</li>
			
			<li class="page <?=$estilo_disabled?> hide-mobile">
				<a class="page-link" href="?page=<?php echo $paginaAtual-1;?>" tabindex="-1"><img src="images/icon-left.png" style="margin-right:5px">Anterior</a>
			</li>
			<!--FIM ANTERIOR-->
			
			<!--INICIO PRIMEIRA PAGINA-->
			<?php if($paginaAtual==1) {$estiloPagAtual="page-item active";}else $estiloPagAtual = "page-item";?>
				
				<?php if(($paginaAtual == $totalPaginas || $totalPaginas == 0) & $paginaAtual == 1) {$displaynone = "display:none";} else{$displaynone="";}?>
				
				<li class="page <?=$estiloPagAtual?>" style="<?=$displaynone?>"><a class="page-link" href="?page=<?php echo 1; ?>"><?php echo 1;?></a></li>
				
				<?php if($paginaAtual >= 4){?><div class="pagination-separator"><span>...</span></div> <?php }?>
				
			<!--FIM PRIMEIRA PAGINA-->
			
			<!--INICIO PAGINA DO MEIO-->
			<?php 
			//Verificando se é dispositivo mobile
			$UserMobile = new mobile();
			$IsMobile = $UserMobile->ismobile();
			
			if($IsMobile) {$lim = 2;}else{$lim = 3;};
			
			$inicio = ((($paginaAtual - $lim) > 1) ? $paginaAtual - $lim : 1);
			
			$fim = ((($paginaAtual+$lim) < $totalPaginas) ? $paginaAtual+$lim : $totalPaginas);
			
			if($totalPaginas > 1 && $paginaAtual <= $totalPaginas){
				for($i = $inicio+1; $i <= $fim-1; $i++){
				
					if($paginaAtual==$i) {$estiloPagAtual="page-item active";}else $estiloPagAtual = "page-item";?>
							
					<li class="page <?=$estiloPagAtual?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
					
				<?php } ?>
			<?php } ?>
				
			<!--FIM PAGINA DO MEIO-->
			
			<!--INICIO ULTIMA PAGINA-->	
			<?php if($paginaAtual < $totalPaginas-2){?><div class="pagination-separator"><span>...</span></div> <?php }?>
			
			<?php if($paginaAtual==$totalPaginas) {
					$estiloPagAtual="page-item active";
				}else $estiloPagAtual = "page-item";
				
				for($i=1; $i <= $totalPaginas;$i++){
					if($paginaAtual+$i == $totalPaginas) {$displaynone = "display:none";} else{$displaynone="";}
				}
				?>								
				
				<li class="page <?=$estiloPagAtual?>" style="<?=$displaynone?>"><a class="page-link" href="?page=<?php echo $totalPaginas; ?>"><?php echo $totalPaginas;?></a></li>
			<!--FIM ULTIMA PAGINA-->
			
			<!--INICIO PROXIMO-->	
			<?php if($paginaAtual==$totalPaginas || $totalPaginas == 0){
					$estiloDisabled = "page-item disabled";
				}else{$estiloDisabled = "page-item";}?>
				
				<li class="page <?=$estiloDisabled?> desktop-hide">
					<a class="page-link" href="?page=<?php echo $paginaAtual+1;?>" tabindex="-1"><img src="images/icon-right.png"></a>
				</li>
				
				<li class="page <?=$estiloDisabled?> hide-mobile">
					<a class="page-link" href="?page=<?php echo $paginaAtual+1;?>">Próximo<img src="images/icon-right.png" style="margin-left:5px"></a>
				</li>
			<!--FIM PROXIMO-->
		</ul>
</div><!--fim Div Pagination-->
<hr class="no-print hide-mobile horizontal-separator">