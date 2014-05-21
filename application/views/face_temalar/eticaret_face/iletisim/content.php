<script>
$(document).ready(function(){
	$('#iletisim_guvenlik_kodu').attr('src', '<?php echo site_url('site'); ?>/img_kontrol/iletisim?' + (new Date).getTime());
});
</script>
    <!--orta -->
    <div id="orta" class="sola">
		<div id="iletisim">
    	<h1 id="sayfa_baslik"><?php echo lang('messages_static_page_contact_title'); ?></h1>
			<div class="page_ust"></div>
			<div class="page_ic" style="<?php echo (config('site_google_maps_durum') == 1) ? 'height:370px;' : '' ?>">
				<?php if(config('site_google_maps_durum') == 2) {?>
				<div class="i_logo"><img src="<?php echo base_url(ssl_status()); ?>upload/editor/<?php echo config('site_ayar_logo'); ?>" style="width:140px" alt="<?php echo config('site_ayar_baslik'); ?>" title="<?php echo config('site_ayar_baslik'); ?>" /></div>
				<?php } else {?>
					<div class="i_google"><?php echo config('site_google_maps_kodu');?></div>
				<?php } ?>
				<span class="ib_baslik i_adres sola"><?php echo lang('messages_static_page_contact_address'); ?></span>
				<span class="ib_yazi sola" style="width:500px;height:auto;word-wrap:break-word;">: <?php echo config('site_ayar_sirket_adres');?></span>
				<div class="clear"></div>

				<span class="ib_baslik i_telefon sola"><?php echo lang('messages_static_page_contact_phone'); ?></span>
				<span class="ib_yazi sola">: <?php echo config('site_ayar_sirket_tel');?><?php echo (config('site_ayar_sirket_tel') && config('site_ayar_sirket_tel_p') == '1') ? ' (Pbx)':NULL;?></span>
				<div class="clear"></div>

				<?php if(config('site_ayar_sirket_tel2')){?>
					<span class="ib_baslik sola">&nbsp;</span>
					<span class="ib_yazi sola">: <?php echo config('site_ayar_sirket_tel2');?><?php echo (config('site_ayar_sirket_tel2') && config('site_ayar_sirket_tel2_p') == '1') ? ' (Pbx)':NULL;?></span>
					<div class="clear"></div>
				<?php } ?>

				<?php if(config('site_ayar_sirket_tel3')){?>
					<span class="ib_baslik sola">&nbsp;</span>
					<span class="ib_yazi sola">: <?php echo config('site_ayar_sirket_tel3');?><?php echo (config('site_ayar_sirket_tel_p') && config('site_ayar_sirket_tel3_p') == '1') ? ' (Pbx)':NULL;?></span>
					<div class="clear"></div>
				<?php } ?>

				<?php if(config('site_ayar_sirket_tel4')){?>
					<span class="ib_baslik sola">&nbsp;</span>
					<span class="ib_yazi sola">: <?php echo config('site_ayar_sirket_tel4');?><?php echo (config('site_ayar_sirket_tel4') && config('site_ayar_sirket_tel4_p') == '1') ? ' (Pbx)':NULL;?></span>
					<div class="clear"></div>
				<?php } ?>

				<?php if(config('site_ayar_sirket_tel5')){?>
					<span class="ib_baslik sola">&nbsp;</span>
					<span class="ib_yazi sola">: <?php echo config('site_ayar_sirket_tel5');?><?php echo (config('site_ayar_sirket_tel5') && config('site_ayar_sirket_tel5_p') == '1') ? ' (Pbx)':NULL;?></span>
					<div class="clear"></div>
				<?php } ?>

				<?php if(config('site_ayar_sirket_fax')){?>
				<span class="ib_baslik i_fax sola"><?php echo lang('messages_static_page_contact_fax'); ?></span>
				<span class="ib_yazi sola">: <?php echo config('site_ayar_sirket_fax');?><?php echo (config('site_ayar_sirket_fax') && config('site_ayar_sirket_fax_p') == '1') ? ' (Pbx)':NULL;?></span>
				<div class="clear"></div>
				<?php } ?>

				<?php if( config('site_ayar_sirket_fax2')){?>
					<span class="ib_baslik sola">&nbsp;</span>
					<span class="ib_yazi sola">: <?php echo config('site_ayar_sirket_fax2');?><?php echo (config('site_ayar_sirket_fax2') && config('site_ayar_sirket_fax2_p') == '1') ? ' (Pbx)':NULL;?></span>
					<div class="clear"></div>
				<?php } ?>

				<?php if( config('site_ayar_sirket_fax3')){?>
					<span class="ib_baslik sola">&nbsp;</span>
					<span class="ib_yazi sola">: <?php echo config('site_ayar_sirket_fax3');?><?php echo (config('site_ayar_sirket_fax3') && config('site_ayar_sirket_fax3_p') == '1') ? ' (Pbx)':NULL;?></span>
					<div class="clear"></div>
				<?php } ?>
				<span class="ib_baslik i_mail sola"><?php echo lang('messages_static_page_contact_email'); ?></span>
				<span class="ib_yazi sola">: <a href="mailto:<?php echo config('site_ayar_mail');?>"><?php echo config('site_ayar_mail');?></a></span>
				<div class="clear"></div>
			</div>
			<div class="page_alt"></div>
