<div class="container">
<div class="row">
<div class="col-md-8">
	<h2>Create New User Account</h2>
	<p class="lead">Enter in the following details. You will be given a username and password which you can copy and paste.
	 Email this to the new user.</p>
</div>
</div>
<div class="row">
<?php echo form_open('Dashboard/process-new-user'); ?>
<div class="col-md-6">
		<div class="row">
		<div class="col-md-3">
		<div class="form-group">
		<?php echo form_label('Title', 'title'); ?>
		<?php $title_array = array(
			'Dr' => 'Dr',
			'Mr' => 'Mr',
			'Mrs' => 'Mrs',
			'Ms' => 'Ms',);
		echo form_dropdown('title', $title_array, 'Dr', array('class'=>'form-control', 'id'=>'title'));
		 ?>	
		</div>
		</div>
		</div>
	<div class="form-group">
		<?php echo form_label('First Name', 'first_name'); ?>
		<?php echo form_input('first_name', set_value('first_name'), array('class'=>'form-control','id'=>'first_name')); ?>
	</div>
	<div class="form-group">
		<?php echo form_label('Last Name', 'last_name'); ?>
		<?php echo form_input('last_name', set_value('last_name'), array('class'=>'form-control','id'=>'last_name')); ?>
	</div>
	<div class="form-group">
		<?php echo form_label('Email', 'email'); ?>
		<?php echo form_input('email', set_value('email'), array('class'=>'form-control','id'=>'email')); ?>
	</div>
	<div class="form-group">
		<?php echo form_label('Skill Asset', 'skill_level'); ?>
		<?php $skill_array = array(
			'skill_1' => 'Nurse',
			'skill_2' => 'Doctor - Core Trainee',
			'skill_3' => 'Doctor - House Officer',
			'skill_4' => 'Doctor - Senior House Officer',);
		echo form_dropdown('skill_level', $skill_array, 'skill_1', array('class'=>'form-control','id'=>'skill_level'));?>
	</div>
</div>
<div class="col-md-6">
	<?php if (validation_errors()): ?>
	<div class="alert alert-danger"><?php echo validation_errors(); ?></div>
	<?php endif; ?>
</div>
	
</div>
<div class="row">
	<div class="col-md-4">
		<button type="submit" class="btn btn-lg btn-info"><span class="glyphicon glyphicon-save"></span> Create New User!</button>
	</div>
	<?php echo form_close(); ?>
</div>
</div>