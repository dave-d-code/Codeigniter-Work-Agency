<div class="container">
	<div class="row">
		<div class="col-md-6">
		<div class="panel panel-primary">
		<div class="panel-heading">Hospital Name</div>
		<div class="panel-body">
		 <h3><?php echo $job_details->client->client_name; ?></h3>
		 <h4><?php echo $job_details->client->client_location; ?></h4>		
		</div>
		</div>
		</div>
	
	<?php if (isset($agency_on)): ?>
	<div class="col-md-6">
		<div class="panel panel-primary">
		<div class="panel-heading"><?php echo $agency_panel_title; ?></div>
		<div class="panel-body">
		 <h4><?php echo $agency_panel_text_1; ?></h4>
		 <h4><?php echo $agency_panel_text_2; ?></h4>		
		</div>
		</div>
		</div>
	<?php endif ?>
	</div>
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
	<div class="col-md-6">
		<div class="panel panel-primary">
		<div class="panel-heading">Requested Skill</div>
		<div class="panel-body">
		 <?php echo $job_details->req_skill_1; ?>		
		</div>
		</div>
	
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
	<div class="col-md-4">
		<?php echo $page_button_2; ?>
	</div>
	<div class="col-md-4">
		<?php echo $page_button_3; ?>
	</div>
</div>
</div>