<div class="container">
<div class="row">
<div class="col-md-8">
<h3>Change your Username</h3>
<p class="lead">This will be the username used to login with. Your new username must
be unique. The site will check if this is the case.</p>
</div>
</div>
<div class="row">
<div class="col-md-5">
<?php echo form_open('Admin/username-submit'); ?>
<div class="form-group">
<?php echo form_label('Enter new username', 'username'); ?>
<?php echo form_input('username', set_value('username'), array('class'=>'form-control', 'id'=>'username')); ?>
</div>
<button type="submit" class="btn btn-lg btn-info"><span class="glyphicon glyphicon-wrench"></span> Change Username</button>
<?php echo form_close(); ?>
</div>
<div class="col-md-5">
<?php if(validation_errors()): ?>
	<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
<?php endif; ?>
</div>
</div>
</div>