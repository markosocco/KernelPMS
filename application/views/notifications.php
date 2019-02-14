<html>
	<head>
		<title>Kernel - Notifications</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/notificationsStyle.css")?>"> -->
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Notifications
					<small>What do I have to know?</small>
				</h1>

				<ol class="breadcrumb">
          <?php $dateToday = date('F d, Y | l');?>
          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
        </ol>
			</section>

      <!-- Main content -->
			<section class="content container-fluid">
        <!-- START HERE -->

				<?php if($notifications == null):?>
					<h3 class="box-title" style="text-align:center">There are no notifications</h3>
				<?php else:?>

					<div class="box box-danger">
						<div class="box-header">
							<!-- <h3 class="box-title">Generate Reports</h3> -->
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<table id="logsList" class="table table-bordered table-hover">
								<tbody>
									<form action="" method="POST" id="redirectForm"></form>
									<?php
										foreach ($notification as $n) {
											echo "<tr class='notification'
												data-projectID='" . $n['projects_PROJECTID'] . "'
												data-taskID='" . $n['tasks_TASKID'] . "'
												data-notifID='" . $n['NOTIFICATIONID'] . "'
												data-type='" . $n['TYPE'] . "'>";
												echo "<td>" . $n['TIMESTAMP'] . "</td>";
												echo "<td>" . $n['DETAILS'] . "</td>";
											echo "</tr>";
										}
									?>
								</tbody>
							</table>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				<?php endif;?>

			</section>
		</div>
		  <?php require("footer.php"); ?>
		</div> <!--.wrapper closing div-->
		<script>

			$("body").on('click', '.notification', function() {

				var $projectID = $(this).attr('data-projectID');
				var $taskID = $(this).attr('data-taskID');
				var $notifType = $(this).attr('data-type');
				var $notifID = $(this).attr('data-notifID');

				$("#redirectForm").attr("name", "formSubmit");
				$("#redirectForm").attr("action", "notifRedirect");
				$("#redirectForm").append("<input type='hidden' name='projectID' value='" + $projectID + "'>");
				$("#redirectForm").append("<input type='hidden' name='taskID' value='" + $taskID + "'>");
				$("#redirectForm").append("<input type='hidden' name='type' value='" + $notifType + "'>");
				$("#redirectForm").append("<input type='hidden' name='notifID' value='" + $notifID + "'>");
				$("#redirectForm").submit();

			});

		</script>
	</body>
</html>
