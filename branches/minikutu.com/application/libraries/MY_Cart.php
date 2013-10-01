<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class MY_Cart extends CI_Cart {

	// These are the regular expression rules that we use to validate the product ID and product name
	var $product_id_rules	= '\.\:\-_\/\'\(\) a-üöçşığz A-ÜÖÇŞİĞZ 0-9'; // alpha-numeric, dashes, underscores, or periods
	var $product_name_rules	= '\.\:\-_\/\'\(\) a-üöçşığz A-ÜÖÇŞİĞZ 0-9'; // alpha-numeric, dashes, underscores, colons or periods
	
	// Private variables.  Do not change!
	var $CI;
	var $_cart_contents	= array();


	/**
	 * Shopping Class Constructor
	 *
	 * The constructor loads the Session class, used to store the shopping cart contents.
	 */		
	public function __construct($params = array())
	{	
		// Set the super object to a local variable for use later
		$this->CI =& get_instance();
		
		// Are any config settings being passed manually?  If so, set them
		$config = array();
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				$config[$key] = $val;
			}
		}
		
		// Load the Sessions class
		$this->CI->load->library('session', $config);
			
		// Grab the shopping cart array from the session table, if it exists
		if ($this->CI->session->userdata('cart_contents') !== FALSE)
		{
			$this->_cart_contents = $this->CI->session->userdata('cart_contents');
		}
		else
		{
			// No cart exists so we'll set some base values
			$this->_cart_contents['cart_total']		= 0;		
			$this->_cart_contents['total_items']	= 0;	
			$this->_cart_contents['toplam_kdv']		= 0;
		}
	
		log_message('debug', "Cart Class Initialized");
	}

	// --------------------------------------------------------------------
	
	/**
	 * Insert items into the cart and save it to the session table
	 *
	 * @access	public
	 * @param	array
	 * @return	bool
	 */
	public function insert($items = array())
	{
		//return $items;
		// Was any cart data passed? No? Bah...
		if ( ! is_array($items) OR count($items) == 0)
		{
			log_message('error', 'The insert method must be passed an array containing data.');
			return FALSE;
		}

		// You can either insert a single product using a one-dimensional array, 
		// or multiple products using a multi-dimensional one. The way we
		// determine the array type is by looking for a required array key named "id"
		// at the top level. If it's not found, we will assume it's a multi-dimensional array.
	
		$save_cart = FALSE;		
		if (isset($items['id']))
		{			
			if ($this->_insert($items) == TRUE)
			{
				$save_cart = TRUE;
			}
		}
		else
		{
			foreach ($items as $val)
			{
				if (is_array($val) AND isset($val['id']))
				{
					if ($this->_insert($val) == TRUE)
					{
						$save_cart = TRUE;
					}
				}			
			}
		}

		// Save the cart data if the insert was successful
		if ($save_cart == TRUE)
		{
			$this->_save_cart();
			return TRUE;
		}

		return FALSE;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Insert
	 *
	 * @access	private
	 * @param	array
	 * @return	bool
	 */
	public function _insert($items = array())
	{

		// Was any cart data passed? No? Bah...
		if ( ! is_array($items) OR count($items) == 0)
		{
			log_message('error', 'The insert method must be passed an array containing data.');
			return FALSE;
		}
		
		// --------------------------------------------------------------------
	
		// Does the $items array contain an id, quantity, price, and name?  These are required
		if ( ! isset($items['id']) OR ! isset($items['qty']) OR ! isset($items['price']) OR ! isset($items['name']))
		{
			log_message('error', 'The cart array must contain a product ID, quantity, price, and name.');
			return FALSE;
		}

		// --------------------------------------------------------------------
	
		// Prep the quantity. It can only be a number.  Duh...
		$items['qty'] = trim(preg_replace('/([^0-9])/i', '', $items['qty']));
		// Trim any leading zeros
		$items['qty'] = trim(preg_replace('/(^[0]+)/i', '', $items['qty']));

		// If the quantity is zero or blank there's nothing for us to do
		if ( ! is_numeric($items['qty']) OR $items['qty'] == 0)
		{
			return FALSE;
		}

		// Prep the quantity. It can only be a number.  Duh...
		$items['stok_kodu'] = $items['stok_kodu'];
		$items['kdv_orani'] = $items['kdv_orani'];
		$items['kdv_fiyati'] = $items['kdv_fiyati'];

		// If the quantity is zero or blank there's nothing for us to do
		if (is_null($items['stok_kodu']))
		{
			return FALSE;
		}
		
		// If the quantity is zero or blank there's nothing for us to do
		if (is_null($items['kdv_orani']))
		{
			return FALSE;
		}
		
		// If the quantity is zero or blank there's nothing for us to do
		if (is_null($items['kdv_fiyati']))
		{
			return FALSE;
		}

		// --------------------------------------------------------------------
	
		// Validate the product ID. It can only be alpha-numeric, dashes, underscores or periods
		// Not totally sure we should impose this rule, but it seems prudent to standardize IDs.
		// Note: These can be user-specified by setting the $this->product_id_rules variable.
		/*if ( ! preg_match("/^[".$this->product_id_rules."]+$/i", $items['id']))
		{
			log_message('error', 'Invalid product ID.  The product ID can only contain alpha-numeric characters, dashes, and underscores');
			return FALSE;
		}*/

		// --------------------------------------------------------------------
	
		// Validate the product name. It can only be alpha-numeric, dashes, underscores, colons or periods.
		// Note: These can be user-specified by setting the $this->product_name_rules variable.
		/*if ( ! preg_match("/^[".$this->product_name_rules."]+$/i", $items['name']))
		{
			log_message('error', 'An invalid name was submitted as the product name: '.$items['name'].' The name can only contain alpha-numeric characters, dashes, underscores, colons, and spaces');
			return FALSE;
		}*/

		// --------------------------------------------------------------------

		$items['secenek_fiyat'] = $items['secenek_fiyat'];
		$items['gercek_fiyat'] = $items['price'];
		$items['price'] = $items['price'];

		// Prep the price.  Remove anything that isn't a number or decimal point.
		$items['price'] = trim(preg_replace('/([^0-9\.])/i', '', $items['price']));
		// Trim any leading zeros
		$items['price'] = trim(preg_replace('/(^[0]+)/i', '', $items['price']));

		// Is the price a valid number?
		if ( ! is_numeric($items['price']))
		{
			log_message('error', 'An invalid price was submitted for product ID: '.$items['id']);
			return FALSE;
		}

		// --------------------------------------------------------------------
		
		// We now need to create a unique identifier for the item being inserted into the cart.
		// Every time something is added to the cart it is stored in the master cart array.  
		// Each row in the cart array, however, must have a unique index that identifies not only 
		// a particular product, but makes it possible to store identical products with different options.  
		// For example, what if someone buys two identical t-shirts (same product ID), but in 
		// different sizes?  The product ID (and other attributes, like the name) will be identical for 
		// both sizes because it's the same shirt. The only difference will be the size.
		// Internally, we need to treat identical submissions, but with different options, as a unique product.
		// Our solution is to convert the options array to a string and MD5 it along with the product ID.
		// This becomes the unique "row ID"

		$row_id_olustur = '';
		if (isset($items['options']) AND count($items['options']) > 0) {
			$row_id_olustur .= implode('', $items['options']);
		}

		if (isset($items['secenek']) AND count($items['secenek']) > 0) {
			//echo debug($items['secenek']);
			//$row_id_olustur .= implode('', $items['secenek']);
			foreach($items['secenek'] as $key => $value) {
				if(isset($value['product_option_id'])) {
					$row_id_olustur .= $value['product_option_id'];
				}
				if(isset($value['product_option_value_id'])) {
					$row_id_olustur .= $value['product_option_value_id'];
				}
			}
		}

		if ($row_id_olustur != '') {
			$rowid = md5($items['id'] . $row_id_olustur);
			//log_message('error', $rowid);
		} else {
			// No options were submitted so we simply MD5 the product ID.
			// Technically, we don't need to MD5 the ID in this case, but it makes
			// sense to standardize the format of array indexes for both conditions
			$rowid = md5($items['id']);
		}		

		// --------------------------------------------------------------------

		// Now that we have our unique "row ID", we'll add our cart items to the master array
		
		// let's unset this first, just to make sure our index contains only the data from this submission
		unset($this->_cart_contents[$rowid]);		
		
		// Create a new index with our new row ID
		$this->_cart_contents[$rowid]['rowid'] = $rowid;
	
		// And add the new items to the cart array			
		foreach ($items as $key => $val)
		{
			$this->_cart_contents[$rowid][$key] = $val;
		}

		// Woot!
		return TRUE;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Update the cart
	 *
	 * This function permits the quantity of a given item to be changed. 
	 * Typically it is called from the "view cart" page if a user makes
	 * changes to the quantity before checkout. That array must contain the
	 * product ID and quantity for each item.
	 *
	 * @access	public
	 * @param	array
	 * @param	string
	 * @return	bool
	 */
	public function update($items = array())
	{
		// Was any cart data passed?
		if ( ! is_array($items) OR count($items) == 0)
		{
			return FALSE;
		}
			
		// You can either update a single product using a one-dimensional array, 
		// or multiple products using a multi-dimensional one.  The way we
		// determine the array type is by looking for a required array key named "id".
		// If it's not found we assume it's a multi-dimensional array
		$save_cart = FALSE;
		if (isset($items['rowid']) AND isset($items['qty']))
		{
			if ($this->_update($items) == TRUE)
			{
				$save_cart = TRUE;
			}
		}
		else
		{
			foreach ($items as $val)
			{
				if (is_array($val) AND isset($val['rowid']) AND isset($val['qty']))
				{
					if ($this->_update($val) == TRUE)
					{
						$save_cart = TRUE;
					}
				}			
			}
		}
		
		if(isset($items['tip']))
		{
			$this->_cart_contents[$items['rowid']]['tip'] = $items['tip'];
			$save_cart = TRUE;
		}
		
		if(isset($items['durum']))
		{
			$this->_cart_contents[$items['rowid']]['durum'] = $items['durum'];
			$save_cart = TRUE;
		}
		
		if(isset($items['kdv_orani']))
		{
			$this->_cart_contents[$items['rowid']]['kdv_orani'] = $items['kdv_orani'];
			$save_cart = TRUE;
		}
		
		if(isset($items['kdv_fiyati']))
		{
			$this->_cart_contents[$items['rowid']]['kdv_fiyati'] = $items['kdv_fiyati'];
			$save_cart = TRUE;
		}

		// Save the cart data if the insert was successful
		if ($save_cart == TRUE)
		{
			$this->_save_cart();
			return TRUE;
		}

		return FALSE;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Update the cart
	 *
	 * This function permits the quantity of a given item to be changed. 
	 * Typically it is called from the "view cart" page if a user makes
	 * changes to the quantity before checkout. That array must contain the
	 * product ID and quantity for each item.
	 *
	 * @access	private
	 * @param	array
	 * @return	bool
	 */	
	public function _update($items = array())
	{
		// Without these array indexes there is nothing we can do
		if ( ! isset($items['qty']) OR ! isset($items['rowid']) OR ! isset($this->_cart_contents[$items['rowid']]))
		{
			return FALSE;
		}
		
		// Prep the quantity
		$items['qty'] = preg_replace('/([^0-9])/i', '', $items['qty']);

		// Is the quantity a number?
		if ( ! is_numeric($items['qty']))
		{
			return FALSE;
		}
		
		// Is the new quantity different than what is already saved in the cart?
		// If it's the same there's nothing to do
		if ($this->_cart_contents[$items['rowid']]['qty'] == $items['qty'])
		{
			return FALSE;
		}

		// Is the quantity zero?  If so we will remove the item from the cart.
		// If the quantity is greater than zero we are updating
		if ($items['qty'] == 0)
		{
			unset($this->_cart_contents[$items['rowid']]);		
		}
		else
		{
			$allowed_qty = $this->_cart_contents[$items['rowid']]['allowed_qty'];
			if ($allowed_qty < $items['qty']) {
				$_qty = $allowed_qty;
			} else {
				$_qty = $items['qty'];
			}
			$this->_cart_contents[$items['rowid']]['qty'] = $_qty;
		}

		if(isset($items['durum']))
		{
			$this->_cart_contents[$items['rowid']]['durum'] = $items['durum'];
		}
		
		if(isset($items['kdv_fiyati']))
		{
			$this->_cart_contents[$items['rowid']]['kdv_fiyati'] = $items['kdv_fiyati'];
		}
		
		if(isset($items['kdv_orani']))
		{
			$this->_cart_contents[$items['rowid']]['kdv_orani'] = $items['kdv_orani'];
		}

		return TRUE;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Save the cart array to the session DB
	 *
	 * @access	private
	 * @return	bool
	 */
	public function _save_cart()
	{
		// Unset these so our total can be calculated correctly below
		unset($this->_cart_contents['total_items']);
		unset($this->_cart_contents['cart_total']);
		unset($this->_cart_contents['toplam_kdv']);

		// Lets add up the individual prices and set the cart sub-total
		$total = 0;
		foreach ($this->_cart_contents as $key => $val) {
			// We make sure the array contains the proper indexes
			if ( ! is_array($val) OR ! isset($val['price']) OR ! isset($val['qty'])) {
				continue;
			}

			/*if($val['durum']) {
				$qty													= $val['qty'];
				$secenek_fiyat											= $val['secenek_fiyat'];
				$total													+= $val['price'] * $val['qty'];
				$this->_cart_contents[$key]['gercek_fiyat_subtotal']	= $val['gercek_fiyat'] * $val['qty'];
				$this->_cart_contents[$key]['subtotal']					= $val['price'] * $val['qty'];
			}*/

			if($val['durum']) {
				$fiyat_bilgi											= fiyat_hesapla($val['stok_kodu'], $val['qty'], kur_oku('usd'), kur_oku('eur'));
				$secenek_fiyat											= $val['secenek_fiyat'];
				$total													+= ($fiyat_bilgi['fiyat'] + $val['secenek_fiyat']) * $val['qty'];
				$this->_cart_contents[$key]['gercek_fiyat_subtotal']	= $val['gercek_fiyat'] * $val['qty'];
				$this->_cart_contents[$key]['subtotal']					= ($fiyat_bilgi['fiyat'] + $val['secenek_fiyat']) * $val['qty'];
			}
		}

		/*$toplam_kdv_fiyati = 0;
		foreach ($this->_cart_contents as $key => $val) {
			//if($val['durum']) {
			if($val['durum'] != 0 AND $key != 'kupon_tipi' AND $key != 'kupon_kodu' AND $key != 'kupon_degeri' AND $key != 'kupon_mesaj') {
				$fiyat_bilgi			= fiyat_hesapla($val['stok_kodu'], $qty, kur_oku('usd'), kur_oku('eur'));
				$qty					= $val['qty'];
				$secenek_fiyat			= $val['secenek_fiyat'];
				$subtotal				= $val['price'] * $val['qty'];
				$toplam_kdv_fiyati		+= ($subtotal * ($fiyat_bilgi['kdv_orani'] + 1)) - $subtotal;
			}
		}*/

		$toplam_kdv_fiyati = 0;
		foreach ($this->_cart_contents as $key => $val) {
			//if($val['durum']) {
			if($val['durum'] != 0 AND $key != 'kupon_tipi' AND $key != 'kupon_kodu' AND $key != 'kupon_degeri' AND $key != 'kupon_mesaj') {
				$fiyat_bilgi			= fiyat_hesapla($val['stok_kodu'], $val['qty'], kur_oku('usd'), kur_oku('eur'));
				$secenek_fiyat			= $val['secenek_fiyat'];
				$subtotal				= ($fiyat_bilgi['fiyat'] + $val['secenek_fiyat']) * $val['qty'];
				$toplam_kdv_fiyati		+= ($subtotal * ($fiyat_bilgi['kdv_orani'] + 1)) - $subtotal;
			}
		}
		
		//log_message('error', $toplam_kdv_fiyati);

		// Set the cart total and total items.
		$contents								= $this->_cart_contents;
		unset($contents['kupon_kodu']);
		unset($contents['kupon_degeri']);
		unset($contents['kupon_tipi']);
		unset($contents['kupon_mesaj']);
		$this->_cart_contents['total_items']	= count($contents);
		$this->_cart_contents['cart_total']		= $total;
		$this->_cart_contents['toplam_kdv']		= $toplam_kdv_fiyati;

		// Is our cart empty?  If so we delete it from the session
		if (count($this->_cart_contents) <= 2) {
			$this->CI->session->unset_userdata('cart_contents');
			
			// Nothing more to do... coffee time!
			return FALSE;
		}

		// If we made it this far it means that our cart has data.
		// Let's pass it to the Session class so it can be stored
		$this->CI->session->set_userdata(array('cart_contents' => $this->_cart_contents));

		// Woot!
		return TRUE;	
	}

	// --------------------------------------------------------------------
	
	/**
	 * Cart Total
	 *
	 * @access	public
	 * @return	integer
	 */
	public function total()
	{
		return $this->_cart_contents['cart_total'];
	}

	// --------------------------------------------------------------------
	
	/**
	 * Total Items
	 *
	 * Returns the total item count
	 *
	 * @access	public
	 * @return	integer
	 */
	public function total_items()
	{
		return $this->_cart_contents['total_items'];
	}
	
	public function toplam_kdv()
	{
		return $this->_cart_contents['toplam_kdv'];
	}

	// --------------------------------------------------------------------
	
	/**
	 * Cart Contents
	 *
	 * Returns the entire cart array
	 *
	 * @access	public
	 * @return	array
	 */
	public function contents()
	{
		$cart = $this->_cart_contents;

		// Remove these so they don't create a problem when showing the cart table
		unset($cart['total_items']);
		unset($cart['cart_total']);
		unset($cart['toplam_kdv']);
		unset($cart['kupon_kodu']);
		unset($cart['kupon_degeri']);
		unset($cart['kupon_tipi']);
		unset($cart['kupon_mesaj']);
	
		return $cart;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Has options
	 *
	 * Returns TRUE if the rowid passed to this function correlates to an item
	 * that has options associated with it.
	 *
	 * @access	public
	 * @return	array
	 */
	public function has_options($rowid = '')
	{
		if ( ! isset($this->_cart_contents[$rowid]['options']) OR count($this->_cart_contents[$rowid]['options']) === 0)
		{
			return FALSE;
		}
		
		return TRUE;
	}
	
	public function has_secenek($rowid = '')
	{
		if ( ! isset($this->_cart_contents[$rowid]['secenek']) OR count($this->_cart_contents[$rowid]['secenek']) === 0)
		{
			return FALSE;
		}
		
		return TRUE;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Product options
	 *
	 * Returns the an array of options, for a particular product row ID
	 *
	 * @access	public
	 * @return	array
	 */
	public function product_options($rowid = '')
	{
		if ( ! isset($this->_cart_contents[$rowid]['options']))
		{
			return array();
		}

		return $this->_cart_contents[$rowid]['options'];
	}

	public function stok_secenek($rowid = '')
	{
		if ( ! isset($this->_cart_contents[$rowid]['secenek']))
		{
			return array();
		}

		return $this->_cart_contents[$rowid]['secenek'];
	}

	// --------------------------------------------------------------------
	
	/**
	 * Format Number
	 *
	 * Returns the supplied number with commas and a decimal point.
	 *
	 * @access	public
	 * @return	integer
	 */
	public function format_number($n = '')
	{
		/*if ($n == '')
		{
			return '';
		}*/
	
		// Remove anything that isn't a number or decimal point.
		$n = trim(preg_replace('/([^0-9\.])/i', '', $n));
	
		return number_format($n, 2, ',', '.');
	}
		
	// --------------------------------------------------------------------

	public function toplam_indirim()
	{
		$toplam_indirim = 0;
		$kupon_tipi = (isset($this->_cart_contents['kupon_tipi'])) ? $this->_cart_contents['kupon_tipi'] : '';

		if(config('site_ayar_kdv_goster') == '1') {
			$total_price = $this->_cart_contents['cart_total'] + $this->_cart_contents['toplam_kdv'];
		} else {
			$total_price = $this->_cart_contents['cart_total'];
		}

		if($kupon_tipi == '1') {
			$toplam_indirim = ($total_price) * $this->_cart_contents['kupon_degeri'] / 100;
		} else if($kupon_tipi == '2') {
			$toplam_indirim = $this->_cart_contents['kupon_degeri'];
		}

		if($toplam_indirim >= $total_price) {
			return $total_price;
		} else {
			return $toplam_indirim;
		}
	}

	public function kupon_tipi($id = NULL, $unset = FALSE)
	{
		if ( ! $unset) {
			$this->_cart_contents['kupon_tipi'] = $id;
			$this->CI->session->set_userdata(array('cart_contents' => $this->_cart_contents));
		} else {
			unset($this->_cart_contents['kupon_tipi']);
			$this->CI->session->set_userdata(array('cart_contents' => $this->_cart_contents));
		}
	}

	public function kupon_kodu($kod = NULL, $unset = FALSE)
	{
		if ( ! $unset) {
			$this->_cart_contents['kupon_kodu'] = $kod;
			$this->CI->session->set_userdata(array('cart_contents' => $this->_cart_contents));
		} else {
			unset($this->_cart_contents['kupon_kodu']);
			$this->CI->session->set_userdata(array('cart_contents' => $this->_cart_contents));
		}
	}

	public function kupon_degeri($deger = NULL, $unset = FALSE)
	{
		if ( ! $unset) {
			$this->_cart_contents['kupon_degeri'] = $deger;
			$this->CI->session->set_userdata(array('cart_contents' => $this->_cart_contents));
		} else {
			unset($this->_cart_contents['kupon_degeri']);
			$this->CI->session->set_userdata(array('cart_contents' => $this->_cart_contents));
		}
	}

	public function set_kupon_mesaj($deger = NULL, $unset = FALSE)
	{
		if ( ! $unset) {
			$this->_cart_contents['kupon_mesaj'] = $deger;
			$this->CI->session->set_userdata(array('cart_contents' => $this->_cart_contents));
		} else {
			unset($this->_cart_contents['kupon_mesaj']);
			$this->CI->session->set_userdata(array('cart_contents' => $this->_cart_contents));
		}
	}

	public function kuponu_iptal_et()
	{
		$this->kupon_kodu(NULL, TRUE);
		$this->kupon_degeri(NULL, TRUE);
		$this->kupon_tipi(NULL, TRUE);
		$this->set_kupon_mesaj(NULL, TRUE);
	}

	public function get_kupon_kodu()
	{
		$kod = isset($this->_cart_contents['kupon_kodu']) ? $this->_cart_contents['kupon_kodu'] : '';
		return $kod;
	}

	public function kupon_mesaj()
	{
		$mesaj = isset($this->_cart_contents['kupon_mesaj']) ? $this->_cart_contents['kupon_mesaj'] : '';
		return $mesaj;
	}

	/**
	 * Destroy the cart
	 *
	 * Empties the cart and kills the session
	 *
	 * @access	public
	 * @return	null
	 */
	public function destroy()
	{
		unset($this->_cart_contents);

		$this->_cart_contents['cart_total']		= 0;
		$this->_cart_contents['total_items']	= 0;
		$this->_cart_contents['toplam_kdv']		= 0;

		$this->CI->session->unset_userdata('cart_contents');
	}

}

/* End of file isimsiz.php */
/* Location: ./dev10/application/libraries/isimsiz.php */

?>