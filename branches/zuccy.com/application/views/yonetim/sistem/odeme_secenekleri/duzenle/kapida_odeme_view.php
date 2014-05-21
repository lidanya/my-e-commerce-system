<?php 
	$this->load->view('yonetim/header_view');
	$val = $this->validation;

	// Ayarlar
	$rules['modul_durum']							= 'trim|required|xss_clean';
	$rules['modul_sira']							= 'trim|required|xss_clean';
	// Ek Özellikler
	$rules['ozellik_ucret']							= 'trim|required|xss_clean';
	$rules['ozellik_siparis_durum']					= 'trim|required|xss_clean';

	// Ayarlar
	$fields['modul_durum']							= 'Ödeme Durum';
	$fields['modul_sira']							= 'Ödeme Sırası';
	// Ek Özellikler
	$fields['ozellik_ucret']						= 'Ödeme Ücreti';
	$fields['ozellik_siparis_durum']				= 'Siparis Durumu';

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
		);

		$this->db->where('odeme_id', $modul->odeme_id);
		if($this->db->update('odeme_secenekleri', $eklentiler_data))
		{
			$eklenti_durum = true;
		}

		if($this->db->update('ayarlar', array('ayar_deger' => number_format($val->ozellik_ucret, 2, '.', '')), array('ayar_adi' => 'site_ayar_kapida_odeme_tutari')))
		{
			$eklenti_durum = true;
		}

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
			<tr>
				<td>Ödeme Ücreti</td>
				<td><input type="text" name="ozellik_ucret" style="width:80px;" value="<?php echo config('site_ayar_kapida_odeme_tutari'); ?>" size="1" /> - TL</td>
			</tr>
		</table>
	</form>
	</div>
</div>
<?php $this->load->view('yonetim/footer_view'); ?>