<!--orta -->
<div id="orta" class="sola">
	
	<!-- FILTRE BAŞLADI  -->
	<form action="<?php echo face_site_url('urun/arama/index') ?>" method="get" id="filtre" accept-charset="utf-8" target="_top">
		
		<input type="hidden" name="aranan" value="<?php echo _get('aranan') ?>" />
		
	<div id="ara_tab_cont">
		<a id="marka" href="javascript:;" class="sola"><?php echo lang('messages_product_search_brands'); ?></a>
		<a id="kategori" href="javascript:;" class="sola ara_tab_aktif"><?php echo lang('messages_product_search_categories'); ?></a>
		<div class="clear"></div>
	</div>
	<div id="ara_modul">
		<!--Markalar -->
		<div id="ara_marka">
			<div>			
				<?php foreach($manufacturers as $key => $value) { ?>
					<?php $check = (in_array($key, $get_manufacturers)) ? 'checked="checked"' : NULL; ?>
					<i><input type="checkbox" name="manufacturer[]" value="<?php echo $key ?>" <?php echo $check; ?> /> <?php echo $value ?></i>
				<?php } ?>
			</div>
			<div class="clear"></div>
			<a href="javascript:;" class="ara_tumu"><?php echo lang('messages_product_search_all'); ?></a>
		</div>
		<!--Kategoriler -->
		<div id="ara_kategori">
			<div>
				<?php foreach($categories as $key => $value) { ?>
					<?php $check = (in_array($key, $get_categories)) ? 'checked="checked"' : NULL; ?>
					<i><input type="checkbox" name="category[]" value="<?php echo $key ?>" <?php echo $check; ?> /> <?php echo $value ?></i>
				<?php } ?>
			</div>
			<div class="clear"></div>
			<a href="javascript:;" class="ara_tumu"><?php echo lang('messages_product_search_all'); ?></a>
		</div>
		<!-- fiyat araligi -->
		<div id="ara_fiyat">
			<div id="af_sol" class="sola">
				<i><?php echo lang('messages_product_search_price_range') ?> : </i>
				<div id="af_fiyatlar" class="sola">
					<span id="af_min" fiyat="<?php echo (float) $fiyat->min_fiyat; ?>"><span id="af_min_fiyat"></span> <input type="hidden" name="min_fiyat" id="min_fiyat" value="<?php echo _get('min_fiyat', 0); ?>" /></span>
					<b> ~ </b>
					<span id="af_max" fiyat="<?php echo (float) $fiyat->max_fiyat; ?>"><span id="af_max_fiyat"></span> <input type="hidden" name="max_fiyat" id="max_fiyat" value="<?php echo _get('max_fiyat', 0); ?>" /></span>
				</div>
				<div class="clear"></div>
			</div>
			<div id="af_sag" class="saga">
				<div id="af_bar">
					<div id="af_gosterge"></div>
					<div id="af_left" step="<?php echo _get('step_left', 10); ?>"><div id="af_sola"></div></div>
					<input type="hidden" name="step_left" value="<?php echo _get('step_left', 10); ?>" id="step_left">
					<div id="af_right" step="<?php echo _get('step_right', 400); ?>"><div id="af_saga"></div></div>
					<input type="hidden" name="step_right" value="<?php echo _get('step_right', 400); ?>" id="step_right">
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div id="af_selects">
			<span class="af_uzun">
				<?php echo form_dropdown('tip', $tip, _get('tip', '0')) ?>
			</span>
			<span class="af_orta">
				<?php echo form_dropdown('sort_by', $sort_by, _get('sort_by', '6')) ?>
			</span>
			<span class="af_orta">
				<?php echo form_dropdown('limit', $per_page, _get('limit', '20')) ?>
			</span>
			<a class="butonum" href="javascript:;" onclick="form_submit();">
				<span class="butsol"></span>
				<span class="butor"><?php echo lang('messages_product_search_button'); ?></span>
				<span class="butsag"></span>
			</a>
			<div class="clear"></div>
		</div>
	</div>
	<div id="ara_tab_alt"></div>
	
	</form>
	<!-- FILTRE BİTTİ  -->
	
<!--orta -->
<?php 
if($arama_sonuc AND $arama_sonuc['toplam'] > 0)
{
?>
	<!-- Arama Sonucu Varsa -->
	<h1 id="sayfa_baslik"><?php echo lang('messages_product_search_title'); ?></h1>
	<!--Sayfalama varsa baş-->
	<?php
		if($pagination) {
			echo $pagination['links'];
		}
	?>
	<!--Sayfalama varsa son-->
	<div class="clear"></div>
	<?php
		if($arama_sonuc) {
		?>
			<!--Ürün Listele-->
			<div class="liste_container">
			<?php
				$sii = 0;
				foreach($arama_sonuc['sorgu']->result() as $arama) {
					$k = $sii%2;
					if($k == 0) {
						$div_class = ' ud_gri';
					} else {
						$div_class = NULL;
					}

					$sablon_gonder->product_id		= $arama->product_id;
					$sablon_gonder->model			= $arama->model;
					$sablon_gonder->name			= $arama->name;
					$sablon_gonder->new_product		= $arama->new_product;
					$sablon_gonder->quantity		= $arama->quantity;
					$sablon_gonder->seo				= $arama->seo;
					$sablon_gonder->image			= $arama->image;
					$sablon_gonder->div_class 		= $div_class;

					$this->product_model->stock_shema($sablon_gonder, 'normal_liste', 'face');
					$sii++;
				}
			?>
			<!--Ürün Listele Son-->
	<?php 
		}
	?>
	</div>
	<div class="clear"></div>
	<!--Sayfalama varsa baş-->
	<?php
		if($pagination) {
			echo $pagination['links'];
		}
	?>
	<!--Sayfalama varsa son-->
	<!-- Arama Sonucu Varsa SON-->
	<div class="clear"></div>
<?php } else { ?>
	<!--Arama Sonucu Yoksa -->
	<h1 id="sayfa_baslik"><?php echo lang('messages_product_search_title'); ?></h1>
	<div id="sonuc_yok">
		<div class="sola" style="margin-left:30px;"><img src="<?php echo face_resim();?>unlem.png" alt="dikkat" title="Sonuç Bulunamadı"></div>
		<ul  class="sola">
			<?php
				$kelime = '<b title="'. strip_tags($aranan_kelime) .'">'. character_limiter(strip_tags($aranan_kelime), 50) .'</b>';
				echo strtr(lang('messages_product_search_keywords'), array('{kelime}' => $kelime));
			?>
			<li><?php echo lang('messages_product_search_valid_words'); ?></li>
			<li><?php echo lang('messages_product_search_similar_words'); ?></li>
			<li><?php echo lang('messages_product_search_generic_words'); ?></li>
		</ul>
		<div class="clear"></div>
	</div>
	<!--Arama Sonucu Yoksa SON-->
<?php } ?>

<!--orta son -->
</div>
<script type="text/javascript" charset="utf-8">
	function form_submit() {
		var step_left = $('#af_left').attr('step');
		var step_right = $('#af_right').attr('step');
		var min_price = $('#af_min_fiyat').html();
		var max_price = $('#af_max_fiyat').html();

		$('#min_fiyat').val(min_price);
		$('#max_fiyat').val(max_price);
		$('#step_left').val(step_left);
		$('#step_right').val(step_right);
		$('#filtre').submit();
	}
</script>