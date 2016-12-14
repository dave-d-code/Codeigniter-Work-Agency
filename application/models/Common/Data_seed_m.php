<?php // Delete end tag

/**
* To provide initial data object to pass around the models, 
* and then to controllers, and then the view.
* This is akin to $scope in AngularJS
*/
class Data_seed_m extends CI_Model
{

	public function standard() // this to go????
	{
		$data = $this->template();
		$data->page_bottom_title = date('Y') . ' Locum2';
		return $data;
	}



	// ************* Client section *************************************

	public function client_standard()
	{
		$data = $this->template();
		$data->page_my_css = '<link rel="stylesheet" href="'. site_url('assets/css/common.css') .'">';
		$data->nav_no_of_pros = '';
		$data->nav_no_of_cons = '';
		$data->nav_no_of_problems = '';
		$data->log_username = '';
		return $data;
	}

	// ********************* User Section ********************************

	public function user_standard()
	{
		$data = $this->template();
		$data->page_my_css = '<link rel="stylesheet" href="'. site_url('assets/css/common.css') .'">';
		$data->nav_no_of_pros = '';
		$data->nav_no_of_cons = '';
		$data->nav_no_of_problems = '';
		$data->log_username = '';
		return $data;
	}

	// ******************** Agency Section **************************

	public function agency_standard()
	{
		$data = $this->template();
		$data->page_my_css = '<link rel="stylesheet" href="'. site_url('assets/css/common.css') .'">';
		$data->nav_no_of_pros = '';
		$data->nav_no_of_cons = '';
		$data->nav_no_of_problems = '';
		$data->log_username = '';
		return $data;
	}

	public function agency_individual_job()
	{
		$data = $this->agency_standard();
		$data->agency_on = true;
		$data->agency_panel_text_2 = '';
		$data->page_button_1 = '';
		$data->page_button_2 = '';
		$data->page_button_3 = '';
		return $data;
	}

	
	
	public function agency_ajax()
	{
		$data = $this->agency_standard();
		$data->page_js_link_1 = '<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>';
		$data->page_js_link_3 ='<script src="' . site_url('assets/js/agency_ajax.js') . '"></script>';
		return $data;
	}

	

	// **************** Common Section **************************************

	// login page
	public function common_login()
	{
		$data = $this->template();
		$data->page_title = 'ACME AGENCY - Medical Professionals Hire Site';
		$data->page_my_css = '<link rel="stylesheet" href="'. site_url('assets/css/login.css') .'">';
		$data->page_meta_robots = '<META NAME="ROBOTS" CONTENT="INDEX, NOFOLLOW">';
		$data->page_meta_description = '<meta name="description" content="ACME Agency Site for the short term hire of Medical Professionals.">';
		$data->page_bottom_title = date('Y') . ' Locum2';
		return $data;
	}

	// for the job request form
	public function request_form()
	{
		$data = $this->client_standard();
		$data->page_extra_css_1 = '<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/hot-sneaks/jquery-ui.css">';
		$data->page_extra_css_2 = '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.1/jquery-ui-timepicker-addon.min.css">';
		$data->page_js_link_1 ='<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>';
		$data->page_js_link_2 ='<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.1/jquery-ui-timepicker-addon.min.js"></script>';
		$data->page_js_link_3 ='';
		$data->page_extra_js = '<script>$(function() {
			$("#shift_date").datepicker({dateFormat: "dd-mm-yy"});
			$("#shift_time").timepicker();
		});</script>';
		return $data;
	}

	// for all users whom are suspended. no access to main site.
	public function suspended()
	{
		return $data = $this->template();
	}

	// result for the request form....
	public function request_result()
	{
		$data = $this->client_standard();
		$data->modal_result_text = '';
		$data->modal_result_title = 'Request Form Result';
		$data->modal_result_link = '';
		$data->page_extra_js = '<script>$(document).ready(function () {
    		$("#result_modal").modal("show").on("shown.bs.modal", function () {
        	$(".modal").css("display", "block");})});</script>';
		return $data;
	}



	
	// ************************* Start up for all ***************************
	protected function template()
	{
		$data = new stdClass();
		$data->page_title = 'Locum Version 2 Site';
		$data->page_extra_css_1 = '';
		$data->page_extra_css_2 = '';
		$data->page_my_css = '';
		$data->page_js_link_1 ='';
		$data->page_js_link_2 ='';
		$data->page_js_link_3 ='';
		$data->page_extra_js = '';
		$data->page_meta_robots = '<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">';
		$data->page_meta_description = '';
		return $data;
	}










} // end of class






/* End of file Data_seed_m.php */
/* Location: ./application/models/Common/Data_seed_m.php */


 ?>