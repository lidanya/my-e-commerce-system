<?php $this->load->view(tema() . 'odeme/header'); ?>
<div id="uye_kontrol">
	<div id="uk_baslik">
		<a id="uye_kayit" class="sola uk_aktif"><?php echo lang('messages_checkout_user_check_fast_buy_text'); ?></a>
		<a id="uye_giris" class="sola"><?php echo lang('messages_checkout_user_check_login_text'); ?></a>
		<div class="clear"></div>
	</div>
	<div id="uk_tablar">
		<div id="uye_kayit_tab" class="uk_tab">
			<form id="uye_kayit_form">
			<div class="adim_info adim_beyaz"><?php echo lang('messages_checkout_user_check_fast_buy_information'); ?></div>
			<div id="odeme_form">
				<b><?php echo lang('messages_checkout_user_check_fast_buy_form_email'); ?></b>
				<p class="of_normal"><input type="text" name="email" value="" /></p>
				<i style="display:none;width:350px;margin-top:18px;" id="uye_kayit_form_email_error" class="mesaj_silme_toplu of_hata"></i>
				<div class="clear"></div>
				<b><?php echo lang('messages_checkout_user_check_fast_buy_form_name'); ?></b>
				<p class="of_normal"><input type="text" name="name" value="" /></p>
				<i style="display:none;width:350px;margin-top:18px;" id="uye_kayit_form_name_error" class="mesaj_silme_toplu of_hata"></i>
				<div class="clear"></div>
				<b><?php echo lang('messages_checkout_user_check_fast_buy_form_captcha'); ?></b>
				<p class="of_kisa"><input type="text" name="captcha" value="" /></p>
				<p class="of_kisa" id="uye_kayit_form_guvenlik_kodu"></p>
				<i style="display:none;width:350px;margin-top:18px;" id="uye_kayit_form_captcha_error" class="mesaj_silme_toplu of_hata"></i>
				<div class="clear"></div>
				<b>&nbsp;</b>
				<p class="of_aciklama">
					<input type="checkbox" name="agree" value="1" />
					<?php
						$information_type = config('information_types');
						$information = $this->information_model->get_information_by_id(config('site_ayar_sozlesme_id'));
						$seo = NULL;
						if($information) {
							$seo = strtr($information_type[$information->type]['url'], array('{url}' => $information->seo));
						}
					?>
					<?php echo strtr(lang('messages_checkout_user_check_fast_buy_form_agree'), array('{url}' => site_url($seo))); ?>
				</p>
				<i style="display:none;width:350px;" id="uye_kayit_form_agree_error" class="mesaj_silme_toplu of_hata"></i>
				<div class="clear"></div>
				<b>&nbsp;</b>
				<p class="of_aciklama" id="uye_kayit_form_buton">
					<a href="javascript:;" onclick="uye_ol_form_gonder();" class="butonum">
						<span class="butsol"></span>
						<span class="butor"><?php echo lang('messages_checkout_user_check_fast_buy_form_button_text'); ?></span>
						<span class="butsag"></span>
					</a>
				</p>
				<div class="clear"></div>
			</div>
			</form>
		</div>
		<div id="uye_giris_tab" class="uk_tab">
			<form id="uye_giris_form">
			<div class="adim_info adim_beyaz"><?php echo lang('messages_checkout_user_check_login_information'); ?></div>
			<div id="odeme_form">
				<b class="mesaj_silme_toplu" style="display:none;" id="uye_giris_form_basarisiz_1">&nbsp;</b><i style="display:none;width:350px;margin-top:18px;" id="uye_giris_form_basarisiz" class="mesaj_silme_toplu of_hata"></i>
				<div class="clear"></div>
				<b><?php echo lang('messages_checkout_user_check_login_form_email'); ?></b>
				<p class="of_normal"><input type="text" name="email" /></p>
				<i style="display:none;width:350px;margin-top:18px;" id="uye_giris_form_email_error" class="mesaj_silme_toplu of_hata"></i>
				<div class="clear"></div>
				<b><?php echo lang('messages_checkout_user_check_login_form_password'); ?></b>
				<p class="of_normal"><input type="password" name="password" /></p>
				<i style="display:none;width:350px;margin-top:18px;" id="uye_giris_form_password_error" class="mesaj_silme_toplu of_hata"></i>
				<div class="clear"></div>
				<b>&nbsp;</b>
				<p class="of_aciklama">
					<input type="checkbox" name="agree" value="1" />
					<?php
						$information_type = config('information_types');
						$information = $this->information_model->get_information_by_id(config('site_ayar_sozlesme_id'));
						$seo = NULL;
						if($information) {
							$seo = strtr($information_type[$information->type]['url'], array('{url}' => $information->seo));
						}
					?>
					<?php echo strtr(lang('messages_checkout_user_check_login_form_agree'), array('{url}' => site_url($seo))); ?>
				</p>
				<i style="display:none;width:350px;" id="uye_giris_form_agree_error" class="mesaj_silme_toplu of_hata"></i>
				<div class="clear"></div>
				<b>&nbsp;</b>
				<p class="of_aciklama" id="uye_giris_form_buton">
					<a href="javascript:;" onclick="uye_giris_form_gonder();" class="butonum">
						<span class="butsol"></span>
						<span class="butor"><?php echo lang('messages_checkout_user_check_login_form_button_text'); ?></span>
						<span class="butsag"></span>
					</a>
				</p><br/>
				<div class="sifremut">
				<p class="of_aciklama" id="uye_kayit_form_buton">
					<a href="<?php echo ssl_url('uye/sifre_hatirlat'); ?>" onclick="" class="butonum">
						<span class="butsol"></span>
						<span class="butor"><?php echo 'Şifremi Unuttum';?> </span>
						<span class="butsag"></span>
					</a>
				</p>
				</div>
				<div class="clear"></div>
			</div>
			</form>
		</div>
		
	</div>
