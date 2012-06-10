<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<?php
/* Tanımlamalar */
if(config('site_ayar_logo'))
{
	$site_logo = site_url('upload/editor/' . config('site_ayar_logo'));
} else {
	$site_logo = site_url(site_resim() . 'logo.png');
}

$_1cirenk = $this->config->item('1_renk');
$_2cirenk = $this->config->item('2_renk');

/* Rasgele Reklamlar */
$this->db->order_by('reklam_id', 'random');
$mail_reklamlari_sorgu = $this->db->get_where('mail_reklamlar', array('reklam_flag' => '1'), 1);
if($mail_reklamlari_sorgu->num_rows() > 0)
{
	$reklam_bigli 	= $mail_reklamlari_sorgu->row();
	$degiskenler 	= array(
		'{_1cirenk}' => $_1cirenk,
		'{_2cirenk}' => $_2cirenk,
		'{_SiteUrl}' => site_url()
	);
	$reklam = strtr($reklam_bigli->reklam_icerik, $degiskenler);
	$reklam_link = $reklam_bigli->reklam_link;
} else {
	$reklam = strtr(base_url(), array('http://' => '', '/' => ''));
	$reklam_link = base_url();
}
?>
<body>

<div style="width:700px;background-color:#ffffff !important;font-family:arial !important">
	<!-- Sablon Ust -->
	<div style="width:700px;border-bottom:solid 1px #d4d4d4;padding-bottom:5px;">
		<div style="float:left;width:200px;">
			<a href="<?php echo site_url() ;?>" target="_blank">
				<img style="border:none;" width="200" src="<?php echo $site_logo; ?>" alt="<?php echo config('firma_adi'); ?>" title="<?php echo config('firma_adi'); ?>"/>
			</a>
		</div>
		<div style="width:400px;height:45px;float:right;background-color:#f1f1f1;margin-top:10px;text-align:center;padding-top:25px;font-size:18px !important;color:<?php echo $_1cirenk; ?>"><a style="text-decoration:none; color:#008fff !important;" href="<?php echo $reklam_link;?>"><?php echo $reklam; ?></a></div>
		<div style="clear:both;"></div>
	</div>
	<div style="width:700px;background-color:#ffffff !important;font-family:arial !important">
	<!-- Sablon Ust Son-->