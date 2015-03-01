<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Takeaway Library.
 * Used to provide common functions for access takeaway information.
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Takeaways
{
    private $ci;
    
    public function __construct()
    {
            $this->ci = & get_instance();
    }
    
    /**
     * Get the details of a takeaway
     *
     * @param Integer takeaway ID
     */
    public function getDetails($id)
    {
        // Load the takeaway model
        $this->ci->load->model('takeaway_model','takeawaymodel');
        
        // Get the details for the takeaway
        $takeaway = $this->ci->takeawaymodel->getDetails($id);
        if ($takeaway) {
               return $takeaway;
        }
        
        return false;
    }

    /**
     * Get the takeaway logo
     *
     * @param Integer takeaway ID
     */
    public function getLogo($id)
    {
        // Check path to see if logo exists if not return default
        $path = 'images/users/'.$id.'/logo.jpg';
        $abspath = getcwd().'/'.$path;
        if (file_exists($abspath))
              return $path;

        // Otherwise return default
        return 'images/nologo.gif';
    }   
 
}
