<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User Library.
 * Used for common user functions.
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class User
{
    private $user;
    private $ci;
    
    public function __construct()
    {
            $this->ci = & get_instance();
            $this->user = $this->ci->session->userdata('user');
            $this->ci->load->model('user_model','usermodel');
    }
    
    /**
     * Login a user
     *
     * @param String email
     * @param String pass
     * @return Boolean
     */
    public function login($email, $pass)
    {
            $result = $this->ci->usermodel->login($email,$pass);
            
	    // If the user was logged in
            if ($result) {
                    $this->user['uid'] = $result->uId;
                    $this->user['name'] = $result->name;
                    $this->user['group'] = $result->groupId;
                    
		    // Save the data to the session
                    $this->ci->session->set_userdata('user', $this->user);
                    
                    return true;
            }
            
            return false;        
    }

    public function checkUser($email, $pass)
    {
            $result = $this->ci->usermodel->login($email, $pass);
             
	    // If the user was logged in
            if ($result)
		return true;

	    return false;
    }
    
    /**
     * Register a new user
     *
     * @param String email
     * @param String pass
     * @param Integer group
     * @return Boolean
     */
    public function register($email, $pass, $group = 1)
    {
            $result = $this->ci->usermodel->register($email,$pass,$group);
            
            if ($result)
                return $result;
            
            return false;
    }

    /**
     * Update a users details
     *
     * @param String email
     * @param String pass
     * @return Boolean
     */
    public function updateUser($uId, $email, $pass)
    {
	    $result = $this->ci->usermodel->update($uId,$email,$pass);
            
            if ($result)
                return $result;
            
            return false;
    }
    
    /**
     * Logout a user
     */
    public function logout()
    {
	    // Remove user data from the session
	    unset($this->user);
            $this->ci->session->unset_userdata('user');
    }
    
    /**
     * Check if a user is loged in or not
     * 
     * @return Boolean
     */
    public function isLoggedIn()
    {
            if (isset($this->user['uid']) && $this->user['uid'] != 0)
                return true;
            
            return false;
    }
    
    /**
     * Check if a user is an admin
     * 
     * @return Boolean
     */
    public function isAdmin()
    {
            if (isset($this->user['uid']) && $this->user['group'] == 3)
                return true;
            
            return false;
        
    }
    
    
    /**
     * Check if a user is an admin viewing another users account
     * 
     * @return Boolean
     */
    public function isAdminSwitched()
    {
            if (isset($this->user['admin']))
                return true;
            
            return false;
        
    }

    /**
     * Check if a user is a takeaway owner
     * 
     * @return Boolean
     */
    public function isTakeawayOwner()
    {
	    // If user is part of group 2 and user has takeaways associated with it
            if (isset($this->user['uid']) && $this->user['group'] == 2 && $this->ci->usermodel->getTakeaways($this->user['uid']))
                return true;
            
            return false;
        
    }
    
    /**
     * Check user logged in and if so return user details
     * else return empty details
     *
     * @return Array The user details
     */
    public function getUserDetails()
    {
        $data = array('loggedIn' => false, 'uid' => 0, 'delivery_name' => null, 'address1' => null,'address2' => null,'town' => null,'postcode' => null,'telephone' => null);
	if ($this->isLoggedIn()) {
                $data['loggedIn'] = true;
                $data['uid'] = $this->user['uid'];

                // Get last used delivery address - if any
                $lastaddress = $this->ci->usermodel->getLastAddress($this->user['uid']);
                if ($lastaddress) {
                        $data['delivery_name'] = $lastaddress->delivery_name;
                        $data['address1'] = $lastaddress->address1;
			$data['address2'] = $lastaddress->address2;
			$data['town'] = $lastaddress->town;
                        $data['postcode'] = $lastaddress->postcode;
                        $data['telephone'] = $lastaddress->phone;
                }
	}
        
        return $data;
    }
    
    /**
     * Returns a list of takeaways owned by the user
     *
     * @return Array The takeaway details
     */
    public function getTakeaways()
    {
        $takeaway = $this->ci->usermodel->getTakeaways($this->user['uid']);
        if ($takeaway) {
               return $takeaway;
        }
        
        return false;
    }
    
    /**
     * Function will return a takeaway owned by the specified user
     * If only one is owned, then will return this one otherwise
     * will select the first one registered.
     *
     * @return Array The takeaway details
     */
    public function getDefaultTakeaway()
    {
        $takeaway = $this->ci->usermodel->getDefault($this->user['uid']);
        if ($takeaway) {
               return $takeaway;
        }
        
        return false;
    }

    /**
     * Function will return the ID of the takeaway being viewed, if this ID
     * is not stored in the session then the default takeaway will be chosen.
     *
     * @return Integer The takeaway ID
     */
    public function getTakeawayId()
    {
	$loaded = false;
	
	// Is a takeawayId specified? If not then load the default takeaway
	if ($this->ci->session->userdata('takeawayId')) {
		$takeawayId = $this->ci->session->userdata('takeawayId');
		if ($this->ci->usermodel->checkUserisOwner($takeawayId, $this->user['uid']))
			$loaded = true;
	}
	
	if (!$loaded) {
		$takeawayObject = $this->getDefaultTakeaway();
		$takeawayId = $takeawayObject->takeawayId;
		$this->ci->session->set_userdata('takeawayId', $takeawayId);
	}
	
	return $takeawayId;
    }
    
    /**
     * Get the users name
     * @return String name
     */
    public function getName()
    {
            return $this->user['name'];
    }
    
    /**
     * Get the users ID
     * @return Integer uid
     */
    public function getUid()
    {
            return $this->user['uid'];
    }
    
    /**
     * Get the users Group ID
     * @return Integer gid
     */
    public function getGid()
    {
            return $this->user['group'];
    }
    
    /**
     * Get the users email address
     * @return String email
     */
    public function getEmail()
    {
            $result = $this->ci->usermodel->getEmail($this->getUid());
            
            return $result->email;
    }
    
    /**
     * Get the users encrypted password
     * @return String password
     */
    public function getPass()
    {
            $result = $this->ci->usermodel->getPass($this->getUid());
            
            return $result->password;
    }
    
    /**
     * Check the email address exists
     *
     * @param String email
     * @return Boolean
     */
    public function checkEmailExists($email)
    {
            return $this->ci->usermodel->checkEmail($email);
    }
}
