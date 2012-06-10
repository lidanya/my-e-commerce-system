<?php 
	$this->load->view('yonetim/header_view');
	$val = $this->validation;

	// Ayarlar
	$rules['modul_durum']							= 'trim|required|xss_clean';
	$rules['modul_sira']							= 'trim|required|xss_clean';
	// Ek Özellikler
	$rules['ozellik_siparis_durum']					= 'trim|required|xss_clean';
	$rules['site_taksit_limit']						= 'trim|required|xss_clean';

	// Ayarlar
	$fields['modul_durum']							= 'Ödeme Durum';
	$fields['modul_sira']							= 'Ödeme Sırası';
	// Ek Özellikler
	$fields['ozellik_siparis_durum']				= 'Siparis Durumu';
	$fields['site_taksit_limit']					= 'Taksit Limiti';

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
		
		$this->db->update('ayarlar', array('ayar_deger' => $val->site_taksit_limit), array('ayar_adi' => 'site_taksit_limit'));
		
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
				<td>Taksit Limiti<span class="help">Taksit seçeneğinin kaç liradan sonra görüneceğini gösterir; Sepetteki ürünlerin toplamı burada belirttiğiniz rakamı aşarsa satın alma aşamasında kredikartı taksitli ödeme seçeneği görünür hale gelir.</span></td>
				<td><input type="text" name="site_taksit_limit" value="<?php echo config('site_taksit_limit'); ?>" size="5" /></td>
			</tr>
		</table>
	</form>
	<h4>Poslar</h4>
	<table class="list">
		<thead>
			<tr>
				<td class="left">
					Banka
				</td>
				<td class="left">
					Pos Tipi
				</td>
				<td class="left">
					Standart
				</td>
				<td class="left">
					Taksit
				</td>
				<td class="left">
					Peşin Komisyonu
				</td>
				<td class="left">
					Durum
				</td>
				<td class="right">İşlemler</td>
			</tr>
		</thead>

		<tbody>
			<?php
				$poslar_sorgu = $this->odeme_secenekleri_model->odeme_secenek_kredi_karti_pos_listele();
				if ($poslar_sorgu->num_rows() > 0)
				{
					foreach ($poslar_sorgu->result() as $poslar)
					{
				?>
			<tr>
				<td class="left">
					<?php echo $poslar->kk_banka_adi; ?>
				</td>
				<td class="left">
					<?php echo ucwords($poslar->kk_banka_pos_tipi); ?>
				</td>
				<td class="left">
					<span class="standart_class" id="standart_span_<?php echo $poslar->kk_id; ?>"><?php echo ($poslar->kk_banka_standart == '1') ? 'Evet' : 'Hayır'; ?></span>
				</td>
				<td class="left">
					<?php echo ($poslar->kk_banka_taksit == '1') ? 'Evet' : 'Hayır'; ?>
				</td>
				<td class="left">
					%<?php echo $poslar->kk_pesin_komisyon; ?>
				</td>
				<td class="left">
					<?php echo ($poslar->kk_banka_durum == '1') ? 'Aktif' : 'Pasif'; ?>
				</td>
				<td class="right">
					[ <a href="javascript:;" title="Pos Bilgilerini Düzenlemek İçin Tıklayın" onclick="pos_bilgileri(<?php echo $poslar->kk_id; ?>);">Pos Giriş Bilgileri</a> ] [ <a href="javascript:;" title="Taksit Seçeneklerini Düzenlemek İçin Tıklayın" onclick="taksit_secenegi(<?php echo $poslar->kk_id; ?>);">Taksit Seçenekleri</a> ]
				</td>
			</tr>
			<tr style="display:none;" id="table_taksit_secenegi_<?php echo $poslar->kk_id; ?>">
				<td colspan="2" class="left" style="text-align:center;">
					<?php echo $poslar->kk_banka_adi; ?> - Taksit Seçenekleri
				</td>
				<td colspan="5" class="left" style="">
					<div id="taksit_mesaj_<?php echo $poslar->kk_id; ?>" style="text-align:center;margin-bottom:7px;display:none;">
					</div>
					<table class="list">
						<thead>
							<tr>
								<td class="left">
									Taksit Sayısı
								</td>
								<td class="left">
									Komisyon
								</td>
								<td class="left">
									Durum
								</td>
								<td class="right">İşlemler</td>
							</tr>
						</thead>

						<tbody id="taksitler_tbody_<?php echo $poslar->kk_id; ?>">
							<tr>
								<form method="post" enctype="multipart/form-data" id="taksit_ekle_<?php echo $poslar->kk_id; ?>">
								<td class="left">
									<?php echo form_hidden('taksit_ekle_pos_id', $poslar->kk_id); ?>
									<input type="text" name="taksit_ekle_taksit_sayisi" id="taksit_ekle_taksit_sayisi_<?php echo $poslar->kk_id; ?>" /> <span id="taksit_ekle_taksit_sayisi_error_<?php echo $poslar->kk_id; ?>"></span>
								</td>
								<td class="left">
									<?php
										$yeni_ekle_komisyon['00'] = '0';
										$yeni_ekle_komisyon['01'] = '1';
										$yeni_ekle_komisyon['02'] = '2';
										$yeni_ekle_komisyon['03'] = '3';
										$yeni_ekle_komisyon['04'] = '4';
										$yeni_ekle_komisyon['05'] = '5';
										$yeni_ekle_komisyon['06'] = '6';
										$yeni_ekle_komisyon['07'] = '7';
										$yeni_ekle_komisyon['08'] = '8';
										$yeni_ekle_komisyon['09'] = '9';
										for($i=10;$i<=99;$i++)
										{
											$yeni_ekle_komisyon[$i] = $i;
										}
										echo '% ' . form_dropdown('taksit_ekle_komisyon', $yeni_ekle_komisyon, '00', 'id="'. 'taksit_ekle_komisyon_' . $poslar->kk_id .'"');
								?>
								</td>
								<td class="left">
									<?php
										$durum_array = array('0' => 'Pasif', '1' => 'Aktif');
										echo form_dropdown('taksit_ekle_durum', $durum_array, 1, 'id="'. 'taksit_ekle_durum_' . $poslar->kk_id .'"');
									?>
								</td>
								<td class="right">[ <span id="taksit_ekle_span_id_<?php echo $poslar->kk_id; ?>"><a href="javascript:;" onclick="pos_taksit_ekle('<?php echo $poslar->kk_id; ?>');">Ekle</a></span> ]</td>
								</form>
							</tr>
						<?php
							$this->db->order_by('kkts_taksit_sayisi', 'asc');
							$taksit_secenekleri = $this->db->get_where('odeme_secenek_kredi_karti_taksit_secenekleri', array('kk_id' => $poslar->kk_id));
							foreach($taksit_secenekleri->result() as $taksit_secenegi)
							{
							?>
							<tr id="taksit_duzenle_tr_<?php echo $taksit_secenegi->kkts_id; ?>">
								<form method="post" enctype="multipart/form-data" id="taksit_duzenle_<?php echo $taksit_secenegi->kkts_id; ?>">
								<td class="left">
									<?php echo $taksit_secenegi->kkts_taksit_sayisi; ?>
								</td>
								<td class="left">
									<?php echo form_hidden('taksit_duzenle_id', $taksit_secenegi->kkts_id); ?>
									<?php
										$duzenle_komisyon['00'] = '0';
										$duzenle_komisyon['01'] = '1';
										$duzenle_komisyon['02'] = '2';
										$duzenle_komisyon['03'] = '3';
										$duzenle_komisyon['04'] = '4';
										$duzenle_komisyon['05'] = '5';
										$duzenle_komisyon['06'] = '6';
										$duzenle_komisyon['07'] = '7';
										$duzenle_komisyon['08'] = '8';
										$duzenle_komisyon['09'] = '9';
										for($i=10;$i<=99;$i++)
										{
											$duzenle_komisyon[$i] = $i;
										}
										echo '% ' . form_dropdown('taksit_duzenle_komisyon', $duzenle_komisyon, $taksit_secenegi->kkts_komisyon, 'id="'. 'taksit_duzenle_komisyon_' . $taksit_secenegi->kkts_id .'"');
									?>
								</td>
								<td class="left">
									<?php
										$durum_array = array('0' => 'Pasif', '1' => 'Aktif');
										echo form_dropdown('taksit_duzenle_durum', $durum_array, $taksit_secenegi->kkts_durum, 'id="'. 'taksit_duzenle_durum_' . $taksit_secenegi->kkts_id .'"');
									?>
								</td>
								<td class="right">[ <span id="taksit_sil_span_id_<?php echo $taksit_secenegi->kkts_id; ?>"><a href="javascript:;" onclick="pos_taksit_sil('<?php echo $taksit_secenegi->kkts_id; ?>', '<?php echo $poslar->kk_id; ?>');">Sil</a></span> ] [ <span id="taksit_duzenle_span_id_<?php echo $taksit_secenegi->kkts_id; ?>"><a href="javascript:;" onclick="pos_taksit_guncelle('<?php echo $taksit_secenegi->kkts_id; ?>', '<?php echo $poslar->kk_id; ?>');">Kaydet</a></span> ]</td>
								</form>
							</tr>
							<?php
							}
						?>
						</tbody>
					</table>
				</td>
			</tr>
			<tr style="display:none;" id="table_pos_bilgileri_<?php echo $poslar->kk_id; ?>">
			<form method="post" enctype="multipart/form-data" id="pos_bilgileri_<?php echo $poslar->kk_id; ?>">
				<td colspan="2" class="left" style="text-align:center;border-bottom:0;">
					<?php echo $poslar->kk_banka_adi; ?> - Pos Bilgileri
				</td>
				<td colspan="5" class="left" style="border-bottom:0;">
					<div id="<?php echo $poslar->kk_id; ?>_tabs_div" class="vtabs">
						<a style="text-decoration:none;" href="javascript:;" tab="#<?php echo $poslar->kk_id; ?>_tip_genel_bilgiler">Genel Bilgiler</a>
						<?php
							$pos_tipleri = @unserialize($poslar->kk_banka_bilgi);
							foreach($pos_tipleri as $pos_tip_key => $pos_tip_value)
							{
								echo '<a style="text-decoration:none;" href="javascript:;" tab="#'. $poslar->kk_id .'_tip_'. $pos_tip_key .'"><img src="'. yonetim_resim() .'error_small.png" style="border:0;visibility:hidden;" id="'. $poslar->kk_id .'_tip_'. $pos_tip_key .'_hata_ikon" /> '. ucwords($pos_tip_key) .'</a>';
							}
						?>
					</div>
					<div id="<?php echo $poslar->kk_id; ?>_tip_genel_bilgiler" class="vtabs-content">
						<br />Genel Bilgiler<br /><br />
						<table class="form">
							<tr style="height:30px;width:200px;">
								<td style="width:50px;">
									Pos Tipi
								</td>
								<td style="width:50px;">
									<?php echo form_hidden('pos_bilgi_id', $poslar->kk_id); ?>
									<?php
										$pos_secilebilir_tipleri = @unserialize($poslar->kk_banka_secilebilir_pos_tipleri);
										$secilebilir_tipler = array();
										foreach($pos_secilebilir_tipleri as $key => $value)
										{
											$secilebilir_tipler[$key] = ucwords($key);
										}
										echo form_dropdown('pos_bilgi_tipi', $secilebilir_tipler, $poslar->kk_banka_pos_tipi, 'id="'. 'pos_bilgi_tip_' . $poslar->kk_id .'"');
									?>
									<span id="pos_bilgi_tip_hata_<?php echo $poslar->kk_id; ?>" class="error" style="display: inline;"></span>
								</td>
							</tr>
							<tr style="height:30px;width:200px;">
								<td style="width:50px;">
									Gönderim Tipi
								</td>
								<td style="width:50px;">
									<?php
										$pos_secilebilir_test_tipleri = @unserialize($poslar->kk_banka_secilebilir_test_tipleri);
										$secilebilir_test_tipler = array();
										foreach($pos_secilebilir_test_tipleri as $key => $value)
										{
											$secilebilir_test_tipler[$key] = ucwords($key);
										}
										echo form_dropdown('pos_bilgi_test_tipi', $secilebilir_test_tipler, $poslar->kk_banka_test_tipi, 'id="'. 'pos_bilgi_test_tip_' . $poslar->kk_id .'"');
									?>
									<span id="pos_bilgi_test_tip_hata_<?php echo $poslar->kk_id; ?>" class="error" style="display: inline;"></span>
								</td>
							</tr>
							<tr style="height:30px;width:200px;">
								<td style="width:50px;">
									Standart
								</td>
								<td style="width:50px;">
									<?php
										$standart_array = array('0' => 'Hayır', '1' => 'Evet');
										echo form_dropdown('pos_bilgi_standart', $standart_array, $poslar->kk_banka_standart, 'id="'. 'pos_bilgi_standart_' . $poslar->kk_id .'"');
									?>
									<span id="pos_bilgi_standart_hata_<?php echo $poslar->kk_id; ?>" class="error" style="display: inline;"></span>
								</td>
							</tr>
							<tr style="height:30px;width:200px;">
								<td style="width:50px;">
									Taksit
								</td>
								<td style="width:50px;">
									<?php
										$taksit_array = array('0' => 'Hayır', '1' => 'Evet');
										echo form_dropdown('pos_bilgi_taksit', $taksit_array, $poslar->kk_banka_taksit, 'id="'. 'pos_bilgi_taksit_' . $poslar->kk_id .'"');
									?>
									<span id="pos_bilgi_taksit_hata_<?php echo $poslar->kk_id; ?>" class="error" style="display: inline;"></span>
								</td>
							</tr>
							<tr style="height:30px;width:200px;">
								<td style="width:50px;">
									Peşin Komisyonu
								</td>
								<td style="width:50px;">
									<?php
										$pesin_komisyon_array['00'] = '0';
										$pesin_komisyon_array['01'] = '1';
										$pesin_komisyon_array['02'] = '2';
										$pesin_komisyon_array['03'] = '3';
										$pesin_komisyon_array['04'] = '4';
										$pesin_komisyon_array['05'] = '5';
										$pesin_komisyon_array['06'] = '6';
										$pesin_komisyon_array['07'] = '7';
										$pesin_komisyon_array['08'] = '8';
										$pesin_komisyon_array['09'] = '9';
										for($i=10;$i<=99;$i++)
										{
											$pesin_komisyon_array[$i] = $i;
										}
										echo '% ' . form_dropdown('pos_bilgi_pesin_komisyon', $pesin_komisyon_array, $poslar->kk_pesin_komisyon, 'id="'. 'pos_bilgi_pesin_komisyon_' . $poslar->kk_id .'"');
									?>
									<span id="pos_bilgi_pesin_komisyon_hata_<?php echo $poslar->kk_id; ?>" class="error" style="display: inline;"></span>
								</td>
							</tr>
							<tr style="height:30px;width:200px;">
								<td style="width:50px;">
									Pos Durumu
								</td>
								<td style="width:50px;">
									<?php
										$durum_array = array('0' => 'Pasif', '1' => 'Aktif');
										echo form_dropdown('pos_bilgi_durum', $durum_array, $poslar->kk_banka_durum, 'id="'. 'pos_bilgi_durum_' . $poslar->kk_id .'"');
									?>
									<span id="pos_bilgi_durum_hata_<?php echo $poslar->kk_id; ?>" class="error" style="display: inline;"></span>
								</td>
							</tr>
						</table>
					</div>
					<?php
						$pos_tipleri = @unserialize($poslar->kk_banka_bilgi);
						foreach($pos_tipleri as $pos_tip_key => $pos_tip_value)
						{
							echo '<div id="'. $poslar->kk_id .'_tip_'. $pos_tip_key .'" class="vtabs-content">';
							$secilebilir_banka_tipleri = @unserialize($poslar->kk_banka_secilebilir_pos_tipleri);
							if(isset($secilebilir_banka_tipleri->{$pos_tip_key}))
							{
								if($secilebilir_banka_tipleri->{$pos_tip_key}->taksit === '1')
								{
									$taksit = '<span style="color:green;">Taksit desteği var</span>';	
								} else {
									$taksit = '<span style="color:red;">Taksit desteği yok</span>';
								}
							} else {
								$taksit = '<span style="color:orange;">Taksit desteği bilinmiyor</span>';
							}
						?>
						<br /><?php echo ucwords($pos_tip_key); ?> - Ayarları - <?php echo $taksit; ?>
						<br /><br />
						<table class="form">
						<?php
							foreach($pos_tip_value as $pos_tip_value_tips_key => $pos_tip_value_tips_value)
							{
								echo '<tr style="height:30px;width:200px;">
									<td style="width:50px;">'. ucwords($pos_tip_value_tips_key) .' : </td>
									<td style="width:50px;"><input style="text" id="pos_'. $poslar->kk_id .'_'. $pos_tip_key .'_'. $pos_tip_value_tips_key .'" name="pos_bilgi_modelleri['. $poslar->kk_id .']['. $pos_tip_key .']['. $pos_tip_value_tips_key .']" value="'. $pos_tip_value_tips_value .'" /></td>
									</tr>';
							}
						?>
						</table>
						<?php
							echo '</div>';
						}
					?>
				</td>
				</form>
			</tr>
			<tr style="display:none;" id="table_pos_bilgileri_2_<?php echo $poslar->kk_id; ?>">
				<td colspan="2" style="border-top:0;height:30px;">
				</td>
				<td colspan="5" style="border-top:0;line-height:40px;height:40px;text-align:right;">
					<span id="pos_bilgi_guncelle_mesaj_<?php echo $poslar->kk_id; ?>"></span> &nbsp; <span id="pos_bilgi_guncelle_buton_<?php echo $poslar->kk_id; ?>"><a onclick="pos_bilgilerini_guncelle('<?php echo $poslar->kk_id; ?>');" class="buton"><span>Kaydet</span></a></span>
				</td>
			</tr>
			<?php
						echo '<script type="text/javascript"><!--
$(\'#'. $poslar->kk_id .'_tabs_div a\').tabs();
//--></script>';
					}
				}
				else
				{
			?>
			<tr>
				<td colspan="7" style="height:30px;text-align:center;"> -- POS Bulunamadı -- </td>
			</tr>
			<?php
				}
			?>
		</tbody>
	</table>
	</div>

<script type="text/javascript"><!--
function taksit_secenegi(tr_id)
{
	if($('#table_taksit_secenegi_' + tr_id).css('display') == 'none')
	{
		$('#table_taksit_secenegi_' + tr_id).attr('style', 'display:table_row;');
	} else {
		$('#table_taksit_secenegi_' + tr_id).attr('style', 'display:none;');
	}
}

function pos_taksit_guncelle(tr_id, pos_id)
{
	$.ajax({
		type: "POST",
		url: "<?php echo yonetim_url('sistem/odeme_secenekleri/ajax_pos_taksit_duzenle'); ?>",
		data: jQuery('#taksit_duzenle_' + tr_id).serialize(),
		dataType: 'json',
		beforeSend: function() {
			$('#taksit_duzenle_span_id_' + tr_id).html('<img src="<?php echo yonetim_resim(); ?>loading_1.gif" /> &nbsp; Lütfen bekleyiniz...');
		},
		complete: function() {
			$('#taksit_duzenle_span_id_' + tr_id).html('<a href="javascript:;" onclick="pos_taksit_guncelle(\'' + tr_id + '\', \'' + pos_id + '\');">Kaydet</a>');
		},
		success: function(data) {
			if(data.success != '')
			{
				$('#taksitler_tbody_' + pos_id).load('<?php echo yonetim_url("sistem/odeme_secenekleri/ajax_pos_taksit_listele"); ?>/' + pos_id);
				$('#taksit_mesaj_' + pos_id).show();
				$('#taksit_mesaj_' + pos_id).html('<span style="color:green;">' + data.success + '</span>');
				setTimeout("$('#taksit_mesaj_' + " + pos_id + ").slideUp('slow');$('#taksit_mesaj_' + " + pos_id + ").html('');", 5000);
			}

			if(data.error != '')
			{
				$('#taksit_mesaj_' + pos_id).show();
				$('#taksit_mesaj_' + pos_id).html('<span style="color:red;">' + data.error + '</span>');
				setTimeout("$('#taksit_mesaj_' + " + pos_id + ").slideUp('slow');$('#taksit_mesaj_' + " + pos_id + ").html('');", 5000);
			}
		}
	});
}

function pos_taksit_ekle(tr_id)
{
	$.ajax({
		type: "POST",
		url: "<?php echo yonetim_url('sistem/odeme_secenekleri/ajax_pos_taksit_ekle'); ?>",
		data: jQuery('#taksit_ekle_' + tr_id).serialize(),
		dataType: 'json',
		beforeSend: function() {
			$('#taksit_ekle_span_id_' + tr_id).html('<img src="<?php echo yonetim_resim(); ?>loading_1.gif" /> &nbsp; Lütfen bekleyiniz...');
		},
		complete: function() {
			$('#taksit_ekle_span_id_' + tr_id).html('<a href="javascript:;" onclick="pos_taksit_ekle(\'' + tr_id + '\');">Ekle</a>');
		},
		success: function(data) {
			if(data.success != '')
			{
				$('#taksitler_tbody_' + tr_id).load('<?php echo yonetim_url("sistem/odeme_secenekleri/ajax_pos_taksit_listele"); ?>/' + tr_id);
				$('#taksit_mesaj_' + tr_id).show();
				$('#taksit_mesaj_' + tr_id).html('<span style="color:green;">' + data.success + '</span>');
				setTimeout("$('#taksit_mesaj_' + " + tr_id + ").slideUp('slow');$('#taksit_mesaj_' + " + tr_id + ").html('');", 5000);
			}

			if(data.taksit_ekle_taksit_sayisi_error != '')
			{
				$('#taksit_ekle_taksit_sayisi_error_' + tr_id).html('<span style="color:red;">' + data.taksit_ekle_taksit_sayisi_error + '</span>');
			} else {
				$('#taksit_ekle_taksit_sayisi_error_' + tr_id).html('');
			}

			if(data.error != '')
			{
				$('#taksit_mesaj_' + tr_id).show();
				$('#taksit_mesaj_' + tr_id).html('<span style="color:red;">' + data.error + '</span>');
				setTimeout("$('#taksit_mesaj_' + " + tr_id + ").slideUp('slow');$('#taksit_mesaj_' + " + tr_id + ").html('');", 5000);
			}
		}
	});
}

function pos_taksit_sil(tr_id, pos_id)
{
	$.ajax({
		type: "POST",
		url: "<?php echo yonetim_url('sistem/odeme_secenekleri/ajax_pos_taksit_sil'); ?>",
		data: "kkts_id=" + tr_id,
		dataType: 'json',
		beforeSend: function() {
			$('#taksit_sil_span_id' + tr_id).html('<img src="<?php echo yonetim_resim(); ?>loading_1.gif" /> &nbsp; Lütfen bekleyiniz...');
		},
		complete: function() {
			$('#taksit_sil_span_id' + tr_id).html('<a href="javascript:;" onclick="pos_taksit_sil(\'' + tr_id + '\', \'' + pos_id + '\');">Sil</a>');
		},
		success: function(data) {
			if(data.success != '')
			{
				$('#taksitler_tbody_' + pos_id).load('<?php echo yonetim_url("sistem/odeme_secenekleri/ajax_pos_taksit_listele"); ?>/' + pos_id);
				$('#taksit_mesaj_' + pos_id).show();
				$('#taksit_mesaj_' + pos_id).html('<span style="color:green;">' + data.success + '</span>');
				setTimeout("$('#taksit_mesaj_' + " + pos_id + ").slideUp('slow');$('#taksit_mesaj_' + " + pos_id + ").html('');", 5000);
			}

			if(data.error != '')
			{
				$('#taksit_mesaj_' + pos_id).show();
				$('#taksit_mesaj_' + pos_id).html('<span style="color:red;">' + data.error + '</span>');
				setTimeout("$('#taksit_mesaj_' + " + pos_id + ").slideUp('slow');$('#taksit_mesaj_' + " + pos_id + ").html('');", 5000);
			}
		}
	});
}

function pos_bilgileri(tr_id)
{
	if($('#table_pos_bilgileri_' + tr_id).css('display') == 'none')
	{
		$('#table_pos_bilgileri_' + tr_id).attr('style', 'display:table_row;');
	} else {
		$('#table_pos_bilgileri_' + tr_id).attr('style', 'display:none;');
	}

	if($('#table_pos_bilgileri_2_' + tr_id).css('display') == 'none')
	{
		$('#table_pos_bilgileri_2_' + tr_id).attr('style', 'display:table_row;');
	} else {
		$('#table_pos_bilgileri_2_' + tr_id).attr('style', 'display:none;');
	}
}

function pos_bilgilerini_guncelle(tr_id)
{
	$.ajax({
		type: "POST",
		url: "<?php echo yonetim_url('sistem/odeme_secenekleri/ajax_pos_bilgi_duzenle'); ?>",
		data: jQuery('#pos_bilgileri_' + tr_id).serialize(),
		dataType: 'json',
		beforeSend: function() {
			$('#pos_bilgi_guncelle_buton_' + tr_id).css('visibility', 'hidden');
			$('#pos_bilgi_guncelle_mesaj_' + tr_id).html('<img src="<?php echo yonetim_resim(); ?>loading_1.gif" /> &nbsp; Lütfen bekleyiniz...');
		},
		complete: function() {
			$('#pos_bilgi_guncelle_buton_' + tr_id).css('visibility', 'visible');
		},
		success: function(data) {
			$('#pos_bilgi_guncelle_mesaj_' + tr_id).html('');

			if(data.pos_bilgi_tipi_error != '')
			{
				$('#pos_bilgi_tip_hata_' + tr_id).html(data.pos_bilgi_tipi_error);
				$('#pos_bilgi_tip_hata_' + tr_id).css('display', 'inline');
			} else {
				$('#pos_bilgi_tip_hata_' + tr_id).html('');
				$('#pos_bilgi_tip_hata_' + tr_id).css('display', 'none');
			}

			if(data.pos_bilgi_test_tipi_error != '')
			{
				$('#pos_bilgi_test_tip_hata_' + tr_id).html(data.pos_bilgi_test_tipi_error);
				$('#pos_bilgi_test_tip_hata_' + tr_id).css('display', 'inline');
			} else {
				$('#pos_bilgi_test_tip_hata_' + tr_id).html('');
				$('#pos_bilgi_test_tip_hata_' + tr_id).css('display', 'none');
			}

			if(data.pos_bilgi_standart_error != '')
			{
				$('#pos_bilgi_standart_hata_' + tr_id).html(data.pos_bilgi_standart_error);
				$('#pos_bilgi_standart_hata_' + tr_id).css('display', 'inline');
			} else {
				$('#pos_bilgi_standart_hata_' + tr_id).html('');
				$('#pos_bilgi_standart_hata_' + tr_id).css('display', 'none');
			}

			if(data.pos_bilgi_taksit_error != '')
			{
				$('#pos_bilgi_taksit_hata_' + tr_id).html(data.pos_bilgi_taksit_error);
				$('#pos_bilgi_taksit_hata_' + tr_id).css('display', 'inline');
			} else {
				$('#pos_bilgi_taksit_hata_' + tr_id).html('');
				$('#pos_bilgi_taksit_hata_' + tr_id).css('display', 'none');
			}

			if(data.pos_bilgi_pesin_komisyon_error != '')
			{
				$('#pos_bilgi_pesin_komisyon_hata_' + tr_id).html(data.pos_bilgi_pesin_komisyon_error);
				$('#pos_bilgi_pesin_komisyon_hata_' + tr_id).css('display', 'inline');
			} else {
				$('#pos_bilgi_pesin_komisyon_hata_' + tr_id).html('');
				$('#pos_bilgi_pesin_komisyon_hata_' + tr_id).css('display', 'none');
			}

			if(data.pos_bilgi_durum_error != '')
			{
				$('#pos_bilgi_durum_hata_' + tr_id).html(data.pos_bilgi_durum_error);
				$('#pos_bilgi_durum_hata_' + tr_id).css('display', 'inline');
			} else {
				$('#pos_bilgi_durum_hata_' + tr_id).html('');
				$('#pos_bilgi_durum_hata_' + tr_id).css('display', 'none');
			}

			if(data.pos_bilgi_standart == '1')
			{
				$('.standart_class').html('Hayır');
				$('#standart_span_' + tr_id).html('Evet');
			}

			if(data.success != '')
			{
				$('#pos_bilgi_guncelle_mesaj_' + tr_id).html('<span style="color:green;">'+ data.success +'</span>');
			}

			if(data.error != '')
			{
				$('#pos_bilgi_guncelle_mesaj_' + tr_id).html('<span style="color:red;">'+ data.error +'</span>');
			}
		}
	});
}
//--></script>

</div>
<?php $this->load->view('yonetim/footer_view'); ?>