<div class="kutu" style="position:relative;">
<?php
if($eklenti_baslik_goster)
{
	echo '<div class="modul_ust">'. $eklenti_baslik .'</div>' . "\n";
	echo '<div class="modul_ic">' . "\n";
}
?>
<?php if(eklenti_ayar('indirimli_urunler', 'tip') == '1' || eklenti_ayar('indirimli_urunler', 'tip') == NULL) { ?>
	<?php
		$indirimli_urunler_sorgu = $this->eklentiler_indirimli_urunler_model->indirimli_urunler_listele();
		if($indirimli_urunler_sorgu) {
	?>
	<script type="text/javascript">
	$(document).ready(function(){	
		$("#indirimli_urunler_1").easySlider({
			auto: 		true, 
			continuous:	true,
			prevId:		'k_indirimli_urunler_1_ileri',
			prevText:	'',
			nextId:		'k_indirimli_urunler_1_geri',	
			nextText:	'',
			numericId: 	'k_indirimli_urunler_1'
		});
	});
	</script>
		<div class="kayan_urun" id="indirimli_urunler_1">
			<ul>
				<?php $i = 1; foreach($indirimli_urunler_sorgu['query'] as $indirimli_urunler) { if($i <=5): ?>
				<li>
					<?php
						$sablon_gonder->product_id		= $indirimli_urunler->product_id;
						$sablon_gonder->model			= $indirimli_urunler->model;
						$sablon_gonder->name			= $indirimli_urunler->name;
						$sablon_gonder->new_product		= $indirimli_urunler->new_product;
						$sablon_gonder->quantity		= $indirimli_urunler->quantity;
						$sablon_gonder->seo				= $indirimli_urunler->seo;
						$sablon_gonder->image			= $indirimli_urunler->image;
						$sablon_gonder->date_end		= $indirimli_urunler->date_end;
						
						$sablon_gonder->durum		= 'modul-indirimli';
						
						$this->product_model->stock_shema($sablon_gonder, 'kayan_liste');
					?>
				</li>
				<?php endif; ++$i; } ?>
			</ul>
		</div>
	<?php } ?>
<?php } else if(eklenti_ayar('indirimli_urunler', 'tip') == '2') { ?>
	<?php
		$indirimli_urunler_sorgu = $this->eklentiler_indirimli_urunler_model->indirimli_urunler_listele();
		if($indirimli_urunler_sorgu) {
	?>
	<div class="yan_urun_liste">
		<ul>
			<?php foreach($indirimli_urunler_sorgu['query'] as $indirimli_urunler) { ?>
				<?php
					$sablon_gonder->product_id		= $indirimli_urunler->product_id;
					$sablon_gonder->model			= $indirimli_urunler->model;
					$sablon_gonder->name			= $indirimli_urunler->name;
					$sablon_gonder->new_product		= $indirimli_urunler->new_product;
					$sablon_gonder->quantity		= $indirimli_urunler->quantity;
					$sablon_gonder->seo				= $indirimli_urunler->seo;
					$sablon_gonder->image			= $indirimli_urunler->image;
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