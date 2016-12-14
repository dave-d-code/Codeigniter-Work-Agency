<?php // remove end tag

defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Self Admin functions for everyone
*/
class Admin_self_m extends MY_Model
{

	public $username_form_rules = array(
			array(
				'field' => 'username',
				'label' => 'Username',
				'rules' => 'min_length[8]|max_length[30]|trim|required|is_unique[all_users_info.username]'
				),
		);

	public $password_form_rules = array(
			array(
				'field' => 'password_1',
				'label' => 'Password',
				'rules' => 'min_length[8]|max_length[30]|trim|required'
				),
			array(
				'field' => 'password_2',
				'label' => 'Password Confirmation',
				'rules' => 'min_length[8]|max_length[30]|trim|required|matches[password_1]'
				),
		);


	function __construct()
	{
		parent::__construct();
	}

	// basic data for the change username form // will use for pw as well..
	public function admin_form()
	{
		$data = $this->Data_seed_m->agency_standard();
		return $data;
	}

	// process the username form
	public function process_username_form($branch)
	{
		$data = $this->Data_seed_m->request_result();
		$data->modal_result_title = 'Username Form Result';
		$data->modal_result_link = $this->which_link($branch);
		$data->modal_result_text = $this->change_username();
		return $data;
	}

	public function process_password_form($branch)
		{
		$data = $this->Data_seed_m->request_result();
		$data->modal_result_title = 'Password Form Result';
		$data->modal_result_link = $this->which_link($branch);
		$data->modal_result_text = $this->change_password();
		return $data;
	}

	// which modal link to follow back..
	protected function which_link($branch)
	{
		switch ($branch) {
			case 'HOS': $str = site_url('Client'); break;
			case 'AGY': $str = site_url('Agency'); break;
			case 'DOC': $str = site_url('User'); break;	
		}
		return $str;
	}

	protected function change_username()
	{
		$safe_username = $this->input->post('username', true);
		$this->db->where('id', $this->my_id);
		$this->db->update('all_users_info', array('username' => $safe_username));
		if ($this->db->affected_rows()) {
			return 'Your username has been changed!';
		} else {
			return 'Your username could NOT be changed.';
		}
	}

	protected function change_password()
	{
		$old_password = $this->input->post('password_1', true);
		$new_password = $this->encrypt_password($old_password);
		$this->db->where('id', $this->my_id);
		$this->db->update('all_users_info', array('password' => $new_password));
		if ($this->db->affected_rows()) {
			return 'Your password has been changed';
		} else {
			return 'Your password could NOT be changed';
		}
	}





	

} // end of class

/* End of file Admin_self_m.php */
/* Location: ./application/models/Common/Admin_self_m.php */


 ?>