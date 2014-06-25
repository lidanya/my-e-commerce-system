<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $this->fonksiyonlar->get_language('code'); ?>">
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<base href="<?php echo base_url(ssl_status()); ?>" />
	<?php $ek_baslik = (!empty($baslik)) ? $baslik : config('site_ayar_baslik'); ?>
	<?php $ek_keywords = (!empty($keywords)) ? $keywords : config('site_ayar_keywords'); ?>
	<?php $ek_description = (!empty($description)) ? $description : config('site_ayar_description'); ?>
	<title><?php echo $ek_baslik; ?></title>

	<script type="text/javascript" charset="utf-8">
		var eticaret_base_url		= "<?php echo base_url(ssl_status()); ?>";
		var eticaret_site_url		= "<?php echo rtrim(ssl_site_url('', ssl_status()), '/') . '/' . $this->lang->lang(); ?>/";
		var js_url				= eticaret_base_url + "<?php echo site_js(); ?>";
		var js_url_n			= eticaret_base_url + "<?php echo site_js(TRUE); ?>";
		var css_url				= eticaret_base_url + "<?php echo site_css(); ?>";
		var css_url_n			= eticaret_base_url + "<?php echo site_css(TRUE); ?>";
		var resim_url			= eticaret_base_url + "<?php echo site_resim(); ?>";
		var resim_url_n			= eticaret_base_url + "<?php echo site_resim(TRUE); ?>";
		var fpAppStatus = "<?php echo config('site_ayar_facebook_status'); ?>";
		var fbAppID = "<?php echo config('site_ayar_facebook_app_id'); ?>";
		var fbAppSecret = "<?php echo config('site_ayar_facebook_secret'); ?>";
	
	</script>

	<link rel="stylesheet" type="text/css" href="<?php echo site_css(); ?>style.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_css(); ?>anasayfa.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_css(TRUE); ?>jquery.countdown.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_css(TRUE); ?>custom.css"/>
	<!--<link rel="shortcut icon" href="<?php echo site_resim(); ?>favicon.ico" />-->
    <link rel="shortcut icon" href="<?php echo base_url(ssl_status()); ?>upload/editor/<?php echo config('site_ayar_favicon'); ?>" />

	<?php echo (!empty($_styles)) ? $_styles:NULL; ?>

	<script type="text/javascript" src="<?php echo site_js(); ?>jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="<?php echo site_js(); ?>easySlider1.7.5.js"></script>
	<script type="text/javascript" src="<?php echo site_js(); ?>jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo site_js(); ?>jquery.cookie.js"></script>
	<script type="text/javascript" src="<?php echo site_js(); ?>bookmark.js"></script>
	<script type="text/javascript" src="<?php echo site_js(); ?>core_header.js"></script>

	<script type="text/javascript" src="<?php echo site_js(); ?>menu_tip02.js"></script>
	<script type="text/javascript" src="<?php echo site_js(); ?>menu_tip03.js"></script>
	<script type="text/javascript" src="<?php echo site_js(); ?>menu_tip04.js"></script>

	<script type="text/javascript" src="<?php echo site_js(); ?>ajax_post.js"></script>
	
	<script type="text/javascript" src="<?php echo site_js(); ?>jquery.countdown.js"></script>
	<script type="text/javascript" src="<?php echo site_js(); ?>jquery.countdown-tr.js"></script>
	
	<?php echo (!empty($_scripts)) ? $_scripts:NULL; ?>
	<?php echo (config('site_google_analytics_durum') == 1) ? config('site_google_analytics_kodu'):NULL;?>
    
    <script type="text/javascript">
    //SKOCH product batch processing
    // kampanyalı veya indirimli ürünleri tarihi geçince otomatik kaldırır.
	function batch(){
	var url = "<?php echo site_url('yonetim/urunler/product_batch'); ?>";
	var data = 'xaRwdQ42'; 
	
	$.post(url,{data:data},function(response){});
	}
	
	setInterval("batch()",30000);
	
	
    </script>
	<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo site_css(); ?>style_ie7.css" />
	<![endif]-->
	<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo site_css(); ?>style_ie6.css" />
	<![endif]-->
   

	<meta name="description" content="<?php echo $ek_description; ?>" />
	<meta name="keywords" content="<?php echo $ek_keywords; ?>" />
	<meta name='robots' content='index, follow' />
	<meta name="author" content="E-Ticaret Sistemim" />
</head>

