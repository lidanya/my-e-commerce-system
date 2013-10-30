<div class="menubg">
	<?php
		$kategoriler = $this->eklentiler_kategori_model->kategori_listele(0);
		if($kategoriler) {
	?>
	<div id="Menu">
	<?php foreach($kategoriler as $key=>$kategori) { $separator_if = count($kategoriler)-1 == $key ? false : true;  ?>
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
    	<a href="<?php echo site_url($kategori->seo . '--category'); ?>" title="<?php echo $kategori->name; ?>"><?php echo character_limiter($kategori->name, 28); ?></a><?php if($separator_if): ?><span class="kat-seperator"></span><?php endif; ?>
       <?php } ?>
	</div>
	<?php } ?>
</div> 