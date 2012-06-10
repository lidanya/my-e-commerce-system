<script>
	jQuery(function($){ jQuery("#tel, #fax").mask("(9999) 999 99 99"); });	
	
	function bolgeGetir(){	
		ulke_kod = jQuery('#ulke').val();
		if(ulke_kod != '')
		{
			jQuery.ajax({
				type:	"POST",
				url:	Daynex_Url_Ssl + "uye/fatura/ajax_bolge_getir",
				data:	"ulke_id=" + ulke_kod,
				dataType: "json",
				beforeSend:function()
				{
					// jQuery('.ulke_err').html('<img src="<?php echo site_resim();?>loading_2.gif" style="position:relative; left:2px; top:4px;" alt="" />');
					// jQuery('.sehir_err').html('<img src="<?php echo site_resim();?>loading_2.gif" style="position:relative; left:2px; top:4px;" alt="" />');
				},
				success:function(data)
				{
					// jQuery('.ulke_err').html('');
					// jQuery('.ulke_err').html('<img src="<?php echo site_resim();?>ok.png" style="position:relative; left:2px; top:4px;" alt="" />');
					// jQuery('.sehir_err').html('<img src="<?php echo site_resim();?>ok.png" style="position:relative; left:2px; top:4px;" alt="" />');
					// jQuery('#ulke').css('background-color','#ffffff');
					jQuery('#bolgeler').html(data.bolge);
					
				}
			});
		}
	}
