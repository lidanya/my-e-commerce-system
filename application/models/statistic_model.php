<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class statistic_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'İstatistik Model Yüklendi');
		$this->run_statistic();
	}

	function run_statistic()
	{
		if($this->agent->is_robot())
		{
			$tarayici = $this->agent->robot();
			$tip = 1; // bot
		} else {
			$tarayici = substr($this->input->user_agent(), 0, 149);
			$tip = 2; // ziyaretçi
		}

		if($this->uri->uri_string())
		{
			$son_sayfa = $this->uri->uri_string();
		} else {
			$son_sayfa = NULL;
		}

		$data = array(
			'istatistik_ip'					=> $this->input->ip_address(),
			'istatistik_son_sayfa'			=> $son_sayfa,
			'istatistik_tarih'				=> standard_date('DATE_MYSQL', time(), 'tr'),
			'istatistik_tarayici_bilgisi'	=> $tarayici,
			'istatistik_tip'				=> $tip
			
		);

		if($this->dx_auth->is_logged_in())
		{
			$data['istatistik_uye_id']		= $this->dx_auth->get_user_id();
		} else {
			$data['istatistik_uye_id']		= NULL;
		}

		$this->db->insert('istatistik', $data);
	}

	// toplam ziyaret sayısını döndürür
	public function total_visit()
	{
		return $this->db->count_all_results('istatistik');
	}

	// toplam tekil ziyaretçi sayısını döndürür
	public function total_uniq()
	{
		$total = $this->db
					->select('istatistik_id')
					->group_by('istatistik_ip')
					->get('istatistik')
					->num_rows();
		return $total;
	}

	// toplam online ziyaretçi sayısını döndürür.
	public function total_online()
	{
		$total = $this->db
					->where('UNIX_TIMESTAMP(`istatistik_tarih`) > (UNIX_TIMESTAMP() - 300)')
					->group_by('istatistik_ip')
					->get('istatistik')
					->num_rows();
		return $total;
	}

	// günlük rapor
	public function daily()
	{
		$q = $this->db
					->select('HOUR(istatistik_tarih) as date, COUNT(DISTINCT istatistik_ip) as uniq_visit, COUNT(*) as total_visit')
					->where('DAY(istatistik_tarih) = DAY(CURDATE())')
					->group_by('date')
					->get('istatistik');

		return $this->_to_js($q->result(), 'daily');
	}

	// haftalık rapor
	public function weekly()
	{
		$q = $this->db
					->select('WEEKDAY(istatistik_tarih) as date, COUNT(DISTINCT istatistik_ip) as uniq_visit, COUNT(*) as total_visit')
					->where('WEEK(istatistik_tarih, 1) = WEEK(CURDATE(), 1)')
					->group_by('date')
					->get('istatistik');
		return $this->_to_js($q->result(), 'weekly');
	}

	// aylık rapor
	public function monthly()
	{
		$q = $this->db
					->select('DAY(istatistik_tarih) as date, COUNT(DISTINCT istatistik_ip) as uniq_visit, COUNT(*) as total_visit')
					->where('MONTH(istatistik_tarih) = MONTH(CURDATE())')
					->group_by('date')
					->get('istatistik');

		return $this->_to_js($q->result(), 'monthly');
	}

	// yıllık rapor
	public function yearly()
	{
		$q = $this->db
					->select('MONTH(istatistik_tarih) as date, COUNT(DISTINCT istatistik_ip) AS uniq_visit, COUNT(*) as total_visit')
					->where('YEAR(istatistik_tarih) = YEAR(CURDATE())')
					->group_by('date')
					->get('istatistik');

		return $this->_to_js($q->result(), 'yearly');
	}

	/**
	 * google stats plugininde kullanılmak üzere veriler javascript e uygun hale çevriliyor.
	 * 
	 * @param object
	 * @return string
	 **/
	private function _to_js($data, $group = 'daily')
	{
		$stats = array();
		foreach ($data as $row) {
			$stats[$row->date] = array((string) $row->date, (int) $row->uniq_visit, (int) $row->total_visit);
		}
		$this->_fill_data($stats, $group);
		ksort($stats);
		return $stats;
	}

	/**
	 * Boş gelen değerleri doldurur. örneğin ayın 5. gününe ait hiçbir veri yok 
	 * ayın 5. günü diye bir key oluşturulup 0 değerler atanır.
	 * 
	 * @return string neye göre guruplanacak
	 * 		- daily default
	 * 		- monthly
	 * 		- yearly
	 * @return void
	 **/
	private function _fill_data(&$data, $group = 'daily')
	{
		switch ($group) {
			case 'daily':
				$keys = range(0, 23);
				break;
			case 'weekly':
				$keys = array(
					0	=> 'Pazartesi',
					1	=> 'Salı',
					2	=> 'Çarşamba',
					3	=> 'Perşembe',
					4	=> 'Cuma',
					5	=> 'Cumartesi',
					6	=> 'Pazar'
				);
				break;
			case 'monthly':
				$keys = range(1, date('t'));
				break;
			case 'yearly':
				$keys = array(
					1	=> 'Ocak',
					2	=> 'Şubat',
					3	=> 'Mart',
					4	=> 'Nisan',
					5	=> 'Mayıs',
					6	=> 'Haziran',
					7	=> 'Temmuz',
					8	=> 'Ağustos',
					9	=> 'Eylül',
					10	=> 'Ekim',
					11	=> 'Kasım',
					12	=> 'Aralık'
				);
				break;
			default:
				$keys = range(1, date('t'));
				break;
		}

		foreach ($keys as $_key => $_value) {
			if( ! isset($data[$_key])) {
				$data[$_key] = array((string) $_value, (int) 0, (int) 0);
			} else {
				$data[$_key] = array((string) $_value, (int) $data[$_key][1], (int) $data[$_key][2]);
			}
		}
	}

}