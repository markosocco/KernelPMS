<html>
	<head>
		<title>Kernel - Add Task Dependencies</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/addDependenciesStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini fixed">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
		    <!-- Content Header (Page header) -->
		    <section class="content-header">
		      <h1>
		        <?php echo $project['PROJECTTITLE'] ?>

						<?php
						$startdate = date_create($project['PROJECTSTARTDATE']);
						$enddate = date_create($project['PROJECTENDDATE']);
						?>

						<?php $diff = $dateDiff + 1;?>
						<small><?php echo date_format($startdate, "F d, Y") . " - " . date_format($enddate, "F d, Y"). "\t(" . $diff;?>
						<?php if ($dateDiff < 1):?>
							day remaining)</small>
						<?php else:?>
							days remaining)</small>
						<?php endif;?>

		      </h1>

					<ol class="breadcrumb">
	          <?php $dateToday = date('F d, Y | l');?>
	          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
	        </ol>
		    </section>

		    <!-- Main content -->
		    <section class="content container-fluid">
					<div class="container-fluid">
					  <ul class="list-unstyled multi-steps">
					    <li>Input Project Details</li>
					    <li>Add Main Activities</li>
					    <li>Add Sub Activities</li>
					    <li>Add Tasks</li>
					    <li class="is-active">Identify Dependencies</li>
					  </ul>
					</div>
					<br>
					<div class="row">
		        <div class="col-xs-12">
		          <div class="box box-danger">
		            <div class="box-header">
		              <h3 class="box-title">Enter task dependencies</h3>
		              <div class="box-tools">
		              </div>
		            </div>
		            <!-- /.box-header -->
								<form id='addDependencies' name = 'addDependencies' action = 'addDependencies' method="POST">

                  <input type="hidden" name="project_ID" value="<?php echo $project['PROJECTID']; ?>">

									<?php $c = 0; ?>

  								<?php foreach ($mainActivity as $key=>$value): ?>
  		            <div class="box-body table-responsive no-padding">
  		              <table class="table table-hover" id="table_<?php echo $key;?>">

  										<?php if($key == 0): ?>

  										<thead>
  			                <tr>
  												<th width="25%">Task Title</th>
  												<th width="25%">Department</th>
  												<th width="10%">Start Date</th>
  												<th width="10%" class="text-center">Target<br>End Date</th>
  												<th width="30%">Dependency</th>
  			                </tr>
  										</thead>

  									<?php endif; ?>

  										<tbody>
  											<tr>
  												<td width="25%"><b><?php echo $value['TASKTITLE']; ?></b></td>
  												<td width="25%"><b>
  													<?php

															$mDepts = array();

  														foreach ($allTasks as $row)
  														{
  															if($value['TASKTITLE'] == $row['TASKTITLE'])
  															{
  																foreach ($departments as $row2)
  																{
  																	if($row['USERID'] == $row2['users_DEPARTMENTHEAD'])
  																	{
  																		$mDepts[] = $row2['DEPARTMENTNAME'];
  																	}
  																}
  															}
  														}

															echo implode(", ", $mDepts);
  													?>
  												</b></td>

													<?php
														$startdate = date_create($value['TASKSTARTDATE']);
														$enddate = date_create($value['TASKENDDATE']);
														$diff = date_diff($enddate, $startdate);
														$dDiff = intval($diff->format('%d'));
													?>

  												<td width="10%"><b><?php echo date_format($startdate, "M d, Y"); ?></b></td>
  												<td width="10%" align="center"><b><?php echo date_format($enddate, "M d, Y"); ?></b></td>
  												<td width="30%"></td>
  											</tr>

  											<?php foreach ($subActivity as $sKey => $sValue): ?>
  													<?php if ($sValue['tasks_TASKPARENT'] == $value['TASKID']): ?>
  														<tr>
  															<td style="padding-left:20px;"><i><?php echo $sValue['TASKTITLE']; ?></i></td>
  															<td><i>
  																<?php

																		$sDepts = array();

  																	foreach ($allTasks as $row)
  																	{
  																		if($sValue['TASKTITLE'] == $row['TASKTITLE'])
  																		{
  																			foreach ($departments as $row2)
  																			{
  																				if($row['USERID'] == $row2['users_DEPARTMENTHEAD'])
  																				{
  																					$sDepts[] = $row2['DEPARTMENTNAME'];
  																				}
  																			}
  																		}
  																	}

																		echo implode(", ", $sDepts);
  																?>
  															</i></td>

																<?php
																	$startdate = date_create($sValue['TASKSTARTDATE']);
																	$enddate = date_create($sValue['TASKENDDATE']);
																	$diff = date_diff($enddate, $startdate);
																	$dDiff = intval($diff->format('%d'));
																?>

  															<td><i><?php echo date_format($startdate, "M d, Y"); ?></i></td>
  															<td align="center"><i><?php echo date_format($enddate, "M d, Y"); ?></i></td>
  															<td></td>
  														</tr>

															<?php foreach ($tasks as $tKey => $tValue): ?>
																<?php if($tValue['tasks_TASKPARENT'] == $sValue['TASKID']): ?>
																	<tr>
																		<td style="padding-left:40px;">
																			<!-- TASK NAME @TASK LEVEL -->
																			<?php echo $tValue['TASKTITLE']; ?>
																		</td>
																		<!-- CHANGE TO NORMAL SELECT. 1:1 -->
																		<td>
																			<!-- DEPARTMENT @TASK LEVEL -->
																			<?php
																				if (!isset($_SESSION['import']))
																				{
																					$tDepts = array();

			  																	foreach ($allTasks as $row)
			  																	{
			  																		if($tValue['TASKTITLE'] == $row['TASKTITLE'])
			  																		{
			  																			foreach ($departments as $row2)
			  																			{
			  																				if($row['USERID'] == $row2['users_DEPARTMENTHEAD'])
			  																				{
			  																					$tDepts[] = $row2['DEPARTMENTNAME'];
			  																				}
			  																			}
			  																		}
			  																	}

																					echo implode(", ", $tDepts);
																				}

																				else
																				{
																					foreach ($allTasks as $row)
			  																	{
			  																		if($tValue['TASKTITLE'] == $row['TASKTITLE'])
			  																		{
			  																			echo $row['DEPARTMENTNAME'];
			  																		}
			  																	}
																				}
		  																?>
																		</td>

																		<?php
																			$startdate = date_create($tValue['TASKSTARTDATE']);
																			$enddate = date_create($tValue['TASKENDDATE']);
																			$diff = date_diff($enddate, $startdate);
																			$dDiff = intval($diff->format('%d'));
																		?>

																		<td>
																			<!-- START DATE @TASK LEVEL -->
																			<?php echo date_format($startdate, "M d, Y"); ?>
																		</td>
																		<td align="center">
																			<!-- END DATE @TASK LEVEL -->
																			<?php echo date_format($enddate, "M d, Y"); ?>
																		</td>
																		<!-- DEPENDENCY INPUT -->
																		<td>
																			<input type="hidden" name="taskID[]" value="<?php echo $tValue['TASKID']; ?>">
																			<select class="form-control select2" multiple="multiple" name = "dependencies[<?php echo $c; ?>][]" data-placeholder="Select Task">
																					<?php foreach ($groupedTasks as $gKey => $gValue): ?>
																						<?php if($gValue['CATEGORY'] == '3'): ?>
																							<?php if ($gValue['TASKID'] != $tValue['TASKID']): ?>

																								<?php if ($gValue['TASKENDDATE'] <= $tValue['TASKSTARTDATE']): ?>
																									<option value ='<?php echo $gValue['TASKID']; ?>'>
																										<?php echo $gValue['TASKTITLE']; ?>
																									</option>
																								<?php endif; ?>

																							<?php endif; ?>
																						<?php endif; ?>
																					<?php endforeach; ?>
																			</select>
																		</td>

																	</tr>
																	<?php $c++; ?>
															<?php endif; ?>
														<?php endforeach; ?>

  												<?php endif; ?>
  											<?php endforeach; ?>
  										</tbody>
  									</table>
  		            </div>
  								<?php endforeach; ?>

  		            <!-- /.box-body -->
  								<div class="box-footer">
  									<!-- <button type="button" class="btn btn-success"><i class="fa fa-backward"></i> Add Tasks</button> -->
  									<button type="submit" class="btn btn-success pull-right" id="scheduleTasks"><i class="fa fa-forward"></i> Generate Gantt Chart</button>
  									<!-- <button type="button" class="btn btn-primary pull-right" style="margin-right: 5%">Skip This Step</button> -->
  								</div>
  								</form>
  		          </div>
  		          <!-- /.box -->
  		        </div>
  		      </div>
  		    </section>
  		    <!-- /.content -->
  		  </div>
  			<?php require("footer.php"); ?>

  		</div>
  		<!-- ./wrapper -->

  		<script type='text/javascript'>

  		$("#myProjects").addClass("active");

  	  $(function ()
  		{
  			//Initialize Select2 Elements
  	    $('.select2').select2()

  			//Date picker
  			$('body').on('focus',".taskStartDate", function(){
  			    $(this).datepicker({
  						format: 'yyyy-mm-dd',
  	  	       autoclose: true,
  						 orientation: 'bottom'
  					});
  			});

  			$('body').on('focus',".taskEndDate", function(){
  					$(this).datepicker({
  						format: 'yyyy-mm-dd',
  						 autoclose: true,
  						 orientation: 'bottom'
  					});
  			});
  		 });
  		</script>

  	</body>
  </html>
