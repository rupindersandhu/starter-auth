<?php

/**
 * core/MY_Controller.php
 *
 * Default application controller
 *
 * @author		JLP
 * @copyright           2010-2013, James L. Parry
 * ------------------------------------------------------------------------
 */
class Application extends CI_Controller {

	protected $data = array();   // parameters for view components
	protected $id; // identifier for our content

	/**
	 * Constructor.
	 * Establish view parameters & load common helpers
	 */

	function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->data['title'] = "Top Secret Government Site"; // our default title
		$this->errors = array();
		$this->data['pageTitle'] = 'welcome';   // our default page
	}

	/**
	 * Render this page
	 */
	function render()
	{
		$mychoices = array('menudata' => $this->makemenu());
		$this->data['menubar'] = $this->parser->parse('_menubar', $mychoices, true);
		$this->data['content'] = $this->parser->parse($this->data['pagebody'], $this->data, true);

		// finally, build the browser page!
		$this->data['data'] = &$this->data;
		$this->parser->parse('_template', $this->data);
	}

	// build menu choices depending on the user role
	function makemenu()
	{
		$choices = array();
                
		$choices[] = array('name' => "Alpha", 'link' => '/alpha');
                
                if ($this->has_access([ROLE_USER, ROLE_ADMIN])) { 
                    $choices[] = array('name' => "Beta", 'link' => '/beta'); 
                }
                if ($this->has_access(ROLE_ADMIN)) { 
                    $choices[] = array('name' => "Gamma", 'link' => '/gamma'); 
                }
		if (!$this->session->userdata('userName')) {
                    $choices[] = array('name' => "Login", 'link' => '/auth');
                } else {
                    $choices[] = array('name' => "Welcome, ".$this->session->userdata('userName')." (Logout)", 'link' => '/auth/logout');
                }
		return $choices;
	}
        
        function has_access($roleNeeded = null) {
            $userRole = $this->session->userdata('userRole');
            if ($roleNeeded == null) {
                return true;
            }
            if (is_array($roleNeeded)) {
                if (in_array($userRole, $roleNeeded)) {
                    return true;
                }
            } else if (strcmp($userRole, $roleNeeded) == 0) {
                return true;
            }
            return false;
        }
        
        function restrict($roleNeeded = null) {
            $userRole = $this->session->userdata('userRole');
            if ($roleNeeded != null) {
                if (is_array($roleNeeded)) {
                    if (!in_array($userRole, $roleNeeded)) {
                        redirect("/");
                        return;
                    }
                } else if ($userRole != $roleNeeded) {
                    redirect("/");
                    return;
                }
            }
        }

}

/* End of file MY_Controller.php */
/* Location: application/core/MY_Controller.php */