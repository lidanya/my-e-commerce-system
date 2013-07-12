<!--orta -->
<div id="orta" class="sola">
<h1 id="sayfa_baslik"><?php echo lang('messages_member_product_title'); ?></h1>
<div id="takip">
	<div style="margin: 0 auto;width:700px;line-height:20px;" class="t_bilgi"><?php echo lang('messages_member_product_message'); ?></div>
	<div class="ta_oge f_oge_baslik" style="padding:0;border:none;">
		<span class="ta_tablo01 sola"><?php echo lang('messages_member_product_product_name'); ?></span>
		<span class="ta_tablo02 sola" style="padding-top:7px;height:27px;"><?php echo lang('messages_member_product_product_transactions'); ?></span>
		<div class="clear"></div>
	</div>
<?php
	if($urunler->num_rows() > 0)
	{
		foreach($urunler->result() as $urun)
		{
			echo '<div class="ta_oge">
						<span class="ta_tablo01 sola"><a class="ta_link" href="'. site_url($urun->seo .'--product') .'" target="_blank" title="'. $urun->name .'">'. character_limiter($urun->name, 50) .'</a></span>
						<span class="ta_tablo02 sola">
						<a class="butonum" href="javascript:;" onclick="redirect(\''. ssl_url('uye/urun_takip/listeden_cik/' . $urun->follow_id) .'\');">
						<span class="butsol"></span>
						<span class="butor">'. lang('messages_member_product_delete_product') .'</span>
						<span class="butsag"></span>
						</a>
						</span>
					<div class="clear"></div>
					</div>';
		}
	} else {
		echo '<div class="ta_oge" style="text-align:center;"><span>'. lang('messages_member_product_no_product') .'</span></div>';
	}
?>
</div>

</div>
<!--orta son -->