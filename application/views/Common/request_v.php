<div class="container">
<div class="row">
	<div class="col-md-10 text-center">
		<h2>
			Online request form for staff
		</h2>
		<p class="lead">Complete and submit this form to request staff. This site will auto select and contact the right candidates for you.</p>
	</div>
</div>
<?php echo form_open($page_form_link); ?>
<?php $this->load->view('Forms/request_f'); ?>
</div>