<style>
	body{
		background-image: url('../assets/images/result-modal-2.gif');
 	 	background-repeat: no-repeat;
 	 	background-size: 100%;
	}

	.modal-body{
		background-color: #ffe6b3;
	}
</style>

<div class="container">
<div class="row">
<div class="modal" tabindex="-1" role="dialog" id="result_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?= $modal_result_title; ?></h4>
      </div>
      <div class="modal-body">
        <p class="lead text-center"><?= $modal_result_text; ?></p>
      </div>
      <div class="modal-footer">     
        <a href="<?= $modal_result_link; ?>" class="btn btn-lg btn-warning"><span class="glyphicon glyphicon-home"></span> Front Page</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</div>
</div>