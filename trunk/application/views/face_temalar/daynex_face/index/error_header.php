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
		var daynex_base_url		= "<?php echo base_url(ssl_status()); ?>";
		var daynex_site_url		= "<?php echo rtrim(ssl_face_url('', ssl_status()), '/') . '/' . $this->lang->lang(); ?>/";
		var js_url				= daynex_base_url + "<?php echo face_js(); ?>";
		var js_url_n			= daynex_base_url + "<?php echo face_js(TRUE); ?>";
		var css_url				= daynex_base_url + "<?php echo face_css(); ?>";
		var css_url_n			= daynex_base_url + "<?php echo face_css(TRUE); ?>";
		var resim_url			= daynex_base_url + "<?php echo face_resim(); ?>";
		var resim_url_n			= daynex_base_url + "<?php echo face_resim(TRUE); ?>";

		var facebook_app_id		= "<?php echo config('site_ayar_facebook_app_id'); ?>";
		var facebook_app_url	= "<?php echo config('site_ayar_facebook_url'); ?>";
	</script>

	<link rel="stylesheet" type="text/css" href="<?php echo face_css(); ?>style.css"/>
	<link rel="shortcut icon" href="<?php echo face_resim(); ?>favicon.ico" />

	<?php echo (!empty($_styles)) ? $_styles:NULL; ?>

	<script type="text/javascript" src="<?php echo face_js(); ?>jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="<?php echo face_js(); ?>facebook.js"></script>

	<?php echo (!empty($_scripts)) ? $_scripts:NULL; ?>
	<?php echo (config('site_google_analytics_durum') == 1) ? config('site_google_analytics_kodu'):NULL;?>
	<meta name="description" content="<?php echo $ek_description; ?>" />
	<meta name="keywords" content="<?php echo $ek_keywords; ?>" />
	<meta name='robots' content='index, follow' />
	<meta name="author" content="E Ticaret Sistemim E-Ticaret ve Web Çözümleri" />
</head>

<body>
	<div id="fb-root"></div>
	<script src="http://connect.facebook.net/tr_TR/all.js" type="text/javascript" charset="utf-8"></script>
	<div id="header" style="margin-top:150px;">
		<div class="hesol" style="margin:auto;">
			<div id="h_logo">
				<a href="<?php echo face_site_url(); ?>">
					<img src="<?php echo face_resim(); ?>daynex_e_ticaret_logo.png" alt="<?php echo config('site_ayar_baslik'); ?>" title="<?php echo config('site_ayar_baslik'); ?>" />
				</a>
			</div>
		</div>
		<!--hesol SON -->
	</div>