<?php // delete end tag

defined('BASEPATH') OR exit('No direct script access allowed');

class Secure extends CI_Controller 
{
		function __construct()
	{
		parent::__construct();
		$this->load->model('Common/Start_m');
		
	}

	public function index()
	{
		$data = $this->Start_m->frontpage();
		$this->load->view('Templates/header_v', $data);
		$this->load->view('Common/login_v', $data);
		$this->load->view('Templates/footer_v', $data);
	}

	public function login()
	{
		$this->load->library('form_validation', $this->Start_m->login_form_rules);
		if ($this->form_validation->run() == FALSE) {
			$this->index();
		} else {
			$result = $this->Start_m->login();
			if (!$result) {
				$this->index();
			} elseif ($result->suspended) {
				$data = $this->Start_m->suspended();
				$this->load->view('Templates/header_v', $data);
				$this->load->view('Common/suspended_v', $data);
				$this->load->view('Templates/footer_v', $data);
			} else {
				switch ($result->access) {
					case 'DOC': redirect('User'); break;
					case 'HOS': redirect('Client'); break;
					case 'AGY': redirect('Agency'); break;
				}
			}
		}
	}



	public function logout()
	{
		$this->Start_m->logout();
		redirect('','refresh');
	}








} // end of class

/* End of file Secure.php */
/* Location: ./application/controllers/Secure.php */

 ?>