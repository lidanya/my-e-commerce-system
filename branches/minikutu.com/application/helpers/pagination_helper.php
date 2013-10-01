<?php defined('BASEPATH') OR exit('No direct script access allowed.');

function create_pagination($uri, $total_rows, $limit = NULL, $uri_segment = 4, $type = 'site', $query_string = FALSE)
{
	$ci =& get_instance();
	$ci->load->library('daynex_pagination');

	$current_page					= $ci->uri->segment($uri_segment, 0);

	$config['total_rows']			= $total_rows;
	$config['per_page']				= $limit === NULL ? 20 : $limit;
	$config['uri_segment']			= $uri_segment;
	$config['page_query_string']	= $query_string;

	$config['base_url']				= $uri;
	$config['total_rows']			= $total_rows;
	$config['per_page']				= $limit;
	$config['uri_segment']			= $uri_segment;
	$config['num_links']			= 6;

	$config['full_tag_open']		= '<div class="liste_sag saga"><ul>';
	$config['full_tag_close']		= '</ul></div>';

	$config['first_link']			= '<img src="'. site_resim() .'liste_bas.png" alt="" style="margin:3px 0;" />';
	$config['first_tag_open']		= '<li><span>';
	$config['first_tag_close']		= '</span></li>';
	if ($type == 'face') {
		$config['first_a_class']	= 'class="buton" target="_top"';
	} else {
		$config['first_a_class']	= 'class="buton"';
	}

	$config['last_link']			= '<img src="'. site_resim() .'liste_son.png" alt="" style="margin:3px 0;" />';
	$config['last_tag_open']		= '<li><span>';
	$config['last_tag_close']		= '</span></li>';
	if ($type == 'face') {
		$config['last_a_class']		= 'class="buton" target="_top"';
	} else {
		$config['last_a_class']		= 'class="buton"';
	}

	$config['next_link']			= '<img src="'. site_resim() .'liste_ileri.png" alt="" style="margin:3px 0;" />';
	$config['next_tag_open']		= '<li><span>';
	$config['next_tag_close']		= '</span></li>';
	if ($type == 'face') {
		$config['next_a_class']		= 'class="buton" target="_top"';
	} else {
		$config['next_a_class']		= 'class="buton"';
	}

	$config['prev_link']			= '<img src="'. site_resim() .'liste_geri.png" alt="" style="margin:3px 0;" />';
	$config['prev_tag_open']		= '<li><span>';
	$config['prev_tag_close']		= '</span></li>';
	if ($type == 'face') {
		$config['prev_a_class']		= 'class="buton" target="_top"';
	} else {
		$config['prev_a_class']		= 'class="buton"';
	}

	$config['cur_tag_open']			= '<li class="l_aktif">';
	$config['cur_tag_close']		= '</li>';
	$config['cur_a_class']			= 'class="l_sayfa"';

	$config['num_tag_open']			= '<li><span>';
	$config['num_tag_close']		= '</span></li>';
	if ($type == 'face') {
		$config['num_a_class']		= 'class="l_sayfa" target="_top"';
	} else {
		$config['num_a_class']		= 'class="l_sayfa"';
	}

	$ci->daynex_pagination->initialize($config);

	return array(
		'current_page' 				=> $current_page,
		'per_page' 					=> $config['per_page'],
		'limit'						=> array($config['per_page'], $current_page),
		'links' 					=> $ci->daynex_pagination->create_links()
	);
}

/* End of file pagination_helper.php */