</script>
<!--orta -->
<?php 
$ftr = $fatura->row();
?>
<div id="orta" class="sola">
	<h1 id="sayfa_baslik">Fatura Bilgisi Güncelleme</h1>
	<!-- Kaydet & Düzenle -->
	<form action="<?php echo ssl_url('uye/fatura/duzenle/'. $this->uri->segment(4)); ?>" method="post" name="fatura_ekle" id="fatura_ekle">
		<div class="f_form">
			<div style="margin: 20px auto;" class="f_bilgi">Yeni bir fatura bilgisi kaydetmek için lütfen aşağıdaki formu doldurun. Formu doldururken eğer bir <b>Firma Adı</b> girerseniz faturanız <b>"Kurumsal Fatura"</b> olarak değerlendirilir. Bu anlamda <b>"Vergi Dairesi"</b> ve <b>"Vergi Numarası"</b> Hanelerinin doldurulması zorunlu olacaktır. <br><br>Fatura bilgisinin <b>"Bireysel Fatura"</b> olarak değerlendirilmesini isterseniz <b>"Firma Adı"</b> hanesini boş bırakmanız yeterli olacaktır. Bu durumda da <b>"T.C. Kimlik Numarası"</b> girmeniz zorunlu tutulmaktadır. </div>
			<span class="f_text sola">Fatura Adı</span>
			<span class="f_box sola"><input type="text" name="fatura_adi" id="fatura_adi" value="<?php echo (!empty($val->fatura_adi)) ? $val->fatura_adi : $ftr->inv_name ; ?>" /></span>
			<div class="clear"></div>
			<?php
			echo ($val->fatura_adi_error) ? '<div style="line-height: 20px;color:red;">'. $val->fatura_adi_error .'</div><div class="clear"></div>' : NULL;
			?>
			<span class="f_text sola">Adınız</span>
			<span class="f_box sola"><input type="text" name="adi" id="adi" value="<?php echo (!empty($val->adi)) ? $val->adi : $ftr->inv_username ;?>" /></span>
			<div class="clear"></div>
			<?php
			echo ($val->adi_error) ? '<div style="line-height: 20px;color:red;">'. $val->adi_error .'</div><div class="clear"></div>' : NULL;
			?>
			<span class="f_text sola">Soyadınız</span>
			<span class="f_box sola"><input type="text" name="soyad" id="soyad" value="<?php echo (!empty($val->soyad)) ? $val->soyad : $ftr->inv_usersurname ?>" /></span>
			<div class="clear"></div>
			<?php
			echo ($val->soyad_error) ? '<div style="line-height: 20px;color:red;">'. $val->soyad_error .'</div><div class="clear"></div>' : NULL;
			?>
			<span class="f_text sola">TC Kimlik No</span>
			<span class="f_box sola"><input type="text" name="tckimlik" id="tckimlik" maxlength="11" value="<?php echo (!empty($val->tckimlik)) ? $val->tckimlik : $ftr->inv_tckimlik ?>" /></span>
			<div class="clear"></div>
			<?php
			echo ($val->tckimlik_error) ? '<div style="line-height: 20px;color:red;">'. $val->tckimlik_error .'</div><div class="clear"></div>' : NULL;
			?>
			<span class="f_text sola">&nbsp;</span>
			<span class="f_box sola" style="text-align:center;width:250px;">T.C Kimlik Numaranızı <a href="http://tckimlik.nvi.gov.tr/Web/QueryIdentityNumber.aspx" target="_blank">Buradan</a> Öğrenin</span>
			<div class="clear"></div>
	
			<span class="f_text sola">Firmanızın Adı</span>
			<span class="f_box sola"><input type="text" name="firmaadi" id="firmaadi" value="<?php echo (!empty($val->firmaadi)) ? $val->firmaadi :  $ftr->inv_firma ?>" /></span>
			<div class="clear"></div>
			<?php
			echo ($val->firmaadi_error) ? '<div style="line-height: 20px;color:red;">'. $val->firmaadi_error .'</div><div class="clear"></div>' : NULL;
			?>
			<span class="f_text sola">Adresiniz</span>
			<span class="f_box sola"><textarea name="adres" id="adres"><?php echo (!empty($val->adres)) ? $val->adres :  $ftr->inv_adr_id ;?></textarea></span>
			<div class="clear"></div>
			<?php
			echo ($val->adres_error) ? '<div style="line-height: 20px;color:red;">'. $val->adres_error .'</div><div class="clear"></div>' : NULL;
			?>
					
			<span class="f_text sola">Ülke</span>
			<span class="f_box sola">
				<?php
					$once_secim= (!empty($val->ulke)) ?  $val->ulke : $ftr->inv_ulke;
					$seciniz = '';
					$style = ' onchange="bolgeGetir();" id="ulke"';
					echo form_dropdown_from_db('ulke', 'SELECT ulke_id, ulke_adi FROM daynex_ulkeler', $seciniz, $once_secim, $style);
				?>
				<span class="ulke_err"></span>
			</span>
			<span class="f_text_kucuk sola">Şehir</span>
			<span class="f_box sola">
				<span id="bolgeler" style="display:inline;">
				<?php
					if($this->input->post('ulke') && $this->input->post('sehir'))
					{
						$once_secim= $val->sehir;
						$seciniz = '';
						$style = 'id="sehir"';
						echo form_dropdown_from_db('sehir', 'SELECT bolge_id, bolge_adi FROM daynex_ulke_bolgeleri  Where ulke_id='.$val->ulke, $seciniz, $once_secim, $style);
					} else {
						$once_secim= $ftr->inv_sehir;
						$seciniz = '';
						$style = 'id="sehir"';
						echo form_dropdown_from_db('sehir', 'SELECT bolge_id, bolge_adi FROM daynex_ulke_bolgeleri  Where ulke_id='.$ftr->inv_ulke, $seciniz, $once_secim, $style);
					}
					
				
				?>
				</span>
				<span class="sehir_err"></span>
			</span>
			<span class="f_text_kucuk sola">İlçe</span>
			<span class="f_box sola"><input type="text" style="width:90px;" name="ilce" id="ilce" value="<?php echo (!empty($val->ilce)) ? $val->ilce : $ftr->inv_ilce ?>" /></span>
			<span class="f_text_kucuk sola">Posta Kodu</span>
			<span class="f_box sola"><input type="text" style="width:90px;" name="postak" id="postak" maxlength="5" value="<?php echo (!empty($val->postak)) ? $val->postak : $ftr->inv_pkodu ?>" /></span>
			<div class="clear"></div>
			<?php
			echo ($val->ulke_error) ? '<div style="line-height: 20px;color:red;">'. $val->ulke_error .'</div><div class="clear"></div>' : NULL;
			?>
			
			<?php
			echo ($val->sehir_error) ? '<div style="line-height: 20px;color:red;">'. $val->sehir_error .'</div><div class="clear"></div>' : NULL;
			?>
			
			<?php
			echo ($val->ilce_error) ? '<div style="line-height: 20px;color:red;">'. $val->ilce_error .'</div><div class="clear"></div>' : NULL;
			?>
			<?php
			echo ($val->postak_error) ? '<div style="line-height: 20px;color:red;">'. $val->postak_error .'</div><div class="clear"></div>' : NULL;
			?>
	
	
			
			<span class="f_text sola">Telefon</span>
			<span class="f_box sola"><input type="text" name="tel" id="tel" value="<?php echo (!empty($val->tel)) ? $val->tel : $ftr->inv_tel;?>" /> </span>
			<div class="clear"></div>
			<?php
			echo ($val->tel_error) ? '<div style="line-height: 20px;color:red;">'. $val->tel_error .'</div><div class="clear"></div>' : NULL;
			?>
			
			<span class="f_text sola">Faks</span>
			<span class="f_box sola"><input type="text" name="fax" id="fax" value="<?php echo (!empty($val->fax)) ? $val->fax : $ftr->inv_fax; ?>" /></span>
			<div class="clear"></div>
			<?php
			echo ($val->fax_error) ? '<div style="line-height: 20px;color:red;">'. $val->fax_error .'</div><div class="clear"></div>' : NULL;
			?>
					
			<span class="f_text sola">Vergi Dairesi</span>
			<span class="f_box sola"><input type="text" style="width:183px;" name="vergid" id="vergid" value="<?php echo (!empty($val->vergid)) ? $val->vergid : $ftr->inv_vda ?>" /></span>
			<span class="f_text sola" style="width:130px;margin-left:20px;">Vergi Numarası</span>
			<span class="f_box sola"><input type="text" style="width:183px;" name="vergin" id="vergin" value="<?php echo (!empty($val->vergin)) ? $val->vergin : $ftr->inv_vno ?>" /></span>
			<div class="clear"></div>
	
			<?php
			echo ($val->vergid_error) ? '<div style="line-height: 20px;color:red;">'. $val->vergid_error .'</div><div class="clear"></div>' : NULL;
			?>
	
			<?php
			echo ($val->vergin_error) ? '<div style="line-height: 20px;color:red;">'. $val->vergin_error .'</div><div class="clear"></div>' : NULL;
			?>
		</div>
		<p style="text-align:right;width:690px;margin-top:10px;">
			<a class="butonum" href="<?php echo ssl_url('uye/fatura'); ?>">
				<span class="butsol"></span>
				<span class="butor">İptal</span>
				<span class="butsag"></span>
			</a>
			<a class="butonum" href="javascript:;" onclick="$('#fatura_ekle').submit();">
				<span class="butsol"></span>
				<span class="butor">Bilgileri Kaydet</span>
				<span class="butsag"></span>
			</a>
			
		</p>
		</form>
	<!-- Kaydet & Düzenle -->
</div>
<!--orta son-->