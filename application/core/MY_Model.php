<?php // delete end tag
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Core Model for all models. 
* Main purpose to share common sql functions etc
*/
class MY_Model extends CI_Model
{

	// form validation rules placed in Model to keep the controller code thin.

	public $request_form_rules = array(
		array(
			'field' => 'shift_date',
			'label' => 'Shift Date',
			'rules' => 'trim|required|max_length[11]|alpha_dash',
			), 
		array(
			'field' => 'shift_time',
			'label' => 'Shift Time',
			'rules' => 'trim|required|max_length[10]',
			), 
		array(
			'field' => 'shift_length',
			'label' => 'Shift Length',
			'rules' => 'trim|required|max_length[3]',
			),
		array(
			'field' => 'comments',
			'label' => 'Comments',
			'rules' => 'trim|max_length[2000]',
			),
		array(
			'field' => 'req_skill_1',
			'label' => 'Skill 1',
			'rules' => 'trim|required|max_length[10]',
			),
		);

	// Unique ID for every individual to be used later for database queries
	protected $my_id;

	function __construct()
	{
		parent::__construct();
		$this->load->model('Common/Data_seed_m');
		$this->my_id = $this->session->userdata('set_id');
	}

	
	// final process of the hosp/ agency staff request form
	public function request_form_process($agency=false)
	{
		$request_data = $this->job_request_data(); // get the form data in one object.
		$request_data->job_id = $this->create_req_job($request_data); // 1st db submission & job_id returned
		$result = $this->user_match($request_data); // 2nd db submission $ no. of users assigned returned
		if (!$result) {
			$this->no_users_for_job($request_data); // If no users found for the job!
			if ($agency) {
				return 'No Matching users were found! The Request is in the problem bookings table!';
			} else {
				return 'No Matching Users were found! Your Request has been flagged straight to the Agency!';
			}
		} else {
			return $result . ' Users were allocated to your request. You await their online response!';
		}
	}

	// to get the actual name of the user // everyone to use
	public function get_user_realname($id)
	{
		$this->db->select('title, first_name, last_name');
		$this->db->where('user_id', $id);
		$query = $this->db->get('users', 1);
		$result = $query->row();
		return $result->title . ' ' . $result->first_name . ' ' . $result->last_name;
	}


	// to create the initial job in requested jobs and get job id
	// can add req_skill_2 etc later if needed
	protected function create_req_job ($request_data)
	{	
		$insert_data = array(
			'client_id' => $this->my_id,
			'shift_date' => $request_data->shift_date,
			'shift_time' => $request_data->shift_time,
			'shift_length'=> $request_data->shift_length,
			'comments' => $request_data->comments,
			'req_skill_1' => $request_data->req_skill_1,
			);
		$this->db->insert('requested_jobs', $insert_data);
		return $this->db->insert_id();
	}


	// to select and insert into db users whom match the job
	// amended so that agency can recirculate
	protected function user_match($request_data, $is_recirculation=false)
	{
		$job_id = $request_data->job_id;
		$shift_date = $request_data->shift_date;
		$req_skill_1 = $request_data->req_skill_1;
		if ($is_recirculation) {
			$client_id = $request_data->client_id;
		} else {
			$client_id = $this->my_id;
		}


		$this->db->select('user_id');
		// only use those assigned to the client hospital // MAY REVIEW THIS TO ALLOW AGENCY TO RECRUIT DIRECTLY FOR A HOSP.
		$sql1 = "SELECT user_id FROM user_hosp_preferences WHERE client_id = " . $client_id;
		$this->db->where_in('user_id', $sql1, false);

		// eliminate those working on the day
		$sql2 = "SELECT user_id FROM user_not_avail WHERE shift_date = '{$shift_date}'";
		$this->db->where_not_in('user_id', $sql2, false);

		// eliminate those with 2 job proposals already **** can alter 2 later if needed *******
		$sql3 = "SELECT user_id FROM user_current_jobs WHERE shift_date = '{$shift_date}' AND job_status = 'PRO' GROUP BY user_id HAVING COUNT(*) >= 2";
		$this->db->where_not_in('user_id', $sql3, false);

		// match the skill set
		$this->db->where("{$req_skill_1}", 1);

		// exclude those suspended
		$this->db->where('suspended !=', 1);

		//order by reliability
		$this->db->order_by('reliability', 'desc');
		$query = $this->db->get('users', 5); // limit 5		
		$result = $query->result();
		
		// check that you have some users, if zero users return false
		if (!count($result)) {
			return false;
		}
		// add the job number and shift date
		foreach ($result as $row) {
			$row->job_id = $job_id;
			$row->shift_date = $shift_date;
		}

		// insert the data into user_current_jobs
		$this->db->insert_batch('user_current_jobs', $result);
		return $this->db->affected_rows();

	} // end of function

