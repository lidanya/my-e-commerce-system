<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<base href="<?php echo base_url(ssl_status()); ?>" />
	<?php $ek_baslik = (!empty($baslik)) ? $baslik : config('site_ayar_baslik'); ?>
	<?php $ek_keywords = (!empty($keywords)) ? $keywords : config('site_ayar_keywords'); ?>
	<?php $ek_description = (!empty($description)) ? $description : config('site_ayar_description'); ?>
	<title><?php echo $ek_baslik; ?></title>

	<script type="text/javascript" charset="utf-8">
		var daynex_base_url					= "<?php echo base_url(ssl_status()); ?>";
		var daynex_site_url					= "<?php echo rtrim(ssl_face_url('', ssl_status()), '/'); ?>/";
		var js_url							= daynex_base_url + "<?php echo face_js(); ?>";
		var js_url_n						= daynex_base_url + "<?php echo face_js(TRUE); ?>";
		var css_url							= daynex_base_url + "<?php echo face_css(); ?>";
		var css_url_n						= daynex_base_url + "<?php echo face_css(TRUE); ?>";
		var resim_url						= daynex_base_url + "<?php echo face_resim(); ?>";
		var resim_url_n						= daynex_base_url + "<?php echo face_resim(TRUE); ?>";

		var facebook_app_id					= "<?php echo config('site_ayar_facebook_app_id'); ?>";
		var facebook_app_url				= "<?php echo config('site_ayar_facebook_url'); ?>";
		var facebook_app_name				= "<?php echo config('site_ayar_baslik'); ?>";
		var facebook_app_description		= "<?php echo config('site_ayar_description'); ?>";
	</script>

	<link rel="stylesheet" type="text/css" href="<?php echo face_css(); ?>style.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo face_css(); ?>css/anasayfa.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo face_css(TRUE); ?>custom.css"/>
	<link rel="shortcut icon" href="<?php echo face_resim(); ?>favicon.ico" />

	<?php echo (!empty($_styles)) ? $_styles:NULL; ?>

	<script type="text/javascript" src="<?php echo face_js(); ?>jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="<?php echo face_js(); ?>facebook.js"></script>
	<script type="text/javascript" src="<?php echo face_js(); ?>jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo face_js(); ?>jquery.cookie.js"></script>
	<script type="text/javascript" src="<?php echo face_js(); ?>bookmark.js"></script>
	<script type="text/javascript" src="<?php echo face_js(); ?>core_header.js"></script>

	<script type="text/javascript" src="<?php echo face_js(); ?>ajax_post.js"></script>

	<?php echo (!empty($_scripts)) ? $_scripts:NULL; ?>
	<?php echo (config('site_google_analytics_durum') == 1) ? config('site_google_analytics_kodu'):NULL;?>

	<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo face_css(); ?>style_ie7.css" />
	<![endif]-->
	<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo face_css(); ?>style_ie6.css" />
	<![endif]-->

	<meta name="description" content="<?php echo $ek_description; ?>" />
	<meta name="keywords" content="<?php echo $ek_keywords; ?>" />
	<meta name='robots' content='index, follow' />
	<meta name="author" content="E Ticaret Sistemim E-Ticaret ve Web Çözümleri" />
</head>

