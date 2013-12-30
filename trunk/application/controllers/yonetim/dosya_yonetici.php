<?php

if (!defined('BASEPATH')) {
	header('Location: http://' . getenv('SERVER_NAME') . '/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 * */
ini_set('memory_limit', '32M');

class dosya_yonetici extends Admin_Controller
{

	private $error = array();

	/**
	 * isimsiz construct
	 *
	 * @return void
	 * */
	public function __construct() {
		parent::__construct();
		log_message('debug', 'Filemanager Controller Yüklendi');

		giris_kontrol();
	}

	public function index() {
		$data['directory'] = base_url() . 'upload/editor/data/';

		if (isset($_GET['field'])) {
			$data['field'] = $_GET['field'];
		} else {
			$data['field'] = '';
		}

		if (isset($_GET['CKEditorFuncNum'])) {
			$data['fckeditor'] = TRUE;
		} else {
			$data['fckeditor'] = FALSE;
		}

		$this->load->view('yonetim/dosya_yonetici/index', $data);
	}

	public function image() {
		$this->load->model('image/image_model');

		if (isset($_POST['image'])) {
			exit($this->image_model->resize($_POST['image'], 100, 100));
		}
	}

	public function directory() {
		$json = array();

		if (isset($_POST['directory'])) {
			$directories = glob(rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $_POST['directory']), '/') . '/*', GLOB_ONLYDIR);

			if ($directories) {
				$i = 0;

				foreach ($directories as $directory) {
					$json[$i]['data'] = basename($directory);
					$json[$i]['attributes']['directory'] = substr($directory, strlen(DIR_IMAGE . 'data/'));
					$json[$i]['attributes']['id'] = $i;

					$children = glob(rtrim($directory, '/') . '/*', GLOB_ONLYDIR);

					if ($children) {
						$json[$i]['children'] = ' ';
					}

					$i++;
				}
			}
		}

		exit(json_encode($json));
	}

	public function files() {
		$json = array();

		$this->load->model('image/image_model');

		if (isset($_POST['directory']) && $_POST['directory']) {
			$directory = DIR_IMAGE . 'data/' . str_replace('../', '', $_POST['directory']);
		} else {
			$directory = DIR_IMAGE . 'data/';
		}

		$allowed = array(
			'.gif',
			'.jpg',
			'.jpeg',
			'.jpe',
			'.png',
			'.ico' // added by serkankoch
		);

		$files = glob(rtrim($directory, '/') . '/*');

		foreach ($files as $file) {
			if (is_file($file)) {
				$ext = strrchr($file, '.');
			} else {
				$ext = '';
			}

			if (in_array(strtolower($ext), $allowed)) {
				$size = filesize($file);

				$i = 0;

				$suffix = array(
					'B',
					'KB',
					'MB',
					'GB',
					'TB',
					'PB',
					'EB',
					'ZB',
					'YB'
				);

				while (($size / 1024) > 1) {
					$size = $size / 1024;
					$i++;
				}

				$json[] = array(
					'file' => substr($file, strlen(DIR_IMAGE . 'data/')),
					'filename' => basename($file),
					'size' => round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i],
					'thumb' => $this->image_model->resize(substr($file, strlen(DIR_IMAGE)), 50, 50)
				);
			}
		}

		exit(json_encode($json));
	}

	public function create() {
		$json = array();

		if (isset($_POST['directory'])) {
			if ($_POST['name']) {
				$directory = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $_POST['directory']), '/');

				if (!is_dir($directory)) {
					$json['error'] = 'Uyarı: Lütfen dizin seçiniz!';
				}

				if (file_exists($directory . '/' . str_replace('../', '', tr_en_temizle($_POST['name'])))) {
					$json['error'] = 'Uyarı: Aynı isimde dosya ya da dizin var!';
				}
			} else {
				$json['error'] = 'Uyarı: Lütfen yeni isim giriniz!';
			}
		} else {
			$json['error'] = 'Uyarı: Lütfen dizin seçiniz!';
		}

		if (!isset($json['error'])) {
			@mkdir($directory . '/' . str_replace('../', '', tr_en_temizle($_POST['name'])), 0777);
			@chmod($directory . '/' . str_replace('../', '', tr_en_temizle($_POST['name'])), 0777);

			$json['success'] = 'Başarılı: Dizin oluşturuldu!';
		}

		exit(json_encode($json));
	}

	public function delete() {
		$json = array();

		if (isset($_POST['path'])) {
			$path = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $_POST['path']), '/');

			if (!file_exists($path)) {
				$json['error'] = 'Uyarı: Lütfen dosya ya da dizin seçiniz!';
			}

			$_silinemez_klasorler = array(
				rtrim(DIR_IMAGE . 'data/', '/'),
				rtrim(DIR_IMAGE . 'data/resimler/', '/'),
				rtrim(DIR_IMAGE . 'data/resimler/logo/', '/'),
				rtrim(DIR_IMAGE . 'data/resimler/marka/', '/'),
				rtrim(DIR_IMAGE . 'data/resimler/slider/', '/'),
				rtrim(DIR_IMAGE . 'data/resimler/urunler/', '/'),
				rtrim(DIR_IMAGE . 'data/xml_resimleri/', '/')
			);

			if (in_array($path, $_silinemez_klasorler)) {
				$json['error'] = 'Uyarı: Bu dizini silemezsiniz!';
			}
		} else {
			$json['error'] = 'Uyarı: Lütfen dosya ya da dizin seçiniz!';
		}

		if (!isset($json['error'])) {
			if (is_file($path)) {
				@unlink($path);
			} elseif (is_dir($path)) {
				$this->recursiveDelete($path);
			}

			$json['success'] = 'Başarılı: Dosya ya da dizin silindi!';
		}

		exit(json_encode($json));
	}

	protected function recursiveDelete($directory) {
		if (is_dir($directory)) {
			$handle = opendir($directory);
		}

		if (!$handle) {
			return FALSE;
		}

		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..') {
				if (!is_dir($directory . '/' . $file)) {
					@unlink($directory . '/' . $file);
				} else {
					$this->recursiveDelete($directory . '/' . $file);
				}
			}
		}

		@closedir($handle);
		@rmdir($directory);
		return TRUE;
	}

	public function move() {
		$json = array();

		if (isset($_POST['from']) && isset($_POST['to'])) {
			$from = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $_POST['from']), '/');

			if (!file_exists($from)) {
				$json['error'] = 'Uyarı: Dosya ya da dizin yok!';
			}

			$_silinemez_klasorler = array(
				DIR_IMAGE . 'data',
				DIR_IMAGE . 'data/resimler',
				DIR_IMAGE . 'data/resimler/logo',
				DIR_IMAGE . 'data/resimler/marka',
				DIR_IMAGE . 'data/resimler/slider',
				DIR_IMAGE . 'data/resimler/urunler',
				DIR_IMAGE . 'data/xml_resimleri',
			);

			if (in_array($from, $_silinemez_klasorler)) {
				$json['error'] = 'Uyarı: Varsayılan dizin değiştirilemez!';
			}

			/* if ($from == DIR_IMAGE . 'data') {
			  $json['error'] = 'Uyarı: Varsayılan dizin değiştirilemez!';
			  } */

			$to = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $_POST['to']), '/');

			if (!file_exists($to)) {
				$json['error'] = 'Uyarı: Olmayan dizine taşıyamazsınız!';
			}

			if (file_exists($to . '/' . basename($from))) {
				$json['error'] = 'Uyarı: Aynı isimde dosya ya da dizin var!';
			}
		} else {
			$json['error'] = 'Uyarı: Lütfen dizin seçiniz!';
		}

		if (!isset($json['error'])) {
			@rename($from, $to . '/' . basename($from));

			$json['success'] = 'Başarılı: Dosya ya da dizin taşındı!';
		}

		exit(json_encode($json));
	}

	public function copy() {
		$json = array();

		if (isset($_POST['path']) && isset($_POST['name'])) {
			if ((strlen(utf8_decode($_POST['name'])) < 3) || (strlen(utf8_decode($_POST['name'])) > 255)) {
				$json['error'] = 'Uyarı: Dosya adı 3 ile 255 karakter arasında olmalı!';
			}

			$old_name = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $_POST['path']), '/');

			if (!file_exists($old_name) || $old_name == DIR_IMAGE . 'data') {
				$json['error'] = 'Uyarı: Dosya ya da dizin kopyalanamıyor!';
			}

			if (is_file($old_name)) {
				$ext = strrchr($old_name, '.');
			} else {
				$ext = '';
			}

			$new_name = dirname($old_name) . '/' . str_replace('../', '', $_POST['name'] . $ext);

			if (file_exists($new_name)) {
				$json['error'] = 'Uyarı: Aynı isimde dosya ya da dizin var!';
			}
		} else {
			$json['error'] = 'Uyarı: Lütfen dosya ya da dizin seçiniz!';
		}

		if (!isset($json['error'])) {
			if (is_file($old_name)) {
				@copy($old_name, $new_name);
			} else {
				$this->recursiveCopy($old_name, $new_name);
			}
			$json['success'] = 'Başarılı: Dosya ya da dizin kopyalandı!';
		}

		exit(json_encode($json));
	}

	function recursiveCopy($source, $destination) {
		$directory = opendir($source);

		@mkdir($destination);

		while (false !== ($file = readdir($handle))) {
			if (($file != '.') && ($file != '..')) {
				if (is_dir($source . '/' . $file)) {
					$this->recursiveCopy($source . '/' . $file, $destination . '/' . $file);
				} else {
					@copy($source . '/' . $file, $destination . '/' . $file);
				}
			}
		}

		@closedir($directory);
	}

	public function folders() {
		exit($this->recursiveFolders(DIR_IMAGE . 'data/'));
	}

	protected function recursiveFolders($directory) {
		$output = '';

		$output .= '<option value="' . substr($directory, strlen(DIR_IMAGE . 'data/')) . '">' . substr($directory, strlen(DIR_IMAGE . 'data/')) . '</option>';

		$directories = glob(rtrim(str_replace('../', '', $directory), '/') . '/*', GLOB_ONLYDIR);

		foreach ($directories as $directory) {
			$output .= $this->recursiveFolders($directory);
		}

		return $output;
	}

	public function rename() {
		$json = array();

		if (isset($_POST['path']) && isset($_POST['name'])) {
			$_POST['name'] = tr_en_temizle_file($_POST['name']);
			if ((strlen(utf8_decode($_POST['name'])) < 3) || (strlen(utf8_decode($_POST['name'])) > 255)) {
				$json['error'] = 'Uyarı: Dosya adı 3 ile 255 karakter arasında olmalı!';
			}

			$old_name = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $_POST['path']), '/');

			if (!file_exists($old_name) || $old_name == DIR_IMAGE . 'data') {
				$json['error'] = 'Uyarı: Dizin yeniden adlandırılamadı!';
			}

			if (is_file($old_name)) {
				$ext = strrchr($old_name, '.');
			} else {
				$ext = '';
			}

			$new_name = dirname($old_name) . '/' . str_replace('../', '', $_POST['name'] . $ext);

			if (file_exists($new_name)) {
				$json['error'] = 'Uyarı: Aynı isimde dosya ya da dizin var!';
			}
		}

		if (!isset($json['error'])) {
			@rename($old_name, $new_name);

			$json['success'] = 'Başarılı: Dosya ya da dizin yeniden adlandırılıdı!';
		}

		exit(json_encode($json));
	}

	public function upload() {
		$json = array();

		if (isset($_POST['directory'])) {

			if (isset($_FILES['image'])) {
				$directory = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $_POST['directory']), '/');

				if (!is_dir($directory)) {
					$json['error'] = 'Uyarı: Lütfen dizin seçiniz!';
				}

				$files = $_FILES;
				$cpt = count($_FILES['image']['name']);
				for ($i = 0; $i < $cpt; $i++) {

					$_FILES['image']['name'] = $files['image']['name'][$i];
					$_FILES['image']['type'] = $files['image']['type'][$i];
					$_FILES['image']['tmp_name'] = $files['image']['tmp_name'][$i];
					$_FILES['image']['error'] = $files['image']['error'][$i];
					$_FILES['image']['size'] = $files['image']['size'][$i];


					$durum = $this->resim_yukle('image', $directory);
				}
				if (!$durum['durum']) {
					$json['error'] = 'Uyarı: ' . strtr($durum['error'], array('<p>' => '', '</p>' => ''));
				} else {
					$json['success'] = 'Başarılı: Dosyanız yüklendi!';
				}
			} else {
				$json['error'] = 'Uyarı: Lütfen dosya seçiniz!';
			}
		} else {
			$json['error'] = 'Uyarı: Lütfen dizin seçiniz!';
		}

		exit(json_encode($json));
	}

	public function resim_yukle($key = 'upload', $kok_dizin) {
		$config['upload_path'] = $kok_dizin;
		$config['allowed_types'] = 'gif|jpg|jpeg|jpe|png|ico';
		$config['max_size'] = '1024';
		$config['max_width'] = '1024';
		$config['max_height'] = '768';

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload($key)) {
			$gonder = array('durum' => false, 'error' => $this->upload->display_errors());
		} else {
			$gonder = array('durum' => true, 'upload_data' => $this->upload->data());
		}

		return $gonder;
	}

}

?>
