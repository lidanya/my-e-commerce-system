<?php 
$this->load->view('yonetim/header_view');  
?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('<?php echo yonetim_resim();?>order.png');">Şubeler</h1>
    <div class="buttons">
    	<a href="javascript:void(0);" onclick="$('#form').attr('action','<?php echo current_url(); ?>').submit();" class="buton"><span>Kaydet</span></a>
    	<a href="<?php echo yonetim_url('sistem/bayiler'); ?>" class="buton" style="margin-left:10px;"><span>İptal</span></a>
	</div>
  </div>
  <div class="content">
    <div style="display: inline-block; width: 100%;">

      <form action="" method="post" enctype="multipart/form-data" id="form">
      	<input type="hidden" value="<?php echo $id;?>" name="bayi_id"/>
		    <table class="form">
		      <tr>
		        <td><span class="required">*</span> Şube Adı:</td>
		        <td><input type="text" name="bayi_adi" style="width:300px;" value="<?php echo ($val->bayi_adi) ? $val->bayi_adi : $bayi->bayi_adi;?>" />
		        	<?php 
		        	echo ($val->bayi_adi_error) ? '<br><span class="required">'.$val->bayi_adi_error.'</span>' : NULL;
		        	?>
		        </td>
		      </tr>
		      <tr>
		        <td><span class="required">*</span> E-Posta:</td>
		        <td><input type="text" name="bayi_eposta" style="width:300px;" value="<?php echo ($val->bayi_eposta) ? $val->bayi_eposta : $bayi->bayi_eposta;?>" />
		        	<?php 
		        	echo ($val->bayi_eposta_error) ? '<br><span class="required">'.$val->bayi_eposta_error.'</span>' : NULL;
		        	?>
		        </td>
		      </tr>
		      
		      <tr>
		        <td><span class="required">*</span> Telefon 1:</td>
		        <td><input type="text" name="bayi_tel" id="tel1" style="width:300px;" value="<?php echo ($val->bayi_tel) ? $val->bayi_tel : $bayi->bayi_tel;?>" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span title="Santral Varmı ?">Pbx ?</span> <input type="radio" name="bayi_tel_p" value="1" <?php echo ($bayi->bayi_tel_p == '1') ? 'checked="checked"':NULL; ?> />&nbsp;Evet&nbsp;&nbsp;<input type="radio" name="bayi_tel_p" value="0" <?php echo ($bayi->bayi_tel_p == '0') ? 'checked="checked"':NULL; ?> />&nbsp;Hayır
		        	<?php 
		        	echo ($val->bayi_tel_error) ? '<br><span class="required">'.$val->bayi_tel_error.'</span>' : NULL;
		        	?>
		        </td>
		      </tr>
		      <tr>
		        <td>Telefon 2:</td>
		        <td><input type="text" name="bayi_tel2" id="tel2" style="width:300px;" value="<?php echo ($val->bayi_tel2) ? $val->bayi_tel2 : $bayi->bayi_tel2;?>" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span title="Santral Varmı ?">Pbx ?</span> <input type="radio" name="bayi_tel2_p" value="1" <?php echo ($bayi->bayi_tel2_p == '1') ? 'checked="checked"':NULL; ?> />&nbsp;Evet&nbsp;&nbsp;<input type="radio" name="bayi_tel2_p" value="0" <?php echo ($bayi->bayi_tel2_p == '0') ? 'checked="checked"':NULL; ?> />&nbsp;Hayır
		        </td>
		      </tr>
		      <tr>
		        <td>Telefon 3:</td>
		        <td><input type="text" name="bayi_tel3" id="tel3" style="width:300px;" value="<?php echo ($val->bayi_tel3) ? $val->bayi_tel3 : $bayi->bayi_tel3;?>" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span title="Santral Varmı ?">Pbx ?</span> <input type="radio" name="bayi_tel3_p" value="1" <?php echo ($bayi->bayi_tel3_p == '1') ? 'checked="checked"':NULL; ?> />&nbsp;Evet&nbsp;&nbsp;<input type="radio" name="bayi_tel3_p" value="0" <?php echo ($bayi->bayi_tel3_p == '0') ? 'checked="checked"':NULL; ?> />&nbsp;Hayır
		        </td>
		      </tr>

		      <tr>
		        <td>Telefon 4:</td>
		        <td><input type="text" name="bayi_tel4" id="tel4" style="width:300px;" value="<?php echo ($val->bayi_tel4) ? $val->bayi_tel4 : $bayi->bayi_tel4;?>" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span title="Santral Varmı ?">Pbx ?</span> <input type="radio" name="bayi_tel4_p" value="1" <?php echo ($bayi->bayi_tel4_p == '1') ? 'checked="checked"':NULL; ?> />&nbsp;Evet&nbsp;&nbsp;<input type="radio" name="bayi_tel4_p" value="0" <?php echo ($bayi->bayi_tel4_p == '0') ? 'checked="checked"':NULL; ?> />&nbsp;Hayır
		        </td>
		      </tr>

		      <tr>
		        <td>Telefon 5:</td>
		        <td><input type="text" name="bayi_tel5" id="tel5" style="width:300px;" value="<?php echo ($val->bayi_tel5) ? $val->bayi_tel5 : $bayi->bayi_tel5;?>" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span title="Santral Varmı ?">Pbx ?</span> <input type="radio" name="bayi_tel5_p" value="1" <?php echo ($bayi->bayi_tel5_p == '1') ? 'checked="checked"':NULL; ?> />&nbsp;Evet&nbsp;&nbsp;<input type="radio" name="bayi_tel5_p" value="0" <?php echo ($bayi->bayi_tel5_p == '0') ? 'checked="checked"':NULL; ?> />&nbsp;Hayır
		        </td>
		      </tr>

 			  <tr>
		        <td>Fax 1:</td>
		        <td><input type="text" name="bayi_fax" id="fax1" style="width:300px;" value="<?php echo ($val->bayi_fax) ? $val->bayi_fax: $bayi->bayi_fax;?>" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span title="Santral Varmı ?">Pbx ?</span> <input type="radio" name="bayi_fax_p" value="1" <?php echo ($bayi->bayi_fax_p == '1') ? 'checked="checked"':NULL; ?> />&nbsp;Evet&nbsp;&nbsp;<input type="radio" name="bayi_fax_p" value="0" <?php echo ($bayi->bayi_fax_p == '0') ? 'checked="checked"':NULL; ?> />&nbsp;Hayır
		        </td>
		      </tr>
		      <tr>
		        <td>Fax 2:</td>
		        <td><input type="text" name="bayi_fax2" id="fax2" style="width:300px;" value="<?php echo ($val->bayi_fax2) ? $val->bayi_fax2 : $bayi->bayi_fax2;?>" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span title="Santral Varmı ?">Pbx ?</span> <input type="radio" name="bayi_fax2_p" value="1" <?php echo ($bayi->bayi_fax2_p == '1') ? 'checked="checked"':NULL; ?> />&nbsp;Evet&nbsp;&nbsp;<input type="radio" name="bayi_fax2_p" value="0" <?php echo ($bayi->bayi_fax2_p == '0') ? 'checked="checked"':NULL; ?> />&nbsp;Hayır
		        </td>
		      </tr>
		      <tr>
		        <td>Fax 3:</td>
		        <td><input type="text" name="bayi_fax3" id="fax3" style="width:300px;" value="<?php echo ($val->bayi_fax3) ? $val->bayi_fax3 : $bayi->bayi_fax3;?>" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span title="Santral Varmı ?">Pbx ?</span> <input type="radio" name="bayi_fax3_p" value="1" <?php echo ($bayi->bayi_fax3_p == '1') ? 'checked="checked"':NULL; ?> />&nbsp;Evet&nbsp;&nbsp;<input type="radio" name="bayi_fax3_p" value="0" <?php echo ($bayi->bayi_fax3_p == '0') ? 'checked="checked"':NULL; ?> />&nbsp;Hayır
		        </td>
		      </tr>
		      <tr>
		        <td><span class="required">*</span> Adres:</td>
		        <td><textarea name="bayi_adres" style="width:300px; height:60px;"><?php echo ($val->bayi_adres) ? $val->bayi_adres : $bayi->bayi_adres;?></textarea>
		        	<?php 
		        	echo ($val->bayi_adres_error) ? '<br><span class="required">'.$val->bayi_adres_error.'</span>' : NULL;
		        	?>
		        </td>
		      </tr>
			  <tr>
		        <td><span class="required">*</span> Google Maps Durum:</td>
		        <td>
		        	<select name="google_durum" onchange="if($(this).val() == 2){ $('.trr').css('visibility','hidden'); } else { $('.trr').css('visibility','visible');  }">
		        		<option value="2" <?php echo $val->google_durum == 2 || $bayi->bayi_maps_flag == 2 ? 'selected="selected"' : NULL;?>>Kapalı</option>
						<option value="1" <?php echo $val->google_durum == 1 || $bayi->bayi_maps_flag == 1 ? 'selected="selected"' : NULL;?>>Açık</option>
		        	</select>
		        </td>
		      </tr>	
		      <tr class="trr" style="<?php echo ($val->google_durum == 1 || $bayi->bayi_maps_flag == 1) ? 'visibility:visible;' : 'visibility:hidden' ;?>">
		        <td><span class="required">*</span> Google Maps Kodu ( Harita ):</td>
		        <td>
		        	<textarea name="bayi_maps_kodu" style="width:300px; height:60px;"><?php echo ($val->bayi_maps_kodu) ? $val->bayi_maps_kodu : $bayi->bayi_maps_kodu;?></textarea>
		        	<?php 
		        	echo ($val->bayi_maps_kodu_error) ? '<br><span class="required">'.$val->bayi_maps_kodu_error.'</span>' : NULL;
		        	?>
	        	</td>
		      </tr>
			  </table>
	  </form>
  </div>
  </div>
</div>
<script type="text/javascript">
<!--
$(function($){ $("#tel1,#tel2,#tel3,#tel4,#tel5, #fax1,#fax2,#fax3").mask("(9999) 999 99 99"); });
//--></script>

<script type="text/javascript"><!--
$('.vtabs a').tabs(); 
//--></script>
<?php 
$this->load->view('yonetim/footer_view');  
?>