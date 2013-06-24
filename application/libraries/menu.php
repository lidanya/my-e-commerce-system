<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class menu
{
	var $CI;

	function __construct()
	{
		log_message('debug', "Menu Kütüphanesi Yüklendi");
		$this->ci =& get_instance();
	}

	//$deneme = array('admin2','admin3');
	//$this->menu->menu_class('2','3','admin',$deneme,'class="selected_lk"',2); // example
	function menu_class($uri,$uri2 = false,$name,$name2 = false,$class,$select)
	{
		$check = $this->ci->uri->segment($uri);
		$check2 = $this->ci->uri->segment($uri2);
		
			switch($select):
			
			case 1:
			
				if ($check == $name)
				{
					echo $class;
				}
				else
				{
					echo '';
				}
			
			break;
			
			case 2:
				if (is_array($name)) {
					$i = 0;
					$ii = 0;
					foreach($name as $name_as):
					if ($name_as == $check) {
						$ii = $ii+1;
					}
					endforeach;
					$gonder['ii'] = $ii;
					$gonder['durum'] = true;
					$name_array = $gonder;
				} else {
					$gonder['ii'] = 0;
					$gonder['durum'] = false;
					$name_array = $gonder;
				}
				
				if (is_array($name2)) {
					$i_2 = 0;
					$ii_2 = 0;
					foreach($name2 as $name_as2):
					if ($name_as2 == $check2) {
						$ii_2 = $ii_2+1;
					}
					endforeach;
					$gonder['ii'] = $ii_2;
					$gonder['durum'] = true;
					$name2_array = $gonder;
				} else {
					$gonder['ii'] = 0;
					$gonder['durum'] = false;
					$name2_array = $gonder;
				}
				
				if ($name_array['durum'] || $name2_array['durum']) {
					if ($name_array['ii'] >= 1 || $name2_array['ii'] >= 1)
					{
						echo $class;
					}
					else
					{
						echo '';
					}	
				} else {
					if ($check == $name || $check2 == $name2)
					{
						echo $class;
					}
					else
					{
						echo '';
					}
				}			
			
			break;
			
			case 3:
			
				if (is_array($name)) {
					$i = 0;
					$ii = 0;
					foreach($name as $name_as):
					if ($name_as == $check) {
						$ii = $ii+1;
					}
					endforeach;
					$gonder['ii'] = $ii;
					$gonder['durum'] = true;
					$name_array = $gonder;
				} else {
					$gonder['ii'] = 0;
					$gonder['durum'] = false;
					$name_array = $gonder;
				}
				
				if (is_array($name2)) {
					$i_2 = 0;
					$ii_2 = 0;
					foreach($name2 as $name_as2):
					if ($name_as2 == $check2) {
						$ii_2 = $ii_2+1;
					}
					endforeach;
					$gonder['ii'] = $ii_2;
					$gonder['durum'] = true;
					$name2_array = $gonder;
				} else {
					$gonder['ii'] = 0;
					$gonder['durum'] = false;
					$name2_array = $gonder;
				}
				
				if ($name_array['durum'] || $name2_array['durum']) {
					if ($name_array['ii'] >= 1 || $name2_array['ii'] >= 1)
					{
						echo $class;
					}
					else
					{
						echo '';
					}	
				} else {
					if ($check == $name && $check2 == $name2)
					{
						echo $class;
					}
					else
					{
						echo '';
					}
				}
			break;	
			
			case 4:
			
				$kontrol = explode('-', $CI->uri->segment('1'));
			
				if ($check == $name || $kontrol[$uri2] == $name2)
				{
					echo $class;
				}
				else
				{
					echo '';
				}			
			
			break;	
			
			case 5:
			
				$kontrol = explode('-', $CI->uri->segment('1'));
				
				if ($kontrol[$uri] == $name || $kontrol[$uri2] == $name2)
				{
					echo $class;
				}
				else
				{
					echo '';
				}			
			
			break;	
			endswitch;
	}
}