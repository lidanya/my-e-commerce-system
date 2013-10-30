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
	</script>

	<link rel="stylesheet" type="text/css" href="<?php echo site_css(); ?>style.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_css(); ?>minikutu.css"/>
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
	<script type="text/javascript" src="<?php echo site_js(); ?>jquery.lazyload.min.js"></script>
	
	<?php echo (!empty($_scripts)) ? $_scripts:NULL; ?>
	<?php echo (config('site_google_analytics_durum') == 1) ? config('site_google_analytics_kodu'):NULL;?>
    
    <script type="text/javascript">
		$(function() {
			$(".urun_liste_resim img").lazyload({effect : "fadeIn"});
		});
    //SKOCH product batch processing
    // kampanyalı veya indirimli ürünleri tarihi geçince otomatik kaldırır.
//	function batch(){
//	var url = "<?php //echo site_url('yonetim/urunler/product_batch'); ?>";
//	var data = 'xaRwdQ42'; 
//	
//	$.post(url,{data:data},function(response){});
//	}
//	
//	setInterval("batch()",30000);
	
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
<div id="Full" class="topmenubg">
	<div id="Top">
		<a href="<?php echo site_url('site/index'); ?>" title="<?php echo lang('header_top_menu_mainpage'); ?>"><span class="buton-anasayfa"></span></a>
		<a href="/tr/hakkimizda--information" title="Hakkımızda"><span class="buton-hakkimizda"></span></a>
		<a href="<?php echo site_url('site/musteri_hizmetleri'); ?>" title="<?php echo lang('header_top_menu_customer_services'); ?>"><span class="buton-sss"></span></a>
		<a href="<?php echo site_url('site/iletisim'); ?>" title="<?php echo lang('header_top_menu_contact_us'); ?>"><span class="buton-iletisim"></span></a>
		
		<?php if($this->dx_auth->is_logged_in()) { ?>
			<div id="h_uye_panel" class="sola">
				<div class="panel-acik">
				<ul>
					<li><a href="<?php echo ssl_url('uye/bilgi'); ?>" rel="nofollow" title="<?php echo lang('header_user_information'); ?>"><?php echo lang('header_user_information'); ?></a></li>
					<li><a href="<?php echo ssl_url('uye/cagri'); ?>" rel="nofollow" title="<?php echo lang('header_user_ticket'); ?>"><?php echo lang('header_user_ticket'); ?></a></li>
					<li><a href="<?php echo ssl_url('uye/siparisler'); ?>" rel="nofollow" title="<?php echo lang('header_user_order'); ?>"><?php echo lang('header_user_order'); ?></a></li>
					<li><a href="<?php echo ssl_url('uye/urun_takip'); ?>" rel="nofollow" title="<?php echo lang('header_user_product'); ?>"><?php echo lang('header_user_product'); ?></a></li>
					<li><a href="<?php echo ssl_url('uye/fatura'); ?>" rel="nofollow" title="<?php echo lang('header_user_billing'); ?>"><?php echo lang('header_user_billing'); ?></a></li>
					<li><a href="<?php echo ssl_url('uye/cikis'); ?>" rel="nofollow" class="h_cikis" title="<?php echo lang('header_user_logout'); ?>"><?php echo lang('header_user_logout'); ?></a></li>
				</ul>
				</div>
			</div>
			
			<?php } else { ?>
			<div id="h_uye_menu" class="sola">
				<ul>
					<li>
						<a class="uye-kayit" href="<?php echo ssl_url('uye/kayit'); ?>" rel="nofollow" title="<?php echo lang('header_user_reg'); ?>">
							<?php echo lang('header_user_reg'); ?>
						</a>
					</li>
					<li>
						<a class="feys" href="<?php echo ssl_url('uye/giris'); ?>" rel="nofollow" title="<?php echo lang('header_user_login'); ?>">
							<?php echo lang('header_user_login'); ?>
						</a>
					</li>
				</ul>
			</div>
			<?php } ?>
	</div>
	
	<div class="Top_alt">
		<div id="Logo"><a href="/"><img src="<?php echo site_resim(); ?>logo.png" width="288" height="78" /></a></div>
		 <div id="Arama">
			<form action="<?php echo site_url('urun/arama/index'); ?>" id="form_h_arama" method="get">
				<div id="h_a_text" style="padding-top:0;" class="sola">
					<input type="text" name="aranan" value="<?php echo _get('aranan', lang('header_search_input')); ?>" onclick="if(this.value==this.defaultValue){this.value=''}" onblur="if(this.value==''){this.value=this.defaultValue}" />
				</div>
				<a id="h_a_buton" href="javascript:;" onclick="if($('#arama_box').val() == '<?php echo lang('header_search_input'); ?>'){return false;} else {$('#form_h_arama').submit();}"><?php echo lang('header_search_button'); ?></a>
				<div class="clear"></div>
			</form>
		</div>
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
			<p>
				<a href="javascript:;" rel="nofollow">
					<?php $cart_item = ($this->cart->total_items()) ? $this->cart->total_items() : 0; ?>
					<span id="cart_total"><?php echo strtr(lang('header_cart_items'), array('{product_count}' => $cart_item)); ?></span>
				</a>
			</p>

		</div>
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
							<span class="butor"><?php echo lang('header_large_cart_go_cart'); ?></span>
						</a>
					</div>
					<!--saltlinkler SON -->
				</div>
				<!--seport SON -->
				<div class="sepalt"></div>
			</div>

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

<div class="clear"></div>
</div>
	
