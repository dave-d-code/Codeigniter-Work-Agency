<div class="container">
<div class="row">
	<div class="col-md-3">
		<div class="panel panel-primary">
		<div class="panel-heading">Date of Shift</div>
		<div class="panel-body">
		 <?php echo $job_details->shift_date; ?>		
		</div>
		</div>
		<div class="panel panel-primary">
		<div class="panel-heading">Start Time of Shift</div>
		<div class="panel-body">
		 <?php echo $job_details->shift_time; ?>		
		</div>
		</div>
		<div class="panel panel-primary">
		<div class="panel-heading">Length of Shift</div>
		<div class="panel-body">
		 <?php echo $job_details->shift_length; ?>		
		</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-primary">
		<div class="panel-heading">Requested Skill</div>
		<div class="panel-body">
		 <?php echo $job_details->req_skill_1; ?>		
		</div>
		</div>
		<div class="panel panel-primary">
		<div class="panel-heading">Person Assigned</div>
		<div class="panel-body">
		 <?php echo $job_details->assigned_id; ?>		
		</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="panel panel-primary">
		<div class="panel-heading">Comments Provided</div>
		<div class="panel-body">
		<?php echo $job_details->comments; ?>
		</div>
		</div>
	</div>	
</div>
<div class="row">
	<div class="col-md-4">
		<?php echo $page_button_1; ?>
	</div>
</div>
</div>