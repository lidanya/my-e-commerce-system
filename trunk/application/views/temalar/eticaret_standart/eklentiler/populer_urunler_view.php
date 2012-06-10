<div class="kutu" style="position:relative;">
<?php
	if($eklenti_baslik_goster)
	{
		echo '<div class="modul_ust"><span>'. $eklenti_baslik .'</span></div>' . "\n";
		echo '<div class="modul_ic">' . "\n";
	}
?>
<?php if(eklenti_ayar('populer_urunler', 'tip') == '1' || eklenti_ayar('populer_urunler', 'tip') == NULL) { ?>
	<?php
		$populer_urunler_sorgu = $this->eklentiler_populer_urunler_model->yeni_urunler_listele();
		if($populer_urunler_sorgu) {
	?>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#populer_urunler_1").easySlider({
				auto: 		true, 
				continuous:	true,
				prevId:		'populer_urunler_1_ileri',
				prevText:	'',
				nextId:		'populer_urunler_1_geri',
				nextText:	'',
				numericId: 	'k_populer_urunler_1'
			});
		});
	</script>
		<div class="kayan_urun" id="populer_urunler_1">
			<ul>
				<?php $i=1; foreach($populer_urunler_sorgu['query'] as $populer_urunler) { if($i <=5): ?>
				<li>
					<?php
						$sablon_gonder->product_id		= $populer_urunler->product_id;
						$sablon_gonder->model			= $populer_urunler->model;
						$sablon_gonder->name			= $populer_urunler->name;
						$sablon_gonder->new_product		= $populer_urunler->new_product;
						$sablon_gonder->quantity		= $populer_urunler->quantity;
						$sablon_gonder->seo				= $populer_urunler->seo;
						$sablon_gonder->image			= $populer_urunler->image;
						
						$sablon_gonder->durum			= 'modul-populer';
						
						
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