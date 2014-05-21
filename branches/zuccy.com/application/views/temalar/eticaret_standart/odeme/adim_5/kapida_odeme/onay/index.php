<?php
	$this->load->view(tema() . 'header');
	$this->load->view(tema() . 'menu');
	echo $content;
	$this->load->view(tema() . 'footer');
?>