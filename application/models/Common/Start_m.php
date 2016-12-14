<?php // delete end tag

defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Common model for all
* to handle methods such as logging in etc
* that everyone will need. will work with the secure controller
*/
class Start_m extends CI_Model
{
	public $login_form_rules = array(
		array(
			'field' => 'username',
			'label' => 'Username',
			'rules' => 'trim|required',
			),
			array(
			'field' => 'password',
			'label' => 'Password',
			'rules' => 'trim|required',
			),
		); 

	// just to seed the frontpage with data.
	public function frontpage()
	{
		$this->load->model('Common/Data_seed_m');
		$data = $this->Data_seed_m->common_login();
		return $data;
	}

	// simple data model for the suspended page
	public function suspended()
	{
		$this->load->model('Common/Data_seed_m');
		$data = $this->Data_seed_m->suspended();
		return $data;
	}

	// to log everyone in
	// ***** PHP UNIT TEST passed ********
	// ****** This will require future improvement ******
	public function login()
	{	

		$this->db->select('id, username, password, access');
		$this->db->where('username', $this->input->post('username', true));
		$query = $this->db->get('all_users_info', 1);
		if ($result = $query->first_row()) {
			$pw_check = $this->password_check($this->input->post('password', true), $result->password);
			if ($pw_check) {

				// check to see if user suspended or not
				$result->suspended = $this->is_user_suspended($result->id);
				if (!$result->suspended) {
					$array = array(
					'set_id' => $result->id,
					'access_pass' => $result->access,
				);
					$this->session->set_userdata($array);
				}
				return $result;
			} else {
				$this->session->set_flashdata('msg', 'Username or Password is incorrect');
				return false;
			}	
		} else {
			$this->session->set_flashdata('msg', 'Username or Password is incorrect');
			return false;
		}

	}

	// to take log in data and check against db.
	// do logout by unsetting session data and redirecting
	// this will be linked to from all branches
	public function logout()
	{
		$unset_array = array('set_id', 'access_pass', 'username');
		$this->session->unset_userdata($unset_array);
	}

	// UNIT TEST PASSED
	protected function password_check($user_pw, $db_pw) 
	{
		$hash = crypt($user_pw, $db_pw);
		if ($hash === $db_pw) {
			return true;
		} else {
			return false;
		}
	}

	// check if the user has been suspended
	protected function is_user_suspended($id)
	{
		$this->db->select('suspended');
		$this->db->where('user_id', $id);
		$query1 = $this->db->get('users');
		return $query1->row()->suspended;
	}
	

} // end of class


/* End of file Start_m.php */
/* Location: ./application/models/Common/Start_m.php */


 ?>