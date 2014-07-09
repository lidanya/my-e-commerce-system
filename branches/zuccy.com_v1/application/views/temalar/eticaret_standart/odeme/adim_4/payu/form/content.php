<?php $this->load->view(tema() . 'odeme/header'); ?>

<?php //echo debug($siparis_detay); ?>

<?php
	$user_id							= $this->dx_auth->get_user_id();

	$fatura_bilgileri_sorgu				= $this->db->get_where('usr_inv_inf', array('inv_id' => $fatura_id, 'user_id' => $user_id), 1);
	$fatura_bilgi						= $fatura_bilgileri_sorgu->row();

    $teslimat_bilgi = new stdClass();
	$teslimat_bilgi->ad_soyad			= '';
	$teslimat_bilgi->adres				= '';
	$teslimat_bilgi->ulke				= '';
	$teslimat_bilgi->sehir 				= '';
	$teslimat_bilgi->ilce 				= '';
	$teslimat_bilgi->posta_kodu			= '';
	$teslimat_bilgi->telefon			= '';

	if(isset($siparis_detay['teslimat'])) {
		$teslimat_bilgi->ad_soyad		= $siparis_detay['teslimat']['ad_soyad'];
		$teslimat_bilgi->adres			= $siparis_detay['teslimat']['adres'];
		$teslimat_bilgi->ulke			= $siparis_detay['teslimat']['ulke'];
		$teslimat_bilgi->sehir			= $siparis_detay['teslimat']['sehir'];
		$teslimat_bilgi->ilce			= $siparis_detay['teslimat']['ilce'];
		$teslimat_bilgi->posta_kodu		= $siparis_detay['teslimat']['posta_kodu'];
		$teslimat_bilgi->telefon		= $siparis_detay['teslimat']['telefon'];
    }
    var_dump($siparis_detay);
    var_dump($siparis_bilgi);
var_dump(date("Y-m-d",$siparis_bilgi->kayit_tar));
	$this->db->select_sum('stok_tfiyat');
	$toplam_tfiyat_sorgu				= $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id));
	$toplam_tfiyat_bilgi				= $toplam_tfiyat_sorgu->row();
	$stok_toplam_fiyat					= $toplam_tfiyat_bilgi->stok_tfiyat;

	if(isset($siparis_detay['kargo_ucret']) AND $siparis_detay['kargo_ucret']) {
		$kargo_ucret					= ($siparis_detay['kargo_ucret']) ? $siparis_detay['kargo_ucret'] : 0;
	} else {
		$kargo_ucret					= 0;
	}

	$toplam_kdv_fiyati					= 0;
	$this->db->select('stok_kdv_orani, stok_tfiyat');
	$siparis_detay_sorgu				= $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id));
	foreach($siparis_detay_sorgu->result() as $siparis_detay) {
		$toplam_kdv_fiyati 				+= kdv_hesapla($siparis_detay->stok_tfiyat, $siparis_detay->stok_kdv_orani, true);
	}

	$durum								= FALSE;

	$siparis_id 						= $siparis_bilgi->siparis_id;

	$toplam_ucret = 0;
	$toplam_ucret += $stok_toplam_fiyat;
	if(config('site_ayar_kdv_goster') == '1') {
		$toplam_ucret += $toplam_kdv_fiyati;
	}
	if($kargo_ucret > 0) {
		$toplam_ucret += $kargo_ucret;
	}
	if($kupon_ucret > 0) {
		$toplam_ucret -= $kupon_ucret;
	}
	if($toplam_ucret <= 0) {
		$toplam_ucret = 0.01;
	}

?>

    <form method="post" action="https://secure.payu.com.tr/order/lu.php">
        <input type="hidden" name="MERCHANT" value="PAYUDEMO">
        <input type="hidden" name="ORDER_REF" value="112457">
        <input type="hidden" name="ORDER_DATE" value="2012-05-01 15:51:35">
        <input type="hidden" name="ORDER_PNAME[]" value="MacBook Air 13 inç">
        <input type="hidden" name="ORDER_PNAME[]" value="iPhone 4S">
        <input type="hidden" name="ORDER_PCODE[]" value="MBA13">
        <input type="hidden" name="ORDER_PCODE[]" value="IP4S">
        <input type="hidden" name="ORDER_PINFO[]" value="Uzatılmış Garanti - 5 Yıl">
        <input type="hidden" name="ORDER_PINFO[]" value="">
        <input type="hidden" name="ORDER_PRICE[]" value="1750">
        <input type="hidden" name="ORDER_PRICE[]" value="400">
        <input type="hidden" name="ORDER_PRICE_TYPE[]" value="GROSS">
        <input type="hidden" name="ORDER_PRICE_TYPE[]" value="NET">
        <input type="hidden" name="ORDER_QTY[]" value="1">
        <input type="hidden" name="ORDER_QTY[]" value="2">
        <input type="hidden" name="ORDER_VAT[]" value="24">
        <input type="hidden" name="ORDER_VAT[]" value="24">
        <input type="hidden" name="ORDER_SHIPPING" value="50">
        <input type="hidden" name="PRICES_CURRENCY" value="EUR">
        <input type="hidden" name="BILL_FNAME" value="John">
        <input type="hidden" name="BILL_LNAME" value="Doe">
        <input type="hidden" name="BILL_EMAIL" value="john@johndoe.com">
        <input type="hidden" name="DISCOUNT" value="10">
        <input type="hidden" name="DESTINATION_CITY" value="İstanbul">
        <input type="hidden" name="DESTINATION_STATE" value="İstanbul">
        <input type="hidden" name="DESTINATION_COUNTRY" value="TR">
        <input type="hidden" name="PAY_METHOD" value="CCVISAMC">
        <input type="hidden" name="INSTALLMENT_OPTIONS" value="2,3,7,10,12">
        <input type="hidden" name="TESTORDER" value="1">
        <input type="hidden" name="LANGUAGE" value="RO">
        <input type="hidden" name="ORDER_HASH" value="83829ff075d5ba1f50c80df89b648ec4">
        <input type="submit" name="submit" value="Gönder!">
    </form>


<?php $this->load->view(tema() . 'odeme/footer'); ?>