<?php
    $left_data = array("sub_category"=>$sub_category,"markalarimiz"=>$markalarimiz);
	$this->load->view(tema() . 'header');
	$this->load->view(tema() . 'left',$left_data);
	echo $content;
	$this->load->view(tema() . 'right');
	$this->load->view(tema() . 'footer');
?>