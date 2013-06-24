<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * $_GET süper global indeki değeri CI $this->input->get() metodu yardımıyla alır.
 *
 * @param string $name
 * @param string $default (sözkonusu değer yoksa alternatif bir değer girebilirsiniz)
 * @param bool $escape verinin escape edilmesini isterseniz TRUE yapınız
 * @return string or bool
 *
 */
function _get($name, $default = FALSE, $escape = TRUE) {

    $CI =& get_instance();
    return ($CI->input->get($name, $escape) !== FALSE && $CI->input->get($name, $escape) != '')? $CI->input->get($name, $escape) : $default;
}



/**
 * $_POST süper global indeki değeri CI $this->input->post() metodu yardımıyla alır.
 * 
 * @author Serkan Koch
 * 
 * @param string $name
 * @param string $default (sözkonusu değer yoksa alternatif bir değer girebilirsiniz)
 * @param bool $escape verinin escape edilmesini isterseniz TRUE yapınız
 * @return string or bool
 */

function _post($name, $default = FALSE, $escape = FALSE) {

    $CI =& get_instance();
    return ($CI->input->post($name, $escape) !== FALSE && $CI->input->post($name, $escape) != '')? $CI->input->post($name, $escape) : $default;
}

/* End of file input_helper.php */

