<?php
/**
 * Order Model
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Order_model extends CI_Model {  
  
    public function __construct()  
    {  
        parent::__construct();
        $this->load->database();
    }  
    
    /**
     * Create a new order
     * 
     * @param Integer user
     * @param Integer takeaway
     * @param String name
     * @param String address1
     * @param String address2
     * @param String town
     * @param String postcode
     * @param Integer phone
     * @param String comments
     * @param Integer delivery
     * @param Boolean paid
     * @return Integer orderId
     */
    public function createOrder($user = 0, $takeaway, $name, $address1, $address2, $town, $postcode, $phone, $comments, $delivery, $paid = false)  
    {  
            $data = array(
                'userId' => $user,
                'takeawayId' => $takeaway,
                'date' => date("Y-m-d H:i:s"),
                'delivery_name' => ''.$name.'',
                'address1' => ''.$address1.'',
                'address2' => ''.$address2.'',
                'town' => ''.$town.'',
                'postcode' => ''.$postcode.'',
                'phone' => ''.$phone.'',
                'additional' => ''.$comments.'',
                'deliveryType' => ''.$delivery.'',
                'status' => 0,
		'paid' => $paid
            );
            
            $this->db->insert('orders', $data);
            
            return $this->db->insert_id();
    }
    
    /**
     * Add an item to an order
     *
     * @param Integer orderId
     * @param Integer menuId
     * @param double price
     * @param Integer qty
     * @return Boolean
     */
    public function addItem($order, $menu, $price, $qty = 1)
    {
            $data = array(
                'orderId' => $order,
                'menuId' => $menu,
                'qty' => $qty,
                'priceAtOrder' => $price
            );
             
            $this->db->insert('menu_to_orders', $data);
            
            if ($this->db->insert_id())
                return true;
            
            return false;
    }

    /**
     * Remove an order
     * @param Integer orderId
     */
    public function removeOrder($id)
    {
            $this->db->delete('orders', array('orderId' => $id));
            $this->db->delete('menu_to_orders', array('orderId' => $id));
    }
    
    /**
     * Returns details about orders a user has made
     * @param Integer userID
     */
    public function getOrdersForUser($userId)
    {
            $this->db->select('*');
            $this->db->from('orders');
            $this->db->join('takeaways', 'orders.takeawayId = takeaways.takeawayId');
            $this->db->where('orders.userId = ', $userId);
            $this->db->order_by('date', 'desc'); 
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return $query->result();
            }

            return false;
    }
    
    /**
     * Get items contained within an order
     * @param Integer orderId
     */
    public function getItemsInOrder($orderId)
    {
            $this->db->select('*');
            $this->db->from('menu_to_orders');
            $this->db->join('menu', 'menu_to_orders.menuId = menu.menuId');
            $this->db->where('orderId = ', $orderId);
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return $query->result();
            }

            return false;
    }
    
    /**
     * Check if the item has a parent
     * @param Integer menuId
     */
    public function hasParent($menuId)
    {
            $this->db->select('*');
            $this->db->from('menu n');
            $this->db->join('menu m', 'm.menuId = n.parent');
            $this->db->where('n.menuId = ', $menuId);
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return $query->row();
            }

            return false;
    }
    
    /**
     * Get the orders for takeaway where status is x
     * @param Integer takeawayId
     * @param Integer status
     */
    public function getTotalTakeawayOrders($takeawayId, $status)
    {
        
            $this->db->select('*');
            $this->db->from('orders');
            $this->db->where('orders.takeawayId = ', $takeawayId);
            $this->db->where('status = ', $status);
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return $query->result();
            }

            return array();
    }
    
    /**
     * Get the total value of orders for takeaway where status is x
     * @param Integer takeawayId
     */
    public function getValueTakeawayOrders($takeawayId)
    {
        
            $this->db->select('SUM(priceAtOrder*qty) AS value');
            $this->db->from('orders');
            $this->db->join('menu_to_orders', 'menu_to_orders.orderId = orders.orderId');
            $this->db->where('orders.takeawayId = ', $takeawayId);
            $this->db->where('status = 1');
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return $query->row();
            }

            return 0;
    }
    
    /**
     * Get the total number of orders per day
     * @param Integer takeawayId
     */
    public function getTakeawayOrders($takeawayId)
    {
            $this->db->select('COUNT(*) as total, DATE(date) as day');
            $this->db->from('orders');
            $this->db->where('orders.takeawayId = ', $takeawayId);
            $this->db->where('status = 1');
            $this->db->group_by('DATE(date)');
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return $query->result();
            }

            return false;
    }
    
    /**
     * Get the list of pending orders for a takeaway
     * @param Integer takeawayId
     */
    public function getPendingOrders($takeawayId)
    {
            $this->db->select('*');
            $this->db->from('orders');
            $this->db->where('orders.takeawayId = ', $takeawayId);
            $this->db->where('status = 0');
            $this->db->order_by('date', 'asc'); 
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return $query->result();
            }

            return false;
    }
    
    /**
     * Get the list most ordered products
     * @param Integer takeawayId
     */
    public function getPopularProducts($takeawayId)
    {
            $this->db->select('menu_to_orders.menuId AS menuId, name, COUNT(menu_to_orders.menuId) AS count, AVG(qty) AS quantity ');
            $this->db->from('menu_to_orders');
            $this->db->join('orders', 'orders.orderId = menu_to_orders.orderId');
            $this->db->join('menu', 'menu.menuId = menu_to_orders.menuId');
            $this->db->where('orders.takeawayId = ', $takeawayId);
            $this->db->where('orders.status = 1');
            $this->db->group_by("menu_to_orders.menuId"); 
            $this->db->order_by('count', 'desc'); 
            $query = $this->db->get();
            
            if($query->num_rows() > 0) {
                return $query->result();
            }

            return false;
    }
    
    /*
     * Change the status of an order
     * @param Integer orderId
     * @param Integer status
     */
    public function setStatus($orderId, $status)
    {
            $data = array('status' => $status);

            $this->db->where('orderId', $orderId);
            $this->db->update('orders', $data); 
    }
    
    /**
     * Update the takeaway status
     * @param Integer takeawayId
     * @param Integer status
     */
    public function takeawayStatus($takeawayId, $status)
    {
            $data = array('status' => $status);

            $this->db->where('takeawayId', $takeawayId);
            $this->db->update('takeaways', $data); 
    }
  
}  

