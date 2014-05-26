<div class="kutu" style="position:relative;">
<?php
if($eklenti_baslik_goster)
{
	echo '<div class="modul_ust">'. $eklenti_baslik .'</div>' . "\n";
	echo '<div class="modul_ic">' . "\n";
}
?>
<?php if(eklenti_ayar('icerik_kategorileri', 'tip') == '1' || eklenti_ayar('icerik_kategorileri', 'tip') == NULL) { ?>
	<?php
		$icerikler_sorgu = $this->eklentiler_icerik_kategorileri_model->icerik_listele();
		//$icerikler_kategori_sorgu = $this->eklentiler_icerik_kategorileri_model->kategori_detay();
		if($icerikler_sorgu) {
			$information_type = config('information_types');
	?>
	<div class="yan_kategori_liste">
		<ul>
			<?php foreach($icerikler_sorgu as $icerikler) { ?>
			<li>
				<a href="<?php echo site_url(strtr($information_type['information']['url'], array('{url}' => $icerikler->seo))); ?>"><?php echo character_limiter($icerikler->title, 40); ?></a>
			</li>
			<?php } ?>
		</ul>
		<?php /*if(count($icerikler_sorgu)) { ?>
		<a style="color:#515151;font-weight:bold;display:block;border-top:solid 1px #dedede;padding-top:3px;" href="<?php echo site_url(strtr($information_type['information']['cat_url'], array('{url}' => $icerikler_kategori_sorgu->seo))); ?>"><?php echo lang('messages_extension_information_all_information'); ?></a>
		<?php }*/ ?>
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