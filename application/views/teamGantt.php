<html>
	<head>
		<title>Kernel - <?php echo  $projectProfile['PROJECTTITLE'];?></title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/teamGanttStyle.css")?>">
	</head>
	<body class="hold-transition skin-red sidebar-mini sidebar-collapse fixed">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<div style="margin-bottom:10px">
						<a href="<?php echo base_url("index.php/controller/myProjects"); ?>" class="btn btn-default btn" data-toggle="tooltip" data-placement="top" title="Return to My Projects"><i class="fa fa-arrow-left"></i></a>
					</div>
					<h1><?php echo $projectProfile['PROJECTTITLE']; ?></h1>
				</h2><?php echo $_SESSION['DEPARTMENTNAME']; ?></h2>

				<?php if($projectProfile['PROJECTSTATUS'] != 'Planning'): ?>

					<div class="col-md-3 col-sm-6 col-xs-12 pull-right">
							<div class="box-header with-border" style="text-align:center;">
								<h3 class="box-title"><?php echo $_SESSION['DEPARTMENTNAME'];?> Performance</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<div style="display:inline-block; text-align:center; width:49%;">
									<div class="circlechart"
										data-percentage="<?php
											if($departmentCompleteness['completeness'] == NULL){
												echo 0;
											} else {
												if($departmentCompleteness['completeness'] == 100.00){
													echo 100;
												} elseif ($departmentCompleteness['completeness'] == 0.00) {
													echo 0;
												} else {
													echo $departmentCompleteness['completeness'];
												}
											}
											?>">Completeness
									</div>
								</div>
								<div style="display:inline-block; text-align:center; width:49%;">
									<div class="circlechart" id="completeness"
									 data-percentage="<?php
										 if($departmentTimeliness['timeliness'] == NULL){
											 echo 0;
										 } else {
											 if($departmentTimeliness['timeliness'] == 100.00){
												 echo 100;
											 } elseif ($departmentTimeliness['timeliness'] == 0.00) {
												 echo 0;
											 } else {
												 echo $departmentTimeliness['timeliness'];
											 }
										 }
									 ?>">Timeliness
								 </div>
							 </div>
							</div>
					</div>
					<!-- /.col -->

					<?php
						$isResponsible = FALSE;
						foreach ($responsible as $r) {
							if($r['USERID'] == $_SESSION['USERID']){
								$isResponsible = TRUE;
								break;
							}
						}
					?>

					<?php if($isResponsible == TRUE): ?>
						<div class="col-md-3 col-sm-6 col-xs-12 pull-right" style="border-right: solid 1px #b3b3b3;">
								<div class="box-header with-border" style="text-align:center;">
									<h3 class="box-title">My Performance</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div style="display:inline-block; text-align:center; width:49%;">
										<div class="circlechart" id="completeness"
											data-percentage="<?php
												if($employeeCompleteness['completeness'] == NULL){
													echo 0;
												} else {
													if($employeeCompleteness['completeness'] == 100.00){
														echo 100;
													} elseif ($employeeCompleteness['completeness'] == 0.00) {
														echo 0;
													} else {
														echo $employeeCompleteness['completeness'];
													}
												}
												?>">Completeness
										</div>
									</div>
									<div style="display:inline-block; text-align:center; width:49%;">
										<div class="circlechart" id="completeness"
										 data-percentage="<?php
										 if($employeeTimeliness['timeliness'] == NULL){
											 echo 0;
										 } else {
											 if($employeeTimeliness['timeliness'] == 100.00){
												 echo 100;
											 } elseif ($employeeTimeliness['timeliness'] == 0.00) {
												 echo 0;
											 } else {
												 echo $employeeTimeliness['timeliness'];
											 }
										 }
										 ?>">Timeliness
									 </div>
								 </div>
								</div>
						</div>
					<?php endif;?>
				<?php endif; ?>

				<ol class="breadcrumb">
          <?php $dateToday = date('F d, Y | l');?>
          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
        </ol>
				</section>

				<!-- Main content -->
				<section class="content container-fluid">
					<h4>Project Owner: <?php echo $projectProfile['FIRSTNAME']; ?> <?php echo $projectProfile['LASTNAME']; ?></h4>
					<h4>Description: <?php echo $projectProfile['PROJECTDESCRIPTION']; ?></h4>
					<div>

						<?php
						$startdate = date_create($projectProfile['PROJECTSTARTDATE']);
						$enddate = date_create($projectProfile['PROJECTENDDATE']);
						$current = date_create(date("Y-m-d")); // get current date
						?>

						<h4>Initial Duration: <?php echo date_format($startdate, "F d, Y"); ?> to <?php echo date_format($enddate, "F d, Y"); ?>
							(<?php echo $projectProfile['duration'];?>
							<?php if($projectProfile['duration'] > 1):?>
								days)
							<?php else:?>
								day)
							<?php endif;?>
						</h4>

						<?php if ($projectProfile['PROJECTSTATUS'] == 'Archived' || $projectProfile['PROJECTSTATUS'] == 'Complete'):?>
							<?php $actualEnd = date_create($projectProfile['PROJECTACTUALENDDATE']);?>

							<h4 style="color:red">Actual Duration: <?php echo date_format($startdate, "F d, Y"); ?> to <?php echo date_format($actualEnd, "F d, Y"); ?>
								(<?php echo $projectProfile['actualDuration'];?>
								<?php if($projectProfile['actualDuration'] > 1):?>
									days)
								<?php else:?>
									day)
								<?php endif;?>
							</h4>

						<?php else:?>

							<h4 style="color:red">
								<?php if ($current >= $startdate && $current <= $enddate && $projectProfile['PROJECTSTATUS'] == 'Ongoing'):?>
									<?php echo $projectProfile['remaining'];?>
									<?php if($projectProfile['remaining'] > 1):?>
										days remaining
									<?php else:?>
										day remaining
									<?php endif;?>
								<?php elseif ($current < $startdate && $projectProfile['PROJECTSTATUS'] == 'Planning'):?>
									Launch in <?php echo $projectProfile['launching'];?>
									<?php if($projectProfile['launching'] > 1):?>
										days
									<?php else:?>
										day
									<?php endif;?>
								<?php elseif ($current >= $startdate && $current >= $enddate && $projectProfile['PROJECTSTATUS'] == 'Ongoing'):?>
									<?php echo $projectProfile['delayed'];?>
									<?php if($projectProfile['delayed'] > 1):?>
										days delayed
									<?php else:?>
										day delayed
									<?php endif;?>
								<?php endif;?>
							</h4>

						<?php endif;?>

						<form name="gantt" action ='projectDocuments' method="POST" id ="prjID">
							<input type="hidden" name="project_ID" value="<?php echo $projectProfile['PROJECTID']; ?>">
							<input type="hidden" name="projectID_logs" value="<?php echo $projectProfile['PROJECTID']; ?>">
						</form>

						<!-- IF USING GET METHOD
						<a href="<?php echo base_url("index.php/controller/projectDocuments/?id=") . $projectProfile['PROJECTID']; ?>" name="PROJECTID" class="btn btn-success btn-xs" id="projectDocu"><i class="fa fa-folder"></i> View Documents</a> -->
						<!-- <a href="<?php echo base_url("index.php/controller/projectLogs/?id=") . $projectProfile['PROJECTID']; ?>"class="btn btn-default btn-xs"><i class="fa fa-flag"></i> View Logs</a> -->

					</div>
					<br>

					<input type="button" class="btn btn-default btn-sm" value="-" onclick="chart.zoomOut();">
					<input type="button" class="btn btn-default btn-sm" value="+" onclick="chart.zoomIn();">
					<input type="button" class="btn btn-default btn-sm" value="Fit All" onclick="chart.fitAll();">
					<!-- <input type="button" value="Day" onclick="chart.zoomTo('day', 1);"> -->
					<input type="button" class="btn btn-default btn-sm" value="Week" onclick="chart.zoomTo('week', 1);">
					<input type="button" class="btn btn-default btn-sm" value="Month" onclick="chart.zoomTo('month', '1')">

					<!-- Task Details Modal -->
					<div class="modal fade" id="taskDetails" tabindex="-1">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h2 class="modal-title" id='taskName'>Task Name here</h2>
									<h4 id="taskDates">Start Date - End Date (Days)</h4>
								</div>
								<div class="modal-body">
									<div class="btn-group">
										<button type="button" id = "tabRFCEffect" class="btn btn-default tabDetails">RFC Effect</button>
										<button type="button" id = "tabDependency" class="btn btn-default tabDetails">Dependencies</button>
										<button type="button" id = "tabRACI" class="btn btn-default tabDetails">RACI</button>
										<button type="button" id = "tabRFC" class="btn btn-default tabDetails">RFC</button>
										<button type="button" id = "tabDelay" class="btn btn-default tabDetails">Delay</button>
									</div>
									<br><br>

									<div id="divRFCEffect" class="divDetails">
										<table class="table table-bordered" id="rfcEffectDateTable">
											<thead id="rfcEffectDate">
												<th colspan = '5'>Affected Tasks If Approved</th>
												<tr class='text-center'><td id="rfcEffectDateTitle" colspan='5'></td></tr>
												<tr id="rfcEffectDateHeader">
													<th width="1%"></th>
													<th width="35%">Task</th>
													<th width="20%" class="text-center">Start Date</th>
													<th width="20%" class="text-center">End Date</th>
													<th width="24%">Responsible</th>
												</tr>
											</thead>
											<tbody id="rfcEffectDateData">
											</tbody>
										</table>
									</div>

									<div id="divRACI" class="divDetails">
										<table class="table table-bordered">
											<thead id="raciHeader">
												<th colspan = '4'>Current</th>
												<tr>
													<th width="25%" class='text-center'>R</th>
													<th width="25%" class='text-center'>A</th>
													<th width="25%" class='text-center'>C</th>
													<th width="25%" class='text-center'>I</th>
												</tr>
											</thead>
											<tbody id="raciCurrentTable">
											</tbody>
										</table>

										<table class="table table-bordered">
											<thead>
												<th colspan = '4'>History</th>
												<tr class='text-center'><td id="raciHistoryTitle" colspan='4'></td></tr>
												<tr id="raciHeader2">
													<th width="25%" class='text-center'>R</th>
													<th width="25%" class='text-center'>A</th>
													<th width="25%" class='text-center'>C</th>
													<th width="25%" class='text-center'>I</th>
												</tr>
											</thead>
											<tbody id="raciHistoryTable">
											</tbody>
										</table>
									</div>

									<div id="divRFC" class="divDetails">
										<table class="table table-bordered">
											<thead id="rfcHeader">
												<tr>
													<th width="1%" class='text-center'>Type</th>
													<th width="20%">Requested By</th>
													<th width="20%" class='text-center'>Date Requested</th>
													<th width="18%" class='text-center'>Status</th>
													<th width="20%">Reviewed By</th>
													<th width="20%" class='text-center'>Date Reviewed</th>
												</tr>
											</thead>
											<tbody id="rfcHistory">
											</tbody>
										</table>
									</div>

									<div id="divDelay" class="divDetails">
										<table class="table table-bordered" id='projectDelayTable'>
											<thead>
												<th colspan = '2'>Project</th>
												<tr id="affectedDelayHeader">
													<td width="25%">Target End Date</td>
													<td id='projectEndDates'>MMM DD, YYYY</td>
												</tr>
											</thead>
											<tbody id="projectDelayData">
											</tbody>
										</table>

										<table class="table table-bordered">
											<thead id="affectedDelay">
												<th colspan = '5'>Affected Tasks Projection</th>
												<tr class='text-center'><td id="affectedTitle" colspan='5'></td></tr>
												<tr id="affectedDelayHeader">
													<th width="1%"></th>
													<th width="35%">Task</th>
													<th width="20%" class="text-center">Start Date</th>
													<th width="20%" class="text-center">End Date</th>
													<th width="24%">Responsible</th>
												</tr>
											</thead>
											<tbody id="affectedDelayData">
											</tbody>
										</table>
									</div>

									<div id="divDependency" class="divDetails">
										<table class="table table-bordered">
											<thead>
												<th colspan = '5'>Pre-Requisites</th>
												<tr class='text-center'><td id="preReqTitle" colspan='5'></td></tr>
												<tr id="dependencyPreHeader">
													<th width="1%"></th>
													<th width="35%">Task</th>
													<th width="20%" class="text-center">Start Date</th>
													<th width="20%" class="text-center">End Date</th>
													<th width="24%">Responsible</th>
												</tr>
											</thead>
											<tbody id="dependencyPreBody">
											</tbody>
										</table>

										<table class="table table-bordered">
											<thead>
												<th colspan = '5'>Post-Requisites</th>
												<tr class='text-center'><td id="postReqTitle" colspan='5'></td></tr>
												<tr id="dependencyPostHeader">
													<th width="1%"></th>
													<th width="35%">Task</th>
													<th width="20%" class="text-center">Start Date</th>
													<th width="20%" class="text-center">End Date</th>
													<th width="24%">Responsible</th>
												</tr>
											</thead>
											<tbody id="dependencyPostBody">
											</tbody>
										</table>
									</div>

								</div>
								<!-- /.modal-body -->
								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-right" data-dismiss="modal" data-toggle="tooltip" data-placement="left" title="Close"><i class="fa fa-close"></i></button>
								</div>
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>
					<!-- /.modal -->

					<div id="container" style="height: 600px;"></div>

					<!-- </section> -->
				</section>
					</div>
			<?php require("footer.php"); ?>
		</div>
		<script>
			$("#myTeam").addClass("active");
			$('.circlechart').circlechart(); // Initialization

		</script>


		<!-- Javascript for Tasks -->

		<script>

			$('.select2').select2()
			$('.circlechart').circlechart(); // Initialization

			$(function ()
			{
				//Date picker
			 $('#startDate').datepicker({
				 autoclose: true
			 });

			 $('#endDate').datepicker({
				 autoclose: true
			 });
		 });

			$("#projectDocu").click(function() //redirect to individual project profile
			{
				$("#prjID").submit();
			});

			$("#projectLog").click(function() //redirect to individual project logs
			{
				$("#prjID").attr("action","projectLogs");
				$("#prjID").submit();
			});

			$(".task").click(function() //redirect to individual project logs
			{
				var $taskID = $(this).attr('data-id');
				var $rfcID = $(this).attr('data-rfc');
				var $rfcType = $(this).attr('data-rfcType');

				getTaskDetails($taskID);

				// RFC EFFECT
				if($rfcType == 2) //change dates
				{
					$("#rfcEffectPerformer").hide();
					$("#rfcEffectDateTable").show();

					$.ajax({
					 type:"POST",
					 url: "<?php echo base_url("index.php/controller/getRFCDateEffect"); ?>",
					 data: {rfc_ID: $rfcID},
					 dataType: 'json',
					 success:function(rfcAffectedTasks)
					 {
						 $('#rfcEffectDateTitle').hide();
						 $('#rfcEffectDateData').html("");

						 if(rfcAffectedTasks.length > 0)
						 {
							 var d = new Date();
							 var month = d.getMonth()+1;
							 var day = d.getDate();
							 var currDate = d.getFullYear() + '-' +
										((''+month).length<2 ? '0' : '') + month + '-' +
										((''+day).length<2 ? '0' : '') + day;

							 for(i=0; i < rfcAffectedTasks.length; i++)
							 {
								 if(rfcAffectedTasks[i].id != null)
								 {
									 if(rfcAffectedTasks[i].taskStatus == "Complete")
									 {
										 var status = "<td class='bg-teal'></td>";
									 }
									 if(rfcAffectedTasks[i].taskStatus == "Planning")
									 {
										 var status = "<td class='bg-yellow'></td>";
									 }
									 if(rfcAffectedTasks[i].taskStatus == "Ongoing")
									 {
										 if(currDate > rfcAffectedTasks[i].endDate)
											 var status = "<td class='bg-red'></td>";
										 else
											 var status = "<td class='bg-green'></td>";
									 }

									 $('#rfcEffectDateData').append(
													"<tr>" + status +
													"<td>" + rfcAffectedTasks[i].taskTitle+"</td>"+
													"<td align='center'><span style='color:gray'><strike>" +
													moment(rfcAffectedTasks[i].startDate).format('MMM DD, YYYY') +
													"</strike></span><br>" + moment(rfcAffectedTasks[i].newStartDate).format('MMM DD, YYYY') + "</td>" +
													"<td align='center'><span style='color:gray'><strike>" +
													moment(rfcAffectedTasks[i].endDate).format('MMM DD, YYYY') +
													"</strike></span><br>" + moment(rfcAffectedTasks[i].newEndDate).format('MMM DD, YYYY') + "</td>" +
													"<td>" + rfcAffectedTasks[i].responsible + "</td></tr>");
								 }
							 }
						 }
						else
						{
							$("#rfcEffectDateData").html("<tr><td colspan='5' align='center'>There are no post-requisite tasks that will be affected</td></tr>")
							$("#rfcEffectDate").hide();
						}
					 },
					 error:function()
					 {
						 alert("There was a problem in retrieving the effect details");
					 }
					});

					$("#tabRFCEffect").show();
					$("#tabRFCEffect").addClass("active");
					$("#divRFCEffect").show();
				}
				else //change performer
				{
					// $("#rfcEffectDateTable").hide();
					// $.ajax({
					//  type:"POST",
					//  url: "<?php echo base_url("index.php/controller/getDelayEffect"); ?>",
					//  data: {rfc_ID: $rfcID},
					//  dataType: 'json',
					//  success:function(rfcAffectedWorkload)
					//  {
					//  },
					//  error:function()
					//  {
					// 	 alert("There was a problem in retrieving the effect details");
					//  }
					// });

					$("#tabRFCEffect").hide();
					$("#tabDependency").addClass("active");
					$("#divDependency").show();
				}

			});

			// TASK DETAILS

			function getTaskDetails($taskID){
				$(".divDetails").hide();
				$(".tabDetails").removeClass('active');

				$.ajax({
					type:"POST",
					url: "<?php echo base_url("index.php/controller/loadTaskHistory"); ?>",
					data: {task_ID: $taskID},
					dataType: 'json',
					success:function(data)
					{
						$("#taskName").html(data['task'].TASKTITLE);

						if(data['task'].TASKADJUSTEDSTARTDATE == null)
							var startDate = data['task'].TASKSTARTDATE;
						else
							var startDate = data['task'].TASKADJUSTEDSTARTDATE;

						if(data['task'].TASKADJUSTEDENDDATE == null)
							var endDate = data['task'].TASKENDDATE;
						else
							var endDate = data['task'].TASKADJUSTEDENDDATE;

						var diff = ((new Date(endDate) - new Date(startDate))/ 1000 / 60 / 60 / 24)+1;

						$("#taskDates").html(moment(startDate).format('MMMM DD, YYYY') + " - " + moment(endDate).format('MMMM DD, YYYY') + " (" + diff);
						if(diff > 1)
							$("#taskDates").append(" days)");
						else
							$("#taskDates").append(" day)");

							var d = new Date();
							var month = d.getMonth()+1;
							var day = d.getDate();
							var currDate = d.getFullYear() + '-' +
									 ((''+month).length<2 ? '0' : '') + month + '-' +
									 ((''+day).length<2 ? '0' : '') + day;

							var isDelayed = 'false';

							if(currDate > endDate && data['task'].TASKSTATUS == 'Ongoing')
							{
								var isDelayed = 'true';
							}

							if(isDelayed == 'true'){
								$("#tabDelay").show();
								$("#projectDelayTable").hide();

								// DELAY
								$.ajax({
								 type:"POST",
								 url: "<?php echo base_url("index.php/controller/getDelayEffect"); ?>",
								 data: {task_ID: $taskID},
								 dataType: 'json',
								 success:function(affectedTasks)
								 {
									 $('#affectedTitle').hide();
									 $('#affectedDelayData').html("");

									 if(affectedTasks.length > 0)
									 {
										 var d = new Date();
										 var month = d.getMonth()+1;
										 var day = d.getDate();
										 var currDate = d.getFullYear() + '-' +
													((''+month).length<2 ? '0' : '') + month + '-' +
													((''+day).length<2 ? '0' : '') + day;

										 for(i=0; i < affectedTasks.length; i++)
										 {
											 if(affectedTasks[i].id != null)
											 {
												 if(affectedTasks[i].taskStatus == "Complete")
												 {
													 var status = "<td class='bg-teal'></td>";
												 }
												 if(affectedTasks[i].taskStatus == "Planning")
												 {
													 var status = "<td class='bg-yellow'></td>";
												 }
												 if(affectedTasks[i].taskStatus == "Ongoing")
												 {
													 if(currDate > affectedTasks[i].endDate)
														 var status = "<td class='bg-red'></td>";
													 else
														 var status = "<td class='bg-green'></td>";
												 }

												 $('#affectedDelayData').append(
																"<tr>" + status +
																"<td>" + affectedTasks[i].taskTitle+"</td>"+
																"<td align='center'><span style='color:gray'><strike>" + moment(affectedTasks[i].startDate).format('MMM DD, YYYY') + "</strike></span><br>" + moment(affectedTasks[i].newStartDate).format('MMM DD, YYYY') + "</td>"+
																"<td align='center'><span style='color:gray'><strike>" + moment(affectedTasks[i].endDate).format('MMM DD, YYYY') + "</strike></span><br>" + moment(affectedTasks[i].newEndDate).format('MMM DD, YYYY') + "</td>"+
																"<td>" + affectedTasks[i].responsible + "</td></tr>");

												if(affectedTasks[i].projEndDate < affectedTasks[i].newEndDate)
												{
													$("#projectDelayTable").show();
													$('#projectEndDates').html("<span style='color:gray'><strike>" + moment(affectedTasks[i].projEndDate).format('MMM DD, YYYY') + "</strike></span>");
													$('#projectEndDates').append("<b> " + moment(affectedTasks[i].newEndDate).format('MMM DD, YYYY')+ " </b>");
												}
											 }
										 }
									 }
									else
									{
										$("#affectedDelayData").html("<tr><td colspan='5' align='center'>There are no post-requisite tasks that will be affected</td></tr>")
										$("#affectedDelay").hide();
									}
								 },
								 error:function()
								 {
									 alert("There was a problem in retrieving the task details");
								 }
								});

							} else {
								$("#tabDelay").hide();
							}

						// TASK DELEGATION
						$("#raciCurrentTable").html("");
						$("#raciHistoryTable").html("");
						$('#raciHistoryTitle').hide();

						var withHistory = false;

						for(rh=0; rh < data['raciHistory'].length; rh++)
						{
							if(data['raciHistory'][rh].ROLE == 1 && data['raciHistory'][rh].STATUS == 'Current')
							{
								$("#raciCurrentTable").append(
									"<tr>" +
										"<td id = 'currentR'></td>" +
										"<td id = 'currentA'></td>" +
										"<td id = 'currentC'></td>" +
										"<td id = 'currentI'></td>" +
									"</tr>");

								for(rc=0; rc < data['raciHistory'].length; rc++)
								{
									if(data['raciHistory'][rc].ROLE == 1 && data['raciHistory'][rc].STATUS == 'Current')
									{
										$("#currentR").append(data['raciHistory'][rc].FIRSTNAME + " " + data['raciHistory'][rc].LASTNAME + "<br>");
									}
									if(data['raciHistory'][rc].ROLE == 2 && data['raciHistory'][rc].STATUS == 'Current')
									{
										$("#currentA").append(data['raciHistory'][rc].FIRSTNAME + " " + data['raciHistory'][rc].LASTNAME + "<br>");
									}
									if(data['raciHistory'][rc].ROLE == 3 && data['raciHistory'][rc].STATUS == 'Current')
									{
										$("#currentC").append(data['raciHistory'][rc].FIRSTNAME + " " + data['raciHistory'][rc].LASTNAME + "<br>");
									}
									if(data['raciHistory'][rc].ROLE == 4 && data['raciHistory'][rc].STATUS == 'Current')
									{
										$("#currentI").append(data['raciHistory'][rc].FIRSTNAME + " " + data['raciHistory'][rc].LASTNAME + "<br>");
									}
								}
							}

							if(data['raciHistory'][rh].ROLE == 1 && data['raciHistory'][rh].STATUS == 'Changed')
							{
								var withHistory = true;
								$("#raciHistoryTable").append(
									"<tr>" +
										"<td id = 'changedR'></td>" +
										"<td id = 'changedA'></td>" +
										"<td id = 'changedC'></td>" +
										"<td id = 'changedI'></td>" +
									"</tr>");

								for(ro=0; ro < data['raciHistory'].length; ro++)
								{
									if(data['raciHistory'][ro].ROLE == 1 && data['raciHistory'][ro].STATUS == 'Changed')
									{
										$("#changedR").append(data['raciHistory'][ro].FIRSTNAME + " " + data['raciHistory'][ro].LASTNAME);
									}
									if(data['raciHistory'][ro].ROLE == 2 && data['raciHistory'][ro].STATUS == 'Changed')
									{
										$("#changedA").append(data['raciHistory'][ro].FIRSTNAME + " " + data['raciHistory'][ro].LASTNAME);
									}
									if(data['raciHistory'][ro].ROLE == 3 && data['raciHistory'][ro].STATUS == 'Changed')
									{
										$("#changedC").append(data['raciHistory'][ro].FIRSTNAME + " " + data['raciHistory'][ro].LASTNAME);
									}
									if(data['raciHistory'][ro].ROLE == 4 && data['raciHistory'][ro].STATUS == 'Changed')
									{
										$("#changedI").append(data['raciHistory'][ro].FIRSTNAME + " " + data['raciHistory'][ro].LASTNAME);
									}
								}
							}

						} // end for loop

						if(!withHistory)
						{
							$('#raciHistoryTitle').html("There is no RACI assignment history");
							$('#raciHeader2').hide();
							$('#raciHistoryTitle').show();
						}

						// RFC HISTORY
						if(data['changeRequests'].length <= 0)
						{
							$("#rfcHistory").html("<tr><td colspan='5' align='center'>There is no change request history</td></tr>")
							$("#rfcHeader").hide();
						}
						else
						{
							$("#rfcHistory").html("");
							$("#rfcHeader").show();

							for(r=0; r < data['changeRequests'].length; r++)
							{
								if(data['changeRequests'][r].REQUESTTYPE == '1')
									var type = "<i class='fa fa-user-times'></i>";
								else
									var type = "<i class='fa fa-calendar'></i>";

								for(u=0; u < data['users'].length; u++)
								{
									if(data['changeRequests'][r].users_REQUESTEDBY == data['users'][u].USERID)
										var requester = data['users'][u].FIRSTNAME + " " + data['users'][u].LASTNAME;

									if(data['changeRequests'][r].users_APPROVEDBY == data['users'][u].USERID)
										var approver = "<td>" + data['users'][u].FIRSTNAME + " " + data['users'][u].LASTNAME + "</td>";
								}

								if(data['changeRequests'][r].REQUESTSTATUS == 'Pending')
								{
									var approver = "<td align='center'>-</td>";
									var reviewDate = "-";
								}
								else
								{
									var reviewDate = moment(data['changeRequests'][r].APPROVEDDATE).format('MMM DD, YYYY');
								}

								$("#rfcHistory").append(
									"<tr>" +
									"<td align='center'>" + type + "</td>" +
									"<td>" + requester + "</td>" +
									"<td align='center'>" + moment(data['changeRequests'][r].REQUESTEDDATE).format('MMM DD, YYYY') + "</td>" +
									"<td align='center'>" + data['changeRequests'][r].REQUESTSTATUS + "</td>" +
									approver  +
									"<td align='center'>" + reviewDate + "</td>" +
									"</tr>");
							}
						}
					},
					error:function(data)
					{
						alert("There was a problem with loading the change requests");
					}
				});

				// PRE-REQUISITES
				$.ajax({
					type:"POST",
					url: "<?php echo base_url("index.php/controller/getDependenciesByTaskID"); ?>",
					data: {task_ID: $taskID},
					dataType: 'json',
					success:function(preReqData)
					{
						if(preReqData['dependencies'].length > 0)
						{
							$('#dependencyPreBody').html("");
							$('#preReqTitle').html("");
							$("#preReqTitle").hide();
							for(i=0; i<preReqData['dependencies'].length; i++)
							{
								var taskStart = moment(preReqData['dependencies'][i].TASKSTARTDATE).format('MMM DD, YYYY');
								var startDate = preReqData['dependencies'][i].TASKSTARTDATE;

								if(preReqData['dependencies'][i].TASKADJUSTEDENDDATE == null) // check if start date has been previously adjusted
								{
									var taskEnd = moment(preReqData['dependencies'][i].TASKENDDATE).format('MMM DD, YYYY');
									var endDate = preReqData['dependencies'][i].TASKENDDATE;
								}
								else
								{
									var taskEnd = moment(preReqData['dependencies'][i].TASKADJUSTEDENDDATE).format('MMM DD, YYYY');
									var endDate = preReqData['dependencies'][i].TASKADJUSTEDENDDATE;
								}

								if(preReqData['dependencies'][i].TASKADJUSTEDSTARTDATE != null && preReqData['dependencies'][i].TASKADJUSTEDENDDATE != null)
									var taskDuration = parseInt(preReqData['dependencies'][i].adjustedTaskDuration2);
								if(preReqData['dependencies'][i].TASKSTARTDATE != null && preReqData['dependencies'][i].TASKADJUSTEDENDDATE != null)
									var taskDuration = parseInt(preReqData['dependencies'][i].adjustedTaskDuration1);
								else
									var taskDuration = parseInt(preReqData['dependencies'][i].initialTaskDuration);

								if(preReqData['dependencies'][i].TASKSTATUS == "Complete")
								{
									var status = "<td class='bg-teal'></td>";
								}
								if(preReqData['dependencies'][i].TASKSTATUS == "Planning")
								{
									var status = "<td class='bg-yellow'></td>";
								}
								if(preReqData['dependencies'][i].TASKSTATUS == "Ongoing")
								{
									if(preReqData['dependencies'][i].currDate > endDate)
										var status = "<td class='bg-red'></td>";
									else
										var status = "<td class='bg-green'></td>";
								}

								$('#dependencyPreBody').append(
														 "<tr>" + status +
														 "<td>" + preReqData['dependencies'][i].TASKTITLE+"</td>"+
														 "<td align='center'>" + taskStart+"</td>"+
														 "<td align='center'>" + taskEnd +"</td>" +
														 "<td>" + preReqData['dependencies'][i].FIRSTNAME + " " + preReqData['dependencies'][i].LASTNAME + "</td></tr>");
						 }
						 $("#dependencyPreHeader").show();
					 }
					 else
					 {
						 $('#preReqTitle').html("There are no pre-requisite tasks");
						 $('#dependencyPreBody').html("");
						 $("#dependencyPreHeader").hide();
						 $("#preReqTitle").show();
					 }
					},
					error:function()
					{
						alert("There was a problem in retrieving the task details");
					}
					});

				// POST REQUISITES
				$.ajax({
				 type:"POST",
				 url: "<?php echo base_url("index.php/controller/getPostDependenciesByTaskID"); ?>",
				 data: {task_ID: $taskID},
				 dataType: 'json',
				 success:function(postReqData)
				 {
					 if(postReqData['dependencies'].length > 0)
					 {
						 $('#dependencyPostBody').html("");
						 $("#postReqTitle").hide();
						 for(i=0; i<postReqData['dependencies'].length; i++)
						 {
							 var taskStart = moment(postReqData['dependencies'][i].TASKSTARTDATE).format('MMM DD, YYYY');
							 var startDate = postReqData['dependencies'][i].TASKSTARTDATE;

							 if(postReqData['dependencies'][i].TASKADJUSTEDENDDATE == null) // check if start date has been previously adjusted
							 {
								 var taskEnd = moment(postReqData['dependencies'][i].TASKENDDATE).format('MMM DD, YYYY');
								 var endDate = postReqData['dependencies'][i].TASKENDDATE;
							 }
							 else
							 {
								 var taskEnd = moment(postReqData['dependencies'][i].TASKADJUSTEDENDDATE).format('MMM DD, YYYY');
								 var endDate = postReqData['dependencies'][i].TASKADJUSTEDENDDATE;
							 }

							 if(postReqData['dependencies'][i].TASKSTATUS == "Complete")
							 {
								 var status = "<td class='bg-teal'></td>";
							 }
							 if(postReqData['dependencies'][i].TASKSTATUS == "Planning")
							 {
								 var status = "<td class='bg-yellow'></td>";
							 }
							 if(postReqData['dependencies'][i].TASKSTATUS == "Ongoing")
							 {
								 if(postReqData['dependencies'][i].currDate > endDate)
									 var status = "<td class='bg-red'></td>";
								 else
									 var status = "<td class='bg-green'></td>";
							 }

							 $('#dependencyPostBody').append(
														"<tr>" + status +
														"<td>" + postReqData['dependencies'][i].TASKTITLE+"</td>"+
														"<td align='center'>" + taskStart+"</td>"+
														"<td align='center'>" + taskEnd +"</td>" +
														"<td>" + postReqData['dependencies'][i].FIRSTNAME + " " + postReqData['dependencies'][i].LASTNAME + "</td></tr>");

						}
						$("#dependencyPostHeader").show();
					}
					else
					{
						$('#postReqTitle').html("There are no post-requisite tasks");
						$("#dependencyPostHeader").hide();
						$('#dependencyPostBody').html("");
						$("#postReqTitle").show();
					}
				 },
				 error:function()
				 {
					 alert("There was a problem in retrieving the task details");
				 }
				 });

			}

		</script>

		<script>
			anychart.onDocumentReady(function (){

				var rawData = [
					<?php

					foreach ($ganttData as $key => $value) {

						// START: Formatting of TARGET START date
						$targetStartDate = $value['TASKSTARTDATE'];
						$formatted_startDate = date('Y-m-d', strtotime($targetStartDate));
						// END: Formatting of TARGET START date

						// START: Formatting of TARGET END date
						$targetEndDate = $value['TASKENDDATE'];
						$formatted_endDate = date('Y-m-d', strtotime($targetEndDate));
						// END: Formatting of TARGET END date

						// START: Formatting of ACTUAL START date
						$actualStartDate = $value['TASKACTUALSTARTDATE'];
						$formatted_actualStartDate = date('Y-m-d', strtotime($actualStartDate));
						// END: Formatting of ACTUAL START date

						// START: Formatting of ACTUAL END date
						$actualEndDate = $value['TASKACTUALENDDATE'];
						$formatted_actualEndDate = date('Y-m-d', strtotime($actualEndDate));
						// END: Formatting of ACTUAL END date

						// // START: Formatting of ADJUSTED START date
						// $adjustedStartDate = $value['TASKADJUSTEDSTARTDATE'];
						// $formatted_adjustedStartDate = date('Y-m-d', strtotime($adjustedStartDate));
						// // END: Formatting of ACTUAL END date
						//
						// // START: Formatting of ADJUSTED END date
						// $adjustedEndDate = $value['TASKADJUSTEDENDDATE'];
						// $formatted_adjustedEndDate = date('Y-m-d', strtotime($adjustedEndDate));
						// // END: Formatting of ACTUAL END date

						// START: Checks for progress value
						$progress = '0';
						if($value['TASKSTATUS'] == 'Complete' && $value['CATEGORY'] == 3){
							$progress = 100;
						}
						// END: Checks for progress value

						// START: Checks for parent
						$parent = '0';
						if($value['tasks_TASKPARENT'] != NULL){
							$parent = $value['tasks_TASKPARENT'];
						}
						// END: Checks for parent

						// // START: Checks for period
						// $period = '';
						// if($value['TASKADJUSTEDSTARTDATE'] == NULL && $value['TASKADJUSTEDENDDATE'] == NULL){
						// 	$period = $value['initialTaskDuration'];
						// } else if ($value['TASKADJUSTEDSTARTDATE'] == NULL && $value['TASKADJUSTEDENDDATE'] != NULL){
						// 	$period = $value['adjustedTaskDuration1'];
						// } else {
						// 	$period = $value['adjustedTaskDuration2'];
						// }
						// echo "<script>console.log(".$period.");</script>";
						// // END: Checks for period

						// START: Checks for dependecies
						$dependency = '';
						$type = '';
						foreach ($dependencies as $data) {
							if($data['PRETASKID'] == $value['TASKID']){
								$dependency = $data['tasks_POSTTASKID'];
								$type = 'finish-start';
							}
						}
						// END: Checks for dependecies

						// START: Checks for responsible
						$responsiblePerson = '';
						foreach ($responsible as $r) {
							if($r['tasks_TASKID'] == $value['TASKID']){
								$responsiblePerson = $r['FIRSTNAME'] . " " . $r['LASTNAME'];
							}
						}
						// END: Checks for responsible

						// START: Checks for accountable
						$accountablePeople = '';
						$accountableArray = array();

						foreach ($accountable as $a) {

							if($a['tasks_TASKID'] == $value['TASKID']){
								$accountablePeople = $a['FIRSTNAME'] . " " . $a['LASTNAME'];
								array_push($accountableArray, $accountablePeople);
							}
						}
						$accountableCount = count($accountableArray);
						$accountablePerson = '';
						$counter == 0;

						if($accountableCount != 0){

							$accountablePerson = $accountableArray[0];

							for($i = 1; $i < $accountableCount; $i++){
								$accountablePerson .= (", " . $accountableArray[$i]);
							}
						}
						// END: Checks for accountable

						// START: Checks for consulted
						$consultedPeople = '';
						$consultedArray = array();

						foreach ($consulted as $c) {

							if($c['tasks_TASKID'] == $value['TASKID']){
								$consultedPeople = $c['FIRSTNAME'] . " " . $c['LASTNAME'];
								array_push($consultedArray, $consultedPeople);
							}
						}
						$consultedCount = count($consultedArray);
						$consultedPerson = '';
						$counter == 0;

						if($consultedCount != 0){
							$consultedPerson = $consultedArray[0];

							for($i = 1; $i < $consultedCount; $i++){
								$consultedPerson .= (", " . $consultedArray[$i]);
							}
						}
						// END: Checks for consulted

						// START: Checks for informed
						$informedPeople = '';
						$informedArray = array();

						foreach ($informed as $i) {

							if($i['tasks_TASKID'] == $value['TASKID']){
								$informedPeople = $i['FIRSTNAME'] . " " . $i['LASTNAME'];
								array_push($informedArray, $informedPeople);
							}
						}
						$informedCount = count($informedArray);
						$informedPerson = '';
						$counter == 0;

						if($informedCount != 0){

							$informedPerson = $informedArray[0];

							for($i = 0; $i < $informedCount; $i++){
								$informedPerson .= (", " . $informedArray[$i]);
							}
						}
						// END: Checks for informed

						// START: Check for task involved
						$marker = '""';
						$rowHeight = '""';

						if(isset($_SESSION['rfc']) && !isset($_SESSION['userRequest'])){
							if($changeRequest['TASKID'] == $value['TASKID']){
								$marker = "[{'value': '" . $formatted_startDate . "', 'type': 'diagonalCross'}]";
								$rowHeight = 50;
							}
						}


						// END: Check for task involved

						// START: CHECKS IF MAIN OR SUB
						if($value['CATEGORY'] == 2 || $value['CATEGORY'] == 1){
							// START: Planning - no baseline since task have not yet started
							if(($value['TASKACTUALSTARTDATE'] == NULL)){
								echo "
								{
									'id': " . $value['TASKID'] . ",
									'name': '" . $value['TASKTITLE'] . "',
									'actualStart': '" . $formatted_startDate . "',
									'actualEnd': '" . $formatted_endDate . "',
									'actual':{'fill': 'Orange'},
									'responsible': '" . $responsiblePerson  ."',
									'accountable': '" . $accountablePerson ."',
									'consulted': '" . $consultedPerson  ."',
									'informed': '" . $informedPerson  ."',
									'period': '" . $value['initialTaskDuration'] . " days',
									'parent': '" . $parent . "',
									'connectTo': '" . $dependency . "',
									'connectorType': '" . $type . "',
									'markers': " . $marker . ",
								},";
							} // END: Planning - no baseline since task have not yet started

							// START: Ongoing tasks - baselineEnd is the date today
							else if($value['TASKACTUALENDDATE'] == NULL){
								// not delayed
								if($value['TASKENDDATE'] > date('Y-m-d')){ // ongoing but not delayed
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'actual':{'fill': '#006600'},
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $value['initialTaskDuration'] ." days',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "',
										'markers': " . $marker . ",
									},";
								} else { // ongoing and delayed
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'actual':{'fill': '#006600'},
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $value['initialTaskDuration'] ." days',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "',
										'baselineStart': '" . $formatted_endDate . "',
										'baselineEnd': '" . date('M d, Y') . "',
										'baseline':{'fill': 'Red'},
										'markers': " . $marker . ",
									},";
								}

							} // END: Ongoing tasks - baselineEnd is the date today

							// START: Completed parent - baselineStart and baselineEnd are present
							else {
								if($formatted_endDate >= $formatted_actualEndDate){ // Completed but not delayed
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $value['initialTaskDuration'] . " days',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "',
										'markers': " . $marker . ",
									},";

								} else { // Completed but delayed
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $value['initialTaskDuration'] ." days',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "',
										'baselineStart': '" . $formatted_endDate . "',
										'baselineEnd': '" . $formatted_actualEndDate . "',
										'baseline':{'fill': 'Red'},
										'markers': " . $marker . ",
									},";

								}

							} // END: Completed tasks - baselineStart and baselineEnd are present

						} else { // START: IF TASK
							if(($value['TASKACTUALSTARTDATE'] == NULL)){
								echo "
								{
									'id': " . $value['TASKID'] . ",
									'name': '" . $value['TASKTITLE'] . "',
									'actualStart': '" . $formatted_startDate . "',
									'actualEnd': '" . $formatted_endDate . "',
									'actual':{'fill': 'Orange'},
									'responsible': '" . $responsiblePerson  ."',
									'accountable': '" . $accountablePerson ."',
									'consulted': '" . $consultedPerson  ."',
									'informed': '" . $informedPerson  ."',
									'period': '" . $value['initialTaskDuration'] . " days',
									'progressValue': '" . $progress . "%',
									'parent': '" . $parent . "',
									'connectTo': '" . $dependency . "',
									'connectorType': '" . $type . "',
									'markers': " . $marker . ",
								},";
							} // END: Planning - no baseline since task have not yet started

							// START: Ongoing tasks - baselineEnd is the date today
							else if($value['TASKACTUALENDDATE'] == NULL){
								if($value['TASKENDDATE'] > date('Y-m-d')) { // ongoing task but NOT delayed
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'actual':{'fill': '#006600'},
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $value['initialTaskDuration'] . " days',
										'progressValue': '" . $progress . "%',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'markers': " . $marker . ",
									},";
								} else { // ongoing task but delayed
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'actual':{'fill': '#006600'},
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $value['initialTaskDuration'] . " days',
										'progressValue': '" . $progress . "%',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "',
										'baselineStart': '" . $formatted_endDate ."',
										'baselineEnd': '" . date('M d, Y') . "',
										'baseline':{'fill': 'Red'},
										'markers': '" . $marker . "',
									},";
								} // end: ongoing task but delayed

							} // END: Ongoing tasks - baselineEnd is the date today

							// START: Completed tasks - baselineStart and baselineEnd are present
							else {
								if($formatted_endDate >= $formatted_actualEndDate){ // Completed but not delayed
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $value['initialTaskDuration'] ." days',
										'progressValue': '" . $progress . "%',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "',
										'markers': '" . $marker . "',
									},";

								} else { // Completed but delayed
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $value['initialTaskDuration'] ." days',
										'progressValue': '" . $progress . "%',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "',
										'baselineStart': '" . $formatted_endDate . "',
										'baselineEnd': '" . $formatted_actualEndDate . "',
										'baseline':{'fill': 'Red'},
										'markers': '" . $marker . "',
									},";

								} // END: completed but delayed
							} // END: Completed tasks - baselineStart and baselineEnd are present
						} // END: CHECKS FOR CATEGORY
					} // END: Foreach
					?>

				];

				// data tree settings
				var treeData = anychart.data.tree(rawData, "as-table");
				chart = anychart.ganttProject();      // chart type
				chart.data(treeData);                 // chart data

				var tl = chart.getTimeline();
				tl.lineMarker(0).value("current");

				var groupingTasks = tl.groupingTasks();
				var progress = groupingTasks.progress();
	    	progress.normal({fill: '#66b2b2'});

				// groupingTasks.normal({fill: 'Orange'});
				// console.log(anychart.VERSION);

				var elements = tl.elements();
				var selectedElement = elements.selected();
				selectedElement.fill('').stroke('');
				chart.rowSelectedFill('#FFFFCC');

				var tasks = tl.tasks();
				var taskProgress = tasks.progress();
				taskProgress.normal({fill: '#66b2b2'});

				chart.listen('rowdblClick', function(e) {

					var taskID = e.item.get('id');

					// if parent (no modal)
					if(e.item && !e.item.numChildren()){
						$("#taskDetails").modal('show');
						var $taskID = e.item.get('id');

						getTaskDetails($taskID);
						$("#tabRFCEffect").hide();
						$("#tabDependency").addClass("active");
						$("#divDependency").show();
					}
	    	});

				// data grid getter
				var dataGrid = chart.dataGrid();

				dataGrid.column(0).labels({hAlign: 'center'});

				// create custom column
				var columnTitle = dataGrid.column(1);
				columnTitle.title("Task Name");
				columnTitle.setColumnFormat("name", "text");
				columnTitle.width(300)
					.labelsOverrider(labelTextSettingsOverrider)
	      	.labels()
	      	.format(function() {
						return this.item.get('name');
					});
				// .labels().format(function(item) {
	      //   return this.item.get('name');
	      // });

				var columnStartDate = dataGrid.column(2);
				columnStartDate.title("Target Start Date");
				columnStartDate.labels({hAlign: 'center'});
				columnStartDate.setColumnFormat("actualStart", {
					"formatter": dateFormatter
				});
				columnStartDate.width(100);

				var columnEndDate = dataGrid.column(3);

				columnEndDate.title("Target End Date");
				columnEndDate.labels({hAlign: 'center'});
				columnEndDate.setColumnFormat("actualEnd", {
					"formatter": dateFormatter
				});
				columnEndDate.width(100);

				var columnPeriod = dataGrid.column(4);
				columnPeriod.title("Period");
				columnPeriod.setColumnFormat("period", "text");
				columnPeriod.width(80);
				columnPeriod.labels({hAlign: 'center'});

				var columnResponsible = dataGrid.column(5);
				columnResponsible.title("R");
				columnResponsible.setColumnFormat("responsible", "text");
				columnResponsible.width(100);

				var columnAccountable = dataGrid.column(6);
				columnAccountable.title("A");
				columnAccountable.setColumnFormat("accountable", "text");
				columnAccountable.width(100);

				var columnConsulted = dataGrid.column(7);
				columnConsulted.title("C");
				columnConsulted.setColumnFormat("consulted", "text");
				columnConsulted.width(100);

				var columnInformed = dataGrid.column(9);
				columnInformed.title("I");
				columnInformed.setColumnFormat("informed", "text");
				columnInformed.width(100);

				chart.splitterPosition(650);
				chart.container('container').draw();      // set container and initiate drawing

				<?php $count = 0; foreach ($ganttData as $key => $value){ $count++; } ?>

				<?php
					foreach ($ganttData as $key => $value){
						if(isset($_SESSION['rfc']) && !isset($_SESSION['userRequest'])){
							if($changeRequest['TASKID'] == $value['TASKID']){
								if($count == $value['TASKID']){
									echo "chart.zoomTo('day', 7, 'last-date');";
								} else {
									echo "chart.fitToTask('" . $value['TASKID'] . "');";
								}
							}
						} else {
							echo "chart.fitAll();";
						}
					}
				?>

			});

			function dateFormatter (value){
				// var stringDate = strtotime(value);
				var date = new Date(value);
				var month = date.toLocaleDateString("en-US", {month: "short"});
				var day = date.getDate();
				if(day < 10){
					day = "0"+day;
				}
				var year = date.getFullYear()
				return month + " " + day + ", " + year;
			}

			// TASK DETAILS TABS
			$(document).on("click", "#tabDependency", function(){
				$(".divDetails").hide();
				$(".tabDetails").removeClass('active');
				$(this).addClass('active')
				$("#divDependency").show();
			});

			$(document).on("click", "#tabRACI", function(){
				$(".divDetails").hide();
				$(".tabDetails").removeClass('active');
				$(this).addClass('active')
				$("#divRACI").show();
			});

			$(document).on("click", "#tabRFC", function(){
				$(".divDetails").hide();
				$(".tabDetails").removeClass('active');
				$(this).addClass('active')
				$("#divRFC").show();
			});

			$(document).on("click", "#tabDelay", function(){
				$(".divDetails").hide();
				$(".tabDetails").removeClass('active');
				$(this).addClass('active')
				$("#divDelay").show();
			});

			$(document).on("click", "#tabRFCEffect", function(){
				$(".divDetails").hide();
				$(".tabDetails").removeClass('active');
				$(this).addClass('active')
				$("#divRFCEffect").show();
			});

			function labelTextSettingsOverrider(label, item) {
				var taskTitle = item.get('name');
				// console.log(taskTitle);

			}

			// function labelTextSettingsOverrider(label, item) {
			// 	var taskTitle = item.get('name');
			// 	var rfcTaskTitle = '';
			//
			// 	<?php
			// 		foreach ($ganttData as $key => $value){
			// 			if(isset($_SESSION['rfc']) && !isset($_SESSION['userRequest'])){
			// 				if($changeRequest['TASKID'] == $value['TASKID']){
			// 					echo " var rfcTaskTitle = " . $value['TASKTITLE'] . ";";
			// 				}
			// 			}
			// 		}
			// 	?>
			//
			// 	console.log("rfc task title" + rfcTaskTitle);
			// }

		</script>
	</body>
	</html>
