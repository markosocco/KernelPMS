<html>
	<head>
		<title>Kernel - Project Logs</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/projectLogsStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini sidebar-collapse fixed">

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div style="margin-bottom:10px">
					<button id="backBtn" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to Project"><i class="fa fa-arrow-left"></i></button>
					<form id="backForm" action = 'projectGantt' method="POST" data-id="<?php echo $projectID; ?>">
					</form>
				</div>
				<?php
					$startdate = date_create($projectProfile['PROJECTSTARTDATE']);
					$enddate = date_create($projectProfile['PROJECTENDDATE']);
				?>

				<h1>
					Project Logs
					<small><?php echo $projectProfile['PROJECTTITLE']; ?> (<?php echo date_format($startdate, "F d, Y") . " - " . date_format($enddate, "F d, Y");?>)</small>
				</h1>

				<ol class="breadcrumb">
          <?php $dateToday = date('F d, Y | l');?>
          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
        </ol>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<?php if($projectLog == null):?>
					<h3 class="box-title" style="text-align:center">There are no project logs</h3>
				<?php else:?>

				<div class="box box-danger">
					<div class="box-header">
						<!-- <h3 class="box-title">Generate Reports</h3> -->
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="logsList" class="table table-bordered table-hover">
							<tbody>
								<?php
									foreach ($projectLog as $row) {
										echo "<tr>";
											echo "<td>" . $row['TIMESTAMP'] . "</td>";
											echo "<td>" . $row['LOGDETAILS'] . "</td>";
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
		$("#myProjects").addClass("active");

		$(document).on("click", "#backBtn", function() {
			var $project = $("#backForm").attr('data-id');
			$("#backForm").attr("name", "formSubmit");
			$("#backForm").append("<input type='hidden' name='project_ID' value= " + $project + ">");
			$("#backForm").submit();
			});
		</script>

	</body>
</html>
