<html>
	<head>
		<title>Kernel - Dashboard</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/dashboardStyle.css")?>">
	</head>
	<body>
		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Welcome, <b><?php echo $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME']; ?>!</b>
				</h1>

				<ol class="breadcrumb">
          <?php $dateToday = date('F d, Y | l');?>
          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
        </ol>
			</section>

			<section class="content container-fluid">

				<!-- ALERTS -->
				<!-- <?php if (isset($_SESSION['alertMessage'])): ?>
					<script>
					$(document).ready(function()
					{
						successAlert();
					});
					</script>
				<?php endif; ?> -->
				<!-- <div>
					<button id="success" type="button" class="btn btn-success">Test Success</button>
					<button id="warning" type="button" class="btn btn-warning">Test Warning</button>
					<button id="danger" type="button" class="btn btn-danger">Test Danger</button>
					<button id="info" type="button" class="btn btn-info">Test Info</button>
				</div>
				<br> -->

				<?php if($_SESSION['usertype_USERTYPEID'] != 2):?>

					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="box box-danger">
								<div class="box-header with-border">
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
						</div>
						<!-- /.col -->
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title"><?php echo $_SESSION['DEPARTMENTNAME'];?> Performance</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div style="display:inline-block; text-align:center; width:49%;">
										<div class="circlechart" id="completeness"
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
						</div>
						<!-- /.col -->
					</div>

					<!-- MANAGE TABLE -->
					<!-- Main row -->
					<?php if($ongoingProjects != null):?>
						<div class="row">
							<div class="col-md-12">
								<div class="box box-danger" style="height:35%; overflow-y: scroll">
									<div class="box-header with-border">
										<h3 class="box-title">Projects I'm Working On (<?php echo count($ongoingProjects);?>)</h3>
									</div>
									<!-- /.box-header -->
									<div class="box-body">
										<div class="table-responsive">
											<table class="table table-hover no-margin" id="projWeeklyProgress">
												<thead>
												<tr>
													<th width='1%'></th>
													<th>Project</th>
													<th class="text-center">Last Week's Progress</th>
													<th class="text-center">Current Progress</th>
													<th class="text-center">Target End Date</th>
													<th class="text-center">Days Left</th>
												</tr>
												</thead>
												<tbody>
													<?php foreach($ongoingProjects as $key => $ongoingProject): ?>
														<tr class = "projects clickable" data-id="<?php echo $ongoingProject['PROJECTID'];?>">

															<form class='projectForm' action = 'projectGantt' method="POST">
																<input type ='hidden' name='dashboard' value='0'>
															</form>

															<?php if($ongoingProject['datediff'] >= 0):?>
																<td class = 'bg-green'></td>
															<?php else:?>
																<td class = 'bg-red'></td>
															<?php endif;?>

															<td><?php echo $ongoingProject['PROJECTTITLE'];?></td>
															<?php
															$lstwkprogress = "-";
																foreach ($lastWeekProgress as $row)
																{
																	if ($ongoingProject['PROJECTID'] == $row['projects_PROJECTID'])
																	{
																		$lstwkprogress = $row['COMPLETENESS'] . "%";
																	}
																}
															?>
															<td align="center"><?php echo $lstwkprogress; ?></td>
															<td align="center">
																<?php
																	foreach ($currentProgress as $row)
																	{
																		if ($ongoingProject['PROJECTID'] == $row['projects_PROJECTID'])
																		{
																			if($row['COMPLETENESS'] == 100.00){
																				echo 100;
																			} elseif ($row['COMPLETENESS'] == 0.00) {
																				echo 0;
																			} else {
																				echo $row['COMPLETENESS'];
																			}
																		}
																	} ?>%
															</td>
															<?php
															if($ongoingProject['PROJECTADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
																$endDate = date_create($ongoingProject['PROJECTENDDATE']);
															else
																$endDate = date_create($ongoingProject['PROJECTADJUSTEDENDDATE']);
															?>
															<td align="center"><?php echo date_format($endDate, "M d, Y");?></td>

															<td align="center"><?php echo $ongoingProject['datediff'];?>
															</td>
														</tr>
													<?php endforeach;?>
												</tbody>
											</table>
										</div>
										<!-- /.table-responsive -->
									</div>
									<!-- /.box-body -->
									<!-- /.box-footer -->
								</div>
								<!-- /.box -->
							</div>
						</div>
						<?php endif;?>
					<!-- END MANAGE TABLE -->

					<!-- TASK TABLE -->
					<!-- Main row -->
					<div class="row">
						<!-- Left col -->
						<div class="col-md-6">
							<div class="box box-danger" style="height:45%; overflow-y: scroll">
								<div class="box-header with-border">
									<h3 class="box-title">Tasks I Need To Do(<?php echo count($tasks2DaysBeforeDeadline);?>)</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table class="table table-hover no-margin" id="logsList">
											<thead>
												<th width="1%"></th>
												<th>Project</th>
												<th>Task</th>
												<th width = '16%' class = 'text-center'>End Date</th>
												<th width = '15%' class = 'text-center'>Status</th>
											</thead>
											<tbody>

												<?php if($tasks2DaysBeforeDeadline != NULL): ?>
													<?php
														foreach ($tasks2DaysBeforeDeadline as $data)
														{
															if($data['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
																$endDate = date_create($data['TASKENDDATE']);
															else
																$endDate = date_create($data['TASKADJUSTEDENDDATE']);
															if($data['DATEDIFF'] < 0){
																$status = "<td style='color:red' class = 'text-center'><b>DELAYED</b></td>";
																$color = "bg-red";
															} else {
																if($data['DATEDIFF'] > 1)
																	$status = "<td class = 'text-center'>" . $data['DATEDIFF'] . " days left</td>";
																else
																	$status = "<td class = 'text-center'>" . $data['DATEDIFF'] . " day left</td>";
																$color = "bg-green";
															}
															echo "<tr class='clickable deadline'>";
																echo "<td class='" . $color . "'></td>";
																echo "<td class='projectLink'>" . $data['PROJECTTITLE'] . "</td>";
																echo "<td>" . $data['TASKTITLE'] . "</td>";
																echo "<td class = 'text-center'>" . date_format($endDate, "M d, Y") . "</td>";
																echo $status;
															echo "</tr>";
														}
													?>

												<?php else: ?>
												<tr>
													<td colspan="4" align="center">No tasks to do</td>
												</tr>

												<?php endif;?>
											</tbody>
										</table>
									</div>
									<!-- /.table-responsive -->
								</div>
								<!-- /.box-body -->
								<!-- /.box-footer -->
							</div>
							<!-- /.box -->
						</div>


						<?php if ($_SESSION['usertype_USERTYPEID'] != 5): ?>
							<!-- Right col -->

							<div class="col-md-6">
								<div class="box box-danger" style="height:45%; overflow-y: scroll">
									<div class="box-header with-border">
										<h3 class="box-title">Tasks I Need To Delegate (<?php echo count($delegateTasks);?>)</h3>
									</div>
									<!-- /.box-header -->
									<div class="box-body">
										<div class="table-responsive">
											<table class="table table-hover no-margin" id="projWeeklyProgress">
												<thead>
												<tr>
													<th width = '1%'></th>
													<th>Project</th>
													<th>Task</th>
													<th class="text-center">Start Date</th>
													<!-- <th class="text-center">Days Until Launch</th> -->
												</tr>
												</thead>
												<tbody>
													<?php if ($delegateTasks != NULL): ?>

													<?php foreach($delegateTasks as $delegateTask):?>
														<?php $startdate = date_create($delegateTask['TASKSTARTDATE']);?>

														<tr class="clickable delegate" data-id="<?php echo $delegateTask['TASKID'];?>">
															<?php if($delegateTask['TASKSTATUS'] == "Planning"):?>
																<td class = 'bg-yellow'></td>
															<?php elseif($delegateTask['TASKSTATUS'] == "Ongoing" && $delegateTask['currentDate'] <= $delegateTask['TASKSTARTDATE']):?>
																<td class = 'bg-green'></td>
															<?php else:?>
																<td class = 'bg-red'></td>
															<?php endif;?>
															<td><?php echo $delegateTask['PROJECTTITLE'];?></td>
															<td><?php echo $delegateTask['TASKTITLE'];?></td>
															<td align="center"><?php echo date_format($startdate, 'M d, Y');?></td>
															<!-- <td align="center"><?php echo $delegateTask['launching'];?></td> -->
														</tr>
													<?php endforeach;?>

													<form class='delegateTaskClick' action = 'taskDelegate' method="POST">
														<input type ='hidden' name='dashboard' value='0'>
													</form>

												<?php else: ?>
												<tr>
													<td colspan="4" align="center">No tasks to delegate</td>
												</tr>
												<?php endif;?>
												</tbody>
											</table>
										</div>
										<!-- /.table-responsive -->
									</div>
									<!-- /.box-body -->
								</div>
									<!-- /.box-footer -->
								</div>
								<!-- /.box -->


					</div>

						<!-- APPROVAL TABLE -->
						<!-- Main row -->
						<div class="row">
							<!-- Left col -->
							<div class="col-md-12">
								<div class="box box-danger" style="height:45%; overflow-y: scroll">
									<div class="box-header with-border">
										<h3 class="box-title">Change Requests I Need To Approve (<?php echo count($changeRequests);?>)</h3>
									</div>
									<!-- /.box-header -->
									<div class="box-body">
										<div class="table-responsive">
											<table class="table table-hover no-margin" id="requestApproval">
												<thead>
												<tr>
													<th>Date Requested</th>
													<th class="text-center">Request Type</th>
													<th>Project</th>
													<th>Task</th>
													<th>Requested By</th>
												</tr>
												</thead>
												<tbody>
													<?php if($changeRequests != null):?>
														<?php foreach($changeRequests as $changeRequest):
															$dateRequested = date_create($changeRequest['REQUESTEDDATE']);
															// if($changeRequest['REQUESTTYPE'] == 1)
															// 	$type = "Change Performer";
															// else
															// 	$type = "Change Date/s";
														?>
															<tr class="request clickable" data-project = "<?php echo $changeRequest['PROJECTID']; ?>" data-request = "<?php echo $changeRequest['REQUESTID']; ?>">

																<form class='changeRequestApproval' action = 'projectGantt' method="POST">
																	<input type ='hidden' name='dashboard' value='0'>
																	<input type ='hidden' name='rfc' value='0'>
																</form>

																<td><?php echo date_format($dateRequested, "M d, Y"); ?></td>
																<!-- <td><?php echo $type;?></td> -->
																<td align="center">
																	<?php if($changeRequest['REQUESTTYPE'] == 1):?>
																		<i class="fa fa-user-times"></i>
																	<?php else:?>
																		<i class="fa fa-calendar"></i>
																	<?php endif;?>
																</td>
																<td><?php echo $changeRequest['PROJECTTITLE'];?></td>
																<td><?php echo $changeRequest['TASKTITLE'];?></td>
																<td><?php echo $changeRequest['FIRSTNAME'] . " " .  $changeRequest['LASTNAME'] ;?></td>
															</tr>
													<?php endforeach;?>

												<?php else: ?>
													<tr>
														<td colspan='5' class='text-center'>
															There are no change requests
														</td>
													</tr>

												<?php endif; ?>

												</tbody>
											</table>
										</div>
										<!-- /.table-responsive -->
									</div>
									<!-- /.box-body -->
									<!-- /.box-footer -->
								</div>
								<!-- /.box -->
							</div>
						</div>
						<!-- END APPROVAL TABLE -->

					<?php endif; ?>

				<?php endif;?>




					<!-- Main row -->
					<div class="row">
						<!-- Left col -->
						<div class="col-md-12">
							<div class="box box-danger" style="height:45%; overflow-y: scroll">
								<div class="box-header with-border">
									<h3 class="box-title">Documents I Need To Acknowledge (<?php echo count($toAcknowledgeDocuments);?>)</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table class="table table-hover no-margin" id="requestApproval">
											<thead>
											<tr>
												<th width="30%">Document</th>
												<th width="25%">Project</th>
												<th width="15%">Uploaded By</th>
												<th width="15%">Department</th>
												<th width="15%" class="text-center">Action</th>
											</tr>
											</thead>
											<tbody>

												<form action='acknowledgeDocument' method='POST' class ='acknowledgeDocument'> </form>

												<?php
													if($toAcknowledgeDocuments != NULL){
														foreach($toAcknowledgeDocuments as $row){
															if($row['users_UPLOADEDBY'] != $_SESSION['USERID']){
																if($row['ACKNOWLEDGEDDATE'] == ''){
																	echo "<tr class='clickable'>";
																		echo "<td>" . $row['DOCUMENTNAME'] . "</td>";
																		echo "<td>" . $row['PROJECTTITLE'] . "</td>";
																		echo "<td>" . $row['FIRSTNAME'] . " " . $row['LASTNAME'] . "</td>";
																		echo "<td>" . $row['DEPARTMENTNAME'] . "</td>";
																		echo "<td align='center'>
																		<a href = '" . $row['DOCUMENTLINK'] . "' download><button type='button' class='btn btn-success' data-toggle='tooltip' data-placement='top' title='Download'>
																		<i class='fa fa-download'></i></button></a>
																		<span data-toggle='tooltip' data-placement='top' title='Acknowledge'>
																		<button type='button' class='btn btn-warning document acknowledgeButton' name='documentButton'
																			data-toggle='modal' data-target='#confirmAcknowledge'
																			data-docuID ='" . $row['DOCUMENTID'] . "'
																			data-projectID = '" . $row['projects_PROJECTID'] . "'
																			data-docuName = '" . $row['DOCUMENTNAME'] ."'>
																			<i class='fa fa-check-circle'></i></button></span></td>";
																	echo "</tr>";
																}
															}
														}
													}
													else{
														echo "<tr>";
															echo "<td colspan='5' class='text-center'>";
																echo "There are no documents to acknowledge";
															echo "</td>";
														echo "</tr>";
													}

											?>
											</tbody>
										</table>
									</div>
									<!-- /.table-responsive -->
								</div>
								<!-- /.box-body -->
								<!-- /.box-footer -->
							</div>
							<!-- /.box -->
						</div>
					</div>
					<!-- END DOCUMENTS TABLE -->

				<!-- CONFIRM ACKNOWLEDGEMENT -->
				<div class="modal fade" id="confirmAcknowledge" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Confirm Document Acknowledgement</h2>
							</div>
							<div class="modal-body">
								<h4>Are you sure you want to acknowledge this document?</h4>
								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
									<button id = "doneConfirm" type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i> </button>
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
		$("#dashboard").addClass("active");
		$('.circlechart').circlechart(); // Initialization
		$(document).on("click", ".request", function() {
			var $project = $(this).attr('data-project');
			var $request = $(this).attr('data-request');
			$(".changeRequestApproval").attr("name", "formSubmit");
			$(".changeRequestApproval").append("<input type='hidden' name='project_ID' value= " + $project + ">");
			$(".changeRequestApproval").append("<input type='hidden' name='request_ID' value= " + $request + ">");
			$(".changeRequestApproval").submit();
			});
		$(document).on("click", ".projects", function() {
			var $project = $(this).attr('data-id');
			$(".projectForm").attr("name", "formSubmit");
			$(".projectForm").append("<input type='hidden' name='project_ID' value= " + $project + ">");
			$(".projectForm").submit();
			});
			$(document).on("click", ".acknowledgeButton", function() {
				var $documentID = $(this).attr('data-docuID');
				var $projectID = $(this).attr('data-projectID');
				var $documentName = $(this).attr('data-docuName');
				$("#doneConfirm").attr('data-docuID', $documentID);
				$("#doneConfirm").attr('data-projectID', $projectID);
				$("#doneConfirm").attr('data-docuName', $documentName);
			});
		$(document).on("click", "#doneConfirm", function() {
			var $documentID = $(this).attr('data-docuID');
			var $projectID = $(this).attr('data-projectID');
			var $documentName = $(this).attr('data-docuName');
			$(".acknowledgeDocument").attr("name", "formSubmit");
			$(".acknowledgeDocument").append("<input type='hidden' name='documentID' value= " + $documentID + ">");
			$(".acknowledgeDocument").append("<input type='hidden' name='projectID' value= " + $projectID + ">");
			$(".acknowledgeDocument").append("<input type='hidden' name='fileName' value= " + $documentName + ">");
			$(".acknowledgeDocument").append("<input type='hidden' name='fromWhere' value='dashboard'>");
			$(".acknowledgeDocument").submit();
		});
		$(document).on("click", ".deadline", function(){
			window.location.replace("<?php echo base_url("index.php/controller/taskTodo") ?>");
		});
		$(document).on("click", ".delegate", function(){
			$(".delegateTaskClick").attr("name", "formSubmit");
			$(".delegateTaskClick").submit();
		});
		$(document).ready(function()
		{
			$("#success").click(function(){
				successAlert();
			});
			$("#danger").click(function(){
				dangerAlert();
			});
			$("#warning").click(function(){
				warningAlert();
			});
			$("#info").click(function(){
				infoAlert();
			});
		});
	</script>

	</body>
</html>
