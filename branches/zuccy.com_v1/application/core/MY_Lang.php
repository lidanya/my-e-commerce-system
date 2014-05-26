<?php 

if (! defined('BASEPATH')) exit('No direct script access');

class MY_Lang extends CI_Lang
{
	// languages
	var $languages = array();

	// special URIs (not localized)
	var $special = array();

	// where to redirect if no language in URI
	var $default_uri = '';

	protected $config;
	protected $db;
	protected $uri;
	protected $router;

	function __construct()
	{
		$this->config	=& load_class('Config', 'core');
		$this->uri		=& load_class('URI', 'core');
		$this->router	=& load_class('Router', 'core');

		$db = $this->database();
		//exit('<pre>' . print_r($db, TRUE));

		/* Selected Languages Query */
		$_languages = array();
		$_languages['tr'] = 'turkish';
		if(isset($db) AND is_object($db)) {
			$db->select('language_id, name, code, locale, image, directory, filename, sort_order, status');
			$db->from('language');
			$db->where('status', '1');
			$lang_query = $db->get();
			if($lang_query) {
				foreach($lang_query->result_array() as $language) {
					$_languages[$language['code']] = $language['directory'];
				}
			}
		}
		/* Selected Languages Query */

		$this->languages = $_languages;
		$this->special = $this->config->item('special_uri');
		$this->default_uri = $this->config->item('default_uri');

		$segment = $this->uri->segment(1);
		if (isset($this->languages[$segment]))	// URI with language -> ok
		{
			$language = $this->languages[$segment];
			$this->config->set_item('language', $language);
		} elseif($this->is_special($segment)) { // special URI -> no redirect
			$_def_lang = $this->default_lang();
			$this->config->set_item('language', $this->languages[$_def_lang]);
			return;
		} else { // URI without language -> redirect to default_uri
			// set default language
			$_def_lang = $this->default_lang();
			$this->config->set_item('language', $this->languages[$_def_lang]);

			// redirect
			header("Location: " . $this->config->site_url($this->localized($this->default_uri)), TRUE, 302);
			exit;
		}
	}

	function database()
	{
		// load database configs
		require APPPATH.'config/database'.EXT;
		$_array_params = $db[$active_group];
		$_array_params['db_debug'] = FALSE;

		// set db configs debug false
		require_once BASEPATH.'database/DB'.EXT;

		// Load the DB class
		$db =& DB($_array_params, TRUE);
		return $db;
	}

	// get current language
	// ex: return 'en' if language in CI config is 'english' 
	function lang()
	{
		$language = $this->config->item('language');
		$lang = array_search($language, $this->languages);
		if ($lang) {
			return $lang;
		}

		return NULL;	// this should not happen
	}

	function is_special($uri)
	{
		$exploded = explode('/', $uri);
		if (in_array($exploded[0], $this->special)) {
			return TRUE;
		}
		if(isset($this->languages[$uri])) {
			return TRUE;
		}

		return FALSE;
	}

	function switch_uri($lang)
	{
		$uri = $this->uri->uri_string();
		if ($uri != '') {
			$exploded = explode('/', $uri);
			if($exploded[1] == $this->lang()) {
				$exploded[1] = $lang;
			}
			$uri = implode('/', $exploded);
		} else {
			$uri = $lang;
		}

		$lang_uri = $lang;
		$orginal_check = $uri;
		$new_check = $this->seo_changer($uri, $lang, $lang_uri, $orginal_check);

		return $new_check;
	}

