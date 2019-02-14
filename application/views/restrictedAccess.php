<html>
	<head>
		<title>Kernel - Restricted Access</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/restrictedAccessStyle.css")?>">
		<link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>animate.css/animate.min.css"/>
		<!-- Google Font -->
		<link rel="stylesheet"
					href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	</head>
	<body>
		<div id = mainContainer>
			<img class="animated zoomIn" id = "logo"  src = "<?php echo base_url("/assets/media/tei.png")?>">
			<h3 id="formHeader">KERNEL:<br>PROJECT MANAGEMENT</h3>
			<br><br>
			<div class = "">
				<h1>Hey, you seemed lost.</h1>
				<?php if(!isset($_SESSION['USERID'])):?>
					<h2>Please log in so I can recognize you. Have a nice day!</h2>
					<br>
					<a class="btn btn-primary redirectBtn" href = "<?php echo base_url('index.php/controller/login');?>" style="text-decoration-color:white">Log In</a>
				<?php else:?>
					<h2>Please return to the dashboard. Have a nice day!</h2>
					<br>
					<a class="btn btn-primary redirectBtn" href = "<?php echo base_url('index.php/controller/dashboard');?>" >Dashboard</a>
				<?php endif;?>
			</div>
		</div>

		<footer>
			<p>&copy; 2018 <a href="http://www.ilovetaters.com">Taters Enterprises Inc</a>. All rights reserved.</p>
			<!-- <p>© 2018 Team Lowkey, Inc. All Rights Reserved</p> -->
		</footer>

		<!-- jQuery 3 -->
		<script src="<?php echo base_url()."assets/"; ?>bower_components/jquery/dist/jquery.min.js"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="<?php echo base_url()."assets/"; ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<!-- Bootstrap Notify -->
		<script src="<?php echo base_url()."assets/"; ?>bootstrap-notify-3.1.3/dist/bootstrap-notify.min.js"></script>

		<script>

	  </script>
	</body>
</html>
