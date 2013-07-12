<div class="kutu" style="position:relative;z-index:997;">
<?php
if($eklenti_baslik_goster)
{
	echo '<div class="modul_ust">'. $eklenti_baslik .'</div>' . "\n";
	echo '<div class="modul_ic" style="padding:0;width:218px;">' . "\n";
}
?>
<?php if(eklenti_ayar('kategori', 'tip') == '1' || eklenti_ayar('kategori', 'tip') == NULL) { ?>
	<?php
		$kategoriler = $this->eklentiler_kategori_model->kategori_listele(0);
		if($kategoriler) {
	?>
<div id="kategori_menu01">
	<ul>
		<?php foreach($kategoriler as $kategori) { ?>
			<?php
				$kategori_seo_name = str_replace('--category', '', $this->uri->segment(2));
				$aktif = NULL;
				$parts = explode('---', $kategori_seo_name);
				if($parts[0] == $kategori->seo) {
					$aktif = 'class="k_aktif"';
				} else {
					$aktif = NULL;
				}
			?>
		<li><a <?php echo $aktif; ?> href="<?php echo site_url($kategori->seo . '--category'); ?>" title="<?php echo $kategori->name; ?>"><?php echo character_limiter($kategori->name, 28); ?></a></li>
		<?php } ?>
	</ul>
</div>
	<?php } ?>
<?php } else if(eklenti_ayar('kategori', 'tip') == '2' || eklenti_ayar('kategori', 'tip') == NULL) { ?>
	<?php
		$kategoriler = $this->eklentiler_kategori_model->kategori_listele(0);
		if($kategoriler) {
	?>

<div id="kategori_menu02">
	<ul>
		<?php foreach($kategoriler as $kategori) { ?>
			<?php
				$kategori_seo_name = str_replace('--category', '', $this->uri->segment(2));
				$aktif = NULL;
				$parts = explode('---', $kategori_seo_name);
				if($parts[0] == $kategori->seo) {
					$aktif = 'class="k_aktif"';
				} else {
					$aktif = NULL;
				}
			?>
		<li>
			<li><a <?php echo $aktif; ?> href="<?php echo site_url($kategori->seo . '--category'); ?>" title="<?php echo $kategori->name; ?>"><?php echo character_limiter($kategori->name, 28); ?></a>
			<ul>
				<?php
					$a_kategoriler = $this->eklentiler_kategori_model->kategori_listele($kategori->category_id);
					if($a_kategoriler) {
						foreach($a_kategoriler as $a_kategori) {
							$kategori_seo_name = str_replace('--category', '', $this->uri->segment(2));
							$a_aktif = NULL;
							$parts = explode('---', $kategori_seo_name);
							if($parts[0] == $a_kategori->seo) {
								$a_aktif = 'class="k_aktif"';
							} else {
								$a_aktif = NULL;
							}
							echo '<li><a '. $a_aktif .' href="'. site_url($kategori->seo . '---' . $a_kategori->seo . '--category') .'" title="'. $a_kategori->name .'">'. character_limiter($a_kategori->name, 15) .'</a>';
						}
					} else {
						$a_kategori_urunler = $this->eklentiler_kategori_model->kategori_urun_listele($kategori->category_id);
						if($a_kategori_urunler) {
							if($a_kategori_urunler['total']) {
								foreach($a_kategori_urunler['query'] as $a_kategori_urunler) {
									$urun_seo_name = str_replace('--product', '', $this->uri->segment(2));
									$a_u_aktif = NULL;
									$parts = explode('---', $urun_seo_name);
									if($parts[0] == $a_kategori_urunler->seo) {
										$a_u_aktif = 'class="k_aktif"';
									} else {
										$a_u_aktif = NULL;
									}
									echo '<li><a '. $a_u_aktif .' href="'. site_url($a_kategori_urunler->seo . '--product') .'" title="'. $a_kategori_urunler->name .'">'. character_limiter($a_kategori_urunler->name, 15) .'</a>';
								}
							}
						}
					}
				?>
			</ul>
		</li>
			<?php } ?>
	</ul>
</div>
	<?php } ?>
<?php } else if(eklenti_ayar('kategori', 'tip') == '3' || eklenti_ayar('kategori', 'tip') == NULL) { ?>
<?php
	$kategoriler = $this->eklentiler_kategori_model->kategori_listele(0);
	if($kategoriler) {
?>
<div id="kategori_menu03">
	<ul>
		<?php foreach($kategoriler as $kategori) { ?>
			<?php
				$kategori_seo_name = str_replace('--category', '', $this->uri->segment(2));
				$aktif = NULL;
				$parts = explode('---', $kategori_seo_name);
				if($parts[0] == $kategori->seo) {
					$aktif = 'class="k_aktif"';
				} else {
					$aktif = NULL;
				}
			?>
		<li>
			<li><a <?php echo $aktif; ?> href="<?php echo site_url($kategori->seo . '--category'); ?>" title="<?php echo $kategori->name; ?>"><?php echo character_limiter($kategori->name, 28); ?></a>
			<ul>
				<?php
					$a_kategoriler = $this->eklentiler_kategori_model->kategori_listele($kategori->category_id);
					if($a_kategoriler) {
						foreach($a_kategoriler as $a_kategori) {
							$kategori_seo_name = str_replace('--category', '', $this->uri->segment(2));
							$a_aktif = NULL;
							$parts = explode('---', $kategori_seo_name);
							if($parts[0] == $a_kategori->seo) {
								$a_aktif = 'class="k_aktif"';
							} else {
								$a_aktif = NULL;
							}
							echo '<li><a '. $a_aktif .' href="'. site_url($kategori->seo . '---' . $a_kategori->seo . '--category') .'" title="'. $a_kategori->name .'">'. character_limiter($a_kategori->name, 15) .'</a>';
						}
					} else {
						$a_kategori_urunler = $this->eklentiler_kategori_model->kategori_urun_listele($kategori->category_id);
						if($a_kategori_urunler) {
							if($a_kategori_urunler['total']) {
								foreach($a_kategori_urunler['query'] as $a_kategori_urunler) {
									$urun_seo_name = str_replace('--product', '', $this->uri->segment(2));
									$a_u_aktif = NULL;
									$parts = explode('---', $urun_seo_name);
									if($parts[0] == $a_kategori_urunler->seo) {
										$a_u_aktif = 'class="k_aktif"';
									} else {
										$a_u_aktif = NULL;
									}
									echo '<li><a '. $a_u_aktif .' href="'. site_url($a_kategori_urunler->seo . '--product') .'" title="'. $a_kategori_urunler->name .'">'. character_limiter($a_kategori_urunler->name, 15) .'</a>';
								}
							}
						}
					}
				?>
			</ul>
		</li>
			<?php } ?>
	</ul>
</div>
	<?php } ?>
<?php } else if(eklenti_ayar('kategori', 'tip') == '4' || eklenti_ayar('kategori', 'tip') == NULL) { ?>

<?php
	$kategoriler = $this->eklentiler_kategori_model->kategori_listele(0);
	if($kategoriler) {
?>

<script type="text/javascript">
$(document).ready(function(){
	$("#kategori_menu04").treeview({
		animated: "fast",
		persist: "cookie"
	});
});
</script>

<ul id="kategori_menu04">
	<?php foreach($kategoriler as $kategori) { ?>
		<li>
			<a href="<?php echo site_url($kategori->seo . '--category'); ?>" title="<?php echo $kategori->name; ?>"><span><?php echo character_limiter($kategori->name, 28); ?></span></a>
			<?php
				$alt_kategoriler = $this->eklentiler_kategori_model->kategori_listele($kategori->category_id);
				if($alt_kategoriler) {
			?>
				<ul>
					<?php foreach($alt_kategoriler as $alt_kategori) { ?>
						<li>
							<a href="<?php echo site_url($kategori->seo . '---' . $alt_kategori->seo . '--category'); ?>" title="<?php echo $alt_kategori->name; ?>"><span><?php echo character_limiter($alt_kategori->name, 28); ?></span></a>
							<?php
								$alt_alt_kategoriler = $this->eklentiler_kategori_model->kategori_listele($alt_kategori->category_id);
								if($alt_alt_kategoriler) {
							?>
								<ul>
									<?php foreach($alt_alt_kategoriler as $alt_alt_kategori) { ?>
										<li>
											<a href="<?php echo site_url($kategori->seo . '---' . $alt_kategori->seo . '---' . $alt_alt_kategori->seo . '--category'); ?>" title="<?php echo $alt_alt_kategori->name; ?>"><span><?php echo character_limiter($alt_alt_kategori->name, 28); ?></span></a>
											<?php
												$alt_alt_alt_kategoriler = $this->eklentiler_kategori_model->kategori_listele($alt_alt_kategori->category_id);
												if($alt_alt_alt_kategoriler) {
											?>
												<ul>
													<?php foreach($alt_alt_alt_kategoriler as $alt_alt_alt_kategori) { ?>
														<li>
															<a href="<?php echo site_url($kategori->seo . '---' . $alt_kategori->seo . '---' . $alt_alt_kategori->seo . '---' . $alt_alt_alt_kategori->seo . '--category'); ?>" title="<?php echo $alt_alt_alt_kategori->name; ?>"><span><?php echo character_limiter($alt_alt_alt_kategori->name, 28); ?></span></a>
														</li>
													<?php } ?>
												</ul>
											<?php } ?>
										</li>
									<?php } ?>
								</ul>
							<?php } ?>
						</li>
					<?php } ?>
				</ul>
			<?php } ?>
		</li>
	<?php } ?>
</ul>

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