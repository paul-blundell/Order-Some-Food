<?php  

/**
 * Menu API.
 * This API will return the menu for a specified takeaway ID
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Menu extends CI_Controller
{
        public function index()  
        {
		// Load the menu and api models
		$this->load->model('menu_model','menu');
		$this->load->model('api_model','api');
		
		// Is there a signature and takeaway ID specified?
		if (!isset($_POST['signature']) || !isset($_POST['takeaway'])) 
		{
			$data['message'] = 'Missing required data.';
			$this->load->view('api/error_view', $data);
			return;
		}
			
		// Get the secret key from the model
		$secret = $this->api->getSecret($_POST['key']);
		
		// Check a secret key was found for the supplied api key and check the signature matches
		if (!$secret || strtoupper($_POST['signature']) != strtoupper(sha1($_POST['key'].$secret->client_secret.$_POST['takeaway']))) 
		{
			$data['message'] = 'Unauthorised Access.';
			$this->load->view('api/error_view',$data);
			return;
		}
		
		// If the takeaway does not exist
		if (!$this->takeaways->getDetails($_POST['takeaway'])) 
		{
			$data['message'] = 'Invalid takeaway requested.';
			$this->load->view('api/error_view',$data);
			return;
		}
		
		// Get the menu data for the selected takeaway
		$results = $this->menu->getCategories($_POST['takeaway']);
	
		// Process menu information into readable format
		$menu = array();
		
		// Loop through each category
		foreach ($results AS $cat) 
		{
			// Get the products for the category
			$products = $this->menu->getProducts($cat->categoryId);

			$menu[$cat->categoryId] = array();
			
			// Loop through each product
			foreach ($products AS $row) 
			{
				// Get the child products for the product
				$children = $this->menu->getChildren($row->menuId);
				$childArray = array();
				// If there is children
				if ($children) {
					// Loop through the children and add to array
					foreach ($children AS $child)
						$childArray[] = $child;
				}
				// Add item to the array under the category ID key
				$menu[$cat->categoryId][] = array("id" => $row->menuId, 
								"name" => $row->name, 
								"description" => $row->description, 
								"price" => $row->price, 
								"children" => $childArray);
			}
		}
		
		// Output as XML with takeaway details
		$data['takeaway'] = $this->takeaways->getDetails($_POST['takeaway']);
		$data['categories'] = $results;
		$data['menu'] = $menu;
		$this->load->view('api/menu_view', $data);
	}
} 
