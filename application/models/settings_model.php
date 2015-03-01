<?php
/**
 * Settings Model
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Settings_model extends CI_Model {  
  
    public function __construct()  
    {  
            parent::__construct();
            $this->load->database();
    }
    
    public function getSetting($key)
    {
        $this->db->select('settingValue');
        $this->db->from('takeaways');
        $this->db->where('settingKey = ', $key);
        $query = $this->db->get();
            
        if($query->num_rows() == 0)
            return null;
        
        return $query->result();
    }
    
}