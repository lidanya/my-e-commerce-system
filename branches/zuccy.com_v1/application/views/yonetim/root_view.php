<div>
	<div class="box" style="width: 1038px;margin:auto;">
	   	<div class="content_ust">
			<div>
				<div id="main_kutular_H">
					<ul>
						<li><a href="<?php echo yonetim_url('customer_management/customer/lists'); ?>">
							<div id="main_kutularlar_rsm"><img src="<?php echo yonetim_resim();?>kullanici.png" style="border:0px"></div>
							<div id="main_kutularlar_yazi">Müşteriler</div>
						</a></li>
						<li><a href="<?php echo yonetim_url('urunler/product/lists'); ?>">
							<div id="main_kutularlar_rsm"><img src="<?php echo yonetim_resim();?>urunler_k.png" style="border:0px"></div>
							<div id="main_kutularlar_yazi">Ürünler</div>
						</a></li>
						<li><a href="<?php echo yonetim_url('satis/siparisler'); ?>">
							<div id="main_kutularlar_rsm"><img src="<?php echo yonetim_resim();?>siparis_k.png" style="border:0px"></div>
							<div id="main_kutularlar_yazi">Siparişler</div>
						</a></li>
						<li><a href="<?php echo yonetim_url('cagri/cevapbekleyen'); ?>">
							<div id="main_kutularlar_rsm"><img src="<?php echo yonetim_resim();?>cagrilar.png" style="border:0px"></div>
							<div id="main_kutularlar_yazi">Çağrılar</div>
						</a></li>
						<li><a href="<?php echo yonetim_url('urunler/review/lists'); ?>">
							<div id="main_kutularlar_rsm"><img src="<?php echo yonetim_resim();?>yorumlar.png" style="border:0px"></div>
							<div id="main_kutularlar_yazi">Yorumlar</div>
						</a></li>
						<li><a href="<?php echo yonetim_url('moduller/slider/listele'); ?>">
							<div id="main_kutularlar_rsm"><img src="<?php echo yonetim_resim();?>slider.png" style="border:0px"></div>
							<div id="main_kutularlar_yazi">Slider</div>
						</a></li>
						<li><a href="<?php echo yonetim_url('content_management/information/lists/information'); ?>">
							<div id="main_kutularlar_rsm"><img src="<?php echo yonetim_resim();?>bilgi_sayfalari.png" style="border:0px"></div>
							<div id="main_kutularlar_yazi">Bilgi Sayfaları</div>
						</a></li>
						<li><a href="<?php echo yonetim_url('urunler/manufacturer/lists'); ?>">
							<div id="main_kutularlar_rsm"><img src="<?php echo yonetim_resim();?>markalar.png" style="border:0px"></div>
							<div id="main_kutularlar_yazi">Markalar</div>
						</a></li>
						<li><a href="<?php echo yonetim_url('sistem/genel_ayarlar'); ?>">
							<div id="main_kutularlar_rsm"><img src="<?php echo yonetim_resim();?>genel_ayarlar.png" style="border:0px"></div>
							<div id="main_kutularlar_yazi">Genel Ayarlar</div>
						</a></li>
					</ul>
				</div>
			</div>
			<div style="clear:both"></div>	
		</div>
	</div>
	<div style="clear:both"></div>
</div>

<div id="main_hizala_bosluk_alt"></div>

