<script>
$(document).ready(function(){
	$('#iletisim_guvenlik_kodu').attr('src', site_url('site/img_kontrol/iletisim?' + (new Date).getTime()));
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
				<span class="ib_yazi sola" style="width:480px;height:auto;word-wrap:break-word;">: <?php echo config('site_ayar_sirket_adres');?></span>
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
				<span class="ib_yazi sola">: <a class="sitelink" href="mailto:<?php echo config('site_ayar_mail');?>"><?php echo config('site_ayar_mail');?></a></span>
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
			<?php 
			if($this->dx_auth->is_logged_in() && $ide_inf != '')
			{
				$disabled = 'disabled="disabled"';
				$iletisim = $this->session->userdata('val');
				if(!empty($iletisim))
				{
					$eposta 	= $iletisim['email'];
					$adsoyad 	= $iletisim['adsoyad'];
					$konu 		= $iletisim['konu'];
					$mesaj		= $iletisim['mesaj'];
				} else {
					$eposta 	= $this->dx_auth->get_username();
					$adsoyad 	= $ide_inf->ide_adi . ' ' . $ide_inf->ide_soy;
					$adsoyad 	= ($val->TxtAdSoyad) 	? $val->TxtAdSoyad : $adsoyad;
					$konu 		= ($val->ticket_konu) 	? $val->ticket_konu : NULL;
					$mesaj		= ($val->ticket_mesaj) ? $val->ticket_mesaj : NULL;
				}
				
			} else {
				$disabled = '';
				$eposta		= ($val->eposta) ? $val->eposta  : NULL;
				$adsoyad 	= ($val->TxtAdSoyad)? $val->TxtAdSoyad : NULL;
				$konu 		= ($val->ticket_konu) ? $val->ticket_konu  : NULL;
				$mesaj		= ($val->ticket_mesaj) ? $val->ticket_mesaj : NULL;				
			}
			$this->session->unset_userdata('val');
			?>
			<div class="baslik"><?php echo lang('messages_static_page_contact_form_title'); ?></div>
			<div class="page_ust"></div>
			<div class="page_ic" style="background:#fff url(<?php echo site_resim();?>iletisim_bg.png) no-repeat top right;">
				<form action="<?php echo ssl_url('site/iletisim'); ?>" method="post" name="iletisim_form" id="iletisim_form">
					<input type="hidden" name="ticket_tip" id="ticket_tip" value="soru"/>
				<span class="i_text sola"><?php echo lang('messages_static_page_contact_form_email'); ?> :</span>
				<span class="i_box sola">
					<?php 
						if($this->dx_auth->is_logged_in())
						{
							?>
								<input type="hidden" name="eposta" id="eposta" value="<?php echo ($eposta);?>" />
								<input type="text" name="eposta1" id="eposta1" value="<?php echo ($eposta);?>" <?php echo $disabled; ?>/>
							<?php 
						} else {
							?>
								<input type="text" name="eposta" id="eposta" value="<?php echo ($eposta);?>" <?php echo $disabled; ?>/>
							<?php 
						}
						
					?>
					
				</span>
				<div class="clear"></div>
				<?php
				echo ($val->eposta_error) ? '<div style="line-height: 20px;color:red; margin-left:160px;">'. $val->eposta_error .'</div><div class="clear"></div>':NULL;
				?>
				<span class="i_text sola"><?php echo lang('messages_static_page_contact_form_name'); ?> :</span>
				<span class="i_box sola"><input type="text" name="TxtAdSoyad" id="TxtAdSoyad" value="<?php echo ($adsoyad);?>"/></span>
				<div class="clear"></div>
				<?php
				echo ($val->TxtAdSoyad_error) ? '<div style="line-height: 20px;color:red; margin-left:160px;">'. $val->TxtAdSoyad_error .'</div><div class="clear"></div>':NULL;
				?>
				
				<span class="i_text sola"><?php echo lang('messages_static_page_contact_form_subject'); ?> :</span>
				<span class="i_box sola"><input type="text" name="ticket_konu" id="ticket_konu" value="<?php echo ($konu);?>" /></span>
				<div class="clear"></div>
				<?php
				echo ($val->ticket_konu_error) ? '<div style="line-height: 20px;color:red; margin-left:160px;">'. $val->ticket_konu_error .'</div><div class="clear"></div>':NULL;
				?>
				
				<span class="i_text sola"><?php echo lang('messages_static_page_contact_form_message'); ?> :</span>
				<span class="i_box sola"><textarea name="ticket_mesaj" id="ticket_mesaj"><?php echo ($mesaj);?></textarea></span>
				<div class="clear"></div>
				<?php
				echo ($val->ticket_mesaj_error) ? '<div style="line-height: 20px;color:red; margin-left:160px;">'. $val->ticket_mesaj_error .'</div><div class="clear"></div>':NULL;
				?>
				
				<span class="i_text sola"><?php echo lang('messages_static_page_contact_form_securty_code'); ?> :</span>
				<span class="i_box sola"><img id="iletisim_guvenlik_kodu" alt="Güvenlik Kodu" title="Güvenlik Kodu" /></span>
				<span class="i_box sola"><input type="text" name="captcha" class="uye_box_sifre" style="width:190px;" autocomplete="off" /></span>
				<div class="clear"></div>
				<?php
				echo ($val->captcha_error) ? '<div style="line-height: 20px;color:red;  margin-left:160px;">'. $val->captcha_error .'</div><div class="clear"></div>':NULL;
				?>
				<span class="i_text sola">&nbsp;</span>
				<span class="i_box sola">
					<a href="javascript:;" onclick="$('#iletisim_form').submit();" class="butonum">
						<span class="butsol"></span>
						<span class="butor"><?php echo lang('messages_static_page_contact_form_button'); ?></span>
						<span class="butsag"></span>
					</a>
					
				</span>
				</form>
			</div>
			<div class="page_alt"></div>
		</div>	
    </div>
    <!--orta son -->