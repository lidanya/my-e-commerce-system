<div id="orta" class="sola">
	<!-- Ödeme Seçenekleri -->
	<h1 id="sayfa_baslik"><?php echo lang('messages_static_page_payment_options_title'); ?></h1>
	<div id="bs_container">

		<div class="page_ust"></div>
		<div class="page_ic">
			<span class="bs_baslik"><?php echo lang('messages_static_page_payment_options_information'); ?>:</span>
			
			<?php
				$this->db->order_by('odeme_sira', 'asc');
				$sorgu = $this->db->get_where('odeme_secenekleri', array('odeme_durum' => '1'));
				if($sorgu->num_rows()) {
					foreach($sorgu->result() as $odeme_secenekleri) {

						$_unserialize_baslik = @unserialize($odeme_secenekleri->odeme_baslik);
						$unserialize_baslik = isset($_unserialize_baslik[get_language('language_id')]) ? $_unserialize_baslik[get_language('language_id')] : NULL;

						$_unserialize_aciklama = @unserialize($odeme_secenekleri->odeme_aciklama);
						$unserialize_aciklama = isset($_unserialize_aciklama[get_language('language_id')]) ? $_unserialize_aciklama[get_language('language_id')] : NULL;

						echo '<div class="os_oge">';
						echo '<span class="os_tablo01 sola"><img src="' . site_resim() . $odeme_secenekleri->odeme_resim . '.png" alt="'. $odeme_secenekleri->odeme_baslik .'"></span>';
						echo '<span class="os_tablo02 saga">';
						echo '<span class="os_baslik">'. $unserialize_baslik .'</span>';
						echo '<span class="os_yazi">'. $unserialize_aciklama .'</span>';
						echo '</span>';
						echo '<div class="clear"></div>';
						echo '</div>';
					}
				}
			?>
		</div>
		<div class="page_alt"></div>
	</div>
</div>