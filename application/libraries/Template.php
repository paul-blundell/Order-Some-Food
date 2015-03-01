<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Template Library.
 * This library will load a view into another template view.
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Template
{
	private $template_data = array();
	private $ci;
		
        public function __construct()
        {
                $this->ci = & get_instance();
		// Get the takeaway model
                $this->ci->load->model('takeaway_model','takeawaymodel');
        }

        /**
	 * Set the content at a specified location
	 *
	 * @param String name The name of the location to replace
	 * @param String value The value of what to replace the name with
	 */
        function set($name, $value)
        {
                $this->template_data[$name] = $value;
        }
        
	/**
	 * Load the view inside the template
	 *
	 * @param String view The name of the view to load
	 * @param Array view_data Data that the view may be accessing
	 * @param Boolean custom Is this loading the custom template or the normal template
	 */
        function load($view = '' , $view_data = array(), $custom = FALSE)
        {
		// Load the standard template
                $template = 'theme/template';
		
		// If custom is set, then we want the custom template (User defined styles)
                if ($custom) {
				$template = 'theme/custom';
				$takeaway = $this->ci->takeawaymodel->getTakeawayFromShortName($custom);
                
				// Get the values set by the takeaway and override the CSS
				$css = '<style type="text/css">';
				// If a background colour or image specified
				if ($takeaway->background) {
						if (strpos($takeaway->background,"http://") === false)
								$css .= '#wrapper { background: '.$takeaway->background.'; }';
						else
								$css .= '#wrapper { background: url('.$takeaway->background.') top center; }';
				}
				
				if ($takeaway->fontsize)
						$css .= '#page { font-size: '.$takeaway->fontsize.'px; }';
						
				if ($takeaway->buttons)
						$css .= '.styledbutton { background-color: '.$takeaway->buttons.'; }';
						
				if ($takeaway->categoryColour) {
						$css .= '.category .head { background-color: '.$takeaway->categoryColour.'; }';
						$css .= '.menu-left .column-head, .menu-right .column-head { background-color: '.$takeaway->categoryColour.'; }';
				}
						
				$css .= '</style>';
				
				// Set the CSS and takeaway name
				$view_data['customCss'] = $css;
				$view_data['takeawayname'] = $takeaway->name;
				$view_data['ads'] = ($takeaway->type == 2) ? false : true;
                }
                
		// Load the correct view into the template file
                $this->set('contents', $this->ci->load->view($view, $view_data, TRUE));			
                return $this->ci->load->view($template, $this->template_data, FALSE);
        }
}
