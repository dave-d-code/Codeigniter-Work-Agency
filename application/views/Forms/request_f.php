<div class="row">
<div class="col-md-3">
	<div class="form-group">
	<?php echo form_label('Shift Date', 'shift_date'); ?>
	<?php echo form_input('shift_date', set_value('shift_date'), array('class'=>'form-control', 'id'=>'shift_date')); ?>	
	</div>
	<div class="form-group">
	<?php echo form_label('Start Time for Shift', 'shift_time'); ?>
	<?php echo form_input('shift_time', set_value('shift_time'), array('class'=>'form-control', 'id'=>'shift_time')); ?>
	</div>
</div>
<div class="col-md-3">
	<div class="form-group">
		<?php echo form_label('Length of Shift', 'shift_length'); ?>
		<?php $length_array = array(
			'8'=>'8 Hours',
			'9'=>'9 Hours',
			'10'=>'10 Hours',
			'11'=>'11 Hours',
			'12'=>'12 Hours',
			'13'=>'13 Hours',
			'14'=>'14 Hours',
			'15'=>'15 Hours',
			'16'=>'16 Hours',
			);
		echo form_dropdown('shift_length', $length_array, '12', array('class'=>'form-control', 'id'=>'shift_length')); ?>
	</div>
	<div class="form-group">
		<?php echo form_label('Skill Requested', 'req_skill_1'); ?>
		<?php $skill_array = array(
				'skill_1' => 'Nurse',
				'skill_2' => 'Doctor - Core Trainee',
				'skill_3' => 'Doctor - House Officer',
				'skill_4' => 'Doctor - Senior House Officer'
		);
		echo form_dropdown('req_skill_1', $skill_array, 'skill_1', array('class'=>'form-control', 'id'=>'req_skill_1'));?>
	</div>
</div>
<div class="col-md-6">
<?php if(validation_errors()): ?>
	<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
<?php endif; ?>
</div>
</div>
<div class="row">
	<div class="col-md-8">
	<div class="form-group">
		<?php echo form_label('Comments', 'comments'); ?>
		<?php echo form_textarea('comments', set_value('comments'), array(
										'class'=>'form-control',
										'id'=>'comments',
										'placeholder'=>'Enter any comments here')); ?>

	</div>
	<button type="submit" class="btn btn-warning btn-lg pull-right"><span class="glyphicon glyphicon-send"></span> Send This Request</button>
	</div>
	<div class="col-md-4">
		  <?php echo form_close(); ?>
	</div>
</div>