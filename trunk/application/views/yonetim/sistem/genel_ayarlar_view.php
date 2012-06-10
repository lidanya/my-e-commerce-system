<?php 
	$this->load->view('yonetim/header_view'); 
	$val = $this->validation; 
	if ($val->error_string) 
	{
	?>
	<div class="warning">
		Kaydedilirken sorun oluştu! <br />
		<?php echo $val->error_string; ?>
	</div>
	<?php 
	}
?>
<div class="box">
	<div class="left"></div>
  	<div class="right"></div>
  	<div class="heading">
		<h1 style="background: url('<?php echo yonetim_resim();?>setting.png') 2px 9px no-repeat;">Ayarlar</h1>
    	<div class="buttons">
			<a onclick="$('#form').submit();" class="buton"><span>Kaydet</span></a>
      	</div>
  	</div>
  	<div class="content">
    	<div id="tabs" class="htabs">
    		<a tab="#tab_general">Genel</a>
    		<a tab="#tab_store">Mağaza</a>
    		<a tab="#tab_productdetail">Ürün Detay</a>
	    	<a tab="#tab_option">Seçenekler</a>
            <a tab="#tab_basket">Sepet Ayarları</a>
    		<a tab="#tab_server">Sunucu</a>
    		<a tab="#tab_kurlar">Döviz Kurları</a>
    		<a tab="#tab_coupon">Kupon Uygulaması</a>
    		<a tab="#tab_facebook">Facebook Ayarları</a>
		</div>

    	<form action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data" id="form">
    		
			<div id="tab_general">
				<table class="form">
					<tr>
						<td><span class="required">*</span> Mağaza Adı:</td>
						<td><input type="text" name="config_name" value="<?php echo config('firma_adi'); ?>" size="40" />
						  <?php if ($val->config_name_error) { ?>
						  <span class="error"><?php echo $val->config_name_error; ?></span>
						  <?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> Mağaza Yetkilisi:</td>
						<td><input type="text" name="config_owner" value="<?php echo config('firma_sahibi'); ?>" size="40" />
						  <?php if ($val->config_owner_error) { ?>
						  <span class="error"><?php echo $val->config_owner_error; ?></span>
						  <?php } ?>
						</td>
					</tr>
                                    
                                        
					<tr>
						<td><span class="required">*</span> Telefon 1:</td>
						<td><input type="text" name="config_telephone" id="config_telephone" value="<?php echo config('site_ayar_sirket_tel'); ?>" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span title="Santral Varmı ?">Pbx ?</span> <input type="radio" name="config_telephone_p" value="1" <?php echo (config('site_ayar_sirket_tel_p') == '1') ? 'checked="checked"':NULL; ?> />&nbsp;Evet&nbsp;&nbsp;<input type="radio" name="config_telephone_p" value="0" <?php echo (config('site_ayar_sirket_tel_p') == '0') ? 'checked="checked"':NULL; ?> />&nbsp;Hayır
						 <?php if ($val->config_telephone_error) { ?>
						  <span class="error"><?php echo $val->config_telephone_error; ?></span>
						  <?php } ?>
						</td>
					</tr>
					
					<tr>
						<td>Telefon 2:</td>
						<td><input type="text" name="config_telephone2" id="config_telephone2" value="<?php echo config('site_ayar_sirket_tel2'); ?>" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span title="Santral Varmı ?">Pbx ?</span> <input type="radio" name="config_telephone2_p" value="1" <?php echo (config('site_ayar_sirket_tel2_p') == '1') ? 'checked="checked"':NULL; ?> />&nbsp;Evet&nbsp;&nbsp;<input type="radio" name="config_telephone2_p" value="0" <?php echo (config('site_ayar_sirket_tel2_p') == '0') ? 'checked="checked"':NULL; ?> />&nbsp;Hayır
						 <?php if ($val->config_telephone2_error) { ?>
						  <span class="error"><?php echo $val->config_telephone2_error; ?></span>
						  <?php } ?>
						</td>
					</tr>
					
					<tr>
						<td>Telefon 3:</td>
						<td><input type="text" name="config_telephone3" id="config_telephone3" value="<?php echo config('site_ayar_sirket_tel3'); ?>" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span title="Santral Varmı ?">Pbx ?</span> <input type="radio" name="config_telephone3_p" value="1" <?php echo (config('site_ayar_sirket_tel3_p') == '1') ? 'checked="checked"':NULL; ?> />&nbsp;Evet&nbsp;&nbsp;<input type="radio" name="config_telephone3_p" value="0" <?php echo (config('site_ayar_sirket_tel3_p') == '0') ? 'checked="checked"':NULL; ?> />&nbsp;Hayır
						 <?php if ($val->config_telephone3_error) { ?>
						  <span class="error"><?php echo $val->config_telephone3_error; ?></span>
						  <?php } ?>
						</td>
					</tr>
					
					<tr>
						<td>Telefon 4:</td>
						<td><input type="text" name="config_telephone4" id="config_telephone4" value="<?php echo config('site_ayar_sirket_tel4'); ?>" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span title="Santral Varmı ?">Pbx ?</span> <input type="radio" name="config_telephone4_p" value="1" <?php echo (config('site_ayar_sirket_tel4_p') == '1') ? 'checked="checked"':NULL; ?> />&nbsp;Evet&nbsp;&nbsp;<input type="radio" name="config_telephone4_p" value="0" <?php echo (config('site_ayar_sirket_tel4_p') == '0') ? 'checked="checked"':NULL; ?> />&nbsp;Hayır
						 <?php if ($val->config_telephone4_error) { ?>
						  <span class="error"><?php echo $val->config_telephone4_error; ?></span>
						  <?php } ?>
						</td>
					</tr>

					<tr>
						<td>Telefon 5:</td>
						<td><input type="text" name="config_telephone5" id="config_telephone5" value="<?php echo config('site_ayar_sirket_tel5'); ?>" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span title="Santral Varmı ?">Pbx ?</span> <input type="radio" name="config_telephone5_p" value="1" <?php echo (config('site_ayar_sirket_tel5_p') == '1') ? 'checked="checked"':NULL; ?> />&nbsp;Evet&nbsp;&nbsp;<input type="radio" name="config_telephone5_p" value="0" <?php echo (config('site_ayar_sirket_tel5_p') == '0') ? 'checked="checked"':NULL; ?> />&nbsp;Hayır
						 <?php if ($val->config_telephone5_error) { ?>
						  <span class="error"><?php echo $val->config_telephone5_error; ?></span>
						  <?php } ?>
						</td>
					</tr>
					<tr>
						<td>Fax 1:</td>
						<td><input type="text" name="config_fax" id="config_fax" value="<?php echo config('site_ayar_sirket_fax'); ?>" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span title="Santral Varmı ?">Pbx ?</span> <input type="radio" name="config_fax_p" value="1" <?php echo (config('site_ayar_sirket_fax_p') == '1') ? 'checked="checked"':NULL; ?> />&nbsp;Evet&nbsp;&nbsp;<input type="radio" name="config_fax_p" value="0" <?php echo (config('site_ayar_sirket_fax_p') == '0') ? 'checked="checked"':NULL; ?> />&nbsp;Hayır
						 <?php if ($val->config_fax_error) { ?>
						  <span class="error"><?php echo $val->config_fax_error; ?></span>
						  <?php } ?>
						</td>
					</tr>
					<tr>
						<td>Fax 2:</td>
						<td><input type="text" name="config_fax2" id="config_fax2" value="<?php echo config('site_ayar_sirket_fax2'); ?>" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span title="Santral Varmı ?">Pbx ?</span> <input type="radio" name="config_fax2_p" value="1" <?php echo (config('site_ayar_sirket_fax2_p') == '1') ? 'checked="checked"':NULL; ?> />&nbsp;Evet&nbsp;&nbsp;<input type="radio" name="config_fax2_p" value="0" <?php echo (config('site_ayar_sirket_fax2_p') == '0') ? 'checked="checked"':NULL; ?> />&nbsp;Hayır
						 <?php if ($val->config_fax2_error) { ?>
						  <span class="error"><?php echo $val->config_fax2_error; ?></span>
						  <?php } ?>
						</td>
					</tr>
					<tr>
						<td>Fax 3:</td>
						<td><input type="text" name="config_fax3" id="config_fax3" value="<?php echo config('site_ayar_sirket_fax3'); ?>" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span title="Santral Varmı ?">Pbx ?</span> <input type="radio" name="config_fax3_p" value="1" <?php echo (config('site_ayar_sirket_fax3_p') == '1') ? 'checked="checked"':NULL; ?> />&nbsp;Evet&nbsp;&nbsp;<input type="radio" name="config_fax3_p" value="0" <?php echo (config('site_ayar_sirket_fax3_p') == '0') ? 'checked="checked"':NULL; ?> />&nbsp;Hayır
						 <?php if ($val->config_fax3_error) { ?>
						  <span class="error"><?php echo $val->config_fax3_error; ?></span>
						  <?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> Adres:</td>
						<td><textarea name="config_address" cols="40" rows="5"><?php echo config('site_ayar_sirket_adres'); ?></textarea>
						  <?php if ($val->config_address_error) { ?>
						  <span class="error"><?php echo $val->config_address_error; ?></span>
						  <?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> E-Posta Gönderen:<br /> <span class="help">Sistemden otomatik olarak gönderilen maillerin gönderen bölümünde çıkacak olan yazı.<br />Örn: Mağaza Adı</span></td>
						<td><input type="text" name="config_email_title" value="<?php echo config('site_ayar_email_baslik'); ?>" size="40" />
						 <?php if ($val->config_email_title_error) { ?>
						  <span class="error"><?php echo $val->config_email_title_error; ?></span>
						  <?php } ?>
						</td>
					</tr>					
					<tr>
						<td><span class="required">*</span> E-Posta:<br /> <span class="help">Tüm iletişim bilgilerinde görüncek olan mağazanızın e-posta adresi.<br />Örn: info@adresiniz.com</span></td>
						<td><input type="text" name="config_email" value="<?php echo config('site_ayar_mail'); ?>" size="40" />
						 <?php if ($val->config_email_error) { ?>
						  <span class="error"><?php echo $val->config_email_error; ?></span>
						  <?php } ?>
						</td>
					</tr>					
					<tr>
						<td><span class="required">*</span> E-Posta(destek):<br /> <span class="help">Sistem hakkında yardım ya da teknik destek isteyen müşterilerinizin kullanacağı e-posta adresi.<br />Örn: destek@adresiniz.com</span></td>
						<td><input type="text" name="config_email_destek" value="<?php echo config('site_ayar_email_destek'); ?>" size="40" />
						 <?php if ($val->config_email_destek_error) { ?>
						  <span class="error"><?php echo $val->config_email_destek_error; ?></span>
						  <?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> E-Posta(cevapsız):<br /> <span class="help">Sisteminizden gönderilen otomatik bilgi mesajları gibi cevap beklenmeyen iletilerin gönderen bölümünde çıkacak olan e-posta adresi.<br />Örn: no-reply@adresiniz.com</span></td>
						<td><input type="text" name="config_email_cevapsiz" value="<?php echo config('site_ayar_email_cevapsiz'); ?>" size="40" />
						 <?php if ($val->config_email_cevapsiz_error) { ?>
						  <span class="error"><?php echo $val->config_email_cevapsiz_error; ?></span>
						  <?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> E-Posta(admin):<br /> <span class="help">Sistem üzerindeki çağrılar ve siparişler gibi bölümlerde yapılan yazışmaların bir kopyasını gönderildiği yönetici e-posta adresi.<br />Örn: admin@adresiniz.com</span></td>
						<td><input type="text" name="config_email_admin" value="<?php echo config('site_ayar_email_admin'); ?>" size="40" />
						 <?php if ($val->config_email_admin_error) { ?>
						  <span class="error"><?php echo $val->config_email_admin_error; ?></span>
						  <?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							Mağaza Logosu<br />
							<span class="help">Mağaza logosu eklemek için tıklayın. Yükleyeceğiniz logonun genişliğinin 200px'i geçmemesi önerilir.</span>
						</td>
						<?php
						
						if($this->input->post('image'))
						{
							$preview			= $this->image_model->resize($this->input->post('image'), 100, 100);
							$preview_input 		= $this->input->post('image');
						} else {
							if(config('site_ayar_logo') != '')
							{
								if(file_exists(DIR_IMAGE . config('site_ayar_logo')))
								{
									$preview			= $this->image_model->resize(config('site_ayar_logo'), 100, 100);
									$preview_input 		= config('site_ayar_logo');
								} else {
									$preview			= $this->image_model->resize('resim_ekle.jpg', 100, 100);
									$preview_input 		= 'resim_ekle.jpg';
								}
							} else {
								$preview			= $this->image_model->resize('resim_ekle.jpg', 100, 100);
								$preview_input 		= 'resim_ekle.jpg';
							}
						}
						
						?>
        				<td>
        					<input type="hidden" name="image" value="<?php echo $preview_input; ?>" id="image">
        					<img src="<?php echo $preview; ?>" alt="" id="preview" onmouseover="$(this).attr('src','<?php echo $this->image_model->resize('resim_ekle_hover.jpg', 100, 100); ?>');" onmouseout="$(this).attr('src','<?php echo $preview; ?>');" title="Resim eklemek yada değiştirmek için tıklayınız." onclick="image_upload('image', 'preview');" style="cursor: pointer; border: 1px solid #EEEEEE;">
        					<img src="<?php echo yonetim_resim(); ?>image.png" alt="" title="Resim eklemek yada değiştirmek için tıklayınız." style="cursor: pointer;" align="top" onclick="image_upload('image', 'preview');">
						</td>
					</tr>
					
					<tr>
						<td>
							Mağaza Favicon<br />
							<span class="help">Yükleyeceğiniz faviconun 16px genişlik ve yüksekliğinde olmalıdır. (.png) uzantılı dosya kullanabilirsiniz.</span>
						</td>
						<?php
						
						if($this->input->post('imageFav'))
						{
							$preview			= $this->image_model->resize($this->input->post('imageFav'), 16, 16);
							$preview_input 		= $this->input->post('imageFav');
						} else {
							if(config('site_ayar_favicon') != '')
							{
								if(file_exists(DIR_IMAGE . config('site_ayar_favicon')))
								{
									$preview			= $this->image_model->resize(config('site_ayar_favicon'), 16, 16);
									$preview_input 		= config('site_ayar_favicon');
								} else {
									$preview			= $this->image_model->resize('resim_ekle.jpg', 100, 100);
									$preview_input 		= 'resim_ekle.jpg';
								}
							} else {
								$preview			= $this->image_model->resize('resim_ekle.jpg', 100, 100);
								$preview_input 		= 'resim_ekle.jpg';
							}
						}
						
						?>
        				<td>
        					<input type="hidden" name="imageFav" value="<?php echo $preview_input; ?>" id="imageFav">
        					<img src="<?php echo $preview; ?>" alt="" id="preview-fav" onmouseover="$(this).attr('src','<?php echo $this->image_model->resize('resim_ekle_hover.jpg', 100, 100); ?>');" onmouseout="$(this).attr('src','<?php echo $preview; ?>');" title="Resim eklemek yada değiştirmek için tıklayınız." onclick="image_upload('imageFav', 'preview-fav');" style="cursor: pointer; border: 1px solid #EEEEEE;">
        					<img src="<?php echo yonetim_resim(); ?>image.png" alt="" title="Resim eklemek yada değiştirmek için tıklayınız." style="cursor: pointer;" align="top" onclick="image_upload('imageFav', 'preview-fav');">
						</td>
					</tr>
					
					
					<tr>
						<td>
							Mağaza Teması<br />
							<span class="help">Mağaza Teması Değiştirebilirsiniz.</span>
						</td>
        				<td>
        					<?php
        					$tema_secimi = ($val->config_tema) ? $val->config_tema:config('site_ayar_tema');
							echo form_dropdown('config_tema', $temalar, $tema_secimi);
        					?>
						</td>
					</tr>
					<tr>
						<td>
							Mağaza Tema Renk<br />
							<span class="help">Mağaza Tema Rengini Değiştirebilirsiniz.</span>
						</td>
        				<td>
        					<?php
        					$tema_renk_secimi = ($val->config_tema_renkler) ? $val->config_tema_renkler:config('site_ayar_tema_asset');
							echo form_dropdown('config_tema_renkler', $renkler, $tema_renk_secimi);
        					?>
						</td>
					</tr>
				</table>
			</div>			
			
			<div id="tab_store">
				<table class="form">
					<tr>
					<td><span class="required">*</span> Site Başlığı:<br /> <span class="help">Web Siteniz Açıldığında Görüntülenecek Olan Sayfasının Başlığı (Title)</span></td>
					<td><input type="text" name="config_title" value="<?php echo config('site_ayar_baslik'); ?>" style="width:540px !important;" />
					 <?php if ($val->config_title_error) { ?>
					  <span class="error"><?php echo $val->config_title_error; ?></span>
					  <?php } ?></td>
					</tr>
					<tr>
					<td>Meta Açıklama:<br /> <span class="help">Web Sitenizin Arama Motorları Tarafından Okunmasını Sağlayacak Açıklama (Description)</span></td>
					<td><textarea name="config_meta_description" cols="100" rows="5"><?php echo config('site_ayar_description'); ?></textarea>
					 <?php if ($val->config_meta_description_error) { ?>
					  <span class="error"><?php echo $val->config_meta_description_error; ?></span>
					  <?php } ?></td>
					</tr>
					<tr>
					<td>Meta Kelimeler:<br /> <span class="help">Web Sitenizin Arama Motorları Tarafından Bulunmasını Kolaylaştıracak Anahtar Kelimeler (Keywords)</span></td>
					<td><textarea name="config_meta_keywords" cols="100" rows="3"><?php echo config('site_ayar_keywords'); ?></textarea>
					 <?php if ($val->config_meta_keywords_error) { ?>
					  <span class="error"><?php echo $val->config_meta_keywords_error; ?></span>
					  <?php } ?></td>
					</tr>					
					<tr>
					<td><span class="required">*</span> Copy Right<br /> <span class="help">Web Sitenizin En Alt Bölümünde Çıkacak Olan Kullanım Haklarının Kime Ait Olduğunu Belirten Yazı.</span></td>
					<td><input type="text" name="config_copyright" value="<?php echo config('site_ayar_copyright'); ?>" style="width:540px !important;" />
					 <?php if ($val->config_copyright_error) { ?>
					  <span class="error"><?php echo $val->config_copyright_error; ?></span>
					  <?php } ?></td>
					</tr>
					<tr>
					<td> Google Analytics Durum<br /> <span class="help">Web Sitenizinde Google Analytics'i Aktif Etmek İçin Seçiniz.</span></td>
					<td>
						<select name="config_google_durum">
							<option value="1" <?php echo (config('site_google_analytics_durum') == 1) ? 'selected="selected"' : NULL;?>>Açık</option>
							<option value="2" <?php echo (config('site_google_analytics_durum') == 2) ? 'selected="selected"' : NULL;?>>Kapalı</option>
						</select>
					</td>
					</tr>				
					<tr>
					<td>Google Analytics Kodu<br /> <span class="help">Web Sitenizinde Google Analytics Kullanmak İsterseniz Bu Alana Google Analytics Kodu Girebilirsiniz.</span></td>
					<td><textarea  name="config_google_kodu" cols="100" rows="3"><?php echo config('site_google_analytics_kodu');?></textarea></td>
					</tr>
					<tr>
						<td> Google Maps Durum<br /> <span class="help">Web Sitenizinde Google Maps'i Aktif Etmek İçin Seçiniz.</span></td>
						<td>
							<select name="config_google_maps_durum" onchange="if($(this).val() == 2){ $('.trr').css('visibility','hidden'); } else { $('.trr').css('visibility','visible');  }">
								<option value="1" <?php echo (config('site_google_maps_durum') == 1) ? 'selected="selected"' : NULL;?>>Açık</option>
								<option value="2" <?php echo (config('site_google_maps_durum') == 2) ? 'selected="selected"' : NULL;?>>Kapalı</option>
							</select>
						</td>
					</tr>				
					<tr class="trr" style="<?php echo (config('site_google_maps_durum') == 1) ? 'visibility:visible;' : 'visibility:hidden' ;?>">
						<td>Google Maps Kodu<br /> <span class="help">Web Sitenizinde Google Maps Kullanmak İsterseniz Bu Alana Google Maps Kodu Girebilirsiniz.</span></td>
						<td><textarea  name="config_google_maps_kodu" cols="100" rows="3"><?php echo config('site_google_maps_kodu');?></textarea></td>
					</tr>	
				</table>
			</div>


<!-- //////////// ÜRÜN DETAY TAB -->
			<div id="tab_productdetail">
				<table class="form">

					<tr>
						<td>Ürün Detayda Ürün Kodunu Göster:<br /><span class="help">Ürün detayda ürün kodunu göster.</span></td>
						<td>
						<?php if (config('site_ayar_urun_kodu_goster') == '1') { ?>
						<input type="radio" name="config_urun_kodu_goster" value="1" checked="checked" />
						Evet
						<input type="radio" name="config_urun_kodu_goster" value="0" />
						Hayır
						<?php } else { ?>
						<input type="radio" name="config_urun_kodu_goster" value="1" />
						Evet
						<input type="radio" name="config_urun_kodu_goster" value="0" checked="checked" />
						Hayır
						<?php } ?>
						<?php if ($val->config_customer_price_error) { ?>
						  <span class="error"><?php echo $val->config_customer_price_error; ?></span>
						  <?php } ?></td>
					</tr>
					
					
					<tr>
						<td>Ürün Detayda Beğeni Durumu Göster:<br /><span class="help">Ürün detayda Beğeni Durumu göster.</span></td>
						<td>
						<?php if (config('site_ayar_begeni_durumu_goster') == '1') { ?>
						<input type="radio" name="config_begeni_durumu_goster" value="1" checked="checked" />
						Evet
						<input type="radio" name="config_begeni_durumu_goster" value="0" />
						Hayır
						<?php } else { ?>
						<input type="radio" name="config_begeni_durumu_goster" value="1" />
						Evet
						<input type="radio" name="config_begeni_durumu_goster" value="0" checked="checked" />
						Hayır
						<?php } ?>
						<?php if ($val->config_customer_price_error) { ?>
						  <span class="error"><?php echo $val->config_customer_price_error; ?></span>
						  <?php } ?></td>
					</tr>	
					
				<tr>
						<td>Ürün Detayda Stok Durumu Göster:<br /><span class="help">Ürün detayda Stok Durumu göster.</span></td>
						<td>
						<?php if (config('site_ayar_stok_durumu_goster') == '1') { ?>
						<input type="radio" name="config_stok_durumu_goster" value="1" checked="checked" />
						Evet
						<input type="radio" name="config_stok_durumu_goster" value="0" />
						Hayır
						<?php } else { ?>
						<input type="radio" name="config_stok_durumu_goster" value="1" />
						Evet
						<input type="radio" name="config_stok_durumu_goster" value="0" checked="checked" />
						Hayır
						<?php } ?>
						<?php if ($val->config_customer_price_error) { ?>
						  <span class="error"><?php echo $val->config_customer_price_error; ?></span>
						  <?php } ?></td>
					</tr>
				<tr>
						<td>Ürün Detayda info (MetinBox) Göster:<br /><span class="help">Ürün detayda kısa bilgi göster.</span></td>
						<td>
						<?php if (config('site_ayar_urun_info_goster') == '1') { ?>
						<input type="radio" name="config_urun_info_goster" value="1" checked="checked" />
						Evet
						<input type="radio" name="config_urun_info_goster" value="0" />
						Hayır
						<?php } else { ?>
						<input type="radio" name="config_urun_info_goster" value="1" />
						Evet
						<input type="radio" name="config_urun_info_goster" value="0" checked="checked" />
						Hayır
						<?php } ?>
						<?php if ($val->config_customer_price_error) { ?>
						  <span class="error"><?php echo $val->config_customer_price_error; ?></span>
						  <?php } ?></td>
					</tr>	
					<tr>
						<td>Kampanyalı ürün  tarihinini göster:<br /><span class="help">Ürün detayında kampanyalı ürün  tarihinini göster.</span></td>
						<td>
						<?php if (config('site_ayar_urun_tarih_goster') == '1') { ?>
						<input type="radio" name="config_urun_tarih_goster" value="1" checked="checked" />
						Evet
						<input type="radio" name="config_urun_tarih_goster" value="0" />
						Hayır
						<?php } else { ?>
						<input type="radio" name="config_urun_tarih_goster" value="1" />
						Evet
						<input type="radio" name="config_urun_tarih_goster" value="0" checked="checked" />
						Hayır
						<?php } ?>
						<?php if ($val->config_customer_price_error) { ?>
						  <span class="error"><?php echo $val->config_customer_price_error; ?></span>
						  <?php } ?></td>
					</tr>
				<tr>
						<td>İndirimli ürünlerde kalan süreyi göster<br /><span class="help">Ürün detayında indirimli ürünlerde kalan süreyi göster</span></td>
						<td>
						<?php if (config('site_ayar_urun_kalansure_goster') == '1') { ?>
						<input type="radio" name="config_urun_kalansure_goster" value="1" checked="checked" />
						Evet
						<input type="radio" name="config_urun_kalansure_goster" value="0" />
						Hayır
						<?php } else { ?>
						<input type="radio" name="config_urun_kalansure_goster" value="1" />
						Evet
						<input type="radio" name="config_urun_kalansure_goster" value="0" checked="checked" />
						Hayır
						<?php } ?>
						<?php if ($val->config_customer_price_error) { ?>
						  <span class="error"><?php echo $val->config_customer_price_error; ?></span>
						  <?php } ?></td>
					</tr>	
					
				<tr>
						<td>Ürün detayının altında gösterilecek Ürün grubu<br /><span class="help">Ürün detayından sonra gösterilecek ürün grubu</span></td>
						<td>
						<select name="config_urundetay_urungrubu">
							<option value="daha_ucuz_urunler" <?php if (config('site_ayar_urundetay_urungrubu') == 'daha_ucuz_urunler') { echo 'selected="selected"';} ?> >Daha ucuz ürünler</option>
							<option value="benzer_urunler" <?php if (config('site_ayar_urundetay_urungrubu') == 'benzer_urunler') { echo 'selected="selected"';} ?> >Benzer ürünler</option>
							<option value="kampanyali_urunler" <?php if (config('site_ayar_urundetay_urungrubu') == 'kampanyali_urunler') { echo 'selected="selected"';} ?> >Kampanyalı ürünler</option>
							<option value="indirimli_urunler" <?php if (config('site_ayar_urundetay_urungrubu') == 'indirimli_urunler') { echo 'selected="selected"';} ?> >İndirimli ürünler</option>
							<option value="hicbiri" <?php if (config('site_ayar_urundetay_urungrubu') == 'hicbiri') { echo 'selected="selected"';} ?> >Hiçbiri</option>
						</select>
					</td>
				</tr>									

				</table>
			</div>
<!-- ////ÜRÜN DETAY TAB SONU -->

					

<!-- ////SEÇENEKLER TAB BAŞLANGIÇ -->
			<div id="tab_option">
				<table class="form">
					<tr>
						<td><span class="required">*</span> Sayfa Başına Ürün (Yönetim): <br /> <span class="help">Yönetim sayfalarında sayfa başına kaç ürün gösterileceğini seçin (Siparişler, Müşteriler, sayfaları gibi)</span></td>
						<td><input type="text" name="config_admin_limit" value="<?php echo config('site_ayar_urun_yonetim_sayfa'); ?>" size="3" />
						<?php if ($val->config_admin_limit_error) { ?>
						  <span class="error"><?php echo $val->config_admin_limit_error; ?></span>
						  <?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span> Sayfa Başına Ürün (Site): <br /> <span class="help">Mağazanız sayfalarında sayfa başına kaç ürün gösterileceğini seçin (Ürünler, Kategoriler, sayfaları gibi)</span></td>
						<td><input type="text" name="config_catalog_limit" value="<?php echo config('site_ayar_urun_site_sayfa'); ?>" size="3" />
						<?php if ($val->config_catalog_limit_error) { ?>
						  <span class="error"><?php echo $val->config_catalog_limit_error; ?></span>
						  <?php } ?></td>
					</tr>
					
					
					<tr>
						<td>Giriş Yapınca Fiyatları Göster:<br /><span class="help">Fiyatları müşteri giriş yaptıktan sonra göster.</span></td>
						<td>
						<?php if (config('site_ayar_fiyat_goster') == '1') { ?>
						<input type="radio" name="config_customer_price" value="1" checked="checked" />
						Evet
						<input type="radio" name="config_customer_price" value="0" />
						Hayır
						<?php } else { ?>
						<input type="radio" name="config_customer_price" value="1" />
						Evet
						<input type="radio" name="config_customer_price" value="0" checked="checked" />
						Hayır
						<?php } ?>
						<?php if ($val->config_customer_price_error) { ?>
						  <span class="error"><?php echo $val->config_customer_price_error; ?></span>
						  <?php } ?></td>
					</tr>
                   
					<tr>
						<td>Kdv'li Fiyat Göster:<br /><span class="help">Bu opsiyon seçili olduğunda, site genelinde tüm fiyatların yanında + kdv ifadesi yer alır. Ayrıca sepet ve sipariş ekranlarında kdv tutarları da gösterilir. Opsiyon kapatıldığında ise sepet ve sipariş dahil olmak üzere site genelindeki tüm + kdv ibareleri ve kdv bilgileri kaldırılır, bunun yerine ürünlerin satış fiyatları ekrana yansır.</span></td>
						<td>
						<?php if (config('site_ayar_kdv_goster') == '1') { ?>
						<input type="radio" name="config_kdv_display" value="1" checked="checked" />
						Evet
						<input type="radio" name="config_kdv_display" value="0" />
						Hayır
						<?php } else { ?>
						<input type="radio" name="config_kdv_display" value="1" />
						Evet
						<input type="radio" name="config_kdv_display" value="0" checked="checked" />
						Hayır
						<?php } ?>
						<?php if ($val->config_kdv_display_error) { ?>
						  <span class="error"><?php echo $val->config_kdv_display_error; ?></span>
						  <?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span> Standart Müşteri Grubu: <br /> <span class="help">Üye olan bir müşteri hangi grupta üye olacağını seçiniz.</span></td>
						<td>
							<?php
								$secili = ($val->config_standart_musteri_grup) ? $val->config_standart_musteri_grup:config('site_ayar_varsayilan_mus_grub');
								$_roles = array();
								$this->db->order_by('name','asc');
								$sorgu = $this->db->get_where('roles', array('parent_id' => 1));
								foreach($sorgu->result() as $roles)
								{
									$this->db->order_by('name','asc');
									$sorgu_opt = $this->db->get_where('roles', array('parent_id' => $roles->id));
									if($sorgu_opt->num_rows() > 0)
									{
										foreach($sorgu_opt->result() as $roles_opt)
										{
											$_roles[$roles->name][$roles_opt->id] = $roles_opt->name;
										}
									} else {
										$_roles[$roles->id] = $roles->name;
									}
								}

								echo form_dropdown('config_standart_musteri_grup', $_roles, $secili, 'style="width: 137px;"');
							?>
							<?php if ($val->config_standart_musteri_grup_error) { ?>
							<span class="error"><?php echo $val->config_standart_musteri_grup_error; ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> Kargo İndirimi Varmı ?: <br /> <span class="help">Sepet Toplam Şukadar TL'yi Aşan Ürünlerde Kargo Ücretsiz Durum.</span></td>
						<td>
							<?php $kargo_durum = ($val->config_kargo_indirimi_durum) ? $val->config_kargo_indirimi_durum:config('config_kargo_indirimi_durum'); ?>
							<?php if($kargo_durum == '1') { ?>
								<input type="radio" name="config_kargo_indirimi_durum" value="1" checked="checked" />
								Evet
								<input type="radio" name="config_kargo_indirimi_durum" value="0" />
								Hayır
							<?php } else { ?>
								<input type="radio" name="config_kargo_indirimi_durum" value="1" />
								Evet
								<input type="radio" name="config_kargo_indirimi_durum" value="0" checked="checked" />
								Hayır
							<?php } ?>
							<?php if ($val->config_kargo_indirimi_durum_error) { ?>
							<span class="error"><?php echo $val->config_kargo_indirimi_durum_error; ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> Kargo İndirimi Fiyat ?: <br /> <span class="help">Sepet Toplam Şukadar TL'yi Aşan Ürünlerde Kargo Ücretsiz Fiyatlandırma.</span></td>
						<td>
							<?php $kargo_fiyat = ($val->config_kargo_indirim_fiyat) ? $val->config_kargo_indirim_fiyat:config('config_kargo_indirim_fiyat'); ?>
							<input type="text" name="config_kargo_indirim_fiyat" value="<?php echo $kargo_fiyat; ?>" size="5" />
							<?php if ($val->config_kargo_indirim_fiyat_error) { ?>
							<span class="error"><?php echo $val->config_kargo_indirim_fiyat_error; ?></span>
							<?php } ?>
						</td>
					</tr>
				</table>
			</div>
<!-- ////SEÇENEKLER TAB BİTİŞ -->

<div id="tab_basket">
				<table class="form">
                
                 <!-- sepette ürün resmi göster -->
                    <tr>
						<td>Sepette ürün resmi göster:<br /><span class="help">Sepete eklenen ürünlerin thumbnail olarak resmini göster/gösterme.</span></td>
						<td>
						<?php if (config('site_ayar_sepet_resim_goster') == '1') { ?>
						<input type="radio" name="config_basket_view" value="1" checked="checked" />
						Evet
						<input type="radio" name="config_basket_view" value="0" />
						Hayır
						<?php } else { ?>
						<input type="radio" name="config_basket_view" value="1" />
						Evet
						<input type="radio" name="config_basket_view" value="0" checked="checked" />
						Hayır
						<?php } ?>
						<?php if ($val->config_basket_view_error) { ?>
						  <span class="error"><?php echo $val->config_basket_view_error; ?></span>
						  <?php } ?></td>
					</tr>
                    <!--/ sepet ürün resmi -->
                    
                         <tr>
						<td>Sepete git:<br /><span class="help">Ürün listelerinde sepete ekle butonuna tıklanıldığında sepete gitsin veya eklenildi uyarısı verip sepete gitmesin.</span></td>
						<td>
						<?php if (config('site_ayar_sepete_git') == '1') { ?>
						<input type="radio" name="config_basket_go" value="1" checked="checked" />
						Evet
						<input type="radio" name="config_basket_go" value="0" />
						Hayır
						<?php } else { ?>
						<input type="radio" name="config_basket_go" value="1" />
						Evet
						<input type="radio" name="config_basket_go" value="0" checked="checked" />
						Hayır
						<?php } ?>
						<?php if ($val->config_basket_go_error) { ?>
						  <span class="error"><?php echo $val->config_basket_go_error; ?></span>
						  <?php } ?></td>
					</tr>
				
				
				</table>
			</div>
<!-- ////SEPET TAB BİTİŞ -->

			
			<div id="tab_server">
				<table class="form">
					<tr>
						<td>SSL Kullanımı:<br /><span class="help">SSL kullanbilmeniz için hostunuzda SSL sertifikası kurulu olması gerekir.</span></td>
						<td><?php if (config('site_ayar_ssl')) { ?>
						  <input type="radio" name="config_ssl" value="1" checked="checked" />
						  Açık
						  <input type="radio" name="config_ssl" value="0" />
						  Kapalı
						  <?php } else { ?>
						  <input type="radio" name="config_ssl" value="1" />
						  Açık
						  <input type="radio" name="config_ssl" value="0" checked="checked" />
						  Kapalı
						  <?php } ?>
						<?php if ($val->config_ssl_error) { ?>
						  <span class="error"><?php echo $val->config_ssl_error; ?></span>
						  <?php } ?></td>
					</tr>
					<tr>
						<td>SSL Logosu: <br /><span class="help">Web Sitenizin Alt Bölümünde Görünecek Olan, Sitenizin SSL Sertifkası Kullanıdığını Gösteren Logo Kodları (Bu Kodlar SSL Sertifikası Sağlayan Firma Tarafından Verilir.).</span></td>
						<td><textarea name="config_ssl_code" cols="40" rows="5"><?php echo config('site_ayar_ssl_kod'); ?></textarea>
						<?php if ($val->config_ssl_code_error) { ?>
						  <span class="error"><?php echo $val->config_ssl_code_error; ?></span>
						  <?php } ?></td>
					</tr>

					<tr>
						<td valign="top">Bakım Modu</td>
						<td>
						<?php if($val->config_maintenance == 1 || config('site_ayar_bakim') == 1) { ?>
							<input type="radio" onclick="bakim_modu(this.value);" name="config_maintenance" value="1" checked="checked" />
							Açık
							<input type="radio" onclick="bakim_modu(this.value);" name="config_maintenance" value="0" />
							Kapalı
						<?php } else { ?>
							<input type="radio" onclick="bakim_modu(this.value);" name="config_maintenance" value="1" />
							Açık
							<input type="radio" onclick="bakim_modu(this.value);" name="config_maintenance" value="0" checked="checked" />
							Kapalı
						<?php } ?>
						<?php if ($val->config_maintenance_error) { ?>
						<span class="error"><?php echo $val->config_maintenance_error; ?></span>
						<?php } ?>
						</td>
					</tr>

					<tr>
						<td valign="top">&nbsp;</td>
						<td>

							<div class="bakim_detayi" style="<?php echo ($val->config_maintenance == 1 || config('site_ayar_bakim') == 1) ? 'display:block' : 'display:none;';echo ($val->config_maintenance == 1) ? 'display:block' : 'display:none;'; ?>">
								<textarea name="config_bakim_detay" id="bakim_sayfasi"><?php echo ($val->config_bakim_detay) ? $val->config_bakim_detay:config('site_ayar_bakim_sayfasi_detay'); ?></textarea>
							</div>
							<?php if ($val->config_bakim_detay_error) { ?>
							<span class="error"><?php echo $val->config_bakim_detay_error; ?></span>
							<?php } ?>
						</td>
					</tr>
				</table>
			</div>            
            
			<div id="tab_kurlar">
				<table class="form">
			    	<tr>
			        	<td valign="top">Kur Seçenekleri</td>
			        	<td>
			        		<?php
			        		$gelen_veri = ($val->config_site_ayar_kur) ? $val->config_site_ayar_kur:(config('site_ayar_kur')) ? config('site_ayar_kur'):1;
			        		?>
							<input type="radio" name="config_site_ayar_kur" value="1" onclick="$('.tr_class_girilen').attr('style', 'display:none;');" <?php echo set_radio('config_site_ayar_kur', '1', ($gelen_veri == 1) ? TRUE:FALSE); ?> />Sürekli Merkez Bankasından güncelle<br />
							<input type="radio" name="config_site_ayar_kur" value="2" onclick="$('.tr_class_girilen').attr('style', 'display:none;');" <?php echo set_radio('config_site_ayar_kur', '2', ($gelen_veri == 2) ? TRUE:FALSE); ?> />Merkez bankası verilerine %
						<?php
						$yuzdeler['01'] = '1';
						$yuzdeler['02'] = '2';
						$yuzdeler['03'] = '3';
						$yuzdeler['04'] = '4';
						$yuzdeler['05'] = '5';
						$yuzdeler['06'] = '6';
						$yuzdeler['07'] = '7';
						$yuzdeler['08'] = '8';
						$yuzdeler['09'] = '9';
						for($i=10;$i<=99;$i++)
						{
							$yuzdeler[$i] = $i;
						}
						echo form_dropdown('config_site_ayar_kur_yuzde', $yuzdeler, ($val->config_site_ayar_kur_yuzde) ? $val->config_site_ayar_kur_yuzde:config('site_ayar_kur_yuzde'));
						?>
							 ekle<br />
							<input type="radio" name="config_site_ayar_kur" value="3" onclick="$('.tr_class_girilen').attr('style', 'display:table_row;');" <?php echo set_radio('config_site_ayar_kur', '3', ($gelen_veri == 3) ? TRUE:FALSE); ?> />Girilen değerleri kullan<br />
						</td>
			      	</tr>
			      	<?php $tr_none = ($gelen_veri != 3) ? 'display:none;':'display:tablo-row;'; ?>
					<tr class="tr_class_girilen" style="<?php echo $tr_none; ?>">
						<td></td>
						<td>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							Alış&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							Satış
					  	</td>
					</tr>
					<tr class="tr_class_girilen" style="<?php echo $tr_none; ?>">
						<td>Dolar ($)</td>
						<td>
							<input type="text" style="width:60px;" name="config_kur_dolara" id="config_kur_dolara" value="<?php echo $kur_usd->kur_alis_manuel; ?>" />
							<input type="text" style="width:60px;" name="config_kur_dolars" id="config_kur_dolars" value="<?php echo $kur_usd->kur_satis_manuel; ?>" />
					  	</td>
					</tr>
					<tr class="tr_class_girilen" style="<?php echo $tr_none; ?>">
						<td>Euro (€)</td>
						<td>
							<input type="text" style="width:60px;" name="config_kur_euroa" id="config_kur_euros" value="<?php echo $kur_eur->kur_alis_manuel; ?>" />
							<input type="text" style="width:60px;" name="config_kur_euros" id="config_kur_euros" value="<?php echo $kur_eur->kur_satis_manuel; ?>" />
					  	</td>
					</tr>
					<tr>
						<td>Güncel Merkez Bankası Bilgileri</td>
						<td>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							Alış&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							Satış
					  	</td>
					</tr>
					<tr>
						<td>Dolar ($)</td>
						<td>
							<span style="margin-left:12px;width:60px;display:block;float:left;"><?php echo $kur_usd->kur_alis; ?></span>
							<span style="width:60px;display:block;float:left;"><?php echo $kur_usd->kur_satis; ?></span>
					  	</td>
					</tr>
					<tr>
						<td>Euro (€)</td>
						<td>
							<span style="margin-left:12px;width:60px;display:block;float:left;"><?php echo $kur_eur->kur_alis; ?></span>
							<span style="width:60px;display:block;float:left;"><?php echo $kur_eur->kur_satis; ?></span>
					  	</td>
					</tr>
			    </table>
			</div>

			<div id="tab_coupon">
				<table class="form">
					<tr>
						<td>Kupon Uygulaması<span class="help"></span></td>
						<td><?php echo form_dropdown('config_coupon_status', array('Kapalı', 'Açık'), config('site_ayar_coupon_status')) ?></td>
					</tr>
					<tr id="coupon_limit">
						<td>Günlük Kupon Kullanım Limiti<span class="help">Bir günde maksimum kaç adet kullanılabilsin? Sınırsız yapmak için 0 yazınız.</span></td>
						<td><input type="text" name="config_coupon_limit" value="<?php echo config('site_ayar_coupon_limit') ?>" /></td>
					</tr>
				</table>				
			</div>

			<div id="tab_facebook">
				<table class="form">
					<tr>
						<td>Facebook Giriş Durumu</td>
						<td><?php echo form_dropdown('config_facebook_status', array('0' => 'Kapalı', '1' => 'Açık'), config('site_ayar_facebook_status')) ?></td>
					</tr>
					<tr id="facebook_app_id">
						<td>
							Facebook Uygulama Numarası
							<span class="help">Lütfen facebook uygulama (app_id) numaranızı yazın.</span>
						</td>
						<td><input type="text" name="config_facebook_app_id" value="<?php echo config('site_ayar_facebook_app_id') ?>" style="width:540px !important;" /></td>
					</tr>
					<tr id="facebook_secret">
						<td>
							Facebook Uygulama Şifresi
							<span class="help">Lütfen facebook uygulama (secret) şifrenizi yazın.</span>
						</td>
						<td><input type="text" name="config_facebook_secret" value="<?php echo config('site_ayar_facebook_secret') ?>" style="width:540px !important;" /></td>
					</tr>
					<?php if (config('facebook_app_status')) { ?>
						<tr id="facebook_url">
							<td>
								Facebook Uygulama Adresi
								<span class="help">Lütfen facebook uygulama (app_url) adresini yazın.</span>
							</td>
							<td><input type="text" name="config_facebook_url" value="<?php echo config('site_ayar_facebook_url') ?>" style="width:540px !important;" /></td>
						</tr>
						<tr id="facebook_tema">
							<td>
								Facebook Uygulama Teması
								<span class="help">Facebook uygulama temaları.</span>
							</td>
							<td>
								<?php
									$face_tema_secimi = ($val->config_facebook_tema) ? $val->config_facebook_tema : config('site_ayar_facebook_tema');
									echo form_dropdown('config_facebook_tema', $face_temalar, $face_tema_secimi);
								?>
							</td>
						</tr>
						<tr id="facebook_renk">
							<td>
								Facebook Uygulama Tema Renk
								<span class="help">Facebook uygulama tema renkleri.</span>
							</td>
							<td>
								<?php
									$face_tema_asset_secimi = ($val->config_facebook_tema_asset) ? $val->config_facebook_tema_asset : config('site_ayar_facebook_tema_asset');
									echo form_dropdown('config_facebook_tema_asset', $face_renkler, $face_tema_asset_secimi);
								?>
							</td>
						</tr>
					<?php } ?>
				</table>				
			</div>

    	</form>
  	</div>
</div>
<script type="text/javascript">
	function bakim_modu(val)
	{
		if(val == 1)
		{
			$('.bakim_detayi').attr('style','display:block;');
		} else {
			$('.bakim_detayi').attr('style','display:none;');
		}
	}
</script>

<script type="text/javascript">
<!--
function image_upload(field, preview) {
	$('#dialog').remove();
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="<?php echo yonetim_url(); ?>/dosya_yonetici?field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	$('#dialog').dialog({
		title: 'Resim Yükle',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: '<?php echo yonetim_url("dosya_yonetici/image"); ?>',
					type: 'POST',
					data: 'image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" onmouseover="$(this).attr(\'src\', \'<?php echo $this->image_model->resize("resim_ekle_hover.jpg", 100, 100); ?>\');" onmouseout="$(this).attr(\'src\', \'' + data + '\');" title="Resim eklemek yada değiştirmek için tıklayınız." class="image" onclick="image_upload(\'' + field + '\', \'' + preview + '\', \'' + data + '\');" style="cursor: pointer; border: 1px solid #EEEEEE;" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script>
<script type="text/javascript"><!--
CKEDITOR.replace('bakim_sayfasi', {
	filebrowserBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserImageBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserFlashBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserImageUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserFlashUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>'
});
CKEDITOR.config.width = 1050;
CKEDITOR.config.height = 400;

//--></script>

<script type="text/javascript"><!--
$('#tabs a').tabs();
$('#languages a').tabs();
jQuery(function($){
   $("#config_telephone,#config_telephone2,#config_telephone3,#config_telephone4,#config_telephone5, #config_fax,#config_fax2,#config_fax3").mask("(9999) 999 99 99");
});

//--></script>
<?php $this->load->view('yonetim/footer_view'); ?>