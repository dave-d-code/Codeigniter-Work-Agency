<div class="container">
<div class="row">
<div class="col-md-8">
<h3>Change your Password</h3>
<p class="lead">The password must be longer than 8 characters.</p>
</div>
</div>
<div class="row">
<div class="col-md-5">
<?php echo form_open('Admin/password-submit'); ?>
<div class="form-group">
<?php echo form_label('Enter new password', 'password_1'); ?>
<?php echo form_input('password_1', '', array('class'=>'form-control', 'id'=>'password_1')); ?>
</div>
<div class="form-group">
<?php echo form_label('Confirm new password', 'password_2'); ?>
<?php echo form_input('password_2', '', array('class'=>'form-control', 'id'=>'password_2')); ?>
</div>
<button type="submit" class="btn btn-lg btn-info"><span class="glyphicon glyphicon-wrench"></span> Change Password</button>
<?php echo form_close(); ?>
</div>
<div class="col-md-5">
<?php if(validation_errors()): ?>
	<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
<?php endif; ?>
</div>
</div>
</div>