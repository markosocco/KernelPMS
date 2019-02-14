<html>
	<head>
		<title>Kernel - Tasks To Do</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/notificationsStyle.css")?>"> -->
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Tasks To Do
					<small>What do I need to get done?</small>
				</h1>

				<ol class="breadcrumb">
          <?php $dateToday = date('F d, Y | l');?>
          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
        </ol>
			</section>

      <!-- Main content -->
			<section class="content container-fluid">
        <!-- START HERE -->

				<button id = "viewAll" class="btn btn-default pull-right" data-toggle="tooltip" data-placement="left" title="All Tasks"><i class="fa fa-eye"></i></button>
				<button id = "viewFiltered" class="btn btn-default pull-right" data-toggle="tooltip" data-placement="left" title="To Do"><i class="fa fa-eye-slash"></i></button>

				<br><br>

				<div id = "filteredTasks">

					<div class="row">
						<!-- TO DO -->

						<?php if ($projectsToDo != NULL): ?>
						<div class="col-md-10" id="taskToDoTable">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">To Do</h3>
								</div>
								<!-- /.box-header -->
									<div class="box-body">
										<?php foreach($projectsToDo as $project):?>
											<?php
											$startDate = date_create($project['PROJECTSTARTDATE']);
											$endDate = date_create($project['PROJECTENDDATE']);
											?>

											<div class="box">
												<div class="box-header with-border">
													<h3 class="box-title">
														<?php echo $project['PROJECTTITLE'];?>
														(<?php echo date_format($startDate, "F d, Y");?> - <?php echo date_format($endDate, "F d, Y");?>)
													</h3>
												</div>
													<div class="box-body">
														<div class="table-responsive">
															<table class="table table-hover no-margin toDoTable">
																<thead>
																<tr>
																	<th width="1%"></th>
																	<th width='50%'>Task</th>
																	<th width='12%' class="text-center">End Date</th>
																	<th width='12%' class="text-center">Days Delayed</th>
																	<th width='15%' class="text-center">Action</th>
																</tr>
																</thead>
																<tbody id="taskTable-<?php echo $project['PROJECTID'];?>">

																</tbody>
															</table>
														</div>
													</div>
											</div>
										<?php endforeach;?>
									</div>
							</div>
						</div>

					<?php else:?>
						<div class="col-md-10" id="emptyToDoTable">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">To Do</h3>
								</div>
								<div class="box-body" id="emptyToDo">
									<h4 align="center">You have no tasks due in 2 days</h4>
								</div>
							</div>
						</div>
					<?php endif;?>

					<div class="col-md-2">
						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Projects <br><br><b><?php echo count($projectsToDo);?></b></h4>
								</div>
							</div>
						</div>

						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center" id="totalToDo"> Total <br><br><b>0</b></h4>
								</div>
							</div>
						</div>

						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Delayed <br><br><b><span style='color:red' id= "totalDelayedToDo">0</b></span></h4>
								</div>
							</div>
						</div>
					</div>
					</div> <!-- CLOSING ROW -->

				</div>

				<div id = "allTasks">
					<!-- ALL TASKS -->
					<div class = 'row'>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center" id="projects"> Projects <br><br><b><?php echo count($projects);?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center" id="total"> Tasks <br><br><b><?php echo count($tasks);?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Delayed <br><br><b><span style='color:red' id= "totalDelayed">0</span></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center" id="totalOngoing"> Ongoing <br><br><b>0</b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center" id="totalPlanned"> Planned <br><br><b>0</b></h4>
									</div>
								</div>
							</div>
						</div>
					</div>

					<?php if ($projects != NULL): ?>
					<div class = "row">
						<div class="col-md-12">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">All Tasks</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<?php foreach($projects as $project):?>
										<?php
										$startDate = date_create($project['PROJECTSTARTDATE']);
										$endDate = date_create($project['PROJECTENDDATE']);
										?>
									<div class="box">
										<div class="box-header with-border">
											<h3 class="box-title">
												<?php echo $project['PROJECTTITLE'];?>
												(<?php echo date_format($startDate, "F d, Y");?> - <?php echo date_format($endDate, "F d, Y");?>)
											</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body">
											<div class="table-responsive">
												<table class="table table-hover no-margin" id = "allTaskTable">
													<thead>
													<tr>
														<th width="1%"></th>
														<th width="50%">Task</th>
														<th width="12%" class="text-center">Start Date</th>
														<th width="12%" class="text-center">End Date</th>
														<th width="12%" class="text-center">Days Delayed</th>
														<th width="15%" class="text-center">Action</th>
													</tr>
													</thead>
													<tbody id="taskAll-<?php echo $project['PROJECTID'];?>">
													</tbody>
												</table>
											</div>
										</div>
									</div>
								<?php endforeach;?>
								</div>
							</div>
						</div>
					</div>
				<?php else:?>
					<div class = 'row'>
						<div class="col-md-12">
							<div class="box box-danger">
								<div class="box-header">
									<h3 class="box-title">All Tasks</h3>
								</div>
								<div class="box-body">
									<h4 align="center">You have no tasks</h4>
								</div>
							</div>
						</div>
					</div>

				<?php endif;?>
				</div>

				<form id='viewProject' action = 'projectGantt' method="POST">
					<input type ='hidden' name='delegateTask' value='0'>
				</form>

				<!-- DONE MODAL -->
				<div class="modal fade" id="modal-done" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title" id = "doneTitle">Task Finished</h2>
								<h4 id="doneDates">Start Date - End Date (Days)</h4>
							</div>
							<div class="modal-body">
								<div id="doneDiv">
									<h3 id ="delayed" style="color:red; margin-top:0">This task is delayed.</h3>
									<h4 id ="early" style="margin-top:0">Are you sure you have completed this task?</h4>
									<form id = "doneForm" action="doneTask" method="POST" style="margin-bottom:0;">
										<div class="form-group">
											<textarea id = "remarks" name = "remarks" class="form-control" placeholder="Enter remarks"></textarea>
										</div>
										<div class="modal-footer">
											<button id = "closeConfirmBtn" type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
											<button id = "doneConfirmBtn" type="button" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
										</div>
								</div>
							<!-- CONFIRM COMPLETE -->
							<div id="doneConfirmDiv">
								<div class="modal-body">
									<h4>Are you sure you have completed this task?</h4>
								</div>
								<div class="modal-footer">
									<button id="backConfirmDone" type="button" class="btn btn-default pull-left" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
									<button id = "confirmDone" type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
								</div>
							</div>
						</form>
					</div>

						</div>
					</div>
				</div>
				<!-- END DONE MODAL -->

				<!-- RFC MODAL -->
				<div class="modal fade" id="modal-request" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title" id = "rfcTitle">Request for Change</h2>
								<h4 class="taskDates" id="rfcDates">Start Date - End Date (Days)</h4>
							</div>
							<div class="modal-body">
								<div id = 'request'>
								<form id = "requestForm" action = "submitRFC" method = "POST" style="margin-bottom:0;">

									<h5><b>Request Type: </b></h5>
									<div class="btn-group">
										<button type="button" id = "changePerfBtn" value = '1' class="btn btn-default requestType">Change Performer</button>
										<button type="button" id = "changeDateBtn" value = '2' class="btn btn-default requestType">Change End Date</button>
									</div>
									<br><br>

									<input id="rfcType" type='hidden' name='rfcType' value= "">

							<div id="rfcForm">
									<!-- DISPLAY IF CHANGE TASK DATE OPTION -->
									<div id ="newDateDiv">
									<div class="form-group">
										<label class="end">New Target End Date</label>

										<div class="input-group date end">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control pull-right" id="endDate" name ="endDate" >
										</div>
									</div>
								</div>

									<!-- DISPLAY ON BOTH OPTIONS -->
								<div class="form-group">
									<label>Reason</label>
									<textarea id="rfcReason" class="form-control" name = "reason" placeholder="Reason"></textarea>
								</div>
							</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-default pull-left" id="rfcClose" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
								<button type="button" class="btn btn-success" id="rfcConfirm" data-date="" data-toggle="tooltip" data-placement="left" title="Submit"><i class="fa fa-check"></i></button>
							</div>

					</div>

						<!-- CONFIRM RFC -->
						<div id="submitConfirm">
							<div class="modal-body">
								<h4>Are you sure you want to submit this request?</h4>
							</div>
							<div class="modal-footer">
								<button id="backConfirm" type="button" class="btn btn-default pull-left" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
								<button id="rfcSubmit" type="submit" class="btn btn-success" data-id="" data-end="" data-projEnd="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
							</div>
						</div>
					</form>

						</div>
						</div>
					</div>
				</div>
				<!-- END RFC MODAL -->

				<!-- UPDATE MODAL -->
				<div class="modal fade" id="modal-update" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title" id = "updateTitle">Task Updates</h2>
								<h4 id="updateDates">Start Date - End Date (Days)</h4>
							</div>
							<div class="modal-body">
								<div id="updateDiv">
									<h4 style="margin-top:0">What happened to this task?</h4>
									<form id = "updateForm" action="updateTask" method="POST" style="margin-bottom:0;">
										<input type='hidden' name='page' value= "taskToDo">
										<div class="form-group">
											<textarea id = "remarksUpdate" name = "remarksUpdate" class="form-control" placeholder="Enter update"></textarea>
										</div>
										<div class="modal-footer">
											<button id = "closeConfirmUpdateBtn" type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
											<button id = "updateConfirmBtn" type="button" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
										</div>
								</div>
							<!-- CONFIRM UPDATE -->
							<div id="updateConfirmDiv">
								<div class="modal-body">
									<h4>Are you sure you want to submit task update?</h4>
								</div>
								<div class="modal-footer">
									<button id="backConfirmUpdate" type="button" class="btn btn-default pull-left" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
									<button id = "confirmUpdate" type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
								</div>
							</div>
						</form>
					</div>

						</div>
					</div>
				</div>
				<!-- END UPDATE MODAL -->

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
									<button type="button" id = "tabDependency" class="btn btn-default tabDetails">Dependencies</button>
									<button type="button" id = "tabUpdates" class="btn btn-default tabDetails">Updates</button>
									<button type="button" id = "tabRACI" class="btn btn-default tabDetails">RACI</button>
									<button type="button" id = "tabRFC" class="btn btn-default tabDetails">RFC</button>
									<button type="button" id = "tabDelay" class="btn btn-default tabDetails">Projection</button>
								</div>
								<br><br>

								<div id="divRACI" class="divDetails">
									<table class="table no-margin table-hover">
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

									<table class="table no-margin table-hover">
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

								<div id="divUpdates" class="divDetails">
									<table class="table table-hover no-margin">
										<thead>
											<th colspan = '3'>Task Updates</th>
											<tr class='text-center'><td id="taskUpdatesTitle" colspan='3'></td></tr>
											<tr id="updateHeader">
												<th width="20%" class='text-center'>Date</th>
												<th width="60%">Update</th>
												<th width="20%">Updated By</th>
											</tr>
										</thead>
										<tbody id="updatesTable">
										</tbody>
									</table>
								</div>

								<div id="divRFC" class="divDetails">
									<table class="table no-margin table-hover">
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
									<table class="table no-margin table-hover" id='projectDelayTable'>
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

									<table class="table no-margin table-hover">
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
									<table class="table no-margin table-hover">
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

									<table class="table no-margin table-hover">
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

			</section>
		</div>
		  <?php require("footer.php"); ?>
		</div> <!--.wrapper closing div-->
		<script>
      $("#tasks").addClass("active");
      $("#taskTodo").addClass("active");
			$("#allTasks").hide();

			$.ajax({
				type:"POST",
				url: "<?php echo base_url("index.php/controller/loadTasks"); ?>",
				dataType: 'json',
				success:function(data)
				{
					var totalToDo=0;
					var totalDelayedToDo=0;
					var total=0;
					var totalDelayed=0;
					var totalOngoing=0;
					var totalPlanned=0;

					if(data['tasks'].length > 0)
					{
						$('#taskTable').html("");
						for(i=0; i<data['tasks'].length; i++)
						{
							if(data['tasks'][i].TASKADJUSTEDSTARTDATE == null) // check if start date has been previously adjusted
							{
								var taskStart = moment(data['tasks'][i].TASKSTARTDATE).format('MMM DD, YYYY');
								var startDate = data['tasks'][i].TASKSTARTDATE;
							}
							else
							{
								var taskStart = moment(data['tasks'][i].TASKADJUSTEDSTARTDATE).format('MMM DD, YYYY');
								var startDate = data['tasks'][i].TASKADJUSTEDSTARTDATE;
							}

							if(data['tasks'][i].TASKADJUSTEDENDDATE == null) // check if start date has been previously adjusted
							{
								var taskEnd = moment(data['tasks'][i].TASKENDDATE).format('MMM DD, YYYY');
								var endDate = data['tasks'][i].TASKENDDATE;
							}
							else
							{
								var taskEnd = moment(data['tasks'][i].TASKADJUSTEDENDDATE).format('MMM DD, YYYY');
								var endDate = data['tasks'][i].TASKADJUSTEDENDDATE;
							}

							if(data['tasks'][i].TASKADJUSTEDSTARTDATE != null && data['tasks'][i].TASKADJUSTEDENDDATE != null)
								var taskDuration = parseInt(data['tasks'][i].adjustedTaskDuration2);
							if(data['tasks'][i].TASKSTARTDATE != null && data['tasks'][i].TASKADJUSTEDENDDATE != null)
								var taskDuration = parseInt(data['tasks'][i].adjustedTaskDuration1);
							else
								var taskDuration = parseInt(data['tasks'][i].initialTaskDuration);

							var delayDays = data['tasks'][i].delay - taskDuration;

							if(delayDays <= 0)
								delayDays = 0;

							if(data['tasks'][i].currentDate <= endDate)
								var status = "<td class='bg-green'></td>";
							if(data['tasks'][i].TASKSTATUS == 'Planning')
							{
								var status = "<td class='bg-yellow'></td>";
								var totalPlanned = totalPlanned+1;
							}
							if(data['tasks'][i].currentDate > endDate)
							{
								var status = "<td class='bg-red'></td>";
								if(data['tasks'][i].threshold >= endDate)
									var totalDelayedToDo = totalDelayedToDo+1;
								var totalDelayed = totalDelayed+1;
							}

							var taskID = data['tasks'][i].TASKID;

							if(data['tasks'][i].threshold >= endDate && data['tasks'][i].TASKSTATUS == 'Ongoing')
							{
								var totalToDo = totalToDo+1;

								$('#taskTable-' + data['tasks'][i].PROJECTID).append(
														 "<tr id='" + taskID + "'>" +
														 status + "<td class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails'" +
														 "data-id='"+ taskID +"' data-title='" + data['tasks'][i].TASKTITLE + "'"+
														 " data-start='" + taskStart + "' data-end='"+ taskEnd +"'>" +
														 data['tasks'][i].TASKTITLE+"</td>"+
														 "<td class = 'clickable taskDetails text-center' data-toggle='modal' data-target='#taskDetails'" +
														 "data-id='"+ taskID +"' data-title='" + data['tasks'][i].TASKTITLE + "'"+
														 " data-start='" + taskStart + "' data-end='"+ taskEnd +"'>" +
														 taskEnd+"</td>" +
														 "<td align = 'center' class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails'" +
														 "data-id='"+ taskID +"' data-title='" + data['tasks'][i].TASKTITLE + "'"+
														 " data-start='" + taskStart + "' data-end='"+ taskEnd +"'>" +
														 delayDays+"</td>" +
														 "<td align='center' class = 'action-" + taskID +"'></td>");
							}

							var total = total+1;

							$('#taskAll-' + data['tasks'][i].PROJECTID).append(
													 "<tr id='" + taskID + "'>" +
													 status + "<td class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails'" +
													 "data-id='"+ taskID +"' data-title='" + data['tasks'][i].TASKTITLE + "'"+
													 " data-start='" + taskStart + "' data-end='"+ taskEnd +"'>" +
													 data['tasks'][i].TASKTITLE+"</td>"+
													 "<td class = 'clickable taskDetails text-center' data-toggle='modal' data-target='#taskDetails'" +
													 "data-id='"+ taskID +"' data-title='" + data['tasks'][i].TASKTITLE + "'"+
													 " data-start='" + taskStart + "' data-end='"+ taskEnd +"'>" +
													 taskStart+"</td>" +
													 "<td class = 'clickable taskDetails text-center' data-toggle='modal' data-target='#taskDetails'" +
													 "data-id='"+ taskID +"' data-title='" + data['tasks'][i].TASKTITLE + "'"+
													 " data-start='" + taskStart + "' data-end='"+ taskEnd +"'>" +
													 taskEnd+"</td>" +
													 "<td align = 'center' class = 'clickable taskDetails text-center' data-toggle='modal' data-target='#taskDetails'" +
													 "data-id='"+ taskID +"' data-title='" + data['tasks'][i].TASKTITLE + "'"+
													 " data-start='" + taskStart + "' data-end='"+ taskEnd +"'>" +
													 delayDays+"</td>" +
													 "<td align='center' class = 'action-" + taskID +"'></td>");


						 if(data['tasks'][i].threshold >= endDate || data['tasks'][i].isProjectOwner == '1') //if delayed or project owner
							{
								$(".action-" + taskID).append(
								 '<button disabled type="button"' +
 								 'class="btn btn-warning btn-sm rfcBtn" data-id="' + taskID +
 								 '" data-title="' + data['tasks'][i].TASKTITLE +
 								 '" data-start="'+ taskStart +
 								 '" data-end="'+ taskEnd +'" data-projEnd="'+ data['tasks'][i].PROJECTENDDATE +'"data-toggle="tooltip" data-placement="top" title="Request for Change">' +
								 '<i class="fa fa-flag"></i></button>');
							}
							else
							{
								$(".action-" + taskID).append(
 								 '<span data-toggle="modal" data-target="#modal-request"><button type="button"' +
 								 'class="btn btn-warning btn-sm rfcBtn" data-id="' + taskID +
 								 '" data-title="' + data['tasks'][i].TASKTITLE +
 								 '" data-start="'+ taskStart +
 								 '" data-end="'+ taskEnd +'" data-projEnd="'+ data['tasks'][i].PROJECTENDDATE +'" data-toggle="tooltip" data-placement="top" title="Request for Change">' +
 								 '<i class="fa fa-flag"></i></button></span>');
							}

							$(".action-" + taskID).append(
							 '<span data-toggle="modal" data-target="#modal-update"><button type="button"' +
							 'class="btn btn-primary btn-sm updateBtn" data-id="' + taskID +
							 '" data-title="' + data['tasks'][i].TASKTITLE +
							 '" data-start="'+ taskStart +
							 '" data-end="'+ taskEnd +'" data-projEnd="'+ data['tasks'][i].PROJECTENDDATE +'" data-toggle="tooltip" data-placement="top" title="Update">' +
							 '<i class="fa fa-commenting"></i></button></span>');

							if(data['tasks'][i].TASKSTATUS == 'Ongoing') //if task is ongoing
							{
								var totalOngoing = totalOngoing+1;

										 // AJAX TO CHECK IF DEPENDENCIES ARE COMPLETE
		 								$.ajax({
		 								 type:"POST",
		 								 url: "<?php echo base_url("index.php/controller/getDependenciesByTaskID"); ?>",
		 								 data: {task_ID: taskID},
		 								 dataType: 'json',
		 								 success:function(dependencyData)
		 								 {
		 									 var taskID = dependencyData['taskID'].TASKID;
		 									 var taskTitle = dependencyData['taskID'].TASKTITLE;
		 									 var startDate = moment(dependencyData['taskID'].TASKSTARTDATE).format('MMM DD, YYYY');
		 									 var endDate = moment(dependencyData['taskID'].TASKENDDATE).format('MMM DD, YYYY');
		 									 var isDelayed = dependencyData['taskID'].currentDate > dependencyData['taskID'].TASKENDDATE;

		 									 if(dependencyData['dependencies'].length > 0) //if task has pre-requisite tasks
		 									 {
		 										 var isComplete = 1;
		 										 for (var d = 0; d < dependencyData['dependencies'].length; d++)
		 										 {
		 											 if(dependencyData['dependencies'][d].TASKSTATUS != 'Complete') // if there is a pre-requisite task that is ongoing
		 											 {
		 												 isComplete = 0;
		 											 }
		 										 }
		 										 if(isComplete == 1) // if all pre-requisite tasks are complete, task can be marked done
		 										 {
		 											 $(".action-" + dependencyData['taskID'].TASKID).append(
		 													'<span data-toggle="modal" data-target="#modal-done"><button type="button"' +
		 													'class="btn btn-success btn-sm doneBtn" data-id="' + taskID +
		 													'" data-title="' + taskTitle + '"' +
		 													'data-delay="' + isDelayed + '" data-start="'+ startDate +
		 													'" data-end="'+ endDate +'" data-toggle="tooltip" data-placement="top" title="Done">' +
		 													'<i class="fa fa-check"></i></button></span>');
		 										 }
												 else
												 {
													 $(".action-" + dependencyData['taskID'].TASKID).append(
														 '<button disabled type="button"' +
														 'class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Pre-req/s have not been accomplished">' +
														 '<i class="fa fa-check"></i></button>');
												 }
		 									 }
		 									 else // if task has no prerequisites
		 									 {
		 										 $('.action-' + dependencyData['taskID'].TASKID).append(
		 												'<span data-toggle="modal" data-target="#modal-done"><button type="button"' +
		 												'class="btn btn-success btn-sm doneBtn" data-id="' + taskID +
		 												'" data-title="' + taskTitle + '"' +
		 												'data-delay="' + isDelayed + '" data-start="'+ startDate +
		 												'" data-end="'+ endDate +'" data-toggle="tooltip" data-placement="top" title="Done">' +
		 												'<i class="fa fa-check"></i></button></span>');
		 									 }
		 								 },
		 								 error:function()
		 								 {
		 									 alert("There was a problem in checking the task dependencies");
		 								 }
		 							 }); // end of dependencies ajax
							}
							else
							{
								 $(".action-" + taskID).append(
									 '<button disabled type="button"' +
									 'class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Task is not yet ongoing">' +
									 '<i class="fa fa-check"></i></button>');
							}
						}
						$('#totalToDo').html("Tasks<br><br><b>" + totalToDo + "</b>");
						$('#totalDelayedToDo').html(totalDelayedToDo);
						$('#totalDelayed').html(totalDelayed);
						$('#totalOngoing').html("Ongoing<br><br><b>" + totalOngoing + "</b>");
						$('#totalPlanned').html("Planned<br><br><b>" + totalPlanned + "</b>");
					}
				},
				error:function()
				{
					alert("There was a problem in retrieving the tasks");
				},
				complete:function()
				{
					// $('.allTasks').DataTable({
					// 	'paging'      : false,
					// 	'lengthChange': false,
					// 	'searching'   : true,
					// 	'ordering'    : true,
					// 	'info'        : false,
					// 	'autoWidth'   : false,
					// 	'order'				: [[ 5, "desc" ]],
					// 	'columnDefs'	: [
					// 	{
					// 		'targets'		: [ 0, 6 ],
					// 		'orderable'	: false
					// 	} ]
					// });
					// if(totalToDo>=0)
					// {
					// 	$('.toDoTable').DataTable({
					// 		'paging'      : false,
					// 		'lengthChange': false,
					// 		'searching'   : true,
					// 		'ordering'    : true,
					// 		'info'        : false,
					// 		'autoWidth'   : false,
					// 		'order'				: [[ 4, "desc" ]],
					// 		'columnDefs'	: [
					// 		{
					// 			'targets'		: [ 0, 5 ],
					// 			'orderable'	: false
					// 		} ]
					// 	});
					// }
				}
			});

			$("#viewFiltered").toggle();

			$(document).on("click", "#viewAll", function()
			{
				$("#allTasks").toggle();
				$("#filteredTasks").toggle();

				$("#viewAll").toggle();
				$("#viewFiltered").toggle();
			});

			$(document).on("click", "#viewFiltered", function()
			{
				$("#allTasks").toggle();
				$("#filteredTasks").toggle();

				$("#viewAll").toggle();
				$("#viewFiltered").toggle();
			});

			$(document).on("click", ".viewProject", function() {
				var $projectID = $(this).attr('data-id');
				$("#viewProject").attr("name", "formSubmit");
				$("#viewProject").append("<input type='hidden' name='project_ID' value= " + $projectID + ">");
				$("#viewProject").submit();
			});

			// RFC SCRIPT
			$("#rfcForm").hide();

			$("body").on('click','.rfcBtn',function()
			 {
				 var $id = $(this).attr('data-id');
				 var $date = $(this).attr('data-date');
				 var $title = $(this).attr('data-title');
				 var $start = new Date($(this).attr('data-start'));
				 var $end = new Date($(this).attr('data-end'));
				 var $projEnd = new Date($(this).attr('data-projEnd'));
				 var $diff = (($end - $start)/ 1000 / 60 / 60 / 24)+1;
				 $("#rfcSubmit").attr("data-id", $id); //pass data id to confirm button
				 $("#rfcSubmit").attr("data-end", moment($end).format('YYYY-M-D')); //pass data id to confirm button
				 $("#rfcSubmit").attr("data-projEnd", moment($projEnd).format('YYYY-M-D')); //pass data id to confirm button
				 $("#rfcSubmit").attr("data-date", $date); //pass data date boolean to confirm button
				 $("#rfcTitle").html($title);
				 $("#rfcDates").html(moment($start).format('MMMM DD, YYYY') + " - " + moment($end).format('MMMM DD, YYYY') + " ("+ $diff);
				 if($diff>1)
					$("#rfcDates").append(" days)");
				 else
					$("#rfcDates").append(" day)");
			 });

			 $("#submitConfirm").hide();

			 $("body").on('click','#rfcClose',function(){
				 $("#rfcType").val("0");
				 $("#rfcForm").hide();
				 $("#rfcReason").val("");
				 $("#endDate").val("")
			 });

			 $("#rfcSubmit").click(function()
			 {
				 var $id = $(this).attr('data-id');
				 console.log($id);
				 $("#requestForm").attr("name", "formSubmit");
				 $("#requestForm").append("<input type='hidden' name='task_ID' value= " + $id + ">");
			 });

			 $("body").on('click','#rfcConfirm',function(){
				 $("#request").hide();
				 $("#submitConfirm").show();
			 });

			 $("body").on('click','#backConfirm',function(){
				 $("#request").show();
				 $("#submitConfirm").hide();
			 });

			 $(".btn-group > .btn").click(function(){
 			    $(".btn-group > .btn").removeClass("active");
 			    $(this).addClass("active");
 					$("#rfcType").attr("value", $(this).val());
 			});

			 $("body").on('click','#changePerfBtn',function(){
				 $("#rfcForm").show();
				 $("#newDateDiv").hide();
				 $("#rfcReason").show();
				 $("#startDate").attr("required", false);
				 $("#endDate").attr("required", false);
			 });

			 $("body").on('click','#changeDateBtn',function(){
				 $("#rfcForm").show();
				 $("#newDateDiv").show();
				 $("#rfcReason").show();

				 if($("#rfcSubmit").attr('data-date') == 'true') // IF TASK IS ONGOING
				 {
					 $(".start").hide();
				 }
				 else
				 {
					 $(".start").show();
					 $(".end").show();
				 }

				 //Date picker
				 $('#endDate').datepicker({
						format: 'yyyy-mm-dd',
						startDate: $('#rfcSubmit').attr('data-end'),
						endDate: $('#rfcSubmit').attr('data-projEnd'),
						autoclose: true,
						orientation: 'auto'
					});
			 });

			 // END RFC SCRIPT

			 // DONE SCRIPT

			$("body").on('click','.doneBtn',function(){
			 var $id = $(this).attr('data-id');
			 var $title = $(this).attr('data-title');
			 var $start = new Date($(this).attr('data-start'));
			 var $end = new Date($(this).attr('data-end'));
			 var $diff = (($end - $start)/ 1000 / 60 / 60 / 24)+1;
			 $("#doneTitle").html($title);
			 $("#doneDates").html(moment($start).format('MMMM DD, YYYY') + " - " + moment($end).format('MMMM DD, YYYY') + " ("+ $diff);
			 if($diff > 1)
				 $("#doneDates").append(" days)");
			 else
				 $("#doneDates").append(" day)");
			 $("#doneConfirmBtn").attr("data-id", $id); //pass data id to confirm button
			 var isDelayed = $(this).attr('data-delay'); // true = delayed
			 if(isDelayed == 'false')
			 {
				 $("#delayed").hide();
				 $("#early").show();
				 $("#remarks").attr("required", false);
				 $("#remarks").attr("placeholder", "Remarks (optional)");
			 }
			 else
			 {
				 $("#early").hide();
				 $("#delayed").show();
				 $("#remarks").attr("placeholder", "Reason (required)");
			 }
		 });

		 $("body").on('click','#confirmDone',function(){
			 var $id = $("#doneConfirmBtn").attr('data-id');
			 $("#doneForm").attr("name", "formSubmit");
			 $("#doneForm").append("<input type='hidden' name='task_ID' value= " + $id + ">");
		 });

		 $("#doneConfirmDiv").hide();

		 $("body").on('click','#closeConfirmBtn',function(){
			 $("#remarks").val("");
		 });

		 $("body").on('click','#doneConfirmBtn',function(){
			 $("#doneDiv").hide();
			 $("#doneConfirmDiv").show();
		 });

		 $("body").on('click','#backConfirmDone',function(){
			 $("#doneDiv").show();
			 $("#doneConfirmDiv").hide();
		 });

		 // END DONE SCRIPT

		 // START TASK DETAILS
		 $(document).on("click", ".taskDetails", function(){
			 $(".divDetails").hide();
			 $(".tabDetails").removeClass('active');
			 $("#tabDependency").addClass("active");
			 $("#divDependency").show();

			 var $taskID = $(this).attr('data-id');

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
							 var currentIndex = rh;
							 var withHistory = true;
							 $("#raciHistoryTable").append(
								 "<tr>" +
									 "<td id = 'changedR" + currentIndex + "'></td>" +
									 "<td id = 'changedA" + currentIndex + "'></td>" +
									 "<td id = 'changedC" + currentIndex + "'></td>" +
									 "<td id = 'changedI" + currentIndex + "'></td>" +
								 "</tr>");

								 if(data['raciHistory'][currentIndex].ROLE == 1 && data['raciHistory'][currentIndex].STATUS == 'Changed')
								 {
									 $("#changedR" + currentIndex).append(data['raciHistory'][currentIndex].FIRSTNAME + " " + data['raciHistory'][currentIndex].LASTNAME);
								 }
							 for(ro=currentIndex; ro < data['raciHistory'].length; ro++)
							 {
								 if(data['raciHistory'][ro].ROLE == 2 && data['raciHistory'][ro].STATUS == 'Changed')
								 {
									 $("#changedA" + currentIndex).append(data['raciHistory'][ro].FIRSTNAME + " " + data['raciHistory'][ro].LASTNAME);
								 }
								 if(data['raciHistory'][ro].ROLE == 3 && data['raciHistory'][ro].STATUS == 'Changed')
								 {
									 $("#changedC" + currentIndex).append(data['raciHistory'][ro].FIRSTNAME + " " + data['raciHistory'][ro].LASTNAME);
								 }
								 if(data['raciHistory'][ro].ROLE == 4 && data['raciHistory'][ro].STATUS == 'Changed')
								 {
									 $("#changedI" + currentIndex).append(data['raciHistory'][ro].FIRSTNAME + " " + data['raciHistory'][ro].LASTNAME);
								 }
								 if(data['raciHistory'][ro+1].ROLE == 1 && data['raciHistory'][ro].STATUS == 'Changed')
								 {
									 break;
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

				// TASK UPDATES
 				$.ajax({
 				 type:"POST",
 				 url: "<?php echo base_url("index.php/controller/getTaskUpdates"); ?>",
 				 data: {task_ID: $taskID},
 				 dataType: 'json',
 				 success:function(taskUpdates)
 				 {
					 if(taskUpdates.length > 0)
					 {
						 $('#updatesTable').html("");
						 $("#taskUpdatesTitle").hide();
						 $('#updateHeader').show();
						 for(i=0; i< taskUpdates.length; i++)
						 {
							 $('#updatesTable').append(
														"<tr>" +
														"<td align='center'>" + moment(taskUpdates[i].COMMENTDATE).format('MMM DD, YYYY') +"</td>"+
														"<td>" + taskUpdates[i].COMMENT +"</td>"+
														"<td>" + taskUpdates[i].FIRSTNAME + " " + taskUpdates[i].LASTNAME + "</td>" + "</tr>");
						 }
					 }
					 else
					 {
						 $('#taskUpdatesTitle').html("There are no task updates");
						 $("#updateHeader").hide();
						 $('#updatesTable').html("");
						 $("#taskUpdatesTitle").show();
					 }
				 },
 				 error:function()
 				 {
 					 alert("There was a problem in retrieving the task updates");
 				 }
 				 });
		 });

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

		 $(document).on("click", "#tabUpdates", function(){
			 $(".divDetails").hide();
			 $(".tabDetails").removeClass('active');
			 $(this).addClass('active')
			 $("#divUpdates").show();
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
		 // END TASK DETAILS

		 // TASK UPDATE
		 $("body").on('click','.updateBtn',function(){
			var $id = $(this).attr('data-id');
			var $title = $(this).attr('data-title');
			var $start = new Date($(this).attr('data-start'));
			var $end = new Date($(this).attr('data-end'));
			var $diff = (($end - $start)/ 1000 / 60 / 60 / 24)+1;
			$("#updateTitle").html($title);
			$("#updateDates").html(moment($start).format('MMMM DD, YYYY') + " - " + moment($end).format('MMMM DD, YYYY') + " ("+ $diff);
			if($diff > 1)
				$("#updateDates").append(" days)");
			else
				$("#updateDates").append(" day)");
			$("#updateConfirmBtn").attr("data-id", $id); //pass data id to confirm button
		});

		$("body").on('click','#confirmUpdate',function(){
			var $id = $("#updateConfirmBtn").attr('data-id');
			$("#updateForm").attr("name", "formSubmit");
			$("#updateForm").append("<input type='hidden' name='task_ID' value= " + $id + ">");
		});

		$("#updateConfirmDiv").hide();

		$("body").on('click','#closeConfirmUpdateBtn',function(){
			$("#remarksUpdate").val("");
		});

		$("body").on('click','#updateConfirmBtn',function(){
			$("#updateDiv").hide();
			$("#updateConfirmDiv").show();
		});

		$("body").on('click','#backConfirmUpdate',function(){
			$("#updateDiv").show();
			$("#updateConfirmDiv").hide();
		});

		</script>
	</body>
</html>
