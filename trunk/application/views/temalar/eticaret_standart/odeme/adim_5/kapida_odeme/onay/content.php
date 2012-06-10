<?php $this->load->view(tema() . 'odeme/header'); ?>

<div id="odeme_secimi">
	<div class="odeme_baslik"><?php echo lang('messages_checkout_5_pay_door_title') ?></div>
	<div id="oo_mesaj" class="oo_onay">
		<b><?php echo lang('messages_checkout_5_pay_door_success_title'); ?></b><br />
		<?php echo strtr(lang('messages_checkout_5_pay_door_success_message'), array('{siparis_id}' => $siparis_id, '{siparis_a_title}' => lang('messages_checkout_5_pay_door_success_a_title'), '{siparis_a_href}' => ssl_url('uye/siparisler/detay/' . $siparis_id))); ?>
	</div>

	<div id="oo_baslik" class="oo_havale"><?php echo lang('messages_checkout_5_pay_door_payment_title'); ?></div>
	<div id="oo_kapida">
		<?php echo lang('messages_checkout_5_pay_door_payment_information'); ?>
	</div>
	<div class="oo_buton">
		<a href="javascript:;" onclick="redirect('<?php echo site_url('site/index'); ?>');" class="butonum">
			<span class="butsol"></span>
			<span class="butor"><?php echo lang('messages_button_back_home'); ?></span>
			<span class="butsag"></span>
		</a>
	</div>

</div>

<?php $this->load->view(tema() . 'odeme/footer'); ?>