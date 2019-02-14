<html>
	<head>
		<title>Kernel - Project Summary</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/projectSummaryStyle.css")?>">
	</head>
	<body class="hold-transition skin-red sidebar-mini sidebar-collapse fixed">

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div style="margin-bottom:10px">
					<form action = 'projectGantt' id="back" method="POST" style="display:inline-block">
					</form>
					<a id ="backToProject" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to Project"><i class="fa fa-arrow-left"></i></a>
				</div>
				<h1>
					<?php echo $project['PROJECTTITLE']; ?> - Project Summary
					<!-- <small>What can I improve on the next project?</small> -->
					<small>What happened to this project?</small>
				</h1>

				<ol class="breadcrumb">
          <?php $dateToday = date('F d, Y | l');?>
          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
        </ol>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<div class="row">
					<div class="col-md-9 col-sm-6 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">Statistics</h3>
							</div>

							<!-- /.box-header -->
							<div class="box-body">
								<table>
									<tbody>
										<?php
										$actualenddate = date_create($project['PROJECTACTUALENDDATE']);
										?>
										<?php

											$delayCounter = 0;
											$earlyCounter = 0;

											foreach ($groupedTasks as $row)
											{
												if($row['TASKADJUSTEDENDDATE'] == null)
													$endDate = $row['TASKENDDATE'];
												else
													$endDate = $row['TASKADJUSTEDENDDATE'];

												if ($row['TASKACTUALENDDATE'] < $endDate)
													$earlyCounter++;

												if ($row['TASKACTUALENDDATE'] > $endDate)
													$delayCounter++;
											}
										?>
										<?php

											$approvedCounter = 0;
											$deniedCounter = 0;
											$pendingCounter = 0;
											$dateCounter = 0;
											$performerCounter = 0;

											foreach ($changeRequests as $request)
											{
												if($request['REQUESTSTATUS'] == 'Approved' )
													$approvedCounter++;
												if($request['REQUESTSTATUS'] == 'Denied' )
													$deniedCounter++;
												if($request['REQUESTSTATUS'] == 'Pending' )
													$pendingCounter++;

												if($request['REQUESTTYPE'] == '1' )
													$performerCounter++;
												else
													$dateCounter++;
											}
										?>
										<tr>
											<th width="25.33%"></th>
											<th width="25.33%"></th>
											<th width="25.33%"></th>
										</tr>
										<tr>
											<td><p>Date of Completion: <b><?php echo date_format($actualenddate, "F d, Y"); ?></b></p></td>
											<td><p>Total number of main activities: <b><?php echo count($mainActivity); ?></b></p></td>
											<td><p>Total number of requests: <b><?php echo count($changeRequests);?></b></p></td>
										</tr>
										<tr>
											<td><p>Total number of days: <b><?php echo $project['actualDuration']; ?></b></p></td>
											<td><p>Total number of sub activities: <b><?php echo count($subActivity); ?></b></p></td>
											<td><p style="text-indent:5%">Change Performer: <b><?php echo $performerCounter;?></b></p></td>
										</tr>
										<tr>
											<td><p>Departments involved: <b><?php echo count($departments);?></b></p></td>
											<td><p>Total number of tasks: <b><?php echo count($tasks); ?></b></p></td>
											<td><p style="text-indent:5%">Change End Date: <b><?php echo $dateCounter;?></b></p></td>
										</tr>
										<tr>
											<td><p>People involved: <b><?php echo count($team);?></b></p></td>
											<td><p>Total number of delayed tasks:<b> <?php echo $delayCounter;?></b></p></td>
											<td><p>Total number of approved requests: <b><?php echo $approvedCounter;?></b></p></td>
										</tr>
										<tr>
											<td><p>Total number of documents: <b><?php echo count($documents);?></b></p></td>
											<td><p>Total number of early tasks:<b> <?php echo $earlyCounter;?></b></p></td>
											<td><p>Total number of denied requests: <b><?php echo $deniedCounter;?></b></p></td>
										</tr>
										<tr>
											<td></td>
											<td></td>
											<td><p>Total number of missed requests: <b><?php echo $pendingCounter;?></b></p></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
	        <!-- /.col -->
					<div class="col-md-3 col-sm-3 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">Project Performance</h3>
							</div>
							<!-- /.box-header -->

							<div class="box-body">
								<div style="text-align:center;">
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
					</div>
	        <!-- /.col -->
				</div>

				<div class="row">

				</div>

				<!-- ALL DEPARTMENTS INVOLVED IN THE PROJECT -->
				<div class="row" id="deptPerformance">

					<?php foreach($departments as $department):?>
						<div class="col-md-3 col-sm-3 col-xs-12">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title"><?php echo $department['DEPARTMENTNAME'];?></h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div style="text-align:center;">
										<div class="circlechart" id="completeness"
										data-percentage="<?php
 										 if($department['timeliness'] == NULL){
 											 echo 0;
 										 } else {
 											 if($department['timeliness'] == 100.00){
 												 echo 100;
 											 } elseif ($department['timeliness'] == 0.00) {
 												 echo 0;
 											 } else {
 												 echo $department['timeliness'];
 											 }
 										 }
 										 ?>">Timeliness</div>
			 					 </div>
								</div>
							</div>
		        </div>
					<?php endforeach;?>

				</div>


				<!-- TEAM MEMBERS -->
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">Team Members</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<table class="table table-hover no-margin" id="">
									<thead>
										<tr>
											<th>Name</th>
											<th>Department</th>
											<th class='text-center'>Total Tasks</th>
											<th class='text-center'>Timeliness</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($team as $member):?>

											<?php $numTasks = 0;?>
											<?php $timeliness = 0;?>

											<?php foreach($taskCount as $count)
												if($count['USERID'] == $member['USERID'])
													$numTasks = $count['taskCount'];
											?>

											<?php foreach($employeeTimeliness as $empTimeliness)
												if($empTimeliness['USERID'] == $member['USERID'])
													$timeliness = $empTimeliness['timeliness'];
											?>
											<?php if($timeliness == 100.00)
											 	$timeliness = 100;
											?>

											<tr>
												<td><?php echo $member['FIRSTNAME'];?> <?php echo $member['LASTNAME'];?></td>
												<td><?php echo $member['DEPARTMENTNAME'];?></td>
												<td class='text-center'><?php echo $numTasks;?></td>
												<td class='text-center'><?php echo $timeliness;?>%</td>
											</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
	        </div>
	        <!-- /.col -->
				</div>

				<!-- DELAYED TASKS -->
				<?php if($delayedTasks != null):?>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h5 class="box-title">Delayed Tasks</h5>
							</div>
							<!-- /.box-header -->
							<div class="box-body" id="delayedBox">
								<table class="table table-bordered table-condensed" id="delayedTable">
									<thead>
										<tr>
											<th width="20%">Task</th>
											<th width="10%" class='text-center'>Target<br>End Date</th>
											<th width="10%" class='text-center'>Actual<br>End Date</th>
											<th width="5%" class='text-center'>Days Delayed</th>
											<th width="15%">Responsible</th>
											<th width="15%" class='text-center'>Department</th>
											<th width="25">Reason</th>
										</tr>
									</thead>
									<tbody id="delayedData">
										<?php foreach ($delayedTasks as $task):?>
											<?php
											if($task['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
											{
												$endDate = $task['TASKENDDATE'];
												$delay = $task['actualInitial'];
											}
											else
											{
												$endDate = $task['TASKADJUSTEDENDDATE'];
												$delay = $task['actualAdjusted'];
											}?>

											<?php if($task['TASKACTUALENDDATE'] > $endDate):?>
											<tr>
												<td><?php echo $task['TASKTITLE'];?></td>
												<td class='text-center'><?php echo date_format(date_create($endDate), "M d, Y");?></td>
												<td class='text-center'><?php echo date_format(date_create($task['TASKACTUALENDDATE']), "M d, Y");?></td>
												<td align="center"><?php echo $delay;?></td>
												<td><?php echo $task['FIRSTNAME'];?> <?php echo $task['LASTNAME'];?></td>
												<td class='text-center'><?php echo $task['DEPARTMENTNAME'];?></td>
												<td><?php echo $task['TASKREMARKS'];?></td>
											</tr>
										<?php endif;?>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- /.col -->
				</div>
			<?php else:?>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h5 class="box-title">Delayed Tasks</h5>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<h6 align="center">There were no delayed tasks</h6>
							</div>
						</div>
					</div>
					<!-- /.col -->
				</div>
			<?php endif;?>

				<!-- EARLY TASKS -->
				<?php if($earlyTasks != null):?>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">Early Tasks</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<table class="table table-hover no-margin" id="">
									<thead>
										<tr>
											<th width="20%">Task</th>
											<th width="15%">Responsible</th>
											<th width="15%" class='text-center'>Department</th>
											<th width="10%" class='text-center'>Target<br>End Date</th>
											<th width="10%" class='text-center'>Actual<br>End Date</th>
											<th width="5%" class='text-center'>Days Early</th>
											<th width="25">Reason</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($earlyTasks as $task):?>
											<?php
											if($task['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
											{
												$endDate = $task['TASKENDDATE'];
												$early = $task['actualInitial'];
											}
											else
											{
												$endDate = $task['TASKADJUSTEDENDDATE'];
												$early = $task['actualAdjusted'];
											}
											?>

											<?php if($task['TASKACTUALENDDATE'] < $endDate):?>
												<tr>
													<td><?php echo $task['TASKTITLE'];?></td>
													<td><?php echo $task['FIRSTNAME'];?> <?php echo $task['LASTNAME'];?></td>
													<td class='text-center'><?php echo $task['DEPARTMENTNAME'];?></td>
													<td class='text-center'><?php echo date_format(date_create($endDate), "M d, Y");?></td>
													<td class='text-center'><?php echo date_format(date_create($task['TASKACTUALENDDATE']), "M d, Y");?></td>
													<td align="center"><?php echo $early;?></td>
													<td><?php echo $task['TASKREMARKS'];?></td>
												</tr>
											<?php endif;?>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
	        </div>
	        <!-- /.col -->
				</div>
				<?php else:?>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">Early Tasks</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<h4 align="center">There were no early tasks</h4>
								</div>
							</div>
						</div>
						<!-- /.col -->
					</div>
				<?php endif;?>

				<!-- Requests -->
				<?php if($changeRequests != null):?>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">Change Requests</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<table class="table table-hover no-margin" id="">
									<thead>
										<tr>
											<th width="0%" class='text-center'>Type</th>
											<th width="0%">Task</th>
											<th width="0%">Requested By</th>
											<th width="0%" class='text-center'>Department</th>
											<th width="0%" class='text-center'>Date Requested</th>
											<th width="0">Reason</th>
											<th width="0%" class='text-center'>Status</th>
											<th width="0%">Reviewed By</th>
											<th width="0%" class='text-center'>Date Approved</th>
											<th width="0">Remarks</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($changeRequests as $request):?>
											<tr>
												<?php if($request['REQUESTTYPE'] == '1')
														$type = "<i class='fa fa-user-times'></i>";
													else
														$type = "<i class='fa fa-calendar'></i>";
													?>

												<?php foreach($users as $user)
													if($user['USERID'] == $request['users_REQUESTEDBY'])
													{
														$requester = $user['FIRSTNAME'] . " " . $user['LASTNAME'];
														foreach($allDepartments as $dept)
														{
															if($user['departments_DEPARTMENTID'] == $dept['DEPARTMENTID'])
															$deptName = $dept['DEPARTMENTNAME'];
														}
													}
													else if($user['USERID'] == $request['users_APPROVEDBY'])
													{
														$approver = $user['FIRSTNAME'] . " " . $user['LASTNAME'];
													}
												?>

												<?php
												$requestdate = date_create($request['REQUESTEDDATE']);
												$approveddate = date_create($request['APPROVEDDATE']);
												?>

												<td class='text-center'><?php echo $type;?></td>
												<td><?php echo $request['TASKTITLE'];?></td>
												<td><?php echo $requester;?></td>
												<td class='text-center'><?php echo $deptName;?></td>
												<td class='text-center'><?php echo date_format($requestdate, "M d, Y");?></td>
												<td><?php echo $request['REASON'];?></td>
												<td class='text-center'><?php echo $request['REQUESTSTATUS'];?></td>
												<?php if($request['REQUESTSTATUS'] == 'Pending'):?>
													<td align='center'>-</td>
												<?php else:?>
													<td><?php echo $approver;?></td>
												<?php endif;?>
												<td class='text-center'><?php echo date_format($approveddate, "M d, Y");?></td>
												<?php if($request['REMARKS'] == ""):?>
												<td align="center">-</td>
												<?php else:?>
												<td><?php echo $request['REMARKS'];?></td>
												<?php endif;?>
											</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
	        </div>
	        <!-- /.col -->
				</div>
			<?php else:?>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">Change Requests</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<h4 align="center">There were no change requests</h4>
							</div>
						</div>
	        </div>
	        <!-- /.col -->
				</div>
			<?php endif;?>

			</section>
		</div>
		  <?php require("footer.php"); ?>
		</div> <!--.wrapper closing div-->
		<script>
		  $("#myProjects").addClass("active");
			$('.circlechart').circlechart(); // Initialization

			$(document).on("click", "#backToProject", function() {
				var $id = <?php echo $project['PROJECTID']; ?>;
				$("#back").attr("name", "formSubmit");
				$("#back").append("<input type='hidden' name='project_ID' value= " + $id + ">");
				$("#back").submit();
				});

		</script>



	</body>
</html>
