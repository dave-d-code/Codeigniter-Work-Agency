<div class="modal show" role="dialog">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h1 class="modal-title text-center">ACME AGENCY Site</h1>
<p class="text-center">Please log in using your credentials</p>
</div>

<div class="modal-body">
<div class="row">

<div class="col-md-6 col-md-offset-3">
<?php echo validation_errors('<p class="alert alert-info">', '</p>'); ?>
<?php if ($this->session->flashdata('msg')) {echo '<p class="alert alert-info">' . $this->session->flashdata('msg') . '</p>';} ?>
<?php echo form_open('Secure/login'); ?>
<div class="form-group">
<?php echo form_label('Username', 'username'); ?>
<?php echo form_input('username', '', array('class'=>'form-control', 'id'=>'username', 'required'=>'required'));  ?>
</div>
<div class="form-group">
<?php echo form_label('Password', 'password'); ?>
<?php echo form_password('password', '', array('class'=>'form-control', 'id'=>'username', 'required'=>'required')); ?>
</div>
<button type="submit" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-user"></span>  Log In Here</button>
<?php echo form_close(); ?>
</div>

</div>
</div>


<div class="modal-footer modal-blue">
&copy; <?php echo $page_bottom_title; ?>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->