<?php 
if($bayiler->num_rows() > 0)
{
	foreach($bayiler->result() as $i => $r):
?>
			<div class="baslik" style="margin-top:10px;"><?php echo $r->bayi_adi;?></div>
			<div class="page_ust"></div>
			<div class="page_ic" style="<?php echo ($r->bayi_maps_flag == 1) ? 'height:370px;' : '' ?>">
				<?php if($r->bayi_maps_flag == 2) {?>
				<div class="i_logo"><img src="<?php echo $this->image_model->resize(config('site_ayar_logo'), 198, 62); ?>" style="width:140px;" /></div>
				<?php } else {?>
					<div class="i_google"><?php echo $r->bayi_maps_kodu;?></div>
				<?php } ?>
				<span class="ib_baslik i_adres sola"><?php echo lang('messages_static_page_contact_address'); ?></span>
				<span class="ib_yazi sola" style="width:500px;height:auto;word-wrap:break-word;">: <?php echo $r->bayi_adres;?></span>
				<div class="clear"></div>

				<span class="ib_baslik i_telefon sola"><?php echo lang('messages_static_page_contact_phone'); ?></span>
				<span class="ib_yazi sola">: <?php echo $r->bayi_tel;?><?php echo ($r->bayi_tel && $r->bayi_tel_p == '1') ? ' (Pbx)':NULL; ?></span>
				<div class="clear"></div>

				<?php if($r->bayi_tel2){?>
					<span class="ib_baslik sola">&nbsp;</span>
					<span class="ib_yazi sola">: <?php echo $r->bayi_tel2;?><?php echo ($r->bayi_tel2 && $r->bayi_tel2_p == '1') ? ' (Pbx)':NULL; ?></span>
					<div class="clear"></div>
				<?php } ?>

				<?php if($r->bayi_tel3){?>
					<span class="ib_baslik sola">&nbsp;</span>
					<span class="ib_yazi sola">: <?php echo $r->bayi_tel3;?><?php echo ($r->bayi_tel3 && $r->bayi_tel3_p == '1') ? ' (Pbx)':NULL; ?></span>
					<div class="clear"></div>
				<?php } ?>

				<?php if($r->bayi_tel4){?>
					<span class="ib_baslik sola">&nbsp;</span>
					<span class="ib_yazi sola">: <?php echo $r->bayi_tel4;?><?php echo ($r->bayi_tel4 && $r->bayi_tel4_p == '1') ? ' (Pbx)':NULL; ?></span>
					<div class="clear"></div>
				<?php } ?>

				<?php if($r->bayi_tel5){?>
					<span class="ib_baslik sola">&nbsp;</span>
					<span class="ib_yazi sola">: <?php echo $r->bayi_tel5;?><?php echo ($r->bayi_tel5 && $r->bayi_tel5_p == '1') ? ' (Pbx)':NULL; ?></span>
					<div class="clear"></div>
				<?php } ?>

				<?php if( $r->bayi_fax){?>
				<span class="ib_baslik i_fax sola"><?php echo lang('messages_static_page_contact_fax'); ?></span>
				<span class="ib_yazi sola">: <?php echo $r->bayi_fax;?><?php echo ($r->bayi_fax && $r->bayi_fax_p == '1') ? ' (Pbx)':NULL; ?></span>
				<div class="clear"></div>
				<?php } ?>

				<?php if( $r->bayi_fax2){?>
					<span class="ib_baslik sola">&nbsp;</span>
					<span class="ib_yazi sola">: <?php echo $r->bayi_fax2;?><?php echo ($r->bayi_fax2 && $r->bayi_fax2_p == '1') ? ' (Pbx)':NULL; ?></span>
					<div class="clear"></div>
				<?php } ?>
				
				<?php if( $r->bayi_fax3){?>
					<span class="ib_baslik sola">&nbsp;</span>
					<span class="ib_yazi sola">: <?php echo $r->bayi_fax3;?><?php echo ($r->bayi_fax3 && $r->bayi_fax3_p == '1') ? ' (Pbx)':NULL; ?></span>
					<div class="clear"></div>
				<?php } ?>

				<span class="ib_baslik i_mail sola"><?php echo lang('messages_static_page_contact_email'); ?></span>
				<span class="ib_yazi sola">: <a href="mailto:<?php echo $r->bayi_eposta;?>"><?php echo $r->bayi_eposta;?></a></span>
				<div class="clear"></div>
			</div>
			<div class="page_alt"></div>
<?php 
	endforeach;
}
?>
	<br />
    </div>
    <!--orta son -->