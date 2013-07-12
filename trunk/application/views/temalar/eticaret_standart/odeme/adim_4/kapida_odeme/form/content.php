<?php $this->load->view(tema() . 'odeme/header'); ?>

<?php
	$this->db->select_sum('stok_tfiyat');
	$toplam_tfiyat_sorgu				= $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id));
	$toplam_tfiyat_bilgi				= $toplam_tfiyat_sorgu->row();
	$stok_toplam_fiyat					= $toplam_tfiyat_bilgi->stok_tfiyat;

	if(config('site_ayar_kapida_odeme_tutari') != '0' OR config('site_ayar_kapida_odeme_tutari') != '0.00') {
		$kargo_ucret += config('site_ayar_kapida_odeme_tutari'); 
	}
?>

<div id="odeme_secimi">
	<div class="odeme_baslik"><?php echo lang('messages_checkout_title_payment_details'); ?></div>
	<div id="o_sol" class="sola">
		<div id="os_bilgi_bg">
			<div id="os_bilgi">
				<div id="os_rak">
					<i><?php echo lang('messages_checkout_4_pay_door_sub_total'); ?></i>
					<b><?php echo format_number($stok_toplam_fiyat); ?> TL</b>
					<?php if($kupon_ucret > 0) { ?>
						<i class="s_yesil"><?php echo lang('messages_checkout_4_pay_door_coupon_total'); ?></i>
						<b class="s_yesil">-<?php echo format_number($kupon_ucret); ?> TL</b>
					<?php } ?>
					<i><?php echo lang('messages_checkout_4_pay_door_shipping_total'); ?></i>
					<b><?php echo format_number($kargo_ucret); ?> TL</b>
					<?php $hidden = (config('site_ayar_kdv_goster') == '0') ? ' style="visibility:hidden;"':NULL; ?>
					<i<?php echo $hidden; ?>><?php echo lang('messages_checkout_4_pay_door_vat_total'); ?></i>
					<b<?php echo $hidden; ?>><?php echo format_number($toplam_kdv_fiyati); ?> TL</b>
					<div class="clear"></div>
				</div>
				<div id="os_toplam">
					<i><?php echo lang('messages_checkout_4_pay_door_total'); ?></i>
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
		<?php echo form_open_ssl('odeme/adim_5/kapida_odeme', array('name' => 'form_devam_et', 'id' => 'form_devam_et')); ?>
		<?php
			echo form_hidden('siparis_id', $siparis_id);
			echo form_hidden('fatura_id', $fatura_id);
		?>
		<div id="os">
			<b class="os_baslik"><?php echo lang('messages_checkout_4_pay_door_payment_title'); ?></b>
			<div id="os_k_aciklama">
				<u><img src="<?php echo site_resim(); ?>ds3_kapida.png" alt="" /></u>
				<p><?php echo strtr(lang('messages_checkout_4_pay_door_message'), array('{toplam_ucret}' => format_number(config('site_ayar_kapida_odeme_tutari')))); ?></p>
				<div class="clear"></div>
				<br />
				<a id="os_diger" href="javascript:;" onclick="redirect('<?php echo ssl_url('odeme/adim_3/'. $siparis_id .'/'. $fatura_id); ?>');" class="info" title="<?php echo lang('messages_checkout_4_pay_door_other_payment_options_title'); ?>">
		<?php echo lang('messages_checkout_4_pay_door_other_payment_options'); ?>
				</a>
				<div id="os_buton">
					<a href="javascript:;" onclick="$('#form_devam_et').submit();" class="butonum">
						<span class="butsol"></span>
						<span class="butor"><?php echo lang('messages_checkout_4_pay_door_form_button_text'); ?></span>
						<span class="butsag"></span>
					</a>
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
	<div id="o_sag" class="saga">
		<div class="adim_info adim_gri"><?php echo lang('messages_checkout_4_pay_door_information'); ?></div>
	</div>
	<div class="clear"></div>
</div>

<?php $this->load->view(tema() . 'odeme/footer'); ?>