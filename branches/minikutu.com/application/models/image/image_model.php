<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/

require_once APPPATH . 'models/image/image' . EXT;

class image_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Image Model Yüklendi');
	}

	function resize($filename, $width, $height)
	{
		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			return;
		}

		$info = pathinfo($filename);
		$extension = $info['extension'];
		$old_image = $filename;
		$new_image = 'cache/' . substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

		if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
			$path = '';
			
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				
				if (!file_exists(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}		
			}

			$image = new image(DIR_IMAGE . $old_image);
			$image->resize($width, $height);
			$image->save(DIR_IMAGE . $new_image);
		}
		if (config('site_ayar_ssl') == '1' && isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
			$base_url = '/';
		} else {
			$base_url = $this->config->item('base_url');
		}
		return $base_url . 'upload/editor/' . $new_image;
	}
}