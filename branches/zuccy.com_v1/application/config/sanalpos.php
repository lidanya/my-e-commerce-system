<?php
	$config['banka_detaylari'] = array(
		'debug'				=> '0', // 0 kapalı 1 açık
		'akbank'			=> array(
			'host'				=> array('host' => 'www.sanalakpos.com'),
			'ccpay'				=> '/servlet/cc5ApiServer',
			'3dhosting'			=> '/servlet/est3Dgate',
			'3dmodel'			=> '/servlet/est3Dgate',
			'3dpay'				=> '/servlet/est3Dgate',
			'provizyon'			=> '/servlet/cc5ApiServer'
		),
		'anadolubank'		=> array(
			'host'				=> array('host' => 'anadolusanalpos.est.com.tr'),
			'ccpay'				=> '/servlet/cc5ApiServer',
			'3dhosting'			=> '/servlet/est3Dgate',
			'3dmodel'			=> '/servlet/est3Dgate',
			'3dpay'				=> '/servlet/est3Dgate',
			'provizyon'			=> '/servlet/cc5ApiServer'
		),
		'citibank'			=> array(
			'host'				=> array('host' => 'csanalpos.est.com.tr'),
			'ccpay'				=> '/servlet/cc5ApiServer',
			'3dhosting'			=> '/servlet/est3Dgate',
			'3dmodel'			=> '/servlet/est3Dgate',
			'3dpay'				=> '/servlet/est3Dgate',
			'provizyon'			=> '/servlet/cc5ApiServer'
		),
		'denizbank'			=> array(
			'host'				=> array('host'	=> 'sanalpos.denizbank.com.tr'),
			'ccpay'				=> '/servlet/cc5ApiServer',
			'3dhosting'			=> '/servlet/est3Dgate',
			'3dmodel'			=> '/servlet/est3Dgate',
			'3dpay'				=> '/servlet/est3Dgate',
			'provizyon'			=> '/servlet/cc5ApiServer'
		),
		'finansbank'		=> array(
			'host'				=> array('host'	=> 'www.fbwebpos.com'),
			'ccpay'				=> '/servlet/cc5ApiServer',
			'3dhosting'			=> '/servlet/est3Dgate',
			'3dmodel'			=> '/servlet/est3Dgate',
			'3dpay'				=> '/servlet/est3Dgate',
			'provizyon'			=> '/servlet/cc5ApiServer'
		),
		'fortis'			=> array(
			'host'				=> array('host'	=> 'fortissanalpos.est.com.tr'),
			'ccpay'				=> '/servlet/cc5ApiServer',
			'3dhosting'			=> '/servlet/est3Dgate',
			'3dmodel'			=> '/servlet/est3Dgate',
			'3dpay'				=> '/servlet/est3Dgate',
			'provizyon'			=> '/servlet/cc5ApiServer'
		),
		'garanti'			=> array(
			'host'				=> array('host'	=> 'sanalposprov.garanti.com.tr'),
			'3dmodel'			=> '/servlet/gt3dengine',
			'3dpay'				=> '/servlet/gt3dengine',
			'3dfull'			=> '/servlet/gt3dengine',
			'3dhalf'			=> '/servlet/gt3dengine',
			'oospay'			=> '/servlet/gt3dengine',
			'3doospay'			=> '/servlet/gt3dengine',
			'3doosfull'			=> '/servlet/gt3dengine',
			'3dooshalf'			=> '/servlet/gt3dengine',
			'ccpay'				=> '/VPServlet',
			'provizyon'			=> '/VPServlet'
		),
		'halkbank'			=> array(
			'host'				=> array('host' => 'sanalpos.halkbank.com.tr'),
			'ccpay'				=> '/servlet/cc5ApiServer',
			'3dhosting'			=> '/servlet/est3Dgate',
			'3dmodel'			=> '/servlet/est3Dgate',
			'3dpay'				=> '/servlet/est3Dgate',
			'provizyon'			=> '/servlet/cc5ApiServer'
		),
		'ingbank'			=> array(
			'host'				=> array('host' => 'sanalpos.ingbank.com.tr'),
			'ccpay'				=> '/servlet/cc5ApiServer',
			'3dhosting'			=> '/servlet/est3Dgate',
			'3dmodel'			=> '/servlet/est3Dgate',
			'3dpay'				=> '/servlet/est3Dgate',
			'provizyon'			=> '/servlet/cc5ApiServer'
		),
		'isbank'			=> array(
			'host'				=> array('host' => 'spos.isbank.com.tr'),
			'ccpay'				=> '/servlet/cc5ApiServer',
			'3dhosting'			=> '/servlet/est3Dgate',
			'3dmodel'			=> '/servlet/est3Dgate',
			'3dpay'				=> '/servlet/est3Dgate',
			'provizyon'			=> '/servlet/cc5ApiServer'
		),
		'kuveytturk'		=> array(
			'host'				=> array('host' => 'netpos.kuveytturk.com.tr'),
			'ccpay'				=> '/servlet/cc5ApiServer',
			'3dhosting'			=> '/servlet/est3Dgate',
			'3dmodel'			=> '/servlet/est3Dgate',
			'3dpay'				=> '/servlet/est3Dgate',
			'provizyon'			=> '/servlet/cc5ApiServer'
		),
		'teb'				=> array(
			'host'				=> array('host' => 'sanalpos.teb.com.tr'),
			'ccpay'				=> '/servlet/cc5ApiServer',
			'3dhosting'			=> '/servlet/est3Dgate',
			'3dmodel'			=> '/servlet/est3Dgate',
			'3dpay'				=> '/servlet/est3Dgate',
			'provizyon'			=> '/servlet/cc5ApiServer'
		),
		'yapikredi'			=> array(
			'host'				=> array('host' => 'www.posnet.ykb.com', 'test' => 'setmpos.ykb.com'),
			'3ds'				=> '/3DSWebService/OOS'
		),
		'ziraat'			=> array(
			'host'				=> array('host' => 'sanalpos.ziraatbank.com.tr', 'test' => 'sanalpos-test.ziraatbank.com.tr'),
			'ccpay'				=> '/?WSDL'
		),
	);

	$config['banka_pos_gonderim_adres_basarili'] = 'odeme/adim_5/kredi_karti/{siparis_id}/{fatura_id}/{banka}/{tip}';
	//$config['banka_pos_gonderim_adres_hatali'] = 'site/deneme/{siparis_id}/{fatura_id}/{banka}/{tip}';
	$config['banka_pos_gonderim_adres_hatali'] = 'odeme/adim_5/kredi_karti/{siparis_id}/{fatura_id}/{banka}/{tip}';
	$config['banka_pos_pesin_mesaji'] = '<h2 align="center" style="margin-top: 30px;">Bilgileriniz kontrol ediliyor...</h2>';
	$config['banka_pos_3d_mesaji'] = '<h2 align="center" style="margin-top: 30px;">İlgili bankaya yönlendiriliyorsunuz...</h2>';
?>