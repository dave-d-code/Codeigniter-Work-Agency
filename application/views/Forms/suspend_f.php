<div class="container">
<div class="row">
<div class="col-md-8 text-center">
<h2>Suspend A User Account</h2>
<p class="lead">Enter in the User's surname to find the appropiate user from the list.</p>
<div class="alert alert-danger">
<h3>Suspending a User will prevent them from using this site, and they will no longer be allocated any jobs!</h3>
</div>
</div>
</div>
<div class="row">
	<?php echo form_open('Dashboard/suspend-user-account'); ?>
<div class="col-md-8">
	<div class="input-group">
	<span class="input-group-btn">
		<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search"></span> Search</button>		
	</span>
	<?php echo form_input('search_form', set_value('search_form'), array('class'=>'form-control', 'placeholder'=>'Enter surname...', 'required'=>'required')); ?>
	<?php echo form_close(); ?>
	</div>
</div>
<div class="col-md-4">
	<?php if (validation_errors()): ?>
	<div class="alert alert-warning"><?php echo validation_errors(); ?></div>
<?php endif; ?>
</div>
</div>
<style>
	#move-down{
		padding-top: 20px;
	}
</style>

<div class="row" id="move-down">
	<div class="col-md-8">
		<?php echo $table; ?>
	</div>
</div>
</div>