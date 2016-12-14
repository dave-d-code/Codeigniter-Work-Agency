<?php // delete end tag

defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Super admin functions for AGENCY branch only
*/
class Dashboard extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->security_check_2();
		$this->load->model('Agency/Dashboard_m');
	}

	// to create a new user account
	public function create_user_account()
	{
		$data = $this->Dashboard_m->new_user_form();
		$this->load_my_views('Forms/create_user_f', $data);
	}

	// to process the above new user account
	public function process_new_user()
	{
		$this->load->library('form_validation', $this->Dashboard_m->user_form_rules);
		if ($this->form_validation->run() == FALSE) {
			$this->create_user_account();
		} else {
			$data = $this->Dashboard_m->process_new_user();
			$this->load_my_views('Agency/new_user_result_v', $data);
		}
	}

	// suspend a user account form
	public function suspend_user_account()
	{
		$this->load->library('form_validation', $this->Dashboard_m->suspend_form_rules);
		if ($this->form_validation->run() == FALSE) {
			$data = $this->Dashboard_m->find_user_by_surname();
		} else {
			$data = $this->Dashboard_m->find_user_by_surname(true);
		}
		$this->load_my_views('Forms/suspend_f', $data);
	}

	// actual process of suspending a user
	public function suspend_this_user($id)
	{
		$data = $this->Dashboard_m->suspend_user($id);
		$this->load_my_views('Common/result_modal_v', $data);
	}

	protected function load_my_views($view, $data)
	{
		$this->load->view('Templates/header_v', $data);
		$this->load->view('Agency/nav_agency_v', $data);
		$this->load->view($view, $data);
		$this->load->view('Templates/footer_v', $data);
	}

	// check that only agency is here...
	private function security_check_2()
	{
		$user_access = $this->session->userdata('access_pass');
		if ($user_access !== 'AGY') {
			switch ($user_access) {
				case 'DOC': redirect('User','refresh'); break;
				case 'HOS': redirect('Client','refresh'); break;
				default: redirect('','refresh'); break;
			}
		}
	}






} // end of class

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */


 ?>