<?php // delete end tag

defined('BASEPATH') OR exit('No direct script access allowed');

/**
* controller for the Users section 
*/
class User extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('User/User_m');
		$this->security_check_2();
	}

	private function security_check_2()
	{
		$user_access = $this->session->userdata('access_pass');
		if ($user_access !== 'DOC') {
			switch ($user_access) {
				case 'AGY': redirect('Agency','refresh'); break;
				case 'HOS': redirect('Client','refresh'); break;
				default: redirect('','refresh'); break;
			}
		}
	}

	public function index()
	{
		$data = $this->User_m->frontpage();
		$this->load_my_views('User/frontpage_v', $data);
	}

	public function live_job_offers($id=false)
	{
		if ($id) {
			if ($this->User_m->user_id_check($id)) {
				$data = $this->User_m->user_individual_job($id, 'PRO');
				$this->load_my_views('Common/individual_jobs_all_v', $data);
			} else {
				$data = $this->User_m->frontpage(); // change this?
				$this->load_my_views('Common/not_allowed_v', $data);
			}
			
		} else {
			$data = $this->User_m->user_job_table('PRO');
			$this->load_my_views('Common/table_v', $data);
		}
		
	}

	public function my_confirmed_jobs($id=false)
	{
		if ($id) {
			if ($this->User_m->user_id_check($id)) {
				$data = $this->User_m->user_individual_job($id, 'ASS');
				$this->load_my_views('Common/individual_jobs_all_v', $data);
			} else {
				$data = $this->User_m->frontpage(); // change this?
				$this->load_my_views('Common/not_allowed_v', $data);
			}
		} else {
			$data = $this->User_m->user_job_table('ASS');
			$this->load_my_views('Common/table_v', $data);
		}
	}

	// button to reject a job on the live_job_offers screen
	public function reject_this_job($id)
	{
		$this->User_m->reject_job($id);
		redirect('User/live-job-offers');
	}

	public function assign_me_to_this_job($id)
	{
		$this->User_m->accept_a_job($id);
		redirect('User/live-job-offers');
	}

	protected function load_my_views($view, $data)
	{
		$this->load->view('Templates/header_v', $data);
		$this->load->view('User/nav_user_v', $data);
		$this->load->view($view, $data);
		$this->load->view('Templates/footer_v', $data);
	}





} // end of class



/* End of file User.php */
/* Location: ./application/controllers/User.php */


 ?>