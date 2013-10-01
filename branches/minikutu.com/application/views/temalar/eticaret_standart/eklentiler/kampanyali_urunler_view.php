<div class="kutu" style="position:relative;">
<?php
if($eklenti_baslik_goster)
{
	echo '<div class="modul_ust">'. $eklenti_baslik .'</div>' . "\n";
	echo '<div class="modul_ic">' . "\n";
}
?>
<?php if(eklenti_ayar('kampanyali_urunler', 'tip') == '1' || eklenti_ayar('kampanyali_urunler', 'tip') == NULL) { ?>
	<?php
		$kampanyali_urunler_sorgu = $this->eklentiler_kampanyali_urunler_model->kampanyali_urunler_listele();
		if($kampanyali_urunler_sorgu) {
	?>
	<script type="text/javascript">
	$(document).ready(function(){	
		$("#kampanyali_urunler_1").easySlider({
			auto: 		true, 
			continuous:	true,
			prevId:		'k_kampanyali_urunler_1_ileri',
			prevText:	'',
			nextId:		'k_kampanyali_urunler_1_geri',	
			nextText:	'',
			numericId: 	'k_kampanyali_urunler_1'
		});
	});
	</script>
		<div class="kayan_urun" id="kampanyali_urunler_1">
			<ul>
				<?php $i =1; foreach($kampanyali_urunler_sorgu['query'] as $kampanyali_urunler) { if($i <=5): ?>
				<li>
					<?php
						$sablon_gonder->product_id		= $kampanyali_urunler->product_id;
						$sablon_gonder->model			= $kampanyali_urunler->model;
						$sablon_gonder->name			= $kampanyali_urunler->name;
						$sablon_gonder->new_product		= $kampanyali_urunler->new_product;
						$sablon_gonder->quantity		= $kampanyali_urunler->quantity;
						$sablon_gonder->seo				= $kampanyali_urunler->seo;
						$sablon_gonder->image			= $kampanyali_urunler->image;
						$sablon_gonder->date_end			= $kampanyali_urunler->date_end;
						
						$sablon_gonder->durum			= 'modul-kampanya';
						
						$this->product_model->stock_shema($sablon_gonder, 'kayan_liste');
					?>
				</li>
				<?php endif; ++$i; } ?>
			</ul>
		</div>
	<?php } ?>
<?php } else if(eklenti_ayar('kampanyali_urunler', 'tip') == '2') { ?>
	<?php
		$kampanyali_urunler_sorgu = $this->eklentiler_kampanyali_urunler_model->kampanyali_urunler_listele();
		if($kampanyali_urunler_sorgu) {
	?>
	<div class="yan_urun_liste">
		<ul>
			<?php foreach($kampanyali_urunler_sorgu['query'] as $kampanyali_urunler) { ?>
				<?php
					$sablon_gonder->product_id		= $kampanyali_urunler->product_id;
					$sablon_gonder->model			= $kampanyali_urunler->model;
					$sablon_gonder->name			= $kampanyali_urunler->name;
					$sablon_gonder->new_product		= $kampanyali_urunler->new_product;
					$sablon_gonder->quantity		= $kampanyali_urunler->quantity;
					$sablon_gonder->seo				= $kampanyali_urunler->seo;
					$sablon_gonder->image			= $kampanyali_urunler->image;
					$this->product_model->stock_shema($sablon_gonder, 'kayan_liste_2');
				?>
			<?php } ?>
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