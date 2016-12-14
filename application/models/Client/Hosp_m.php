<?php //remove end tag

defined('BASEPATH') OR exit('No direct script access allowed');

/**
* first model for use with the clients
*/
class Hosp_m extends MY_Model
{

	protected $table_config_hosp = array(
		'table_open' =>'<table class="table table-striped table-bordered table-hover">',
		);


	function __construct()
	{
		parent::__construct();
	}

	public function frontpage()
	{
		$data = $this->Data_seed_m->client_standard();
		$data = $this->hosp_nav_counts($data);
		return $data;
	}

	public function request()
	{
		$data = $this->Data_seed_m->request_form();
		$data->page_form_link = 'Client/submit';
		$data = $this->hosp_nav_counts($data);
		return $data;
	}


	public function hosp_request_result()
	{
		$data = $this->Data_seed_m->request_result();
		$data->modal_result_text = $this->request_form_process();
		$data->modal_result_link = site_url('Client/');
		$data = $this->hosp_nav_counts($data);
		return $data;
	}

	// get the general table data for proposed and assigned jobs
	public function proposals($status='ACT')
	{
		switch ($status) {
			case 'ACT': $link = 'Client/current-bookings/'; break;
			case 'ASS': $link = 'Client/confirmed-assignments/'; break;
		}

		$this->load->library('table', $this->table_config_hosp);
		$this->load->helper('general');
		$data = $this->Data_seed_m->client_standard();
		$data = $this->hosp_nav_counts($data);
		$table_data = $this->general_jobs_list($status, $this->my_id, false);
		$table_1 = array(
			array('Shift Date', 'Shift Time', 'Skill Requested', 'Action'),
		);
		foreach ($table_data as $row) {
			$feed = array();
			$feed[] = normal_date($row->shift_date);
			$feed[] = time_cut($row->shift_time);
			$feed[] = skill_translate($row->req_skill_1);
			$feed[] = '<a href="' . site_url($link . $row->id) . 
			'" class="btn btn-info"><span class="glyphicon glyphicon-zoom-in"></span> View This!</a>';
			$table_1[] = $feed; 
		}

		$data->table = $this->table->generate($table_1);
		return $data;
	}

	// this might go into the main model..or not
	public function individual_job($status, $id)
	{
		$this->load->helper('general');
		$data = $this->Data_seed_m->client_standard();
		$data = $this->hosp_nav_counts($data);
		$data->job_details = $this->general_jobs_list($status, $this->my_id, $id);
		// clean up the data abit...
		$data->job_details->shift_length = add_hours($data->job_details->shift_length);
		$data->job_details->req_skill_1 = skill_translate($data->job_details->req_skill_1);
		if ($data->job_details->assigned_id) {
			$data->job_details->assigned_id = $this->get_user_realname($data->job_details->assigned_id);
		} else {
			$data->job_details->assigned_id = 'Awaiting Online Assignment...';
		}
		switch ($status) {
			case 'ACT':
				$data->page_button_1 = '<a href="' . site_url('Client/current-bookings') .'" class="btn btn-lg btn-warning">
					<span class="glyphicon glyphicon-backward"></span> Back to List</a>';
				break;
			case 'ASS':
				$data->page_button_1 = '<a href="' . site_url('Client/confirmed-assignments') .'" class="btn btn-lg btn-warning">
					<span class="glyphicon glyphicon-backward"></span> Back to List</a>';
				break;
		}
		return $data;
	}



	protected function hosp_nav_counts($data) 
	{
		$data->nav_no_of_pros = $this->nav_feed_all('ACT', $this->my_id);
		$data->nav_no_of_cons = $this->nav_feed_all('ASS', $this->my_id);
		if ($this->session->has_userdata('username')) {
			$data->log_username = $this->session->userdata('username');
		} else {
			$data->log_username = $this->which_hosp($this->my_id, false);
			$this->session->set_userdata('username', $data->log_username);
		}
		return $data;
	}









} // end of class



/* End of file Hosp_m.php */
/* Location: ./application/models/Client/Hosp_m.php */


 ?>