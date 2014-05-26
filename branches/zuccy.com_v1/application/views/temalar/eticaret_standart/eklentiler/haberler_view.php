<div class="kutu" style="position:relative;">
<?php
if($eklenti_baslik_goster)
{
	echo '<div class="modul_ust">'. $eklenti_baslik .'</div>' . "\n";
	echo '<div class="modul_ic">' . "\n";
}
?>
<?php if(eklenti_ayar('haberler', 'tip') == '1' || eklenti_ayar('haberler', 'tip') == NULL) { ?>
	<?php
		$haberler_sorgu = $this->eklentiler_haberler_model->haberler_listele();
		if($haberler_sorgu) {
			$information_type = config('information_types');
	?>
	<div class="yan_icerik_liste">
		<ul>
			<?php foreach($haberler_sorgu as $haberler) { ?>
			<li>
				<a href="<?php echo site_url(strtr($information_type['news']['url'], array('{url}' => $haberler->seo))); ?>"><img src="<?php echo show_image($haberler->image, 50, 50); ?>" alt="<?php echo $haberler->title; ?>" title="<?php echo $haberler->title; ?>" /></a>
				<a class="y_i_listebaslik sitelink" href="<?php echo site_url(strtr($information_type['news']['url'], array('{url}' => $haberler->seo))); ?>">
					<?php
						echo character_limiter(strip_tags($haberler->title), 40);
					?>
				</a>
			</li>
			<?php } ?>
		</ul>
		<a class="sitelink" href="<?php echo site_url($information_type['news']['all_url']); ?>"><?php echo lang('messages_extension_news_all_news'); ?></a>
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