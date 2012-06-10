<?php
	$kullanici_bilgi = $this->yonetim_model->kullanici_bilgi_getir();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//TR" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv='content-language' content="tr" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo config('site_ayar_baslik'); ?></title>

	<base href="<?php echo base_url(); ?>">

	<link media="screen" rel="stylesheet" type="text/css" href="<?php echo yonetim_css(); ?>stylesheet.css" />
	<link media="screen" rel="stylesheet" type="text/css" href="<?php echo yonetim_css(); ?>toplu_mail.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo yonetim_css(); ?>menu.css" media="screen" />

	<script type="text/javascript" src="<?php echo yonetim_js(); ?>jquery/jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="<?php echo yonetim_js(); ?>jquery/ajax_post.js"></script>
	<script type="text/javascript" src="<?php echo yonetim_js(); ?>jquery/ui/jquery-ui-1.8.9.custom.min.js"></script>
	<link media="screen" rel="stylesheet" type="text/css" href="<?php echo yonetim_js(); ?>jquery/ui/themes/ui-lightness/jquery-ui-1.8.9.custom.css" />
	<script type="text/javascript" src="<?php echo yonetim_js(); ?>jquery/ui/external/jquery.bgiframe-2.1.2.js"></script>
	<script type="text/javascript" src="<?php echo yonetim_js(); ?>jquery/tabs.js"></script>
	<script type="text/javascript" src="<?php echo yonetim_js(); ?>jquery/php.default.min.js"></script>
	<script type="text/javascript" src="<?php echo yonetim_js(); ?>ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="<?php echo yonetim_js(); ?>jquery/thumbnailviewer.js"></script>
	<script type="text/javascript" src="<?php echo yonetim_js(); ?>jquery/hoverIntent.js"></script>
    <script type="text/javascript" src="<?php echo yonetim_js(); ?>jquery/jquery.scrollTo-1.4.2-min.js"></script>
    <script type="text/javascript" src="<?php echo yonetim_js(); ?>jquery/maskedinput-1.2.2.min.js"></script>
    <script type="text/javascript" src="<?php echo yonetim_js(); ?>jquery/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="<?php echo yonetim_js(); ?>menu.js"></script>
	<script type="text/javascript">

	// initialise plugins
	jQuery(function(){
		jQuery('ul.sf-menu').superfish();
	});

	$(document).ready(function(){
		var currentPosition = 0;
		var slideWidth = 360;
		var slides = $('.slide');
		var numberOfSlides = slides.length;

		// Remove scrollbar in JS
		$('#slidesContainer').css('overflow', 'hidden');

		// Wrap all .slides with #slideInner div
		slides
		.wrapAll('<div id="slideInner"></div>')
		// Float left to display horizontally, readjust .slides width
		.css({
		  'float' : 'left',
		  'width' : slideWidth
		});

		// Set #slideInner width equal to total width of all slides
		$('#slideInner').css('width', slideWidth * numberOfSlides);

		// Insert controls in the DOM
		$('#slideshow')
		.prepend('<span class="control" id="leftControl">Clicking moves left</span>')
		.append('<span class="control" id="rightControl">Clicking moves right</span>');

		// Hide left arrow control on first load
		manageControls(currentPosition);

		// Create event listeners for .controls clicks
		$('.control')
		.bind('click', function(){
		// Determine new position
		currentPosition = ($(this).attr('id')=='rightControl') ? currentPosition+1 : currentPosition-1;

		// Hide / show controls
		manageControls(currentPosition);
		// Move slideInner using margin-left
		$('#slideInner').animate({
		  'marginLeft' : slideWidth*(-currentPosition)
		});
		});

		// manageControls: Hides and Shows controls depending on currentPosition
		function manageControls(position){
		// Hide left arrow if position is first slide
		if(position==0){ $('#leftControl').hide() } else{ $('#leftControl').show() }
		// Hide right arrow if position is last slide
		if(position==numberOfSlides-1){ $('#rightControl').hide() } else{ $('#rightControl').show() }
		}
	});

	//-----------------------------------------
	// Confirm Actions (delete, uninstall)
	//-----------------------------------------
	$(document).ready(function(){
	    // Confirm Delete
	    $('#form').submit(function(){
	        if ($(this).attr('action').indexOf('sil', 1) != -1) {
	            if (!confirm(($('#form').attr('mesaj')) ? $('#form').attr('mesaj') : 'Onaylıyor musunuz ?')) {
	                return false;
	            }
	        }
	    });
		/*$('#content table.list tr').hover(
			function(){
				$(this).css('background-color', '#E6FBD3');
			},
			function(){
				$(this).css('background-color', '#FFFFFF');
			}
		);*/
		$(".list tr:even").css("background-color", "#F4F4F8");
	});
	</script>
