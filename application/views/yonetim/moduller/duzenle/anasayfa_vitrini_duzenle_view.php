<?php 
	$this->load->view('yonetim/header_view');
	$val = $this->validation;

	// Ayarlar
	$rules['modul_baslik']							= 'trim|required|xss_clean';
	$rules['modul_posizyon']						= 'trim|required|xss_clean';
	$rules['modul_durum']							= 'trim|required|xss_clean';
	$rules['modul_baslik_goster']					= 'trim|required|xss_clean';
	$rules['modul_sira']							= 'trim|required|xss_clean';
	// Özellikler
	$rules['ozellik_tip']							= 'trim|required|numeric|xss_clean';
	$rules['ozellik_siralama_sekli']				= 'trim|required|xss_clean';
	$rules['ozellik_siralama_limit']				= 'trim|required|numeric|xss_clean';

	// Ayarlar
	$fields['modul_baslik']							= 'Modül Başlığı';
	$fields['modul_posizyon']						= 'Modül Pozisyon';
	$fields['modul_durum']							= 'Modül Durum';
	$fields['modul_baslik_goster']					= 'Modül Başlık Göster';
	$fields['modul_sira']							= 'Modül Sırası';
	// Özellikler
	$fields['ozellik_tip']							= 'Modül Görünüm Tipi';
	$fields['ozellik_siralama_sekli']				= 'Modül Sıralama Şekli';
	$fields['ozellik_siralama_limit']				= 'Modül Sıralama Limit';

	$val->set_rules($rules);
	$val->set_fields($fields);

	if($val->run())
	{
		$eklenti_durum = false;
		$eklentiler_data = array(
			'eklenti_yer'				=> serialize($val->modul_posizyon),
			'eklenti_baslik'			=> serialize($val->modul_baslik),
			'eklenti_baslik_goster'		=> $val->modul_baslik_goster,
			'eklenti_durum'				=> $val->modul_durum,
			'eklenti_sira'				=> $val->modul_sira,
		);

		$this->db->where('eklenti_id', $modul->eklenti_id);
		if($this->db->update('eklentiler', $eklentiler_data))
		{
			$eklenti_durum = true;
		}

		$this->db->where('eklenti_ascii', $modul->eklenti_ascii);
		$this->db->where('ayar_adi', 'tip');
		if($this->db->update('eklentiler_ayalar', array('ayar_deger' => $val->ozellik_tip)))
		{
			$eklenti_durum = true;
		}

		$this->db->where('eklenti_ascii', $modul->eklenti_ascii);
		$this->db->where('ayar_adi', 'tip');
		if($this->db->update('eklentiler_ayalar', array('ayar_deger' => $val->ozellik_tip)))
		{
			$eklenti_durum = true;
		}

		$this->db->where('eklenti_ascii', $modul->eklenti_ascii);
		$this->db->where('ayar_adi', 'siralama_sekli');
		if($this->db->update('eklentiler_ayalar', array('ayar_deger' => $val->ozellik_siralama_sekli)))
		{
			$eklenti_durum = true;
		}

		$this->db->where('eklenti_ascii', $modul->eklenti_ascii);
		$this->db->where('ayar_adi', 'siralama_limit');
		if($this->db->update('eklentiler_ayalar', array('ayar_deger' => $val->ozellik_siralama_limit)))
		{
			$eklenti_durum = true;
		}

		if($eklenti_durum)
		{
			echo '<div class="success">Modül Başarılı Bir Şekilde Düzenlendi.</div>';
		}

		redirect(yonetim_url('moduller/modul/listele', false), 'refresh');
	} else {
		if($val->error_string)
		{
			echo '<div class="warning">Modül Düzenlenirken Bir Hata Oluştu.</div>';
		}
	}
	$unserialize_baslik = @unserialize($modul->eklenti_baslik);
