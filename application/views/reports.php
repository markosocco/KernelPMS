<html>
	<head>
		<title>Kernel - Reports</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/reportsStyle.css")?>"> -->
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Reports
					<small>What do I show the boss?</small>
				</h1>

				<ol class="breadcrumb">
          <?php $dateToday = date('F d, Y | l');?>
          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
        </ol>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<div class="box box-danger">
					<div class="box-header">
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="reportList" class="table no-margin table-hover">
							<tbody>
								<!-- SHOW ONLY TO THOSE PO'S WITH ONGOING PROJECTS --> <!-- Change to $allOngoingProjects for restricted access -->
								<?php if($_SESSION['usertype_USERTYPEID'] != 5 && $_SESSION['usertype_USERTYPEID'] != 1):?>
								<tr>
									<td>Project Status Report</td>
									<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#changeProj'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
								</tr>
								<tr>
									<td>Project Progress Report</td>
									<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#changeEmp'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
								</tr>
								<tr>
									<td>Project Summary</td>
									<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#projectSummary'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
								</tr>
								<?php endif;?>

								<?php if($_SESSION['usertype_USERTYPEID'] == 2):?> <!-- Change to == for restricted access -->
									<tr>
										<td>Department Performance</td>
										<td align="center"><a href="<?php echo base_url("index.php/controller/reportsDepartmentPerformance"); ?>" target="_blank" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='top' title='Generate Report'><i class="fa fa-print"></i></a></td>
									</tr>
								<?php endif;?>

								<?php if ($_SESSION['usertype_USERTYPEID'] != 5): ?>
								<tr>
									<td>Project Performance</td>
									<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#projPerf'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
									</tr>
								<?php endif; ?>

								<?php if($_SESSION['departments_DEPARTMENTID'] != 1):?>
									<?php if($_SESSION['usertype_USERTYPEID'] == 3 || $_SESSION['usertype_USERTYPEID'] == 4):?>
										<tr>
											<td>Team Performance</td>
											<td align="center"><a href="<?php echo base_url("index.php/controller/reportsTeamPerformance"); ?>" target="_blank" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='top' title='Generate Report'><i class="fa fa-print"></i></a></td>
										</tr>
									<?php endif;?>

									<tr>
										<td>Employee Performance</td>
										<td align="center"><a href="<?php echo base_url("index.php/controller/reportsEmployeePerformance"); ?>" target="_blank" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='top' title='Generate Report'><i class="fa fa-print"></i></a></td>
									</tr>
								<?php endif;?>

							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

				<!-- PROJECT SUMMARY -->
				<div class="modal fade" id="projectSummary" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Project Summary</h2>
							</div>
							<div class="modal-body">
								<form name="projSummReport" id="projSummReport" action="reportsProjectSummary" method="POST" target="">
									<h4>Project: </h4>
									<select name="project" id="projectSummarySelect" class="form-control select2" data-placeholder="Select Departments" required>
										<option disabled selected value = "0">-- Select a Project -- </option>
										<?php
											foreach ($allProjects as $value) {
												echo "<option value=" . $value['PROJECTID'] . ">" . $value['PROJECTTITLE'] . "</option>";
											}
										?>
									</select>
									<input type ="hidden" id="summHasProject" name="summHasProject" value="">
								</form>
								<div class="modal-footer">
									<button id="closeProjectSummary" type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
									<button id="generateProjSumm" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='left' title='Generate Report'><i class="fa fa-print"></i></button>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

				<!-- PROJECT PERFORMANCE -->
				<div class="modal fade" id="projPerf" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Project Performance</h2>
							</div>
							<div class="modal-body">
								<form name="projPerfReport" id="projPerfReport" action="reportsProjectPerformance" method="POST" target="">
									<h4>Project: </h4>
									<select name="project" id="projectPerformanceSelect" class="form-control select2">
										<option disabled selected value = "0">-- Select a Project -- </option>
										<?php
											foreach ($allOngoingProjects as $value) {
												echo "<option value=" . $value['PROJECTID'] . ">" . $value['PROJECTTITLE'] . "</option>";
											}
										?>
									</select>
									<input type ="hidden" id="perfHasProject" name="perfHasProject" value="">
								</form>

								<div class="modal-footer">
									<button id ="closeProjPerfReport" type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
									<button id="generateProjPerfReport" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='left' title='Generate Report'><i class="fa fa-print"></i></button>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

				<!-- PROJECT STATUS REPORT -->
				<div class="modal fade" id="changeProj" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Project Status Report</h2>
							</div>
							<div class="modal-body">
								<form name="projStatusReport" id="projStatusReport" action="reportsProjectStatus" method="POST" target=''>
									<h4>Status Interval: </h4>
									<div class="btn-group" id="btnStatus">
										<button type="button" id = "weeklyBtn" value = '7' class="btn btn-default intervalsStatus">Weekly</button>
										<button type="button" id = "monthlyBtn" value = '31' class="btn btn-default intervalsStatus">Monthly</button>
									</div>
									<input id="intervalValueStatus" type='hidden' name='interval' value= "">
									<input id="statHasInterval" type='hidden' name='hasInterval' value= "">
									<input id="statHasProject" type='hidden' name='hasProject' value= "">
									<br><br>
									<h4>Project: </h4>
									<select id="projectStatus" name="project" class="form-control select2" data-placeholder="Select Departments" required>
										<option disabled selected value = "0">-- Select a Project -- </option>
										<?php
											foreach ($allOngoingProjects as $value) {
												echo "<option value=" . $value['PROJECTID'] . ">" . $value['PROJECTTITLE'] . "</option>";
											}
										?>
									</select>
								</form>

								<div class="modal-footer">
									<button id ="closeStatusReport" type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
									<button id="generateStatusReport" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='left' title='Generate Report'><i class="fa fa-print"></i></button>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

				<!-- PROJECT PROGRESS REPORT -->
				<div class="modal fade" id="changeEmp" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Project Progress Report</h2>
							</div>
							<div class="modal-body">
								<form name="projProgressReport" id="projProgressReport" action="reportsProjectProgress" method="POST" data-interval='' target="">
									<h4>Progress Interval: </h4>
									<div class="btn-group" id="btnProgress">
										<button type="button" id = "weeklyBtn" value = '7' class="btn btn-default intervalsProgress">Weekly</button>
										<button type="button" id = "monthlyBtn" value = '31' class="btn btn-default intervalsProgress">Monthly</button>
									</div>
									<input id="intervalValueProgress" type='hidden' name='interval' value= "">
									<input id="progHasInterval" type='hidden' name='hasInterval' value= "">
									<input id="progHasProject" type='hidden' name='hasProject' value= "">
									<br><br>
									<h4>Project: </h4>
									<select id="projectProgressSelect" name="project" class="form-control select2" data-placeholder="Select Departments">
										<option disabled selected value = "0">-- Select a Project -- </option>
										<?php
											foreach ($allOngoingProjects as $value) {
												echo "<option value=" . $value['PROJECTID'] . ">" . $value['PROJECTTITLE'] . "</option>";
											}
										?>
									</select>
								</form>

								<div class="modal-footer">
									<button id="closeProjectProgress" type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
									<button id="generateProgressReport" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='left' title='Generate Report'><i class="fa fa-print"></i></button>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

			</section>
		</div>

		<?php require("footer.php"); ?>

		</div> <!--.wrapper closing div-->

		<script>
		$("#reports").addClass("active");

		$(document).ready(function() {

			// PROJECT SUMMARY REPORT
			$("#projectSummarySelect").change(function() {
				$("#summHasProject").attr("value", 1);
			});

			$("#generateProjSumm").click(function()
			{
				var summHasProject = $("#summHasProject").val();

				if (summHasProject != "")
				{
					$("#projSummReport").attr("target", "_blank");
				}

				$("#projSummReport").submit();
			});

			$("#closeProjectSummary").click(function()
			{
				$("#projectSummarySelect").val("0");
			});

			// PROJECT STATUS REPORT
			$(".btn-group > .btn").click(function(){
			    $(".btn-group > .btn").removeClass("active");
			    $(this).addClass("active");
					$("#intervalValueStatus").attr("value", $(this).val());
					$("#statHasInterval").attr("value", 1);
			});

			$("#projectStatus").change(function() {
				$("#statHasProject").attr("value", 1);
			});


			$("#generateStatusReport").click(function()
			{
				var statHasInterval = $("#statHasInterval").val();
				var statHProject = $("#statHasProject").val();

				if (statHasInterval != "" && statHProject != "")
				{
					$("#projStatusReport").attr("target", "_blank");
				}

	      $("#projStatusReport").submit();
			});

			$("#closeStatusReport").click(function()
			{
				$("#projectStatus").val("0");
				$(".intervalsStatus").removeClass("active");
			});

			// PROJECT PROGRESS REPORT
			$(".btn-group > .btn").click(function(){
			    $(".btn-group > .btn").removeClass("active");
			    $(this).addClass("active");
					$("#intervalValueProgress").attr("value", $(this).val());
					$("#progHasInterval").attr("value", 1);
			});

			$("#projectProgressSelect").change(function() {
				$("#progHasProject").attr("value", 1);
			});

			$("#generateProgressReport").click(function()
			{
				var progHasInterval = $("#progHasInterval").val();
				var progHasProject = $("#progHasProject").val();

				if (progHasInterval != "" && progHasProject != "")
				{
					$("#projProgressReport").attr("target", "_blank");
				}

				$("#projProgressReport").submit();
			});

			$("#closeProjectProgress").click(function()
			{
				$("#projectProgressSelect").val("0");
				$(".intervalsProgress").removeClass("active");
			});

			// PROJECT PERFORMANCE REPORT
			$("#projectPerformanceSelect").change(function() {
				$("#perfHasProject").attr("value", 1);
			});

			$("#generateProjPerfReport").click(function()
			{
				var perfHasProject = $("#perfHasProject").val();

				if (perfHasProject != "")
				{
					$("#projPerfReport").attr("target", "_blank");
				}

				$("#projPerfReport").submit();
			});

			$("#closeProjPerfReport").click(function()
			{
				$("#projectPerformanceSelect").val("0");
			});
		});
		</script>

	</body>
</html>
