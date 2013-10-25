<div id="orta" class="sola">
	<!-- Banka Bilgileri -->
	<h1 id="sayfa_baslik"><?php echo lang('messages_static_page_bank_information_title'); ?></h1>
	<div id="bs_container">
		
		<div class="page_ust"></div>
		<div class="page_ic">
			<span class="bs_baslik"><?php echo lang('messages_static_page_bank_information_banks'); ?>:</span>
			<div class="bb_oge bb_baslik">
				<span class="bb_tablo01 sola" style="padding-top:8px;"><?php echo lang('messages_static_page_bank_information_bank_text'); ?></span>
				<span class="bb_tablo02 sola"><?php echo lang('messages_static_page_bank_information_branch'); ?></span>
				<span class="bb_tablo03 sola"><?php echo lang('messages_static_page_bank_information_account_information'); ?></span>
				<span class="bb_tablo04 sola"><?php echo lang('messages_static_page_bank_information_iban_number'); ?></span>
				<div class="clear"></div>
			</div>
			<?php
			
			$this->db->order_by('odeme_secenek_havale.havale_sira', 'asc');
			$this->db->select('odeme_secenek_havale.*, odeme_secenek_havale_detay.*');
			$this->db->join('odeme_secenek_havale', 'odeme_secenek_havale_detay.banka_id = odeme_secenek_havale.havale_id');
			$havale_sorgu = $this->db->get_where('odeme_secenek_havale_detay', array('odeme_secenek_havale.havale_durum' => '1', 'odeme_secenek_havale_detay.hesap_durum' => '1'));
			if($havale_sorgu->num_rows() > 0)
			{
				$i = 0;
				foreach($havale_sorgu->result() as $havaleler)
				{
					$banka_logo = ($i == 1) ? '<img src="' . face_resim() . $havaleler->havale_banka_resim . '" alt="'. $havaleler->havale_banka_baslik .'">':'&nbsp;';
					
					$sube = ($havaleler->sube != '') ? $havaleler->sube:'&nbsp;';
					$hesap_no = ($havaleler->hesap_no != '') ? $havaleler->hesap_no:'&nbsp;';
					$iban_no = ($havaleler->iban_no != '') ? $havaleler->iban_no:'&nbsp;';

					if($havaleler->hesap_sahip != '')
					{
						echo '<div style="margin-left:10px;">'. $havaleler->hesap_sahip .'</div>' . "\n";
					}
					echo '<div class="bb_oge">' . "\n";
					echo '<span class="bb_tablo01 sola">'. '<img src="' . face_resim() . $havaleler->havale_banka_resim . '" alt="'. $havaleler->havale_banka_baslik .'">' .'</span>' . "\n";
					echo '<span class="bb_tablo02 sola">'. $sube .'</span>' . "\n";
					echo '<span class="bb_tablo03 sola">'. $hesap_no .'</span>' . "\n";
					echo '<span class="bb_tablo04 sola">'. $iban_no .'</span>' . "\n";
					echo '<div class="clear"></div>' . "\n";
					echo '</div>' . "\n";
					$i++;
				}
			} else {
				echo '<div class="bb_oge">' . "\n";
				echo '<span class="bb_tablo01 sola">'. '&nbsp;' . "\n";
				echo '<span class="bb_tablo02 sola">'. '&nbsp;' .'</span>' . "\n";
				echo '<span class="bb_tablo03 sola">'. '&nbsp;' .'</span>' . "\n";
				echo '<span class="bb_tablo04 sola">'. '&nbsp;' .'</span>' . "\n";
				echo '<div class="clear"></div>' . "\n";
				echo '</div>' . "\n";
			}
			?>
		</div>
		<div class="page_alt"></div>
	</div>
</div>