<div class="box">
	<div class="content" style="width: 1038px;margin:auto;">
		<div style="clear:both"></div>  
		<div style="display: inline-block; width: 100%; margin-bottom: 15px; clear: both;">
			<div style="float: left; width: 609px;">
	        	<div id="siparis_takip">
	        		<div id="siparis_takip_icerik">
	        			<table width="570" border="0">
							<tr>
								<td width="160"><span class="tablobaslik1">Tarih</span></td>
								<td width="249" class="tablobaslik1">İsim</td>
								<td width="139" class="tablobaslik1">Durumu</td>
							</tr>
						<?php
						$this->db->order_by('siparis_id', 'desc');
						$this->db->where_not_in('siparis_flag', '-1');
						$siparis_sorgu = $this->db->get('siparis', 8);
						if($siparis_sorgu->num_rows() > 0)
						{
							foreach($siparis_sorgu->result() as $siparisler)
							{
								$uye_bilgi_kontrol = uye_bilgi($siparisler->user_id);
								if($uye_bilgi_kontrol)
								{
									if($uye_bilgi_kontrol->ide_adi && $uye_bilgi_kontrol->ide_soy)
									{
										$uye_bilgi = anchor(yonetim_url('satis/siparisler/duzenle/' . $siparisler->siparis_id, false), $uye_bilgi_kontrol->ide_adi . ' ' . $uye_bilgi_kontrol->ide_soy);
									} else {
										$uye_bilgi = anchor(yonetim_url('satis/siparisler/duzenle/' . $siparisler->siparis_id, false), $uye_bilgi_kontrol->email);
									}
								} else {
									$uye_bilgi = '<span style="color: red;">Kullanıcı Bulunamadı</span>';
								}
	
								if($siparisler->siparis_flag == 0)
								{
									$siparis_durum = '<td class="tabloicerik1" height="25" align="right">'. anchor(yonetim_url('satis/siparisler/duzenle/' . $siparisler->siparis_id, false), siparis_durum_goster($siparisler->siparis_flag)) .'</td>';
								} else if($siparisler->siparis_flag == 1)
								{
									$siparis_durum = '<td class="tabloicerik1" height="25" align="right">'. anchor(yonetim_url('satis/siparisler/duzenle/' . $siparisler->siparis_id, false), siparis_durum_goster($siparisler->siparis_flag)) .'</td>';
								} else if($siparisler->siparis_flag == 2)
								{
									$siparis_durum = '<td class="yesil" height="25" align="right">'. anchor(yonetim_url('satis/siparisler/duzenle/' . $siparisler->siparis_id, false), siparis_durum_goster($siparisler->siparis_flag), array('class' => 'yesil')) .'</td>';
								} else if($siparisler->siparis_flag == 3)
								{
									$siparis_durum = '<td class="kirmizi" height="25" align="right">'. anchor(yonetim_url('satis/siparisler/duzenle/' . $siparisler->siparis_id, false), siparis_durum_goster($siparisler->siparis_flag), array('class' => 'kirmizi')) .'</td>';
								} else {
									$siparis_durum = '<td class="tabloicerik1" height="25" align="right">'. anchor(yonetim_url('satis/siparisler/duzenle/' . $siparisler->siparis_id, false), siparis_durum_goster($siparisler->siparis_flag)) .'</td>';
								}
								
								echo '<tr>' . "\n";
									echo '<td height="25"><span class="tabloicerik1">'. standard_date('DATE_TR4', $siparisler->kayit_tar, 'tr') .'</span></td>' . "\n";
									echo '<td class="tabloicerik1" height="25">'. $uye_bilgi .'</td>' . "\n";
									echo $siparis_durum . "\n";
								echo '</tr>' . "\n";
							}
							echo '</table>' . "\n";
						} else {
							echo '<tr>' . "\n";
							echo '<td colspan="2" style="text-align: center;">Sipariş yok.</td>' . "\n";
							echo '</tr>' . "\n";
						}
						?>
						</table>
	        		</div>
	        	</div>
				<div id="main_hizala_bosluk"></div>
				
			</div>
			
			
			<div style="float: right; width: 397px;" >				
				
				<div id="ziyaretci_istatistikleri">
					<div id="ziyaretci_istatistikleri_icerik">

					<table width="360" border="0">
						<tr>
							<td width="190"><span class="tablobaslik1">Stoğu Azalan Ürünler</span></td>
							<td width="270" align="center" height="20"><span class="tablobaslik1">Miktar</span></td>
						</tr>
						<?php
							$language_id = get_language('language_id', config('site_ayar_yonetim_dil'));
							$this->db->distinct();
							$this->db->select(
								get_fields_from_table('product', 'p.', array(), ', ') .
								get_fields_from_table('product_description', 'pd.', array('name'), '')
							);
							$this->db->from('product p');
							$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
							$this->db->where('p.quantity <=', config('site_ayar_stok_dusus_uyari_miktari'));
							$this->db->where('pd.language_id', (int) $language_id);
							$this->db->limit(9);
							$stok_uyari_sorgu = $this->db->get();
							if($stok_uyari_sorgu->num_rows()) {
								foreach($stok_uyari_sorgu->result() as $stok_uyari) {
									echo '<tr>';
									echo '<td width="230" height="20"><span class="turkuaz">'. anchor(yonetim_url('urunler/product/edit/' . $stok_uyari->product_id, false), character_limiter($stok_uyari->name, 10), 'title="'. $stok_uyari->name .'"') .'</span></td>';
									echo '<td align="center" height="20"><span class="boldgri">'. $stok_uyari->quantity .'</span></td>';
									echo '</tr>';
								}
							} else {
								echo '<tr><td colspan="2" width="230" height="20" style="text-align: center;">Bulunamadı</td></tr>';
							}
						?>
					</table>

					</div>
					
				</div>
		</div>
		<div style="clear:both"></div>     
      	
    	
			<div id="main_hizala_bosluk"></div>
			
			<div style="float: left; width: 609px;">
	        	
	        	<div id="ziyaretcileriniz" style="overflow:auto;">
	        		<div id="ziyaretcileriniz_icerik">
			<table width="277" border="0">
				<tr>
					<td width="162" class="tablobaslik1">Ziyaretçi</td>
					<td width="159" class="tablobaslik1">Ziyaret Ettiği Sayfa</td>
				</tr>
				<?php
				$sure = time()-300;
				$this->db->select('istatistik.istatistik_id, istatistik.istatistik_ip, istatistik.istatistik_uye_id, istatistik.istatistik_son_sayfa');
				$this->db->order_by('istatistik_id', 'desc');
				$this->db->group_by('istatistik_ip');
				$this->db->where('UNIX_TIMESTAMP(istatistik_tarih) > UNIX_TIMESTAMP(\''. standard_date('DATE_MYSQL', $sure, 'tr') .'\') AND istatistik_tip = \'2\'');
				$this->db->from('istatistik INNER JOIN (SELECT MAX(istatistik_id) AS id FROM e_istatistik GROUP BY istatistik_ip) ids ON e_istatistik.istatistik_id = ids.id');
				$this->db->limit(9);
				$sorgu = $this->db->get();
				if($sorgu->num_rows() > 0)
				{
					foreach($sorgu->result() as $ziyaretci)
					{
						if(!$ziyaretci->istatistik_uye_id)
						{
							$uye_bilgi = $ziyaretci->istatistik_ip;
						} else {
							$uye_bilgi_kontrol = uye_bilgi($ziyaretci->istatistik_uye_id);
							if($uye_bilgi_kontrol)
							{
								if($uye_bilgi_kontrol->ide_adi && $uye_bilgi_kontrol->ide_soy)
								{
									$uye_bilgi = character_limiter($uye_bilgi_kontrol->ide_adi . ' ' . $uye_bilgi_kontrol->ide_soy, 50);
								} else {
									$uye_bilgi = character_limiter($uye_bilgi_kontrol->email, 50);
								}
							} else {
								$uye_bilgi = '<span style="color: red;">Kullanıcı Bulunamadı</span>';
							}
						}
						
						if($ziyaretci->istatistik_son_sayfa)
						{
							$son_sayfasi = $ziyaretci->istatistik_son_sayfa;
						} else {
							$son_sayfasi = 'site';
						}

						echo '<tr>' . "\n";
							echo '<td class="tabloicerik1">'. $uye_bilgi .'</td>' . "\n";
							echo '<td><span class="lacivert">'. anchor($son_sayfasi, 'Dolaştığı Sayfaya Git', 'target="_blank" title="'. $son_sayfasi .'"') .'</span></td>' . "\n";
						echo '</tr>' . "\n";
					}
				}
				?>
			</table>	
	        		</div>
	        	</div>
	        	<div id="son_yapilan_yorumlar" style="overflow:auto;">
	        		<div id="son_yapilan_yorumlar_icerik">
	        			<table width="250" border="0">
							<tr>
								<td class="tablobaslik1">Yorum</td>
								<td class="tablobaslik1">Durum</td>
							</tr>
	        			<?php
							$this->db->select(
								get_fields_from_table('review', 'r.', array(), ', ')
							);
							$this->db->from('review r');
							$yorumlar = $this->db->get();
							if($yorumlar->num_rows() > 0)
							{
								foreach($yorumlar->result() as $musteri_yorum)
								{
									$yorum_durum = ($musteri_yorum->status) ? 'Onaylandı':'Onay Bekliyor';
									echo '<tr>' . "\n";
										echo '<td class="tablobaslik1">'. anchor(yonetim_url('urunler/review/edit/' . $musteri_yorum->review_id), character_limiter($musteri_yorum->author, 15)) .'</td>' . "\n";
										echo '<td class="tablobaslik1">'. $yorum_durum .'</td>' . "\n";
									echo '</tr>' . "\n";
								}
							} else {
								echo '<tr>' . "\n";
								echo '<td colspan="2" style="text-align: center;">Yazılan yorum yok.</td>' . "\n";
								echo '</tr>' . "\n";
							}
	        			?>
	        			</table>
	        		</div>
	        	</div>
				<div id="main_hizala_bosluk"></div>
				
			</div>
			
			
			<div style="float: right; width: 350px;text-align:center;" >				
				
			</div>
                        
			<div style="clear:both"></div>     
			<div id="main_hizala_bosluk"></div>
		</div> 

		<form action="<?php echo yonetim_url('statistic/chart_change'); ?>" method="post" accept-charset="utf-8" id="chart_submit">
			<input type="hidden" name="type" value="daily" id="chart_type">
			<input type="hidden" name="redirect" value="<?php echo yonetim_url(); ?>#aralik_gelismis_tablo" id="chart_redirect">
			<div id="aralik_gelismis_tablo">
				<!-- end box / title --> 
				<div class="gelismis_istatistik"> 
					<div class="legend"> 
						<h6>
							<div style="font-size: 12px; padding: 2px 5px 0px 0px;">Seçiniz : 
								<?php
									$type = $this->session->userdata('chart_type');
									if ( ! in_array($type, array('daily', 'weekly', 'monthly', 'yearly'))) {
										$type = 'daily';
									}
									$_option_array = array(
										'daily' => 'Gün',
										'weekly' => 'Hafta',
										'monthly' => 'Ay',
										'yearly' => 'Yıl'
									);
									echo form_dropdown('aralik_gelismis_secim', $_option_array, $type, 'id="aralik_gelismis_secim" onchange="get_dynamic_chart(this.value)" style="margin: 2px 3px 0 0;"');
								?>
								&nbsp; Şuan sitede <?php echo $this->statistic_model->total_online();; ?> ziyaretçi var.
							</div>
						</h6>
					</div> 
					<div id="new_chart" style="width: 1000px; height: 200px; margin: auto;text-align:center;line-height:200px;"><img src="<?php echo yonetim_resim(); ?>loading2.gif" /> &nbsp; Lütfen bekleyiniz istatistik hesaplanıyor...</div> 
				</div>
			</div>

		</form>

		<?php
			$type = $this->session->userdata('chart_type');
			if ( ! in_array($type, array('daily', 'weekly', 'monthly', 'yearly'))) {
				$type = 'daily';
			}
			if ($type == 'daily') {
				$js_title = 'Bu Güne Ait Ziyaret İstatistikleri';
			} elseif ($type == 'weekly') {
				$js_title = 'Bu Haftaya Ait Ziyaret İstatistikleri';
			} elseif ($type == 'monthly') {
				$js_title = 'Bu Aya Ait Ziyaret İstatistikleri';
			} elseif ($type == 'yearly') {
				$js_title = 'Bu Yıla Ait Ziyaret İstatistikleri';
			}
			$statistics = $this->statistic_model->$type();
			$stats = json::encode(array_values($statistics));
		?>

		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript" charset="utf-8">
			function get_dynamic_chart (type)
			{
				$('#chart_type').attr('value', type);
				$('#new_chart').html('<img src="<?php echo yonetim_resim(); ?>loading2.gif" /> &nbsp; Lütfen bekleyiniz istatistik hesaplanıyor...');
				setTimeout("$('#chart_submit').submit();", 3000);
			}

			google.load("visualization", "1", {packages:["corechart"]});
			google.setOnLoadCallback(drawChart);
			/* Google Chart Start */
			function drawChart() {
				var data = new google.visualization.DataTable();
				data.addColumn('string', 'Year');
				data.addColumn('number', 'Tekil');
				data.addColumn('number', 'Çoğul');
				data.addRows(<?php echo $stats; ?>);

				// ColumnChart, LineChart, AreaChart, ComboChart, 
				var chart = new google.visualization.LineChart(document.getElementById('new_chart'));
				chart.draw(data, {width: 1000, height: 200, backgroundColor: "aliceblue", pointSize: 5, title: "<?php echo $js_title; ?>"});
			}
			/* Google Chart End */
		</script>

 	</div>
</div>
<div style="clear:both"></div>
<div id="main_hizala_bosluk"></div>
</div>