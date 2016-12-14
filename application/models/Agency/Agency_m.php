<?php // delete end tag

/**
* Main Agency Model from MY Model
*/
class Agency_m extends MY_Model
{

	protected $table_config_agency = array(
		'table_open' =>'<table class="table table-striped table-bordered table-hover">',
		'heading_cell_start'    => '<th class="',
		'cell_start'            => '<td class="',
		'cell_alt_start'        => '<td class="',
		);
	
	function __construct()
	{
		parent::__construct();
	}

	// simple model for the frontpage	
	public function frontpage()
	{
		$data = $this->Data_seed_m->agency_standard();
		$data = $this->agency_nav_counts($data);
		return $data;
	}

	// data model for ajax table bookings.
	// 1 is 'ACT', 2 is 'ASS', 3 is 'PRO'
	public function all_bookings($link)
	{
		$data = $this->Data_seed_m->agency_ajax();
		$data = $this->agency_nav_counts($data);
		$data->page_js_link_2 = '<script>var myDirectory = "' . site_url('/Agency/ajaxrequest/' . $link) .  '";</script>';
		return $data;
	}

	// print out individual jobs for the section
	public function agency_indivdual_job($id, $status)
	{
		$this->load->helper('general');
		$data = $this->Data_seed_m->agency_individual_job();
		$data = $this->agency_nav_counts($data);
		$data->job_details = $this->general_jobs_list($status, false, $id);

		// clean up general data for presentation
		$data->job_details->client = $this->which_hosp($data->job_details->client_id, true);
		$data->job_details->shift_length = add_hours($data->job_details->shift_length);
		$data->job_details->req_skill_1 = skill_translate($data->job_details->req_skill_1);
		$data->job_details->shift_time = time_cut($data->job_details->shift_time);
		$data->job_details->shift_date = normal_date($data->job_details->shift_date);
		$data->job_details->comments = htmlentities($data->job_details->comments);

		switch ($status) {
			case 'ACT': 
				$data->agency_panel_title = 'Job Status Stats:';
				$data->agency_panel_text_1 = 'Number of Users currently assigned:   ' . $this->user_job_count($id, 'PRO');
				$data->agency_panel_text_2 = 'Number of Users whom have rejected: ' . $this->user_job_count($id, 'REJ');
				$data->page_button_1 = '<a href="' . site_url('Agency/current-bookings') .'" class="btn btn-lg btn-warning">
					<span class="glyphicon glyphicon-backward"></span> Back to List</a>';
				break;
			case 'ASS': 
				$data->agency_panel_title = 'Name of Assigned User:';
				$data->agency_panel_text_1 = $this->get_user_realname($data->job_details->assigned_id);
				$data->page_button_1 = '<a href="' . site_url('Agency/confirmed-assignments') .'" class="btn btn-lg btn-warning">
					<span class="glyphicon glyphicon-backward"></span> Back to List</a>';
				break;
			case 'PRO': 
				$data->agency_panel_title = 'Nature of Problem';
				$data->agency_panel_text_1 = $data->job_details->problem_reason;
				$data->page_button_1 = '<a href="' . site_url('Agency/problem-with-bookings') .'" class="btn btn-lg btn-warning">
					<span class="glyphicon glyphicon-backward"></span> Back to List</a>';
				$data->page_button_2 = '<a href="' . site_url('Agency/reject-this-job/' . $id) .'" class="btn btn-lg btn-danger" onclick="return confirm_reject();"
				 data-toggle="tooltip" title="Reject and delete this job, and contact the hospital. Consider recirculation over time first.."><span class="glyphicon glyphicon-trash"></span> REJECT THIS JOB</a>';
				$data->page_button_3 = '<a href="' . site_url('Agency/recirculate-this-job/' . $id) .'" class="btn btn-lg btn-success"
				data-toggle="tooltip" title="Circumstances change over time! Ask the site to find more users for this job again." >
					<span class="glyphicon glyphicon-repeat"></span> RECIRCULATE TO USERS</a>';
				$data->page_extra_js = '<script src="' . site_url('assets/js/user_buttons.js') .  '"></script>';
				break;		
		}
		return $data;
	}

