<div class="kutu" style="position:relative;">
<?php
if($eklenti_baslik_goster)
{
	echo '<div class="modul_ust">'. $eklenti_baslik .'</div>' . "\n";
	echo '<div class="modul_ic">' . "\n";
}
?>
<?php if(eklenti_ayar('duyurular', 'tip') == '1' || eklenti_ayar('duyurular', 'tip') == NULL) { ?>
	<?php
		$duyurular_sorgu = $this->eklentiler_duyurular_model->duyurular_listele();
		if($duyurular_sorgu) {
			$information_type = config('information_types');
	?>
	<div class="yan_icerik_liste">
		<ul>
			<?php foreach($duyurular_sorgu as $duyurular) { ?>
			<li>
				<a href="<?php echo site_url(strtr($information_type['announcement']['url'], array('{url}' => $duyurular->seo))); ?>"><img src="<?php echo show_image($duyurular->image, 50, 50); ?>" alt="<?php echo $duyurular->title; ?>" title="<?php echo $duyurular->title; ?>" /></a>
				<a class="y_i_listebaslik" href="<?php echo site_url(strtr($information_type['announcement']['url'], array('{url}' => $duyurular->seo))); ?>"><?php echo character_limiter(strip_tags($duyurular->description), 40); ?></a>
			</li>
			<?php } ?>
		</ul>
		<a href="<?php echo site_url($information_type['announcement']['all_url']); ?>"><?php echo lang('messages_extension_announcement_all_announcement'); ?></a>
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