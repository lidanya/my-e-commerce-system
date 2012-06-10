<?php
	$this->load->view(tema() . 'header');
	$this->load->view(tema() . 'left');
	echo $content;
	$this->load->view(tema() . 'right');
	$this->load->view(tema() . 'footer');
?>