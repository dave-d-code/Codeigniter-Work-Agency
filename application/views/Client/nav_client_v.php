<nav class="navbar navbar-default" id="custom-bootstrap-menu">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo site_url('Client/') ?>"><span class="glyphicon glyphicon-home"></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="<?php echo site_url('Client/requests'); ?>"><span class="glyphicon glyphicon-pencil"></span> Request Form</a></li>
        <li><a href="<?php echo site_url('Client/current-bookings/'); ?>"><span class="glyphicon glyphicon-inbox"></span> Current Bookings <span class="badge"><?php echo $nav_no_of_pros; ?></span></a></li> 
        <li><a href="<?php echo site_url('Client/confirmed-assignments/'); ?>"><span class="glyphicon glyphicon-book"></span> Confirmations <span class="badge"><?php echo $nav_no_of_cons; ?></span></a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-th-list"></span> Self Admin Menu<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo site_url('Admin/change-my-username'); ?>"><span class="glyphicon glyphicon-tag"></span> Change own username</a></li>
            <li><a href="<?php echo site_url('Admin/change-my-password'); ?>"><span class="glyphicon glyphicon-lock"></span> Change own password</a></li>
          </ul>
        </li>
        <li><a href="<?php echo site_url('Secure/logout'); ?>"><span class="glyphicon glyphicon-log-out"></span>  Logout</a></li>
      </ul>
      <p class="navbar-text navbar-right"><?php echo $log_username; ?></p>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>