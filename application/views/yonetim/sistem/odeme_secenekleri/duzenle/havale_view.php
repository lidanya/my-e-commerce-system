<?php 
	$this->load->view('yonetim/header_view');
	$val = $this->validation;

	// Ayarlar
	$rules['modul_durum']							= 'trim|required|xss_clean';
	$rules['modul_sira']							= 'trim|required|xss_clean';
	// Ek Özellikler
	$rules['ozellik_siparis_durum']					= 'trim|required|xss_clean';
	$rules['ozellik_indirim_orani']					= 'trim|required|xss_clean';
	$rules['ozellik_indirim_tipi']					= 'trim|required|xss_clean';

	// Ayarlar
	$fields['modul_durum']							= 'Ödeme Durum';
	$fields['modul_sira']							= 'Ödeme Sırası';
	// Ek Özellikler
	$fields['ozellik_siparis_durum']				= 'Siparis Durumu';
	$fields['ozellik_indirim_orani']				= 'İndirim Oranı';
	$fields['ozellik_indirim_tipi']					= 'İndirim Oran Tipi';

	$val->set_rules($rules);
	$val->set_fields($fields);

	$unserialize_baslik = @unserialize($modul->odeme_baslik);

	if($val->run())
	{
		$eklenti_durum = false;
		$eklentiler_data = array(
			'odeme_durum'				=> $val->modul_durum,
			'odeme_sira'				=> $val->modul_sira,
			'odeme_siparis_durum'		=> $val->ozellik_siparis_durum,
			'odeme_indirim_orani'		=> $val->ozellik_indirim_orani,
			'odeme_indirim_tipi'		=> $val->ozellik_indirim_tipi,
		);

		$this->db->where('odeme_id', $modul->odeme_id);
		if($this->db->update('odeme_secenekleri', $eklentiler_data))
		{
			$eklenti_durum = true;
		}

		/*$this->db->where('eklenti_ascii', $modul->eklenti_ascii);
		$this->db->where('ayar_adi', 'tip');
		if($this->db->update('eklentiler_ayalar', array('ayar_deger' => $val->ozellik_tip)))
		{
			$eklenti_durum = true;
		}*/

		if($eklenti_durum)
		{
			echo '<div class="success">'. $unserialize_baslik[get_language('language_id', config('site_ayar_yonetim_dil'))] .' Ödeme Seçeneği Başarılı Bir Şekilde Düzenlendi.</div>';
		}

		redirect(yonetim_url('sistem/odeme_secenekleri/duzenle/' . $modul->odeme_model), 'refresh');
	} else {
		if($val->error_string)
		{
			echo '<div class="warning">'. $unserialize_baslik[get_language('language_id', config('site_ayar_yonetim_dil'))] .' Ödeme Seçeneği Düzenlenirken Bir Hata Oluştu.</div>';
		}
	}
?>
<script>
function banka_duzenle(banka_model)
{
	$.ajax({
		type: "POST",
		url: "<?php echo yonetim_url('sistem/odeme_secenekleri/ajax_banka_duzenle'); ?>",
		data: "banka_durum=" + $('#' + 'banka_durum_' + banka_model).val()
		+ "&banka_model=" + banka_model,
		dataType: 'json',
		beforeSend: function() {
			$('#duzenle_buton_' + banka_model).html('<img src="<?php echo yonetim_resim(); ?>loading_1.gif" alt="" /> Bekleyiniz...');
		},
		success: function(data) {
			if (data.basarili) {
				$('#duzenle_buton_' + banka_model).html('<a onclick="banka_duzenle(\''+ banka_model +'\');" style="cursor: pointer;">Kaydet</a>');
				alert(data.basarili);
			}
		}
	});
}
function banka_hesap_ekle(banka_model)
{
	$.ajax({
		type: "POST",
		url: "<?php echo yonetim_url('sistem/odeme_secenekleri/ajax_banka_hesap_ekle'); ?>",
		data: "hesap_no=" + $('#' + 'hesap_no_yeni_' + banka_model).val()
		+ "&sube=" + $('#' + 'sube_yeni_' + banka_model).val()
		+ "&iban_no=" + $('#' + 'iban_no_yeni_' + banka_model).val()
		+ "&hesap_sahip=" + $('#' + 'hesap_sahip_yeni_' + banka_model).val()
		+ "&tur=" + $('#' + 'tur_yeni_' + banka_model).val()
		+ "&durum=" + $('#' + 'hesap_durum_yeni_' + banka_model).val()
		+ "&banka_model=" + banka_model,
		dataType: 'json',
		beforeSend: function() {
			$('#banka_hesap_ekle_buton_' + banka_model).html('<img src="<?php echo yonetim_resim(); ?>loading_1.gif" alt="" /> Bekleyiniz...');
		},
		success: function(data) {
			if (data.basarili) {
				$('#banka_hesap_ekle_buton_' + banka_model).html('<a onclick="banka_hesap_ekle(\''+ banka_model +'\');" style="cursor: pointer;">Ekle</a>');

				$('#hesap_tablo_detay_' + banka_model).load('<?php echo yonetim_url(); ?>/sistem/odeme_secenekleri/ajax_banka_hesap_listele/' + banka_model);

				alert(data.basarili);
			}

			if(data.basarisiz)
			{
				$('#banka_hesap_ekle_buton_' + banka_model).html('<a onclick="banka_hesap_ekle(\''+ banka_model +'\');" style="cursor: pointer;">Ekle</a>');
				alert(data.basarisiz);
			}
		}
	});
}

