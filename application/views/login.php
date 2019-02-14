<html>
	<head>
		<title>Kernel - Log In</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.7 -->
		<link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
		<!-- <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/bootstrap/dist/css/bootstrap.min.css"> -->
		<link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>animate.css/animate.min.css"/>
		<link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href = "<?php echo base_url("/assets/css/loginStyle.css")?>">


		<!-- Google Font -->
	  <link rel="stylesheet"
	        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	</head>
	<body>

		<div id = mainContainer>

			<img class="animated zoomIn" id = "logo"  src = "<?php echo base_url("/assets/media/tei.png")?>">
			<h3 id="formHeader">KERNEL:<br>PROJECT MANAGEMENT</h3>

			<div id = "login" class = "loginElements">
				<form name = "loginForm" action = "validateLogin" method = "POST">
					<input type = "text" placeholder = "USERNAME" name = "email" value = "<?php if (isset($_SESSION['stickyemail'])) echo $_SESSION['stickyemail']; ?>" required>
					<input type = "password" placeholder = "PASSWORD" name = "password" required>
					<input type = "submit" class="btn btn-success" name = "submitLogin" value = "LOG IN">
				</form>
			</div>

			<p>Unable to Log In? <span id="requestLogin" data-toggle='modal' data-target='#modal-cantLogin'><u>Request from Admin</u></span></p>
		</div>

		<footer>
			<p>&copy; 2018 <a href="http://www.ilovetaters.com">Taters Enterprises Inc</a>. All rights reserved.</p>
			<!-- <p>© 2018 Team Lowkey, Inc. All Rights Reserved</p> -->
		</footer>

		<!-- CANT LOGIN MODAL -->
		<div class="modal fade" id="modal-cantLogin" tabindex="-1">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header" style="text-align: left">
		        <h2 class="modal-title">Can't Log In?</h2>
		      </div>
		      <div class="modal-body">
						<h4>Provide us your email and we will send you your credentials.</h4>
						<h4>Please be sure to provide your corporate email (@tatersgroup.com).</h4>

						<?php echo form_open('/controller/send_mail'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
								<input type="email" name='emailadd' class="form-control" placeholder="Email">
							</div>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
			        <button type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
			      </div>
					<?php echo form_close(); ?>
				</div>
		  </div>
		</div>
		<!-- CANT LOGIN MODAL -->

		<!-- jQuery 3 -->
		<script src="<?php echo base_url()."assets/"; ?>bower_components/jquery/dist/jquery.min.js"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="<?php echo base_url()."assets/"; ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<!-- Bootstrap Notify -->
		<script src="<?php echo base_url()."assets/"; ?>bootstrap-notify-3.1.3/dist/bootstrap-notify.min.js"></script>

		<script>

		// ALERTS
		<?php if (isset($_SESSION['alertMessage'])): ?>
				$(document).ready(function()
				{
					<?php if (isset($_SESSION['danger'])): ?>
						$(document).ready(function()
						{
							dangerAlert();
						});

					<?php elseif (isset($_SESSION['success'])): ?>

						$(document).ready(function()
						{
							successAlert();
						});

					<?php elseif (isset($_SESSION['info'])): ?>

						$(document).ready(function()
						{
							infoAlert();
						});

					<?php elseif (isset($_SESSION['warning'])): ?>

						$(document).ready(function()
						{
							warningAlert();
						});

					<?php endif; ?>
				});
		<?php endif; ?>

	    function successAlert ()
	    {
	      $.notify({
	        // options
	        icon: 'fa fa-check',
	        message: ' <?php if (isset($_SESSION['alertMessage'])) echo $_SESSION['alertMessage']; ?>'
	        },{
	        // settings
	        type: 'success',
	        offset: 60,
	        delay: 5000,
	        placement: {
	          from: "top",
	          align: "center"
	        },
	        animate: {
	          enter: 'animated fadeInDownBig',
	          exit: 'animated fadeOutUpBig'
	        },
	        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
	          '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
	          '<span data-notify="icon"></span>' +
	          '<span data-notify="title">{1}</span>' +
	          '<span data-notify="message">{2}</span>' +
	        '</div>'
	        });
	    };

	    function dangerAlert ()
	    {
	      $.notify({
	        // options
	        icon: 'fa fa-ban',
	        message: ' <?php if (isset($_SESSION['alertMessage'])) echo $_SESSION['alertMessage']; ?>'
	        },{
	        // settings
	        type: 'danger',
	        offset: 20,
	        delay: 5000,
	        placement: {
	          from: "top",
	          align: "center"
	        },
	        animate: {
	          enter: 'animated fadeInDownBig',
	          exit: 'animated fadeOutUpBig'
	        },
	        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
	          '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
	          '<span data-notify="icon"></span>' +
	          '<span data-notify="title">{1}</span>' +
	          '<span data-notify="message">{2}</span>' +
	        '</div>'
	        });
	    };

	    function warningAlert ()
	    {
	      $.notify({
	        // options
	        icon: 'fa fa-warning',
	        message: ' <?php if (isset($_SESSION['alertMessage'])) echo $_SESSION['alertMessage']; ?>'
	        },{
	        // settings
	        type: 'warning',
	        offset: 60,
	        delay: 5000,
	        placement: {
	          from: "top",
	          align: "center"
	        },
	        animate: {
	          enter: 'animated fadeInDownBig',
	          exit: 'animated fadeOutUpBig'
	        },
	        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
	          '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
	          '<span data-notify="icon"></span>' +
	          '<span data-notify="title">{1}</span>' +
	          '<span data-notify="message">{2}</span>' +
	        '</div>'
	        });
	    };

	    function infoAlert ()
	    {
	      $.notify({
	        // options
	        icon: 'fa fa-info',
	        message: ' <?php if (isset($_SESSION['alertMessage'])) echo $_SESSION['alertMessage']; ?>'
	        },{
	        // settings
	        type: 'info',
	        offset: 60,
	        delay: 5000,
	        placement: {
	          from: "top",
	          align: "center"
	        },
	        animate: {
	          enter: 'animated fadeInDownBig',
	          exit: 'animated fadeOutUpBig'
	        },
	        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
	          '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
	          '<span data-notify="icon"></span>' +
	          '<span data-notify="title">{1}</span>' +
	          '<span data-notify="message">{2}</span>' +
	        '</div>'
	        });
	      };
	  </script>

	</body>
</html>
