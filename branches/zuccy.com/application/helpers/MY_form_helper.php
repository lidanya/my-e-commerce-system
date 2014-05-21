<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/

if ( ! function_exists('form_open_ssl'))
{
	function form_open_ssl($action = '', $attributes = '', $hidden = array())
	{
		$CI =& get_instance();

		if ($attributes == '')
		{
			$attributes = 'method="post"';
		}

		$action = ( strpos($action, '://') === FALSE) ? ssl_url($action) : $action;

		$form = '<form action="'.$action.'"';
	
		$form .= _attributes_to_string($attributes, TRUE);
	
		$form .= '>';

		if (is_array($hidden) AND count($hidden) > 0)
		{
			$form .= form_hidden($hidden);
		}

		return $form;
	}
}

if ( ! function_exists('form_dropdown_data'))
{
	function form_dropdown_data($name = '', $sql, $selected = array())
	{
	    $CI =& get_instance();
	    if(! is_array($selected))
	    {
	        $selected = array($selected);
	    }
	
	    // If no selected state was submitted we will attempt to set it automatically
	    if(count($selected) === 0)
	    {
	        // If the form name appears in the $_POST array we have a winner!
	        if(isset($_POST[$name]))
	        {
	            $selected = array($_POST[$name]);
	        }
	    }
	    if ($extra != '') $extra = ' '.$extra;
	    
	    $multiple = (count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';
	    $query=$CI->db->query($sql);
	    return $query->result_array();
	}
}

if ( ! function_exists('form_dropdown_from_db'))
{
    function form_dropdown_from_db($name = '', $sql, $once = array(), $selected = array(), $extra = '')
    {
        $CI =& get_instance();
        if ( ! is_array($selected))
        {
            $selected = array($selected);
        }

        // If no selected state was submitted we will attempt to set it automatically
        if (count($selected) === 0)
        {
            // If the form name appears in the $_POST array we have a winner!
            if (isset($_POST[$name]))
            {
                $selected = array($_POST[$name]);
            }
        }

        if ($extra != '') $extra = ' '.$extra;

        $multiple = (count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

        $form = '<select name="'.$name.'"'.$extra.$multiple.">\n";
        $query=$CI->db->query($sql);
        
        if ($once)
        {
        	foreach($once as $once_k => $once_s):
        		$sel_1 = (in_array($once_k, $selected))?' selected="selected"':'';
        		$form .= '<option value="'.$once_k.'"'.$sel_1.'>'.$once_s."</option>\n";
        	endforeach;
        }
        
        if ($query->num_rows() > 0)
        {
           foreach ($query->result_array() as $row)
           {
                  $values = array_values($row);
                  if (count($values)===2){
                    $key = (string) $values[0];
                    $val = (string) $values[1];
                    //$this->option($values[0], $values[1]);
                  }

                $sel = (in_array($key, $selected))?' selected="selected"':'';

                $form .= '<option value="'.$key.'"'.$sel.'>'.$val."</option>\n";
           }
        }
        $form .= '</select>';
        return $form;
    }
}

if ( ! function_exists('form_dropdown_from_dbadr'))
{
    function form_dropdown_from_dbadr($name = '', $sql, $once = array(), $selected = array(), $extra = '')
    {
        $CI =& get_instance();
        if ( ! is_array($selected))
        {
            $selected = array($selected);
        }

        // If no selected state was submitted we will attempt to set it automatically
        if (count($selected) === 0)
        {
            // If the form name appears in the $_POST array we have a winner!
            if (isset($_POST[$name]))
            {
                $selected = array($_POST[$name]);
            }
        }

        if ($extra != '') $extra = ' '.$extra;

        $multiple = (count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

        $form = '<select name="'.$name.'"'.$extra.$multiple.">\n";
        $query=$CI->db->query($sql);
        
        if ($once)
        {
        	foreach($once as $once_k => $once_s):
        		$sel_1 = (in_array($once_k, $selected))?' selected="selected"':'';
        		$form .= '<option value="'.$once_k.'"'.$sel_1.'>'.$once_s."</option>\n";
        	endforeach;
        }
        
        if ($query->num_rows() > 0)
        {
           foreach ($query->result_array() as $row)
           {
                  $values = array_values($row);
                  if (count($values)===2){
                    $key = (string) $values[0];
                    $val = (string) $values[1];
                    //$this->option($values[0], $values[1]);
                  }

                $sel = (in_array($key, $selected))?' selected="selected"':'';

                $form .= '<option value="'.$key.'"'.$sel.'>'.$key."</option>\n";
           }
        }
        $form .= '</select>';
        return $form;
    }
}