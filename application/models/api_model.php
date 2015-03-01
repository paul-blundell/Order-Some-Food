<?php

/**
 * API Model
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Api_model extends CI_Model {  
  
    public function __construct()  
    {  
        parent::__construct();
        $this->load->database();
    }  
  
    /**
     * Get the an API secret value
     * @param String key
     */
    public function getSecret($key)
    {
            $query = $this->db->query('SELECT client_secret FROM api_clients WHERE active = 1 AND client_key = ?',$key);
            return $query->row();
    }
}  
?>