<body>
<div id="fb-root"></div>
<script src="http://connect.facebook.net/tr_TR/all.js" type="text/javascript" charset="utf-8"></script>
<div id="header">
	<div class="hesol sola">
		<div id="h_logo" class="sola">
			<a href="<?php echo face_site_url('site/index'); ?>" target="_top">
				<img src="<?php echo base_url(ssl_status()); ?>upload/editor/<?php echo config('site_ayar_logo'); ?>" alt="<?php echo config('site_ayar_baslik'); ?>" title="<?php echo config('site_ayar_baslik'); ?>" />
			</a>
		</div>
	</div>
	<!--hesol SON -->
		<div class="hesag2">
			<div id="h_arama" class="saga">
				<form action="<?php echo face_site_url('urun/arama/index'); ?>" id="form_h_arama" method="get" target="_top">
					<input type="hidden" value="0" id="kategori_kriter" name="kategori">
					<div id="h_a_kat" class="sola">
						<span id="h_a_kat_txt"><?php echo lang('header_search_category_select'); ?></span>
						<div>
							<a href="javascript:;" dyn="0" target="_top"><?php echo lang('header_search_category_select'); ?></a>
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
			<div id="welcome" class="saga" style="margin-top:10px;margin-right:5px;height:60px;">
				<?php if($this->facebook_lib->user AND $this->dx_auth->is_logged_in()) { ?>
					<?php
						$fb_user	= $this->facebook_lib->user;
						$user		= user_info();
					?>
					<div class="saga">
						<div class="saga">
							Hoşgeldin, <br />
							<span style="font-weight:bold;"><?php echo $fb_user['name']; ?></span><br />
							Son Giriş Tarihi,<br />
							<?php echo standard_date('DATE_TR1', mysql_to_unix($user->last_login), 'tr'); ?>
						</div>
						<div class="saga" style="margin-top:5px;margin-right:10px;">
							<?php if (isset($fb_user['username'])): ?>
								<img src="https://graph.facebook.com/<?php echo $fb_user['username']; ?>/picture">
							<?php else: ?>
								<img src="https://graph.facebook.com/<?php echo $fb_user['id']; ?>/picture">
							<?php endif; ?>
						</div>
					</div>
				<?php } else { ?>
					<div class="saga">
						<div class="saga">
							Hoşgeldin, Ziyaretçi<br />
							<a href="<?php echo face_site_url('uye/giris/facebook'); ?>" target="_top">Üye girişi</a> yapın yada<br /> gezinmeye devam edin!<br />
							<a href="javascript:;" title="Son Ziyaret Tarihi">SZT</a> : <?php echo standard_date('DATE_TR1', time(), 'tr'); ?>
						</div>
						<div class="saga" style="margin-top:3px;margin-right:10px;">
							<a href="<?php echo face_site_url('uye/giris/facebook'); ?>" target="_top"><img src="<?php echo face_resim(TRUE); ?>no_profile.gif"></a>
						</div>
					</div>
				<?php } ?>
				<div class="saga" style="margin-right:10px;text-align:center;">
					<a href="javascript:;" title="Arkadaşını Davet Et" onclick="invite_friends(facebook_app_name, facebook_app_description);"><img src="<?php echo face_resim(TRUE); ?>friend_added.png" style="height:40px;"></a><br />
					<a href="javascript:;" title="Arkadaşını Davet Et" onclick="invite_friends(facebook_app_name, facebook_app_description);">Arkadaşını Daver Et</a>
				</div>
				<div class="saga" style="margin-right:10px;text-align:center;padding-top:20px;">
					<fb:like href="<?php echo face_site_url(''); ?>" send="true" width="300" show_faces="false" action="like" font=""></fb:like>
				</div>
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
				<li><a class="h_ilk<?php $this->menu->menu_class(2,3,'site','index',' h_aktif',3);?>" href="<?php echo face_site_url('site/index'); ?>" title="<?php echo lang('header_middle_menu_mainpage'); ?>" target="_top"><em><?php echo mb_strtoupper(lang('header_middle_menu_mainpage')); ?></em></a></li>
				<li><a class="<?php $this->menu->menu_class(2,3,'urun','yeni','h_aktif',3);?>" href="<?php echo face_site_url('urun/yeni'); ?>" title="<?php echo lang('header_middle_menu_new_products'); ?>" target="_top"><em><?php echo mb_strtoupper(lang('header_middle_menu_new_products')); ?></em></a></li>
				<li><a class="<?php $this->menu->menu_class(2,3,'urun','kampanyali','h_aktif',3);?>" href="<?php echo face_site_url('urun/kampanyali'); ?>" title="<?php echo lang('header_middle_menu_campaign_products'); ?>" target="_top"><em><?php echo mb_strtoupper(lang('header_middle_menu_campaign_products')); ?></em></a></li>
				<li><a class="h_son<?php $this->menu->menu_class(2,3,'urun','indirimli',' h_aktif',3);?>" href="<?php echo face_site_url('urun/indirimli'); ?>" title="<?php echo lang('header_middle_menu_discount_products'); ?>" target="_top"><em><?php echo mb_strtoupper(lang('header_middle_menu_discount_products')); ?></em></a></li>
			</ul>
		</div>
	</div>
	<!--menuar SON -->
	<div class="clear"></div>

</div>

	<?php $categories = $this->category_model->get_categories_by_menu(0); ?>
	<?php if ($categories) { ?>
	<div id="menu">
		<ul>
			<?php foreach ($categories as $category) { ?>
				<li>
					<a href="<?php echo face_site_url($category['href'] . '--category'); ?>" target="_top"><?php echo $category['name']; ?></a>
					<?php if ($category['children']) { ?>
						<div>
							<?php for ($i = 0; $i < count($category['children']);) { ?>
								<ul>
									<?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
									<?php for (; $i < $j; $i++) { ?>
										<?php if (isset($category['children'][$i])) { ?>
											<li>
												<a href="<?php echo face_site_url($category['children'][$i]['href'] . '--category'); ?>" target="_top"><?php echo $category['children'][$i]['name']; ?></a>
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

<!-- main -->
<div id="main">