<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class kategori_model extends CI_Model
{
	protected $kategoriler = array();
	
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Kategori Model Yüklendi');

		$this->load->library('pagination');
		$this->load->library('daynex_pagination');
	}

	function kategori_varmi($kategori_seo_name)
	{
		$this->db->where('stk_kategori_seo', $kategori_seo_name);
		$this->db->where('stk_kategori_flag', '1');
		$toplam = $this->db->count_all_results('stok_kategori');
		if($toplam > 0)
		{
			return true;
		} else {
			return false;
		}
	}

	function kategori_kaydet($kategori_id)
	{
		$this->kategoriler = array();
		self::ketegori_getir($kategori_id);
		if(count($this->kategoriler) > 0)
		{
			$this->kategoriler = array_unique($this->kategoriler);
		}
		return $this->kategoriler;
	}

	function ketegori_getir($id)
	{
		$sorgu = $this->db->get_where('stok_kategori', array('stk_kategori_ust_id' => (int) $id));
		$this->kategoriler[] = $id;
		foreach ($sorgu->result() as $sonuc) 
		{
			$this->kategoriler[] = $sonuc->stk_kategori_id;
			$this->ketegori_getir($sonuc->stk_kategori_id);
		}
	}

	function urun_sayisini_topla($kategori_id)
	{
		$sayi = 0;

		$cache_grup = 'kategori_urun_sayisi';
		$cache_expire = '60'; // minutes
		$cache_kontrol = $this->cache->get($kategori_id, $cache_grup);
		if($cache_kontrol === FALSE)
		{
			$kategoriler = self::kategori_kaydet($kategori_id);
			if(count($kategoriler) > 0)
			{
				foreach($kategoriler as $kategori)
				{
					$this->db->select('
						stok_kategori.stk_kategori_id,
						stok_kategori.stk_kategori_flag,
						stok.stok_id,
						stok.stok_flag,
					');
					$this->db->where('stok_kategori.stk_kategori_flag', '1');
					$this->db->where('stok.stok_flag', '1');
					$this->db->where('stok_stok_kategori.kategori_id', $kategori);
					$this->db->join('stok_kategori', 'stok_stok_kategori.kategori_id = stok_kategori.stk_kategori_id', 'left');
					$this->db->join('stok', 'stok_stok_kategori.stok_id = stok.stok_id', 'left');
					$count_all = $this->db->count_all_results('stok_stok_kategori');
					if($count_all > 0)
					{
						$sayi += $count_all;
					}
				}

				$this->cache->set($kategori_id, $sayi, $cache_grup, $cache_expire);
			}
		} else {
			$sayi = $cache_kontrol;
		}

		return $sayi;
	}

	function kategori_bilgi($kategori_seo_name)
	{
		$this->db->select('stk_kategori_id, stk_kategori_adi, stk_kategori_seo, stk_kategori_title, stk_kategori_keywords, stk_kategori_description, stk_kategori_sira, stk_kategori_resim, stk_kategori_ust_id, stk_kategori_flag');
		$sorgu = $this->db->get_where('stok_kategori', array('stk_kategori_seo' => $kategori_seo_name, 'stk_kategori_flag' => '1'), 1);
		return $sorgu->row();
	}

	function alt_kategori($ust_kategori_id)
	{
		if(eklenti_ayar('kategori', 'siralama_sekli') != NULL)
		{
			$siralama = eklenti_ayar('kategori', 'siralama_sekli');
		} else {
			$siralama = 'asc';
		}
		$this->db->order_by('stk_kategori_sira', $siralama);
		$this->db->select('stk_kategori_id, stk_kategori_adi, stk_kategori_seo, stk_kategori_title, stk_kategori_keywords, stk_kategori_description, stk_kategori_sira, stk_kategori_resim, stk_kategori_ust_id, stk_kategori_flag');
		$sorgu = $this->db->get_where('stok_kategori', array('stk_kategori_ust_id' => $ust_kategori_id, 'stk_kategori_flag' => '1'));
		return $sorgu;
	}

	function kampanya_kontrol($stok_id)
	{
		$this->db->order_by('indirim_oncelik', 'asc');
		$this->db->select('indirim_id, indirim_musteri_grubu, indirim_miktar, indirim_oncelik, indirim_fiyati, indirim_basla, indirim_bitir, indirim_tip, indirim_flag, stok_id');
		$sorgu = $this->db->get_where('stok_indirim_kampanya', array('stok_id' => $stok_id, 'indirim_flag' => '1', 'indirim_basla <' => time(), 'indirim_bitir >' => time()), 1);
		return $sorgu;
	}

	function urun_listele($kategori_id, $kategori_bilgi, $kategori_seo_name, $sort, $order, $sayfa = 0)
	{
		$per_page = (config('site_ayar_urun_site_sayfa')) ? config('site_ayar_urun_site_sayfa') : 9;

		$stok_table_name = $this->db->dbprefix('stok');
		$stok_detay_table_name = $this->db->dbprefix('stok_detay');
		$stok_kategori_table_name = $this->db->dbprefix('stok_kategori');
		$stok_stok_kategori_table_name = $this->db->dbprefix('stok_stok_kategori');
		$stok_resim_table_name = $this->db->dbprefix('stok_resim');

		if($sort == 'stok_id')
		{
			$sort = $stok_table_name . '.stok_id';
		}

		$query = 'SELECT SQL_CALC_FOUND_ROWS
'. $stok_table_name .'.stok_id,
'. $stok_table_name .'.stok_kod,
'. $stok_table_name .'.stok_adi,
'. $stok_table_name .'.stok_fiyat,
'. $stok_table_name .'.stok_fiyat_maliyet,
'. $stok_table_name .'.stok_kdv,
'. $stok_table_name .'.stok_acik,
'. $stok_table_name .'.stok_tur,
'. $stok_table_name .'.fiyat_tur,
'. $stok_table_name .'.stok_flag,
'. $stok_table_name .'.stok_hit,
'. $stok_kategori_table_name .'.stk_kategori_id,
'. $stok_kategori_table_name .'.stk_kategori_adi,
'. $stok_kategori_table_name .'.stk_kategori_seo,
'. $stok_kategori_table_name .'.stk_kategori_title,
'. $stok_kategori_table_name .'.stk_kategori_keywords,
'. $stok_kategori_table_name .'.stk_kategori_description,
'. $stok_kategori_table_name .'.stk_kategori_sira,
'. $stok_kategori_table_name .'.stk_kategori_resim,
'. $stok_kategori_table_name .'.stk_kategori_ust_id,
'. $stok_kategori_table_name .'.stk_kategori_flag,
'. $stok_stok_kategori_table_name .'.stk_kat_id,
'. $stok_stok_kategori_table_name .'.kategori_id,
'. $stok_stok_kategori_table_name .'.stk_kat_flag,
'. $stok_stok_kategori_table_name .'.stok_id,
'. $stok_detay_table_name .'.stk_detay_id,
'. $stok_detay_table_name .'.stk_detay_seo,
'. $stok_detay_table_name .'.stk_detay_anahtar,
'. $stok_detay_table_name .'.stk_detay_kisa_ack,
'. $stok_detay_table_name .'.stk_detay_sira,
'. $stok_detay_table_name .'.stk_detay_miktar,
'. $stok_detay_table_name .'.stk_detay_stoktan_dus,
'. $stok_detay_table_name .'.stk_detay_stok_durumu,
'. $stok_detay_table_name .'.stk_detay_gecerlilik_tarihi,
'. $stok_detay_table_name .'.stk_detay_kargo_gerekli,
'. $stok_detay_table_name .'.stk_detay_uzunluk,
'. $stok_detay_table_name .'.stk_detay_genislik,
'. $stok_detay_table_name .'.stk_detay_yukseklik,
'. $stok_detay_table_name .'.stk_detay_uzunluk_olcusu,
'. $stok_detay_table_name .'.stk_detay_agirlik,
'. $stok_detay_table_name .'.stk_detay_agirlik_olcusu,
'. $stok_detay_table_name .'.stk_detay_uretici,
'. $stok_detay_table_name .'.stk_detay_kar_orani,
'. $stok_detay_table_name .'.stk_detay_benzer_urun,
'. $stok_detay_table_name .'.stk_detay_as_goster,
'. $stok_detay_table_name .'.stk_detay_yeni_urun,
'. $stok_detay_table_name .'.stk_detay_video,
'. $stok_detay_table_name .'.stk_detay_ozellik_goster,
'. $stok_resim_table_name .'.stk_resim_adi
FROM ('.  $stok_stok_kategori_table_name .')
LEFT JOIN '. $stok_table_name .' ON '. $stok_stok_kategori_table_name .'.stok_id = '.  $stok_table_name .'.stok_id
LEFT JOIN '. $stok_detay_table_name .' ON '. $stok_table_name .'.stok_id = '.  $stok_detay_table_name .'.stok_id
LEFT JOIN '. $stok_kategori_table_name .' ON '. $stok_stok_kategori_table_name .'.kategori_id = '.  $stok_kategori_table_name .'.stk_kategori_id
LEFT OUTER JOIN '. $stok_resim_table_name .' ON 
('.  $stok_table_name .'.stok_id = '. $stok_resim_table_name .'.stok_id
	AND
	(
		'. $stok_resim_table_name .'.stok_id = '.  $stok_table_name .'.stok_id
		AND
		('. $stok_resim_table_name .'.stk_resim_default = \'1\' OR '. $stok_resim_table_name .'.stk_resim_default IS NULL)
		OR
		'. $stok_resim_table_name .'.stok_id IS NULL
	)
)';
		$query .= "\n" . 'WHERE ' . $stok_stok_kategori_table_name . '.kategori_id = \''. $this->db->escape_str($kategori_id) .'\'';
		$query .= "\n" . 'AND ' . $stok_stok_kategori_table_name . '.stk_kat_flag = \'1\'';
		$query .= "\n" . 'AND ' . $stok_table_name . '.stok_flag = \'1\'';
		$query .= "\n" . 'GROUP BY ' . $stok_table_name . '.stok_id';
		$query .= "\n" . 'ORDER BY '. $sort .' '. $order;
		$query .= "\n" . 'LIMIT ' . $sayfa . ', ' . $per_page;

		$sorgu = $this->db->query($query, FALSE);
		$sorgu_say = $this->db->select('FOUND_ROWS() as toplam')->get()->row()->toplam;

		$config['per_page'] = $per_page;
		$config['total_rows'] = $sorgu_say;
		$config['full_tag_open'] = 'Sayfa : ';
		$config['full_tag_close'] = '';
		$config['num_links'] = 6;
		$config['base_url'] = site_url($kategori_seo_name . '--category/' . $sort . '-' . $order);
		$config['uri_segment'] = 3;

		$config['full_tag_open'] = '<div class="liste_sag saga"><ul>';
		$config['full_tag_close'] = '</ul></div>';

		$config['first_link'] = '<img src="'. site_resim() .'liste_bas.png" alt="" style="margin:3px 0;" />';
		$config['first_tag_open'] = '<li><span>';
		$config['first_tag_close'] = '</span></li>';
		$config['first_a_class'] = 'class="buton"';

		$config['last_link'] = '<img src="'. site_resim() .'liste_son.png" alt="" style="margin:3px 0;" />';
		$config['last_tag_open'] = '<li><span>';
		$config['last_tag_close'] = '</span></li>';
		$config['last_a_class'] = 'class="buton"';

		$config['next_link'] = '<img src="'. site_resim() .'liste_ileri.png" alt="" style="margin:3px 0;" />';
		$config['next_tag_open'] = '<li><span>';
		$config['next_tag_close'] = '</span></li>';
		$config['next_a_class'] = 'class="buton"';

		$config['prev_link'] = '<img src="'. site_resim() .'liste_geri.png" alt="" style="margin:3px 0;" />';
		$config['prev_tag_open'] = '<li><span>';
		$config['prev_tag_close'] = '</span></li>';
		$config['prev_a_class']	= 'class="buton"';

		$config['cur_tag_open'] = '<li class="l_aktif">';
		$config['cur_tag_close'] = '</li>';
		$config['cur_a_class'] = 'class="l_sayfa"';

		$config['num_tag_open'] = '<li><span>';
		$config['num_tag_close'] = '</span></li>';
		$config['num_a_class'] = 'class="l_sayfa"';

		$this->daynex_pagination->initialize($config);

		$gonder = array('sorgu' => $sorgu, 'toplam' => $sorgu_say);
		return $gonder;
	}

	function resim_bul($stk_kat_id = 0)
	{
		$stok = $this->db->get_where('stok_stok_kategori',array('kategori_id'=>$stk_kat_id),1);
		if($stok->num_rows() > 0)
		{
			$resim = $this->db->get_where('stok_resim',array('stok_id'=>$stok->row()->stok_id),1);
			if($resim->num_rows() > 0)
			{
				return $resim->row()->stk_resim_adi;
			}
		}
		return FALSE;
	}
}