function banka_hesap_duzenle(banka_model, detay_id)
{
	$.ajax({
		type: "POST",
		url: "<?php echo yonetim_url('sistem/odeme_secenekleri/ajax_banka_hesap_duzenle'); ?>",
		data: "hesap_no=" + $('#' + 'hesap_no_' + banka_model + '_' + detay_id).val()
		+ "&sube=" + $('#' + 'sube_' + banka_model + '_' + detay_id).val()
		+ "&iban_no=" + $('#' + 'iban_no_' + banka_model + '_' + detay_id).val()
		+ "&hesap_sahip=" + $('#' + 'hesap_sahip_' + banka_model + '_' + detay_id).val()
		+ "&tur=" + $('#' + 'tur_' + banka_model + '_' + detay_id).val()
		+ "&durum=" + $('#' + 'hesap_durum_' + banka_model + '_' + detay_id).val()
		+ "&banka_model=" + banka_model
		+ "&hesap_id=" + detay_id,
		dataType: 'json',
		beforeSend: function() {
			$('#banka_duzenle_buton_' + banka_model + '_' + detay_id).html('<img src="<?php echo yonetim_resim(); ?>loading_1.gif" alt="" /> Bekleyiniz...');
		},
		success: function(data) {
			if (data.basarili) {
				$('#banka_duzenle_buton_' + banka_model + '_' + detay_id).html('<a onclick="banka_hesap_duzenle(\''+ banka_model +'\', \''+ detay_id +'\');" style="cursor: pointer;">Kaydet</a>');

				$('#hesap_tablo_detay_' + banka_model).load('<?php echo yonetim_url(); ?>/sistem/odeme_secenekleri/ajax_banka_hesap_listele/' + banka_model);

				alert(data.basarili);
			}

			if(data.basarisiz)
			{
				$('#banka_duzenle_buton_' + banka_model + '_' + detay_id).html('<a onclick="banka_hesap_duzenle(\''+ banka_model +'\', \''+ detay_id +'\');" style="cursor: pointer;">Kaydet</a>');
				alert(data.basarisiz);
			}
		}
	});
}

function banka_hesap_sil(banka_model, hesap_d_id)
{
	$.ajax({
		type: "POST",
		url: "<?php echo yonetim_url('sistem/odeme_secenekleri/ajax_banka_hesap_sil'); ?>",
		data: "hesap_d_id=" + hesap_d_id
		+ "&banka_model=" + banka_model,
		dataType: 'json',
		beforeSend: function() {
			$('#banka_hesap_sil_buton_' + banka_model + '_' + hesap_d_id).html('<img src="<?php echo yonetim_resim(); ?>loading_1.gif" alt="" /> Bekleyiniz...');
		},
		success: function(data) {
			if (data.basarili) {
				$('#hesap_tablo_detay_' + banka_model).load('<?php echo yonetim_url(); ?>/sistem/odeme_secenekleri/ajax_banka_hesap_listele/' + banka_model);
				alert(data.basarili);
			}

			if(data.basarisiz)
			{
				$('#banka_hesap_sil_buton_' + banka_model + '_' + hesap_d_id).html('<a onclick="banka_hesap_sil(\''+ banka_model +'\', \''+ hesap_d_id +'\');" style="cursor: pointer;">Sil</a>');
				alert(data.basarisiz);
			}
		}
	});
}