?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim(); ?>module.png');"><?php echo $unserialize_baslik[$this->fonksiyonlar->get_language('language_id', config('site_ayar_yonetim_dil'))]; ?></h1>
		<div class="buttons"><a onclick="$('#form').submit();" class="buton"><span>Kaydet</span></a><a onclick="location = '<?php echo yonetim_url('moduller/modul/listele'); ?>';" class="buton" style="margin-left:10px;"><span>İptal</span></a></div>
	</div>
	<div class="content">
		<form action="<?php echo yonetim_url('moduller/modul/duzenle/'. $modul->eklenti_id); ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="form">
				<tr>
					<td><span style="font-weight:bold;">Modül Durum Ayarları ;</span></td> 
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>Modül Başlık :</td> 
					<td>
						<?php $languages_i = 0; ?>
						<?php foreach ($languages as $language) { ?>
							<?php if($languages_i != 0) { ?>
							<div style="margin-top:5px;"></div>
							<?php } ?>
							<?php echo form_input(array('name' => 'modul_baslik['. $language['language_id'] .']', 'value' => isset($unserialize_baslik[$language['language_id']]) ? $unserialize_baslik[$language['language_id']] : NULL, 'style' => 'width:250px;')); ?>&nbsp;<img src="<?php echo yonetim_resim(); ?>flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
						<?php $languages_i++; ?>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>Modül Pozisyon :</td>
					<td>
					<?php
						$_posizyon_yerler = array('Anasayfa');
						$posizyon_yerler = array();
						$posizyon_yerler = @unserialize($modul->eklenti_yer);

						foreach($_posizyon_yerler as $_posizyon_yer)
						{
							$checked = (in_array(strtolower($_posizyon_yer), $posizyon_yerler)) ? 'checked="chedked"':NULL;
							echo '<div style="margin: 5px;"><input type="checkbox" name="modul_posizyon[]" value="'. strtolower($_posizyon_yer) .'" '. $checked .' /> '. $_posizyon_yer .'</div>' . "\n";
						}
					?>
					</td>
				</tr>
				<tr>
					<td>Modül Durumu :</td>
					<td>
						<?php
							$_modul_durum = array('1' => 'Açık', '0' => 'Kapalı');
							echo form_dropdown('modul_durum', $_modul_durum, $modul->eklenti_durum);
						?>
					</td>
				</tr>
				<tr>
					<td>Modül Başlık Göster :</td>
					<td>
						<?php
							$_modul_baslik_goster = array('1' => 'Açık', '0' => 'Kapalı');
							echo form_dropdown('modul_baslik_goster', $_modul_baslik_goster, $modul->eklenti_baslik_goster);
						?>
					</td>
				</tr>
				<tr>
					<td>Modül Sırası :</td> 
					<td>
						<?php echo form_input(array('name' => 'modul_sira', 'value' => $modul->eklenti_sira, 'size' => '3')); ?>
					</td>
				</tr>
				<tr>
					<td><span style="font-weight:bold;">Modül Özellik Ayarları ;</span></td> 
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>Görünüm Tipi :</td> 
					<td>
						<?php
							$_listeleme_tipi = array('1' => 'Tip 1');
							echo form_dropdown('ozellik_tip', $_listeleme_tipi, eklenti_ayar($modul->eklenti_ascii, 'tip'));
						?>
					</td>
				</tr>
				<tr>
					<td>Sıralama Şekli :</td> 
					<td>
						<?php
							$_siralama_sekli = array('asc' => 'İlk eklenenden son eklenene doğru', 'desc' => 'Son eklenenden ilk eklenene doğru', 'random' => 'Rasgele');
							echo form_dropdown('ozellik_siralama_sekli', $_siralama_sekli, eklenti_ayar($modul->eklenti_ascii, 'siralama_sekli'));
						?>
					</td>
				</tr>
				<tr>
					<td>Sıralama Limiti :</td> 
					<td>
						<?php echo form_input(array('name' => 'ozellik_siralama_limit', 'value' => eklenti_ayar($modul->eklenti_ascii, 'siralama_limit'), 'size' => '3')); ?>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
<?php
	$this->load->view('yonetim/footer_view');
?>