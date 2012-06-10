<?php $this->load->view(tema() . 'odeme/header'); ?>

<?php
	$this->db->select_sum('stok_tfiyat');
	$toplam_fiyat_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id), 1);
	$toplam_fiyat_bilgi = $toplam_fiyat_sorgu->row();

	$this->db->order_by('odeme_sira','asc');
	$odeme_secenekleri = $this->db->get_where('odeme_secenekleri', array('odeme_durum' => '1'));
	$odeme_secenekleri_sayi = $odeme_secenekleri->num_rows();
?>

<div id="odeme_secimi">
	<div class="odeme_baslik"><?php echo lang('messages_checkout_title_payment_options'); ?></div>
	<?php if(!$odeme_secenekleri_sayi) { ?>
	<?php /*<!-- eger hic bir odeme secenegi acik degilse -->*/ ?>
	<div class="adim_info adim_sari"><?php echo lang('messages_checkout_3_information'); ?></div>
	<?php } else { ?>
	<?php echo form_open_ssl('odeme/adim_4/belirle', array('name' => 'form_devam_et', 'id' => 'form_devam_et')); ?>
	<div id="o_sol" class="sola">
		<div id="os_bilgi_bg">
			<div id="os_bilgi">
				<div id="os_rak">
					<i><?php echo lang('messages_checkout_3_sub_total'); ?></i>
					<b><?php echo format_number($toplam_fiyat_bilgi->stok_tfiyat); ?> TL</b>
					<?php if($kupon_ucret > 0) { ?>
						<i class="s_yesil"><?php echo lang('messages_checkout_3_coupon_total'); ?></i>
						<b class="s_yesil">-<?php echo format_number($kupon_ucret); ?> TL</b>
					<?php } ?>
					<i><?php echo lang('messages_checkout_3_shipping_total'); ?></i>
					<b><?php echo format_number($kargo_ucret); ?> TL</b>
					<?php $hidden = (config('site_ayar_kdv_goster') == '0') ? ' style="visibility:hidden;"':NULL; ?>
					<i<?php echo $hidden; ?>><?php echo lang('messages_checkout_3_vat_total'); ?></i>
					<b<?php echo $hidden; ?>><?php echo format_number($toplam_kdv_fiyati); ?> TL</b>
					<div class="clear"></div>
				</div>
				<div id="os_toplam">
					<i><?php echo lang('messages_checkout_3_total'); ?></i>
					<b>
						<?php
							$total_price = 0;
							$total_price += $toplam_fiyat_bilgi->stok_tfiyat;
							if(config('site_ayar_kdv_goster') == '1') {
								$total_price += $toplam_kdv_fiyati;
							}
							if($kargo_ucret > 0) {
								$total_price += $kargo_ucret;
							}
							if($kupon_ucret > 0) {
								$total_price -= $kupon_ucret;
							}
							if($total_price <= 0) {
								$total_price = 0.01;
							}
							echo format_number($total_price) . ' TL';
						?>
					</b>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		<div id="os">
			<?php
				echo form_hidden('siparis_id', $siparis_id);
				echo form_hidden('fatura_id', $fatura_id);
				$i = 0;
				$this->db->order_by('odeme_sira','asc');
				$odeme_secenekleri = $this->db->get_where('odeme_secenekleri', array('odeme_durum' => '1'));
				foreach($odeme_secenekleri->result() as $odeme_secenekleri) {
					if($odeme_secenekleri->odeme_model == 'havale') {
						$this->db->where('havale_durum', '1');
						$havale_sayisi = $this->db->count_all_results('odeme_secenek_havale');
						if($havale_sayisi > 0) {
							$i++;
							$secili = ($i == '1') ? ' checked="checked"':NULL;

							$_baslik_unserialize = @unserialize($odeme_secenekleri->odeme_baslik);
							$_aciklama_unserialize = @unserialize($odeme_secenekleri->odeme_aciklama);
							$language_id = get_language('language_id');
							$baslik = (isset($_baslik_unserialize[$language_id])) ? $_baslik_unserialize[$language_id] : NULL;
							$aciklama = (isset($_aciklama_unserialize[$language_id])) ? $_aciklama_unserialize[$language_id] : NULL;

							echo '
								<div class="os_oge" onclick="tip_sec(\''. $odeme_secenekleri->odeme_model .'\');">
									<i><input type="radio" '. $secili .' class="odeme_secenekleri" onclick="tip_sec(\''. $odeme_secenekleri->odeme_model .'\');" id="odeme_secenek_'. $odeme_secenekleri->odeme_model .'" name="odeme_secenegi" value="'. $odeme_secenekleri->odeme_model .'" /></i>
									<u><img src="'. site_resim() . $odeme_secenekleri->odeme_resim . '.png" alt="'. $odeme_secenekleri->odeme_model .'" /></u>
									<p><b>'. $baslik .'</b>
								';
							if($odeme_secenekleri->odeme_indirim_orani != '00')
							{
								$cevir = array('01' => '1', '02' => '2', '03' => '3', '04' => '4', '05' => '5', '06' => '6', '07' => '7', '08' => '8', '09' => '9');
								$odeme_secenekleri->odeme_indirim_orani = strtr($odeme_secenekleri->odeme_indirim_orani, $cevir);
								$indirim_yazi = strtr(lang('messages_checkout_3_bank_transfer_discount_text'), array('{dis}' => '%' . $odeme_secenekleri->odeme_indirim_orani));
								echo '<b class="s_yesil">'. $indirim_yazi .'</b><br />';
							}
							echo '
									'. $aciklama .'
									</p>
									<div class="clear"></div>
								</div>
							';
						}
					} elseif($odeme_secenekleri->odeme_model == 'kredi_karti') {
						$this->db->where('kk_banka_durum', '1');
						$kredi_karti_sayisi = $this->db->count_all_results('odeme_secenek_kredi_karti');

						$_baslik_unserialize = @unserialize($odeme_secenekleri->odeme_baslik);
						$_aciklama_unserialize = @unserialize($odeme_secenekleri->odeme_aciklama);
						$language_id = get_language('language_id');
						$baslik = (isset($_baslik_unserialize[$language_id])) ? $_baslik_unserialize[$language_id] : NULL;
						$aciklama = (isset($_aciklama_unserialize[$language_id])) ? $_aciklama_unserialize[$language_id] : NULL;

						if($kredi_karti_sayisi > 0) {
							$i++;
							$secili = ($i == '1') ? ' checked="checked"':NULL;

							echo '
								<div class="os_oge" onclick="tip_sec(\''. $odeme_secenekleri->odeme_model .'\');">
									<i><input type="radio" '. $secili .' class="odeme_secenekleri" onclick="tip_sec(\''. $odeme_secenekleri->odeme_model .'\');" id="odeme_secenek_'. $odeme_secenekleri->odeme_model .'" name="odeme_secenegi" value="'. $odeme_secenekleri->odeme_model .'" /></i>
									<u><img src="'. site_resim() . $odeme_secenekleri->odeme_resim . '.png" alt="'. $odeme_secenekleri->odeme_model .'" /></u>
									<p><b>'. $baslik .'</b><br />'. $aciklama .'</p>
									<div class="clear"></div>
								</div>
							';
						}
					} elseif($odeme_secenekleri->odeme_model == 'kapida_odeme') {
						$i++;
						$secili = ($i == '1') ? ' checked="checked"':NULL;

						$_baslik_unserialize = @unserialize($odeme_secenekleri->odeme_baslik);
						$_aciklama_unserialize = @unserialize($odeme_secenekleri->odeme_aciklama);
						$language_id = get_language('language_id');
						$baslik = (isset($_baslik_unserialize[$language_id])) ? $_baslik_unserialize[$language_id] : NULL;
						$aciklama = (isset($_aciklama_unserialize[$language_id])) ? $_aciklama_unserialize[$language_id] : NULL;

						echo '
							<div class="os_oge" onclick="tip_sec(\''. $odeme_secenekleri->odeme_model .'\');">
								<i><input type="radio" '. $secili .' class="odeme_secenekleri" onclick="tip_sec(\''. $odeme_secenekleri->odeme_model .'\');" id="odeme_secenek_'. $odeme_secenekleri->odeme_model .'" name="odeme_secenegi" value="'. $odeme_secenekleri->odeme_model .'" /></i>
								<u><img src="'. site_resim() . $odeme_secenekleri->odeme_resim . '.png" alt="'. $odeme_secenekleri->odeme_model .'" /></u>
								<p><b>'. $baslik .'</b><br />'. $aciklama .'</p>
								<div class="clear"></div>
							</div>
						';
					}
				}
			?>
			<?php if($i > 0) { ?>
			<div id="os_buton">
				<a href="javascript:;" onclick="$('#form_devam_et').submit();" class="butonum">
					<span class="butsol"></span>
					<span class="butor"><?php echo lang('messages_checkout_3_form_button_text'); ?></span>
					<span class="butsag"></span>
				</a>
				
			</div>
			<?php } ?>
		</div>
	</div>
	<?php echo form_close(); ?>
	<?php } ?>
	<?php if($odeme_secenekleri_sayi) { ?>
	<div id="o_sag" class="saga">
		<div class="adim_info adim_gri"><?php echo lang('messages_checkout_3_information_2'); ?></div>
	</div>
	<?php } ?>
	<?php if(!$odeme_secenekleri_sayi) { ?>
		<div id="os_buton">
			
			<a href="javascript:;" onclick="redirect('<?php echo ssl_url('odeme/adim_2/'. $siparis_id .'/'. $fatura_id .''); ?>');" class="butonum">
				<span class="butsol"></span>
				<span class="butor"><?php echo lang('messages_button_back'); ?></span>
				<span class="butsag"></span>
			</a>
			
		</div>
	<?php } ?>
	<div class="clear"></div>
</div>
<?php if($odeme_secenekleri_sayi) { ?>
<script type="text/javascript" charset="utf-8">
	function tip_sec(tip) {
		$('.odeme_secenekleri').attr('checked','');
		$('#odeme_secenek_' + tip).attr('checked','checked');
	}
</script>
<?php } ?>

<?php $this->load->view(tema() . 'odeme/footer'); ?>