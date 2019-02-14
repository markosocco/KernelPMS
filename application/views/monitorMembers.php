<html>
	<head>
		<title>Kernel - Monitor Members</title>

		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/monitorMembersStyle.css")?>">
	</head>
	<body class="hold-transition skin-red sidebar-mini fixed">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<?php if($_SESSION['departments_DEPARTMENTID'] == 1):?>
						<a href="<?php echo base_url("index.php/controller/monitorTeam"); ?>" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to Department"><i class="fa fa-arrow-left"></i></a>
						<br><br>
						<h1>
							Monitor Members
							<small>What's happening to the members of this department?</small>
						</h1>
					<?php else:?>
						<a href="<?php echo base_url("index.php/controller/monitorTeam"); ?>" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to My Team"><i class="fa fa-arrow-left"></i></a>
						<br><br>
						<h1>
							Monitor Members
							<small>What's happening to the members of my team?</small>
						</h1>
					<?php endif;?>

					<ol class="breadcrumb">
						<?php $dateToday = date('F d, Y | l');?>
						<p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
					</ol>
					<div class="col-md-4 col-sm-6 col-xs-12 pull-right">
              <div class="box-header with-border" style="text-align:center;">
                <h3 class="box-title">Performance</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <div style="display:inline-block; text-align:center; width:49%;">
                  <div class="circlechart"
                    data-percentage="<?php
											if($completeness['completeness'] == NULL){
												echo 0;
											} else {
												if($completeness['completeness'] == 100.00){
													echo 100;
												} elseif ($completeness['completeness'] == 0.00) {
													echo 0;
												} else {
													echo $completeness['completeness'];
												}
											}
											?>">Completeness
                  </div>
                </div>
                <div style="display:inline-block; text-align:center; width:49%;">
                  <div class="circlechart"
                   data-percentage="<?php
										 if($timeliness['timeliness'] == NULL){
											 echo 0;
										 } else {
											 if($timeliness['timeliness'] == 100.00){
												 echo 100;
											 } elseif ($timeliness['timeliness'] == 0.00) {
												 echo 0;
											 } else {
												 echo $timeliness['timeliness'];
											 }
										 }
										 ?>">Timeliness
                 </div>
               </div>
              </div>
          </div>
          <!-- /.col -->
				</section>
				<!-- Main content -->
				<section class="content container-fluid">
					<!-- START HERE -->
          <h3><?php echo $user['FIRSTNAME'] . " " . $user['LASTNAME']; ?></h3>
          <h4><?php echo $user['POSITION']; ?></h4>

					<div class = 'row'>

						<?php $projCount = 0;?>
						<?php foreach ($pCount as $p): ?>
						<?php  if ($p['USERID'] == $user['USERID']): ?>
							<?php $projCount = $p['PROJECTCOUNT']; ?>
						<?php endif; ?>
					<?php endforeach; ?>

					<?php $taskCount = 0;?>
					<?php foreach ($tCount as $t): ?>
					<?php  if ($t['users_USERID'] == $user['USERID']): ?>
						<?php $taskCount =  $t['TASKCOUNT']; ?>
					<?php endif; ?>
				<?php endforeach; ?>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center" id="total"> Ongoing Projects <br><br><b><?php echo $projCount;?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Ongoing Tasks <br><br><b><?php echo $taskCount;?></b></h4>
									</div>
								</div>
							</div>
						</div>
					</div>

          <div class="box box-danger">
            <div class="box-header with-border">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- START LOOP HERE -->
							<?php if($projects == NULL): ?>
								<h3 class = "projects" align="center">There are no projects</h3>
							<?php else: ?>
              <?php foreach ($projects as $row): ?>
								<?php
								$startDate = date_create($row['PROJECTSTARTDATE']);
								$endDate = date_create($row['PROJECTENDDATE']);
								?>
								<div class="box">
									<div class="box-header with-border">
										<h3 class="box-title">
											<?php echo $row['PROJECTTITLE'];?>
											(<?php echo date_format($startDate, "F d, Y");?> - <?php echo date_format($endDate, "F d, Y");?>)
										</h3>
									</div>
										<div class="box-body">
											<div class='table-responsive'>

												<table class="table table-hover no-margin">
					                <thead>
					                  <tr>
															<th width=".5%"></th>
					                    <th width="27%">Task</th>
					                    <th width="10%" class="text-center">Start Date</th>
					                    <th width="10%" class="text-center">Target<br>End Date</th>
					                    <th class="text-center" width="17.5%">A</th>
					                    <th class="text-center" width="17.5%">C</th>
					                    <th class="text-center" width="17.5%">I</th>
															<th class="text-center">Action</th>
					                  </tr>
					                </thead>
					                <tbody>
					                  <?php foreach ($tasks as $t): ?>
															<?php if ($row['PROJECTID'] == $t['PROJECTID']): ?>
																<?php
																if($t['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
																	$startDate = date_create($t['TASKSTARTDATE']);
																else
																	$startDate = date_create($t['TASKADJUSTEDSTARTDATE']);

																if($t['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
																	$endDate = $t['TASKENDDATE'];
																else
																	$endDate = $t['TASKADJUSTEDENDDATE'];

																if($endDate < $t['currDate'] && $t['TASKSTATUS'] == 'Ongoing')
																	$delay = "true";
																else {
																	$delay = "false";
																}
																?>
																<tr>
																	<?php if ($t['TASKSTATUS'] == 'Ongoing'): ?>
																		<?php if($endDate >= $t['currDate']):?>
																			<td data-toggle='modal' data-target='#taskDetails'
																			class='clickable task bg-green' data-id="<?php echo $t['TASKID'];?>" data-delay="<?php echo $delay;?>"></td>
																		<?php else:?>
																			<td data-toggle='modal' data-target='#taskDetails'
																			class='clickable task bg-red' data-id="<?php echo $t['TASKID'];?>" data-delay="<?php echo $delay;?>"></td>
																		<?php endif;?>
																	<?php elseif ($t['TASKSTATUS'] == 'Planning'): ?>
																		<td data-toggle='modal' data-target='#taskDetails'
																		class='clickable task bg-yellow' data-id="<?php echo $t['TASKID'];?>" data-delay="<?php echo $delay;?>"></td>
																	<?php elseif ($t['TASKSTATUS'] == 'Complete'): ?>
																		<td data-toggle='modal' data-target='#taskDetails'
																		class='clickable task bg-teal' data-id="<?php echo $t['TASKID'];?>" data-delay="<?php echo $delay;?>"></td>
																	<?php else: ?>
																		<td data-toggle='modal' data-target='#taskDetails' class='clickable task' data-id="<?php echo $t['TASKID'];?>" data-delay="<?php echo $delay;?>"></td>
																	<?php endif; ?>
																	<!-- <td></td> -->
																	<td data-toggle='modal' data-target='#taskDetails' class='clickable task' data-id="<?php echo $t['TASKID'];?>" data-delay="<?php echo $delay;?>">
																		<?php echo $t['TASKTITLE']; ?>
																	</td>

																	<?php
																		if($t['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
																			$endDate = $t['TASKENDDATE'];
																		else
																			$endDate = $t['TASKADJUSTEDENDDATE'];

																		if($t['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
																			$startDate = $t['TASKSTARTDATE'];
																		else
																			$startDate = $t['TASKADJUSTEDSTARTDATE'];
																	?>

							                    <td data-toggle='modal' data-target='#taskDetails' class='clickable task' data-id="<?php echo $t['TASKID'];?>" data-delay="<?php echo $delay;?>" align='center'>
																		<?php echo date_format(date_create($startDate), "M d, Y"); ?>
																	</td>
							                    <td data-toggle='modal' data-target='#taskDetails' class='clickable task' data-id="<?php echo $t['TASKID'];?>" data-delay="<?php echo $delay;?>" align='center'>
																		<?php echo date_format(date_create($endDate), "M d, Y"); ?>
																	</td>
							                    <td data-toggle='modal' data-target='#taskDetails' class='clickable task' data-id="<?php echo $t['TASKID'];?>" data-delay="<?php echo $delay;?>">
																		<?php foreach ($raci as $raciRow): ?>
																			<?php if ($t['TASKID'] == $raciRow['TASKID']): ?>
																				<?php if ($raciRow['ROLE'] == '2'): ?>
																					<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
																				<?php endif; ?>
																			<?php endif; ?>
																		<?php endforeach; ?>
																	</td>
							                    <td data-toggle='modal' data-target='#taskDetails' class='clickable task' data-id="<?php echo $t['TASKID'];?>" data-delay="<?php echo $delay;?>">
																		<?php foreach ($raci as $raciRow): ?>
																			<?php if ($t['TASKID'] == $raciRow['TASKID']): ?>
																				<?php if ($raciRow['ROLE'] == '3'): ?>
																					<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
																				<?php endif; ?>
																			<?php endif; ?>
																		<?php endforeach; ?>
																	</td>
							                    <td data-toggle='modal' data-target='#taskDetails' class='clickable task' data-id="<?php echo $t['TASKID'];?>" data-delay="<?php echo $delay;?>">
																		<?php foreach ($raci as $raciRow): ?>
																			<?php if ($t['TASKID'] == $raciRow['TASKID']): ?>
																				<?php if ($raciRow['ROLE'] == '4'): ?>
																					<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
																				<?php endif; ?>
																			<?php endif; ?>
																		<?php endforeach; ?>
																	</td>
																	<td align='center'>
																		<?php if($t['TASKSTATUS'] != 'Complete'):?>
																		<span data-toggle="modal" data-target="#modal-delegate">
																		<button type="button" class="btn btn-primary btn-sm delegateBtn task-<?php echo $t['TASKID'];?>"
																		data-toggle="tooltip" data-placement="top" title="Delegate"
																		data-id="<?php echo $t['TASKID'];?>"
																		data-title="<?php echo $t['TASKTITLE'];?>"
																		data-start="<?php echo $t['TASKSTARTDATE'];?>"
																		data-end="<?php echo $t['TASKENDDATE'];?>">
																			<i class="fa fa-users"></i>
																		</button>
																		</span>
																	<?php else:?>
																		<button type="button" class="btn btn-primary btn-sm" disabled><i class="fa fa-users"></i></button>
																	<?php endif;?>
																</td>
							                  </tr>
															<?php endif; ?>
														<?php endforeach; ?>
					                </tbody>
					              </table>

											</div>
										</div>
									</div>
							<?php endforeach; ?>
						<?php endif; ?>
              <!-- END LOOP HERE -->
            </div>
            <!-- /.box-body -->
          </div>

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
												<input type='hidden' name='employee_ID' value="<?php echo $user['USERID'];?>">

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
														<?php foreach($users as $currentUser):?>
															<?php if($currentUser['departments_DEPARTMENTID'] == '1'):?>
															<tr>
																<td><?php echo $currentUser['FIRSTNAME'] . " " .  $currentUser['LASTNAME'];?></td>
																<td class='text-center'>
																	<div class="radio">
																	<label>
																		<input id='user<?php echo $currentUser['USERID'];?>-1' class = "radioEmp" type="radio" name="responsibleEmp" value="<?php echo $currentUser['USERID'];?>" disabled>
																	</label>
																</div>
																</td>
																<td class='text-center'>
																	<div class="checkbox">
																	<label>
																		<input id='user<?php echo $currentUser['USERID'];?>-2' class = "checkEmp" type="checkbox" name="accountableEmp[]" value="<?php echo $currentUser['USERID'];?>" required>
																	</label>
																</div>
																</td>
																<td class='text-center'>
																	<div class="checkbox">
																	<label>
																		<input id='user<?php echo $currentUser['USERID'];?>-3' class = "checkEmp" type="checkbox" name="consultedEmp[]" value="<?php echo $currentUser['USERID'];?>" required>
																	</label>
																</div>
																</td>
																<td class='text-center'>
																	<div class="checkbox">
																	<label>
																		<input id='user<?php echo $currentUser['USERID'];?>-4' class = "checkEmp" type="checkbox" name="informedEmp[]" value="<?php echo $currentUser['USERID'];?>" required>
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

																<?php foreach($taskCounts as $count): ;?>
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
										<button type="button" id = "tabDelay" class="btn btn-default tabDetails">Delay</button>
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
				<!-- /.content -->
			</div>
			<?php require("footer.php"); ?>
		</div>
		<!-- ./wrapper -->
		<script>
			$("#monitor").addClass("active");
			$("#monitorTeam").addClass("active");
			$("#monitorDepartments").addClass("active");
      $('.circlechart').circlechart(); // Initialization

			$(document).on("click", ".task", function(){
				$(".divDetails").hide();
				$(".tabDetails").removeClass('active');
				$("#tabDependency").addClass("active");
				$("#divDependency").show();

				var $taskID = $(this).attr('data-id');
				var $isDelayed = $(this).attr('data-delay');

				if($isDelayed == 'true'){
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

			// DELEGATE
			$("body").on("click", function(){ // REMOVE ALL SELECTED IN MODAL
				if($("#modal-delegate").css("display") == 'none')
				{
					$(".radioEmp").prop("checked", false);
					$(".checkEmp").prop("checked", false);
					$("#raciDelegate").show();
					$("#workloadAssessment").hide();
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
				 $("#raciForm").append("<input type='hidden' name='reassigned' value='1'>");
				 $("#raciForm").submit();
			 });

			$("#backConfirm").click(function()
			{
				$("#raciDelegate").show();
				$("#delegateConfirm").hide();
			});
		</script>
	</body>
</html>
