// Jquery confirmation modals to confirm user decisions


$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});

function confirm_deploy() {
  return confirm('You will assign yourself to this job??');
}

function confirm_reject() {
	return confirm('You will reject and delete this job??');
}

function confirm_suspend () {
	return confirm("You will suspend this User's Account??");
}