	// to highlight a job as 'PRO' if no users could be found for a job request
	protected function no_users_for_job($request_data)
	{
		$update_array = array(
				'status' => 4,
				'problem_reason' => 'No matching users could be found for this job!'
			);
		$this->db->where('id', $request_data->job_id);
		$this->db->update('requested_jobs', $update_array);
	}


	// To get all form data for the request form and just clean it here, and it pass around
	protected function job_request_data() 
	{
		$this->load->helper('general');
		$request_data = new stdClass();
		$request_data->shift_date = sql_date($this->input->post('shift_date', true));
		$request_data->shift_time = sql_time($this->input->post('shift_time', true)); // this is shit. keep an eye on it.
		$request_data->shift_length = $this->input->post('shift_length', true);
		$request_data->comments = $this->input->post('comments', true);
		$request_data->req_skill_1 = $this->input->post('req_skill_1', true);
		return $request_data;
	}

	// count the numbers of jobs for client and agency for the nav menu numbers
	protected function nav_feed_all($status, $hosp_id=false)
	{
		$this->db->select('COUNT(id) AS result', false);
		$this->db->where('status', $status);
		$this->db->where('shift_date >=', 'DATE(NOW())', false);
		if ($hosp_id) {
			$this->db->where('client_id', $hosp_id);
		}
		$query = $this->db->get('requested_jobs');
		return $query->row()->result;
	}

	// to list out jobs list - both ACT ASS,  for agency and hosp. // build this to show prob's later on!!
	protected function general_jobs_list($status, $hosp_id=false, $id=false)
	{
		if (!$hosp_id && !$id) { // Agency general list
			$select = 'id, client_id, shift_date, shift_time, req_skill_1';
		}

		if (!$hosp_id && $id) { // Agency specific item
			$select = 'id, client_id, shift_date, shift_time, shift_length, req_skill_1, comments, assigned_id, problem_reason';
			$this->db->where('id', $id);
		}

		if ($hosp_id && !$id) { // Hosp General list
			$select = 'id, shift_date, shift_time, req_skill_1';
			$this->db->where('client_id', $hosp_id);
		}

		if ($hosp_id && $id) { // Hosp specific item
			$select = 'id, shift_date, shift_time, shift_length, req_skill_1, comments, assigned_id';
			$this->db->where('id', $id);
		}

		$this->db->select($select);
		$this->db->where('shift_date >=', 'DATE(NOW())', false);
		$this->db->where('status', $status);
		$this->db->order_by('shift_date', 'asc');
		$query = $this->db->get('requested_jobs');
		if ($id) {
			return $query->row();
		} else {
			return $query->result();
		}
		
	}

	// to see which hospital is being talked about.
	protected function which_hosp($id, $need_locn=false)
	{
		$this->db->select('client_name, client_location');
		$this->db->where('id', $id);
		$query = $this->db->get('all_users_info', 1);
		if ($need_locn) {
			return $query->row();
		} else {
			return $query->row()->client_name;
		}

	}

	// Copied snippet for php 5.4 to produce blowfish password
	// can replace when php 5.5 is available online
	protected function encrypt_password($password) 
	{ 

		$hash_format = "$2y$10$";
		$salt_length = 22;
		$salt = $this->generate_salt($salt_length);
		$format_and_salt = $hash_format . $salt;
		$hash = crypt($password, $format_and_salt);
		return $hash; 
	}

	// salting for above function
	protected function generate_salt($length) 
	{

		$unique_random_string = md5(uniqid(mt_rand(), true));

		$base64_string = base64_encode($unique_random_string);

		$modified_base64_string = str_ireplace('+', '.', $base64_string);

		$salt = substr($modified_base64_string, 0, $length);

		return $salt; 
	}



	






} // end of class


/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */

/**
* 
*/


 ?>