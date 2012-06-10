<?php
	$this->load->view('yonetim/header_view');
	$val = $this->validation;
?>
<div id="header_bgnew">
<div id="header_bg_orta">
    <div style="padding-top:110px;margin:0 auto;text-align:center;">
    <form action="<?php echo yonetim_url('giris'); ?>" method="post" enctype="multipart/form-data" id="form">
      <table style="width: 459px;margin:auto;" align="center">
        <tr>
          <td style="color:#656464;font-size:14px;font-family:Arial;text-align:center;padding-left:68px;">Hoşgeldiniz, IP Adresiniz:<font style="color:#04567f;font-weight:bold;font-family:Arial;font-size:14px;"><?php echo $this->input->ip_address(); ?></font><br>İyi Çalışmalar Dileriz...</td>
        </tr>
        <tr >
          <td style="padding-top:13px;font-size:14px;font-weight:bold;color:#04567f;padding-left:120px;">E-Posta Adresi<br />
            <input type="text" name="username" value="<?php echo ($val->username) ? $val->username:NULL; ?>" autocomplete="off" class="header_inputt" style="text-align:center;" />
            <br />
            Parola<br />
            <input type="password" name="password" value="" autocomplete="off" class="header_inputt" style="text-align:center;" /></td>
        </tr>
        <tr>
          <td>
          	<div id="hatali_giris" class="giris_uyari" style="display:none;">Kullanıcı Adı ya da Şifrenizi Yanlış Girdiniz</div>
          	<div id="yukleniyor" style="display:none;text-align:left;float:left;margin-left:170px;"><img src="<?php echo yonetim_resim(); ?>loading2.gif" /> &nbsp;Giriş Yapılıyor. Lütfen Bekleyin.</div>
          	<a onclick="form_gonder();" class="buton" id="form_buton" style="margin-right:50px;float:right;"><span>&nbsp;&nbsp;Giriş&nbsp;&nbsp;</span></a>
      	</td>
        </tr>
      </table>
    </form>
    </div>
	<script type="text/javascript">
	$('#form input').keydown(function(e) {
		if (e.keyCode == 13) {
			form_gonder();
		}
	});

	function form_gonder()
	{
		$('#hatali_giris').hide();
		$('#yukleniyor').show();
		$('#form_buton').hide();
		
		setTimeout("$('#form').submit()", 1000);
	}

	<?php if( !empty($val->error_string) OR $this->dx_auth->get_auth_error() ) { ?>
		$('#yukleniyor').hide();
		$('#hatali_giris').show();
	<?php } ?>

	</script>

  </div>
</div>
</div>