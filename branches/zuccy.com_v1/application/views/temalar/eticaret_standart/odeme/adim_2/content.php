<?php $this->load->view(tema() . 'odeme/header'); ?>

<div id="kargo_secimi">
	<div class="odeme_baslik"><?php echo lang('messages_checkout_title_shipping_choice'); ?></div>
	<div class="adim_info adim_sari"><?php echo lang('messages_checkout_2_information'); ?></div>
	<div id="o_sol" class="sola">
		<?php echo form_open_ssl('odeme/adim_3/'. $siparis_id . '/' . $fatura_id, array('name' => 'kargo_form', 'id' => 'kargo_form')); ?>
			<?php
				$this->db->select_sum('stok_tfiyat');
				$toplam_fiyat_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id), 1);
				$toplam_fiyat_bilgi = $toplam_fiyat_sorgu->row();

				$this->db->order_by('kargo_sira', 'asc');
				$kargo_sorgu = $this->db->get_where('kargo', array('kargo_flag' => '1'));
				$fiyatlar = array();
				$i = 0;
				foreach($stok_sorgu as $stoklar) {
					if($stoklar->length > 0 AND $stoklar->width > 0 AND $stoklar->height > 0) {
						$desi_miktar = (float) (($stoklar->length * $stoklar->width * $stoklar->height) / 3000);
						$kilo = (int) $stoklar->weight;
						if ($kilo > $desi_miktar) {
							$desi_miktar = $kilo;
						}
						$fiyatlar[$i]['desi'] = TRUE;
						$fiyatlar[$i]['desi_oran'] = $desi_miktar;
						$fiyatlar[$i]['adet'] = $stoklar->pay_quantity;
						$fiyatlar[$i]['carpan'] = ($stoklar->cargo_multiply_required) ? FALSE : FALSE;
					} else {
						$fiyatlar[$i]['desi'] = FALSE;
						$fiyatlar[$i]['desi_oran'] = FALSE;
						$fiyatlar[$i]['adet'] = $stoklar->pay_quantity;
						$fiyatlar[$i]['carpan'] = ($stoklar->cargo_multiply_required) ? FALSE : FALSE;
					}
					$i++;
				}
				$kii = 0;

				foreach($kargo_sorgu->result() as $kargolar) {
					$oran=(float) 0;
					$kii++;
					$resim = show_image($kargolar->kargo_logo, 170, 55);
					// Desi Hesaplaması
					$toplam_fiyat = (float) 0.00;
					foreach($fiyatlar as $fiyat) {
						$oran += ($fiyat['desi_oran']) * ($fiyat["adet"]);
						if($fiyat['desi'] AND $kargolar->kargo_ucret_tip == '2') {
							
							if($oran <= 5) {
								$kargo_ucret_sorgu = $this->db->get_where('kargo_ucret', array('kargo_id' => $kargolar->kargo_id, 'kargo_ucret_flag' => '1', 'kargo_ucret_tip' => 'ucret_tip2'), 1);
								if($kargo_ucret_sorgu->num_rows() > 0) {
									$kargo_ucret_bilgi = $kargo_ucret_sorgu->row();
									$_fiyat = (float) ($kargo_ucret_bilgi->kargo_ucret_ucret) ? $kargo_ucret_bilgi->kargo_ucret_ucret : 0;
								}
							} elseif($oran > 5 AND $oran <= 10) {
								$kargo_ucret_sorgu_2 = $this->db->get_where('kargo_ucret', array('kargo_id' => $kargolar->kargo_id, 'kargo_ucret_flag' => '1', 'kargo_ucret_tip' => 'ucret_tip3'), 1);
								if($kargo_ucret_sorgu_2->num_rows() > 0) {
									$kargo_ucret_bilgi_2 = $kargo_ucret_sorgu_2->row();
									$_fiyat = (float) ($kargo_ucret_bilgi_2->kargo_ucret_ucret) ? $kargo_ucret_bilgi_2->kargo_ucret_ucret : 0;								}
							} elseif($oran > 10 AND $oran <= 20) {
								$kargo_ucret_sorgu_3 = $this->db->get_where('kargo_ucret', array('kargo_id' => $kargolar->kargo_id, 'kargo_ucret_flag' => '1', 'kargo_ucret_tip' => 'ucret_tip4'), 1);
								if($kargo_ucret_sorgu_3->num_rows() > 0) {
									$kargo_ucret_bilgi_3 = $kargo_ucret_sorgu_3->row();
									$_fiyat = (float) ($kargo_ucret_bilgi_3->kargo_ucret_ucret) ? $kargo_ucret_bilgi_3->kargo_ucret_ucret : 0;
								}
							} elseif($oran >20 AND $oran <= 30) {
								$kargo_ucret_sorgu_4 = $this->db->get_where('kargo_ucret', array('kargo_id' => $kargolar->kargo_id, 'kargo_ucret_flag' => '1', 'kargo_ucret_tip' => 'ucret_tip5'), 1);
								if($kargo_ucret_sorgu_4->num_rows() > 0) {
									$kargo_ucret_bilgi_4 = $kargo_ucret_sorgu_4->row();
									$_fiyat = (float) ($kargo_ucret_bilgi_4->kargo_ucret_ucret) ? $kargo_ucret_bilgi_4->kargo_ucret_ucret : 0;
								}
							} elseif($oran > 30 AND $oran <= 40) {
								$kargo_ucret_sorgu_5 = $this->db->get_where('kargo_ucret', array('kargo_id' => $kargolar->kargo_id, 'kargo_ucret_flag' => '1', 'kargo_ucret_tip' => 'ucret_tip6'), 1);
								if($kargo_ucret_sorgu_5->num_rows() > 0) {
									$kargo_ucret_bilgi_5 = $kargo_ucret_sorgu_5->row();
									$_fiyat = (float) ($kargo_ucret_bilgi_5->kargo_ucret_ucret) ? $kargo_ucret_bilgi_5->kargo_ucret_ucret : 0;
								}
							} elseif($oran >40 AND $oran <= 50) {
								$kargo_ucret_sorgu_6 = $this->db->get_where('kargo_ucret', array('kargo_id' => $kargolar->kargo_id, 'kargo_ucret_flag' => '1', 'kargo_ucret_tip' => 'ucret_tip7'), 1);
								if($kargo_ucret_sorgu_6->num_rows() > 0) {
									$kargo_ucret_bilgi_6 = $kargo_ucret_sorgu_6->row();
									$_fiyat = (float) ($kargo_ucret_bilgi_6->kargo_ucret_ucret) ? $kargo_ucret_bilgi_6->kargo_ucret_ucret : 0;
								}
							} elseif($oran > 50) {
								$kargo_ucret_sorgu_7 = $this->db->get_where('kargo_ucret', array('kargo_id' => $kargolar->kargo_id, 'kargo_ucret_flag' => '1', 'kargo_ucret_tip' => 'ucret_tip8'), 1);
								if($kargo_ucret_sorgu_7->num_rows() > 0) {
									$kargo_ucret_bilgi_7 = $kargo_ucret_sorgu_7->row();
									$_fiyat = (float) ($kargo_ucret_bilgi_7->kargo_ucret_ucret) ? $kargo_ucret_bilgi_7->kargo_ucret_ucret : 0;
								}
//------
							} else {
								$kargo_ucret_sorgu_8 = $this->db->get_where('kargo_ucret', array('kargo_id' => $kargolar->kargo_id, 'kargo_ucret_flag' => '1', 'kargo_ucret_tip' => 'ucret_tip1'), 1);
								if($kargo_ucret_sorgu_8->num_rows() > 0)
								{
									$kargo_ucret_bilgi_8 = $kargo_ucret_sorgu_8->row();
								}
							}
						} else {
							$kargo_ucret_sorgu_9 = $this->db->get_where('kargo_ucret', array('kargo_id' => $kargolar->kargo_id, 'kargo_ucret_flag' => '1', 'kargo_ucret_tip' => 'ucret_tip1'), 1);
							if($kargo_ucret_sorgu_9->num_rows() > 0) {
								$kargo_ucret_bilgi_9 = $kargo_ucret_sorgu_9->row();
								$_fiyat = (float) ($kargo_ucret_bilgi_9->kargo_ucret_ucret) ? $kargo_ucret_bilgi_9->kargo_ucret_ucret : 0;
							}
						}
					}

					//-- HESAPLANAN DESİYE GÖRE FİYATLANDIRMA
					/*
								if($oran <= 5) {
									$kargo_ucret_bilgi = $kargo_ucret_sorgu->row();
								}
								if($oran > 5 AND $oran <= 10) {
									$kargo_ucret_bilgi = $kargo_ucret_sorgu_2->row();
								}
								if($oran > 10 AND $oran <= 20) {
									$kargo_ucret_bilgi = $kargo_ucret_sorgu_3->row();
								}
								if($oran > 20 AND $oran <= 30) {
									$kargo_ucret_bilgi = $kargo_ucret_sorgu_4->row();
								}
								if($oran > 30 AND $oran <= 40) {
									$kargo_ucret_bilgi = $kargo_ucret_sorgu_5->row();
								}
								if($oran > 40 AND $oran <= 50) {
									$kargo_ucret_bilgi = $kargo_ucret_sorgu_6->row();
								}
								if($oran > 50 ) {
									$kargo_ucret_bilgi = $kargo_ucret_sorgu_7->row();
								}
								if($fiyat['desi'] AND $kargolar->kargo_ucret_tip != '2'){
									$kargo_ucret_bilgi = $kargo_ucret_sorgu_9->row();
								}
								*/
									if ($fiyat['carpan']) {
										
										$_fiyat = ($_fiyat * $fiyat['adet']);
									}
									$toplam_fiyat= $_fiyat;

								/*
								
								if(isset($kargo_ucret_sorgu_8)) {
									$kargo_ucret_bilgi = $kargo_ucret_sorgu_8->row();
									if ($fiyat['carpan']) {
										$_fiyat = ($_fiyat * $fiyat['adet']);
									}
									$toplam_fiyat= $_fiyat;
								}
								if(isset($kargo_ucret_sorgu_9)) {
									$kargo_ucret_bilgi = $kargo_ucret_sorgu_9->row();
									if ($fiyat['carpan']) {
										$_fiyat = ($_fiyat * $fiyat['adet']);
									}
									$toplam_fiyat= $_fiyat;
								}
								*/
								
					//----
					
					$toplam_fiyatcik = ($toplam_fiyat) ? $toplam_fiyat : 0;
					$toplam_fiyatcik_orj = $toplam_fiyatcik;
					if((config('config_kargo_indirimi_durum') AND config('config_kargo_indirim_fiyat')) AND ($toplam_fiyat_bilgi->stok_tfiyat >= config('config_kargo_indirim_fiyat'))) {
						$toplam_fiyatcik = (float) 0;
						$kargo_fiyat_yaz = '<strike style="color: red;">' . format_number($toplam_fiyatcik_orj) .' TL</strike><br /><font style="color:green;font-weight:bold;">Ücretsiz</font>';
					} else {
						$kargo_fiyat_yaz = format_number($toplam_fiyatcik) .' TL';
						$toplam_fiyatcik = $toplam_fiyatcik;
					}
					$secili = ($kii == '1') ? ' checked="checked"' : NULL;
	
					echo '
						<div class="k_oge">
							<i onclick="tip_sec(\''. $kargolar->kargo_id .'\');">
							<input type="radio" onclick="tip_sec(\''. $kargolar->kargo_id .'\');" name="kargo_secimi" value="'. $kargolar->kargo_id .'" '. $secili .' id="kargo_'. $kargolar->kargo_id .'" class="kargolar" />
							<input type="hidden" name="kargo_fiyat_'. $kargolar->kargo_id .'" value="'. $toplam_fiyatcik .'" />
							</i>
							<p><img src="'. $resim .'" alt="'. $kargolar->kargo_adi .'" onclick="tip_sec(\''. $kargolar->kargo_id .'\');" width="170" /></p><!--imajlar 170x55 -->
							<span onclick="tip_sec(\''. $kargolar->kargo_id .'\');">'. $kargolar->kargo_adi .'</span>
							<u class="siterenk" onclick="tip_sec(\''. $kargolar->kargo_id .'\');">'. $kargo_fiyat_yaz .'</u>
							<div class="clear"></div>
						</div>
					';
				}
			?>
			<div id="k_buton">
				<a href="javascript:;" class="butonum" onclick="$('#kargo_form').submit();">
					<span class="butsol"></span>
					<span class="butor"><?php echo lang('messages_checkout_2_form_button_text'); ?></span>
					<span class="butsag"></span>
				</a>
			</div>
		<?php echo form_close(); ?>
	</div>
	<div id="o_sag" class="saga">
		<div class="adim_info adim_gri"><?php echo lang('messages_checkout_2_information_2'); ?></div>
	</div>
	<div class="clear"></div>
</div>
<script type="text/javascript" charset="utf-8">
	function tip_sec(tip) {
		$('.kargolar').attr('checked','');
		$('#kargo_' + tip).attr('checked','checked');
	}
</script>
<?php $this->load->view(tema() . 'odeme/footer'); ?>