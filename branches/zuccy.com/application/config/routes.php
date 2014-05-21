<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller']  								= "site";
$route['404_override']										= "site/page_not_found";

/* Face Kategoriler */
$route['(\w{2})/face_app/(:any)--category']						= "face_app/kategori/detay/index/$2";
$route['(\w{2})/face_app/(:any)--category/(:any)']					= "face_app/kategori/detay/index/$2/$3";
$route['(\w{2})/face_app/(:any)--category/(:any)/(:any)']			= "face_app/kategori/detay/index/$2/$3/$4";
/* Face Kategoriler */

/* Face Ürünler */
$route['(\w{2})/face_app/(:any)--product']						= "face_app/urun/detay/index/$2";
$route['(\w{2})/face_app/urun/arama_sonuc/(:any)']			= 'face_app/urun/arama_sonuc/index/$2';
// indirimli ürünler
$route['(\w{2})/face_app/urun/indirimli/index/(:any)']		= 'face_app/urun/indirimli/index/$2';
$route['(\w{2})/face_app/urun/indirimli/(:any)']			= 'face_app/urun/indirimli/index/$2';
// kampanyalı ürünler
$route['(\w{2})/face_app/urun/kampanyali/index/(:any)']		= 'face_app/urun/kampanyali/index/$2';
$route['(\w{2})/face_app/urun/kampanyali/(:any)']			= 'face_app/urun/kampanyali/index/$2';
// yeni ürünler
$route['(\w{2})/face_app/urun/yeni/index/(:any)']			= 'face_app/urun/yeni/index/$2';
$route['(\w{2})/face_app/urun/yeni/(:any)']					= 'face_app/urun/yeni/index/$2';
/* Face Ürünler */

/* Face Duyurular & Haberler */
$route['(\w{2})/face_app/(:any)--announcement']						= 'face_app/information/detail/announcement/$2';
$route['(\w{2})/face_app/(:any)--information']						= 'face_app/information/detail/news/$2';
$route['(\w{2})/face_app/(:any)--information']						= 'face_app/information/detail/information/$2';
/* Face Duyurular & Haberler */

/* Face Markalar */
$route['(\w{2})/face_app/(:any)--manufacturer']						= 'face_app/manufacturer/detail/index/$2';
$route['(\w{2})/face_app/(:any)--manufacturer/(:any)']				= 'face_app/manufacturer/detail/index/$2/$3';
$route['(\w{2})/face_app/(:any)--manufacturer/(:any)/(:num)']			= 'face_app/manufacturer/detail/index/$2/$3/$4';
/* Face Markalar */

/* Kategoriler */
$route['(\w{2})/(:any)--category']									= "kategori/detay/index/$2";
$route['(\w{2})/(:any)--category/(:any)']							= "kategori/detay/index/$2/$3";
$route['(\w{2})/(:any)--category/(:any)/(:any)']					= "kategori/detay/index/$2/$3/$4";
/* Kategoriler */

/* Ürünler */
$route['(\w{2})/(:any)--product']									= "urun/detay/index/$2";
$route['(\w{2})/urun/arama_sonuc/(:any)']					= 'urun/arama_sonuc/index/$2';
// indirimli ürünler
$route['(\w{2})/urun/indirimli/index/(:any)']				= 'urun/indirimli/index/$2';
$route['(\w{2})/urun/indirimli/(:any)']						= 'urun/indirimli/index/$2';
// kampanyalı ürünler
$route['(\w{2})/urun/kampanyali/index/(:any)']				= 'urun/kampanyali/index/$2';
$route['(\w{2})/urun/kampanyali/(:any)']					= 'urun/kampanyali/index/$2';
// yeni ürünler
$route['(\w{2})/urun/yeni/index/(:any)']					= 'urun/yeni/index/$2';
$route['(\w{2})/urun/yeni/(:any)']							= 'urun/yeni/index/$2';
/* Ürünler */

/* Duyurular & Haberler */
$route['(\w{2})/(:any)--announcement']								= 'information/detail/announcement/$2';
$route['(\w{2})/(:any)--news']								= 'information/detail/news/$2';
$route['(\w{2})/(:any)--information']								= 'information/detail/information/$2';
/* Duyurular & Haberler */

/* Markalar */
$route['(\w{2})/(:any)--manufacturer']								= 'manufacturer/detail/index/$2';
$route['(\w{2})/(:any)--manufacturer/(:any)']							= 'manufacturer/detail/index/$2/$3';
$route['(\w{2})/(:any)--manufacturer/(:any)/(:num)']					= 'manufacturer/detail/index/$2/$3/$4';
/* Markalar */

$route['(\w{2})/odeme/adim_2/(:any)/(:any)']				= "odeme/adim_2/index/$2/$3";
$route['(\w{2})/odeme/adim_3/(:any)/(:any)']				= "odeme/adim_3/index/$2/$3";

$route['(\w{2})/uye/cagri/(:num)']							= 'uye/cagri/index/$2';
$route['(\w{2})/uye/sifre_aktivasyon/(:any)']				= 'uye/sifre_aktivasyon/index/$2/$3';

/* Yonetim */
$route['yonetim/icerik_yonetimi/haberler/ok']				= "yonetim/icerik_yonetimi/haberler/listele";
$route['yonetim/icerik_yonetimi/duyurular/ok']				= "yonetim/icerik_yonetimi/duyurular/listele";

// Çağrilar
$route['yonetim/cagri/cevap_yaz/(:num)']					= "yonetim/cagri/cevap_yaz/index/$2";

$route['(\w{2})/(.*)']										= "$2";
$route['(\w{2})']											= $route['default_controller'];


/* End of file routes.php */
/* Location: ./application/config/routes.php */