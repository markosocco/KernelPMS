<html>
	<head>
		<title>Kernel - Monitor Departments</title>

		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/monitorMembersStyle.css")?>">
	</head>
	<body class="hold-transition skin-red sidebar-mini fixed">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Monitor Departments
						<small>What's happening to the departments?</small>
					</h1>
					<br>

					<ol class="breadcrumb">
	          <?php $dateToday = date('F d, Y | l');?>
	          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
	        </ol>
				</section>
				<!-- Main content -->
				<section class="content container-fluid">
					<!-- START HERE -->
					<div id = "departmentsGrid">
						<?php foreach ($departments as $department):?>
							<?php if($department['DEPARTMENTID'] != 1):?>

							<div class="col-lg-3 col-xs-6">
								<!-- small box -->
								<a class = "dept clickable" data-id = "<?php echo $department['DEPARTMENTID']; ?>">
								<div class="small-box bg-primary">
									<div class="inner">

										<h2 class="title"><?php echo $department['DEPT']; ?></h2>
										<p>
											<?php echo $department['DEPARTMENTNAME']; ?><br>
										</p>
									</div>
									<div class="icon" style="margin-top:25px;">
										<i class="ion ion-filing"></i>
									</div>
								</div>
								</a>
							</div>
							<!-- ./col -->
						<?php endif;?>
						<?php endforeach;?>

						<form id="deptForm" action = 'monitorTeam'  method="POST">
						</form>
					</div>
				</section>
				<!-- /.content -->
			</div>
			<?php require("footer.php"); ?>
		</div>
		<!-- ./wrapper -->
		<script>
			$("#monitor").addClass("active");
			$("#monitorDepartments").addClass("active");

			$(document).on("click", ".dept", function() {
				var $id = $(this).attr('data-id');
				$("#deptForm").attr("name", "formSubmit");
				$("#deptForm").append("<input type='hidden' name='dept_ID' value= " + $id + ">");
				$("#deptForm").submit();
			});
		</script>
	</body>
</html>
