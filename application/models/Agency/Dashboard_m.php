<?php // delete end tag

/**
* Model for creating and suspending accounts
*/
class Dashboard_m extends MY_Model
{
	public $user_form_rules = array(
		array(
			'field' => 'title',
			'label' => 'title',
			'rules' => 'trim|required|max_length[4]',
			), 
		array(
			'field' => 'first_name',
			'label' => 'First Name',
			'rules' => 'trim|required|max_length[40]',
			), 
		array(
			'field' => 'last_name',
			'label' => 'Last Name',
			'rules' => 'trim|required|max_length[40]',
			),
		array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|max_length[255]|valid_email|required',
			),
		array(
			'field' => 'skill_level',
			'label' => 'Skill Asset',
			'rules' => 'trim|required|max_length[10]',
			),
		);

	public $suspend_form_rules = array(
		array(
			'field' => 'search_form',
			'label' => 'Search Form',
			'rules' => 'trim|required',
			),
		);

	public $suspended_table = array(
		'table_open' => '<table class="table table-bordered table-striped table-hover text-center">',
		'heading_cell_start'  => '<th class="text-center">',
		);
	
	function __construct()
	{
		parent::__construct();
	}

	// Simple data model for new user account
	public function new_user_form()
	{
		$data = $this->Data_seed_m->agency_standard();
		return $data;
	}

	public function process_new_user()
	{
		$page_data = $this->Data_seed_m->agency_standard();
		$user_data = $this->get_user_data();

		// step 1 enter details in all_users_info and get their new my_id
		$insert_array_1 = array(
			'username' => $user_data->username,
			'password' => $user_data->encrypted_password,
			'access' => 'DOC',
			);
		$this->db->insert('all_users_info', $insert_array_1);
		$user_data->new_id = $this->db->insert_id();

		// step 2 enter details into users table
		$insert_array_2 = array(
			'user_id' => $user_data->new_id,
			'title' => $user_data->title,
			'first_name' => $user_data->first_name,
			'last_name' => $user_data->last_name,
			'email' => $user_data->email,
			$user_data->skill_level => 1,
			);
		$this->db->insert('users', $insert_array_2);

		// step3 auto include user for agency jobs
		$this->db->select('id AS client_id', false);
		$this->db->where('access', 'AGY');
		$query = $this->db->get('all_users_info');
		foreach ($query->result() as $row) {
			$row->user_id = $user_data->new_id;
			$row->client_type = 'AGY';
		}
		$this->db->insert_batch('user_hosp_preferences', $query->result());

		// Step 4 return back some of the $data to the view
		$page_data->full_name = $user_data->full_name;
		$page_data->username = $user_data->username;
		$page_data->password = $user_data->password;
		return $page_data;
	}

	// for finding a user using mysql like, and presenting results in a table
	// if user is selected, he will be then suspended on users.
	public function find_user_by_surname($process=false)
	{
		$data = $this->Data_seed_m->agency_standard();
		$data->page_extra_js = '<script src="' . site_url('assets/js/user_buttons.js') .  '"></script>';
		$data->table = '';

		// search for users based on the last_name
		if ($process) {
			$search_term = $this->input->post('search_form', true);
			$this->db->select('user_id, title, first_name, last_name');
			$this->db->like('last_name', $search_term);
			$query = $this->db->get('users')->result();

			// load the results into a table
			$this->load->library('table', $this->suspended_table);
			$this->table->set_heading('Title', 'First Name', 'Last Name', 'Action');
			foreach ($query as $row) {
				$button = '<a href="' . site_url('Dashboard/suspend-this-user/' . $row->user_id) .'"';
				$button .= ' class="btn btn-danger" onclick="return confirm_suspend();">';
				$button .= '<span class="glyphicon glyphicon-alert"></span> Suspend Account</a>';
				$this->table->add_row($row->title, $row->first_name, $row->last_name, $button);
			}
			$data->table = $this->table->generate();

		}
		return $data;
	}

	// suspend a user, check is user, and
	public function suspend_user($id)
	{
		$data = $this->Data_seed_m->request_result();
		$data->modal_result_title = 'Suspend User Result';
		$data->modal_result_link = site_url('Agency');
		$data->suspended_user = $this->is_a_user($id);
		if ($data->suspended_user) {
			$update_array = array('suspended' => 1);
			$this->db->where('user_id', $id);
			$this->db->update('users', $update_array);
			$full_name = $data->suspended_user->title . ' ' . $data->suspended_user->first_name . ' ' . $data->suspended_user->last_name;
			$data->modal_result_text = $full_name . ' has been suspended!';
		} else {
			$data->modal_result_text = 'Invalid user to suspend';
		}
		return $data;
	}

	protected function is_a_user($id)
	{
		$this->db->select('title, first_name, last_name');
		$this->db->where('user_id', $id);
		$query = $this->db->get('users');
		if (count($query->row())) {
			return $query->row();
		} else {
			return false;
		}
	}



	protected function get_user_data()
	{
		$user_data = new stdClass();
		$user_data->title = $this->input->post('title', true);
		$user_data->first_name = $this->input->post('first_name', true);
		$user_data->last_name = $this->input->post('last_name', true);
		$user_data->email = $this->input->post('email', true);
		$user_data->skill_level = $this->input->post('skill_level', true);

		// generate username and password....
		$user_data->full_name = $user_data->title . ' ' . $user_data->first_name . ' ' . $user_data->last_name;
		$user_data->username = $this->make_username($user_data->full_name);
		$user_data->password = $this->generateRandomString();
		$user_data->encrypted_password = $this->encrypt_password($user_data->password);
		return $user_data;
	}

	// to create random password...
	protected function generateRandomString($length = 10) 
	{
	    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ$%#@';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	// create username for everyone by making the initials and adding random 000### number
	protected function make_username($string)
	{
	    $expr = '/(?<=\s|^)[a-z]/i';
	    preg_match_all($expr, $string, $matches);
	    $initials = implode('', $matches[0]);
	    $initials = strtoupper($initials);
	   	$result = $initials . '000' . rand(111, 999);
	   	return $result;
	}





} // end of class



/* End of file Dashboard_m.php */
/* Location: ./application/models/Agency/Dashboard_m.php */



 ?>