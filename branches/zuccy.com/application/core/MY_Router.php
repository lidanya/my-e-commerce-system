<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class MY_Router extends CI_Router {

	function __construct()
	{
		parent::__construct();
	}

	/**
	 *  Set the directory name
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */
	function set_directory($dir)
	{
		$this->directory = str_replace('.', '', $dir).'/';
	}

	/**
	 * Set the route mapping
	 *
	 * This function determines what should be served based on the URI request,
	 * as well as any "routes" that have been set in the routing config file.
	 *
	 * @access	private
	 * @return	void
	 */
	function _set_routing()
	{
		// Are query strings enabled in the config file?  Normally CI doesn't utilize query strings
		// since URI segments are more search-engine friendly, but they can optionally be used.
		// If this feature is enabled, we will gather the directory/class/method a little differently
		$segments = array();
		if ($this->config->item('enable_query_strings') === TRUE AND isset($_GET[$this->config->item('controller_trigger')]))
		{
			if (isset($_GET[$this->config->item('directory_trigger')]))
			{
				$this->set_directory(trim($this->uri->_filter_uri($_GET[$this->config->item('directory_trigger')])));
				$segments[] = $this->fetch_directory();
			}

			if (isset($_GET[$this->config->item('controller_trigger')]))
			{
				$this->set_class(trim($this->uri->_filter_uri($_GET[$this->config->item('controller_trigger')])));
				$segments[] = $this->fetch_class();
			}

			if (isset($_GET[$this->config->item('function_trigger')]))
			{
				$this->set_method(trim($this->uri->_filter_uri($_GET[$this->config->item('function_trigger')])));
				$segments[] = $this->fetch_method();
			}
		}

		// Load the routes.php file.
		if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/routes'.EXT))
		{
			include(APPPATH.'config/'.ENVIRONMENT.'/routes'.EXT);
		}
		elseif (is_file(APPPATH.'config/routes'.EXT))
		{
			include(APPPATH.'config/routes'.EXT);
		}

		$this->routes = ( ! isset($route) OR ! is_array($route)) ? array() : $route;
		/*$this->_set_dynamic_routes();
		if(isset($last_routes) AND is_array($last_routes)) {
			$this->routes = array_merge($this->routes, $last_routes);
			unset($last_routes);
		}*/
		unset($route);

		// Set the default controller so we can display it in the event
		// the URI doesn't correlated to a valid controller.
		$this->default_controller = ( ! isset($this->routes['default_controller']) OR $this->routes['default_controller'] == '') ? FALSE : strtolower($this->routes['default_controller']);

		// Were there any query string segments?  If so, we'll validate them and bail out since we're done.
		if (count($segments) > 0)
		{
			return $this->_validate_request($segments);
		}

		// Fetch the complete URI string
		$this->uri->_fetch_uri_string();

		// Is there a URI string? If not, the default controller specified in the "routes" file will be shown.
		if ($this->uri->uri_string == '')
		{
			return $this->_set_default_controller();
		}

		// Do we need to remove the URL suffix?
		$this->uri->_remove_url_suffix();

		// Compile the segments into an array
		$this->uri->_explode_segments();

		// Parse any custom routing that may exist
		$this->_parse_routes();

		// Re-index the segment array so that it starts with 1 rather than 0
		$this->uri->_reindex_segments();
	}

	function _validate_request($segments)
	{
		if (!is_dir(APPPATH.'controllers/'.$segments[0]))
		{
			if (file_exists(APPPATH.'controllers/'.$segments[0].EXT))
			{
				return $segments;
			}
		} else {
			if(count($segments) == 1)
			{
				$this->set_directory($this->directory . $segments[0]);
				
				if (file_exists(APPPATH.'controllers/'.$this->directory.$segments[0].EXT))
				{
					$this->set_class($segments[0]);
					$this->set_method('index');
					
					return $segments;
				}
			}

			if (is_dir(APPPATH.'controllers/'.$segments[0]))
			{
				$this->set_directory($segments[0]);
				$segments = array_slice($segments, 1);

				# ----------- ADDED CODE ------------ #
				while(count($segments) > 0 && is_dir(APPPATH.'controllers/'.$this->directory.$segments[0]))
				{
					// Set the directory and remove it from the segment array
					$this->set_directory($this->directory . $segments[0]);
					$segments = array_slice($segments, 1);
				}
				# ----------- END ------------ #

				if (count($segments) > 0)
				{
					if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].EXT))
					{
						show_404($this->fetch_directory().$segments[0]);
					}
				}
				else
				{
					$this->set_class($this->default_controller);
					$this->set_method('index');
	
					if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.EXT))
					{
						$this->directory = '';
						return array();
					}
				}

				return $segments;
			}
		}

		// If we've gotten this far it means that the URI does not correlate to a valid
		// controller class.  We will now see if there is an override
		if ( ! empty($this->routes['404_override']))
		{
			$x = explode('/', $this->routes['404_override']);

			$this->set_class($x[0]);
			$this->set_method(isset($x[1]) ? $x[1] : 'index');

			return $x;
		}

		show_404($segments[0]);
	}
}