	// only produces a table string.. for use with AJAX hopefully ff
	public function ajax_table_maker($status)
	{
		switch ($status) {
			case 'ACT': $link = 'Agency/current-bookings/'; break;
			case 'ASS': $link = 'Agency/confirmed-assignments/'; break;
			case 'PRO': $link = 'Agency/problem-with-bookings/'; break;
		}

		$this->load->library('table', $this->table_config_agency);
		$this->load->helper('general');
		$table_data = $this->general_jobs_list($status, false, false);
		$table_1 = array(
			array('hidedata">id','">Hospital', '">Shift Date', '">Shift Time', '">Skill Requested', '">Action'),
		);
		foreach ($table_data as $row) {
			$feed = array();
			$feed[] = 'hidedata">' . $row->id;
			$feed[] = '">' . $this->which_hosp($row->client_id, false);
			$feed[] = '">' . normal_date($row->shift_date);
			$feed[] = '">' . time_cut($row->shift_time);
			$feed[] = '">' . skill_translate($row->req_skill_1);
			$feed[] = '">' . '<a href="' . site_url($link . $row->id) . 
			'" class="btn btn-info"><span class="glyphicon glyphicon-zoom-in"></span> View This!</a>';
			$table_1[] = $feed; 
		}

		return $this->table->generate($table_1);
	}

	// data model for the agency request form
	public function request()
	{
		$data = $this->Data_seed_m->request_form();
		$data->page_form_link = 'Agency/submit';
		$data = $this->agency_nav_counts($data);
		return $data;
	}

	// result modal for when agency circulates a job
	public function agency_request_result()
	{
		$data = $this->Data_seed_m->request_result();
		$data->modal_result_text = $this->request_form_process(true);
		$data->modal_result_link = site_url('Agency/');
		$data = $this->agency_nav_counts($data);
		return $data;
	}

	// to recirculate a problem job
	public function job_recirculation($id)
	{
		$job_details = $this->general_jobs_list('PRO', false, $id); // get the details of the job
		if (!$job_details) { // in case false id is given
			$this->session->set_flashdata('msg', 'Unable to locate this problem job in the database');
			return false;
		}
		$job_details->job_id = $id;
		$result = $this->user_match($job_details, true);
		if ($result) {
			$this->found_users_recirculation($id);
			$msg = 'Success! The job has been circulated to ' . $result . ' users!';
			$this->session->set_flashdata('msg', $msg);
			return true;
		} else {
			$this->session->set_flashdata('msg', 'Unable to find any users for this job. Consider waiting longer or rejecting the job altogether.');
			return false;
		}

	}

	// to reject a problem job from requested_jobs
	public function job_rejection($id)
	{
		$job_details = $this->general_jobs_list('PRO', false, $id); // get the details of the job
		if (!$job_details) { // in case false id is given
			$this->session->set_flashdata('msg', 'Unable to locate this problem job in the database');
			return false;
		}
		$update_array = array(
			'status' => 3,
			'rejection_reason' => 'Problem Job! Rejected by the Agency!',
			);
		$this->db->where('id', $id);
		$this->db->update('requested_jobs', $update_array);
		$this->session->set_flashdata('msg', 'The job has been rejected. Update the Hospital.');
	}

	// to turn requested_jobs back to ACT after job recirculation
	protected function found_users_recirculation($id)
	{
		$update_array = array('status' => 1);
		$this->db->where('id', $id);
		$this->db->update('requested_jobs', $update_array);
	}


	// to calculate values for the agency menu
	protected function agency_nav_counts($data)
	{
		$data->nav_no_of_pros = $this->nav_feed_all('ACT', false);
		$data->nav_no_of_cons = $this->nav_feed_all('ASS', false);
		$data->nav_no_of_problems = $this->nav_feed_all('PRO', false);
		if ($this->session->has_userdata('username')) {
			$data->log_username = $this->session->userdata('username');
		} else {
			$data->log_username = $this->which_hosp($this->my_id, false);
			$this->session->set_userdata('username', $data->log_username);
		}
		return $data;
	}


	// to count users attached to a job
	protected function user_job_count($job_id, $status)
	{
		$this->db->select('COUNT(user_id) AS result', false);
		$this->db->where('job_status', $status);
		$this->db->where('job_id', $job_id);
		$query = $this->db->get('user_current_jobs');
		return $query->row()->result;
	}





} // end of class

/* End of file Agency_m.php */
/* Location: ./application/models/Agency/Agency_m.php */


 ?>