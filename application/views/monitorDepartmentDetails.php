<html>
	<head>
		<title>Kernel - Monitor Department Details</title>

		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/monitorMembersStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini fixed">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">

					<button id="backBtn" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to Departments"><i class="fa fa-arrow-left"></i></button>
					<form id="backForm" action = 'monitorDepartment' method="POST" data-id="<?php echo $projectProfile['PROJECTID']; ?>">
					</form>
					<h1>
						Monitor Department
						<small>What's happening to this department in this project?</small>
					</h1>
					<br>
					<h1>
						<?php echo $projectProfile['PROJECTTITLE'];?>
						<?php
						$projectStart = date_create($projectProfile['PROJECTSTARTDATE']);
						$projectEnd = date_create($projectProfile['PROJECTENDDATE']);
						?>
						<small>(<?php echo date_format($projectStart, "F d, Y");?> to <?php echo date_format($projectEnd, "F d, Y");?>)</small>
					</h1>

					<ol class="breadcrumb">
	          <?php $dateToday = date('F d, Y | l');?>
	          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
	        </ol>
				</section>
				<!-- Main content -->
				<section class="content container-fluid">
					<!-- START HERE -->

					<div class = 'row'>
						<?php
						$completed = 0;
						$planned = 0;
						$ongoing = 0;
						$delayed = 0;
						?>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center" id="total"> Total <br><br><b><?php echo count ($tasks);?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<?php foreach($tasks as $task)
							if($task['TASKSTATUS'] == 'Complete')
								$completed++;
							elseif($task['TASKSTATUS'] == 'Planning')
								$planned++;
							elseif($task['TASKSTATUS'] == 'Ongoing')
							{
								$ongoing++;

								if($task['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
									$endDate = $task['TASKENDDATE'];
								else
									$endDate = $task['TASKADJUSTEDENDDATE'];

								if($endDate < $task['currDate'])
									$delayed++;
							}
						?>


						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Delayed <br><br><b><span style='color:red'><?php echo $delayed ;?></span></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Ongoing <br><br><b><?php echo $ongoing ;?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Planned <br><br><b><?php echo $planned ;?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Completed <br><br><b><?php echo $completed ;?></b></h4>
									</div>
								</div>
							</div>
						</div>

					</div>

					<?php if($_SESSION['byDepartment'] == 'false'):?>

					<div class="box box-danger">
  					<div class="box-body">

            <?php foreach($mainActivities as $mainActivity):?>
							<?php
								$startDate = date_create($mainActivity['TASKSTARTDATE']);

								if($mainActivity['TASKADJUSTEDENDDATE'] == "")
									$endDate = date_create($mainActivity['TASKENDDATE']);
								else
									$endDate = date_create($mainActivity['TASKADJUSTEDENDDATE']);
							?>
              <div class="box">
								<div class="box-header">
									<h3 class="box-title">
										<?php echo $mainActivity['TASKTITLE'];?>
										(<?php echo date_format($startDate, "F d, Y");?> - <?php echo date_format($endDate, "F d, Y");?>)
									</h3>
								</div>
        					<div class="box-body">
                    <table class="table no-margin table-hover">
        							<thead>
                      <tr>
                        <th colspan = '8'></th>
                      </tr>
        							<tr>
                        <th width='.5%'></th>
                        <th width="30.5%">Task</th>
                        <th width="10%" class='text-center'>Start Date</th>
                        <th width="10%" class='text-center'>Target<br>End Date</th>
                        <th width="12%" class='text-center'>R</th>
                        <th width="12%" class='text-center'>A</th>
                        <th width="12%" class='text-center'>C</th>
                        <th width="12%" class='text-center'>I</th>
        							</tr>
        							</thead>
        							<tbody>
                        <?php foreach($subActivities as $subActivity):?>
                          <?php if($subActivity['tasks_TASKPARENT'] == $mainActivity['TASKID']):?>
														<?php
															$startDate = date_create($subActivity['TASKSTARTDATE']);

															if($subActivity['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
																$endDate = date_create($subActivity['TASKENDDATE']);
															else
																$endDate = date_create($subActivity['TASKADJUSTEDENDDATE']);
														?>
                            <tr>
                              <td colspan = '9'><i><?php echo $subActivity['TASKTITLE'];?> (<?php echo date_format($startDate, "F d, Y");?> - <?php echo date_format($endDate, "F d, Y");?>)</i></td>
                            </tr>
                          <?php foreach($tasks as $task):?>
                            <?php if($task['tasks_TASKPARENT'] == $subActivity['TASKID']):?>
                              <?php
          										if($task['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
          											$startDate = date_create($task['TASKSTARTDATE']);
          										else
          											$startDate = date_create($task['TASKADJUSTEDSTARTDATE']);

          										if($task['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
          											$endDate = $task['TASKENDDATE'];
          										else
          											$endDate = $task['TASKADJUSTEDENDDATE'];

          										if($endDate < $task['currDate'] && $task['TASKSTATUS'] == 'Ongoing')
          											$delay = "true";
          										else {
          											$delay = "false";
          										}
          										?>
                              <tr>
                                <?php if($endDate < $task['currDate'] && $task['TASKSTATUS'] == 'Ongoing'):?>
                                  <td class="clickable task bg-red" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails"></td>
                                <?php elseif($endDate >= $task['currDate'] && $task['TASKSTATUS'] == 'Ongoing'):?>
                                  <td class="clickable task bg-green" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails"></td>
                                <?php elseif($task['TASKSTATUS'] == 'Complete'):?>
                                  <td class="clickable task bg-teal" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails"></td>
                                <?php elseif($task['TASKSTATUS'] == 'Planning'):?>
                                  <td class="clickable task bg-yellow" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails"></td>
                                <?php endif;?>
																<td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails"><?php echo $task['TASKTITLE'];?></td>
                                <td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails" align='center'><?php echo date_format($startDate, "M d, Y"); ?></td>
                                <td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails" align='center'><?php echo date_format(date_create($endDate), "M d, Y"); ?></td>
                                <td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails">
            											<?php foreach ($raci as $raciRow): ?>
            												<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
            													<?php if ($raciRow['ROLE'] == '1'): ?>
            														<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
            													<?php endif; ?>
            												<?php endif; ?>
            											<?php endforeach; ?>
            										</td>
                                <td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails">
            											<?php foreach ($raci as $raciRow): ?>
            												<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
            													<?php if ($raciRow['ROLE'] == '2'): ?>
            														<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
            													<?php endif; ?>
            												<?php endif; ?>
            											<?php endforeach; ?>
            										</td>
                                <td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails">
            											<?php foreach ($raci as $raciRow): ?>
            												<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
            													<?php if ($raciRow['ROLE'] == '3'): ?>
            														<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
            													<?php endif; ?>
            												<?php endif; ?>
            											<?php endforeach; ?>
            										</td>
                                <td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails">
            											<?php foreach ($raci as $raciRow): ?>
            												<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
            													<?php if ($raciRow['ROLE'] == '4'): ?>
            														<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
            													<?php endif; ?>
            												<?php endif; ?>
            											<?php endforeach; ?>
            										</td>
                              </tr>
                            <?php endif;?>
                          <?php endforeach;?> <!-- TASKS -->
													<?php endif;?>
                        <?php endforeach;?> <!-- SUBACTIVITY -->
        							</tbody>
        						</table>
        					</div>
      				</div>
            <?php endforeach;?> <!-- MAINACTIVITY -->
  					</div>
  				</div>
				<?php else:?>

					<div class="box box-danger">
						<div class="box-header">
						</div>
						<div class="box-body">
							<table class="table no-margin table-hover">
								<thead>
								<tr>
									<th width='.5%'></th>
									<th width="30.5%">Task</th>
									<th width="10%" class='text-center'>Start Date</th>
									<th width="10%" class='text-center'>Target<br>End Date</th>
									<th width="12%" class='text-center'>R</th>
									<th width="12%" class='text-center'>A</th>
									<th width="12%" class='text-center'>C</th>
									<th width="12%" class='text-center'>I</th>
								</tr>
								</thead>
								<tbody>
								<?php foreach($tasks as $task):?>
										<?php
										if($task['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
											$startDate = date_create($task['TASKSTARTDATE']);
										else
											$startDate = date_create($task['TASKADJUSTEDSTARTDATE']);

										if($task['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
											$endDate = $task['TASKENDDATE'];
										else
											$endDate = $task['TASKADJUSTEDENDDATE'];

										if($endDate < $task['currDate'] && $task['TASKSTATUS'] == 'Ongoing')
											$delay = "true";
										else {
											$delay = "false";
										}
										?>
										<tr>
											<?php if($endDate < $task['currDate'] && $task['TASKSTATUS'] == 'Ongoing'):?>
												<td class="clickable task bg-red" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails"></td>
											<?php elseif($endDate >= $task['currDate'] && $task['TASKSTATUS'] == 'Ongoing'):?>
												<td class="clickable task bg-green" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails"></td>
											<?php elseif($task['TASKSTATUS'] == 'Complete'):?>
												<td class="clickable task bg-teal" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails"></td>
											<?php elseif($task['TASKSTATUS'] == 'Planning'):?>
												<td class="clickable task bg-yellow" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails"></td>
											<?php endif;?>
											<td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails"><?php echo $task['TASKTITLE'];?></td>
											<td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails" align='center'><?php echo date_format($startDate, "M d, Y"); ?></td>
											<td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails" align='center'><?php echo date_format(date_create($endDate), "M d, Y"); ?></td>
											<td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails">
												<?php foreach ($raci as $raciRow): ?>
													<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
														<?php if ($raciRow['ROLE'] == '1'): ?>
															<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
														<?php endif; ?>
													<?php endif; ?>
												<?php endforeach; ?>
											</td>
											<td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails">
												<?php foreach ($raci as $raciRow): ?>
													<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
														<?php if ($raciRow['ROLE'] == '2'): ?>
															<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
														<?php endif; ?>
													<?php endif; ?>
												<?php endforeach; ?>
											</td>
											<td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails">
												<?php foreach ($raci as $raciRow): ?>
													<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
														<?php if ($raciRow['ROLE'] == '3'): ?>
															<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
														<?php endif; ?>
													<?php endif; ?>
												<?php endforeach; ?>
											</td>
											<td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails">
												<?php foreach ($raci as $raciRow): ?>
													<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
														<?php if ($raciRow['ROLE'] == '4'): ?>
															<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
														<?php endif; ?>
													<?php endif; ?>
												<?php endforeach; ?>
											</td>
										</tr>
								<?php endforeach;?> <!-- TASKS -->
								</tbody>
							</table>
						</div>
					</div>

				<?php endif;?>

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
				<!-- /.content -->
			</div>
			<?php require("footer.php"); ?>
		</div>
		<!-- ./wrapper -->

		<script>
			$("#monitor").addClass("active");
			$("#monitorProject").addClass("active");
      $('.circlechart').circlechart(); // Initialization

			$(document).on("click", "#backBtn", function() {
				var $project = $("#backForm").attr('data-id');
				$("#backForm").attr("name", "formSubmit");
				$("#backForm").append("<input type='hidden' name='project_ID' value= " + $project + ">");
				$("#backForm").submit();
				});

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
