<html>
	<head>
		<title>Kernel - Monitor Team</title>

		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/myTeamStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini fixed">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<?php if($_SESSION['departments_DEPARTMENTID'] == 1):?>
						<a href="<?php echo base_url("index.php/controller/monitorDepartments"); ?>" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to Departments"><i class="fa fa-arrow-left"></i></a>
						<br><br>
						<h1>
							Monitor Department
							<small>What's happening to this department?</small>
						</h1>
					<?php else:?>
					<h1>
						Monitor Team
						<small>What's happening to my team?</small>
					</h1>
				<?php endif;?>


					<ol class="breadcrumb">
						<?php $dateToday = date('F d, Y | l');?>
						<p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
					</ol>
				</section>
				<!-- Main content -->
				<section class="content container-fluid">
					<!-- START HERE -->
						<div class="row">

							<form id = 'employeeDrillDown' action = 'monitorMembers'  method="POST">
							</form>

							<?php foreach ($staff as $key => $row): ?>
								<?php if ($row['USERID'] != $_SESSION['USERID']): ?>
								<div class="col-md-4 employee" data-id="<?php echo $row['USERID']; ?>">
									<!-- Widget: user widget style 1 -->
									<div class="box box-widget widget-user clickable">
										<!-- Add the bg color to the header using any of the bg-* classes -->
										<div class="widget-user-header bg-aqua-active">
											<h3 class="widget-user-username"><?php echo $row['FIRSTNAME'] . " " . $row['LASTNAME']; ?></h3>
											<h5 class="widget-user-desc"><?php echo $row['POSITION']; ?></h5>
										</div>
										<div class="widget-user-image">
											<img src="<?php echo $row['IDPIC']; ?>" class="img-circle" alt="User Image">
											<!-- <img src="<?php echo base_url()."assets/"; ?>media/idpic.png" class="img-circle" alt="User Image"> -->
										</div>
										<div class="box-footer">
											<div class="row">
												<div class="col-sm-4 border-right">
													<div class="description-block">
														<h5 class="description-header">
															<?php foreach ($completeness as $p): ?>
																<?php if ($p['USERID'] == $row['USERID']): ?>
																	<?php if ($p['completeness'] == NULL): ?>
																		0%
																	<?php elseif ($p['completeness'] == 100.00): ?>
																		100%
																	<?php elseif ($p['completeness'] == 0.00): ?>
																		0%
																	<?php else: ?>
																		<?php echo $p['completeness'] . "%"; ?>
																	<?php endif; ?>
																<?php endif; ?>
															<?php endforeach; ?>
														</h5>
														<span class="description-text">COMPLETENESS</span>
													</div>
													<!-- /.description-block -->
												</div>
												<!-- /.col -->
												<div class="col-sm-4 border-right">
													<div class="description-block">

														<?php
					                    $tcount = 0;
					                  ?>
					                  <?php foreach($taskCount as $t): ?>
					                    <?php if($row['USERID'] == $t['users_USERID']):?>
					                      <?php $tcount = $t['TASKCOUNT']; ?>
					                    <?php endif;?>
					                  <?php endforeach;?>

														<h5 class="description-header">
															<?php echo $tcount; ?>
														</h5>
														<span class="description-text">TASKS</span>
													</div>
													<!-- /.description-block -->
												</div>
												<!-- /.col -->
												<div class="col-sm-4">
													<div class="description-block">
														<h5 class="description-header">
															<?php foreach ($timeliness as $p): ?>
																<?php if ($p['USERID'] == $row['USERID']): ?>
																	<?php if ($p['timeliness'] == NULL): ?>
																		0%
																	<?php elseif ($p['timeliness'] == 100.00): ?>
																		100%
																	<?php elseif ($p['timeliness'] == 0.00): ?>
																		0%
																	<?php else: ?>
																		<?php echo $p['timeliness'] . "%"; ?>
																	<?php endif; ?>
																<?php endif; ?>
															<?php endforeach; ?>
														</h5>
														<span class="description-text">TIMELINESS</span>
													</div>
													<!-- /.description-block -->
												</div>
												<!-- /.col -->
											</div>
											<!-- /.row -->
										</div>
									</div>
									<!-- /.widget-user -->
								</div>
								<!-- /.col -->
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</section>
				<!-- /.content -->
			</div>
			<?php require("footer.php"); ?>
		</div>
		<!-- ./wrapper -->
		<script>
			$("#monitor").addClass("active");
			$("#monitorTeam").addClass("active");
			$("#monitorDepartments").addClass("active");

			$(document).on("click", ".employee", function() {
	      var $id = $(this).attr('data-id');
	      $("#employeeDrillDown").attr("name", "formSubmit");
	      $("#employeeDrillDown").append("<input type='hidden' name='employee_ID' value= " + $id + ">");
	      $("#employeeDrillDown").submit();

				console.log($id);
	    });

		</script>
	</body>
</html>
