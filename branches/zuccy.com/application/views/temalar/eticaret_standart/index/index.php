<?php
	$this->load->view(tema() . 'header');
	$this->load->view(tema() . 'anasayfa_slider');
	$this->load->view(tema() . 'menu');
	$this->load->view(tema() . 'infobar');
	echo $content;
	$this->load->view(tema() . 'right');
	$this->load->view(tema() . 'footer');
?>