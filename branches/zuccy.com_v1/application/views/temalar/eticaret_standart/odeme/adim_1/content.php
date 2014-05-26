<?php $this->load->view(tema() . 'odeme/header'); ?>
<?php
	$user_id = user_id();
	$user_ide_inf = get_user_ide_inf($user_id);
	$user_inv_inf = get_user_inv_inf($user_id);
	$user_adr_inf = get_user_adr_inf($user_id);
	$fatura_varmi = FALSE;
	if($user_inv_inf) {
		$fatura_varmi = TRUE;
		$fatura_bilgi = $user_inv_inf;
	}
	$billing_name = ($fatura_varmi) ? $fatura_bilgi->inv_username . ' ' . $fatura_bilgi->inv_usersurname : $user_ide_inf->ide_adi . ' ' . $user_ide_inf->ide_soy;
	$billing_phone = ($fatura_varmi) ? $fatura_bilgi->inv_tel : NULL;
	$billing_address = ($fatura_varmi) ? $fatura_bilgi->inv_adr_id : NULL;
	$billing_id_number = ($fatura_varmi) ? $fatura_bilgi->inv_tckimlik : NULL;
	$billing_company_name = ($fatura_varmi) ? $fatura_bilgi->inv_firma : NULL;
	$billing_tax_office = ($fatura_varmi) ? $fatura_bilgi->inv_vda : NULL;
	$billing_tax_number = ($fatura_varmi) ? $fatura_bilgi->inv_vno : NULL;
	$billing_fax_number = ($fatura_varmi) ? $fatura_bilgi->inv_fax : NULL;
	$billing_country = ($fatura_varmi) ? $fatura_bilgi->inv_ulke : 215;
	$billing_city = ($fatura_varmi) ? $fatura_bilgi->inv_sehir : 3354;
	$billing_place = ($fatura_varmi) ? $fatura_bilgi->inv_ilce : NULL;
	$billing_postal_code = ($fatura_varmi) ? $fatura_bilgi->inv_pkodu : NULL;
	$teslimat_varmi = FALSE;
	if(isset($siparis_detay['teslimat'])) {
		$teslimat_varmi = TRUE;
		$teslimat_bilgi = $siparis_detay['teslimat'];
	}
	$shipping_name = ($teslimat_varmi) ? $teslimat_bilgi['ad_soyad'] : $user_ide_inf->ide_adi . ' ' . $user_ide_inf->ide_soy;
	$shipping_phone = ($teslimat_varmi) ? $teslimat_bilgi['telefon'] : NULL;
	$shipping_address = ($teslimat_varmi) ? $teslimat_bilgi['adres'] : NULL;
	$shipping_country = ($teslimat_varmi) ? $teslimat_bilgi['ulke'] : 215;
	$shipping_city = ($teslimat_varmi) ? $teslimat_bilgi['sehir'] : 3354;
	$shipping_place = ($teslimat_varmi) ? $teslimat_bilgi['ilce'] : NULL;
	$shipping_postal_code = ($teslimat_varmi) ? $teslimat_bilgi['posta_kodu'] : NULL;