function hesap_tablo_ac(banka_model)
{
	if($('#hesap_buton_' + banka_model).is(".aktif"))
	{
		$('#hesap_buton_' + banka_model).removeClass('aktif').addClass('pasif');
		$('#hesap_tablo_' + banka_model).hide();
		$('#hesap_tablo_' + banka_model).css('background-color','');
		$('#banka_tablo_' + banka_model).css('background','');
		$('#banka_tablo_' + banka_model + ' :input').attr('disabled','');
		$('#duzenle_buton_2_' + banka_model).css('visibility','visible');
		$('.hesap_tablo_2_' + banka_model).hide();
	} else {
		$('#hesap_buton_' + banka_model).removeClass('pasif').addClass('aktif');
		$('#banka_tablo_' + banka_model).css('background','#efefef');
		$('#hesap_tablo_' + banka_model).css('background-color','#e6fbd3');
		$('#banka_tablo_' + banka_model + ' :input').attr('disabled','disabled');
		$('#duzenle_buton_2_' + banka_model).css('visibility','hidden');
		$('#hesap_tablo_' + banka_model).show();
		$('.hesap_tablo_2_' + banka_model).show();
	}
}
</script>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
	<h1 style="background-image: url('<?php echo yonetim_resim(); ?>payment.png');"><?php echo $heading_title; ?></h1>
	<div class="buttons"><a onclick="$('#form').submit();" class="buton"><span>Kaydet</span></a><a onclick="location = '<?php echo yonetim_url('sistem/odeme_secenekleri'); ?>';" class="buton" style="margin-left:10px;"><span>İptal</span></a></div>
	</div>
	<div class="content">
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<table class="form">
			<tr>
				<td>Durumu</td>
				<td>
					<select name="modul_durum">
						<?php if ($bilgi->odeme_durum == '1') { ?>
						<option value="1" selected="selected">Açık</option>
						<option value="0">Kapalı</option>
						<?php } else { ?>
						<option value="1">Açık</option>
						<option value="0" selected="selected">Kapalı</option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Sıralama</td>
				<td><input type="text" name="modul_sira" value="<?php echo $bilgi->odeme_sira; ?>" size="1" /></td>
			</tr>
			<tr>
				<td>Sipariş Durumu<span class="help">Müşteriler sipariş işlemlerini tamamlayınca, siparişlerine otomatik olarak atanmasını istediğiniz durum.</span></td>
				<td>
				<?php
					$secili = $bilgi->odeme_siparis_durum;
					$siparis_durum_array = array('' => ' - Seçiniz - ');
					$query = $this->db
									->select('siparis_durum_tanim_id as id, siparis_durum_baslik as baslik')
									->get('siparis_durum');
					foreach ($query->result() as $siparis_durum) {
						$siparis_durum_array[$siparis_durum->id] = $siparis_durum->baslik;
					}
					echo form_dropdown('ozellik_siparis_durum', $siparis_durum_array, $secili);
				?>
				</td>
			</tr>
			<?php
				$indirim['00'] = '0';
				$indirim['01'] = '1';
				$indirim['02'] = '2';
				$indirim['03'] = '3';
				$indirim['04'] = '4';
				$indirim['05'] = '5';
				$indirim['06'] = '6';
				$indirim['07'] = '7';
				$indirim['08'] = '8';
				$indirim['09'] = '9';
				for($i=10;$i<=99;$i++)
				{
					$indirim[$i] = $i;
				}
			?>
			<tr>
				<td>Ödeme İndirim Oranı</td>
				<td>% <?php echo form_dropdown('ozellik_indirim_orani', $indirim, $modul->odeme_indirim_orani) . form_hidden('ozellik_indirim_tipi', '0'); ?></td>
			</tr>
		</table>
		<h4>Bankalar</h4>
		<table class="list" align="center">
			<tr>
				<td class="left" style="text-align:center; width:150px; font-weight:bold;">Banka</td>
				<td class="left" style="text-align:center; width:89px; font-weight:bold;">Durum</td>
				<td class="left" colspan="10" style="text-align:center; width:300px; font-weight:bold;"></td>
				<td class="right" style="text-align:center; width:200px; font-weight:bold;">Aksiyon</td>
			</tr>
			<?php
			$hesaplar_sorgu = $this->odeme_secenekleri_model->odeme_secenek_havale_hesap_listele();
			if ($hesaplar_sorgu->num_rows() > 0) { ?>
			<?php foreach ($hesaplar_sorgu->result() as $hesaplar) { ?>
			<tr id="banka_tablo_<?php echo $hesaplar->havale_banka_ascii; ?>">
				<td class="left" style="text-align:center; width:89px;"><?php echo (file_exists(APPPATH . 'views/'. tema_asset() .'images/' . $hesaplar->havale_banka_resim)) ? '<img src="'. site_resim() . $hesaplar->havale_banka_resim .'" alt="'. $hesaplar->havale_banka_baslik .'" width="75" />':NULL; ?></td>
				<td class="left" style="text-align:center; width:89px;">
					<select name="banka_durum" id="banka_durum_<?php echo $hesaplar->havale_banka_ascii; ?>" onchange="banka_duzenle('<?php echo $hesaplar->havale_banka_ascii; ?>');">
						<?php if ($hesaplar->havale_durum == '1') { ?>
						<option value="1" selected="selected">Açık</option>
						<option value="0">Kapalı</option>
						<?php } else { ?>
						<option value="1">Açık</option>
						<option value="0" selected="selected">Kapalı</option>
						<?php } ?>
					</select>
				</td>
				<td colspan="10" style="width: 300px;"></td>
				<td class="right" style="text-align:center; width:95px;">
					<span id="duzenle_buton_2_<?php echo $hesaplar->havale_banka_ascii; ?>">[ <span id="duzenle_buton_<?php echo $hesaplar->havale_banka_ascii; ?>"><a onclick="banka_duzenle('<?php echo $hesaplar->havale_banka_ascii; ?>');" style="cursor: pointer;">Kaydet</a></span> ]</span>
					<br>
					[ <a style="cursor: pointer;" id="hesap_buton_<?php echo $hesaplar->havale_banka_ascii; ?>" onclick="hesap_tablo_ac('<?php echo $hesaplar->havale_banka_ascii; ?>');">Banka Hesapları</a> ]
				</td>
			</tr>
			<tr>
				<td colspan="13">
					<table align="center" style="display:none; background-color: #fffdf2;width: 100%;" id="hesap_tablo_detay_<?php echo $hesaplar->havale_banka_ascii; ?>" class="hesap_tablo_2_<?php echo $hesaplar->havale_banka_ascii; ?>">
			<tr style="display:none;" id="hesap_tablo_<?php echo $hesaplar->havale_banka_ascii; ?>">
				<td class="left" style="text-align:center;width:103px;"><b><?php echo $hesaplar->havale_banka_baslik; ?> - Hesap Numaraları</b></td>
				<td class="left" style="text-align:center;width:110px;"><b>Hesap No</b></td>
				<td class="left" style="text-align:center;width:110px;"><b>Şube</b></td>
				<td class="left" style="text-align:center;width:110px;"><b>Hesap Sahibi</b></td>
				<td class="left" style="text-align:center;width:110px;"><b>IBAN No</b></td>
				<td class="left" style="text-align:center;width:120px;"><b>Tip</b></td>
				<td class="left" style="text-align:center;width:120px;"><b>Durum</b></td>
				<td colspan="6"></td>
				<td class="right" style="width:200px;"><b>Aksiyon</b></td>
			</tr>
			<?php
			$turler = array(
				'1' => 'TL',
				'2' => '$',
				'3' => '€'
			);

			echo '<tr style="background-color: #fbf4cc;" class="hesap_tablo_2_'. $hesaplar->havale_banka_ascii .'">
				<td class="left" style="text-align:center; width:89px;">Yeni Ekle</td>
				<td class="left" style="text-align:center; width:89px;">
					<input type="text" value="" name="hesap_no" id="hesap_no_yeni_'. $hesaplar->havale_banka_ascii . '">
				</td>
				<td class="left" style="text-align:center; width:89px;">
					<input type="text" value="" name="sube" id="sube_yeni_'. $hesaplar->havale_banka_ascii . '">
				</td>
				<td class="left" style="text-align:center; width:89px;">
					<input type="text" value="" name="hesap_sahip" id="hesap_sahip_yeni_'. $hesaplar->havale_banka_ascii . '" style="width: 210px;">
				</td>
				<td class="left" style="text-align:center; width:225px;">
					<input type="text" value="" name="iban_no" id="iban_no_yeni_'. $hesaplar->havale_banka_ascii . '" style="width: 210px;">
				</td>
				<td class="left" style="text-align:center; width:89px;">
					'. form_dropdown('tur', $turler, 'TL', 'id="tur_yeni_'. $hesaplar->havale_banka_ascii .'"') .'
				</td>
				<td class="left" style="text-align:center; width:89px;">
					<select name="hesap_durum" id="hesap_durum_yeni_'. $hesaplar->havale_banka_ascii .'">
						<option value="1" selected="selected">Açık</option>
						<option value="0">Kapalı</option>
					</select>
				</td>
				<td colspan="6"></td>
				<td class="right" style="width:95px;">
					[ <span id="banka_hesap_ekle_buton_'. $hesaplar->havale_banka_ascii . '">
						<a style="cursor: pointer;" id="banka_hesap_ekle_buton_'. $hesaplar->havale_banka_ascii .'" onclick="banka_hesap_ekle(\''. $hesaplar->havale_banka_ascii .'\');">Ekle</a>
					</span> ]
				</td>
			</tr>';
			?>
			<?php
			$banka_hesaplar_sorgu = $this->odeme_secenekleri_model->odeme_secenek_havale_banka_hesap_listele($hesaplar->havale_id);
			if ($banka_hesaplar_sorgu->num_rows() > 0) {
			?>
			<?php foreach ($banka_hesaplar_sorgu->result() as $banka_hesaplar) { ?>
			<tr style="display:none; background-color: #fffdf2;" class="hesap_tablo_2_<?php echo $hesaplar->havale_banka_ascii; ?>">
				<td class="left" style="text-align:center; width:89px;"></td>
				<td class="left" style="text-align:center; width:89px;">
					<input type="text" value="<?php echo $banka_hesaplar->hesap_no; ?>" name="hesap_no" id="hesap_no_<?php echo $hesaplar->havale_banka_ascii . '_' . $banka_hesaplar->havale_detay_id; ?>">
				</td>
				<td class="left" style="text-align:center; width:89px;">
					<input type="text" value="<?php echo $banka_hesaplar->sube; ?>" name="sube" id="sube_<?php echo $hesaplar->havale_banka_ascii . '_' . $banka_hesaplar->havale_detay_id; ?>">
				</td>
				<td class="left" style="text-align:center; width:89px;">
					<input type="text" value="<?php echo $banka_hesaplar->hesap_sahip; ?>" name="hesap_sahip" id="hesap_sahip_<?php echo $hesaplar->havale_banka_ascii . '_' . $banka_hesaplar->havale_detay_id; ?>" style="width: 210px;">
				</td>
				<td class="left" style="text-align:center; width:225px;">
					<input type="text" value="<?php echo $banka_hesaplar->iban_no; ?>" name="iban_no" id="iban_no_<?php echo $hesaplar->havale_banka_ascii . '_' . $banka_hesaplar->havale_detay_id; ?>" style="width: 210px;">
				</td>
				<td class="left" style="text-align:center; width:89px;">
					<?php
					
					$turler = array(
						'1' => 'TL',
						'2' => '$',
						'3' => '€'
					);
					
					echo form_dropdown('tur', $turler, $banka_hesaplar->tur, 'id="tur_'. $hesaplar->havale_banka_ascii . '_' . $banka_hesaplar->havale_detay_id .'"');
					
					?>
				</td>
				<td class="left" style="text-align:center; width:89px;">
					<select name="hesap_durum" id="hesap_durum_<?php echo $hesaplar->havale_banka_ascii . '_'. $banka_hesaplar->havale_detay_id; ?>">
						<?php if ($banka_hesaplar->hesap_durum == '1') { ?>
						<option value="1" selected="selected">Açık</option>
						<option value="0">Kapalı</option>
						<?php } else { ?>
						<option value="1">Açık</option>
						<option value="0" selected="selected">Kapalı</option>
						<?php } ?>
					</select>
				</td>
				<td colspan="6"></td>
				<td class="right" style="width:95px;">
					[ <span id="banka_duzenle_buton_<?php echo $hesaplar->havale_banka_ascii . '_' . $banka_hesaplar->havale_detay_id; ?>"><a onclick="banka_hesap_duzenle('<?php echo $hesaplar->havale_banka_ascii; ?>', '<?php echo $banka_hesaplar->havale_detay_id; ?>');" style="cursor: pointer;">Kaydet</a></span> ]
					[ <a style="cursor: pointer;" id="taksit_sil_buton_<?php echo $hesaplar->havale_banka_ascii; ?>" onclick="banka_hesap_sil('<?php echo $hesaplar->havale_banka_ascii; ?>', '<?php echo $banka_hesaplar->havale_detay_id; ?>');">Sil</a> ]
				</td>
			</tr>
			<?php } ?>
			<?php } ?>
					</table>
				</td>
			</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="center" colspan="6">Banka Bulunamadı</td>
			</tr>
			<?php } ?>
			</tbody>
		</table>
	</form>
	</div>
</div>
<?php $this->load->view('yonetim/footer_view'); ?>