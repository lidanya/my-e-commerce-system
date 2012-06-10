<?php

	// Information Types
	$config['information_types'] = array(
		'news' => array(
				'title'			=> 'Haber',
				'url'			=> '{url}--news',
				'cat_url'		=> '{url}--newsc',
				'all_url'		=> 'information/all_detail/news'
			),
		'announcement' => array(
				'title' 		=> 'Duyuru',
				'url'			=> '{url}--announcement',
				'cat_url'		=> '{url}--announcementc',
				'all_url'		=> 'information/all_detail/announcement'
			),
		'information' => array(
				'title'			=> 'Bilgi Sayfası',
				'url'			=> '{url}--information',
				'cat_url'		=> '{url}--informationc',
				'all_url'		=> 'information/all_detail/information'
			),
		'manufacturer' => array(
				'title'			=> 'Markalar',
				'url'			=> '{url}--manufacturer',
				'cat_url'		=> '{url}--manufacturerc'
			),
	);

?>