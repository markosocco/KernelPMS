<html>
	<head>
		<title>Kernel - Monitor Tasks</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/notificationsStyle.css")?>"> -->
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Monitor Tasks
					<small>What should I keep my eye on?</small>
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
				<button id = "viewFiltered" class="btn btn-default pull-right" data-toggle="tooltip" data-placement="left" title="Ongoing Tasks"><i class="fa fa-eye-slash"></i></button>

				<br><br>

				<div id = "filteredTasks">

					<div class="row">
						<!-- ONGOING-->

						<?php $delayedTasks=0;?>
						<?php if ($allOngoingACIprojects != NULL): ?>
						<div class="col-md-10">
							<div class="box box-danger">
								<div class="box-header">
									<h3 class="box-title">Ongoing Tasks</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<?php foreach($allOngoingACIprojects as $project):?>
										<?php
										$startDate = date_create($project['PROJECTSTARTDATE']);
										$endDate = date_create($project['PROJECTENDDATE']);
										?>
									<div class="box">
										<div class="box-header">
											<h3 class="box-title">
												<?php echo $project['PROJECTTITLE'];?>
												(<?php echo date_format($startDate, "F d, Y");?> - <?php echo date_format($endDate, "F d, Y");?>)
											</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body">
											<!-- <div class="table-responsive"> -->
												<table class="table table-hover no-margin" id="ongoingTaskTable">
													<thead>
													<tr>
														<th width=".5%"></th>
														<th width="4%" class="text-center">Role</th>
														<th width="40.5%">Task</th>
														<th width="12%" class="text-center">End Date</th>
														<th width="10%" class="text-center">Days Delayed</th>
														<th width="25%">Responsible</th>
														<th width="8%" class='text-center'>Action</th>
													</tr>
													</thead>
													<tbody>

														<?php foreach($uniqueOngoingACItasks as $uniqueOngoingACItask):?>
															<?php if($project['PROJECTID'] == $uniqueOngoingACItask['PROJECTID']):?>
															<?php
															if($uniqueOngoingACItask['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
																$endDate = $uniqueOngoingACItask['TASKENDDATE'];
															else
																$endDate = $uniqueOngoingACItask['TASKADJUSTEDENDDATE'];

															if($uniqueOngoingACItask['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
																$startDate = $uniqueOngoingACItask['TASKSTARTDATE'];
															else
																$startDate = $uniqueOngoingACItask['TASKADJUSTEDSTARTDATE'];

															if($uniqueOngoingACItask['TASKADJUSTEDSTARTDATE'] != null && $uniqueOngoingACItask['TASKADJUSTEDENDDATE'] != null)
																$taskDuration = $uniqueOngoingACItask['adjustedTaskDuration2'];
															elseif($uniqueOngoingACItask['TASKSTARTDATE'] != null && $uniqueOngoingACItask['TASKADJUSTEDENDDATE'] != null)
																$taskDuration = $uniqueOngoingACItask['adjustedTaskDuration1'];
															else
																$taskDuration = $uniqueOngoingACItask['initialTaskDuration'];

															$startdate = date_create($startDate);
															$enddate = date_create($endDate);
															$curdate = date_create(date('Y-m-d'));
															$diff = date_diff($startdate, $curdate);
															$delay = $diff->format("%a")+1;
															?>

															<tr>

																<?php
																$role="";
																foreach($allOngoingACItasks as $currTask)
																{
																	if($uniqueOngoingACItask['TASKID'] == $currTask['TASKID'])
																	{
																		switch($currTask['ROLE'])
																		{
																			case '2': $type = "A"; break;
																			case '3': $type = "C"; break;
																			case '4': $type = "I"; break;
																		}
																		$role .= $type;
																	}
																}
																if($role == null)
																{
																	switch($uniqueOngoingACItask['ROLE'])
																	{
																		case '2': $role = "A"; break;
																		case '3': $role = "C"; break;
																		case '4': $role = "I"; break;
																	}
																}
																?>
																<?php if($taskDuration >= $delay):?>
																	<td class="bg-green" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'></td>
																<?php else:?>
																	<td class="bg-red" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'></td>
																	<?php $delayedTasks++;?>
																<?php endif;?>

																<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'><?php echo $role;?></td>
																<td class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'><?php echo $uniqueOngoingACItask['TASKTITLE'];?></td>
																<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'><?php echo date_format($enddate, 'M d, Y');?></td>
																<?php if($delay-$taskDuration <= 0):?>
																	<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'>0</td>
																<?php else:?>
																	<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'><?php echo $delay - $taskDuration;?></td>
																<?php endif;?>
																<?php foreach($allTasks as $checkTask):?>
																	<?php if($checkTask['TASKID'] == $uniqueOngoingACItask['TASKID']):?>
																		<td class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'><?php echo $checkTask['FIRSTNAME'];?> <?php echo $checkTask['LASTNAME'];?></td>
																	<?php endif;?>
																<?php endforeach;?>
																<td align='center'>
																	<span data-toggle="modal" data-target="#modal-update"><button type="button"
																		class="btn btn-primary btn-sm updateBtn" data-id="<?php echo $uniqueOngoingACItask['TASKID'];?>"
																		data-title="<?php echo $uniqueOngoingACItask['TASKTITLE'];?>"
																		data-start="<?php echo $startDate;?>"
																		data-end="<?php echo $endDate;?>" data-toggle="tooltip" data-placement="top" title="Update">
																	<i class="fa fa-commenting"></i></button></span>
																</td>
															</tr>
														<?php endif;?>
														<?php endforeach;?>

													</tbody>
												</table>
											<!-- </div> -->
										</div>
									</div>
								<?php endforeach;?>
								</div>
							</div>
						</div>
					<?php else:?>
						<div class="col-md-10">
							<div class="box box-danger">
								<div class="box-header">
									<h3 class="box-title">Ongoing Tasks</h3>
								</div>
								<div class="box-body">
									<h4 align="center">You have no ongoing tasks</h4>
								</div>
							</div>
						</div>
					<?php endif;?>

					<div class="col-md-2">

						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Projects <br><br><b><?php echo count($allOngoingACIprojects);?></b></h4>
								</div>
							</div>
						</div>

						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Tasks <br><br><b><?php echo count($uniqueOngoingACItasks);?></b></h4>
								</div>
							</div>
						</div>

						<div class="box box-danger" id="delayedToDoBox">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Delayed <br><br><span style='color:red'><b><?php echo $delayedTasks;?></b></span></h4>
								</div>
							</div>
						</div>
					</div>
					</div> <!-- CLOSING ROW -->

				</div>

				<div id = "allTasks">

					<div class = 'row'>
						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Projects <br><br><b><?php echo count($allACIprojects);?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Tasks <br><br><b><?php echo count($uniqueCompletedACItasks)+count($uniqueOngoingACItasks)+count($uniquePlannedACItasks);?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Delayed <br><br><span style='color:red'><b><?php echo $delayedTasks;?></b></span></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Ongoing <br><br><b><?php echo count($uniqueOngoingACItasks);?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Planned <br><br><b><?php echo count($uniquePlannedACItasks);?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Completed <br><br><b><?php echo count($uniqueCompletedACItasks);?></b></h4>
									</div>
								</div>
							</div>
						</div>

					</div>

					<div class="row">
						<!-- ALL-->

						<?php if ($allACIprojects != NULL): ?>
						<div class="col-md-12">
							<div class="box box-danger">
								<div class="box-header">
									<h3 class="box-title">All Tasks</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<?php foreach($allACIprojects as $project):?>
									<?php
									$startDate = date_create($project['PROJECTSTARTDATE']);
									$endDate = date_create($project['PROJECTENDDATE']);
									?>
									<div class="box">
										<div class="box-header">
											<h3 class="box-title">
												<?php echo $project['PROJECTTITLE'];?>
												(<?php echo date_format($startDate, "F d, Y");?> - <?php echo date_format($endDate, "F d, Y");?>)
											</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body">
											<div class="table-responsive">
												<table class="table table-hover no-margin" id="allTaskTable">
													<thead>
													<tr>
														<th width="1%"></th>
														<th width="4%" class="text-center">Role</th>
														<th width="20%">Task</th>
														<th width="10%" class="text-center">Start Date</th>
														<th width="10%" class="text-center">End Date</th>
														<th width="10%" class="text-center">Actual<br>End Date</th>
														<th width="8%" class="text-center">Days Delayed</th>
														<th width="17%">Responsible</th>
														<th width="8%" class="text-center">Action</th>
													</tr>
													</thead>
													<tbody>

														<?php foreach($uniqueOngoingACItasks as $uniqueOngoingACItask):?>
															<?php if($project['PROJECTID'] == $uniqueOngoingACItask['PROJECTID']):?>
															<?php
															if($uniqueOngoingACItask['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
																$endDate = $uniqueOngoingACItask['TASKENDDATE'];
															else
																$endDate = $uniqueOngoingACItask['TASKADJUSTEDENDDATE'];

															if($uniqueOngoingACItask['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
																$startDate = $uniqueOngoingACItask['TASKSTARTDATE'];
															else
																$startDate = $uniqueOngoingACItask['TASKADJUSTEDSTARTDATE'];

															if($uniqueOngoingACItask['TASKADJUSTEDSTARTDATE'] != null && $uniqueOngoingACItask['TASKADJUSTEDENDDATE'] != null)
																$taskDuration = $uniqueOngoingACItask['adjustedTaskDuration2'];
															elseif($uniqueOngoingACItask['TASKSTARTDATE'] != null && $uniqueOngoingACItask['TASKADJUSTEDENDDATE'] != null)
																$taskDuration = $uniqueOngoingACItask['adjustedTaskDuration1'];
															else
																$taskDuration = $uniqueOngoingACItask['initialTaskDuration'];

															$startdate = date_create($startDate);
															$enddate = date_create($endDate);
															$curdate = date_create(date('Y-m-d'));
															$diff = date_diff($startdate, $curdate);
															$delay = $diff->format("%a")+1;
															?>

															<tr>

																<?php
																$role="";
																foreach($allOngoingACItasks as $currTask)
																{
																	if($uniqueOngoingACItask['TASKID'] == $currTask['TASKID'])
																	{
																		switch($currTask['ROLE'])
																		{
																			case '2': $type = "A"; break;
																			case '3': $type = "C"; break;
																			case '4': $type = "I"; break;
																		}
																		$role .= $type;
																	}
																}
																if($role == null)
																{
																	switch($uniqueOngoingACItask['ROLE'])
																	{
																		case '2': $role = "A"; break;
																		case '3': $role = "C"; break;
																		case '4': $role = "I"; break;
																	}
																}
																?>
																<?php if($taskDuration >= $delay):?>
																	<td class="bg-green" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'></td>
																<?php else:?>
																	<td class="bg-red" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'></td>
																	<?php $delayedTasks++;?>
																<?php endif;?>
																<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'><?php echo $role;?></td>
																<td class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'><?php echo $uniqueOngoingACItask['TASKTITLE'];?></td>
																<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'><?php echo date_format($startdate, 'M d, Y');?></td>
																<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'><?php echo date_format($enddate, 'M d, Y');?></td>
																<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'>-</td>
																<?php if($delay-$taskDuration <= 0):?>
																	<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'>0</td>
																<?php else:?>
																	<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'><?php echo $delay - $taskDuration;?></td>
																<?php endif;?>
																<?php foreach($allTasks as $checkTask):?>
																	<?php if($checkTask['TASKID'] == $uniqueOngoingACItask['TASKID']):?>
																		<td class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueOngoingACItask['TASKID'];?>'><?php echo $checkTask['FIRSTNAME'];?> <?php echo $checkTask['LASTNAME'];?></td>
																	<?php endif;?>
																<?php endforeach;?>																<td align='center'>
																	<span data-toggle="modal" data-target="#modal-update"><button type="button"
																		class="btn btn-primary btn-sm updateBtn" data-id="<?php echo $uniqueOngoingACItask['TASKID'];?>"
																		data-title="<?php echo $uniqueOngoingACItask['TASKTITLE'];?>"
																		data-start="<?php echo $startDate;?>"
																		data-end="<?php echo $endDate;?>" data-toggle="tooltip" data-placement="top" title="Update">
																	<i class="fa fa-commenting"></i></button></span>
																</td>
															</tr>
														<?php endif;?>
														<?php endforeach;?>

														<?php $delayedCompletedTasks = 0;?>
														<?php foreach($uniqueCompletedACItasks as $uniqueCompletedACItask):?>
															<?php if($project['PROJECTID'] == $uniqueCompletedACItask['PROJECTID']):?>
															<?php
															if($uniqueCompletedACItask['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
																$endDate = $uniqueCompletedACItask['TASKENDDATE'];
															else
																$endDate = $uniqueCompletedACItask['TASKADJUSTEDENDDATE'];

															if($uniqueCompletedACItask['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
																$startDate = $uniqueCompletedACItask['TASKSTARTDATE'];
															else
																$startDate = $uniqueCompletedACItask['TASKADJUSTEDSTARTDATE'];

															if($uniqueCompletedACItask['TASKADJUSTEDSTARTDATE'] != null && $uniqueCompletedACItask['TASKADJUSTEDENDDATE'] != null)
																$taskDuration = $uniqueCompletedACItask['adjustedTaskDuration2'];
															elseif($uniqueCompletedACItask['TASKSTARTDATE'] != null && $uniqueCompletedACItask['TASKADJUSTEDENDDATE'] != null)
																$taskDuration = $uniqueCompletedACItask['adjustedTaskDuration1'];
															else
																$taskDuration = $uniqueCompletedACItask['initialTaskDuration'];

															$startdate = date_create($startDate);
															$enddate = date_create($endDate);
															$actualEndDate = date_create($uniqueCompletedACItask['TASKACTUALENDDATE']);
															$curdate = date_create(date('Y-m-d'));
															$diff = date_diff($startdate, $curdate);
															$delay = $diff->format("%a")+1;
															?>

															<tr>

																<?php
																$role="";
																foreach($allCompletedACItasks as $currTask)
																{
																	if($uniqueCompletedACItask['TASKID'] == $currTask['TASKID'])
																	{
																		switch($currTask['ROLE'])
																		{
																			case '2': $type = "A"; break;
																			case '3': $type = "C"; break;
																			case '4': $type = "I"; break;
																		}
																		$role .= $type;
																	}
																}
																if($role == null)
																{
																	switch($uniqueCompletedACItask['ROLE'])
																	{
																		case '2': $role = "A"; break;
																		case '3': $role = "C"; break;
																		case '4': $role = "I"; break;
																	}
																}
																?>
																<td class="bg-teal" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueCompletedACItask['TASKID'];?>'></td>
																<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueCompletedACItask['TASKID'];?>'><?php echo $role;?></td>
																<td class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueCompletedACItask['TASKID'];?>'><?php echo $uniqueCompletedACItask['TASKTITLE'];?></td>
																<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueCompletedACItask['TASKID'];?>'><?php echo date_format($startdate, 'M d, Y');?></td>
																<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueCompletedACItask['TASKID'];?>'><?php echo date_format($enddate, 'M d, Y');?></td>
																<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueCompletedACItask['TASKID'];?>'><?php echo date_format($actualEndDate, 'M d, Y');?></td>

																<?php if($delay-$taskDuration <= 0):?>
																	<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueCompletedACItask['TASKID'];?>'>0</td>
																<?php else:?>
																	<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueCompletedACItask['TASKID'];?>'><?php echo $delay - $taskDuration;?></td>
																	<?php $delayedCompletedTasks = $delayedCompletedTasks+1;?>
																<?php endif;?>
																<?php foreach($allTasks as $checkTask):?>
																	<?php if($checkTask['TASKID'] == $uniqueCompletedACItask['TASKID']):?>
																		<td class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniqueCompletedACItask['TASKID'];?>'><?php echo $checkTask['FIRSTNAME'];?> <?php echo $checkTask['LASTNAME'];?></td>
																	<?php endif;?>
																<?php endforeach;?>
																<td align='center'>
																	<button disabled type="button" class="btn btn-primary btn-sm updateBtn"><i class="fa fa-commenting"></i></button>
																</td>
															</tr>
														<?php endif;?>
														<?php endforeach;?>

														<?php foreach($uniquePlannedACItasks as $uniquePlannedACItask):?>
															<?php if($project['PROJECTID'] == $uniquePlannedACItask['PROJECTID']):?>
															<?php
															if($uniquePlannedACItask['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
																$endDate = $uniquePlannedACItask['TASKENDDATE'];
															else
																$endDate = $uniquePlannedACItask['TASKADJUSTEDENDDATE'];

															if($uniquePlannedACItask['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
																$startDate = $uniquePlannedACItask['TASKSTARTDATE'];
															else
																$startDate = $uniquePlannedACItask['TASKADJUSTEDSTARTDATE'];

															$startdate = date_create($startDate);
															$enddate = date_create($endDate);
															$curdate = date_create(date('Y-m-d'));?>
															<tr>

																<?php
																$role="";
																foreach($allPlannedACItasks as $currTask)
																{
																	if($uniquePlannedACItask['TASKID'] == $currTask['TASKID'])
																	{
																		switch($currTask['ROLE'])
																		{
																			case '2': $type = "A"; break;
																			case '3': $type = "C"; break;
																			case '4': $type = "I"; break;
																		}
																		$role .= $type;
																	}
																}
																if($role == null)
																{
																	switch($uniquePlannedACItask['ROLE'])
																	{
																		case '2': $role = "A"; break;
																		case '3': $role = "C"; break;
																		case '4': $role = "I"; break;
																	}
																}
																?>
																<td class="bg-yellow" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniquePlannedACItask['TASKID'];?>'></td>
																<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniquePlannedACItask['TASKID'];?>'><?php echo $role;?></td>
																<td class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniquePlannedACItask['TASKID'];?>'><?php echo $uniquePlannedACItask['TASKTITLE'];?></td>
																<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniquePlannedACItask['TASKID'];?>'><?php echo date_format($startdate, 'M d, Y');?></td>
																<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniquePlannedACItask['TASKID'];?>'><?php echo date_format($enddate, 'M d, Y');?></td>
																<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniquePlannedACItask['TASKID'];?>'>-</td>
																<td align="center" class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniquePlannedACItask['TASKID'];?>'>0</td>
																<?php foreach($allTasks as $checkTask):?>
																	<?php if($checkTask['TASKID'] == $uniquePlannedACItask['TASKID']):?>
																		<td class = 'clickable taskDetails' data-toggle='modal' data-target='#taskDetails' data-id='<?php echo $uniquePlannedACItask['TASKID'];?>'><?php echo $checkTask['FIRSTNAME'];?> <?php echo $checkTask['LASTNAME'];?></td>
																	<?php endif;?>
																<?php endforeach;?>
																<td align='center'>
																	<span data-toggle="modal" data-target="#modal-update"><button type="button"
																		class="btn btn-primary btn-sm updateBtn" data-id="<?php echo $uniquePlannedACItask['TASKID'];?>"
																		data-title="<?php echo $uniquePlannedACItask['TASKTITLE'];?>"
																		data-start="<?php echo $startDate;?>"
																		data-end="<?php echo $endDate;?>" data-toggle="tooltip" data-placement="top" title="Update">
																	<i class="fa fa-commenting"></i></button></span>
																</td>
															</tr>
														<?php endif;?>
														<?php endforeach;?>
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
				<?php endif;?>

					</div> <!-- All tasks closing row -->

				</div>

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
										<input type='hidden' name='page' value= "taskMonitor">
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
									<table class="table table-hover no-margin">
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

									<table class="table table-hover no-margin">
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
									<table class="table table-hover no-margin">
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
									<table class="table table-hover no-margin" id='projectDelayTable'>
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

									<table class="table table-hover no-margin">
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
									<table class="table table-hover no-margin">
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

									<table class="table table-hover no-margin">
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
      $("#monitorTasks").addClass("active");
      $("#monitor").addClass("active");
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

			// $('#ongoingTaskTable').DataTable({
			// 	'paging'      : false,
			// 	'lengthChange': false,
			// 	'searching'   : true,
			// 	'ordering'    : true,
			// 	'info'        : false,
			// 	'autoWidth'   : false,
			// 	'order'				: [[ 6, "desc" ]],
			// 	'columnDefs'	: [
			// 	{
			// 		'targets'		: [ 0 ],
			// 		'orderable'	: false
			// 	} ]
			// });
			//
			// $('#allTaskTable').DataTable({
			// 	'paging'      : false,
			// 	'lengthChange': false,
			// 	'searching'   : true,
			// 	'ordering'    : true,
			// 	'info'        : false,
			// 	'autoWidth'   : false,
			// 	'order'				: false,
			// 	'columnDefs'	: [
			// 	{
			// 		'targets'		: [ 0 ],
			// 		'orderable'	: false
			// 	} ]
			// });

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
