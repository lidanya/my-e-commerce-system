<?php
	$this->load->view('yonetim/header_view');
?>
	<div class="box">
		<div class="left"></div>
		<div class="right"></div>
		<div class="heading">
			<h1 style="background-image: url('<?php echo yonetim_resim() ?>error.png');">Erişim Hatası</h1>
		</div>
		<div class="content">
			<div style="border: 1px solid #DDDDDD; background: #F7F7F7; text-align: center; padding: 15px;">Üzgünüm Bu Sayfaya Erişim Yetkiniz Yok.</div>
		</div>
	</div>
<?php
	$this->load->view('yonetim/footer_view');
?>