	function seo_changer($uri, $lang, $lang_uri, $orginal_check)
	{
		$db = $this->database();
		$ci =& get_instance();
		$check = ltrim($uri, '/' . $lang_uri . '/');
		if(isset($db) AND is_object($db)) {
			if(strpos($check, '--product')) {
				$last_prefix = '--product';
				$product_detail_rtrim = rtrim($check, $last_prefix);

				$db->select(get_fields_from_table('product_description', 'pd.', array('product_id')));
				$db->from('product_description pd');
				$db->where('pd.seo', $product_detail_rtrim);
				$db->where('pd.language_id', get_language('language_id'));
				$db->limit(1);
				$product_detail_query = $db->get();
				if($product_detail_query) {
					if($product_detail_query->num_rows()) {
						$product_detail = $product_detail_query->row();
						$db->select(get_fields_from_table('product_description', 'pd.', array('seo')));
						$db->from('product_description pd');
						$db->where('pd.product_id', $product_detail->product_id);
						$db->where('pd.language_id', get_language('language_id', $lang));
						$db->limit(1);
						$product_detail_query_ = $db->get();
						if($product_detail_query_->num_rows()) {
							$product_detail_ = $product_detail_query_->row();
							$check = $lang_uri . '/' . $product_detail_->seo . $last_prefix;
						}
					}
				}
			} elseif(strripos($check, '--news')) {
				$last_prefix = '--news';
				$news_detail_rtrim = rtrim($check, $last_prefix);

				$db->distinct();
				$db->select(
					get_fields_from_table('information', 'i.', array('information_id'), ', ') .
					get_fields_from_table('information_description', 'id.', array())
				);
				$db->from('information i');
				$db->join('information_description id', 'i.information_id = id.information_id', 'left');
				$db->where('id.seo', $news_detail_rtrim);
				$db->where('id.language_id', get_language('language_id'));
				$db->where('i.type', 'news');
				$db->limit(1);
				$news_detail_query = $db->get();
				if($news_detail_query) {
					if($news_detail_query->num_rows()) {
						$news_detail = $news_detail_query->row();
						$db->select(get_fields_from_table('information_description', 'id.', array('seo')));
						$db->from('information_description id');
						$db->where('id.information_id', $news_detail->information_id);
						$db->where('id.language_id', get_language('language_id', $lang));
						$db->limit(1);
						$news_detail_query_ = $db->get();
						if($news_detail_query_->num_rows()) {
							$news_detail_ = $news_detail_query_->row();
							$check = $lang_uri . '/' . $news_detail_->seo . $last_prefix;
						}
					}
				}
			} elseif(strripos($check, '--announcement')) {
				$last_prefix = '--announcement';
				$announcement_detail_rtrim = rtrim($check, $last_prefix);
				$db->distinct();
				$db->select(
					get_fields_from_table('information', 'i.', array('information_id'), ', ') .
					get_fields_from_table('information_description', 'id.', array())
				);
				$db->from('information i');
				$db->join('information_description id', 'i.information_id = id.information_id', 'left');
				$db->where('id.seo', $announcement_detail_rtrim);
				$db->where('id.language_id', get_language('language_id'));
				$db->where('i.type', 'announcement');
				$db->limit(1);
				$announcement_detail_query = $db->get();
				if($announcement_detail_query) {
					if($announcement_detail_query->num_rows()) {
						$announcement_detail = $announcement_detail_query->row();
						$db->select(get_fields_from_table('information_description', 'id.', array('seo')));
						$db->from('information_description id');
						$db->where('id.information_id', $announcement_detail->information_id);
						$db->where('id.language_id', get_language('language_id', $lang));
						$db->limit(1);
						$announcement_detail_query_ = $db->get();
						if($announcement_detail_query_->num_rows()) {
							$announcement_detail_ = $announcement_detail_query_->row();
							$check = $lang_uri . '/' . $announcement_detail_->seo . $last_prefix;
						}
					}
				}
			} elseif(strripos($check, '--information')) {
				$last_prefix = '--information';
				$information_detail_rtrim = rtrim($check, $last_prefix);

				$db->distinct();
				$db->select(
					get_fields_from_table('information', 'i.', array('information_id'), ', ') .
					get_fields_from_table('information_description', 'id.', array())
				);
				$db->from('information i');
				$db->join('information_description id', 'i.information_id = id.information_id', 'left');
				$db->where('id.seo', $information_detail_rtrim);
				$db->where('id.language_id', get_language('language_id'));
				$db->where('i.type', 'information');
				$db->limit(1);
				$information_detail_query = $db->get();
				if($information_detail_query) {
					if($information_detail_query->num_rows()) {
						$information_detail = $information_detail_query->row();
						$db->select(get_fields_from_table('information_description', 'id.', array('seo')));
						$db->from('information_description id');
						$db->where('id.information_id', $information_detail->information_id);
						$db->where('id.language_id', get_language('language_id', $lang));
						$db->limit(1);
						$information_detail_query_ = $db->get();
						if($information_detail_query_->num_rows()) {
							$information_detail_ = $information_detail_query_->row();
							$check = $lang_uri . '/' . $information_detail_->seo . $last_prefix;
						}
					}
				}
			} elseif(strripos($check, '--category')) {
				$last_prefix = '--category';
				$category_detail_rtrim = rtrim($check, $last_prefix);

				$path = '';
				$parts = explode('---', $category_detail_rtrim);

				$new_categories = array();

				foreach($parts as $path_id) {
					$db->distinct();
					$db->select(
						get_fields_from_table('category_description', 'cd.', array('category_id'), ', ')
					);
					$db->from('category_description cd');
					$db->where('cd.language_id', get_language('language_id'));
					$db->where('cd.seo', $path_id);
					$db->limit(1);
					$category_detail_query = $db->get();
					if ($category_detail_query) {
						if ($category_detail_query->num_rows()) {
							$category_detail = $category_detail_query->row();
							$db->select(get_fields_from_table('category_description', 'cd.', array('seo')));
							$db->from('category_description cd');
							$db->where('cd.category_id', $category_detail->category_id);
							$db->where('cd.language_id', get_language('language_id', $lang));
							$db->limit(1);
							$category_detail_query_ = $db->get();
							if($category_detail_query_->num_rows()) {
								$category_detail_ = $category_detail_query_->row();
								$category_info = $category_detail_;
								$new_categories[] = $category_info->seo;
							}
						}
					}
				}

				if ($new_categories) {
					$check = $lang_uri . '/' . implode('---', $new_categories) . $last_prefix;
				}
			} else {
				$check = $orginal_check;
			}
		} else {
			$check = $orginal_check;
		}

		return $check;
	}

