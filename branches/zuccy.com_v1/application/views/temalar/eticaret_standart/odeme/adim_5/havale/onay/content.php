<?php $this->load->view(tema() . 'odeme/header'); ?>

<div id="odeme_secimi">
	<div class="odeme_baslik"><?php echo lang('messages_checkout_5_bank_transfer_title') ?></div>
	<div id="oo_mesaj" class="oo_onay">
		<b><?php echo lang('messages_checkout_5_bank_transfer_success_title'); ?></b><br />
		<?php echo strtr(lang('messages_checkout_5_bank_transfer_success_message'), array('{siparis_id}' => $siparis_id, '{siparis_a_title}' => lang('messages_checkout_5_bank_transfer_success_a_title'), '{siparis_a_href}' => ssl_url('uye/siparisler/detay/' . $siparis_id))); ?>
	</div>

	<div id="oo_baslik" class="oo_havale"><?php echo lang('messages_checkout_5_bank_transfer_payment_title'); ?></div>
	<div id="oo_havale">
		<div id="oo_hesap" class="sola"><?php echo lang('messages_checkout_5_bank_transfer_account_owner'); ?> :</div>
		<div id="oo_sahip" class="sola"><?php echo $secenek_detay_bilgi->hesap_sahip; ?></div>
		<div class="clear"></div>
		<div id="oo_h_tablo">
			<div id="oo_h_ust">
				<b><?php echo lang('messages_checkout_5_bank_transfer_bank_text'); ?></b>
				<i><?php echo lang('messages_checkout_5_bank_transfer_branch_text'); ?></i>
				<u><?php echo lang('messages_checkout_5_bank_transfer_account_number_text'); ?></u>
				<p><?php echo lang('messages_checkout_5_bank_transfer_iban_number_text'); ?></p>
				<div class="clear"></div>
			</div>
			<div id="oo_h_alt" class="oo_t_bold">
				<b><img src="<?php echo site_resim(); ?><?php echo $secenek_bilgi->havale_banka_resim; ?>" alt="Banka Adı" /></b>
				<i><?php echo $secenek_detay_bilgi->sube; ?></i>
				<u><?php echo $secenek_detay_bilgi->hesap_no; ?></u>
				<p><?php echo $secenek_detay_bilgi->iban_no; ?></p>
				<div class="clear"></div>
			</div>
		</div>
		<div class="oo_buton">
			<a href="javascript:;" onclick="redirect('<?php echo site_url('site/index'); ?>');" class="butonum">
				<span class="butsol"></span>
				<span class="butor"><?php echo lang('messages_button_back_home'); ?></span>
				<span class="butsag"></span>
			</a>
		</div>
	</div>

</div>

<?php $this->load->view(tema() . 'odeme/footer'); ?>