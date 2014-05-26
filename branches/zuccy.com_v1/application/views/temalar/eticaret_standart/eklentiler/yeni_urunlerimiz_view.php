<div class="kutu" style="position:relative;">
<?php
if($eklenti_baslik_goster)
{
	echo '<div class="modul_ust">'. $eklenti_baslik .'</div>' . "\n";
	echo '<div class="modul_ic">' . "\n";
}
?>
<?php if(eklenti_ayar('yeni_urunlerimiz', 'tip') == '1' || eklenti_ayar('yeni_urunlerimiz', 'tip') == NULL) { ?>
	<?php
		$yeni_urunlerimiz_sorgu = $this->eklentiler_yeni_urunlerimiz_model->yeni_urunler_listele();
		if($yeni_urunlerimiz_sorgu) {
	?>
	<script type="text/javascript">
	$(document).ready(function(){	
		$("#yeni_urunlerimiz_1").easySlider({
			auto: 		true, 
			continuous:	true,
			prevId:		'yeni_urunlerimiz_1_ileri',
			prevText:	'',
			nextId:		'yeni_urunlerimiz_1_geri',	
			nextText:	'',
			numericId: 	'k_yeni_urunlerimiz_1'
		});
	});
	</script>
		<div class="kayan_urun" id="yeni_urunlerimiz_1">
			<ul>
				<?php $i =1; foreach($yeni_urunlerimiz_sorgu['query'] as $yeni_urunlerimiz) { if($i <=5):  ?>
				<li>
					<?php
						$sablon_gonder->product_id		= $yeni_urunlerimiz->product_id;
						$sablon_gonder->model			= $yeni_urunlerimiz->model;
						$sablon_gonder->name			= $yeni_urunlerimiz->name;
						$sablon_gonder->new_product		= $yeni_urunlerimiz->new_product;
						$sablon_gonder->quantity		= $yeni_urunlerimiz->quantity;
						$sablon_gonder->seo				= $yeni_urunlerimiz->seo;
						$sablon_gonder->image			= $yeni_urunlerimiz->image;
						
						$sablon_gonder->durum			= 'modul-yeni';
						$this->product_model->stock_shema($sablon_gonder, 'kayan_liste');
					?>
				</li>
				<?php endif; ++$i; } ?>
			</ul>
		</div>
	<?php } ?>
<?php } else if(eklenti_ayar('yeni_urunlerimiz', 'tip') == '2') { ?>
	<?php
		$yeni_urunlerimiz_sorgu = $this->eklentiler_yeni_urunlerimiz_model->yeni_urunler_listele();
		if($yeni_urunlerimiz_sorgu) {
	?>
	<div class="yan_urun_liste">
		<ul>
			<?php foreach($yeni_urunlerimiz_sorgu['query'] as $yeni_urunlerimiz) { ?>
				<?php
					$sablon_gonder->product_id		= $yeni_urunlerimiz->product_id;
					$sablon_gonder->model			= $yeni_urunlerimiz->model;
					$sablon_gonder->name			= $yeni_urunlerimiz->name;
					$sablon_gonder->new_product		= $yeni_urunlerimiz->new_product;
					$sablon_gonder->quantity		= $yeni_urunlerimiz->quantity;
					$sablon_gonder->seo				= $yeni_urunlerimiz->seo;
					$sablon_gonder->image			= $yeni_urunlerimiz->image;
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