<body>
<div id="head-ust">
    <div id="h_ust_menu"s>
        <a href="<?php echo site_url('site/index'); ?>" title="anasayfa">ANASAYFA</a>

        <a href="<?php echo site_url('urun/yeni'); ?>" title="yeni gelenler">
            YENİ GELENLER
        </a>

        <a href="<?php echo site_url('urun/kampanyali'); ?>" title="kampanyali ürünler">
            KAMPANYALI ÜRÜNLER
        </a>

        <a href="<?php echo site_url('urun/indirimli'); ?>" title="İndirimli">
            İNDİRİMDEKİLER
        </a>
        <img style="float:right; margin-top: 8px;" src="<?php echo site_resim()."vip.png"; ?>"/>
    </div>
    <div class="clear"></div>
    <!--h_ust_menu SON -->
</div>
<div id="header">
	<div class="hesol sola">
		<div id="h_logo" class="sola">
			<a href="<?php echo site_url('site/index'); ?>">
				<img src="<?php echo base_url(ssl_status()); ?>upload/editor/<?php echo config('site_ayar_logo'); ?>" alt="<?php echo config('site_ayar_baslik'); ?>" title="<?php echo config('site_ayar_baslik'); ?>" />
			</a>
		</div>
	</div>
	<!--hesol SON -->
	<div class="hesag saga">
		<div class="hesag1">
			<div class="dil_alan saga">
				<?php
					$language_code = get_language('code');
					$languages = $this->fonksiyonlar->get_languages();
				?>
				<div class="saga" style="width:121px;margin-left:20px;margin-top:8px;">
					<?php if ($languages) { ?>
						<?php if(count($languages) > 1) { ?>
						<div class="switcher">
							<?php foreach ($languages as $language) { ?>
								<?php if ($language['code'] == $language_code) { ?>
									<div class="selected">
										<a href="javascript:;" title="<?php echo $language['name']; ?>">
											<img src="<?php echo site_resim(TRUE) . 'flags/' . $language['image']; ?>" alt="<?php echo $language['name']; ?>" />
											&nbsp;&nbsp;<?php echo $language['name']; ?>
										</a>
									</div>
								<?php } ?>
							<?php } ?>
							<div class="option">
							<?php foreach ($languages as $language) { ?>
								<?php if ($language['code'] == $language_code) { ?>
								<a href="javascript:;" title="<?php echo $language['name']; ?>">
								<?php } else { ?>
								<a href="<?php echo site_url($this->lang->switch_uri($language['code'])); ?>" title="<?php echo $language['name']; ?>">
								<?php } ?>
									<img src="<?php echo site_resim(TRUE) . 'flags/' . $language['image']; ?>" alt="<?php echo $language['name']; ?>" />
									&nbsp;&nbsp;<?php echo $language['name']; ?>
								</a>
							<?php } ?>
							</div>
						</div>
						<div class="clear"></div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
			<!--dil_alan SON -->

		</div>
		<!--hesag1 SON -->
		<div class="hesag2" style="position:relative;z-index:1000;">
			<?php if($this->dx_auth->is_logged_in()) { ?>
			<div id="h_uye_panel" class="sola">
				<span><?php echo lang('header_user_account'); ?></span>
				<ul>
					<li><a href="<?php echo ssl_url('uye/bilgi'); ?>" rel="nofollow" title="<?php echo lang('header_user_information'); ?>"><?php echo lang('header_user_information'); ?></a></li>
					<li><a href="<?php echo ssl_url('uye/cagri'); ?>" rel="nofollow" title="<?php echo lang('header_user_ticket'); ?>"><?php echo lang('header_user_ticket'); ?></a></li>
					<li><a href="<?php echo ssl_url('uye/siparisler'); ?>" rel="nofollow" title="<?php echo lang('header_user_order'); ?>"><?php echo lang('header_user_order'); ?></a></li>
					<li><a href="<?php echo ssl_url('uye/urun_takip'); ?>" rel="nofollow" title="<?php echo lang('header_user_product'); ?>"><?php echo lang('header_user_product'); ?></a></li>
					<li><a href="<?php echo ssl_url('uye/fatura'); ?>" rel="nofollow" title="<?php echo lang('header_user_billing'); ?>"><?php echo lang('header_user_billing'); ?></a></li>
					<li><a href="<?php echo ssl_url('uye/cikis'); ?>" rel="nofollow" class="h_cikis" title="<?php echo lang('header_user_logout'); ?>"><?php echo lang('header_user_logout'); ?></a></li>
				</ul>
			</div>	
			<?php if (config('facebook_app_status') AND config('site_ayar_facebook_url')) { ?>
			</div>
			<?php } ?>
			<?php } else { ?>
			<div id="h_uye_menu" class="sola">
				<ul>
					<li>
						<a href="<?php echo ssl_url('uye/kayit'); ?>" rel="nofollow" title="<?php echo lang('header_user_reg'); ?>">
							<?php echo lang('header_user_reg'); ?>
						</a>
					</li>
					<li>
						<a class="feys" href="<?php echo ssl_url('uye/giris'); ?>" rel="nofollow" title="<?php echo lang('header_user_login'); ?>">
							<?php echo lang('header_user_login'); ?>
							<?php if (config('site_ayar_facebook_status')) { ?>
								<small></small>
							<?php } ?>
						</a>
					</li>
				</ul>
			</div>
            <?php if (config('facebook_app_status') AND config('site_ayar_facebook_url')) { ?>
                    <div class="sola" style="padding-left:10px;padding-top:13px;">
			            <a href="https://apps.facebook.com/eticaret/" target="_blank" title="<?php echo lang('header_top_facebook_application'); ?>">
					
						<img src="<?php echo site_resim(TRUE); ?>face.png" style="height:32px;" />
					</a></div>
			
			<?php } ?>
			<?php } ?>
			
			<div id="h_arama" class="saga">
				<form action="<?php echo site_url('urun/arama/index'); ?>" id="form_h_arama" method="get">
					<input type="hidden" value="0" id="kategori_kriter" name="kategori" />
					<div id="h_a_kat" class="sola">
						<span id="h_a_kat_txt"><?php echo lang('header_search_category_select'); ?></span>
						<div>
							<a href="javascript:;" dyn="0"><?php echo lang('header_search_category_select'); ?></a>
							<?php
								$urun_kategori = urun_ana_kategori();
								if($urun_kategori) {
									foreach($urun_kategori as $kategori) {
							?>
								<a href="javascript:;" dyn="<?php echo $kategori['urun_kat_id']; ?>"><?php echo $kategori['urun_kat_adi'];?></a>
							<?php
									}
								}
							?>
						</div>
					</div>
					<div id="h_a_text" style="padding-top:0;" class="sola">
						<input type="text" name="aranan" value="<?php echo _get('aranan', lang('header_search_input')); ?>" onclick="if(this.value==this.defaultValue){this.value=''}" onblur="if(this.value==''){this.value=this.defaultValue}" />
					</div>
					<a id="h_a_buton" href="javascript:;" onclick="if($('#arama_box').val() == '<?php echo lang('header_search_input'); ?>'){return false;} else {$('#form_h_arama').submit();}"><?php echo lang('header_search_button'); ?></a>
					<div class="clear"></div>
				</form>	
			</div>
			<!--arama SON -->
		</div>
		<!--hesag2 SON -->
	</div>
	<!--hesag SON -->
	<div class="clear"></div>
	<div id="menubar">
		<div id="h_alt_menu" class="sola">
			<ul class="sola">
				<li><a class="h_ilk<?php $this->menu->menu_class(2,3,'site','index',' h_aktif',3);?>" href="<?php echo site_url('mutfak--category'); ?>" title="Mutfak"><em>MUTFAK</em></a></li>
				<li><a class="h_ilk<?php $this->menu->menu_class(2,3,'site','index',' h_aktif',3);?>" href="<?php echo site_url('banyo--category'); ?>" title="Banyo"><em>BANYO</em></a></li>
				<li><a class="h_ilk<?php $this->menu->menu_class(2,3,'site','index',' h_aktif',3);?>" href="<?php echo site_url('salon--category'); ?>" title="Salon"><em>SALON</em></a></li>
				<li><a class="h_ilk<?php $this->menu->menu_class(2,3,'site','index',' h_aktif',3);?>" href="<?php echo site_url('yatak-odasi--category'); ?>" title="Yatak Odası"><em>YATAK ODASI</em></a></li>
				<li><a class="h_ilk<?php $this->menu->menu_class(2,3,'site','index',' h_aktif',3);?>" href="<?php echo site_url('dekorasyon--category'); ?>" title="Dekorasyon"><em>DEKORASYON</em></a></li>
				<li><a class="h_ilk<?php $this->menu->menu_class(2,3,'site','index',' h_aktif',3);?>" href="<?php echo site_url('bahce--category'); ?>" title="Bahçe"><em>BAHÇE</em></a></li>
		    </ul>
		</div>
		<!--h_alt_menu SON -->
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				$("#h_sepet a").click(function(){
					if($('#ust_hizlisepet').is(':visible')) {
						$("#ust_hizlisepet").stop(true, true).fadeOut("normal");
					} else {
						$("#ust_hizlisepet").stop(true, true).fadeIn("normal");
					}
				});
				$("#sepetkapa").click(function(){
					$("#ust_hizlisepet").stop(true, true).fadeOut("normal");
				});
			});
			
			function sepet_kapa()
			{
			   $("div.hizlisepet").slideUp('slow');
		    }
		</script>

		<div id="h_sepet" class="saga">
            <span style="position: absolute; right: 84px; font-weight: bold; color: #373737;">Sepetim</span>
			<p>
				<a href="javascript:;" rel="nofollow">
					<?php $cart_item = ($this->cart->total_items()) ? $this->cart->total_items() : 0; ?>
					<span id="cart_total"><?php echo strtr(lang('header_cart_items'), array('{product_count}' => $cart_item)); ?></span>
				</a>
			</p>
			<div class="hizlisepet" id="ust_hizlisepet">
				<div class="sepust"></div>
				<div class="seport">
					<?php $cart_item = ($this->cart->total_items()) ? $this->cart->total_items() : 0; ?>
					<big id="ic_total"><?php echo strtr(lang('header_large_cart_items'), array('{product_count}' => $cart_item)); ?></big>
					<small><strong><?php echo lang('header_large_cart_product_title'); ?></strong><em><?php echo lang('header_large_cart_price_title'); ?></em></small>
					<?php if ($this->cart->contents()) { ?>
					<ul>
						<?php foreach ($this->cart->contents() as $items) { ?>
							<?php if ($items['durum']) { ?>
								<li>
									<dl>
										<dt>
											<font title="<?php echo $items['name']; ?>"><?php echo character_limiter($items['name'], 30); ?></font> - 
											<i class="siterenk">
												(
													<?php echo $items['qty']; ?>
													<?php
														$tanim_bilgi = $this->yonetim_model->tanimlar_bilgi('stok_birim', $items['tip']);
														if($tanim_bilgi->num_rows() > 0)
														{
															$tanim_bilgi_b = $tanim_bilgi->row();
															echo '<font style="cursor:pointer;" title="'. $tanim_bilgi_b->tanimlar_adi .'">' . $tanim_bilgi_b->tanimlar_kod . '</font>';
														} else {
															echo '<font style="cursor:pointer;" title="Ürün Birimi Bulunamadı">bln</font>';
														}
													?>
												)
											</i>
										</dt>
										<dd><?php echo $this->cart->format_number($items['price']); ?> TL</dd>
									</dl>
								</li>
							<?php } ?>
						<?php } ?>
					</ul>
					<u class="saga siterenk">
						<em><?php echo lang('header_large_cart_total_price'); ?>:</em>
						<cite><span id="ttl"><?php echo $this->cart->format_number($this->cart->total()); ?></span> TL</cite>
					</u>
					<?php } ?>
					<div class="clear"></div>
					<div class="saltlinkler">
						<a title="<?php echo lang('header_large_cart_close'); ?>" href="javascript:;" id="sepetkapa" onclick="sepet_kapa();" style="position:relative;bottom:-10px;" class="sitelink sola"><b>x</b> <?php echo lang('header_large_cart_close'); ?></a>
						<a href="javascript:;" onclick="redirect('<?php echo ssl_url('sepet/goster'); ?>');" class="butonum saga">
							<span class="butsol"></span>
							<span class="butor"><?php echo lang('header_large_cart_go_cart'); ?></span>
							<span class="butsag"></span>
						</a>
					</div>
					<!--saltlinkler SON -->
				</div>
				<!--seport SON -->
				<div class="sepalt"></div>
			</div>
			<!--hizlisepet SON -->
		</div>
	</div>
	<!--menuar SON -->
	<div class="clear"></div>

	<?php $categories = $this->category_model->get_categories_by_menu(0); ?>
	<?php if ($categories) { ?>
	<div id="menu">
		<ul>
			<?php foreach ($categories as $category) { ?>
				<li>
					<a href="<?php echo site_url($category['href'] . '--category'); ?>" target="_top"><?php echo $category['name']; ?></a>
					<?php if ($category['children']) { ?>
						<div>
							<?php for ($i = 0; $i < count($category['children']);) { ?>
								<ul>
									<?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
									<?php for (; $i < $j; $i++) { ?>
										<?php if (isset($category['children'][$i])) { ?>
											<li>
												<a href="<?php echo site_url($category['children'][$i]['href'] . '--category'); ?>" target="_top"><?php echo $category['children'][$i]['name']; ?></a>
											</li>
										<?php } ?>
									<?php } ?>
								</ul>
							<?php } ?>
						</div>
					<?php } ?>
				</li>
			<?php } ?>
		</ul>
	</div>
	<?php } ?>

</div>
<div class="clear"></div>

<div class="head-bottom-line"></div>

<!-- main -->
<div id="main">

<?php
	if(
		($this->uri->segment(2) == 'site' AND ($this->uri->segment(3) == '' OR $this->uri->segment(3) == 'index')) OR
		($this->uri->segment(2) == '') OR
		($this->uri->segment(1) == '')
	) {
		$this->moduller->modul_cagir('ust');
	}
?>