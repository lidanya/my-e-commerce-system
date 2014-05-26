<div id="orta" class="sola">
	<h1 id="sayfa_baslik"><?php echo lang('messages_member_billing_detail_title'); ?></h1>
	<?php 
		$ftr = $fatura->row();
	?>
			<!-- Göster -->
			<div class="f_form">
				<span class="fg_text sola"><?php echo lang('messages_member_billing_detail_billing_name'); ?></span>
				<span class="fg_box sola">: <?php echo $ftr->inv_name;?></span>
				<div class="clear"></div>
				
				<span class="fg_text sola"><?php echo lang('messages_member_billing_detail_name'); ?></span>
				<span class="fg_box sola">: <?php echo $ftr->inv_username;?></span>
				<div class="clear"></div>
				
				<span class="fg_text sola"><?php echo lang('messages_member_billing_detail_surname'); ?></span>
				<span class="fg_box sola">: <?php echo $ftr->inv_usersurname;?></span>
				<div class="clear"></div>
				
				<span class="fg_text sola"><?php echo lang('messages_member_billing_detail_id_number'); ?></span>
				<span class="fg_box sola">: <?php echo $ftr->inv_tckimlik;?></span>
				<div class="clear"></div>
				
				<span class="fg_text sola"><?php echo lang('messages_member_billing_detail_company_name'); ?></span>
				<span class="fg_box sola">: <?php echo $ftr->inv_firma;?></span>
				<div class="clear"></div>
				
				<span class="fg_text sola"><?php echo lang('messages_member_billing_detail_address'); ?></span>
				<span class="fg_box sola">: <?php echo $ftr->inv_adr_id;?></span>
				<div class="clear"></div>
				
				<span class="fg_text sola"><?php echo lang('messages_member_billing_detail_country'); ?></span>
				<span class="fg_box sola">: <?php echo ulke_adi($ftr->inv_ulke);?></span>
				<span class="fg_text_kucuk sola"><?php echo lang('messages_member_billing_detail_city'); ?></span>
				<span class="fg_box sola">: <?php echo sehir_adi($ftr->inv_sehir);?></span>
				<span class="fg_text_kucuk sola"><?php echo lang('messages_member_billing_detail_place'); ?></span>
				<span class="fg_box sola">: <?php echo $ftr->inv_ilce;?></span>
				<span class="fg_text_kucuk sola"><?php echo lang('messages_member_billing_detail_postal_code'); ?></span>
				<span class="fg_box sola">: <?php echo $ftr->inv_pkodu;?></span>
				<div class="clear"></div>
				
				<span class="fg_text sola"><?php echo lang('messages_member_billing_detail_phone'); ?></span>
				<span class="fg_box sola">: <?php echo $ftr->inv_tel;?></span>
				<div class="clear"></div>
				
				<span class="fg_text sola"><?php echo lang('messages_member_billing_detail_fax'); ?></span>
				<span class="fg_box sola">: <?php echo $ftr->inv_fax;?></span>
				<div class="clear"></div>
				
				<span class="fg_text sola"><?php echo lang('messages_member_billing_detail_tax_office'); ?></span>
				<span class="fg_box sola">: <?php echo $ftr->inv_vda;?></span>
				<span class="fg_text sola" style="width:130px;margin-left:20px;"><?php echo lang('messages_member_billing_detail_tax_number'); ?></span>
				<span class="fg_box sola">: <?php echo $ftr->inv_vno;?></span>
				<div class="clear"></div>
			</div>
			<p style="text-align:right;width:480px;margin-top:10px;">
				<a class="butonum" onclick="history.back();">
		 			<span class="butsol"></span>
		 			<span class="butor"><img src="<?php echo site_resim(); ?>btn_img_geri.png" alt="<?php echo lang('messages_button_back'); ?>"> <?php echo lang('messages_button_back'); ?></span>
		 			<span class="butsag"></span>
		 		</a>
			</p>
			<!-- Göster SON-->
</div>