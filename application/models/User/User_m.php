<?php // delete end tag

/**
* User model for workers to view, accept or decline jobs etc
* also includes some self admin
*/
class User_m extends MY_Model
{

	protected $table_config_user = array(
		'table_open' =>'<table class="table table-striped table-bordered table_hover">',
		);

	function __construct()
	{
		parent::__construct();
	}

	// simple frontpage display model
	public function frontpage()
	{
		$data = $this->Data_seed_m->user_standard();
		$data = $this->nav_stats($data);
		return $data;
	}

	// view both live job offers and confirmed jobs in table form
	public function user_job_table($status)
	{
		switch ($status) {
			case 'PRO':$link = 'User/live-job-offers/'; break;
			case 'ASS':$link = 'User/my-confirmed-jobs/'; break;
		}

		$this->load->helper('general');
		$this->load->library('table', $this->table_config_user);
		$data = $this->Data_seed_m->user_standard();
		$data = $this->nav_stats($data);
		$table_feed = array(array('Hospital', 'Shift Date', 'Shift Time', 'Action'),);
		$table_data = $this->user_job_results($status, false, false);
		foreach ($table_data as $row) {
			$feed = array();
			$feed[] = $this->which_hosp($row->client_id);
			$feed[] = normal_date($row->shift_date);
			$feed[] = time_cut($row->shift_time);
			$feed[] = '<a href="' . site_url($link . $row->id) . '"
			 class="btn btn-info"><span class="glyphicon glyphicon-zoom-in"></span> View This!</a>';
			$table_feed[] = $feed;
		}
		$data->table = $this->table->generate($table_feed);
		return $data;	
	}

	// to look at individual jobs // SECURITY DONE in the controller...
	public function user_individual_job($id, $status)
	{
		$this->load->helper('general');
		$data = $this->Data_seed_m->user_standard();
		$data = $this->nav_stats($data);
		// clean up the data...
		$data->job_details = $this->user_job_results($status, false, $id);
		$data->job_details->client = $this->which_hosp($data->job_details->client_id, true);
		$data->job_details->shift_length = add_hours($data->job_details->shift_length);
		$data->job_details->req_skill_1 = skill_translate($data->job_details->req_skill_1);
		$data->job_details->shift_time = time_cut($data->job_details->shift_time);
		$data->job_details->shift_date = normal_date($data->job_details->shift_date);
		$data->job_details->comments = htmlentities($data->job_details->comments);
		// Throw the buttons in.
		switch ($status) {
			case 'PRO':
				$data->page_button_1 = '<a href="' . site_url('User/live-job-offers') .'" class="btn btn-lg btn-warning">
					<span class="glyphicon glyphicon-backward"></span> Back to List</a>';
				$data->page_button_2 = '<a href="' . site_url('User/reject-this-job/' . $data->job_details->id) .'" class="btn btn-lg btn-danger"
				 data-toggle="tooltip" title="Reject Button will permanently delete this job from your list">
					<span class="glyphicon glyphicon-remove"></span> REJECT THIS JOB</a>';
				$data->page_button_3 = '<a href="' . site_url('User/assign-me-to-this-job/' . $data->job_details->id) .'" class="btn btn-lg btn-success"
				data-toggle="tooltip" title="Assign Button will mean that you agree to take this job and will commit to it!" 
				onclick="return confirm_deploy()">
					<span class="glyphicon glyphicon-ok"></span> ASSIGN ME TO THIS JOB</a>';
				$data->page_extra_js = '<script src="' . site_url('assets/js/user_buttons.js') .  '"></script>';
				break;
			
			case 'ASS' :
				$data->page_button_1 = '<a href="' . site_url('User/my-confirmed-jobs') .'" class="btn btn-lg btn-warning">
					<span class="glyphicon glyphicon-backward"></span> Back to List</a>';
				$data->page_button_2 = '';
				$data->page_button_3 = '';
				break;
		}
		
		return $data;

	}

	// conduit for the controller to check if a user can look at a job
	public function user_id_check($id)
	{
		return $this->is_your_job($id, true);
	}

	// to reject a job that the user is assigned too.
	// if successful.. check to see if everyone has rejected a job
	public function reject_job($job_id)
	{
		if ($this->is_your_job($job_id, true)) {
			$this->db->where('job_id', $job_id);
			$this->db->where('user_id', $this->my_id);
			$this->db->update('user_current_jobs', array('job_status' => 4));
			if ($this->db->affected_rows()) {
				$this->session->set_flashdata('msg', '<div class="alert alert-success">You have rejected the job!</div>');
			} else {
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Unable to reject this job!</div>');	
			}
			$this->check_job_rejections($job_id);

		} else {
			$this->session->set_flashdata('msg', '<div class="alert alert-danger">This job is not assigned to you!</div>');
		}
		
	}

	public function accept_a_job($job_id)
	{
		if ($checkdata = $this->is_your_job($job_id, true)) { // check if job available to user
			// Step 1 - assign on user_current_jobs
			$this->db->where('job_id', $job_id);
			$this->db->where('user_id', $this->my_id);
			$this->db->update('user_current_jobs', array('job_status' => 2));

			// Step 2 - show ASS on requested_jobs
			$this->db->where('id', $job_id);
			$this->db->update('requested_jobs', array('status' => 2, 'assigned_id'=>$this->my_id));

			// Step 3 - show as unavailable for the day
			$insert_data1 = array(
					'user_id' => $this->my_id,
					'shift_date' => $checkdata->shift_date,
				);
			$this->db->insert('user_not_avail', $insert_data1);

			// Step 4 KIK everyone else of the job
			$this->db->where('job_id', $job_id);
			$this->db->where('job_status', 'PRO');
			$this->db->update('user_current_jobs', array('job_status' => 3));

			// Step 5 plus one reliability score for user
			$this->db->set('reliability', 'reliability + 1', false);
			$this->db->where('user_id', $this->my_id);
			$this->db->update('users');

			// Step 6 cancel user's other job proposals for the day and check you dont shaft another job!
			$this->dont_shaft_a_job($checkdata->shift_date);
			$this->session->set_flashdata('msg', '<div class="alert alert-success">The Job has been successfully assigned to you!</div>');

		} else {
			$this->session->set_flashdata('msg', 'This job is no longer assigned to you!');
		}
	}

