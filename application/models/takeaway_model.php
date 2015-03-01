<?php
/**
 * Takeaway Model
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Takeaway_model extends CI_Model {  
  
    public function __construct()  
    {  
        parent::__construct();
        $this->load->database();
    }  
    
    /**
     * Get a takeeaways details using the shortname
     * @param String name
     */
    public function getTakeawayFromShortName($name)  
    {
            $query = $this->db->query('SELECT * FROM takeaways WHERE shortname = ?',$name);
            return $query->row();
    }

    /**
     * Returns details about a specific takeaway
     * @param Integer takeawayId
     */
    public function getDetails($takeawayId)  
    {
            $query = $this->db->query('SELECT *, (SELECT AVG(rating) FROM ratings WHERE ratings.takeawayId = takeaways.takeawayId) AS rating FROM takeaways LEFT JOIN paypal USING (takeawayId) WHERE takeawayId = ?',$takeawayId);
            return $query->row();
    }

    /**
     * Returns specific takeaways opening times
     * @param Integer takeawayId
     */
    public function getOpeningTimes($takeawayId)  
    {
            $query = $this->db->query('SELECT dayOfWeek, openingTime, closingTime, open FROM takeaway_openingtimes WHERE takeawayId = ?',$takeawayId);

	    $times = array();
	    foreach ($query->result() AS $row) {
		if ($row->open)
			$times[$row->dayOfWeek] = array('open' => $row->openingTime, 'close' => $row->closingTime);
	    }
            return $times;
    }

    /**
     * Returns a list of takeaways which are awaiting approval
     */
    public function getAwaiting()
    {
            $this->db->select('*');
            $this->db->from('takeaways');
            $this->db->where('type = ', 0);
            $query = $this->db->get();
            
            return $query->result();
    }
    
    /**
     * Returns a list of takeaways which have been approved and are active
     */
    public function getApproved()
    {
            $this->db->select('*');
            $this->db->from('takeaways');
            $this->db->where('type > ', 0);
            $query = $this->db->get();
            
            return $query->result();
    }

    /**
     * Update the takeaway type, e.g. Pending, Approved etc
     * @param Integer takeawayId
     * @param String value
     */
    public function setType($takeawayId, $value)
    {
            $this->db->where('takeawayId', $takeawayId);
            $this->db->update('takeaways', array('type' => $value));
    }
    
    /**
     * Removes a takeaway and any menu items associated
     * @param Integer takeawayId
     */
    public function removeTakeaway($takeawayId)
    {
            $this->db->delete('takeaways', array('takeawayId' => $takeawayId));
            
            // Get categories
            $this->db->select('categoryId');
            $this->db->from('menu_categories');
            $this->db->where('takeawayId = ', $takeawayId);
            $categories = $this->db->get();
            
            // Loop through categories and delete menu items
            foreach ($categories->result() AS $category) {
                    $this->db->delete('menu', array('categoryId' => $category->categoryId)); 
            }
            
            // Finally delete all categories
            $this->db->delete('menu_categories', array('takeawayId' => $takeawayId)); 
    }
    
    /**
     * Update the takeaway opening times
     * @param Integer takeawayId
     * @param String code the day of week
     * @param String open
     * @param String close
     * @param boolean closed
     */
    public function updateOpeningTimes($takeawayId, $code, $open, $close, $isOpen = false) {

	    $this->db->select('dayOfWeek');
            $this->db->from('takeaway_openingtimes');
            $this->db->where('takeawayId = ', $takeawayId);
	    $this->db->where('dayOfWeek = ', $code);
            $query = $this->db->get();

	    if (!$isOpen) {
		$open = "00:00:00";
		$close = "00:00:00";
	    }
            
            if($query->num_rows() > 0) {
		    $data = array(
		       'openingTime' => $open,
		       'closingTime' => $close,
		       'open' => $isOpen
		    );

		    $this->db->where('takeawayId', $takeawayId);
		    $this->db->where('dayOfWeek', $code);
		    $this->db->update('takeaway_openingtimes', $data); 
	    } else {
		    $data = array(
		       'takeawayId' => $takeawayId,
		       'dayOfWeek' => $code,
		       'openingTime' => $open,
		       'closingTime' => $close,
		       'open' => $isOpen
		    );

		   $this->db->insert('takeaway_openingtimes', $data);
	    }
    }

    /**
     * Update the takeaway details
     * @param Integer takeawayId
     * @param String address
     * @param String postcode
     * @param String description
     * @param double deliveryCharge
     * @param Integer deliveryTime
     */
    public function updateTakeaway($takeawayId,
                            $address,
                            $postcode,
                            $description,
                            $deliveryCharge,
                            $deliveryTime,
                            $paypal = 0,
                            $paypalEmail = '',
                            $paypalSignature = "",
                            $paypalPassword = "")
    {
            $data = array(
               'address' => $address,
               'postcode' => $postcode,
               'description' => $description,
               'deliveryCharge' => $deliveryCharge,
               'deliveryTime' => $deliveryTime
            );

            $this->db->where('takeawayId', $takeawayId);
            $this->db->update('takeaways', $data);
            
	    $this->db->select('paypalActive');
            $this->db->from('paypal');
            $this->db->where('takeawayId = ', $takeawayId);
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
		    $data = array(
		       'paypalActive' => $paypal,
		       'paypalEmail' => $paypalEmail,
		       'paypalSignature' => $paypalSignature,
		       'paypalPassword' => $paypalPassword
		    );

		    $this->db->where('takeawayId', $takeawayId);
		    $this->db->update('paypal', $data); 
	    } else {
		    $data = array(
		       'takeawayId' => $takeawayId,
		       'paypalActive' => $paypal,
		       'paypalEmail' => $paypalEmail,
		       'paypalSignature' => $paypalSignature,
		       'paypalPassword' => $paypalPassword
		    );

		   $this->db->insert('paypal', $data);

	    }
    }
    
    /**
     * Update the takeaways appearance settings
     * @param Integer takeawayId
     * @param String background
     * @param Integer fontsize
     * @param String buttons
     * @param String category
     */
    public function updateTakeawayAppearance($takeawayId, $background, $fontsize, $buttons, $category)
    {
            $data = array(
               'background' => $background,
               'fontsize' => $fontsize,
               'buttons' => $buttons,
               'categoryColour' => $category
            );

            $this->db->where('takeawayId', $takeawayId);
            $this->db->update('takeaways', $data);
    }
    
    /**
     * Get the categories
     */
    public function getCategories()
    {
            $this->db->select('category_id, category_name');
            $this->db->from('categories');
            $query = $this->db->get();
            
            return $query->result();   
    }
    
    /**
     * Check short name exists or not
     * @param String name
     * @return Boolean
     */
    public function checkShortNames($name)
    {
            $this->db->select('takeawayId');
            $this->db->from('takeaways');
            $this->db->where('shortname = ', $name);
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return true;
            }
            
            return false;
    }
    
    /**
     * Add a new takeaway
     * 
     * @param String name
     * @param String shortname
     * @param String address
     * @param String postcode
     * @param Integer phone
     * @param String description
     * @param double charge
     * @param Integer time
     * @param Integer category
     * @param double latitude
     * @param double longitude
     * @param Integer uid
     * @return Integer takeawayId
     */
    public function addTakeaway($name, $shortname, $address, $postcode, $phone, $description, $charge, $time, $category, $latitude, $longitude, $uid)
    {
            $data = array(
                'name' => $name,
                'shortname' => $shortname,
                'address' => $address,
                'postcode' => $postcode,
                'phone' => $phone,
                'description' => $description,
                'deliveryCharge' => $charge,
                'deliveryTime' => $time,
                'category' => $category,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'status' => 0,
                'userId' => $uid,
                'type' => 0                
            );
             
            $this->db->insert('takeaways', $data);
        
            return $this->db->insert_id();
    }
    
    /**
     * Get the closest takeaways to a specified point
     * @param double lat
     * @param double lng
     */
    public function getData($lat, $lng)
    {
            if ($lat == '' || $lng == '')
                return false;
            
            $query = $this->db->query('SELECT takeawayId, name, description, category, category_name, status, latitude, longitude,
                                      (SELECT AVG(rating) FROM ratings WHERE ratings.takeawayId = takeaways.takeawayId) AS rating,
                                      (3959 * acos(cos(radians('.$lat.')) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$lng.') ) + sin(radians('.$lat.')) * sin(radians(latitude )))) AS distance
                                      FROM `takeaways` JOIN categories ON (category=category_id) WHERE type > 0 HAVING distance <= 20 ORDER BY `status` DESC,`distance` ASC');
            return $query->result();   
    }
}
