<?php
if (config('site_ayar_ssl') == '1' && isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
	$base_url = $this->config->item('ssl_url');
} else {
	$base_url = $this->config->item('base_url');
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<base href="<?php echo $base_url; ?>" />
	<?php $ek_baslik = (!empty($baslik)) ? $baslik:config('site_ayar_baslik'); ?>
	<?php $ek_keywords = (!empty($keywords)) ? $keywords:config('site_ayar_keywords'); ?>
	<?php $ek_description = (!empty($description)) ? $description:config('site_ayar_description'); ?>
	<link rel="shortcut icon" href="<?php echo site_resim(); ?>favicon.ico" />
	<title><?php echo $ek_baslik; ?></title>
	
	<meta name="description" content="<?php echo $ek_description; ?>" />
	<meta name="keywords" content="<?php echo $ek_keywords; ?>" />
	<meta name='robots' content='noindex, nofollow' />
	<meta name="author" content="E-Ticaret Sistemim" />
</head>
<body>
<?php echo (config('site_ayar_bakim_sayfasi_detay')) ? config('site_ayar_bakim_sayfasi_detay') : '<p style="text-align: center; ">
	<img alt="" height="256" src="'. base_url() .'upload/editor/data/bakim.png" width="256" /></p>
<p style="text-align: center; ">
	<span style="font-size:16px;"><em><span style="font-family:verdana,geneva,sans-serif;">Sitemiz Yapım Aşamasındadır</span></em></span></p>';?>
</body>
</html>