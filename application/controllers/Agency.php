<?php // delete end tag

defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Main controller for the AGENCY BRANCH
*/
class Agency extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('Agency/Agency_m');
		$this->security_check_2();
	}

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

	public function index()
	{
		$data = $this->Agency_m->frontpage();
		$this->load_my_views('Agency/frontpage_v', $data);
	}

	// THINK ABOUT SECURITY CHECKS... MAY COMPARE ID AGAINST STATUS. to prevent url browsing on jobs

	public function current_bookings($id=false) // **AJAX *****
	{
		if ($id) {
			$data = $this->Agency_m->agency_indivdual_job($id, 'ACT');
			$this->load_my_views('Common/individual_jobs_all_v', $data);
		} else {
			$data = $this->Agency_m->all_bookings(1); // feeds link into JS file 'ACT'
			$this->load_my_views('Ajax/ajax_table_v', $data);
		}
	}

	public function confirmed_assignments($id=false)
	{
		if ($id) {
			$data = $this->Agency_m->agency_indivdual_job($id, 'ASS');
			$this->load_my_views('Common/individual_jobs_all_v', $data);
		} else {
			$data = $this->Agency_m->all_bookings(2); // feeds link into JS file 'ASS'
			$this->load_my_views('Ajax/ajax_table_v', $data);
		}	
	}
	// table and views for problem bookings
	public function problem_with_bookings($id=false)
	{
		if ($id) {
			$data = $this->Agency_m->agency_indivdual_job($id, 'PRO');
			$this->load_my_views('Common/individual_jobs_all_v', $data);
		} else {
			$data = $this->Agency_m->all_bookings(3); // feeds link into JS file 'PRO'
			$this->load_my_views('Ajax/ajax_table_v', $data);
		}
	}

	// recirculate job
	public function recirculate_this_job($id)
	{
		$this->Agency_m->job_recirculation($id);
		$this->problem_with_bookings();
	}

	// cant deal with job.. reject it entirely.
	public function reject_this_job($id)
	{
		$this->Agency_m->job_rejection($id);
		$this->problem_with_bookings();
	}

	// this just produces the table based on the above link FOR AJAX -- link comes from JS link 2.
	public function ajaxrequest($status)
	{
		$data2 = new stdClass();
		switch ($status) {
			case 1: $status = 'ACT'; break;
			case 2: $status = 'ASS'; break;
			case 3: $status = 'PRO'; break;
		}
		$data2->table = $this->Agency_m->ajax_table_maker($status);
		$this->load->view('Ajax/ajax_value_v', $data2);
	}

	// to deal with agency request form -- will use the client request form view
	public function requests()
	{
		$data = $this->Agency_m->request();
		$this->load_my_views('Common/request_v', $data);
	}

	// to deal with the agency job form
	public function submit()
	{
		$this->load->library('form_validation', $this->Agency_m->request_form_rules);
		if ($this->form_validation->run() == FALSE) {
			$this->requests();
		} else {
			$data = $this->Agency_m->agency_request_result();
			$this->load_my_views('Common/result_modal_v', $data);
		}
	}


	protected function load_my_views($view, $data)
	{
		$this->load->view('Templates/header_v', $data);
		$this->load->view('Agency/nav_agency_v', $data);
		$this->load->view($view, $data);
		$this->load->view('Templates/footer_v', $data);
	}


} // end of class

/* End of file Agency.php */
/* Location: ./application/controllers/Agency.php */


 ?>