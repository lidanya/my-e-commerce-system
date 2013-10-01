<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/

class image
{
	private $file;
	private $image;
	private $new_image;
	private $info;

	public function __construct($file)
	{
		if (file_exists($file)) {
			$this->file = $file;

			$info = getimagesize($this->file);
			$this->info = array(
				'width'		=> $info[0],
				'height'	=> $info[1],
				'bits'		=> $info['bits'],
				'mime'		=> $info['mime'],
				'int_type'	=> $info[2]
			);

			$this->image = $this->create($file);
		} else {
			log_message('debug', 'Error: Could not load image ' . $file . '!');
		}
	}

	private function create($image)
	{
		$int_type = $this->info['int_type'];

		if ($int_type == 1) {
			return imagecreatefromgif($image);
		} elseif ($int_type == 2) {
			return imagecreatefromjpeg($image);
		} elseif ($int_type == 3) {
			return imagecreatefrompng($image);
		}
	}	

	public function save($filename, $quality = 100)
	{
		$int_type = $this->info['int_type'];

		if ($int_type == 1) {
			imagegif($this->new_image, $filename);
		} elseif ($int_type == 2) {
			imagejpeg($this->new_image, $filename, $quality);
		} elseif ($int_type == 3) {
			imagepng($this->new_image, $filename);
		}

		imagedestroy($this->new_image);
	}

	public function resize($width = 0, $height = 0)
	{
		if (!$this->info['width'] || !$this->info['height'])
		{
			return;
		}

		$xpos = 0;
		$ypos = 0;

		if ($this->info['width'] <= $width && $this->info['height'] <= $height)
		{
			$new_width = (int) $this->info['width'];
			$new_height = (int) $this->info['height'];
		} else {
			$scale = min($width / $this->info['width'], $height / $this->info['height']);

			if ($scale == 1)
			{
				return;
			}

			$new_width = (int)($this->info['width'] * $scale);
			$new_height = (int)($this->info['height'] * $scale);
		}

		$xpos = (int)(($width - $new_width) / 2);
		$ypos = (int)(($height - $new_height) / 2);
		$image_old = $this->image;
		$this->new_image = imagecreatetruecolor($width, $height);

		$int_type = $this->info['int_type'];

		if (($int_type == 1) OR ($int_type == 3)) {		
			imagealphablending($this->new_image, false);
			imagesavealpha($this->new_image, true);
			$background = imagecolorallocatealpha($this->new_image, 255, 255, 255, 127);
			imagecolortransparent($this->new_image, $background);
		} else {
			$background = imagecolorallocate($this->new_image, 255, 255, 255);
		}

		imagefilledrectangle($this->new_image, 0, 0, $width, $height, $background);

		imagecopyresampled($this->new_image, $image_old, $xpos, $ypos, 0, 0, $new_width, $new_height, $this->info['width'], $this->info['height']);
		imagedestroy($image_old);

		$this->info['width']  = $width;
		$this->info['height'] = $height;
	}

	public function watermark($file, $position = 'bottomright')
	{
		$watermark = $this->create($file);

		$watermark_width = imagesx($watermark);
		$watermark_height = imagesy($watermark);

		switch($position) {
			case 'topleft':
				$watermark_pos_x = 0;
				$watermark_pos_y = 0;
				break;
			case 'topright':
				$watermark_pos_x = $this->info['width'] - $watermark_width;
				$watermark_pos_y = 0;
				break;
			case 'bottomleft':
				$watermark_pos_x = 0;
				$watermark_pos_y = $this->info['height'] - $watermark_height;
				break;
			case 'bottomright':
			$watermark_pos_x = $this->info['width'] - $watermark_width;
			$watermark_pos_y = $this->info['height'] - $watermark_height;
			break;
		}

		imagecopy($this->image, $watermark, $watermark_pos_x, $watermark_pos_y, 0, 0, 120, 40);

		imagedestroy($watermark);
	}

	public function crop($top_x, $top_y, $bottom_x, $bottom_y)
	{
		$image_old = $this->image;
		$this->image = imagecreatetruecolor($bottom_x - $top_x, $bottom_y - $top_y);

		imagecopy($this->image, $image_old, 0, 0, $top_x, $top_y, $this->info['width'], $this->info['height']);
		imagedestroy($image_old);

		$this->info['width'] = $bottom_x - $top_x;
		$this->info['height'] = $bottom_y - $top_y;
	}

	public function rotate($degree, $color = 'FFFFFF')
	{
		$rgb = $this->html2rgb($color);

	    $this->image = imagerotate($this->image, $degree, imagecolorallocate($this->image, $rgb[0], $rgb[1], $rgb[2]));

		$this->info['width'] = imagesx($this->image);
		$this->info['height'] = imagesy($this->image);
	}

	private function filter($filter)
	{
		imagefilter($this->image, $filter);
	}

    private function text($text, $x = 0, $y = 0, $size = 5, $color = '000000')
    {
		$rgb = $this->html2rgb($color);

		imagestring($this->image, $size, $x, $y, $text, imagecolorallocate($this->image, $rgb[0], $rgb[1], $rgb[2]));
    }

	private function merge($file, $x = 0, $y = 0, $opacity = 100)
	{
		$merge = $this->create($file);
		
		$merge_width = imagesx($image);
		$merge_height = imagesy($image);

		imagecopymerge($this->image, $merge, $x, $y, 0, 0, $merge_width, $merge_height, $opacity);
	}

	private function html2rgb($color)
	{
		if ($color[0] == '#') {
			$color = substr($color, 1);
		}
		
		if (strlen($color) == 6) {
			list($r, $g, $b) = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);   
		} elseif (strlen($color) == 3) {
			list($r, $g, $b) = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);    
		} else {
			return FALSE;
		}
		
		$r = hexdec($r); 
		$g = hexdec($g); 
		$b = hexdec($b);    
		
		return array($r, $g, $b);
	}
}