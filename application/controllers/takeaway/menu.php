<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Edit a takeaway menu
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 *
 * @author Paul Blundell
 */
class Menu extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Load the default and menu language files
		$this->lang->load('user/default');
		$this->lang->load('user/menu');

		// If not a takeaway owner redirect to homepage
		if (!$this->user->isTakeawayOwner())
			redirect(base_url());
	}
	
	public function index()
	{
		// Load the takeaway model
		$this->load->model('takeaway_model','takeaway');
		
		// if there is POST data
		if (count($_POST)>0) {
			// Load the menu model
			$this->load->model('menu_model','menumodel');
			
			// Add a new category
			if (isset($_POST['categoryadd']) && $_POST['name'] != '') {
				// Get the takeaway details
				$takeaway = $this->takeaway->getDetails($this->user->getTakeawayId());
				// Add the category to the database
				$this->menumodel->addCategory($_POST['name'], $takeaway->takeawayId);
			// Rename a category
			} else if (isset($_POST['categoryrename']) && $_POST['name'] != '') {
				// Rename the category in the database
				$this->menumodel->renameCategory($_POST['cat'], $_POST['name']);
			}
			
			// Update an item
			if (isset($_POST['productupdate']))
				$parent = $this->menumodel->updateMenuItem($_POST['productupdate'], $_POST['name'], $_POST['desc'], $_POST['price']);
			// Add an item
			else if (isset($_POST['productadd']))
				$parent = $this->menumodel->addMenuItem($_POST['name'], $_POST['desc'], $_POST['price'], $_POST['category'], 0);
			
			
			// Update child product if exists or add new
			if (isset($_POST['child'])) {
				// Loop through the child products POSTed
				for($i=0;$i<count($_POST['child']);$i++) {
					// If the name is not blank and there is a price
					if ($_POST['childname'][$i] != '' && $_POST['childprice'][$i] != '') {
						// If the ID is 0, then this is a new product so add the item
						if ($_POST['child'][$i] == 0)
							$this->menumodel->addMenuItem($_POST['childname'][$i],'',$_POST['childprice'][$i],$_POST['category'], $parent);
						// ID not 0 then check items exists and if so update the item
						else if ($this->menumodel->checkExists($_POST['child'][$i]))
							$this->menumodel->updateMenuItem($_POST['child'][$i],$_POST['childname'][$i],'',$_POST['childprice'][$i]);
							
					}
				}
			}
			
			// Remove any child products
			if (isset($_POST['remove'])) {
				for($i=0;$i<count($_POST['remove']);$i++) {
					$this->menumodel->removeItem($_POST['remove'][$i]);
				}
			}
			
		}
		
		// Load the takeaway details
		$data['takeaway'] = $this->takeaway->getDetails($this->user->getTakeawayId());
		
		// Load the menu from API
		$this->load->library('curl');
		$result = $this->curl->simple_post('api/menu', array('key'=>API_KEY,
								     'signature'=>sha1(API_KEY.API_SECRET.$data['takeaway']->takeawayId),
								     'takeaway'=>$data['takeaway']->takeawayId));
		
		// Load the XML response
		$data['xml'] = simplexml_load_string($result);
		
		// Output the menu
		$this->template->set('javascript', $this->_javascript());
		$this->template->set('fullwidth', true);
		$this->template->load('menu/edit/view', $data);
	}
	
	/**
	 * Edit an item in the menu
	 *
	 * @param Integer item ID number
	 */
	public function edit($item)
	{
		// Load the menu model
		$this->load->model('menu_model','menumodel');
		
		// Get details of the item and any children
		$data['product'] = $item;
		$data['item'] = $this->menumodel->getDetails($item);
		$data['children'] = $this->menumodel->getChildren($item);
		
		// Load the edit view
		$this->load->view('menu/edit/edit', $data);
	}
	
	/**
	 * Add an item to the menu
	 *
	 * @param Integer category ID number
	 */
	public function add($item)
	{
		// Load the menu model
		$this->load->model('menu_model','menumodel');
		
		// Get details of the item and any children
		$data['category'] = $item;
		$data['item'] = $this->menumodel->getDetails($item);
		$data['children'] = $this->menumodel->getChildren($item);
		
		// Load the add view
		$this->load->view('menu/edit/add', $data);
	}
	
	/**
	 * Remove an item from the menu
	 * But first make sure the user has permission to do so
	 *
	 * @param Integer item ID number
	 */
	public function remove($item)
	{
		// Load the menu and takeaway models
		$this->load->model('menu_model','menumodel');
		$this->load->model('takeaway_model','takeaway');
		
		// Get the takeaway details
		$takeaway = $this->takeaway->getDetails($this->user->getTakeawayId());
		
		// Get any child products and remove them first
		$children = $this->menumodel->getChildren($item);
			
		// Loop through the products and remove
		foreach ($children AS $child) {
			$this->menumodel->removeItem($child->menuId);
		}
			
		// Remove the item
		$this->menumodel->removeItem($item);
		
		
		// Redirect back to the menu
		redirect(base_url('takeaway/menu'));
	}
	
	/**
	 * Sort the category list order
	 */
	public function sort()
	{
		// Load the menu model
		$this->load->model('menu_model','menumodel');
		
		// Loop through each category and set the position in the list
		foreach ($_GET['cat'] as $position => $item)
			$this->menumodel->setCategoryPosition($item, $position);
	}
	
	/**
	 * View the add category form
	 */
	public function category()
	{
		$this->load->view('menu/edit/category');
	}
	
	/**
	 * Edit a category name
	 *
	 * @param Integer category ID number
	 */
	public function editcat($id)
	{
		// Load the menu model
		$this->load->model('menu_model','menumodel');
		
		// Get the category information
		$data['cat'] = $this->menumodel->getCategory($id);
		
		// Load the edit categoroy view
		$this->load->view('menu/edit/category', $data);
	}
	
	/**
	 * Remove category and all products in the category
	 *
	 * @param Integer category ID number
	 */
	public function catremove($cat)
	{
		// Load the menu model
		$this->load->model('menu_model','menumodel');
		
		// Remove the category
		$this->menumodel->removeCategory($cat);
		
		// Redirect back to the menu
		redirect(base_url('takeaway/menu'));
	}
	
	/**
	 * The javascript to be included on the page
	 */
	private function _javascript()
	{
		return '<script type="text/javascript">
			$(document).ready(function() {
				$("#cats").sortable({
					handle : ".handle",
					placeholder: "category-highlight",
					start: function (event, ui) {
						ui.placeholder.height(ui.helper.height());
					},
					update : function () {
						var order = $("#cats").sortable("serialize");
						$.ajax({
							url: "'.base_url('takeaway/menu/sort/').'?"+order
						});
					}
				});
			});

			function removeItem(id)
			{
				var answer = confirm("Are you sure you wish to delete this item?");
				if (answer){
					window.location = "'.base_url('takeaway/menu/remove/').'/"+id;
				}
			}
			
			function removeCat(id)
			{
				var answer = confirm("Are you sure you wish to delete this category? All products in this category will also be removed! This action cannot be undone.");
				if (answer){
					window.location = "'.base_url('takeaway/menu/catremove/').'/"+id;
				}
			}
			</script>';
	}

}