</head>
<body>
<div id="container">
	<?php if ($this->dx_auth->is_logged_in()) { ?>
		<div id="header">
			<div class="div1"><a href="<?php echo yonetim_url(); ?>"><img src="<?php echo yonetim_resim(); ?>logo/e_ticaret_logo.png"></a></div>
			<div id="doviz_bilgieri">
				<span class="mavidoviz">Doviz</span><span class="gridoviz">Bilgileri</span>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

				<img src="<?php echo yonetim_resim();?>dolar.png" align="absmiddle">
				<span class="dovizbold"><?php echo number_format(kur_oku('usd', 'satis'), 4, ',', '.'); ?></span>
				<?php
					$usd_durum = NULL;
					$kur_sorgu = $this->db->get_where('kurlar', array('kur_adi' => 'usd'), 1);
					if($kur_sorgu->num_rows() > 0)
					{
						$kur_bilgi = $kur_sorgu->row();
						if(config('site_ayar_kur') == '3')
						{
							$_satis_0 = $kur_bilgi->kur_satis_manuel;
						} else {
							$_satis_0 = $kur_bilgi->kur_satis;
						}
						if($kur_bilgi->kur_satis_eski <= $_satis_0)
						{
							$usd_durum = '<img src="'. yonetim_resim() .'up.png" align="absmiddle">';
						} else {
							$usd_durum = '<img src="'. yonetim_resim() .'down.png" align="absmiddle">';
						}
					}
				?>
				<?php echo $usd_durum; ?>

				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

				<img src="<?php echo yonetim_resim();?>euro.png" align="absmiddle">
				<span class="dovizbold"><?php echo number_format(kur_oku('eur', 'satis'), 4, ',', '.'); ?></span>
				<?php
					$eur_durum = NULL;
					$kur_sorgu_1 = $this->db->get_where('kurlar', array('kur_adi' => 'eur'), 1);
					if($kur_sorgu_1->num_rows() > 0)
					{
						$kur_bilgi_1 = $kur_sorgu_1->row();
						if(config('site_ayar_kur') == '3')
						{
							$_satis_1 = $kur_bilgi_1->kur_satis_manuel;
						} else {
							$_satis_1 = $kur_bilgi_1->kur_satis;
						}
						if($kur_bilgi_1->kur_satis_eski <= $_satis_1)
						{
							$eur_durum = '<img src="'. yonetim_resim() .'up.png" align="absmiddle">';
						} else {
							$eur_durum = '<img src="'. yonetim_resim() .'down.png" align="absmiddle">';
						}
					}
				?>
				<?php echo $eur_durum; ?>
			</div>
			<div class="div2">
				<div id="hosgeldin"><span class="hosgeldin">Hoşgeldiniz Sayın,</span> <span class="isimsoyisim"><?php echo $kullanici_bilgi->ide_adi.' '.$kullanici_bilgi->ide_soy;?></span><br/><span class="songiris">Son Giriş Zamanı:</span><span class="tarih"><?php echo $kullanici_bilgi->last_login; ?></span></div>
				<div id="gircik">
					<a onclick="window.open('<?php echo site_url(); ?>');"><img src="<?php echo yonetim_resim(); ?>preview.png"align="absmiddle"> <span class="gircik">Mağazaya Git</span></a><br/>
					<a href="<?php echo yonetim_url('giris/cikis'); ?>"><img src="<?php echo yonetim_resim(); ?>logoff.png"align="absmiddle"> <span class="gircik">Çıkış Yap</a></span>
				</div> 
				<div class="clear">	</div>			
					
		</div>
		</div>
		<div id="menu100">
			<div id="menum_container">
			<div id="daynex_menu">
		<span class="solkorner"></span>
		<ul class="sf-menu">
			<li class="ilk">
				<a href="<?php echo yonetim_url(); ?>">Kontrol Paneli</a>
				<ul>
				</ul>
			</li>
			<li>
				<a href="javascript:;">Üye Yönetimi</a>
				<ul>
					<li><a href="<?php echo yonetim_url('customer_management/customer/lists'); ?>"><span class="okum">» </span> Müşteriler</a></li>
					<li><a href="<?php echo yonetim_url('uye_yonetimi/musteri_grup'); ?>"><span class="okum">» </span> Müşteri Grubu</a></li>
					<li><a href="<?php echo yonetim_url('uye_yonetimi/yoneticiler'); ?>"><span class="okum">» </span> Yöneticiler</a></li>
					<li><a href="<?php echo yonetim_url('uye_yonetimi/yonetici_grup'); ?>"><span class="okum">» </span> Yönetici Grubu</a></li>
				</ul>
			</li>
			<li>
				<a href="javascript:;">Ürünler</a>
				<ul>
					<li><a href="<?php echo yonetim_url('urunler/product_category/lists'); ?>"><span class="okum">» </span> Kategoriler</a></li>
					<li><a href="<?php echo yonetim_url('urunler/product/lists'); ?>"><span class="okum">» </span>Ürünler</a></li>
					<li><a href="<?php echo yonetim_url('urunler/product_import'); ?>"><span class="okum">» </span>Ürün İçeri Aktar</a></li>
					<li><a href="<?php echo yonetim_url('urunler/option/lists'); ?>"><span class="okum">» </span> Seçenekler</a></li>
					<li><a href="<?php echo yonetim_url('urunler/manufacturer/lists'); ?>"><span class="okum">» </span> Markalar</a></li>
					<li><a href="<?php echo yonetim_url('urunler/review/lists'); ?>"><span class="okum">» </span> Yorumlar</a></li>
				</ul>
			</li>
			<li>
				<a href="javascript:;">Satış</a>
				<ul>
					<li><a href="<?php echo yonetim_url('satis/siparisler'); ?>"><span class="okum">» </span> Siparişler</a></li>
					<li><a href="<?php echo yonetim_url('satis/e_posta'); ?>"><span class="okum">» </span> E-Posta</a></li>
					<li><a href="<?php echo yonetim_url('satis/coupon'); ?>"><span class="okum">» </span> Kuponlar</a></li>
				</ul>
			</li>
			<li>
				<a href="javascript:;">İçerik Yönetimi</a>
				<ul>
					<li><a href="<?php echo yonetim_url('content_management/information/lists/news'); ?>"><span class="okum">» </span> Haberler</a></li>
					<li><a href="<?php echo yonetim_url('content_management/information_category/lists/news'); ?>"><span class="okum">» </span> Haberler Kategori</a></li>
					<li><a href="<?php echo yonetim_url('content_management/information/lists/announcement'); ?>"><span class="okum">» </span> Duyurular</a></li>
					<li><a href="<?php echo yonetim_url('content_management/information_category/lists/announcement'); ?>"><span class="okum">» </span> Duyurular Kategori</a></li>
					<li><a href="<?php echo yonetim_url('content_management/information/lists/information'); ?>"><span class="okum">» </span> Bilgi Sayfası</a></li>
					<li><a href="<?php echo yonetim_url('content_management/information_category/lists/information'); ?>"><span class="okum">» </span> Bilgi Sayfası Kategori</a></li>
				</ul>				
			</li>
			<li>
				<a href="javascript:;">Çağrılar</a>
				<ul class="uzat">
					<li><a href="<?php echo yonetim_url('cagri/cevaplanmis'); ?>"><span class="okum">» </span> Cevaplanmış Çağrılar</a></li>
					<li><a href="<?php echo yonetim_url('cagri/cevapbekleyen'); ?>"><span class="okum">» </span> Cevap Bekleyen Çağrılar</a></li>
					<li><a href="<?php echo yonetim_url('cagri/arsivdeki'); ?>"><span class="okum">» </span> Arşivdeki Çağrılar</a></li>
				</ul>
			</li>
			<li>
				<a href="javascript:;">Modüller</a>
				<ul>
					<li><a href="<?php echo yonetim_url('moduller/modul/listele'); ?>"><span class="okum">» </span> Modül</a></li>
					<li><a href="<?php echo yonetim_url('moduller/slider'); ?>"><span class="okum">» </span> Slider</a></li>
				</ul>
			</li>
			<li>
				<a href="javascript:;">Sistem</a>
				<ul>
					<li><a href="<?php echo yonetim_url('sistem/genel_ayarlar'); ?>"><span class="okum">» </span> Genel Ayarlar</a></li>
					<li><a href="<?php echo yonetim_url('sistem/bayiler'); ?>"><span class="okum">» </span> Şubeler</a></li>
					<li><a href="<?php echo yonetim_url('sistem/kargo'); ?>"><span class="okum">» </span> Kargo Seçenekleri</a></li>
					<li><a href="<?php echo yonetim_url('sistem/odeme_secenekleri'); ?>"><span class="okum">» </span> Ödeme Seçenekleri</a></li>
				</ul>
			</li>
		</ul>
<span class="sagkorner"></span>
	
		</div>
	</div></div>

	<div id="content">
	<?php
	$mesaj_kontrol = $this->session->flashdata('yonetim_mesaj');
	
	if ($mesaj_kontrol)
	{
		if($mesaj_kontrol['durum'] == 1) {
	?>
		<div class="success" style="margin-top: 10px;">
			<?php
			if(is_array($mesaj_kontrol['mesaj']))
			{
				foreach($mesaj_kontrol['mesaj'] as $mesaj)
				{
					echo $mesaj . ' <br />' . "\n";
				}
			} else {
				echo $mesaj;
			}
			?>
		</div>
	<?php
		} elseif($mesaj_kontrol['durum'] == 2) {
		?>
		<div class="warning" style="margin-top: 10px;">
			<?php
			if(is_array($mesaj_kontrol['mesaj']))
			{
				foreach($mesaj_kontrol['mesaj'] as $mesaj)
				{
					echo $mesaj . ' <br />' . "\n";
				}
			} else {
				echo $mesaj;
			}
			?>
		</div>
		<?php } ?>
	<?php } ?>
	<?php } ?>
<div style="clear:both;"></div>