	// is there a language segment in this $uri?
	function has_language($uri)
	{
		$first_segment = NULL;
		$exploded = explode('/', $uri);
		if(isset($exploded[0])) {
			if($exploded[0] != '') {
				$first_segment = $exploded[0];
			} else if(isset($exploded[1]) && $exploded[1] != '') {
				$first_segment = $exploded[1];
			}
		}
		if($first_segment != NULL) {
			return isset($this->languages[$first_segment]);
		}
		return FALSE;
	}

	// default language: first element of $this->languages
	function default_lang()
	{
		$default_language = 'tr';
		$db = $this->database();
		if(isset($db) AND is_object($db)) {
			$db->select('ayar_adi, ayar_deger');
			$default_query = $db->get_where('ayarlar', array('ayar_adi' => 'site_ayar_dil'), 1);
			if($default_query) {
				if($default_query->num_rows()) {
					$default_language = $default_query->row()->ayar_deger;
				} else {
					$default_language = 'tr';
				}
			}
		}
		/* Default Languages Query */
		if($default_language) {
			if(isset($this->languages[$default_language])) {
				$default_language = $default_language;
			} else {
				$default_language = 'tr';
			}
		}
		/* Default Languages Query */
		return $default_language;
	}

	// add language segment to $uri (if appropriate)
	function localized($uri)
	{
		if($this->has_language($uri) || $this->is_special($uri) || preg_match('/(.+)\.[a-zA-Z0-9]{2,4}$/', $uri)) {
			// we don't need a language segment because:
			// - there's already one
			// - or it's a special uri (set in $special)
			// - or that's a link to a file
		} else {
			$uri = $this->lang() . '/' . $uri;
		}
		return $uri;
	}
}

/* End of file class_name.php */
/* Location: ./application/controllers/class_name.php */