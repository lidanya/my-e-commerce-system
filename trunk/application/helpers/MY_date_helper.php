<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/

if ( ! function_exists('time_duration') )
{
	function time_duration($seconds, $use = null, $zeros = false)
	{
	    // Define time periods
	    $periods = array (
	        'years'     => 31556926,
	        'Months'    => 2629743,
	        'weeks'     => 604800,
	        'days'      => 86400,
	        'hours'     => 3600,
	        'minutes'   => 60,
	        'seconds'   => 1
	        );
	
	    // Break into periods
	    $seconds = (float) $seconds;
	    foreach ($periods as $period => $value) {
	        if ($use && strpos($use, $period[0]) === false) {
	            continue;
	        }
	        $count = floor($seconds / $value);
	        if ($count == 0 && !$zeros) {
	            continue;
	        }
	        $segments[strtolower($period)] = $count;
	        $seconds = $seconds % $value;
	    }
	
	    // Build the string
	    foreach ($segments as $key => $value) {
	        $segment_name = substr($key, 0, -1);
	        $segment = $value . ' ' . $segment_name;
	        $array[] = $segment;
	    }
	
	    $str = implode(', ', $array);
	    
	    $aranan = array('year','years','month','months','Month','Months','week','weeks','day','days','hour','hours','minute','minutes','second','seconds');
	    $degisen = array('yıl','yıl','ay','ay','ay','ay','hafta','hafta','gün','gün','saat','saat','dakika','dakika','saniye','saniye');
	    return str_replace($aranan, $degisen, $str);
	}
}

if ( ! function_exists('mdate'))
{
	function mdate($datestr = '', $time = '', $tr_cevir = '')
	{
	
		$tarih_ceviri = array (
		"Mon" => "Pazartesi",
		"Tue" => "Salı",
		"Wed" => "Çarşamba",
		"Thu" => "Perşembe",
		"Fri" => "Cuma",
		"Sat" => "Cumartesi",
		"Sun" => "Pazar",

		"Jan" => "Ocak",
		"Feb" => "Şubat",
		"Mar" => "Mart",
		"Apr" => "Nisan",
		"May" => "Mayıs",
		"Jun" => "Haziran",
		"Jul" => "Temmuz",
		"Aug" => "Ağustos",
		"Sep" => "Eylül",
		"Oct" => "Ekim",
		"Nov" => "Kasım",
		"Dec" => "Aralık");
		
		$tarih_ceviri_2 = array (
		"Mon" => "Monday",
		"Tue" => "Tuesday",
		"Wed" => "Wednesday",
		"Thu" => "Thursday",
		"Fri" => "Friday",
		"Sat" => "Saturday",
		"Sun" => "Sunday",

		"Jan" => "January",
		"Feb" => "February",
		"Mar" => "March",
		"Apr" => "April",
		"May" => "May",
		"Jun" => "June",
		"Jul" => "July",
		"Aug" => "August",
		"Sep" => "September",
		"Oct" => "October",
		"Nov" => "November",
		"Dec" => "December");

		if ($datestr == '')
			return '';

		if ($time == '')
			$time = now();

		$datestr = str_replace('%\\', '', preg_replace("/([a-z]+?){1}/i", "\\\\\\1", $datestr));
		$tarih = date($datestr, $time);
		
		if (isset($tr_cevir))
		{
			if ($tr_cevir == 'tr')
			{
				return strtr($tarih, $tarih_ceviri);
			}
			elseif ($tr_cevir == 'en')
			{
				return strtr($tarih, $tarih_ceviri_2);
			}
			else
			{
				return gmdate($datestr, $time);
			}
		} else {
			return gmdate($datestr, $time);
		}

	}
}

if ( ! function_exists('standard_date'))
{
	function standard_date($fmt = 'DATE_TR', $time = '', $tr = 'bos')
	{

		$formats = array(
						'DATE_ATOM'		=>	'%Y-%m-%dT%H:%i:%s%Q',
						'DATE_COOKIE'	=>	'%l, %d-%M-%y %H:%i:%s UTC',
						'DATE_ISO8601'	=>	'%Y-%m-%dT%H:%i:%s%O',
						'DATE_RFC822'	=>	'%D, %d %M %y %H:%i:%s %O',
						'DATE_TR'		=>	'%d %M %Y, %D %H:%i:%s',
						'DATE_TR1'		=>	'%d %M %Y',
						'DATE_TR2'		=>	'%d %M %Y, %D',
						'DATE_TR3'		=>	'%F %d, %Y %H:%i:%s',
						'DATE_TR4'		=>	'%d %M %Y, %H:%i:%s',
						'DATE_TR5'		=>	'%m/%d/%Y',
						'DATE_TR6'		=>	'%Y-%m-%d',
						'DATE_TR7'		=>	'%d-%m-%Y - %H:%i:%s',
						'DATE_RFC850'	=>	'%l, %d-%M-%y %H:%m:%i UTC',
						'DATE_RFC1036'	=>	'%D, %d %M %y %H:%i:%s %O',
						'DATE_RFC1123'	=>	'%D, %d %M %Y %H:%i:%s %O',
						'DATE_RSS'		=>	'%D, %d %M %Y %H:%i:%s %O',
						'DATE_W3C'		=>	'%Y-%m-%dT%H:%i:%s%Q',
						'DATE_SAAT'		=>	'%H:%i:%s',
						'DATE_MYSQL'	=>	'%Y-%m-%d %H:%i:%s',
						);

		if ( ! isset($formats[$fmt]))
		{
			return FALSE;
		}
		
		if ($tr == 'tr')
		{
			return mdate($formats[$fmt], $time, 'tr');
		}
		elseif ($tr == 'en')
		{
			return mdate($formats[$fmt], $time, 'en');
		}
		elseif ($tr == 'bos' || $tr == '')
		{
			return mdate($formats[$fmt], $time);
		}

		
	}
}