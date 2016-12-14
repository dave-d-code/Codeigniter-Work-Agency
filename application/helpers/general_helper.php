<?php // remove closing php tags

	/* Just general a helper for making database data look more
	* presentable 
	*/

	// date & time - convert to SQL standard

	function sql_date($input_date) {
		return date("Y-m-d", strtotime($input_date));
	}

	function sql_time($input_time) {
		return $input_time . ':00';
	}

	function normal_date($input_date) {
		return date("l d F Y", strtotime($input_date));
	}

	// to show requested_skills column headings in a readable format

	function skill_translate($skill) {
		switch ($skill) {
			case 'skill_1': return 'Nurse'; break;
			case 'skill_2': return 'Doctor - Core Trainee'; break;
			case 'skill_3': return 'Doctor - House Officer'; break;
			case 'skill_4': return 'Doctor - Senior House Officer'; break;			
		}
	}

	function add_hours($str) {
		return $str . ' Hours';
	}

	function time_cut($str) {
		return substr($str, 0, 5) . ' hrs';
	}




/* End of file general_helper.php */
/* Location: ./application/helpers/general_helper.php */


 ?>