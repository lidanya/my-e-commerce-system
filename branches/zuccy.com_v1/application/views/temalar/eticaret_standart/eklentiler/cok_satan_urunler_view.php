<div class="kutu" style="position:relative;">
<?php
	if($eklenti_baslik_goster)
	{
		echo '<div class="modul_ust"><span>'. $eklenti_baslik .'</span></div>' . "\n";
		echo '<div class="modul_ic">' . "\n";
	}
?>
<?php if(eklenti_ayar('cok_satan_urunler', 'tip') == '1' || eklenti_ayar('cok_satan_urunler', 'tip') == NULL) { ?>
	<?php
		$cok_satan_urunler_sorgu = $this->eklentiler_cok_satan_urunler_model->yeni_urunler_listele();
		if($cok_satan_urunler_sorgu) {
	?>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#cok_satan_urunler_1").easySlider({
				auto: 		true, 
				continuous:	true,
				prevId:		'cok_satan_urunler_1_ileri',
				prevText:	'',
				nextId:		'cok_satan_urunler_1_geri',
				nextText:	'',
				numericId: 	'k_cok_satan_urunler_1'
			});
		});
	</script>
		<div class="kayan_urun" id="cok_satan_urunler_1">
			<ul>
				
				<?php $sablon_gonder = new stdClass(); ?>
				<?php $i=1; foreach($cok_satan_urunler_sorgu['query'] as $cok_satan_urunler) { if($i <=5): ?>
				<li>
					<?php
						$sablon_gonder->product_id		= $cok_satan_urunler->product_id;
						$sablon_gonder->model			= $cok_satan_urunler->model;
						$sablon_gonder->name			= $cok_satan_urunler->name;
						$sablon_gonder->new_product		= $cok_satan_urunler->new_product;
						$sablon_gonder->quantity		= $cok_satan_urunler->quantity;
						$sablon_gonder->seo				= $cok_satan_urunler->seo;
						$sablon_gonder->image			= $cok_satan_urunler->image;
						
						$sablon_gonder->durum			= 'modul-cok-satan';
						
						
						$this->product_model->stock_shema($sablon_gonder, 'kayan_liste');
					?>
				</li>
				<?php endif; ++$i; } ?>
			</ul>
		</div>
	<?php } ?>
<?php } ?>
<?php
if($eklenti_baslik_goster)
{
	echo '</div>' . "\n";
	echo '<div class="modul_alt"></div>' . "\n";
}
?>
</div>