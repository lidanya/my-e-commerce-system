<?php $this->load->view(tema() . 'odeme/header'); ?>

<div id="odeme_secimi">
	<div class="odeme_baslik"><?php echo lang('messages_checkout_5_credit_cart_title'); ?></div>
	<?php if ($gonder_bilgi['result']) { ?>
	<div id="oo_mesaj" class="oo_onay" style="margin-top:50px;margin-bottom:50px;">
		<b><?php echo lang('messages_checkout_5_credit_cart_success_title'); ?></b><br />
		<?php echo strtr(lang('messages_checkout_5_credit_cart_success_message'), array('{siparis_id}' => $siparis_id, '{siparis_a_title}' => lang('messages_checkout_5_credit_cart_success_a_title'), '{siparis_a_href}' => ssl_url('uye/siparisler/detay/' . $siparis_id), '{toplam_ucret}' => format_number($gonder_bilgi['ucret']))); ?>
	</div>
	<div class="oo_buton">
		<a href="javascript:;" onclick="redirect('<?php echo site_url('site/index'); ?>');" class="butonum">
			<span class="butsol"></span>
			<span class="butor"><?php echo lang('messages_button_back_home'); ?></span>
			<span class="butsag"></span>
		</a>
	</div>
	<?php } else { ?>
	<?php
		$hata_kodu = $gonder_bilgi['kod'];
		$this->db->like('kkhk_hata_mesaj', $gonder_bilgi['error_msg']);
		$hata_mesaj_kontrol = $this->db->get_where('odeme_secenek_kredi_karti_hata_kodlari', array('kkhk_hata_durum' => '1', 'kkhk_hata_kodu' => $hata_kodu), 1);

		if($hata_mesaj_kontrol->num_rows()) {
			$hata_mesaj_bilgi = $hata_mesaj_kontrol->row();
			$hata_mesaji = $hata_mesaj_bilgi->kkhk_hata_aciklama;
		} else {
			$hata_mesaji = $gonder_bilgi['error_msg'];
		}
	?>
	<div id="oo_mesaj" class="oo_hata" style="margin-top:50px;margin-bottom:50px;">
		<b><?php echo lang('messages_checkout_5_credit_cart_error_title'); ?></b><br />
		<?php echo $hata_mesaji; ?>
	</div>
	<div class="oo_buton">
		<a href="javascript:;" onclick="redirect('<?php echo ssl_url('odeme/adim_4/kredi_karti/'. $siparis_id .'/'. $fatura_id); ?>');" class="butonum">
			<span class="butsol"></span>
			<span class="butor"><?php echo lang('messages_button_try_again'); ?></span>
			<span class="butsag"></span>
		</a>
		<a href="javascript:;" onclick="redirect('<?php echo site_url('site/index'); ?>');" class="butonum">
			<span class="butsol"></span>
			<span class="butor"><?php echo lang('messages_button_back_home'); ?></span>
			<span class="butsag"></span>
		</a>
	</div>
	<?php } ?>
	<?php
		$debug_config = config('banka_detaylari');
		if ($debug_config['debug']) {
			echo debug($gonder_bilgi['debug']);
		}
	?>
</div>

<?php $this->load->view(tema() . 'odeme/footer'); ?>