	// when a user accepts a job, make sure he doesnt shaft another job by rejecting it by leaving no users
	// Step 1 see what other jobs the user has.
	// Step 2 user rejects other jobs
	// Step 3 check_job_rejections to make sure someone is left
	protected function dont_shaft_a_job($shift_date)
	{
		$this->db->select('job_id');
		$this->db->where('user_id', $this->my_id);
		$this->db->where('shift_date', $shift_date);
		$this->db->where('job_status', 'PRO');
		$job_list = $this->db->get('user_current_jobs');
		if (count($job_list->result())) {    // if there are any other jobs for the user
			foreach ($job_list->result() as $row) {
				$this->reject_other_jobs($shift_date, $row->job_id); // reject the other jobs
				$this->check_job_rejections($row->job_id);
			}
		}		
	}

	// user to reject day's job one by one
	protected function reject_other_jobs($shift_date, $job_id)
	{
		$this->db->where('user_id', $this->my_id);
		$this->db->where('shift_date', $shift_date);
		$this->db->where('job_status', 'PRO');
		$this->db->where('job_id', $job_id);
		$this->db->update('user_current_jobs', array('job_status' => 4));
	}


	// update requested_jobs if all users have rejected
	protected function check_job_rejections($job_id)
	{
		if (!$this->any_user_pro_left($job_id)) {
			$this->db->where('id', $job_id);
			$data = array(
				'status' => 4,
				'problem_reason' => 'All selected users have rejected this job!',
				);
			$this->db->update('requested_jobs', $data);
		}
	}

	// check that a job has PRO status users left
	protected function any_user_pro_left($job_id)
	{	
		$this->db->select('user_id');
		$this->db->where('job_id', $job_id);
		$this->db->where('job_status', 'PRO');
		$query = $this->db->get('user_current_jobs');
		if (count($query->result())) {
			return true;
		} else {
			return false;
		}

	}


	// check the user is dealing with his own job
	protected function is_your_job($job_id, $current_job=false) 
	{
		$this->db->where('job_id', $job_id);
		$this->db->where('user_id', $this->my_id);
		$this->db->select('shift_date');
		if ($current_job) {
			$this->db->where('(job_status', "'PRO' OR job_status = 'ASS')", false);
		}
		$query = $this->db->get('user_current_jobs', 1);
		if ($query->row()) {
			return $query->row();
		} else {
			return false;
		}
	}


	// get the results of outstanding jobs for the user
	// $status to get confirmed jobs as well
	// THIS LOOKS REDUNDANT SEE BELOW
	protected function user_jobs_query($status='PRO')
	{
		$this->db->select('requested_jobs.id, requested_jobs.shift_date, shift_time, client_name, client_location');
		$this->db->join('user_current_jobs', 'requested_jobs.id = user_current_jobs.job_id');
		$this->db->join('all_users_info', 'requested_jobs.client_id = all_users_info.id');
		$this->db->where('job_status', $status);
		$this->db->where('user_id', $this->my_id);
		$this->db->where('requested_jobs.shift_date >=', 'DATE(NOW())', false);
		$this->db->order_by('requested_jobs.shift_date', 'asc');
		$query = $this->db->get('requested_jobs');
		return $query->result();
	}

	// to make the user nav stats
	protected function nav_stats($data)
	{
		$data->nav_no_of_pros = $this->user_job_results('PRO', true, false);
		$data->nav_no_of_cons = $this->user_job_results('ASS', true, false);
		if ($this->session->has_userdata('username')) {
			$data->log_username = $this->session->userdata('username');
		} else {
			$data->log_username = $this->get_user_realname($this->my_id);
			$this->session->set_userdata('username', $data->log_username);
		}
		return $data;
	}


	// to return the user results for all cases
	protected function user_job_results($status, $stats_only=false, $job_id=false) // need to check if its their job...
	{
		if ($stats_only) {
			$this->db->select('COUNT(a.id) AS result', false);
		} elseif ($job_id) {
			$this->db->select('a.id, a.client_id, a.shift_date, a.shift_time, a.shift_length, a.req_skill_1, a.comments');
			$this->db->where('a.id', $job_id);
		} else {
			$this->db->select('a.id, a.client_id, a.shift_date, a.shift_time');
		}
		$this->db->join('user_current_jobs', 'a.id = user_current_jobs.job_id');
		$this->db->where('a.shift_date >=', 'DATE(NOW())', false);
		$this->db->where('user_id', $this->my_id);
		$this->db->where('job_status', $status);
		$query = $this->db->get('requested_jobs AS a');

		if ($stats_only) {
			return $query->row()->result;
		} elseif ($job_id) {
			return $query->row();
		} else {
			return $query->result();
		}	
	}





} // end of class









/* End of file User_m.php */
/* Location: ./application/models/User/User_m.php */


 ?>