</div>

<?php $this->load->view(tema() . 'odeme/footer'); ?>
<script type="text/javascript">
	$('#uye_kayit_form_guvenlik_kodu').html('<img style="margin-left:40px;margin-top:10px;" src="'+ resim_url +'loader.gif" alt="" />');
	setTimeout("$('#uye_kayit_form_guvenlik_kodu').html('<img style=\"margin-top:5px;\" src=\"' + site_url('site/img_kontrol/uye_kayit?' + (new Date).getTime()) + '\" alt=\"\" />')", 1500);

	function uye_ol_form_gonder () {
		var uye_kayit_form = $.fn.ajax_post({
			aksiyon_adresi					: site_url('uye/ajax/kayit_v2'),
			aksiyon_tipi					: 'POST',
			aksiyon_data					: $('#uye_kayit_form').serialize(),
			aksiyon_data_tipi				: 'json',
			aksiyon_data_sonuc_degeri		: false,
			aksiyon_sonucu_bekle			: false,
			aksiyon_yapilirken_islem		: function () {
				$('#uye_kayit_form_buton').html('<img style="margin-left:100px;margin-top:10px;" src="'+ resim_url +'loader.gif" alt="" />');
			},
			aksiyon_sonucu_islem			: function(aksiyon_islem_sonuclari) {
				$('.mesaj_silme_toplu').html('').hide();

				if (aksiyon_islem_sonuclari.basarisiz != '') {
					$('#uye_kayit_form_guvenlik_kodu').html('<img style="margin-left:40px;margin-top:10px;" src="'+ resim_url +'loader.gif" alt="" />');
					setTimeout("$('#uye_kayit_form_guvenlik_kodu').html('<img style=\"margin-top:5px;\" src=\"' + site_url('site/img_kontrol/uye_kayit?' + (new Date).getTime()) + '\" alt=\"\" />')", 1500);
					setTimeout("$('#uye_kayit_form_buton').html('<a href=\"javascript:;\" onclick=\"uye_ol_form_gonder();\" class=\"butonum\"><span class=\"butsol\"></span><span class=\"butor\"><?php echo lang('messages_checkout_user_check_fast_buy_form_button_text'); ?></span><span class=\"butsag\"></span></a>')", 1500);
	
					$.each(aksiyon_islem_sonuclari, function(e_key, e_value) {
						if(e_key.search('_error') != -1) {
							if(e_value != '') {
								$('#uye_kayit_form_' + e_key).html(e_value).show();
							}
						}
					});
				}
			}
		});

		if (uye_kayit_form.basarili != '') {
			$('.mesaj_silme_toplu').html('').hide();
			redirect(site_url('odeme/adim_1'));
		}
	}

	function uye_giris_form_gonder () {
		var uye_giris_form = $.fn.ajax_post({
			aksiyon_adresi					: site_url('uye/ajax/giris_v2'),
			aksiyon_tipi					: 'POST',
			aksiyon_data					: $('#uye_giris_form').serialize(),
			aksiyon_data_tipi				: 'json',
			aksiyon_data_sonuc_degeri		: false,
			aksiyon_sonucu_bekle			: false,
			aksiyon_yapilirken_islem		: function () {
				$('#uye_giris_form_buton').html('<img style="margin-left:100px;margin-top:10px;" src="'+ resim_url +'loader.gif" alt="" />');
			},
			aksiyon_sonucu_islem			: function(aksiyon_islem_sonuclari) {
				$('.mesaj_silme_toplu').html('').hide();

				if (aksiyon_islem_sonuclari.basarisiz != '') {
					setTimeout("$('#uye_giris_form_buton').html('<a href=\"javascript:;\" onclick=\"uye_giris_form_gonder();\" class=\"butonum\"><span class=\"butsol\"></span><span class=\"butor\"><?php echo lang('messages_checkout_user_check_login_form_button_text'); ?></span><span class=\"butsag\"></span></a>');", 1500);

					$.each(aksiyon_islem_sonuclari, function(e_key, e_value) {
						if(e_key.search('_error') != -1) {
							if(e_value != '') {
								$('#uye_giris_form_' + e_key).html(e_value).show();
							}
						}
					});

					if(aksiyon_islem_sonuclari.basarisiz != '') {
						$('#uye_giris_form_basarisiz_1').show();
						$('#uye_giris_form_basarisiz').html(aksiyon_islem_sonuclari.basarisiz).show();
					}
				}
			}
		});

		if (uye_giris_form.basarili != '') {
			$('.mesaj_silme_toplu').html('').hide();
			redirect(site_url('odeme/adim_1'));
		}
	}

	$('#uye_kayit_form input').keydown(function(e) {
		if (e.keyCode == 13) {
			uye_ol_form_gonder();
		}
	});

	$('#uye_giris_tab input').keydown(function(e) {
		if (e.keyCode == 13) {
			uye_giris_form_gonder();
		}
	});
</script>