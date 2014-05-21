<?php

if (!defined('BASEPATH'))
	exit('No direct script access');

class product_batch extends Admin_Controller
{

	function __construct() {
		parent::__construct();
		$this->load->model('yonetim/urunler/product_product_model');
		// kampanyalı ve indirimli ürünlerin otomatik olarak yayından kalkmasını sağlar.
	}

	public function index() {
		$q1 = $this->product_product_model->get_campaign_products();
		$q2 = $this->product_product_model->get_discount_products();

		$time = time();
		$result = array();

		if ($q1 != false) {
			$table = 'e_product_special';
			$column = 'product_special_id';
			foreach ($q1 as $q) {
				$bitis = $q->date_end;

				//echo $bitis." ".$time; return;
				if ($time >= $bitis) {
					//echo $time; return;
					$r = $this->product_product_model->sil($table, $column, $q->product_special_id);

					if ($r)
						$result['silinenKampanya'] = 'Silindi';
				}
			}
		}

		if ($q2 != false) {
			$table = 'e_product_discount';
			$column = 'product_discount_id';
			foreach ($q2 as $q) {
				$bitis = $q->date_end;

				if ($time >= $bitis) {
					$r = $this->product_product_model->sil($table, $column, $q->product_discount_id);

					if ($r)
						$result['silinenIndirim'] = 'Silindi';
				}
			}
		}

		echo json_encode($result);
	}

}

/* END OF product_batch **/	
	