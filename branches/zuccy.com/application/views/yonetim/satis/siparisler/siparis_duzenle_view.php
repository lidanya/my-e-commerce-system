<?php 
$this->load->view('yonetim/header_view');  

if($siparis->num_rows()<1)
{
	redirect(yonetim_url('satis/siparisler'));
}

$order = $siparis->row();

$user_ide = $this->yonetim_model->kullanici_bilgi_getir($order->user_id);
$inv_ide = $this->db->get_where('usr_inv_inf',array('inv_id'=>$order->usr_inv_id) ,1)->row();

?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim();?>order.png');">Sipariş Düzenle</h1>
		<div class="buttons">
			<a href="javascript:void(0);" onclick="$('#form').attr('action','<?php echo current_url(); ?>').submit();" class="buton"><span>Kaydet</span></a>
			<a href="<?php echo yonetim_url('satis/siparisler'); ?>" class="buton" style="margin-left:10px;"><span>İptal</span></a>
		</div>
	</div>

	<div class="content">
		<div style="display: inline-block; width: 100%;">
			<div class="vtabs">
				<a tab="#tab_order">Sipariş Detayı</a>
				<a tab="#tab_fatura">Fatura Bilgileri</a>
				<a tab="#tab_teslimat">Teslimat Bilgileri</a>
				<a tab="#tab_product">Ürünler</a>
			</div>

			<form action="" method="post" enctype="multipart/form-data" id="form">

			<?php
				$this->db->select_sum('stok_tfiyat');
				$toplam_tfiyat_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $order->siparis_id));
				$toplam_tfiyat_bilgi = $toplam_tfiyat_sorgu->row();
				$stok_toplam_fiyat = $toplam_tfiyat_bilgi->stok_tfiyat;

				$siparis_data = false;
				if($order->siparis_data)
				{
					if(is_serialized($order->siparis_data))
					{
						$siparis_data = @unserialize($order->siparis_data);
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

				/* Kupon İndirim Ücret Hesaplaması */
				$kupon_ucret = 0;
				if($siparis_data)
				{
					if(array_key_exists('teslimat_bilgileri', $siparis_data))
					{
						if(array_key_exists('kupon_indirim', $siparis_data['teslimat_bilgileri']))
						{
							if(array_key_exists('fiyat', $siparis_data['teslimat_bilgileri']['kupon_indirim']))
							{
								$kupon_ucret = (float) $siparis_data['teslimat_bilgileri']['kupon_indirim']['fiyat'];
							}
						}
					}
				}
				/* Kupon İndirim Ücret Hesaplaması */

				/* Kdv Hesaplama Başlangıç */
				$toplam_kdv_fiyati = 0;
				/* Kdv Hesaplama Başlangıç */
			?>

			<div id="tab_product" class="vtabs-content">
				<table id="products" class="list">
					<thead>
						<tr>
							<td class="left">Ürün Adı</td>
							<td class="right">Ürün Miktarı</td>
							<td class="right">Ürün Birim Fiyatı</td>
							<td class="right" width="150">Ürün Toplam Fiyatı</td>
						</tr>
					</thead>

					<?php
						$language_id = get_language('language_id', config('site_ayar_yonetim_dil'));
						$this->db->select(
							get_fields_from_table('product', 'p.', array(), ', ') . 
							get_fields_from_table('product_description', 'pd.', array(), ', ') . 
							get_fields_from_table('siparis_detay', 'sd.', array(), '')
						);
						$this->db->from('siparis_detay sd');
						$this->db->join('product p', 'sd.stok_kodu = p.model', 'left');
						$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
						$this->db->where('sd.siparis_id', (int) $order->siparis_id);
						$this->db->where('pd.language_id', (int) $language_id);
						$toplam_fiyat_sorgu = $this->db->get();

						if($toplam_fiyat_sorgu->num_rows())
						{
							$other_product = $toplam_fiyat_sorgu->result();
					?>
					<tbody id="product">
						<?php
							foreach ($other_product as $row)
							{
								/* Kdv Hesaplama Başlangıç */
								if(config('site_ayar_kdv_goster') == '1')
								{
									$kdv_fiyati = kdv_hesapla($row->stok_tfiyat, $row->tax, FALSE);
									$toplam_kdv_fiyati += $kdv_fiyati;
								}
								/* Kdv Hesaplama Başlangıç */
						?>
							<tr>
								<td class="left">
									<span style="font-weight:bold;">Ürün Kodu : </span><?php echo $row->model; ?></br />
									<span style="font-weight:bold;">Ürün Adı : </span><?php echo $row->name; ?>
									<?php
										if(is_serialized($row->siparis_det_data))
										{
											$siparis_detay_data = @unserialize($row->siparis_det_data);
									?>
										<br />
										<span style="font-weight:bold;">Ürün Seçeneği : </span>
										<br /><span>
											<?php
												//echo debug($siparis_detay_data['secenek']);
												foreach ($siparis_detay_data['secenek'] as $option_row)
												{
											?>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<?php echo character_limiter($option_row['name'], 25); ?> : 
											<?php
												$_onek = null;
												if($option_row['price_prefix']) {
													if($option_row['price'] > 0) {
														$_onek = ' (' . $option_row['price_prefix'] . format_number($option_row['price']) . ') TL';
													} else {
														$_onek = null;
													}
												}
												if($option_row['type'] == 'file') {
													echo '<a target="_blank" href="'. base_url() .'upload/download/'. $option_row['option_value'] .'">' . $option_row['option_value'] . '</a>';
												} else {
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
								<td class="right"><?php echo $row->stok_miktar;?>&nbsp;<?php
						$tanim_bilgi = $this->yonetim_model->tanimlar_bilgi('stok_birim', $row->stock_type);
						if($tanim_bilgi->num_rows() > 0)
						{
							$tanim_bilgi_b = $tanim_bilgi->row();
							echo '<font style="cursor:pointer;" title="'. $tanim_bilgi_b->tanimlar_adi .'">' . $tanim_bilgi_b->tanimlar_kod . '</font>';
						} else {
							echo '<font style="cursor:pointer;" title="Ürün Birimi Bulunamadı">bln</font>';
						}
					?></td>
								<td class="right"><?php echo $this->cart->format_number($row->stok_bfiyat); ?> TL</td>
								<td class="right"><?php echo $this->cart->format_number($row->stok_tfiyat); ?> TL</td>
							</tr>
						<?php
							}
						?>
					</tbody>

					<tbody id="totals">
						<tr>
							<td colspan="3" class="right"><span style="text-align:right;">Ara Toplamı</span></td>
							<td class="right">
								<?php
									echo $this->cart->format_number($toplam_tfiyat_bilgi->stok_tfiyat) .' TL';
								?>
							</td>
						</tr>

						<?php if($indirim_ucret != '0') { ?>
						<tr>
							<td colspan="3" class="right"><span style="text-align:right;">İndirim Toplamı</span></td>
							<td class="right">
								<?php
									echo '-' . $this->cart->format_number($indirim_ucret) .' TL';
								?>
							</td>
						</tr>
						<?php } ?>

						<?php if($kargo_ucret != '0') { ?>
						<tr>
							<td colspan="3" class="right"><span style="text-align:right;">Kargo Toplamı</span></td>
							<td class="right">
								<?php
									echo $this->cart->format_number($kargo_ucret) .' TL';
								?>
							</td>
						</tr>
						<?php } ?>

						<?php if($kapida_odeme_ucret != '0') { ?>
						<tr>
							<td colspan="3" class="right"><span style="text-align:right;">Kapıda Ödeme Toplamı</span></td>
							<td class="right">
								<?php
									echo $this->cart->format_number($kapida_odeme_ucret) .' TL';
								?>
							</td>
						</tr>
						<?php } ?>

						<?php if($kupon_ucret != '0') { ?>
						<tr>
							<td colspan="3" class="right"><span style="text-align:right;">Kupon Kodu</span></td>
							<td class="right">
								<?php echo $siparis_data['teslimat_bilgileri']['kupon_indirim']['kupon']; ?>
							</td>
						</tr>
						<tr>
							<td colspan="3" class="right"><span style="text-align:right;">Kupon İndirim Toplamı</span></td>
							<td class="right">
								<?php
									echo '-' . $this->cart->format_number($kupon_ucret) .' TL';
								?>
							</td>
						</tr>
						<?php } ?>

						<?php if(config('site_ayar_kdv_goster') == '1') { ?>
						<tr>
							<td colspan="3" class="right"><span style="text-align:right;">KDV Toplamı</span></td>
							<td class="right">
								<?php
									echo $this->cart->format_number($toplam_kdv_fiyati) .' TL';
								?>
							</td>
						</tr>
						<?php } ?>
						<tr>
							<td colspan="3" class="right"><span style="text-align:right;">Genel Toplamı</span></td>
							<td class="right">
								<?php
									$total_price = 0;
									$total_price += $stok_toplam_fiyat;
									
									
								$this->db->select('stok_kdv_orani');
						        $this->db->from('siparis_detay');
								$this->db->where('siparis_id', (int) $order->siparis_id);
					        	$toplam_fiyatikdv = $this->db->get();
								$kdvorani=$toplam_fiyatikdv->row()->stok_kdv_orani+1;
                                if(config('site_ayar_kdv_goster') == '0'){ $total_price=$total_price*$kdvorani;}


									if(config('site_ayar_kdv_goster') == '1' && $kdvorani>1 ) {
										$total_price += $toplam_kdv_fiyati;
									}
									if($kargo_ucret > 0) {
										$total_price += $kargo_ucret;
									}
									if($kapida_odeme_ucret > 0) {
										$total_price += $kapida_odeme_ucret;
									}
									if($kupon_ucret > 0) {
										$total_price -= $kupon_ucret;
									}
									if($total_price <= 0) {
										$total_price = 0.01;
									}
									echo format_number($total_price) . ' TL';
								?>
							</td>
						</tr>
					</tbody>
				<?php 
					}
				?>
				</table>
			</div>

			<?php
				$serialize_durum = is_serialized($order->siparis_data);
				$siparis_data = @unserialize($order->siparis_data);
			?>

			<div id="tab_order" class="vtabs-content">
			<table class="form">
				<tr>
					<td>Sipariş ID</td>
					<td># <?php echo $order->siparis_id;?></td>
				</tr>
				<?php
				if($order->siparis_data)
				{
					$siparis_data = @unserialize($order->siparis_data);
					if(array_key_exists('odeme_tipi', $siparis_data) && array_key_exists('odeme_secenegi', $siparis_data) && array_key_exists('odeme_secenegi_detay', $siparis_data))
					{
						$odeme_tipi = $siparis_data['odeme_tipi'];
						$odeme_secenegi = $siparis_data['odeme_secenegi'];
						$odeme_secenegi_detay = $siparis_data['odeme_secenegi_detay'];
						if($odeme_tipi == 'havale')
						{
							$odeme_secenek_sorgu = $this->db->get_where('odeme_secenek_havale', array('havale_id' => $siparis_data['odeme_secenegi']), 1);
							if($odeme_secenek_sorgu->num_rows() > 0)
							{
								$odeme_secenegi = $odeme_secenek_sorgu->row()->havale_banka_baslik;
							} else {
								$odeme_secenegi = false;
							}

							$odeme_secenegi_detay_sorgu = $this->db->get_where('odeme_secenek_havale_detay', array('havale_detay_id' => $siparis_data['odeme_secenegi_detay']), 1);
							if($odeme_secenegi_detay_sorgu->num_rows() > 0)
							{
								$odeme_secenegi_detay = $odeme_secenegi_detay_sorgu->row()->iban_no;
							} else {
								$odeme_secenegi_detay = false;
							}

							$detay_deger = '';
							$detay_deger .= ($odeme_secenegi) ? ' ' . $odeme_secenegi . ' - ':' Belirsiz - ';
							$detay_deger .= ($odeme_secenegi_detay) ? ' ' . $odeme_secenegi_detay . ' ':' Belirsiz ';
							$indirim = NULL;
							if(array_key_exists('indirim_orani', $siparis_data['teslimat_bilgileri']))
							{
								if($siparis_data['teslimat_bilgileri']['indirim_orani'] != '00')
								{
									$cevir = array('01' => '1', '02' => '2', '03' => '3', '04' => '4', '05' => '5', '06' => '6', '07' => '7', '08' => '8', '09' => '9');
									$indirim_c = strtr($siparis_data['teslimat_bilgileri']['indirim_orani'], $cevir);
									$indirim = ' <span style="color:green;font-size:14px;font-weight:bold;"> (%'. $indirim_c .' indirim!)</span>';
								}
							}
							$odeme_detayi = 'Havale ('. $detay_deger .')' . $indirim;
						} elseif ($odeme_tipi == 'kredi_karti') {
							$detay_deger = '';

							$odeme_secenegi_detay_sorgu = $this->db->get_where('odeme_secenek_kredi_karti', array('kk_id' => $odeme_secenegi_detay), 1);
							if($odeme_secenegi_detay_sorgu->num_rows() > 0)
							{
								$odeme_secenegi_detay_bilgi = $odeme_secenegi_detay_sorgu->row();
								$odeme_secenegi_detay = $odeme_secenegi_detay_bilgi->kk_banka_adi;
							} else {
								$odeme_secenegi_detay = null;
							}

							$detay_deger_ = '';

							if($odeme_secenegi !== '1')
							{
								$detay_deger_ .= ' Banka : ' . $odeme_secenegi_detay . ' ';
							}
							if(array_key_exists('kredi_kart_no', $siparis_data))
							{
								$detay_deger .= ' Kart Numarası : ' . $siparis_data['kredi_kart_no'] . ' ';
							} else {
								$detay_deger .= ' Kredi Kart Numarasını Alamadım ';
							}
							if(array_key_exists('taksit_sayisi', $siparis_data))
							{
								$detay_deger .= ($siparis_data['taksit_sayisi'] == 1 || $siparis_data['taksit_sayisi'] == 0) ? ' Peşin ':' Taksit Sayısı : ' . $siparis_data['taksit_sayisi'] . ' ';
							} else {
								$detay_deger .= ' Taksit Sayısını Alamadım ';
							}
							$odeme_detayi = 'Kredi Kartı'. $detay_deger_ .' ('. $detay_deger .')';
						} elseif ($odeme_tipi == 'kapida_odeme') {
							$odeme_detayi = 'Kapıda Ödeme';
						}
					} else {
						$odeme_detayi = 'Belirtilmemiş';
					}
				} else {
					$odeme_detayi = 'Belirtilmemiş';
				}
				?>
				<tr>
					<td>Ödeme Tipi</td>
					<td><?php echo $odeme_detayi; ?></td>
				</tr>
				<tr>
					<td>Müşteri Grubu</td>
					<td>
					<?php 
						$this->db->where('id', $user_ide->role_id);
						$sonuc = $this->db->get('roles', 1);
						if($sonuc->num_rows() > 0)
						{
							echo $sonuc->row()->name;
						} else {
							echo '<span style="color:red;">Belirtilmemiş</span>';
						}
					?>
					</td>
				</tr>
				<tr>
					<td>Müşteri İsmi</td>
					<td>
						<a href="<?php echo yonetim_url('customer_management/customer/edit/'. $order->user_id); ?>" title="Üye Profilini incelemek için tıklayın" target="_blank"><?php echo (!empty($inv_ide->inv_username) ? $inv_ide->inv_username : '');?> <?php echo (!empty($inv_ide->inv_usersurname) ? $inv_ide->inv_usersurname : '');?></a>
					</td>
				</tr>
				<tr>
					<td>Müşteri E-Posta</td>
					<td>
						<a href="<?php echo yonetim_url('customer_management/customer/edit/'. $order->user_id); ?>" title="Üye Profilini incelemek için tıklayın" target="_blank"><?php echo $user_ide->email;?></a>
					</td>
				</tr>
				<tr>
					<td>Sipariş Tarihi</td>
					<td>
						<?php echo standard_date('DATE_TR', $order->kayit_tar, 'tr'); ?>
					</td>
				</tr>
				<tr>
					<td>Toplam Fiyat</td>
					<td>
						<?php
							echo $this->cart->format_number($total_price) . ' TL';
						?>
					</td>
				</tr>
				<tr>
					<td>Sipariş Durum</td>
					<td id="order_status">
						<?php
							$secili = $order->siparis_flag;
							echo form_dropdown_from_db('siparis_durum', "SELECT siparis_durum_tanim_id,siparis_durum_baslik FROM ". $this->db->dbprefix('siparis_durum') ."", false, $secili);
						?>
					</td>
				</tr>
				<tr>
					<td>Sipariş Notu</td>
					<td>
						<p>
							<?php echo isset($siparis_data['teslimat_bilgileri']['siparis_not']) ? $siparis_data['teslimat_bilgileri']['siparis_not'] : 'Sipariş notu yazılmamıştır!'; ?>
						</p>
					</td>
				</tr>
				<tr>
					<td>Sipariş Durum Açıklama</td>
					<td>
						<textarea name="siparis_aciklama" style="width:250px; height: 100px;"><?php echo $order->siparis_flag_data;?></textarea>
					</td>
				</tr>
			</table>
		</div>

		<div id="tab_fatura" class="vtabs-content">
			<table class="form">
				<tr>
					<td>TC Kimlik No</td>
					<td>
						<?php echo (!empty($inv_ide->inv_tckimlik) ? $inv_ide->inv_tckimlik : '<span style="color:red;">Belirtilmemiş.</span>');?>
					</td>
				</tr>
				<tr>
					<td>Firma Adı</td>
					<td>
						<?php echo (!empty($inv_ide->inv_firma) ? $inv_ide->inv_firma : '<span style="color:red;">Belirtilmemiş.</span>');?>
					</td>
				</tr>
				<tr>
					<td>Adres</td>
					<td>
						<?php echo (!empty($inv_ide->inv_adr_id) ? $inv_ide->inv_adr_id : '<span style="color:red;">Belirtilmemiş.</span>');?>
					</td>
				</tr>
				<tr>
					<td>Ülke</td>
					<td>
						<?php echo (!empty($inv_ide->inv_ulke) ? ulke_adi($inv_ide->inv_ulke) : '<span style="color:red;">Belirtilmemiş.</span>');?>
					</td>
				</tr>
				<tr>
					<td>Şehir</td>
					<td>
						<?php echo (!empty($inv_ide->inv_sehir) ? sehir_adi($inv_ide->inv_sehir) : '<span style="color:red;">Belirtilmemiş.</span>');?>
					</td>
				</tr>
				<tr>
					<td>İlçe</td>
					<td>
						<?php echo (!empty($inv_ide->inv_ilce) ? $inv_ide->inv_ilce : '<span style="color:red;">Belirtilmemiş.</span>');?>
					</td>
				</tr>
				<tr>
					<td>Posta Kodu</td>
					<td>
						<?php echo (!empty($inv_ide->inv_pkodu) ? $inv_ide->inv_pkodu : '<span style="color:red;">Belirtilmemiş.</span>');?>
					</td>
				</tr>
				<tr>
					<td>Telefon</td>
					<td>
						<?php echo (!empty($inv_ide->inv_tel) ? $inv_ide->inv_tel : '<span style="color:red;">Belirtilmemiş.</span>');?>
					</td>
				</tr>
				<tr>
					<td>Faks</td>
					<td>
						<?php echo (!empty($inv_ide->inv_fax) ? $inv_ide->inv_fax : '<span style="color:red;">Belirtilmemiş.</span>');?>
					</td>
				</tr>
				<tr>
					<td>Vergi Dairesi</td>
					<td>
						<?php echo (!empty($inv_ide->inv_vda) ? $inv_ide->inv_vda : '<span style="color:red;">Belirtilmemiş.</span>');?>
					</td>
				</tr>
				<tr>
					<td>Vergi Numarası</td>
					<td>
						<?php echo (!empty($inv_ide->inv_vno) ? $inv_ide->inv_vno : '<span style="color:red;">Belirtilmemiş.</span>');?>
					</td>
				</tr>
			</table>
		</div>

		<div id="tab_teslimat" class="vtabs-content">
			<table class="form">
				<?php if(is_array($siparis_data) && $serialize_durum) { ?>
				<?php if(array_key_exists('teslimat_bilgileri', $siparis_data)) { ?>
				<?php if(array_key_exists('teslimat', $siparis_data['teslimat_bilgileri'])) { ?>
				<tr>
					<td>Ad Soyad : </td>
					<?php
					if(array_key_exists('ad_soyad', $siparis_data['teslimat_bilgileri']['teslimat']))
					{
						echo '<td>' . $siparis_data['teslimat_bilgileri']['teslimat']['ad_soyad'] . '</td>';
					} else {
						echo '<td><span style="color:red;">Belirtilmemiş</span></td>';
					}
					?>
				</tr>
				<tr>
					<td>Adres : </td>
					<?php
					if(array_key_exists('adres', $siparis_data['teslimat_bilgileri']['teslimat']))
					{
						echo '<td>' . $siparis_data['teslimat_bilgileri']['teslimat']['adres'] . '</td>';
					} else {
						echo '<td><span style="color:red;">Belirtilmemiş</span></td>';
					}
					?>
				</tr>
				<tr>
					<td>Ülke : </td>
					<?php
					if(array_key_exists('ulke', $siparis_data['teslimat_bilgileri']['teslimat']))
					{
						echo '<td>' . ulke_adi($siparis_data['teslimat_bilgileri']['teslimat']['ulke']) . '</td>';
					} else {
						echo '<td><span style="color:red;">Belirtilmemiş</span></td>';
					}
					?>
				</tr>
				<tr>
					<td>Şehir : </td>
					<?php
					if(array_key_exists('sehir', $siparis_data['teslimat_bilgileri']['teslimat']))
					{
						echo '<td>' . sehir_adi($siparis_data['teslimat_bilgileri']['teslimat']['sehir']) . '</td>';
					} else {
						echo '<td><span style="color:red;">Belirtilmemiş</span></td>';
					}
					?>
				</tr>
				<tr>
					<td>İlçe : </td>
					<?php
					if(array_key_exists('ilce', $siparis_data['teslimat_bilgileri']['teslimat']))
					{
						echo '<td>' . $siparis_data['teslimat_bilgileri']['teslimat']['ilce'] . '</td>';
					} else {
						echo '<td><span style="color:red;">Belirtilmemiş</span></td>';
					}
					?>
				</tr>
				<tr>
					<td>Posta Kodu : </td>
					<?php
					if(array_key_exists('posta_kodu', $siparis_data['teslimat_bilgileri']['teslimat']))
					{
						echo '<td>' . $siparis_data['teslimat_bilgileri']['teslimat']['posta_kodu'] . '</td>';
					} else {
						echo '<td><span style="color:red;">Belirtilmemiş</span></td>';
					}
					?>
				</tr>
				<tr>
					<td>Telefon : </td>
					<?php
					if(array_key_exists('telefon', $siparis_data['teslimat_bilgileri']['teslimat']))
					{
						echo '<td>' . $siparis_data['teslimat_bilgileri']['teslimat']['telefon'] . '</td>';
					} else {
						echo '<td><span style="color:red;">Belirtilmemiş</span></td>';
					}
					?>
				</tr>
				<tr>
					<td>Kargo : </td>
					<?php
					if(array_key_exists('kargo_id', $siparis_data['teslimat_bilgileri']))
					{
						echo '<td>' . kargo_adi_yazdir($siparis_data['teslimat_bilgileri']['kargo_id']) . '</td>';
					} else {
						echo '<td><span style="color:red;">Belirtilmemiş</span></td>';
					}
					?>
				</tr>
				<?php } else { ?>
				<tr>
					<td colspan="2">Teslimat Bilgileri Bulunamadı.</td>
				</tr>				
				<?php } ?>
				<?php } else { ?>
				<tr>
					<td colspan="2">Teslimat Bilgileri Bulunamadı.</td>
				</tr>				
				<?php } ?>
				<?php } else { ?>
				<tr>
					<td colspan="2">Teslimat Bilgileri Bulunamadı.</td>
				</tr>				
				<?php } ?>
			</table>
		</div>

		</form>
	</div>
	</div>
</div>
<script type="text/javascript"><!--
$('.vtabs a').tabs(); 
//--></script>
<?php 
$this->load->view('yonetim/footer_view');  
?>