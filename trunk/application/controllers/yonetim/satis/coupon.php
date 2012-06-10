<?php if (! defined('BASEPATH')) exit('No direct script access');

/**
 * --------------------------------------------------------------------------
 * KUPON KODLARI
 * --------------------------------------------------------------------------
 *
 * @package coupon codes
 **/
class Coupon extends Admin_Controller 
{
	
	private $izin_linki = 'satis/coupon';
	
	// indirim tipleri
	public	$type = array(1 => '% (Yüzde)', 2 => 'TL (Sabit Miktar)');
	
	// kupon durumarı
	public $status = array('Henüz Kullanılmadı', 'Kullanıldı', 'Süresi Doldu', 'all' => 'Hepsi');
	
	function __construct() {
		
		parent::__construct();
		
		// ilgili dosyalar yüklensin
		$this->load->model('yonetim/coupon_model');
	}
	
	function index() {
		
		sayfa_kontrol($this->izin_linki, uri_string());
		
		// tüm kuponlar
		$result = $this->coupon_model->gets($_GET);	
		
		$data = array(
			'result' => $result
			);
		
		$this->load->view('yonetim/satis/coupon/index_view', $data);	
	}
	
	public function add() {
		
		sayfa_kontrol($this->izin_linki, uri_string());
		
		$this->load->library('form_validation', null, 'fv');
		$this->fv->set_rules('date_start', 'Başlangıç tarihi', 'trim|required|callback__check_date');
		$this->fv->set_rules('date_end', 'Bitiş tarihi', 'trim|required');
		$this->fv->set_rules('type', 'İndirim Tipi', 'trim|required|numeric');
		$this->fv->set_rules('value', 'İndirim Değeri', 'trim|required|numeric|callback__check_value');
		$this->fv->set_rules('generating_type', 'Kod Oluşturma', 'trim|required');
	
		
		if($this->input->post('generating_type') == 'manuel'):
			$this->fv->set_rules('codes', 'Kupon Kodları', 'trim|required');
		else:
			$this->fv->set_rules('qty', 'Adet', 'trim|required|numeric');
		endif;
			
		
		if ($this->fv->run() === false) {
			
			$data = array();
			
			$this->load->view('yonetim/satis/coupon/add_view', $data);
			
		} else {
			
			if($this->input->post('generating_type') == 'manuel') {
				$codes = $this->parsh_code($_POST['codes']);
			} else {
				$codes = $this->generate_code($_POST['qty']);
			}
			
			$codes = array_unique($codes);
			$codes = $this->remove_exists($codes);
			
			$data = array();
			
			if(count($codes) > 0) {
				foreach($codes as $code) {
					$data[] = array(
						'code' => $code,
						'type' => $_POST['type'],
						'value' => $_POST['value'],
						'date_start' => $_POST['date_start'],
						'date_end' => $_POST['date_end']
						);
				}	
			}
			
			$num = $this->coupon_model->add($data);
			
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array("{$num} adet kupon başarıyla eklendi");
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/satis/coupon/index');
		}
		
	}
	
	
	public function delete() {
		
		$num = $this->coupon_model->delete($_POST['coupon_id']);
		
		$yonetim_mesaj				= array();
		$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
		$yonetim_mesaj['mesaj']		= array("{$num} adet kupon başarıyla silindi");
		$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
		redirect('yonetim/satis/coupon/index');
	}
	
	// HELPERS
	
	/**
	 * istenilen miktarda kupon kodu oluşturur.
	 * 
	 * @param int kupon miktarı
	 * @return array
	 **/
	private function generate_code($qty = 1) {
		
		$this->load->helper('string');
		
		// adet 1den küçükse 1 yap
		$qty = ((int)$qty < 1)? 1 : $qty;
		
		$codes = array();
		
		for ($i=0; $i < $qty; $i++) { 
			$codes[] = random_string('alnum', 12);
		}
		
		return $codes;
	}
	
	/**
	 * Formdan gelen kodları filtreliyip parsh eder.
	 * 
	 * @param string parsh edilecek kodlar
	 * @return array
	 **/
	private function parsh_code($data) {
		
		$data = array_map('trim', explode(PHP_EOL, $data));
		
		$codes = array();
		foreach($data as $code) {
			if(strlen($code) < 8)
				continue;
				
			$codes[] = $code;
		}
		
		return $codes;	
	}
	
	/**
	 * Daha önceden db 'ye eklenmiş kodları listeden atar.
	 * 
	 * @param array db de kontrol edilecek kodlar
	 * @return array
	 **/
	public function remove_exists($data) {	
		
		$data = array_combine($data, $data);
		
		$q = $this->db
			->select('code')
			->where_in('code', $data)
			->get('coupon');
			
		if(!$q->num_rows())
			return $data;
		
		$db_exists = array();
		foreach($q->result() as $row) {
			unset($data[$row->code]);
		}
			
		return $data;	
	}
	
	// CALLLBACKS
	
	/**
	 * Kupon başlangıç ve bitiş tarihinin doğruluğunu teyid eder
	 * 
	 * @return void
	 **/
	public function _check_date() {
		
		$date_start = strtotime($_POST['date_start']);
		$date_end = strtotime($_POST['date_end']);
		
		if($date_end <= $date_start) {
			$this->fv->set_message('_check_date', 'Bitiş tarihi başlangıç tarihine eşit veya daha küçük olamaz.');
			return false;
		}
		
		return true;
	}
	
	/**
	 * İndirim değerinin doğruluğunu kontrol eder.
	 * Örneğin tip yüzdeyse en fazla %99 değer girebilir.
	 * 
	 * @return void
	 **/
	public function _check_value() {
		
		if($_POST['type'] == '1') {
			
			if((float)$_POST['value'] > 99) {
				$this->fv->set_message('_check_value', 'İndirim yüzdesi maksimum &#37;99 olmalıdır.');
				return false;
			}
		}
		
		return true;	
	}

}

/* End of file Coupon.php */