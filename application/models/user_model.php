<?php
/**
 * User Model
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class User_model extends CI_Model {  
  
    public function __construct()  
    {  
            parent::__construct();
            $this->load->database();
    }
    
    /**
     * Login a user
     * @param String email
     * @param String pass
     */
    public function login($email, $pass)
    {
            $this->db->select('uId, groupId, name, email');
            $this->db->from('users');
            $this->db->where('email LIKE ', $email);
            $this->db->where('password = ', sha1($email.'_'.$pass));
            
            $query = $this->db->get();
           
            if($query->num_rows() > 0) {
                return $query->row(); 
            }
            
            return false;
    }
    
    /**
     * Register a new user
     * @param String email
     * @param String pass
     * @param Integer group
     */
    public function register($email, $pass, $group)
    {
            $data = array(
                'name' => '',
                'email' => ''.$email.'',
                'password' => sha1($email.'_'.$pass),
                'groupId' => $group
            );
             
            $this->db->insert('users', $data);
        
            return $this->db->insert_id();
    }

    /**
     * Update a user
     * @param String email
     * @param String pass
     */
    public function update($uId, $email, $pass)
    {
            $data = array(
                'email' => ''.$email.'',
                'password' => sha1($email.'_'.$pass)
            );
             
            $this->db->where('uId', $uId);
            $this->db->update('users', $data);
        
            return $this->db->insert_id();
    }

    
    /**
     * Get the details of a user
     * @param Integer uid
     */
    public function getDetails($uid)
    {
            $this->db->select('name, email, groupId');
            $this->db->from('users');
            $this->db->where('uId = ', $uid);
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return $query->row();
            }
            
            return false;
    }

    
    /**
     * Get a users email address
     * @param Integer uid
     */
    public function getEmail($uid)
    {          
            $this->db->select('email');
            $this->db->from('users');
            $this->db->where('uId = ', $uid);
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return $query->row();
            }
            
            return false;
    }
    
    /**
     * Get a users encrypted password
     * @param Integer uid
     */
    public function getPass($uid)
    {          
            $this->db->select('password');
            $this->db->from('users');
            $this->db->where('uId = ', $uid);
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return $query->row();
            }
            
            return false;
    }
    
    /**
     * Check the email address does not exist
     * @param String email
     * @return Boolean
     */
    public function checkEmail($email)
    {          
            $this->db->select('uId');
            $this->db->from('users');
            $this->db->where('email LIKE ', $email);
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return true;
            }
            
            return false;        
    }
    
    /**
     * Get the last address used
     * @param Integer uid
     */
    public function getLastAddress($uid)
    {
            $this->db->select('delivery_name, address1, address2, town, postcode, phone');
            $this->db->from('orders');
            $this->db->where('userId = ', $uid);
            $this->db->order_by('date', 'desc');
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return $query->row();
            }
            
            return false;
    }
    
    /**
     * Get the takeaways owned by the users
     * @param Integer uid
     */
    public function getTakeaways($uid)
    {
            $this->db->select('takeawayId, name, shortname, address, postcode, description, deliveryCharge, deliveryTime, category, status, background, fontsize, buttons, categoryColour');
            $this->db->from('takeaways');
            $this->db->where('userId = ', $uid);
            $this->db->where('type > ', 0);
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return $query->result();
            }
            
            return false;
    }
    
    /**
     * Get the default takeaway owned by the user
     * @param Integer uid
     */
    public function getDefault($uid)
    {
            $this->db->select('takeawayId, name, shortname, address, postcode, description, deliveryCharge, deliveryTime, category, status, background, fontsize, buttons, categoryColour');
            $this->db->from('takeaways');
            $this->db->where('userId = ', $uid);
            $this->db->where('type > ', 0);
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return $query->row();
            }
            
            return false;
    }
    
    /**
     * Check the user is an owner of the specified takeaway
     * @param Integer takeawayId
     * @param Integer uid
     * @return Boolean
     */
    public function checkUserisOwner($takeawayId, $uid)
    {
            $this->db->select('takeawayId');
            $this->db->from('takeaways');
            $this->db->where('userId = ', $uid);
            $this->db->where('takeawayId = ', $takeawayId);
            $this->db->where('type > ', 0);
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return true;
            }
            
            return false;
    }
    
    /**
     * Remove a user
     * @param Integer uid
     */
    public function removeUser($id)
    {
            $this->db->delete('users', array('uId' => $id)); 
    }
    
}