?>
<div id="fatura_teslimat">
	<div class="odeme_baslik"><?php echo lang('messages_checkout_title_billing_information'); ?></div>
	<div class="adim_info adim_sari"><?php echo lang('messages_checkout_1_information'); ?></div>
	<div id="odeme_form">
		<form id="fatura_adresi_form">
		<b><?php echo lang('messages_checkout_1_billing_form_name'); ?></b>
		<p class="of_normal"><input type="text" value="<?php echo $billing_name; ?>" name="name" /></p>
		<i id="fatura_adresi_form_name_error" style="display:none;width:300px;margin-top:15px;" class="fatura_adresi_form_mesaj_silme of_hata"></i>
		<div class="clear"></div>
		<b><?php echo lang('messages_checkout_1_billing_form_phone'); ?></b>
		<p class="of_normal"><input type="text" value="<?php echo $billing_phone; ?>" class="phone_mask" name="phone" /></p>
		<i id="fatura_adresi_form_phone_error" style="display:none;width:300px;margin-top:15px;" class="fatura_adresi_form_mesaj_silme of_hata"></i>
		<div class="clear"></div>
		<b><?php echo lang('messages_checkout_1_billing_form_address'); ?></b>
		<p class="of_uzun"><textarea name="address"><?php echo $billing_address; ?></textarea></p>
		<i id="fatura_adresi_form_address_error" style="display:none;width:300px;margin-top:15px;" class="fatura_adresi_form_mesaj_silme of_hata"></i>
		<div class="clear"></div>
		<div id="ft_tablar">
			<div id="ft_fatura_tab">
				<b><?php echo lang('messages_checkout_1_billing_form_id_number'); ?></b>
				<p class="of_normal"><input type="text" name="id_number" value="<?php echo $billing_id_number; ?>" /></p>
				<i id="fatura_adresi_form_id_number_error" style="display:none;width:300px;margin-top:15px;" class="fatura_adresi_form_mesaj_silme of_hata"></i>
				<div class="clear"></div>
				<b><?php echo lang('messages_checkout_1_billing_form_company_name'); ?></b>
				<p class="of_normal"><input type="text" name="company_name" value="<?php echo $billing_company_name; ?>" /></p>
				<i id="fatura_adresi_form_company_name_error" style="display:none;width:300px;margin-top:15px;" class="fatura_adresi_form_mesaj_silme of_hata"></i>
				<div class="clear"></div>
				<b><?php echo lang('messages_checkout_1_billing_form_tax_office'); ?></b>
				<p class="of_normal"><input type="text" name="tax_office" value="<?php echo $billing_tax_office; ?>" /></p>
				<i id="fatura_adresi_form_tax_office_error" style="display:none;width:300px;margin-top:15px;" class="fatura_adresi_form_mesaj_silme of_hata"></i>
				<div class="clear"></div>
				<b><?php echo lang('messages_checkout_1_billing_form_tax_number'); ?></b>
				<p class="of_normal"><input type="text" name="tax_number" value="<?php echo $billing_tax_number; ?>" /></p>
				<i id="fatura_adresi_form_tax_number_error" style="display:none;width:300px;margin-top:15px;" class="fatura_adresi_form_mesaj_silme of_hata"></i>
				<div class="clear"></div>
				<b><?php echo lang('messages_checkout_1_billing_form_fax_number'); ?></b>
				<p class="of_normal"><input type="text" class="phone_mask" name="fax_number" value="<?php echo $billing_fax_number; ?>" /></p>
				<i id="fatura_adresi_form_fax_number_error" style="display:none;width:300px;margin-top:15px;" class="fatura_adresi_form_mesaj_silme of_hata"></i>
				<div class="clear"></div>
				<b><?php echo lang('messages_checkout_1_billing_form_country'); ?></b>
				<p class="of_kisa">
					<?php
						$ulkeler = get_ulkeler();
						echo form_dropdown('country', $ulkeler, $billing_country, 'onchange="change_city(\'fatura_adresi_form_city\', $(this).val());"');
					?>
				</p>
				<b class="of_kisa"><?php echo lang('messages_checkout_1_billing_form_city'); ?></b>
				<p class="of_kisa">
					<?php
						$sehirler = get_sehirler($billing_country);
						echo form_dropdown('city', $sehirler, $billing_city, 'id="fatura_adresi_form_city"');
					?>
				</p>
				<b class="of_kisa"><?php echo lang('messages_checkout_1_billing_form_place'); ?></b>
				<p class="of_kisa"><input type="text" name="place" value="<?php echo $billing_place; ?>" /></p>
				<b class="of_orta"><?php echo lang('messages_checkout_1_billing_form_postal_code'); ?></b>
				<p class="of_kisa"><input type="text" name="postal_code" class="postal_code_mask" value="<?php echo $billing_postal_code; ?>" /></p>
				<div class="clear"></div>
			</div>
			</form>
			<form id="siparis_not_form">
			<div id="ft_not_tab">
				<b><?php echo lang('messages_checkout_1_billing_form_order_note'); ?></b>
				<p class="of_uzun"><textarea name="order_note"><?php echo isset($siparis_detay['siparis_not']) ? $siparis_detay['siparis_not'] : NULL; ?></textarea></p>
				<i id="siparis_not_form_order_note_error" style="display:none;width:300px;margin-top:15px;" class="siparis_not_form_mesaj_silme of_hata"></i>
				<div class="clear"></div>
			</div>
			</form>
			<form id="teslimat_adresi_form">
			<div id="ft_teslimat_tab">
				<b><?php echo lang('messages_checkout_1_shipping_form_name'); ?></b>
				<p class="of_normal"><input type="text" name="name" value="<?php echo $shipping_name; ?>" /></p>
				<i id="teslimat_adresi_form_name_error" style="display:none;width:300px;margin-top:15px;" class="teslimat_adresi_form_mesaj_silme of_hata"></i>
				<div class="clear"></div>
				<b><?php echo lang('messages_checkout_1_shipping_form_phone'); ?></b>
				<p class="of_normal"><input type="text" class="phone_mask" name="phone" value="<?php echo $shipping_phone; ?>" /></p>
				<i id="teslimat_adresi_form_phone_error" style="display:none;width:300px;margin-top:15px;" class="teslimat_adresi_form_mesaj_silme of_hata"></i>
				<div class="clear"></div>
				<b><?php echo lang('messages_checkout_1_shipping_form_address'); ?></b>
				<p class="of_uzun"><textarea name="address"><?php echo $shipping_address; ?></textarea></p>
				<i id="teslimat_adresi_form_address_error" style="display:none;width:300px;margin-top:15px;" class="teslimat_adresi_form_mesaj_silme of_hata"></i>
				<div class="clear"></div>
				<b><?php echo lang('messages_checkout_1_shipping_form_country'); ?></b>
				<p class="of_kisa">
					<?php
						$ulkeler = get_ulkeler();
						echo form_dropdown('country', $ulkeler, $shipping_country, 'onchange="change_city(\'teslimat_adresi_form_city\', $(this).val());"');
					?>
				</p>
				<b class="of_kisa"><?php echo lang('messages_checkout_1_shipping_form_city'); ?></b>
				<p class="of_kisa">
					<?php
						$sehirler = get_sehirler($billing_country);
						echo form_dropdown('city', $sehirler, $shipping_city, 'id="teslimat_adresi_form_city"');
					?>
				</p>
				<b class="of_kisa"><?php echo lang('messages_checkout_1_shipping_form_place'); ?></b>
				<p class="of_kisa"><input type="text" name="place" value="<?php echo $shipping_place; ?>" /></p>
				<b class="of_orta"><?php echo lang('messages_checkout_1_shipping_form_postal_code'); ?></b>
				<p class="of_kisa"><input type="text" name="postal_code" value="<?php echo $shipping_postal_code; ?>" class="postal_code_mask" /></p>
				<div class="clear"></div>
			</div>
			</form>
		</div>
		
		<div id="ft_secenekler">
			<a id="ft_fatura" class="ft_onelmi"><?php echo lang('messages_checkout_1_billing_add_detail_billing_information'); ?><span title="<?php echo lang('messages_checkout_1_billing_add_detail_billing_information_title'); ?>">&nbsp;</span></a>
			<a id="ft_not"><?php echo lang('messages_checkout_1_billing_add_order_note'); ?><span title="<?php echo lang('messages_checkout_1_billing_add_order_note_title'); ?>">&nbsp;</span></a>
			<a id="ft_teslimat"><?php echo lang('messages_checkout_1_billing_different_shipping_address'); ?><span title="<?php echo lang('messages_checkout_1_billing_different_shipping_address_title'); ?>">&nbsp;</span></a>
			<div class="clear"></div>
		</div>

		<b>&nbsp;</b>
		<p class="of_aciklama">
			<a href="javascript:;" onclick="step_check();" class="butonum">
				<span class="butsol"></span>
				<span class="butor"><?php echo lang('messages_checkout_1_billing_form_button_text'); ?></span>
				<span class="butsag"></span>
			</a>
			
		</p>
		<div class="clear"></div>
	</div>
