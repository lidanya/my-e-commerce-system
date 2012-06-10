<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="" lang="" xml:lang="">
	<head>
		<title><?php echo config('site_ayar_baslik'); ?></title>
		<base href="<?php echo base_url(); ?>" />
		<style>
		body {background: #FFFFFF;}
		body, td, th, input, select, textarea, option, optgroup {font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;color: #000000;}
		h1 {text-transform: uppercase;color: #CCCCCC;text-align: right;font-size: 24px;font-weight: normal;padding-bottom: 5px;margin-top: 0px;margin-bottom: 15px;border-bottom: 1px solid #CDDDDD;}
		.div1 {width: 100%;margin-bottom: 20px;}
		.div2 {float: left;display: inline-block;}
		.div3 {float: right;display: inline-block;padding: 5px;}
		.heading td {background: #E7EFEF;}
		.address, .product {border-collapse: collapse;}
		.address {width: 100%;margin-bottom: 20px;border-top: 1px solid #CDDDDD;border-right: 1px solid #CDDDDD;}
		.address th, .address td {border-left: 1px solid #CDDDDD;border-bottom: 1px solid #CDDDDD;padding: 5px;}
		.address td {width: 50%;}
		.product {width: 100%;margin-bottom: 20px;border-top: 1px solid #CDDDDD;border-right: 1px solid #CDDDDD;}
		.product td {border-left: 1px solid #CDDDDD;border-bottom: 1px solid #CDDDDD;padding: 5px;}
		</style>
	</head>

	<body>
	<?php
		foreach($siparisler as $siparis)
		{
			$siparis_sorgu = $this->db->get_where('siparis', array('siparis_id' => $siparis), 1);
			if($siparis_sorgu->num_rows() > 0)
			{
				$siparis_bilgi = $siparis_sorgu->row();

				$this->db->select_sum('stok_tfiyat');
				$toplam_tfiyat_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_bilgi->siparis_id));
				$toplam_tfiyat_bilgi = $toplam_tfiyat_sorgu->row();
				$stok_toplam_fiyat = $toplam_tfiyat_bilgi->stok_tfiyat;

				$siparis_data = false;
				if($siparis_bilgi->siparis_data)
				{
					if(is_serialized($siparis_bilgi->siparis_data))
					{
						$siparis_data = @unserialize($siparis_bilgi->siparis_data);
						//echo debug($siparis_data);
					}
				}

				/* İndirim Oran & Fiyat Hesaplaması */
				$indirim_orani = '00';
				if($siparis_data)
				{
					if(array_key_exists('teslimat_bilgileri', $siparis_data))
					{
						if(array_key_exists('indirim_orani', $siparis_data['teslimat_bilgileri']))
						{
							$indirim_orani = $siparis_data['teslimat_bilgileri']['indirim_orani'];
						}
					}
				}

				$indirim_ucret = 0;
				if($indirim_orani != '00')
				{
					$indirim_ucret_hesapla = ($stok_toplam_fiyat * ((100-$indirim_orani)/100));
					$indirim_ucret = (float) ($stok_toplam_fiyat - $indirim_ucret_hesapla);
					$stok_toplam_fiyat = ($stok_toplam_fiyat - $indirim_ucret);
				}
				/* İndirim Oran & Fiyat Hesaplaması */

				/* Kargo Ücret Hesaplaması */
				$kargo_ucret = 0;
				if($siparis_data)
				{
					if(array_key_exists('teslimat_bilgileri', $siparis_data))
					{
						if(array_key_exists('kargo_ucret', $siparis_data['teslimat_bilgileri']))
						{
							$kargo_ucret = (float) $siparis_data['teslimat_bilgileri']['kargo_ucret'];
						}
					}
				}
				/* Kargo Ücret Hesaplaması */

				/* Kapıda Ödeme Ücret Hesaplaması */
				$kapida_odeme_ucret = 0;
				if($siparis_data)
				{
					if(array_key_exists('teslimat_bilgileri', $siparis_data))
					{
						if(array_key_exists('kapida_odeme_ucret', $siparis_data['teslimat_bilgileri']))
						{
							$kapida_odeme_ucret = (float) $siparis_data['teslimat_bilgileri']['kapida_odeme_ucret'];
						}
					}
				}
				/* Kapıda Ödeme Ücret Hesaplaması */

				/* Kdv Hesaplama Başlangıç */
				$toplam_kdv_fiyati = 0;
				/* Kdv Hesaplama Başlangıç */

				$inv_ide_bilgi = false;
				$inv_ide_sorgu = $this->db->get_where('usr_inv_inf', array('inv_id' => $siparis_bilgi->usr_inv_id), 1);
				if($inv_ide_sorgu->num_rows() > 0)
				{
					$inv_ide_bilgi = $inv_ide_sorgu->row();
				}
				?>
				<div style="page-break-after: always;">
					<h1><?php echo config('firma_adi');?></h1>

					<div class="div1">
						<table width="100%">
							<tr>
								<td>
									<b>İletişim Bilgileri</b>
									<br>
									<?php 
										$name = ($inv_ide_bilgi && $inv_ide_bilgi->inv_username) ? $inv_ide_bilgi->inv_username : null;
										$surname = ($inv_ide_bilgi && $inv_ide_bilgi->inv_usersurname) ? $inv_ide_bilgi->inv_usersurname : null;
										$username =  $name . ' ' . $surname;
									?>
									<?php echo ($name) ? $username : null; ?>
									<br>
									<?php echo ($inv_ide_bilgi && $inv_ide_bilgi->inv_adr_id) ? $inv_ide_bilgi->inv_adr_id : null; ?>
									<br>
									<?php echo ($inv_ide_bilgi && $inv_ide_bilgi->inv_tel) ? $inv_ide_bilgi->inv_tel : null; ?>
									<br>
									<?php echo ($inv_ide_bilgi && $inv_ide_bilgi->inv_fax) ? $inv_ide_bilgi->inv_fax : null; ?>
								</td>
							</tr>
						</table>
					</div>

					<table class="address">
						<tr class="heading">
							<td width="50%"><b>Firma Adresi</b></td>
						</tr>
						<tr>
							<td><?php echo config('site_ayar_sirket_adres'); ?></td>
						</tr>
					</table>

					<table class="product">
						<tr class="heading">
							<td style="width:50%;text-align:left;"><b>Ürün Adı</b></td>
							<td><b>Stok Kodu</b></td>
							<td align="right" style="width:8%;text-align:center;"><b>Miktarı</b></td>
							<td align="right" style="width:10%;text-align:center;"><b>Ürün Birim Fiyatı</b></td>
							<td align="right" style="width:10%;text-align:center;"><b>Ürün Toplam Fiyatı</b></td>
						</tr>

						<!-- Ürünler -->
						<?php
							$siparis_urunleri = $this->db->get_where('siparis_detay',array('siparis_id' => $siparis_bilgi->siparis_id));
							if($siparis_urunleri->num_rows() > 0) 
							{
								foreach($siparis_urunleri->result() as $siparis_urun)
								{
									
									/* Kdv Hesaplama Başlangıç */
									if(config('site_ayar_kdv_goster') == '1')
									{
										$kdv_fiyati = kdv_hesapla($siparis_urun->stok_tfiyat, $siparis_urun->stok_kdv_orani, true);
										$toplam_kdv_fiyati += $kdv_fiyati;
									}
									/* Kdv Hesaplama Başlangıç */
								?>
								<tr>
									<td>
										<?php
											/*
											$stok_adi_sorgu = $this->db->get_where('stok', array('stok_kod' => $siparis_urun->stok_kodu), 1);
											if($stok_adi_sorgu->num_rows() > 0)
											{
												$stok_adi_bilgi = $stok_adi_sorgu->row();
												echo $stok_adi_bilgi->stok_adi;
											} else {
												echo 'Stok Adını Alamadım';
											}
											*/
											
											$stok_adi_sorgu = $this->db->join('product','product.model = siparis_detay.stok_kodu','left')
											->join('product_description','product_description.product_id = product.product_id','left')
											->get_where('siparis_detay', array('stok_kodu' => $siparis_urun->stok_kodu), 1);
											if($stok_adi_sorgu->num_rows() > 0)
											{
												$stok_adi_bilgi = $stok_adi_sorgu->row();
												
												echo $stok_adi_bilgi->name;
											} else {
												echo 'Stok Adını Alamadım';
											}
										
											
										?>
										<?php
											if(is_serialized($siparis_urun->siparis_det_data))
											{
												$siparis_detay_data = @unserialize($siparis_urun->siparis_det_data);
										?>
												<br /><span>
												<?php
													foreach ($siparis_detay_data['secenek'] as $option_row)
													{
												?>
													&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $option_row['name']; ?> :
													<?php
														if(isset($option_row['option_value']))
														{
																if($option_row['price'] > 0)
																{
																	$_onek = ' (' . $option_row['price_prefix'] . format_number($option_row['price']) . ') TL';
																} else {
																	$_onek = null;
																}
															echo $option_row['option_value'] . $_onek;
														}
													?>
											<br />
												<?php
													}
												?>
										<?php
											}
										?>
									</td>
									<td>
										<?php echo $siparis_urun->stok_kodu; ?>
									</td>
									<td align="right" style="text-align:center;"><?php echo $siparis_urun->stok_miktar; ?>&nbsp;<?php
						$tanim_bilgi = $this->yonetim_model->tanimlar_bilgi('stok_birim', $siparis_urun->stok_tip);
						if($tanim_bilgi->num_rows() > 0)
						{
							$tanim_bilgi_b = $tanim_bilgi->row();
							echo '<font style="cursor:pointer;" title="'. $tanim_bilgi_b->tanimlar_adi .'">' . $tanim_bilgi_b->tanimlar_kod . '</font>';
						} else {
							echo '<font style="cursor:pointer;" title="Ürün Birimi Bulunamadı">bln</font>';
						}
					?></td>
									<td align="right"><?php echo $this->cart->format_number($siparis_urun->stok_bfiyat); ?> TL</td>
									<td align="right"><?php echo $this->cart->format_number($siparis_urun->stok_tfiyat); ?> TL</td>
								</tr>
								<?php
								if($siparis_urun->stok_aciklama != '')
								{
								?>
								<tr>
									<td colspan="4">
										<table class="product">
											<tr class="heading">
												<td><b>Açıklama</b></td>
											</tr>
											<tr>
												<td><?php echo $siparis_urun->stok_aciklama; ?></td>
											</tr>
										</table>
									</td>
								</tr>
								<?php	
								}
								?>
								<?php
								}
							}
						?>
						<!-- /Ürünler -->
						</table>

						<table class="address">
							<tr class="heading">
								<td align="left" colspan="3"><b>Fiyatlandırma</b></td>
								<td align="right">
									Fiyat
								</td>
							</tr>
							<tr>
								<td align="right" style="width:90%;" colspan="3"><b>Ara Toplamı</b></td>
								<td align="right" style="width:10%;">
									<?php 
										echo $this->cart->format_number($toplam_tfiyat_bilgi->stok_tfiyat) .' TL';
									?>
								</td>
							</tr>
							<?php if($indirim_ucret != '0') { ?>
							<tr>
								<td align="right" style="width:90%;" colspan="3"><b>İndirim Toplamı</b></td>
								<td align="right" style="width:10%;color:green;">
									<?php 
										echo '-' . $this->cart->format_number($indirim_ucret) .' TL';
									?>
								</td>
							</tr>
							<?php } ?>
							<?php if($kargo_ucret != '0') { ?>
							<tr>
								<td align="right" style="width:90%;" colspan="3"><b>Kargo Toplamı</b></td>
								<td align="right" style="width:10%;">
									<?php 
										echo $this->cart->format_number($kargo_ucret) .' TL';
									?>
								</td>
							</tr>
							<?php } ?>
							<?php if($kapida_odeme_ucret != '0') { ?>
							<tr>
								<td align="right" style="width:90%;" colspan="3"><b>Kapıda Ödeme Toplamı</b></td>
								<td align="right" style="width:10%;">
									<?php 
										echo $this->cart->format_number($kapida_odeme_ucret) .' TL';
									?>
								</td>
							</tr>
							<?php } ?>
							<?php if(config('site_ayar_kdv_goster') == '1') { ?>
							<tr>
								<td align="right" style="width:90%;" colspan="3"><b>KDV Toplamı</b></td>
								<td align="right" style="width:10%;">
									<?php 
										echo $this->cart->format_number($toplam_kdv_fiyati) .' TL';
									?>
								</td>
							</tr>
							<?php } ?>
							<?php if(config('site_ayar_kdv_goster') == '1') { ?>
							<tr>
								<td align="right" style="width:90%;" colspan="3"><b>Genel Toplamı</b></td>
								<td align="right" style="width:10%;">
									<?php 
										echo $this->cart->format_number($stok_toplam_fiyat + $toplam_kdv_fiyati + $kargo_ucret + $kapida_odeme_ucret) .' TL';
									?>
								</td>
							</tr>
							<?php } else { ?>
							<tr>
								<td align="right" style="width:90%;" colspan="3"><b>Genel Toplamı</b></td>
								<td align="right" style="width:10%;">
									<?php 
										echo $this->cart->format_number($stok_toplam_fiyat + $kargo_ucret + $kapida_odeme_ucret) .' TL';
									?>
								</td>
							</tr>
							<?php } ?>
						</table>
				</div>
				<?php
			}
		}
	?>
	</body>
</html>