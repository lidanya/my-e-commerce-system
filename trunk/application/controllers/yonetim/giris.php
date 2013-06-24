<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class giris extends Admin_Controller
{
	var $redirect_url;
	
	function __construct()
	{
		parent::__construct();	

		$this->load->model('yonetim/yonetim_model','yonetimModel');

		if ( $this->redirect_url != '' )
		{
			$this->session->set_flashdata('login_redirect', $this->redirect_url);
		}
		else
		{
			$this->session->keep_flashdata('login_redirect');
		}
	}

	function cikis()
	{
		$this->dx_auth->logout();
		redirect('yonetim/giris');
	}
	
	function index()
	{
		if ( ! $this->dx_auth->is_logged_in())
		{
			$val = $this->validation;

			$rules['username']		= 'trim|required|xss_clean';
			$rules['password']		= 'trim|required|xss_clean';

			$fields['username']		= 'Kullanıcı Adı';
			$fields['password']		= 'Parola';

			$val->set_rules($rules);
			$val->set_fields($fields);

			$request_uri = ($this->session->flashdata('login_redirect') != '') ? $this->session->flashdata('login_redirect') : 'yonetim';

			if ($val->run() AND $this->dx_auth->login($val->username, $val->password, 1))
			{
				redirect($request_uri, 'location');
			}
			else
			{
				if ( $this->redirect_url != '' )
				{
					$this->session->set_flashdata('login_redirect', $this->redirect_url);
				}
				else
				{
					$this->session->keep_flashdata('login_redirect');
				}

				if ($this->dx_auth->is_banned())
				{
					$this->dx_auth->deny_access('banned');
				}
				else
				{
					$this->load->view('yonetim/giris/giris_view');
				}
			}
		}
		else
		{
			if (!$this->dx_auth->is_role('admin'))
			{  
				redirect('');
			} else {
				redirect('yonetim');
			}
		}
	}
	
	function yetki_yok()
	{
		$this->load->view('yonetim/yetki_yok_view');
	}
}