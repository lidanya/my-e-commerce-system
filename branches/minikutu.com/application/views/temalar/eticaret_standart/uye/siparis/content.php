<!--orta -->
<div id="orta" class="sola">
	<!--Listele -->
	<h1 id="sayfa_baslik"><?php echo lang('messages_member_orders_title'); ?></h1>
	<div id="siparis">
		<span><?php echo lang('messages_member_orders_message'); ?><br />&nbsp;</span>
	<?php 
		if($siparisler->num_rows()) {
	?>
		<!-- header -->		
		<div class="s_oge f_oge_baslik">
			<span class="s_tablo01 sola"><?php echo lang('messages_member_orders_no'); ?></span>
			<span class="s_tablo02 sola"><?php echo lang('messages_member_orders_status'); ?></span>
			<span class="s_tablo03 sola" style="font-style:normal;font-size:13px;width:430px;"><?php echo lang('messages_member_orders_remarks'); ?></span>
		</div>
		<!-- header son -->
		
		<!-- tbody -->
		<?php
			$i = 0;
			foreach($siparisler->result() as $siparis):
				$z = $i%2;
				if($z == 0) {
				$div_class = ' f_gri';
				} else {
					$div_class = NULL;
				}
				if($siparis->siparis_flag_data == '') {
					$aciklama = lang('messages_member_orders_no_remark');
				} else {
					$aciklama = $siparis->siparis_flag_data;
				}
		?>
		<div class="s_oge<?php echo $div_class; ?>" style="cursor:pointer;">
			<span class="s_tablo01 sola">
				<a class="sitelink" rel="nofollow" href="javascript:;" onclick="location = '<?php echo ssl_url('uye/siparisler/detay/'. $siparis->siparis_id);?>';"><?php echo $siparis->siparis_id; ?></a>
			</span>
			<span class="s_tablo02 sola"><b class="siterenk" onclick="location = '<?php echo ssl_url('uye/siparisler/detay/'. $siparis->siparis_id);?>';"><?php echo siparis_durum_goster($siparis->siparis_flag); ?></b></span>
			<span class="s_tablo03 sola" style="width:425px;"><?php echo $aciklama; ?></span>
		</div>
		<?php
			$i++;
			endforeach;
		?>
		<!-- tbody son -->
	<?php } else { ?>
		<div class="s_oge f_oge_baslik">
			<span class="s_tablo01" style="width:100%;"><?php echo lang('messages_member_orders_no_orders'); ?></span>
		</div>
	<?php } ?>
	</div>
	<!--Listele SON-->	
</div>
