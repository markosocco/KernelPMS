<html>
<head>
	<title>Kernel - <?php echo  $projectProfile['PROJECTTITLE'];?></title>
	<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/projectGanttStyle.css")?>">
</head>
<body class="hold-transition skin-red sidebar-mini sidebar-collapse fixed">
	<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div style="margin-bottom:10px">

					<?php if(isset($_SESSION['dashboard'])): ?>
							<a href="<?php echo base_url("index.php/controller/dashboard"); ?>" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to Dashboard"><i class="fa fa-arrow-left"></i></a>
					<?php elseif(isset($_SESSION['archives'])): ?>
							<a href="<?php echo base_url("index.php/controller/archives"); ?>" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to Archives"><i class="fa fa-arrow-left"></i></a>
					<?php elseif(isset($_SESSION['changeRequest']) || isset($_SESSION['userRequest'])): ?>
							<a href="<?php echo base_url("index.php/controller/rfc"); ?>" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to Change Requests"><i class="fa fa-arrow-left"></i></a>
					<?php elseif(isset($_SESSION['rfc'])): ?>
							<a href="<?php echo base_url("index.php/controller/rfc"); ?>" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to Change Requests"><i class="fa fa-arrow-left"></i></a>
					<?php elseif(isset($_SESSION['myTasks'])): ?>
							<a href="<?php echo base_url("index.php/controller/myTasks"); ?>" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to My Tasks"><i class="fa fa-arrow-left"></i></a>
					<?php elseif(isset($_SESSION['templates'])): ?>
							<a href="<?php echo base_url("index.php/controller/templates"); ?>" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to Templates"><i class="fa fa-arrow-left"></i></a>
					<?php elseif(isset($_SESSION['monitorTasks'])): ?>
							<a href="<?php echo base_url("index.php/controller/taskMonitor"); ?>" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to Monitor Tasks"><i class="fa fa-arrow-left"></i></a>
					<?php else: ?>
							<a href="<?php echo base_url("index.php/controller/myProjects"); ?>" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to My Projects"><i class="fa fa-arrow-left"></i></a>
					<?php endif; ?>

					<?php if(isset($_SESSION['changeRequest']) || isset($_SESSION['userRequest']) || isset($_SESSION['rfc'])): ?>
						<script>$("#rfc").addClass("active");</script>
					<?php else:?>
						<script>$("#myProjects").addClass("active");</script>
					<?php endif;?>

				</div>

			<?php if(isset($_SESSION['rfc']) || isset($_SESSION['userRequest'])): ?>

				<!-- RFC GANTT START -->
				<div id="rfcGantt">
					<div class="row">
							<div class="col-md-6">
								<div class="box box-danger">
									<div class="box-header">
										<h3 class="box-title">Change Request Details</h3>
									</div>
									<!-- /.box-header -->
									<div class="box-body">
										<form id = "approvalForm" action="approveDenyRFC" method="POST" style="margin-bottom:0;"
										data-request="<?php echo $changeRequest['REQUESTID'];?>"
										data-project="<?php echo $changeRequest['PROJECTID'];?>"
										data-task="<?php echo $changeRequest['TASKID'];?>"
										data-type="<?php echo $changeRequest['REQUESTTYPE'];?>"
										data-requestor="<?php echo $changeRequest['users_REQUESTEDBY'];?>">

											<?php $dateRequested = date_create($changeRequest['REQUESTEDDATE']);

											if($changeRequest['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
											{
												$startDate = date_create($changeRequest['TASKSTARTDATE']);
												$start = $changeRequest['TASKSTARTDATE'];
											}
											else
											{
												$startDate = date_create($changeRequest['TASKADJUSTEDSTARTDATE']);
												$start = $changeRequest['TASKADJUSTEDSTARTDATE'];
											}

											if($changeRequest['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
											{
												$endDate = date_create($changeRequest['TASKENDDATE']);
												$end = $changeRequest['TASKENDDATE'];
											}
											else
											{
												$endDate = date_create($changeRequest['TASKADJUSTEDENDDATE']);
												$end = $changeRequest['TASKADJUSTEDENDDATE'];
											}

											$newStartDate = date_create($changeRequest['NEWSTARTDATE']);
											$newEndDate = date_create($changeRequest['NEWENDDATE']);
											?>
											<?php if($changeRequest['REQUESTTYPE'] == 1)
												$type = "Change Performer";
											else
												$type = "Change Date/s";?>

											<table class="table">
												<tr>
													<td>
														<label>Date Requested</label>
														<p><?php echo date_format($dateRequested, "F d, Y");?></p>
													</td>
													<td>
														<label>Request Type</label>
														<p>
															<?php if($changeRequest['REQUESTTYPE'] == 1):?>
																<i class="fa fa-user-times"></i>
															<?php else:?>
																<i class="fa fa-calendar"></i>
															<?php endif;?>
															<?php echo $type;?>
														</p>
													</td>
													<td colspan='2'>
														<label>Requester</label>
														<p><?php echo $changeRequest['FIRSTNAME'] . " " .  $changeRequest['LASTNAME'] ;?></p>
													</td>
												</tr>

												<tr>
													<td>
														<label>Project</label>
														<p><?php echo $changeRequest['PROJECTTITLE'];?></p>
													</td>
													<td class="clickable task" data-toggle='modal' data-target='#taskDetails'
													data-id="<?php echo $changeRequest['tasks_REQUESTEDTASK'];?>"
													data-rfc='<?php echo $changeRequest['REQUESTID'];?>'
													data-rfcType = '<?php echo $changeRequest['REQUESTTYPE'];?>'>
														<label>Task Name</label>
														<p><?php echo $changeRequest['TASKTITLE'];?></p>
													</td>
													<td>
														<label>Start Date</label>
														<p><?php echo date_format($startDate, "F d, Y");?></p>
													</td>
													<td>
														<label>End Date</label>
														<p><?php echo date_format($endDate, "F d, Y");?></p>
													</td>
												</tr>

												<tr>
													<?php if($changeRequest['REQUESTTYPE'] == '2'):?>
														<td colspan='2'>
													<?php else:?>
														<td colspan='4'>
													<?php endif;?>
														<label>Reason</label>
														<p><?php echo $changeRequest['REASON'];?></p>
													</td>

													<?php if($changeRequest['REQUESTTYPE'] == '2'):?>
													<td colspan='2'>
														<label>Requested End Date</label>
														<?php if($changeRequest['NEWENDDATE'] != ""):?>
															<p><?php echo date_format($newEndDate, "F d, Y");?></p>
														<?php else:?>
															<p>-</p>
														<?php endif;?>
													</td>
													<?php endif;?>
												</tr>

											</table>
									</div>
									<!-- /.box-body -->
									<!-- /.box-footer -->
								</div>
								<!-- /.box -->
							</div>

						<?php endif;?>

						<?php if(isset($_SESSION['rfc'])):?>

							<div class="col-md-6">
								<div class="box box-danger">
									<!-- /.box-header -->
									<div class="box-body">
											<div class="form-group">
												<?php if (isset($_SESSION['remarks'])): ?>
													<textarea id = "remarks" name = "remarks" class="form-control" rows="5"><?php echo $_SESSION['remarks']; ?></textarea>
												<?php else: ?>
													<textarea id = "remarks" name = "remarks" class="form-control" rows="5" placeholder="Enter remarks"></textarea>
												<?php endif; ?>
											</div>

										<span data-toggle="modal" data-target="#modal-deny">
											<button id = "denyRequest" type="button" class="btn btn-danger pull-left" style="display:block" data-toggle="tooltip" data-placement="right" title="Deny">
												<i class="fa fa-close"></i>
											</button>
										</span>

											<?php if($changeRequest['REQUESTTYPE'] == '1'):?>

												<span data-toggle="modal" data-target="#modal-delegate">
													<button id = "approveRequest" type="button" class="btn btn-success pull-right delegateApprove" style="display:block;"
													data-id="<?php echo $changeRequest['tasks_REQUESTEDTASK'];?>" data-user="<?php echo $changeRequest['users_REQUESTEDBY'];?>"
													data-toggle="tooltip" data-placement="left" title="Approve & Delegate">
														<i class="fa fa-check"></i><i class="fa fa-user" ></i>
													</button>
												</span>
											<?php else:?>

												<span data-toggle="modal" data-target="#modal-approve">
													<button id = "approveRequest" type="button" class="btn btn-success pull-right" style="display:block;"
													data-toggle="tooltip" data-placement="left" title="Approve">
														<i class="fa fa-check"></i></i>
													</button>
												</span>
											<?php endif;?>
									</div>
									<!-- /.box-body -->
									<!-- /.box-footer -->
								</div>
								<!-- /.box -->
							</div>
						<?php endif;?>
						<?php if(isset($_SESSION['rfc']) || isset($_SESSION['userRequest'])): ?>

					</div>
					<hr style="height:1px; background-color:black">
				</div>
				<!-- RFC GANTT END -->

				<!-- DELEGATE MODAL -->
				<div class="modal fade" id="modal-delegate">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title taskTitle"><?php echo $changeRequest['TASKTITLE'];?></h2>
								<h4 class="taskDates"><?php echo date_format($startDate, "F d, Y");?> - <?php echo date_format($endDate, "F d, Y");?>
									(<?php
										if($changeRequest['TASKADJUSTEDSTARTDATE'] != null && $changeRequest['TASKADJUSTEDENDDATE'] != null)
											$taskDuration = $changeRequest['adjustedTaskDuration2'];
										elseif($changeRequest['TASKSTARTDATE'] != null && $changeRequest['TASKADJUSTEDENDDATE'] != null)
											$taskDuration = $changeRequest['adjustedTaskDuration1'];
										else
											$taskDuration = $changeRequest['initialTaskDuration'];

									echo $taskDuration;?>
									<?php if($taskDuration > 1):?>
										 Days)
									<?php else:?>
										Day)
									<?php endif;?>
								</h4>
								<h4>Current Task Responsible: <?php echo $changeRequest['FIRSTNAME'] . " " .  $changeRequest['LASTNAME'];?></h4>
							</div>

							<div class="modal-body">
								<div id="raciDelegate">
								<!-- <div class="box box-danger"> -->
									<!-- /.box-header -->
									<div class="box-body" id ='delegateDiv'>
										<form id="raciForm" action="delegateTask" method="POST">

											<!-- TEAM DIV -->
											<div class="form-group raciDiv" id = "teamDiv">
											<table id="teamList" class="table table-bordered table-hover">
												<thead>
												<tr>
													<th>Executive</th>
													<th class='text-center'>R*</th>
													<th class='text-center'>A</th>
													<th class='text-center'>C</th>
													<th class='text-center'>I</th>
													<!-- <th>No. of Projects (Ongoing & Planned)</th>
													<th>No. of Tasks (Ongoing & Planned)</th> -->
												</tr>
												</thead>

												<tbody id='assignment'>
													<!-- EXECUTIVES -->
													<?php foreach($users as $user):?>
														<?php if($user['departments_DEPARTMENTID'] == '1'):?>
														<tr>
															<td><?php echo $user['FIRSTNAME'] . " " .  $user['LASTNAME'];?></td>
															<td class='text-center'>
																<div class="radio">
																<label>
																	<input id='user<?php echo $user['USERID'];?>-1' class = "radioEmp" type="radio" name="responsibleEmp" value="<?php echo $user['USERID'];?>" disabled>
																</label>
															</div>
															</td>
															<td class='text-center'>
																<div class="checkbox">
																<label>
																	<input id='user<?php echo $user['USERID'];?>-2' class = "checkEmp" type="checkbox" name="accountableEmp[]" value="<?php echo $user['USERID'];?>" required>
																</label>
															</div>
															</td>
															<td class='text-center'>
																<div class="checkbox">
																<label>
																	<input id='user<?php echo $user['USERID'];?>-3' class = "checkEmp" type="checkbox" name="consultedEmp[]" value="<?php echo $user['USERID'];?>" required>
																</label>
															</div>
															</td>
															<td class='text-center'>
																<div class="checkbox">
																<label>
																	<input id='user<?php echo $user['USERID'];?>-4' class = "checkEmp" type="checkbox" name="informedEmp[]" value="<?php echo $user['USERID'];?>" required>
																</label>
															</div>
															</td>
														</tr>
														<?php endif;?>
													<?php endforeach;?>

													<thead>
														<tr>
															<th colspan='5'>Department</th>
														</tr>
													</thead>

													<!-- ALL DEPARTMENTS -->
													<?php foreach($departments as $department):?>
														<?php if($department['DEPARTMENTID'] != $changeRequest['departments_DEPARTMENTID'] && $department['DEPARTMENTNAME'] != 'Executive'):?>
															<tr>
																<td><?php echo $department['DEPARTMENTNAME'];?></td>
																<td class='text-center'>
																	<div class="radio">
																	<label>
																		<input id='user<?php echo $department['users_DEPARTMENTHEAD'];?>-1' class = "radioEmp" type="radio" name="responsibleEmp" value="<?php echo $department['users_DEPARTMENTHEAD'];?>" required>
																	</label>
																</div>
																</td>
																<td class='text-center'>
																	<div class="checkbox">
																	<label>
																		<input id='user<?php echo $department['users_DEPARTMENTHEAD'];?>-2' class = "checkEmp" type="checkbox" name="accountableEmp[]" value="<?php echo $department['users_DEPARTMENTHEAD'];?>" required>
																	</label>
																</div>
																</td>
																<td class='text-center'>
																	<div class="checkbox">
																	<label>
																		<input id='user<?php echo $department['users_DEPARTMENTHEAD'];?>-3' class = "checkEmp" type="checkbox" name="consultedEmp[]" value="<?php echo $department['users_DEPARTMENTHEAD'];?>" required>
																	</label>
																</div>
																</td>
																<td class='text-center'>
																	<div class="checkbox">
																	<label>
																		<input id='user<?php echo $department['users_DEPARTMENTHEAD'];?>-4' class = "checkEmp" type="checkbox" name="informedEmp[]" value="<?php echo $department['users_DEPARTMENTHEAD'];?>" required>
																	</label>
																</div>
																</td>
															</tr>
														 <?php endif;?>
													<?php endforeach;?>

													<!-- STAFF IN DEPARTMENT -->
													<thead><tr><th colspan='5'>Employee</th></tr></thead>

													<?php foreach($wholeDept as $employee):?>
														<tr>
															<?php $hasProjects = false;?>
															<?php foreach($projectCount as $count): ;?>
																<?php $hasProjects = false;?>
																<?php if ($count['USERID'] == $employee['USERID']):?>
																	<?php $hasProjects = $count['projectCount'];?>
																	<?php break;?>
																<?php endif;?>
															<?php endforeach;?>
															<?php if ($hasProjects <= '0'):?>
																<?php $hasProjects = 0;?>
															<?php endif;?>

															<?php $hasTasks = false;?>
															<?php foreach($taskCount as $count): ;?>
																<?php $hasTasks = false;?>
																<?php if ($count['USERID'] == $employee['USERID']):?>
																	<?php $hasTasks = $count['taskCount'];?>
																	<?php break;?>
																<?php endif;?>
															<?php endforeach;?>
															<?php if ($hasTasks <= '0'):?>
																<?php $hasTasks = 0;?>
															<?php endif;?>

															<td class='clickable moreInfo' data-id="<?php echo $employee['USERID'];?>"
															data-name="<?php echo $employee['FIRSTNAME'];?> <?php echo $employee['LASTNAME'];?>"
															data-projectCount = "<?php echo $hasProjects;?>"
															data-taskCount = "<?php echo $hasTasks;?>"><?php echo $employee['FIRSTNAME'] . " " .  $employee['LASTNAME'];?> <br><i><span style="font-size:11px"><?php echo $employee['POSITION'];?></span></i></td>
															<td class='text-center'>
																<div class="radio">
																<label>
																	<input id='user<?php echo $employee['USERID'];?>-1' class = "radioEmp" type="radio" name="responsibleEmp" value="<?php echo $employee['USERID'];?>" required>
																</label>
															</div>
															</td>
															<td class='text-center'>
																<div class="checkbox">
																<label>
																	<?php if($employee['usertype_USERTYPEID'] == '5'):?>
																		<input disabled id='user<?php echo $employee['USERID'];?>-2' class = "checkEmp" type="checkbox" name="accountableEmp[]" value="<?php echo $employee['USERID'];?>" required>
																	<?php else:?>
																		<input id='user<?php echo $employee['USERID'];?>-2' class = "checkEmp" type="checkbox" name="accountableEmp[]" value="<?php echo $employee['USERID'];?>" required>
																	<?php endif;?>
																</label>
															</div>
															</td>
															<td class='text-center'>
																<div class="checkbox">
																<label>
																	<?php if($employee['usertype_USERTYPEID'] == '5'):?>
																		<input disabled id='user<?php echo $employee['USERID'];?>-3' class = "checkEmp" type="checkbox" name="consultedEmp[]" value="<?php echo $employee['USERID'];?>" required>
																	<?php else:?>
																		<input id='user<?php echo $employee['USERID'];?>-3' class = "checkEmp" type="checkbox" name="consultedEmp[]" value="<?php echo $employee['USERID'];?>" required>
																	<?php endif;?>																</label>
															</div>
															</td>
															<td class='text-center'>
																<div class="checkbox">
																<label>
																	<?php if($employee['usertype_USERTYPEID'] == '5'):?>
																		<input disabled id='user<?php echo $employee['USERID'];?>-4' class = "checkEmp" type="checkbox" name="informedEmp[]" value="<?php echo $employee['USERID'];?>" required>
																	<?php else:?>
																		<input id='user<?php echo $employee['USERID'];?>-4' class = "checkEmp" type="checkbox" name="informedEmp[]" value="<?php echo $employee['USERID'];?>" required>
																	<?php endif;?>																	</label>
															</div>
															</td class='text-center'>

															<!-- <?php $hasProjects = false;?>
															<?php foreach($projectCount as $count): ;?>
																<?php $hasProjects = false;?>
																<?php if ($count['USERID'] == $employee['USERID']):?>
																	<td align="center"><?php echo $count['projectCount'];?></td>
																	<?php $hasProjects = $count['projectCount'];?>
																	<?php break;?>
																<?php endif;?>
															<?php endforeach;?>
															<?php if ($hasProjects <= '0'):?>
																<?php $hasProjects = 0;?>
																<td align="center">0</td>
															<?php endif;?>

															<?php $hasTasks = false;?>
															<?php foreach($taskCount as $count): ;?>
																<?php $hasTasks = false;?>
																<?php if ($count['USERID'] == $employee['USERID']):?>
																	<td align="center"><?php echo $count['taskCount'];?></td>
																	<?php $hasTasks = $count['taskCount'];?>
																	<?php break;?>
																<?php endif;?>
															<?php endforeach;?>
															<?php if ($hasTasks <= '0'):?>
																<?php $hasTasks = 0;?>
																<td align="center">0</td>
															<?php endif;?> -->
														 </tr>
													<?php endforeach;?>
												</tbody>
											</table>
											<p>* Only one department/employee is allowed to be assigned</p>

											</div>
										</form>

									<!-- /.box-body -->
								</div>
							<!-- </div> -->

							<div class="modal-footer">
								<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
								<button type="button" id = "delegateBtn" class="btn btn-success" data-toggle="tooltip" data-placement="left" title="Confirm Approve & Delegate"><i class="fa fa-check"></i></button>
							</div>
						</form>
						</div>

						<!-- WORKLOAD ASSESSMENT -->
						<div id="workloadAssessment">

							<div class="modal-header">
								<h3 class="modal-title" id ="workloadEmployee">Employee Name</h3>
								<h4 id = "empJobDescription">Job Description: </h4>
								<h4 id = "workloadProjects">Total Number of Projects: </h4>
								<h4 id = "workloadTasks">Total Number of Tasks: </h4>
							</div>
							<div class="modal-body" id = "workloadDiv">
							</div>
							<div class="modal-footer">
								<button type="button" id="backWorkload" class="btn btn-default pull-left" data-toggle="tooltip" data-placement="right" title="Back"><i class="fa fa-arrow-left"></i></button>
							</div>

						</div>

						<!-- CONFIRM DELEGATE -->
						<div id="delegateConfirm">
							<div class="modal-body">
								<h4>Are you sure you want to approve and re-delegate this task?</h4>
							</div>
							<div class="modal-footer">
								<button id="backConfirmDelegate" type="button" class="btn btn-default pull-left" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
								<button id = "confirmDelegateBtn" type="submit" class="btn btn-success delegate" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm Approve & Delegate"><i class="fa fa-check"></i></button>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		<!-- END DELEGATE -->

			<!-- CONFIRM DENY MODAL -->
			<div class="modal fade" id="modal-deny">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h2 class="modal-title taskTitle"><?php echo $changeRequest['TASKTITLE'];?></h2>
							<h4 class="taskDates"><?php echo date_format($startDate, "F d, Y");?> - <?php echo date_format($endDate, "F d, Y");?>
								(<?php
									if($changeRequest['TASKADJUSTEDSTARTDATE'] != null && $changeRequest['TASKADJUSTEDENDDATE'] != null)
										$taskDuration = $changeRequest['adjustedTaskDuration2'];
									elseif($changeRequest['TASKSTARTDATE'] != null && $changeRequest['TASKADJUSTEDENDDATE'] != null)
										$taskDuration = $changeRequest['adjustedTaskDuration1'];
									else
										$taskDuration = $changeRequest['initialTaskDuration'];

								echo $taskDuration;?>
								<?php if($taskDuration > 1):?>
									 Days)
								<?php else:?>
									Day)
								<?php endif;?>
							<h4>Current Task Responsible: <?php echo $changeRequest['FIRSTNAME'] . " " .  $changeRequest['LASTNAME'];?></h4>
						</div>
						<div class="modal-body">
							<div id="denyConfirm">
								<h4>Are you sure you want to deny this request?</h4>
								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
									<button id = "confirmDenyBtn" type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm Deny"><i class="fa fa-check"></i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

				<!-- APPROVE MODAL -->
				<div class="modal fade" id="modal-approve">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title taskTitle"><?php echo $changeRequest['TASKTITLE'];?></h2>
								<h4 class="taskDates"><?php echo date_format($startDate, "F d, Y");?> - <?php echo date_format($endDate, "F d, Y");?>
									(<?php
										if($changeRequest['TASKADJUSTEDSTARTDATE'] != null && $changeRequest['TASKADJUSTEDENDDATE'] != null)
											$taskDuration = $changeRequest['adjustedTaskDuration2'];
										elseif($changeRequest['TASKSTARTDATE'] != null && $changeRequest['TASKADJUSTEDENDDATE'] != null)
											$taskDuration = $changeRequest['adjustedTaskDuration1'];
										else
											$taskDuration = $changeRequest['initialTaskDuration'];

									echo $taskDuration;?>
									<?php if($taskDuration > 1):?>
										 Days)
									<?php else:?>
										Day)
									<?php endif;?>
								</h4>

								<h4>Current Task Responsible: <?php echo $changeRequest['FIRSTNAME'] . " " .  $changeRequest['LASTNAME'];?></h4>
							</div>

						<div class="modal-body">

							<!-- CONFIRM APPROVE -->
							<div id="approveConfirm">
								<h4>Are you sure you want to approve this request?</h4>
								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
									<button id = "confirmApproveBtn" type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Approve"><i class="fa fa-check"></i></button>
								</div>
							</div>
								<!-- /.box-body -->
							</div>
						</div>
					</div>
				</div> <!-- END OF MODAL DIV -->
			<?php endif;?>

				<h1>
					<?php echo $projectProfile['PROJECTTITLE']; ?>
						<!-- <?php if ($projectProfile['PROJECTSTATUS'] == 'Planning' || $projectProfile['PROJECTSTATUS'] == 'Ongoing'): ?>

							<form id="editProjectForm" action = 'editProject'  method="POST" style="display:inline-block">
								<input type='hidden' name='edit' value='<?php echo $projectProfile['PROJECTID'];?>'>
							</form>

							<a id="editProject" data-id="<?php echo $projectProfile['PROJECTID']; ?>"><i class="fa fa-edit"></i></a>
						<?php endif; ?> -->
				</h1>

				<ol class="breadcrumb">
					<?php $dateToday = date('F d, Y | l');?>
					<p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
				</ol>

				<?php if($projectProfile['PROJECTSTATUS'] != 'Planning'): ?>

					<div class="col-md-3 col-sm-6 col-xs-12 pull-right">
							<div class="box-header with-border" style="text-align:center;">
								<h3 class="box-title">Project Performance</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<div style="display:inline-block; text-align:center; width:49%;">
									<div class="circlechart"
										data-percentage="<?php
											if($projectCompleteness['completeness'] == NULL){
												echo 0;
											} else {
												if($projectCompleteness['completeness'] == 100.00){
													echo 100;
												} elseif ($projectCompleteness['completeness'] == 0.00) {
													echo 0;
												} else {
													echo $projectCompleteness['completeness'];
												}
											}
											?>">Completeness
									</div>
								</div>
								<div style="display:inline-block; text-align:center; width:49%;">
									<div class="circlechart"
									 data-percentage="<?php
										 if($projectTimeliness['timeliness'] == NULL){
											 echo 0;
										 } else {
											 if($projectTimeliness['timeliness'] == 100.00){
												 echo 100;
											 } elseif ($projectTimeliness['timeliness'] == 0.00) {
												 echo 0;
											 } else {
												 echo $projectTimeliness['timeliness'];
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
										<div class="circlechart"
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
										<div class="circlechart"
										 data-percentage="<?php
										 if($employeeTimeliness['timeliness'] == 0.00 && $employeeTimeliness['projects_PROJECTID'] == NULL){
											 echo 100;
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

				<!-- /.col -->
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

						<h4><b>Actual Duration: <?php echo date_format($startdate, "F d, Y"); ?> to <?php echo date_format($actualEnd, "F d, Y"); ?>
							(<?php echo $projectProfile['actualDuration'];?>
							<?php if($projectProfile['actualDuration'] > 1):?>
								days)
							<?php else:?>
								day)
							<?php endif;?>
						</b></h4>

					<?php else:?>

						<h4><b>
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
						</b></h4>

					<?php endif;?>

					<form name="gantt" action ='projectDocuments' method="POST" id ="prjID">
						<input type="hidden" name="project_ID" value="<?php echo $projectProfile['PROJECTID']; ?>">
						<input type="hidden" name="projectID_logs" value="<?php echo $projectProfile['PROJECTID']; ?>">
					</form>

					<form name="gantt" action ='parkProject' method="POST" id ="parkProj">
						<input type="hidden" name="project_ID" value="<?php echo $projectProfile['PROJECTID']; ?>">
					</form>

					<!-- IF USING GET METHOD
					<a href="<?php echo base_url("index.php/controller/projectDocuments/?id=") . $projectProfile['PROJECTID']; ?>" name="PROJECTID" class="btn btn-success btn-xs" id="projectDocu"><i class="fa fa-folder"></i> View Documents</a> -->
					<!-- <a href="<?php echo base_url("index.php/controller/projectLogs/?id=") . $projectProfile['PROJECTID']; ?>"class="btn btn-default btn-xs"><i class="fa fa-flag"></i> View Logs</a> -->

					<a name="PROJECTID" class="btn btn-primary btn" id="projectDocu" data-toggle="tooltip" data-placement="top" title="Documents"><i class="glyphicon glyphicon-folder-open"></i></a>

					<a name="PROJECTID_logs" class="btn btn-primary btn" id="projectLog" data-toggle="tooltip" data-placement="top" title="Logs"><i class="fa fa-list"></i></a>

					<?php if ($projectProfile['PROJECTSTATUS'] == 'Complete' && $_SESSION['usertype_USERTYPEID'] != 5): ?>

						<form action = 'archiveProject' id="archiveProject" method="POST" style="display:inline-block">
						</form>
						<span data-toggle="modal" data-target="#confirmArchive"><a name="" class="btn btn-primary btn" id="archiveProject" data-toggle="tooltip" data-placement="top" title="Archive Project"><i class="fa fa-archive"></i></a></span>

					<?php elseif($projectProfile['PROJECTSTATUS'] == 'Archived' && !isset($_SESSION['templates']) && !isset($_SESSION['templateProjectGantt']) && $_SESSION['usertype_USERTYPEID'] != 5): ?>

						<?php if (!$isTemplate): ?>
							<form id="templateProject" action = 'templateProject' method="POST" style="display:inline-block">
							</form>
							<span data-toggle="modal" data-target="#confirmTemplate"><a name="" class="btn btn-primary btn" id="templateProject" data-toggle="tooltip" data-placement="top" title="Save as Template"><i class="fa fa-window-maximize"></i></a></span>
						<?php endif; ?>

					<?php elseif (isset($_SESSION['templates']) || isset($_SESSION['templateProjectGantt'])): ?>
						<form id="useTemplateForm" action = 'addProjectDetails' method="POST" style="display:inline-block">
							<input type="hidden" name="templates" value="<?php echo $projectProfile['PROJECTID']; ?>">
						</form>
						<span data-toggle="modal" data-target="#confirmUseTemplate"><a name="" class="btn btn-primary btn" id="useTemplate" data-toggle="tooltip" data-placement="top" title="Use Template"><i class="fa fa-window-maximize"></i></a></span>
					<?php endif; ?>

					<!-- <?php if($projectProfile['PROJECTSTATUS'] == 'Ongoing'): ?>
						<span data-toggle="modal" data-target="#confirmPark"><a name="" class="btn btn-default btn" id="parkProject" data-toggle="tooltip" data-placement="top" title="Park Project"><i class="fa fa-clock-o"></i></a></span>
					<?php endif;?> -->

					<?php if($projectProfile['PROJECTSTATUS'] == 'Parked'): ?>
						<span data-toggle="modal" data-target="#confirmContinue"><a name="" class="btn btn-primary btn" id="continueProject" data-toggle="tooltip" data-placement="top" title="Continue Project"><i class="fa fa-clock-o"></i></a></span>
					<?php endif;?>

					<?php if ($projectProfile['PROJECTSTATUS'] == 'Complete' || $projectProfile['PROJECTSTATUS'] == 'Archived' || isset($_SESSION['templateProjectGantt'])): ?>
						<form action = 'projectSummary' id="projectSummary" method="POST" style="display:inline-block">

							<?php if (isset($_SESSION['templateProjectGantt'])): ?>
								<input type="hidden" name="templateProjSummary" value="templateProjSummary">
							<?php endif; ?>

							<input type="hidden" name="project_ID" value="<?php echo $projectProfile['PROJECTID']; ?>">
						</form>
						<a name="" class="btn btn-primary btn" id="projectSummary" data-toggle="tooltip" data-placement="top" title="Project Summary"><i class="fa fa-bar-chart"></i></a>
					<?php endif; ?>

				</div>
				<br>

				<!-- LEGEND -->
				<!-- <div style="margin-bottom:10px">
					<small style="display: inline-block">Legend:</small>
					<div style="width: 20px; height: 10px; background-color:#ED6C1F; display:inline-block; margin-left:10px;"></div> Selected
					<div style="width: 20px; height: 10px; background-color:#465A63; display:inline-block; margin-left:10px;"></div> Parent Target Timeline
					<div style="width: 20px; height: 10px; background-color:#2278CF; display:inline-block; margin-left:10px;"></div> Task Progress
					<div style="width: 20px; height: 10px; background-color:#F2331E; display:inline-block; margin-left:10px;"></div> Delayed
					<div style="width: 20px; height: 10px; background-color:#BBD6F1; display:inline-block; margin-left:10px;"></div> Actual Timeline
					<div style="width: 20px; height: 10px; background-color:#68B6F3; display:inline-block; margin-left:10px;"></div> Child Target Timeline
					<div style="width: 20px; height: 10px; background-color:#0C7F12; display:inline-block; margin-left:10px;"></div> Ongoing
				</div> -->

				<!-- CONFIRM ARCHIVE -->
				<div class="modal fade" id="confirmArchive" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Archive Project</h2>
							</div>
							<div class="modal-body">
								<h4>Are you sure you want to archive this project?</h4>
								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
									<button id= "doneArchive" type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

				<!-- CONFIRM PARK -->
				<div class="modal fade" id="confirmPark" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Park Project</h2>
							</div>
							<div class="modal-body">
								<h4>Are you sure you want to park this project?</h4>
								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
									<button id = "donePark" type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

				<!-- CONFIRM USE TEMPLATE -->
				<div class="modal fade" id="confirmUseTemplate" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Use Template</h2>
							</div>
							<div class="modal-body">
								<h4>Are you sure you want use this template?</h4>
								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
									<button id = "doneUseTemplate" type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

				<!-- CONFIRM TEMPLATE -->
				<div class="modal fade" id="confirmTemplate" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Save Project as Template</h2>
							</div>
							<div class="modal-body">
								<h4>Are you sure you want to make this project a template?</h4>
								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
									<button id = "doneTemplate" type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

				<!-- CONFIRM CONTINUE -->
				<div class="modal fade" id="confirmContinue" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Continue Project</h2>
							</div>
							<div class="modal-body">
								<h4>Are you sure you want to continue this project?</h4>
								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
									<button id = "doneContinue" type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

				<input type="button" class="btn btn-default btn-sm" value="-" onclick="chart.zoomOut();">
				<input type="button" class="btn btn-default btn-sm" value="+" onclick="chart.zoomIn();">
				<input type="button" class="btn btn-default btn-sm" value="Fit All" onclick="chart.fitAll();">
				<!-- <input type="button" value="Day" onclick="chart.zoomTo('day', 1);"> -->
				<input type="button" class="btn btn-default btn-sm" value="Week" onclick="chart.zoomTo('week', 1);">
				<input type="button" class="btn btn-default btn-sm" value="Month" onclick="chart.zoomTo('month', '1')">
				<br>
				<br>
				<span>Legend: </span>
        <p class="btn bg-teal btn-xs">Completed</p>
        <p class="btn btn-success btn-xs">Ongoing</p>
        <p class="btn btn-danger btn-xs">Delayed</p>
        <p class="btn btn-warning btn-xs">Planned</p>

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
									<button type="button" id = "tabUpdates" class="btn btn-default tabDetails">Updates</button>
									<button type="button" id = "tabRACI" class="btn btn-default tabDetails">RACI</button>
									<button type="button" id = "tabRFC" class="btn btn-default tabDetails">RFC</button>
									<button type="button" id = "tabDelay" class="btn btn-default tabDetails">Projection</button>
								</div>
								<br><br>

								<div id="divRFCEffect" class="divDetails">
									<table class="table no-margin table-hover" id="rfcEffectDateTable">
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

				<div id="container" style="height: 600px;">
				</div>


				<!-- </section> -->
			</section>
				</div>
		<?php require("footer.php"); ?>
	</div>
	<script>

	// $("#dependencyModal").hide();

	$(document).on("click", "#doneArchive", function() {
		var $id = <?php echo $projectProfile['PROJECTID']; ?>;
		$("#archiveProject").attr("name", "formSubmit");
		$("#archiveProject").append("<input type='hidden' name='project_ID' value= " + $id + ">");
		$("#archiveProject").submit();
		});

	$(document).on("click", "#projectSummary", function() {
		var $id = <?php echo $projectProfile['PROJECTID']; ?>;
		// $("form").attr("name", "formSubmit");
		// $("#projectSummary").append("<input type='hidden' name='templateProjSummary' value='templateProjSummary'>");
		$("#projectSummary").submit();
		});

	$(document).on("click", "#doneTemplate", function() {
		var $id = <?php echo $projectProfile['PROJECTID']; ?>;
		$("#templateProject").attr("name", "formSubmit");
		$("#templateProject").append("<input type='hidden' name='project_ID' value= " + $id + ">");
		$("#templateProject").submit();
		});

	$(document).on("click", "#doneUseTemplate", function() {
		var $id = <?php echo $projectProfile['PROJECTID']; ?>;
		$("#useTemplateForm").attr("name", "formSubmit");
		$("#useTemplateForm").append("<input type='hidden' name='project_ID' value= " + $id + ">");
		$("#useTemplateForm").submit();
		});

	$("#donePark").click(function() //submitPark
	{
		$("#parkProj").submit();
	});

	$("#doneContinue").click(function() //submitPark
	{
		// INSERT REDIRECT TO EDIT PROJECT
	});

	$(document).on("click", "#editProject", function() {
		var $id = <?php echo $projectProfile['PROJECTID']; ?>;
		$("#editProjectForm").append("<input type='hidden' name='project_ID' value= " + $id + ">");
		$("#editProjectForm").submit();
		});

		// $("#myProjects").addClass("active");

		// $("#projectDocu").click(function()
		// {
		// 	("#gantt").submit();
		// });

		// RFC APPROVAL SCRIPT

		$("body").on("click", function(){ // REMOVE ALL SELECTED IN MODAL
			if($("#modal-delegate").css("display") == 'none')
			{
				$(".radioEmp").prop("checked", false);
				$(".checkEmp").prop("checked", false);
				$("#raciDelegate").show();
				$("#workloadAssessment").hide();
				$("#delegateConfirm").hide();
			}
		});

		$(document).on("click", "#confirmDelegateBtn", function() { // approve with delegate
			$(".checkEmp").prop('disabled', false);
			$(".radioEmp").prop('disabled', false);

			var $request = $("#approvalForm").attr('data-request');
			var $project = $("#approvalForm").attr('data-project');
			var $task = $("#approvalForm").attr('data-task');
			var $type = $("#approvalForm").attr('data-type');
			var $requestor = $("#approvalForm").attr('data-requestor');
			$("#approvalForm").attr("name", "formSubmit");
			$("#approvalForm").append("<input type='hidden' name='request_ID' value= '" + $request + "'>");
			$("#approvalForm").append("<input type='hidden' name='request_type' value= '" + $type + "'>");
			$("#approvalForm").append("<input type='hidden' name='project_ID' value= '" + $project + "'>");
			$("#approvalForm").append("<input type='hidden' name='task_ID' value= '" + $task + "'>");
			$("#approvalForm").append("<input type='hidden' name='requestor_ID' value= '" + $requestor + "'>");
			$("#approvalForm").append("<input type='hidden' name='status' value= 'Approved'>");
			$("#approvalForm").submit();
			});

		$(document).on("click", "#confirmDenyBtn", function() { // deny
			var $request = $("#approvalForm").attr('data-request');
			var $project = $("#approvalForm").attr('data-project');
			var $task = $("#approvalForm").attr('data-task');
			var $type = $("#approvalForm").attr('data-type');
			var $requestor = $("#approvalForm").attr('data-requestor');
			$("#approvalForm").attr("name", "formSubmit");
			$("#approvalForm").append("<input type='hidden' name='request_ID' value= '" + $request + "'>");
			$("#approvalForm").append("<input type='hidden' name='request_type' value= '" + $type + "'>");
			$("#approvalForm").append("<input type='hidden' name='project_ID' value= '" + $project + "'>");
			$("#approvalForm").append("<input type='hidden' name='task_ID' value= '" + $task + "'>");
			$("#approvalForm").append("<input type='hidden' name='requestor_ID' value= '" + $requestor + "'>");
			$("#approvalForm").append("<input type='hidden' name='status' value= 'Denied'>");
			$("#approvalForm").submit();
			});

		$(document).on("click", "#confirmApproveBtn", function() { // approve without delegate
			var $request = $("#approvalForm").attr('data-request');
			var $project = $("#approvalForm").attr('data-project');
			var $task = $("#approvalForm").attr('data-task');
			var $type = $("#approvalForm").attr('data-type');
			var $requestor = $("#approvalForm").attr('data-requestor');
			$("#approvalForm").attr("name", "formSubmit");
			$("#approvalForm").append("<input type='hidden' name='request_ID' value= '" + $request + "'>");
			$("#approvalForm").append("<input type='hidden' name='request_type' value= '" + $type + "'>");
			$("#approvalForm").append("<input type='hidden' name='project_ID' value= '" + $project + "'>");
			$("#approvalForm").append("<input type='hidden' name='task_ID' value= '" + $task + "'>");
			$("#approvalForm").append("<input type='hidden' name='requestor_ID' value= '" + $requestor + "'>");
			$("#approvalForm").append("<input type='hidden' name='status' value= 'Approved'>");
			$("#approvalForm").submit();
			});

			$("body").on("click", ".delegateApprove", function(){
				var $id = $(this).attr('data-id');
				var $user = $(this).attr('data-user');

				$.ajax({
					type:"POST",
					url: "<?php echo base_url("index.php/controller/getRACIByTaskID"); ?>",
					data: {taskID: $id},
					dataType: 'json',
					success:function(data)
					{
						for(x=0; data['raci'].length >x; x++)
						{
							$("#user" + data['raci'][x].users_USERID + "-" + data['raci'][x].ROLE).prop('checked', true);
							if((data['raci'][x].ROLE == '3' || data['raci'][x].ROLE == '4') && <?php echo $_SESSION['usertype_USERTYPEID'];?> == '4')
							{
								$("#user" + data['raci'][x].users_USERID + "-" + data['raci'][x].ROLE).prop('disabled', true);
							}
							if(data['raci'][x].ROLE == '1' && data['raci'][x].users_USERID == $user)
							{
								$("#user" + data['raci'][x].users_USERID + "-" + data['raci'][x].ROLE).prop('checked', false);
								$("#user" + data['raci'][x].users_USERID + "-" + data['raci'][x].ROLE).prop('disabled', true);
							}
						}
					},
					error:function(data)
					{
						alert("There was a problem with loading the RACI");
					}
				});
			});

			$("body").on("click", ".moreInfo", function(){
				function loadWorkloadTasks($projectID)
			 {
				 $.ajax({
					 type:"POST",
					 url: "<?php echo base_url("index.php/controller/getUserWorkloadTasksUnique"); ?>",
					 data: {userID: $id, projectID: $projectID},
					 dataType: 'json',
					 success:function(data)
					 {
						 for(x=0; data['workloadTasks'].length > x; x++)
						 {
							 var $taskID = data['workloadTasks'][x].TASKID;
							 $.ajax({
								type:"POST",
								url: "<?php echo base_url("index.php/controller/getRACIByTaskID"); ?>",
								data: {taskID: $taskID},
								dataType: 'json',
								success:function(data)
								{
									var type="";
									var role="";
									if(data['raci'][0].TASKADJUSTEDENDDATE == null) // check if end date has been previously adjusted
									{
										var taskEnd = moment(data['raci'][0].TASKENDDATE).format('MMM DD, YYYY');
										var endDate = data['raci'][0].TASKENDDATE;
									}
									else
									{
										var taskEnd = moment(data['raci'][0].TASKADJUSTEDENDDATE).format('MMM DD, YYYY');
										var endDate = data['raci'][0].TASKADJUSTEDENDDATE;
									}

									if(data['raci'][0].TASKADJUSTEDSTARTDATE == null) // check if start date has been previously adjusted
										var taskStart = moment(data['raci'][0].TASKSTARTDATE).format('MMM DD, YYYY');
									else
										var taskStart = moment(data['raci'][0].TASKADJUSTEDSTARTDATE).format('MMM DD, YYYY');

									for(t=0; t<data['raci'].length; t++)
									{
										if(data['raci'][t].users_USERID == $id)
										{
											switch(data['raci'][t].ROLE)
											{
												case '1': type = "R"; break;
												case '2': type = "A"; break;
												case '3': type = "C"; break;
												case '4': type = "I"; break;
												default: type = ""; break;
											}
											var role = role + type;
										}
									}

									if(data['raci'][0].TASKSTATUS == "Complete")
									{
										var status = "<td class='bg-teal'></td>";
										// var status = "<i class='fa fa-circle' style='color:teal' data-toggle='tooltip' data-placement='top' title='Completed'></i>"
									}
									if(data['raci'][0].TASKSTATUS == "Planning")
									{
										var status = "<td class='bg-orange'></td>";
										// var status = "<i class='fa fa-circle' style='color:orange' data-toggle='tooltip' data-placement='top' title='Planned'></i>"
									}
									if(data['raci'][0].TASKSTATUS == "Ongoing")
									{
										if(data['raci'][0].currentDate > endDate)
										var status = "<td class='bg-red'></td>";
											// var status = "<i class='fa fa-circle' style='color:red' data-toggle='tooltip' data-placement='top' title='Delayed'></i>"
										else
										var status = "<td class='bg-green'></td>";
											// var status = "<i class='fa fa-circle' style='color:green' data-toggle='tooltip' data-placement='top' title='Ongoing'></i>"
									}

									$("#project_" + $projectID).append("<tr>" +
													 status +
													 "<td>" + role + "</td>" +
													 "<td>" + data['raci'][0].TASKTITLE + "</td>" +
													 "<td>" + taskStart + "</td>" +
													 "<td>" + taskEnd + "</td>" +
													 "</tr>");
								},
								error:function()
								{
									alert("Failed to retrieve RACI of task");
								}
							 });
						 }
						 if(data['userData'].JOBDESCRIPTION != null)
							$("#empJobDescription").html("Job Description: " + data['userData'].JOBDESCRIPTION);
						 else
							$("#empJobDescription").html("Job Description: -");
					 },
					 error:function()
					 {
						 alert("Failed to retrieve user data.");
					 }
				 });
				}

				var $id = $(this).attr('data-id');
				var $projectCount = $(this).attr('data-projectCount');
				var $taskCount = $(this).attr('data-taskCount');
				$("#workloadEmployee").html($(this).attr('data-name'));
				$("#workloadProjects").html("Total Number of Projects: " + $projectCount);
				$("#workloadTasks").html("Total Number of Tasks: " + $taskCount);
				$('#workloadDiv').html("");
				$("#workloadAssessment").show();
				$("#raciDelegate").hide();

				$.ajax({
					type:"POST",
					url: "<?php echo base_url("index.php/controller/getUserWorkloadProjects"); ?>",
					data: {userID: $id},
					dataType: 'json',
					success:function(data)
					{
						$('#workloadDiv').html("");
						for(p=0; p<data['workloadProjects'].length; p++)
						{
							var $projectID = data['workloadProjects'][p].PROJECTID;
							$('#workloadDiv').append("<div class = 'box'>" +
											 "<div class = 'box-header'>" +
												 "<h3 class = 'box-title text-blue'> " + data['workloadProjects'][p].PROJECTTITLE + "</h3>" +
											 "</div>" +
											 "<div class = 'box-body table-responsive no-padding'>" +
												 "<table class='table table-hover' id='project_" + $projectID + "'>" +
												 "<th width='1%'></th>" +
												"<th width='1%'></th>" +
													"<th>Task Name</th>" +
													"<th>Start Date</th>" +
													"<th>End Date</th>");

							 loadWorkloadTasks($projectID);

							 $('#workloadDiv').append("</table>" +
																				 "</div>" +
																			 "</div>");
						}
					},
					error:function()
					{
						alert("Failed to retrieve user data.");
					}
				});

			});

		$("#backWorkload").click(function(){

			$("#raciDelegate").show();
			$("#workloadAssessment").hide();

		});

		$("#delegateBtn").click(function(){
			$("#raciDelegate").hide();
			$("#workloadAssessment").hide();
			$("#delegateConfirm").show();
		});

		$("#backConfirmDelegate").click(function(){

			$("#raciDelegate").show();
			$("#workloadAssessment").hide();
			$("#delegateConfirm").hide();

		});
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

		}

	</script>

	<script>

	// PROJECT GANTT START
		var chart;
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

					if($informedCount != 0)
					{
						$informedPerson = implode(", ", $informedArray);
						// $informedPerson = $informedArray[0];
						//
						// for($i = 0; $i < $informedCount; $i++)
						// {
						// 	$informedPerson .= (", " . $informedArray[$i]);
						// }
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

		$(document).on("click", "#tabUpdates", function(){
			$(".divDetails").hide();
			$(".tabDetails").removeClass('active');
			$(this).addClass('active')
			$("#divUpdates").show();
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
