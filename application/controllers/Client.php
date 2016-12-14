<?php // delete end tag

defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Main controller for the Client branch
*/
class Client extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('Client/Hosp_m');
		$this->security_check_2();
	}

	private function security_check_2()
	{
		$user_access = $this->session->userdata('access_pass');
		if ($user_access !== 'HOS') {
			switch ($user_access) {
				case 'AGY': redirect('Agency','refresh'); break;
				case 'DOC': redirect('User','refresh'); break;
				default: redirect('','refresh'); break;
			}
		}
	}

	// front page
	public function index()
	{
		$data = $this->Hosp_m->frontpage();
		$this->load_my_views('Client/frontpage', $data);
	}

	// form for staff
	public function requests()
	{
		$data = $this->Hosp_m->request();
		$this->load_my_views('Common/request_v', $data);
	}

	// staff form processing
	public function submit()
	{
		$this->load->library('form_validation', $this->Hosp_m->request_form_rules);
		if ($this->form_validation->run() == FALSE) {
			$this->requests();
		} else {
			$data = $this->Hosp_m->hosp_request_result();
			$this->load_my_views('Common/result_modal_v', $data);
		}
	}

	// list generic listings for PROPOSED jobs
	public function current_bookings($id=false)
	{
		if ($id) {
			$data = $this->Hosp_m->individual_job('ACT', $id);
			$this->load_my_views('Client/individual_job_v', $data);
		} else {
			$data = $this->Hosp_m->proposals();
			$this->load_my_views('Common/table_v', $data);
		}
		
	}

	// list generic listing for ASSIGNED jobs
	public function confirmed_assignments($id=false)
	{
		if ($id) {
			$data = $this->Hosp_m->individual_job('ASS', $id);
			$this->load_my_views('Client/individual_job_v', $data);
		} else {
			$data = $this->Hosp_m->proposals($status='ASS');
			$this->load_my_views('Common/table_v', $data);
	 	}
	}





	// load up the general views each time
	protected function load_my_views($view, $data)
	{
		$this->load->view('Templates/header_v', $data);
		$this->load->view('Client/nav_client_v', $data);
		$this->load->view($view, $data);
		$this->load->view('Templates/footer_v', $data);
	}




} // end of class



/* End of file Client.php */
/* Location: ./application/controllers/Client.php */


 ?>