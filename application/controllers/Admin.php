<?php //delete end tag

defined('BASEPATH') OR exit('No direct script access allowed');

/**
*  Controller for AGENCY SUPER FUNCTIONS ONLY
*  PUt here to keep out of the way
*/
class Admin extends MY_Controller
{
	public $which_branch = '';
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('Common/Admin_self_m');
		$this->which_branch = $this->session->userdata('access_pass');	

	}

	// simple data for the change username.. no nav counts
	public function change_my_username()
	{
		$data = $this->Admin_self_m->admin_form();
		$this->load_my_views('Forms/username_f', $data);	
	}

	// when username form is submitted..
	public function username_submit()
	{
		$this->load->library('form_validation', $this->Admin_self_m->username_form_rules);
		if ($this->form_validation->run() ==  FALSE) {
			$this->change_my_username();
		} else {
			$data = $this->Admin_self_m->process_username_form($this->which_branch);
			$this->load_my_views('Common/result_modal_v', $data);	
		}
	}

	// simple data for change password.. no nav counts
	public function change_my_password()
	{
		$data = $this->Admin_self_m->admin_form();
		$this->load_my_views('Forms/password_f', $data);
	}

	// when password form is submitted
	public function password_submit()
	{
		$this->load->library('form_validation', $this->Admin_self_m->password_form_rules);
		if ($this->form_validation->run() ==  FALSE) {
			$this->change_my_password();
		} else {
			$data = $this->Admin_self_m->process_password_form($this->which_branch);
			$this->load_my_views('Common/result_modal_v', $data);	
		}
	}



	// will have to load the individual nav menu for each branch
	protected function load_my_views($view, $data)
	{
		$this->load->view('Templates/header_v', $data);
		switch ($this->which_branch) {
			case 'HOS': $this->load->view('Client/nav_client_v', $data); break;
			case 'AGY': $this->load->view('Agency/nav_agency_v', $data); break;
			case 'DOC': $this->load->view('User/nav_user_v', $data); break;	
		}
		$this->load->view($view, $data);
		$this->load->view('Templates/footer_v', $data);
	}






} // end of class




/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */


 ?>