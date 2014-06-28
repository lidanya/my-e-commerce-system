    <!--orta -->
    <div id="orta" class="sola">
		<!--Goster-->
		<h1 id="sayfa_baslik"><?php echo lang('messages_member_order_detail_title'); ?></h1>
		<div id="siparis">
			<?php
				if($siparis_detay->num_rows()) {
			?>
			<!-- header-->
			<div class="sg_baslik">
				<div class="sola">
					<b class="siterenk"><?php echo lang('messages_member_order_detail_order_no'); ?> : <b class="siterenk"><?php echo $siparis_bilgi->siparis_id; ?></b></b> - <i><?php echo standard_date('DATE_TR', $siparis_bilgi->kayit_tar, get_language('code')); ?></i>
				</div>
				<div class="saga">
					<b class="siterenk"><?php echo lang('messages_member_order_detail_order_status'); ?> : </b> <i><?php echo siparis_durum_goster($siparis_bilgi->siparis_flag); ?></i>
				</div>
			</div>
			<div class="s_oge f_oge_baslik">
				<span class="sg_tablo01 sola"><?php echo lang('messages_member_order_detail_product_name'); ?></span>
				<span class="sg_tablo02 sola"><?php echo lang('messages_member_order_detail_quantity'); ?></span>
				<span class="sg_tablo03 sola"><?php echo lang('messages_member_order_detail_unit_price'); ?></span>
				<span class="sg_tablo04 sola"><?php echo lang('messages_member_order_detail_total_price'); ?></span>
				<div class="clear"></div>
			</div>
			<!-- header son -->
			
			<!-- body-->
			<?php 
        	$this->db->select_sum('stok_bfiyat');
        	$toplam_bfiyat_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_bilgi->siparis_id));
        	$toplam_bfiyat_bilgi = $toplam_bfiyat_sorgu->row();

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
			$kupon_indirim = '0';
			$kupon_indirim = @$siparis_data['teslimat_bilgileri']['kupon_indirim']['fiyat'];
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

			//echo debug($kapida_odeme_ucret);

			/* Kdv Hesaplama Başlangıç */
			$toplam_kdv_fiyati = 0;
			/* Kdv Hesaplama Başlangıç */

        	$i = 0;
			foreach($siparis_detay->result() as $siparis):
				$z = $i%2;
				if($z == 0)
				{
					$div_class = ' f_gri';
				} else {
					$div_class = NULL;
				}

				/* Kdv Hesaplama Başlangıç */
				if(config('site_ayar_kdv_goster') == '1')
				{
					$kdv_fiyati = kdv_hesapla($siparis->stok_tfiyat, $siparis->stok_kdv_orani, true);
					$toplam_kdv_fiyati += $kdv_fiyati;
				}
				/* Kdv Hesaplama Başlangıç */
			?>
			<div class="s_oge<?php echo $div_class;?>">
				<span class="sg_tablo01 sola" style="height:auto;padding-bottom:5px;">
					<?php echo character_limiter($siparis->name, 50); ?>
					<?php
						if(is_serialized($siparis->siparis_det_data))
						{
							$siparis_detay_data = @unserialize($siparis->siparis_det_data);
							//echo debug($siparis_detay_data);
					?>
							<br />
							<?php
								foreach ($siparis_detay_data['secenek'] as $option_row)
								{
							?>
								&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $option_row['name']; ?> :
								<?php
									if(isset($option_row['product_option_id']))
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
				</span>
				<span class="sg_tablo02 sola"><?php echo $siparis->stok_miktar; ?></span>
				<span class="sg_tablo03 sola"><b ><?php echo format_number($siparis->stok_bfiyat); ?> TL</b></span>
				<span class="sg_tablo04 sola"><b class="siterenk"><?php echo format_number($siparis->stok_tfiyat); ?> TL <?php //echo $kdv_fiyati; ?></b></span>
			</div>
			<?php endforeach;?>
			<!-- body son-->
			<?php } else { ?>
			<div class="s_oge f_oge_baslik">
				<span class="sg_tablo01" style="width:100%;"><?php echo lang('messages_member_order_no_detail'); ?></span>
			</div>				
			<?php } ?>
			<!-- Ürülerin Toplamı -->		
			<div class="sg_alt">
				<div class="sola" style="margin-top:25px;">
					<a rel="nofollow" href="javascript:;" onclick="location = '<?php echo ssl_url('uye/siparisler'); ?>';" class="butonum">

						<span class="butor"><img src="<?php echo site_resim(); ?>btn_img_geri.png" alt="" /> <?php echo lang('messages_button_back_list'); ?></span>

					</a>
					
				</div>
				<?php 
					if($siparis_detay->num_rows()) {
				?>
				<?php
					if(config('site_ayar_kdv_goster')) {
				?>
				<div class="saga" style="margin-top:10px;">
					<span class="sg_fiyat saga"><b class="siterenk"><?php echo ($toplam_tfiyat_bilgi->stok_tfiyat) ? format_number($toplam_tfiyat_bilgi->stok_tfiyat) : NULL ;?> TL</b></span>
					<span class="sg_text saga"><?php echo lang('messages_member_order_detail_subtotal'); ?> :</span>
					<div class="clear"></div>

					<?php if($indirim_ucret != '0' || $kupon_indirim != '0') { ?>
					<span class="sg_fiyat saga"><b class="siterenk">-<?php echo format_number($indirim_ucret  + $kupon_indirim);?> TL</b></span>
					<span class="sg_text saga"><?php echo lang('messages_member_order_detail_discount_total'); ?> :</span>
					<div class="clear"></div>
					<?php } ?>

					<?php if($kargo_ucret != '0') { ?>
					<span class="sg_fiyat saga"><b class="siterenk"><?php echo format_number($kargo_ucret);?> TL</b></span>
					<span class="sg_text saga"><?php echo lang('messages_member_order_detail_shipping_total'); ?> :</span>
					<div class="clear"></div>
					<?php } ?>

					<?php if($kapida_odeme_ucret != '0') { ?>
					<span class="sg_fiyat saga"><b class="siterenk"><?php echo format_number($kapida_odeme_ucret);?> TL</b></span>
					<span class="sg_text saga"><?php echo lang('messages_member_order_detail_payment_at_the_door_total'); ?> :</span>
					<div class="clear"></div>
					<?php } ?>

					<span class="sg_fiyat saga"><b class="siterenk"><?php echo format_number($toplam_kdv_fiyati); ?> TL</b></span>
					<span class="sg_text saga"><?php echo lang('messages_member_order_detail_vat_total'); ?> :</span>
					<div class="clear"></div>

					<span class="sg_fiyat saga"><b class="siterenk" style="font-size:16px;"><?php echo format_number(($stok_toplam_fiyat + $toplam_kdv_fiyati + $kargo_ucret + $kapida_odeme_ucret - $indirim_ucret - $kupon_indirim)); ?> TL</b></span>
					<span class="sg_text saga" style="padding-top:3px;"><?php echo lang('messages_member_order_detail_total'); ?> :</span>
					<div class="clear"></div>
				</div>
				<?php } else { ?>
				<div class="saga" style="margin-top:10px;">
					<span class="sg_fiyat saga"><b class="siterenk"><?php echo ($toplam_tfiyat_bilgi->stok_tfiyat) ? format_number($toplam_tfiyat_bilgi->stok_tfiyat) : NULL ;?> TL</b></span>
					<span class="sg_text saga"><?php echo lang('messages_member_order_detail_subtotal'); ?> :</span>
					<div class="clear"></div>

					<?php if($indirim_ucret != '0' || $kupon_indirim != 0) { ?>
					<span class="sg_fiyat saga"><b class="siterenk">-<?php echo format_number($indirim_ucret + $kupon_indirim);?> TL</b></span>
					<span class="sg_text saga"><?php echo lang('messages_member_order_detail_discount_total'); ?> :</span>
					<div class="clear"></div>
					<?php } ?>

					<?php if($kargo_ucret != '0') { ?>
					<span class="sg_fiyat saga"><b class="siterenk"><?php echo format_number($kargo_ucret);?> TL</b></span>
					<span class="sg_text saga"><?php echo lang('messages_member_order_detail_shipping_total'); ?> :</span>
					<div class="clear"></div>
					<?php } ?>

					<?php if($kapida_odeme_ucret != '0') { ?>
					<span class="sg_fiyat saga"><b class="siterenk"><?php echo format_number($kapida_odeme_ucret);?> TL</b></span>
					<span class="sg_text saga"><?php echo lang('messages_member_order_detail_payment_at_the_door_total'); ?> :</span>
					<div class="clear"></div>
					<?php } ?>

					<span class="sg_fiyat saga"><b class="siterenk" style="font-size:16px;"><?php echo ($stok_toplam_fiyat + $kargo_ucret + $kapida_odeme_ucret  - $indirim_ucret - $kupon_indirim); ?> TL</b></span>
					<span class="sg_text saga" style="padding-top:3px;"><?php echo lang('messages_member_order_detail_total'); ?> :</span>
					<div class="clear"></div>
				</div>
				<?php } ?>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<!-- Ürülerin Toplamı Son -->		

		</div>
		<!--Goster SON-->
    </div>
    <!--orta son-->