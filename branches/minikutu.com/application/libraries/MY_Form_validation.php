<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class MY_Form_validation extends CI_Form_validation {

	function valid_website($str)
	{
		return ( ! preg_match("/^([www]+)(\.[a-z0-9\+_\-]+)*.([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
	}

}