</div>

<script type="text/javascript" charset="utf-8">
	function step_check () {
		var ft_fatura_tab = tab_visible_check('ft_fatura_tab');
		var ft_not_tab = tab_visible_check('ft_not_tab');
		var ft_teslimat_tab = tab_visible_check('ft_teslimat_tab');

		var ft_fatura_tab_kontrol = billing_address_send();
		if(ft_fatura_tab == false && ft_fatura_tab_kontrol.basarisiz != '') {
			$('#ft_fatura').click();
		}

		var ft_not_tab_kontrol = true;
		if(ft_not_tab) {
			var order_note_check = order_note_send();
			ft_not_tab_kontrol = (order_note_check.basarili != '') ? true : false;
		}

		var ft_teslimat_tab_kontrol = true;
		var order_shipping_check = order_shipping_send();
		ft_teslimat_tab_kontrol = (order_shipping_check.basarili != '') ? true : false;

		if(
			ft_fatura_tab_kontrol.basarili != '' &&
			ft_fatura_tab_kontrol.fatura_id != '' &&
			ft_not_tab_kontrol &&
			ft_teslimat_tab_kontrol			
		) {
			redirect(site_url("odeme/adim_2/<?php echo $siparis_id; ?>/" + ft_fatura_tab_kontrol.fatura_id));
		}
	}

	function order_note_send () {
		var _order_note_send = $.fn.ajax_post({
			aksiyon_adresi					: site_url('odeme/ajax/siparis_not_ekle'),
			aksiyon_tipi					: 'POST',
			aksiyon_data					: $('#siparis_not_form').serialize(),
			aksiyon_data_tipi				: 'json',
			aksiyon_data_sonuc_degeri		: false,
			aksiyon_sonucu_bekle			: false,
			aksiyon_sonucu_islem			: function(aksiyon_islem_sonuclari) {
				$('.siparis_not_form_mesaj_silme').html('').hide();

				if (aksiyon_islem_sonuclari.basarisiz != '') {
					$.each(aksiyon_islem_sonuclari, function(e_key, e_value) {
						if(e_key.search('_error') != -1) {
							if(e_value != '') {
								$('#siparis_not_form_' + e_key).html(e_value).show();
							}
						}
					});
				}
			}
		});

		return _order_note_send;
	}

	function order_shipping_send () {
		var ft_teslimat_tab = tab_visible_check('ft_teslimat_tab');
		var _order_shipping_send = $.fn.ajax_post({
			aksiyon_adresi					: site_url('odeme/ajax/teslimat_form_gonder_v2'),
			aksiyon_tipi					: 'POST',
			aksiyon_data					: (ft_teslimat_tab) ? $('#teslimat_adresi_form').serialize() : $('#fatura_adresi_form').serialize(),
			aksiyon_data_tipi				: 'json',
			aksiyon_data_sonuc_degeri		: false,
			aksiyon_sonucu_bekle			: false,
			aksiyon_sonucu_islem			: function(aksiyon_islem_sonuclari) {
				$('.teslimat_adresi_form_mesaj_silme').html('').hide();

				if (aksiyon_islem_sonuclari.basarisiz != '') {
					$.each(aksiyon_islem_sonuclari, function(e_key, e_value) {
						if(e_key.search('_error') != -1) {
							if(e_value != '') {
								$('#teslimat_adresi_form_' + e_key).html(e_value).show();
							}
						}
					});
				}
			}
		});

		return _order_shipping_send;
	}

	function tab_visible_check (tab) {
		var ret_tab = false;
		if($('#' + tab).is(":visible")) {
			ret_tab = true;
		}
		return ret_tab;
	}

	function billing_address_send () {
		var _billing_address_send = $.fn.ajax_post({
			aksiyon_adresi					: site_url('odeme/ajax/firma_form_gonder_v2'),
			aksiyon_tipi					: 'POST',
			aksiyon_data					: $('#fatura_adresi_form').serialize(),
			aksiyon_data_tipi				: 'json',
			aksiyon_data_sonuc_degeri		: false,
			aksiyon_sonucu_bekle			: false,
			aksiyon_sonucu_islem			: function(aksiyon_islem_sonuclari) {
				$('.fatura_adresi_form_mesaj_silme').html('').hide();

				if (aksiyon_islem_sonuclari.basarisiz != '') {
					$.each(aksiyon_islem_sonuclari, function(e_key, e_value) {
						if(e_key.search('_error') != -1) {
							if(e_value != '') {
								$('#fatura_adresi_form_' + e_key).html(e_value).show();
							}
						}
					});
				}
			}
		});

		return _billing_address_send;
	}

	function change_city (select_id, country_id) {
		$.fn.ajax_post({
			aksiyon_adresi					: site_url('odeme/ajax/sehir_listele'),
			aksiyon_tipi					: 'POST',
			aksiyon_data					: 'ulke_id=' + country_id,
			aksiyon_data_tipi				: 'html',
			aksiyon_data_sonuc_degeri		: false,
			aksiyon_sonucu_bekle			: false,
			aksiyon_sonucu_islem			: function(aksiyon_islem_sonuclari) {
				$('#' + select_id).html(aksiyon_islem_sonuclari);
			}
		});
	}

	$(document).ready(function(){
		$('.phone_mask').mask("9999 999 99 99");
		$('.postal_code_mask').mask("99999");
		<?php
			/*if(isset($siparis_detay['siparis_not'])) {
				echo '$(\'#ft_not\').click();' . PHP_EOL;
			}*/
		?>
	});

	$('#fatura_adresi_form input, #siparis_not_form input, #teslimat_adresi_form input').keydown(function(e) {
		if (e.keyCode == 13) {
			step_check();
		}
	});

</script>

<?php $this->load->view(tema() . 'odeme/footer'); ?>