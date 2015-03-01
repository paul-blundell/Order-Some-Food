<?php
/**
 * Menu Model
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Menu_model extends CI_Model {  
  
    public function __construct()  
    {  
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Returns menu data for specified takeaway
     * @param Integer takeawayID
     */
    public function getCategories($takeaway)  
    {  
            $query = $this->db->query('SELECT * FROM menu_categories WHERE takeawayId = ? ORDER BY position ASC',$takeaway);
            return $query->result();   
    }
    
    /**
     * Get the products for a particular category
     * @param Integer categoryID
     */
    public function getProducts($category)  
    {  
            $query = $this->db->query('SELECT * FROM menu WHERE categoryId = ? AND parent=0',$category);
            return $query->result();   
    }
    
    /**
     * Get the child products for a particular product
     * @param Integet productID
     */
    public function getChildren($itemId)
    {
            $query = $this->db->query('SELECT * FROM menu WHERE parent=? ORDER BY price ASC',$itemId);
            return $query->result(); 
    }
    
    /**
     * Returns details about a specific menu item
     * @param Integer menuID
     */
    public function getDetails($menuId)  
    {
            $query = $this->db->query('SELECT * FROM menu WHERE menuId = ?',$menuId);
            return $query->row();
    }
    
    /**
     * Returns details about a specific category
     * @param Integer categoryID
     */
    public function getCategory($catId)  
    {
            $query = $this->db->query('SELECT * FROM menu_categories WHERE categoryId = ?',$catId);
            return $query->row();
    }
    
    /**
     * Checks an item exists
     * @param Integer itemID
     */
    public function checkExists($id)
    {
            $this->db->select('menuId');
            $this->db->from('menu');
            $this->db->where('menuId = ', $id);
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return true;
            }

            return false;
    }
    
    /**
     * Updates a menu items details
     * @param Integer id
     * @param String name
     * @param String desc
     * @param double price
     */
    public function updateMenuItem($id, $name, $desc, $price)
    {
            $data = array(
               'name' => $name,
               'description' => $desc,
               'price' => $price
            );

            $this->db->where('menuId', $id);
            $this->db->update('menu', $data);
            
            return $id;
    }
    
    /**
     * Adds a new menu item
     * @param String name
     * @param String desc
     * @param double price
     * @param Integer cat
     * @param Integer parent
     */
    public function addMenuItem($name, $desc, $price, $cat, $parent)
    {
            $data = array(
                'name' => $name,
                'description' => $desc,
                'price' => $price,
                'categoryId' => $cat,
                'parent' => $parent
            );
             
            $this->db->insert('menu', $data);
            
            return $this->db->insert_id();
    }
    
    /**
     * Removes an item from the menu
     * @param Integer id
     */
    public function removeItem($id)
    {
            $this->db->delete('menu', array('menuId' => $id)); 
    }
    
    /**
     * Checks a menu item belongs to a takeaway
     * @param Integer id
     * @param Integer takeaway
     */
    public function confirmItem($id, $takeaway)
    {
            $this->db->select('menuId');
            $this->db->from('menu');
            $this->db->join('menu_categories', 'menu_categories.categoryId = menu.categoryId');
            $this->db->where('takeawayId = ', $takeaway);
            $this->db->where('menuId = ', $id);
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return true;
            }

            return false;
    }
    
    /**
     * Create a new category group
     * @param String name
     * @param Integer takeaway
     */
    public function addCategory($name, $takeaway)
    {
            $data = array(
                'category_name' => $name,
                'takeawayId' => $takeaway
            );
             
            $this->db->insert('menu_categories', $data);
            
            return $this->db->insert_id();
    }
    
    /**
     * Rename a new category group
     * @param Integer cat
     * @param String name
     */
    public function renameCategory($cat, $name)
    {
            $data = array('category_name' => $name);

            $this->db->where('categoryId', $cat);
            $this->db->update('menu_categories', $data);
    }
    
    /**
     * Set a category position
     * @param Integer category
     * @param Integer position
     */
    public function setCategoryPosition($category, $position)
    {
            $data = array('position' => $position);

            $this->db->where('categoryId', $category);
            $this->db->update('menu_categories', $data);
    }
    
    /**
     * Removes category and all products in category
     * @param Integer id
     */
    public function removeCategory($id)
    {
            $this->db->delete('menu', array('categoryId' => $id));
            $this->db->delete('menu_categories', array('categoryId' => $id)); 
    }
    
    /**
     * Check to see if the takeaway rated
     * @param String ip
     */
    public function checkRated($ip)  
    {
            $query = $this->db->query('SELECT rating FROM ratings WHERE ip = ?',$ip);
            return $query->row();
    }
    
    /**
     * Get a takeeaways details using the shortname
     * @param String name
     */
    public function getTakeawayFromShortName($name)  
    {
            $query = $this->db->query('SELECT takeawayId, name FROM takeaways WHERE shortname = ?',$name);
            return $query->row();
    }
    
    /**
     * Checks to see if a takeaway is open or closed
     * @param Integer takeaway
     * @return Boolean
     */
    public function isTakeawayOpen($takeaway)
    {
            $this->db->select('status');
            $this->db->from('takeaways');
            $this->db->where('takeawayId = ', $takeaway);
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                $result = $query->row();
                if ($result->status == 1)
                        return true;
            }

            // Takeaway Closed
            return false;
    }
    
    /**
     * Update the rating of a takeaway
     * @param Integer takeaway
     * @param Integer rating
     * @param String ip
     */
    public function updateRating($takeaway, $rating, $ip)  
    {
        
        $data = array(
               'takeawayId' => $takeaway,
               'rating' => $rating,
               'ip' => $ip
        );
        
        $this->db->where('ip', $ip);
        $this->db->update('ratings', $data); 
    }
    
    /**
     * Insert a new rating
     * @param Integer takeaway
     * @param Integer rating
     * @param String ip
     */
    public function insertRating($takeaway, $rating, $ip)  
    {
        
        $data = array(
               'takeawayId' => $takeaway,
               'rating' => $rating,
               'ip' => $ip
        );
        
        $this->db->insert('ratings', $data);
    }
    
    /**
     * Remove a rating
     * @param String ip
     */
    public function removeRating($ip)
    {
            $this->db->delete('ratings', array('ip' => $ip));
    }
    
}  
?>
