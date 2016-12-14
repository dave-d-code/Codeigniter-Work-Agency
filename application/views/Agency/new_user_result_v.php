<div class="container">
<div class="row">
<div class="col-md-8 text-center">
	<h3 class="text-center">New User Account created for: </h3>
	<h4 class="text-center"><?php echo $full_name; ?></h4>
	<p class="lead">Send the following details below to the user. You can copy and paste the text.</p>
</div>
</div>
<div class="row">
<style>
	#jumbo-user-result{
		background-color: #52527a;
	}
</style>
<div class="col-md-8 text-center">
	<div class="jumbotron" id="jumbo-user-result">
		<p class="lead">
			Username: <?php echo $username; ?><br>
			Password: <?php echo $password; ?><br>
		</p>
	</div>
	<a href="<?php echo site_url('Agency'); ?>" class="btn btn-lg btn-warning"><span class="glyphicon glyphicon-backward"></span> Back to Home Page</a>
</div>
</div>
</div>