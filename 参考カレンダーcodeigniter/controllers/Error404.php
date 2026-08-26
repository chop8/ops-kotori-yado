<?php
class Error404 extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		
		//Import
		$this->load->helper(array('url'));
		
		//Model
		//$this->load->model('Users');
		
		//MY_library
		//$this->load->library('MY_libraries');
		//$this->load->library('MY_user_auth');
	}

	/**
	 * 404
	 */
	public function index() {
		//header
		$this->output->set_status_header('404');
		
		//view
		$this->smarty->assign('base_url', base_url());
		
		$this->smarty->assign('pageTitle', 'ページが見つかりません');
		$this->smarty->assign('menu_on', '404');
		
		$this->smarty->assign('include_file', 'include_404.html');
		$this->smarty->display('main_frame.html');
	}
}
?>