<?php // remove end tag
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* to have shared controller features
* and to do common validation functions
*/
class MY_Controller extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->security_check_1();
		
	}

	protected function security_check_1()
	{
		if (!$this->session->has_userdata('set_id')) {
			redirect('','refresh');
		}
	}



// **** Common Admin functions ********

	

} // end of class




/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */

 ?>