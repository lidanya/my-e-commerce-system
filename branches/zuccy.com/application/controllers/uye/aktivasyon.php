<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class aktivasyon extends Public_Controller {
	function __construct()
	{
		parent::__construct();
	}
	
	
	function sifre($username = "", $key = "")
	{
		// Get username and key
//		$username = $this->uri->segment(4);
//		$key = $this->uri->segment(5);

		// Reset password
		if($this->dx_auth->reset_password($username, $key))
		{
			$mesajlar['baslik'] = 'Aktivasyon Başarılı';
			$mesajlar['icerik'] = 'Yeni şifreniz başarıyla aktif edildi. Artık sistemimize yeni şifrenizle giriş yapabilir, Kullanıcı Bilgilerim bölümünden şifrenizi değiştirebilirsiniz.';
			$this->session->set_flashdata('mesajlar', $mesajlar);
			redirect('site/mesaj?tip=1');
		} else {
			$mesajlar['baslik'] = 'Aktivasyon Başarısız';
			$mesajlar['icerik'] = 'Yeni şifreniz teknik bir nedenden dolayı aktif ediemiyor. Aktivasyon linki daha önce kullanılmış olabilir ya da geçerliliğini yitirmiş olabilir. Daha fazla bilgi için <a href="site/iletisim">buradan</a> yardım alabilirsiniz.';
			$this->session->set_flashdata('mesajlar', $mesajlar);
			redirect('site/mesaj?tip=2');
		}
	}
}
?>