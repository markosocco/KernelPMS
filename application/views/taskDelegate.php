<html>
	<head>
		<title>Kernel - Delegate Tasks</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/notificationsStyle.css")?>"> -->
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div style="margin-bottom:10px">
					<?php if(isset($_SESSION['dashboard'])): ?>
							<a href="<?php echo base_url("index.php/controller/dashboard"); ?>" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to Dashboard"><i class="fa fa-arrow-left"></i></a>
					<?php endif;?>
				</div>

				<h1>
					Delegate Tasks
					<small>What tasks are to be done by my team?</small>
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
				<button id = "viewFiltered" class="btn btn-default pull-right" data-toggle="tooltip" data-placement="left" title="To Delegate"><i class="fa fa-eye-slash"></i></button>

				<br><br>

				<div id = "filteredTasks">
					<div class="row">
						<!-- TO DO -->

						<?php
							$totalToDoProjects=0;
							$totalToDoTasks=0;
						?>

						<div class="col-md-10">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">To Delegate</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">

						<?php if ($delegateTasksByProject != NULL): ?>
							<?php foreach($delegateTasksByProject as $project):?>
								<?php if($project['threshold'] >= $project['PROJECTSTARTDATE']):?> <!-- show only tasks before the project start date -->

										<?php
										$startDate = date_create($project['PROJECTSTARTDATE']);
										$endDate = date_create($project['PROJECTENDDATE']);
										?>

											<?php $totalToDoProjects = $totalToDoProjects+1;?>

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
														<table class="table table-hover no-margin">
															<thead>
															<tr>
																<th width = '1%'></th>
																<th width='40%'>Task</th>
																<th class="text-center">Start Date</th>
																<th class="text-center">Action</th>
															</tr>
															</thead>
															<tbody id="taskDelegateToDo">
																<?php foreach($delegateTasks as $task):?>
																	<?php $startdate = date_create($task['TASKSTARTDATE']);?>

																		<?php if($task['projects_PROJECTID'] == $project['PROJECTID']):?>
																		<?php $totalToDoTasks = $totalToDoTasks+1;?>
																		<tr class="viewProject" data-id="<?php echo $task['TASKID'] ;?>">
																			<?php if($task['TASKSTATUS'] == "Planning"):?>
																				<td class = 'bg-yellow'></td>
																			<?php elseif($task['TASKSTATUS'] == "Ongoing" && $task['currentDate'] <= $task['TASKSTARTDATE']):?>
																				<td class = 'bg-green'></td>
																			<?php else:?>
																				<td class = 'bg-red'></td>
																			<?php endif;?>
																			<td class = "clickable task" data-id="<?php echo $task['TASKID'] ;?>" data-toggle="modal" data-target="#taskDetails"><?php echo $task['TASKTITLE'];?></td>
																			<td class = "clickable task" data-id="<?php echo $task['TASKID'] ;?>" data-toggle="modal" data-target="#taskDetails" align="center"><?php echo date_format($startdate, 'M d, Y');?></td>
																			<td align="center">
																				<span data-toggle="modal" data-target="#modal-delegate">
																				<button type="button" class="btn btn-primary btn-sm delegateBtn task-<?php echo $task['TASKID'];?>"
																				data-toggle="tooltip" data-placement="top" title="Delegate"
																				data-id="<?php echo $task['TASKID'];?>"
																				data-title="<?php echo $task['TASKTITLE'];?>"
																				data-start="<?php echo $task['TASKSTARTDATE'];?>"
																				data-end="<?php echo $task['TASKENDDATE'];?>">
																					<i class="fa fa-users"></i>
																				</button>
																				</span>
																				<!-- <span data-toggle="modal" data-target="#modal-accept"> -->
																				<button disabled id="taskAccept-<?php echo $task['TASKID'];?>" type="button" class="btn btn-success btn-sm acceptBtn"
																				data-toggle="tooltip" data-placement="top" title="Accept"
																				data-id="<?php echo $task['TASKID'];?>"
																				data-title="<?php echo $task['TASKTITLE'];?>"
																				data-start="<?php echo $task['TASKSTARTDATE'];?>"
																				data-end="<?php echo $task['TASKENDDATE'];?>">
																					<i class="fa fa-thumbs-up"></i>
																				</button>
																				<!-- </span> -->
																			</td>
																		</tr>
																	<?php endif;?>
																<?php endforeach;?> <!-- END SUB ACTIVITY -->

															</tbody>
														</table>
													</div>
												</div>
											</div>
										<?php endif;?>
									<?php endforeach;?> <!-- END PROJECT -->
								<?php endif;?>

									<?php if($totalToDoProjects == 0):?>

										<div class="box-body">
											<h4 align="center">You have no tasks to delegate due in 2 days</h4>
										</div>
									<?php endif;?>

								</div>
							</div>
						</div>

						<div class="col-md-2">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center" id="toDoProjects"> Projects <br><br><b><?php echo $totalToDoProjects;?></b></h4>
									</div>
								</div>
							</div>

							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center" id ="toDoTasks"> Tasks <br><br><b><?php echo $totalToDoTasks;?></b></span></h4>
									</div>
								</div>
							</div>
						</div>


					</div> <!-- CLOSING ROW -->
				</div>

				<div id = "allTasks">
					<!-- ALL TASKS -->
					<div class='row'>

						<?php
							$totalToDoProjects=0;
							$totalToDoTasks=0;
						?>

					<div class="col-md-10">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">All Tasks</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<?php if ($delegateTasksByProject != NULL): ?>
								<?php foreach($delegateTasksByProject as $project):?>

									<?php
									$startDate = date_create($project['PROJECTSTARTDATE']);
									$endDate = date_create($project['PROJECTENDDATE']);
									?>

										<?php $totalToDoProjects = $totalToDoProjects+1;?>

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
													<table class="table table-hover no-margin">
														<thead>
														<tr>
															<th width='1%'></th>
															<th width='40%'>Task</th>
															<th class="text-center">Start Date</th>
															<th class="text-center">Action</th>
														</tr>
														</thead>
														<tbody id="taskDelegateToDo">

															<?php foreach($delegateTasks as $task):?>
																<?php $startdate = date_create($task['TASKSTARTDATE']);?>

																	<?php if($task['projects_PROJECTID'] == $project['PROJECTID']):?>
																	<?php $totalToDoTasks = $totalToDoTasks+1;?>
																	<tr class="viewProject" data-id="<?php echo $task['TASKID'] ;?>">
																		<?php if($task['TASKSTATUS'] == "Planning"):?>
																			<td class = 'bg-yellow'></td>
																		<?php elseif($task['TASKSTATUS'] == "Ongoing" && $task['currentDate'] <= $task['TASKSTARTDATE']):?>
																			<td class = 'bg-green'></td>
																		<?php else:?>
																			<td class = 'bg-red'></td>
																		<?php endif;?>
																		<td class = "clickable task" data-id="<?php echo $task['TASKID'] ;?>" data-toggle="modal" data-target="#taskDetails"><?php echo $task['TASKTITLE'];?></td>
																		<td class = "clickable task" data-id="<?php echo $task['TASKID'] ;?>" data-toggle="modal" data-target="#taskDetails" align="center"><?php echo date_format($startdate, 'M d, Y');?></td>
																		<td align="center">
																			<span data-toggle="modal" data-target="#modal-delegate">
																			<button type="button" class="btn btn-primary btn-sm delegateBtn task-<?php echo $task['TASKID'];?>"
																			data-toggle="tooltip" data-placement="top" title="Delegate"
																			data-id="<?php echo $task['TASKID'];?>"
																			data-title="<?php echo $task['TASKTITLE'];?>"
																			data-start="<?php echo $task['TASKSTARTDATE'];?>"
																			data-end="<?php echo $task['TASKENDDATE'];?>">
																				<i class="fa fa-users"></i>
																			</button>
																			</span>
																			<!-- <span data-toggle="modal" data-target="#modal-accept"> -->
																			<?php if($_SESSION['usertype_USERTYPEID'] == '4'):?>
																				<button disabled id="taskAccept-<?php echo $task['TASKID'];?>" type="button" class="btn btn-success btn-sm acceptBtn"
																				data-toggle="tooltip" data-placement="top" title="Accept"
																				data-id="<?php echo $task['TASKID'];?>"
																				data-title="<?php echo $task['TASKTITLE'];?>"
																				data-start="<?php echo $task['TASKSTARTDATE'];?>"
																				data-end="<?php echo $task['TASKENDDATE'];?>">
																					<i class="fa fa-thumbs-up"></i>
																				</button>
																			<?php endif;?>
																			<!-- </span> -->
																		</td>
																	</tr>
																<?php endif;?>
															<?php endforeach;?> <!-- END SUB ACTIVITY -->

														</tbody>
													</table>
												</div>
											</div>
										</div>
								<?php endforeach;?> <!-- END PROJECT -->

							<?php endif;?>

								<?php if($totalToDoProjects == 0):?>

									<div class="box-body">
										<h4 align="center">You have no tasks to delegate</h4>
									</div>
								<?php endif;?>

							</div>
						</div>
					</div>

				<div class="col-md-2">
					<div class="box box-danger">
						<!-- /.box-header -->
						<div class="box-body">
							<div class="table-responsive">
								<h4 align="center" id="toDoProjects"> Projects <br><br><b><?php echo count($delegateTasksByProject);?></b></h4>
							</div>
						</div>
					</div>

					<div class="box box-danger">
						<!-- /.box-header -->
						<div class="box-body">
							<div class="table-responsive">
								<h4 align="center" id ="toDoTasks"> Tasks <br><br><b><?php echo count($delegateTasks);?></b></span></h4>
							</div>
						</div>
					</div>
				</div>

				</div>
			</div>


				<form id='viewProject' action = 'projectGantt' method="POST">
					<input type ='hidden' name='delegateTask' value='0'>
				</form>

				<!-- DELEGATE MODAL -->
				<div class="modal fade" id="modal-delegate">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title taskTitle">Task Name</h2>
								<h4 class="taskDates">Start Date - End Date (Days)</h4>
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
														<?php if($department['DEPARTMENTID'] != $_SESSION['departments_DEPARTMENTID'] && $department['DEPARTMENTNAME'] != 'Executive'):?>
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
													<thead>
														<tr>
															<th colspan='5'>Employee</th>
														</tr>
													</thead>

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

									<!-- /.box-body -->
								</div>
							<!-- </div> -->

							<div class="modal-footer">
								<span data-dismiss="modal">
									<button type="button" class="btn btn-default pull-left" data-toggle="tooltip" data-placement="right" title="Close">
										<i class="fa fa-close"></i>
									</button>
								</span>
								<span data-toggle="modal" data-target="#modal-delegateConfirm">
									<button type="button" class="btn btn-success delegate" data-toggle="tooltip" data-placement="left" title="Confirm Delegate">
										<i class="fa fa-check"></i>
									</button>
								</span>
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
								<button type="button" id="backWorkload" class="btn btn-default pull-left" data-toggle="tooltip" data-placement="top" title="Back"><i class="fa fa-arrow-left"></i></button>
							</div>

						</div>

						<!-- CONFIRM DELEGATE -->
						<div id="delegateConfirm">
							<div class="modal-body">
								<h4>Are you sure you want to delegate this task?</h4>
							</div>
							<div class="modal-footer">
								<button id="backConfirm" type="button" class="btn btn-default pull-left" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
								<button id = "confirmDelegateBtn" type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- ACCEPT MODAL -->
		<div class="modal fade" id="modal-accept" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h2 class="modal-title" id = "acceptTitle">Accept Task</h2>
						<h4 id="acceptDates">Start Date - End Date (Days)</h4>
					</div>
					<div class="modal-body">
						<h4 id ="early" style="margin-top:0">Are you sure you want to accept this task?</h4>
						<form id = "acceptForm" action="acceptTask" method="POST" style="margin-bottom:0;">
							<div class="modal-footer">
								<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
								<button id = "acceptConfirm" type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- END ACCEPT MODAL -->

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
						</div>
						<br><br>

						<div id="divRACI" class="divDetails">
							<table class="table no-margin table-hover">
								<thead>
									<th colspan = '4'>Current</th>
									<tr class='text-center'><td id="raciCurrentTitle" colspan='4'></td></tr>
									<tr  id="raciHeader">
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

				<!-- END MODALS -->

			</section>
		</div>

		  <?php require("footer.php"); ?>
		</div> <!--.wrapper closing div-->
		<script>
			$("#tasks").addClass("active");
			$("#taskDelegate").addClass("active");
			$("#allTasks").hide();

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

			$("body").on("click", function(){ // REMOVE ALL SELECTED IN MODAL
				if($("#modal-delegate").css("display") == 'none')
				{
					$(".radioEmp").prop("checked", false);
					$(".checkEmp").prop("checked", false);
					$("#raciDelegate").show();
					$("#workloadAssessment").hide();
				}
			});

			$("body").on("click", ".editBtn", function() {
				alert("Forward to Edit Project");
			});

			// SET BUTTON ABILITY
			$.ajax({
				type:"POST",
				url: "<?php echo base_url("index.php/controller/setDelegationRestriction"); ?>",
				dataType: 'json',
				success:function(data)
				{
					for(x=0; data['delegateTasks'].length >x; x++)
					{
						var $id = data['delegateTasks'][x].TASKID;
						$.ajax({
							type:"POST",
							url: "<?php echo base_url("index.php/controller/getRACIByTaskID"); ?>",
							data: {taskID: $id},
							dataType: 'json',
							success:function(raci)
							{
								for(t=0; raci['raci'].length >t; t++)
								{
									if(raci['raci'][t].ROLE == '2') //if task has already been delegated, enable the accept button
									{
										$("#taskAccept-" + raci['raci'][t].tasks_TASKID).prop('disabled', false);
									}
								}
							},
							error:function(data)
							{
								alert("There was a problem with loading the RACI");
							}
						});
					}
				},
				error:function(data)
				{
					alert("There was a problem with loading the Delegation Restrictions");
				}
			});

			$("body").on('click','.delegateBtn',function(){
				 var $id = $(this).attr('data-id');
				 var $title = $(this).attr('data-title');
				 var $start = new Date($(this).attr('data-start'));
				 var $end = new Date($(this).attr('data-end'));
				 var $diff = (($end - $start)/ 1000 / 60 / 60 / 24)+1;

				 $(".taskTitle").html($title);
				 $(".taskDates").html(moment($start).format('MMMM DD, YYYY') + " - " + moment($end).format('MMMM DD, YYYY') + " ("+ $diff);
				 if($diff > 1)
					$(".taskDates").append(" days)");
				 else
					$(".taskDates").append(" day)");
					$("#confirmDelegateBtn").attr("data-id", $id); //pass data id to confirm button

					// SET INITIAL RACI
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
								if(<?php echo $_SESSION['usertype_USERTYPEID'];?> == '4')
								{
									$("#user" + <?php echo $_SESSION['users_SUPERVISORS'];?> + "-1").prop('disabled', true);
								}
							}
						},
						error:function(data)
						{
							alert("There was a problem with loading the RACI");
						}
					});
			 });

			 $("#delegateConfirm").hide();
			 $("#workloadAssessment").hide();

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

				 $("#raciDelegate").hide();
				 var $id = $(this).attr('data-id');
				 var $projectCount = $(this).attr('data-projectCount');
				 var $taskCount = $(this).attr('data-taskCount');
				 $("#workloadEmployee").html($(this).attr('data-name'));
				 $("#workloadProjects").html("Total Projects: " + $projectCount);
				 $("#workloadTasks").html("Total Tasks: " + $taskCount);
				 $('#workloadDiv').html("");
				 $("#workloadAssessment").show();

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

			 $("body").on('click','.delegate',function(){
				 $("#raciDelegate").hide();
				 $("#delegateConfirm").show();
			 });

			 $("#confirmDelegateBtn").on("click", function(){
				 $(".checkEmp").prop('disabled', false);
				 $(".radioEmp").prop('disabled', false);

				 var $id = $(this).attr('data-id');
				 $("#raciForm").attr("name", "formSubmit");
				 $("#raciForm").append("<input type='hidden' name='task_ID' value= " + $id + ">");
				 $("#raciForm").submit();
			 });

			$("#backConfirm").click(function()
			{
				$("#raciDelegate").show();
				$("#delegateConfirm").hide();
			});

			// ACCEPT SCRIPT
			$("body").on('click','.acceptBtn',function(){
			 $("#modal-accept").modal('show');
			 var $id = $(this).attr('data-id');
			 var $title = $(this).attr('data-title');
			 var $start = new Date($(this).attr('data-start'));
			 var $end = new Date($(this).attr('data-end'));
			 var $diff = (($end - $start)/ 1000 / 60 / 60 / 24)+1;
			 $("#acceptTitle").html($title);
			 $("#acceptDates").html(moment($start).format('MMMM DD, YYYY') + " - " + moment($end).format('MMMM DD, YYYY') + " ("+ $diff);
			 if($diff > 1)
				 $("#acceptDates").append(" days)");
			 else
				 $("#acceptDates").append(" day)");
			 $("#acceptConfirm").attr("data-id", $id); //pass data id to confirm button
		 });

		 $("body").on('click','#acceptConfirm',function(){
			 var $id = $("#acceptConfirm").attr('data-id');
			 $("#acceptForm").attr("name", "formSubmit");
			 $("#acceptForm").append("<input type='hidden' name='task_ID' value= " + $id + ">");
		 });

		 // TASK DETAILS
		 $(document).on("click", ".task", function(){
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

					 // TASK DELEGATION
					 $("#raciCurrentTable").html("");
					 $("#raciHistoryTable").html("");
					 $('#raciHistoryTitle').hide();
					 $('#raciCurrentTitle').hide();

					 var withHistory = false;
					 var withCurrent = false;

					 for(rh=0; rh < data['raciHistory'].length; rh++)
					 {
						 if(data['raciHistory'][rh].ROLE == 1 && data['raciHistory'][rh].STATUS == 'Current')
						 {
							 withCurrent = true;
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
					 if(!withCurrent)
					 {
						 $('#raciCurrentTitle').html("There is no current RACI assignment");
						 $('#raciHeader').hide();
						 $('#raciCurrentTitle').show();
					 }
				 },
				 error:function(data)
				 {
					 alert("There was a problem with loading the task details");
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

		</script>
	</body>
</html>
