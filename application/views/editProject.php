<html>
	<head>
		<title>Kernel - Edit Project</title>

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
						Edit Project
						<small>What needs to be changed in this project?</small>
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
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Delayed <br><br><b><span style='color:red'><?php echo $delayed ;?></span></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Ongoing <br><br><b><?php echo $ongoing ;?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Planned <br><br><b><?php echo $planned ;?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Completed <br><br><b><?php echo $completed ;?></b></h4>
									</div>
								</div>
							</div>
						</div>

					</div>

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
                        <th colspan = '9'></th>
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
                        <th width='1%'></th>
        							</tr>
        							</thead>
        							<tbody>
												<form id='saveProject' action='saveEditedProject' method='POST'>
													<input type='hidden' name='project_ID' value= "<?php echo $projectProfile['PROJECTID'] ;?>">

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


  																<td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails">
                                    <input id = 'taskTitle-<?php echo $task['TASKID'];?>' type="text" class="form-control" value="<?php echo $task['TASKTITLE'];?>" disabled>
                                  </td>

                                  <?php if($task['TASKSTATUS'] == 'Complete'):?>

                                  <td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails" align='center'>
                                    <input id = 'taskStart-<?php echo $task['TASKID'];?>' type="text" class="form-control" value="<?php echo $task['TASKSTARTDATE']; ?>" disabled>
                                  </td>

                                  <td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails" align='center'>
                                    <input id = 'taskEnd-<?php echo $task['TASKID'];?>' type="text" class="form-control" value="<?php echo $endDate; ?>" disabled>
                                  </td>

                                <?php else:?>

                                  <!-- <td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails">
                                    <input id = 'taskTitle-<?php echo $task['TASKID'];?>' type="text" class="form-control" value="<?php echo $task['TASKTITLE'];?>" >
                                  </td> -->

                                  <td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails" align='center'>
                                    <input id = 'taskStart-<?php echo $task['TASKID'];?>' type="text" class="form-control taskStartDate" value="<?php echo $task['TASKSTARTDATE']; ?>" >
                                  </td>

                                  <td class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-delay="<?php echo $delay;?>" data-toggle="modal" data-target="#taskDetails" align='center'>
                                    <input id = 'taskEnd-<?php echo $task['TASKID'];?>' type="text" class="form-control taskEndDate" value="<?php echo $endDate; ?>">
                                  </td>

                                <?php endif;?>

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


                                <td align='center'>
                                <?php if($task['TASKSTATUS'] == 'Complete'):?>

                                  <a class='btn' disabled>
                                    <i class='glyphicon glyphicon-trash'></i>
                                  </a>

                                <?php else:?>

                                  <a class='btn delButton' data-id = "<?php echo $task['TASKID']; ?>"
                                    data-title="<?php echo $task['TASKTITLE'];?>"
                                    data-start="<?php echo $task['TASKSTARTDATE'];?>"
                                    data-end="<?php echo $endDate;?>">
                                    <i class='glyphicon glyphicon-trash'></i>
                                  </a>
                                <?php endif;?>

																</td>
                              </tr>
                            <?php endif;?>
                          <?php endforeach;?> <!-- TASKS -->
													<?php endif;?>
                        <?php endforeach;?> <!-- SUBACTIVITY -->
											</form>
        							</tbody>
        						</table>
        					</div>
      				</div>
            <?php endforeach;?> <!-- MAINACTIVITY -->
						<button id = "saveBtn" type="button" class="btn btn-success pull-right" data-toggle="tooltip" data-placement="top" title="Save"><i class="fa fa-check"></i></button>
  					</div>
  				</div>

					<!-- SAVE MODAL -->
					<div class="modal fade" id="modal-save" tabindex="-1">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h2 class="modal-title"><?php echo $projectProfile['PROJECTTITLE'];?></h2>
								</div>
								<div class="modal-body">
									<div class="modal-body">
										<h4>Are you sure you want to save the changes made to this project?</h4>
									</div>
									<div class="modal-footer">
										<button id="closeConfirmSave" type="button" class="btn btn-default pull-left" data-toggle="tooltip" data-dismiss="modal" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
										<button id = "confirmSave" type="submit" class="btn btn-success" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
									</div>
							</form>
						</div>
							</div>
						</div>
					</div>
					<!-- END SAVE MODAL -->

					<!-- DELETE MODAL -->
					<div class="modal fade" id="modal-delete" tabindex="-1">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h2 class="modal-title" id = "deleteTitle">Delete Task</h2>
									<h4 id="deleteDates">Start Date - End Date (Days)</h4>
								</div>
								<div class="modal-body">
									<div id="deleteDiv">
										<h4>Why do you want to delete this task?</h4>
										<form id = "deleteForm" action="deleteTask" method="POST" style="margin-bottom:0;">
                      <input type='hidden' name='project_ID' value= "<?php echo $projectProfile['PROJECTID'];?>">

											<div class="form-group">
												<textarea id = "reasonDelete" name = "reasonDelete" class="form-control" placeholder="Enter reason"></textarea>
											</div>
											<div class="modal-footer">
												<button id = "closeDeleteBtn" type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
												<button id = "deleteConfirmBtn" type="button" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
											</div>
									</div>
								<!-- CONFIRM DELETE -->
								<div id="deleteConfirmDiv">
									<div class="modal-body">
										<h4>Are you sure you want to delete this task?</h4>
									</div>
									<div class="modal-footer">
										<button id="backConfirmDelete" type="button" class="btn btn-default pull-left" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
										<button id = "confirmDelete" type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
									</div>
								</div>
							</form>
						</div>
							</div>
						</div>
					</div>
					<!-- END DELETE MODAL -->

				</section>
				<!-- /.content -->
			</div>
			<?php require("footer.php"); ?>
		</div>
		<!-- ./wrapper -->

		<script>
			$("#monitor").addClass("active");
			$("#monitorProject").addClass("active");

			$(document).on("click", "#backBtn", function() {
				var $project = $("#backForm").attr('data-id');
				$("#backForm").attr("name", "formSubmit");
				$("#backForm").append("<input type='hidden' name='project_ID' value= " + $project + ">");
				$("#backForm").submit();
				});

         $('body').on('focus',".taskStartDate", function(){
             $(this).datepicker({
               autoclose: true,
               orientation: 'auto',
               format: 'yyyy-mm-dd',
               startDate: $("#start-0").attr('data-start'), // start date of project
             });
         });

         $('body').on('focus',".taskEndDate", function(){

           $(this).datepicker({
             autoclose: true,
             orientation: 'auto',
             format: 'yyyy-mm-dd',
           });
        });

			// DELETE SCRIPT

		 $("body").on('click','.delButton',function(){
			var $id = $(this).attr('data-id');
			var $title = $(this).attr('data-title');
			var $start = new Date($(this).attr('data-start'));
			var $end = new Date($(this).attr('data-end'));
			var $diff = (($end - $start)/ 1000 / 60 / 60 / 24)+1;
			$("#deleteTitle").html($title);
			$("#deleteDates").html(moment($start).format('MMMM DD, YYYY') + " - " + moment($end).format('MMMM DD, YYYY') + " ("+ $diff);
			if($diff > 1)
				$("#deleteDates").append(" days)");
			else
				$("#deleteDates").append(" day)");
			$("#deleteConfirmBtn").attr("data-id", $id); //pass data id to confirm button
      $("#modal-delete").modal('show');
		});

		$("body").on('click','#confirmDelete',function(){
			var $id = $("#deleteConfirmBtn").attr('data-id');
			$("#deleteForm").attr("name", "formSubmit");
			$("#deleteForm").append("<input type='hidden' name='task_ID' value= " + $id + ">");
		});

		$("#deleteConfirmDiv").hide();

		$("body").on('click','#closeDeleteBtn',function(){
			$("#reasonDelete").val("");
		});

		$("body").on('click','#deleteConfirmBtn',function(){
			$("#deleteDiv").hide();
			$("#deleteConfirmDiv").show();
		});

		$("body").on('click','#backConfirmDelete',function(){
			$("#deleteDiv").show();
			$("#deleteConfirmDiv").hide();
		});

		// END DELETE SCRIPT

		$("body").on('click','#saveBtn',function(){
		 $("#modal-save").modal('show');
		 });

		</script>
	</body>
</html>
