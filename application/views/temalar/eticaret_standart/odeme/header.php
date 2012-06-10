<div id="odeme_cont">
	<div id="stepler">
		<?php
			$siparis_detay = $this->session->userdata('siparis_detay');
		?>
		<span class="step_fatura<?php echo (isset($siparis_detay['odeme_adim']) AND $siparis_detay['odeme_adim'] == 'fatura') ? ' step_aktif': NULL; ?>">
			<?php echo lang('messages_checkout_header_step_1'); ?>
		</span>
		<span class="step_kargo<?php echo (isset($siparis_detay['odeme_adim']) AND $siparis_detay['odeme_adim'] == 'kargo') ? ' step_aktif': NULL; ?>">
			<?php echo lang('messages_checkout_header_step_2'); ?>
		</span>
		<span class="step_odeme<?php echo (isset($siparis_detay['odeme_adim']) AND $siparis_detay['odeme_adim'] == 'odeme') ? ' step_aktif': NULL; ?>">
			<?php echo lang('messages_checkout_header_step_3'); ?>
		</span>
		<span class="step_detay<?php echo (isset($siparis_detay['odeme_adim']) AND $siparis_detay['odeme_adim'] == 'detay') ? ' step_aktif': NULL; ?>">
			<?php echo lang('messages_checkout_header_step_4'); ?>
		</span>
		<span class="step_siparis<?php echo (isset($siparis_detay['odeme_adim']) AND $siparis_detay['odeme_adim'] == 'siparis') ? ' step_aktif': NULL; ?>">
			<?php echo lang('messages_checkout_header_step_5'); ?>
		</span>
		<span class="step_baslik">
			<?php echo lang('messages_checkout_header_steps'); ?>
		</span>
		<div class="clear"></div>
	</div>