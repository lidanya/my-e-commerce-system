<?php $this->load->view(tema() . 'odeme/header'); ?>

<?php
	$this->db->select_sum('stok_tfiyat');
	$toplam_tfiyat_sorgu				= $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id));
	$toplam_tfiyat_bilgi				= $toplam_tfiyat_sorgu->row();
	$stok_toplam_fiyat					= $toplam_tfiyat_bilgi->stok_tfiyat;

	$indirim_ucret = 0;
	if($secenek_bilgi->odeme_indirim_orani != '00') {
		$indirim_ucret_hesapla = ($stok_toplam_fiyat * ((100-$secenek_bilgi->odeme_indirim_orani)/100));
		$indirim_ucret = ($stok_toplam_fiyat - $indirim_ucret_hesapla);
	}
?>

	<div id="odeme_secimi">
		<div class="odeme_baslik"><?php echo lang('messages_checkout_title_payment_details'); ?></div>
		<div id="o_sol" class="sola">
			<div id="os_bilgi_bg">
				<div id="os_bilgi">
					<div id="os_rak">
						<i><?php echo lang('messages_checkout_4_bank_transfer_sub_total'); ?></i>
						<b><?php echo format_number($stok_toplam_fiyat); ?> TL</b>
						<?php if($kupon_ucret > 0) { ?>
							<i class="s_yesil"><?php echo lang('messages_checkout_4_bank_transfer_coupon_total'); ?></i>
							<b class="s_yesil">-<?php echo format_number($kupon_ucret); ?> TL</b>
						<?php } ?>
						<?php if ($indirim_ucret > 0) { ?>
							<i class="s_yesil"><?php echo lang('messages_checkout_4_bank_transfer_discount_total'); ?></i>
							<b class="s_yesil">-<?php echo format_number($indirim_ucret); ?> TL</b>
						<?php } ?>
						<i><?php echo lang('messages_checkout_4_bank_transfer_shipping_total'); ?></i>
						<b><?php echo format_number($kargo_ucret); ?> TL</b>
						<?php $hidden = (config('site_ayar_kdv_goster') == '0') ? ' style="visibility:hidden;"':NULL; ?>
						<i<?php echo $hidden; ?>><?php echo lang('messages_checkout_4_bank_transfer_vat_total'); ?></i>
						<b<?php echo $hidden; ?>><?php echo format_number($toplam_kdv_fiyati); ?> TL</b>
						<div class="clear"></div>
					</div>
					<div id="os_toplam">
						<i><?php echo lang('messages_checkout_4_bank_transfer_total'); ?></i>
						<b>
							<?php
								$total_price = 0;
								$total_price += $stok_toplam_fiyat;
								if(config('site_ayar_kdv_goster') == '1') {
									$total_price += $toplam_kdv_fiyati;
								}
								if($kargo_ucret > 0) {
									$total_price += $kargo_ucret;
								}
								if($indirim_ucret > 0) {
									$total_price -= $indirim_ucret;
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
			<?php echo form_open_ssl('odeme/adim_5/havale', array('name' => 'form_devam_et', 'id' => 'form_devam_et')); ?>
			<div id="os">
				<b class="os_baslik"><?php echo lang('messages_checkout_4_bank_transfer_payment_title'); ?></b>
				<?php
					echo form_hidden('siparis_id', $siparis_id);
					echo form_hidden('fatura_id', $fatura_id);
					$odeme_tipi_varmi = 0;
					$i = 0;
					foreach($odeme_secenekleri->result() as $odeme_secenekleri) {
						$havale_detay_sorgu = $this->db->get_where('odeme_secenek_havale_detay', array('banka_id' => $odeme_secenekleri->havale_id));
						$tipler = '';
						if($havale_detay_sorgu->num_rows()) {
							$odeme_tipi_varmi += 1;
							foreach($havale_detay_sorgu->result() as $hdetay) {
								if($hdetay->tur == '1') {
									$tipler .= '<option value="'. $hdetay->havale_detay_id .'"> TL </option>';
								}
								if($hdetay->tur == '2') {
									$tipler .= '<option value="'. $hdetay->havale_detay_id .'"> $ </option>';
								}
								if($hdetay->tur == '3') {
									$tipler .= '<option value="'. $hdetay->havale_detay_id .'"> € </option>';
								}
							}
							$i++;
							$secili = ($i == '1') ? ' checked="checked"':NULL;
						if($tipler != '') {
					?>
					<div class="os_h_oge">
						<i><input type="radio" <?php echo $secili; ?> name="odeme_secenegi" class="odeme_secenekleri" id="odeme_secenek_<?php echo $odeme_secenekleri->havale_id; ?>" value="<?php echo $odeme_secenekleri->havale_id; ?>" onclick="tip_sec('<?php echo $odeme_secenekleri->havale_id; ?>');" /></i>
						<u><img src="<?php echo site_resim() . $odeme_secenekleri->havale_banka_resim; ?>" alt="<?php echo $odeme_secenekleri->havale_banka_baslik; ?>" onclick="tip_sec('<?php echo $odeme_secenekleri->havale_id; ?>');" /></u>
						<b><?php echo $odeme_secenekleri->havale_banka_baslik; ?></b>
						<span>
							<select onclick="tip_sec('<?php echo $odeme_secenekleri->havale_id; ?>');" name="tipi_<?php echo $odeme_secenekleri->havale_id; ?>" class="indirbox"><?php echo $tipler; ?></select>
						</span>
						<p onclick="tip_sec('<?php echo $odeme_secenekleri->havale_id; ?>');"><img class="info" title="<?php echo lang('messages_checkout_4_bank_transfer_question_information'); ?>" src="<?php echo site_resim(); ?>adim_soru.png" alt="" /></p>
						<div class="clear"></div>
					</div>
						<?php } ?>
					<?php } ?>
				<?php } ?>
				<br />
				<a id="os_diger" href="javascript:;" onclick="redirect('<?php echo ssl_url('odeme/adim_3/'. $siparis_id .'/'. $fatura_id); ?>');" class="info" title="<?php echo lang('messages_checkout_4_bank_transfer_other_payment_options_title'); ?>">
		<?php echo lang('messages_checkout_4_bank_transfer_other_payment_options'); ?>
				</a>
				<div id="os_buton">
					<a href="javascript:;" onclick="$('#form_devam_et').submit();" class="butonum">
						<span class="butsol"></span>
						<span class="butor"><?php echo lang('messages_checkout_4_bank_transfer_form_button_text'); ?></span>
						<span class="butsag"></span>
					</a>
				</div>
			</div>
			<?php echo form_close(); ?>
		</div>
		<div id="o_sag" class="saga">
			<div class="adim_info adim_gri"><?php echo lang('messages_checkout_4_bank_transfer_information'); ?></div>
		</div>
		<div class="clear"></div>
	</div>

<script type="text/javascript" charset="utf-8">

	function tip_sec(tip) {
		$('.odeme_secenekleri').attr('checked','');
		$('#odeme_secenek_' + tip).attr('checked','checked');
	}

</script>

<?php $this->load->view(tema() . 'odeme/footer'); ?>