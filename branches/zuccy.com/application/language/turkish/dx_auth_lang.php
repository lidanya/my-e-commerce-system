<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

$lang['auth_login_incorrect_password'] = "Şifreniz yanlıştır..";
$lang['auth_login_username_not_exist'] = "Kullanıcı adı bulunamadı.";

$lang['auth_username_or_email_not_exist'] = "Kullanıcı adı veya E-Mail adresi bulunamadı..";
$lang['auth_not_activated'] = "Hesabınız aktif edilemedi. E-Mail adresinizi kontrol ediniz.";
$lang['auth_request_sent'] = "Yeni şifre talebinde bulunulmuştur. E-Mail adresinizi kontrol ediniz.";
$lang['auth_incorrect_old_password'] = "Eski şifreniz yanlıştır.";
$lang['auth_incorrect_password'] = "Şifreniz yanlıştır.";

// Email subject
$lang['auth_account_subject'] = "Üyelik Bilgileri";
$lang['auth_activate_subject'] = "Üyelik Aktivasyon";
$lang['auth_forgot_password_subject'] = "Yeni Şifre Talebi";

// Email content
$lang['auth_account_content'] = "%s hoşgeldiniz.

Kayıt olduğunuz için teşekkür ederiz. Hesabınız başarılı bir şekilde oluşturuldu.

Kullnıcı Adı veya E-Mail adresiyle giriş yapabilirsiniz:

E-Mail Adresi : %s
Şifre	      : %s

 %s linkini tıklayarak giriş yapabilirsiniz.

Burada kaldığınız sürece iyi vakit geçireceğinizi umuyoruz  :)

Saygılarımızla,
%s";

$lang['auth_activate_content'] = "%s hoşgeldiniz,

Hesabınızı aktif etmek için aşağıdaki linki tıklayınız:
%s

Hesabınızı %s saat içinde aktif etmeniz gerekmektedir, aksi halde önkaydınız iptal olacaktır ve yeniden başvuru yapmak zorunda kalacaksınız.

Kullanıcı Adı veya E-Mail adresinizle giriş yapabilirsiniz.
Giriş bilgileriniz aşağıdadır:

E-Mail Adresi : %s
Şifre         : %s

Burada kaldığınız sürece iyi vakit geçireceğinizi umuyoruz  :)

Saygılarımızla,
%s";

$lang['auth_forgot_password_content'] = "%s,

Giriş şifrenizi unuttuğunuzdan dolayı yeni şifre talep ettiniz.
Aşağıdaki linki tıklayarak şifre değiştirme işleminizi tamamlayabilirsiniz:
%s

Yeni Şifre      : %s
Aktivasyon Kodu : %s

Başarılı bir şekilde giriş yaptıktan sonra isterseniz şifrenizi değiştirebilirsiniz.

Herhangi bir problemde lütfen bizimle iletişime geçin %s.

Saygılarımızla,
%s";

/* End of file dx_auth_lang.php */
/* Location: ./application/language/english/dx_auth_lang.php */