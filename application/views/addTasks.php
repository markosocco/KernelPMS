<html>
	<head>
		<title>Kernel - Add Tasks</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/addTasksStyle.css")?>"> -->
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
							day)</small>
						<?php else:?>
							days)</small>
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
					    <li class="is-active">Add Tasks</li>
					    <li>Identify Dependencies</li>
					  </ul>
					</div>
					<br>
					<div class="row">
		        <div class="col-xs-12">
		          <div class="box box-danger">
		            <div class="box-header">
		              <h3 class="box-title">Enter tasks for this project</h3>
		              <div class="box-tools">
		                <!-- <div class="input-group input-group-sm" style="width: 150px;">
		                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

		                  <div class="input-group-btn">
		                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
		                  </div>
		                </div> -->
		              </div>
		            </div>
		            <!-- /.box-header -->
								<form id='addTasksForm' name = 'addTasks' action = '<?php echo base_url('index.php/controller/addTasks');?>' method="POST">

								<input type="hidden" name="project_ID" value="<?php echo $project['PROJECTID']; ?>">

								<?php if (isset($_SESSION['templates'])): ?>
									<!-- TEMPLATES START -->
									<?php $c = 0; ?>

									<?php foreach ($mainActivity as $key=>$value): ?>

										<!-- MAIN ACT TABLE START -->

									<div class="box-body table-responsive no-padding">
			              <table class="table table-hover" id="table_<?php echo $key;?>">

											<?php if($key == 0): ?>

											<thead>
				                <tr>
													<th width="5%"></th>
													<th width="25%">Task Title</th>
													<th width="25%">Department</th>
													<th width="15%">Start Date</th>
													<th width="15%">Target End Date</th>
													<th width="10%">Period</th>
													<th width="5%"></th>
				                </tr>
											</thead>

										<?php endif; ?>

											<tbody>
												<tr>
													<td width="5%"></td>
													<td width="25%"><b><?php echo $value['TASKTITLE']; ?></b></td>
													<td width="25%"><b>
														<?php
															$mDepts = array();

															foreach ($tasks as $row)
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
														$dDiff = intval($diff->format('%a'));
													?>

													<td width="15%"><b><?php echo date_format($startdate, "M d, Y"); ?></b></td>
													<td width="15%"><b><?php echo date_format($enddate, "M d, Y"); ?></b></td>
													<td>
														<?php
															if (($dDiff + 1) <= 1)
																echo ($dDiff + 1) . " day";
															else
																echo ($dDiff + 1) . " days";
														?>
													</td>
													<td width="5%"></td>
												</tr>
											</tbody>
											</table>

											<!-- MAIN ACTIVITY TABLE END  -->


												<?php foreach ($subActivity as $sKey => $sValue): ?>
													<?php $x=0; ?>
													<?php $y=0; ?>
													<!-- SUB ACT TABLE START -->
														<?php if ($sValue['tasks_TASKPARENT'] == $value['TASKID']): ?>
															<table class="table table-hover" id = "ma<?php echo $key; ?>_s<?php echo $sKey; ?>">
																<thead>
																	<tr>
																		<th width="5%"></th>
																		<th width="25%"></th>
																		<th width="25%"></th>
																		<th width="15%"></th>
																		<th width="15%"></th>
																		<th width="10%"></th>
																		<th width="15%"></th>
																	</tr>
																</thead>
															<tbody>
															<tr>
																<td></td>
																<!-- <td class="btn" id="addRow"><a class="btn addButton" data-subTot="<?php echo count($subActivity); ?>" data-mTable = "<?php echo $key; ?>" data-sTable="<?php echo $sKey; ?>" data-subAct="<?php echo $sValue['TASKID']; ?>" counter="1" data-sum = "<?php echo count($groupedTasks); ?>"><i class="glyphicon glyphicon-plus-sign"></i></a></td> -->
																<td style="padding-left:20px;"><i><?php echo $sValue['TASKTITLE']; ?></i></td>
																<td><i>
																	<?php

																		$depts = array();

																		foreach ($tasks as $row)
																		{
																			if($sValue['TASKTITLE'] == $row['TASKTITLE'])
																			{
																				foreach ($departments as $row2)
																				{
																					if($row['USERID'] == $row2['users_DEPARTMENTHEAD'])
																					{
																						$depts[] = $row2['DEPARTMENTNAME'];
																					}
																				}
																			}
																		}

																		echo implode(", ", $depts);
																	?>
																</i></td>

																<?php
																	$sdate = date_create($sValue['TASKSTARTDATE']);
																	$edate = date_create($sValue['TASKENDDATE']);
																	$diff = date_diff($sdate, $edate);
																	$dDiff = intval($diff->format('%a'));
																?>

																<td><i><?php echo date_format($sdate, "M d, Y"); ?></i></td>
																<td><i><?php echo date_format($edate, "M d, Y"); ?></i></td>
																<td>
																	<?php
																		if (($dDiff + 1) <= 1)
																			echo ($dDiff + 1) . " day";
																		else
																			echo ($dDiff + 1) . " days";
																	?>
																</td>
															</tr>

																<?php if (in_array($sValue['TEMPLATETASKID'], array_column($templateSubActTaskID, 'TASKID'))): ?>
																	<?php foreach ($templateTasks as $tKey => $tTask): ?>
																		<?php if ($tTask['tasks_TASKPARENT'] == $sValue['TEMPLATETASKID']): ?>
																			<tr>

																				<?php if ($y == 0): ?>
																					<td class="btn" id="addRow"><a class="btn addButton" data-subTot="<?php echo count($subActivity); ?>" data-mTable = "<?php echo $key; ?>" data-sTable="<?php echo $sKey; ?>" data-subAct="<?php echo $sValue['TASKID']; ?>" counter="1" data-sum = "<?php echo count($groupedTasks); ?>" data-dept='<?php echo json_encode($depts); ?>' ><i class="glyphicon glyphicon-plus-sign"></i></a></td>
																				<?php else: ?>
																					<td></td>
																				<?php endif; ?>

																				<td>
																					<div class="form-group">

																						<input type="hidden" name="subActivity_ID[]" value="<?php echo $sValue['TASKID']; ?>">

																						<input type="text" class="form-control" name = "title[]" value="<?php echo $tTask['TASKTITLE'] ?>" required>
																						<input type="hidden" name="row[]" value="<?php echo $c; ?>">
																					</div>
																				</td>
																				<td style="padding-top:10px">
																					<select class="form-control select2" name = "department[<?php echo $c; ?>][]" data-placeholder="Select Departments">
																						<option></option>
																						<?php
																						$selectDepts = array();

																						foreach ($tasks as $row)
																						{
																							if($sValue['TASKTITLE'] == $row['TASKTITLE'])
																							{
																								foreach ($departments as $row2)
																								{
																									if($row['USERID'] == $row2['users_DEPARTMENTHEAD'])
																									{
																										// echo "<option>" . $row2['DEPARTMENTNAME'] . "</option>";
																										array_push($selectDepts, $row2['DEPARTMENTNAME']);
																									}
																								}
																							}
																						}

																						foreach ($selectDepts as $sD)
																						{
																							if (count($selectDepts) != 1)
																							{
																								echo "<option>" . $sD . "</option>";
																							}

																							else
																							{
																								echo "<option selected='selected'>" . $sD . "</option>";
																							}
																						}
																						?>
																					</select>
																				</td>
																				<td>
																					<div class="form-group">
																						<div class="input-group date">
																							<div class="input-group-addon">
																								<i class="fa fa-calendar"></i>
																							</div>
																							<input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" id="start_<?php echo $sValue['TASKID'];?>-<?php echo $y; ?>"
																							data-subAct="<?php echo $sValue['TASKID'];?>" data-num="<?php echo $y; ?>"
																							data-subStart<?php echo $sValue['TASKID']; ?> = "<?php echo $sValue['TASKSTARTDATE']; ?>"
																							data-subEnd<?php echo $sValue['TASKID']; ?> = "<?php echo $sValue['TASKENDDATE']; ?>" required>
																						</div>
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<div class="input-group date">
																							<div class="input-group-addon">
																								<i class="fa fa-calendar"></i>
																							</div>
																							<input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" id="end_<?php echo $sValue['TASKID'];?>-<?php echo $y; ?>"
																							data-subAct="<?php echo $sValue['TASKID']; ?>" data-num="<?php echo $y; ?>" required>
																						</div>
																					</div>
																				</td>
																				<td>
																					<div class="form-group">
																						<input id = "projectPeriod_<?php echo $sValue['TASKID']; ?>-<?php echo $y; ?>" type="text" class="form-control period" value="" readonly>
																					</div>
																				</td>
																				<td></td>
																				<!-- <td class='btn'><a class='btn delButton' data-id = " + i +"><i class='glyphicon glyphicon-trash'></i></a></td> -->
																			</tr>
																			<?php $y++; ?>
																		<?php endif; ?>
																	<?php endforeach; ?>

																<?php else: ?>
																	<tr>
																	  <td class="btn" id="addRow"><a class="btn addButton" data-subTot="<?php echo count($subActivity); ?>" data-mTable = "<?php echo $key; ?>" data-sTable="<?php echo $sKey; ?>" data-subAct="<?php echo $sValue['TASKID']; ?>" counter="1" data-sum = "<?php echo count($groupedTasks); ?>" data-dept='<?php echo json_encode($depts); ?>' ><i class="glyphicon glyphicon-plus-sign"></i></a></td>
																	  <td>
																	    <div class="form-group">

																	      <input type="hidden" name="subActivity_ID[]" value="<?php echo $sValue['TASKID']; ?>">

																	      <input type="text" class="form-control" placeholder="Enter Task Name" name = "title[]" required>
																	      <input type="hidden" name="row[]" value="<?php echo $c; ?>">
																	    </div>
																	  </td>
																	  <td style="padding-top:10px">
																	    <select class="form-control select2" name = "department[<?php echo $c; ?>][]" data-placeholder="Select Departments">
																	      <option></option>
																	      <?php
																				$selectDepts = array();

																				foreach ($tasks as $row)
																				{
																					if($sValue['TASKTITLE'] == $row['TASKTITLE'])
																					{
																						foreach ($departments as $row2)
																						{
																							if($row['USERID'] == $row2['users_DEPARTMENTHEAD'])
																							{
																								// echo "<option>" . $row2['DEPARTMENTNAME'] . "</option>";
																								array_push($selectDepts, $row2['DEPARTMENTNAME']);
																							}
																						}
																					}
																				}

																				foreach ($selectDepts as $sD)
																				{
																					if (count($selectDepts) != 1)
																					{
																						echo "<option>" . $sD . "</option>";
																					}

																					else
																					{
																						echo "<option selected='selected'>" . $sD . "</option>";
																					}
																				}
																	      ?>
																	    </select>
																	  </td>
																	  <td>
																	    <div class="form-group">
																	      <div class="input-group date">
																	        <div class="input-group-addon">
																	          <i class="fa fa-calendar"></i>
																	        </div>
																	        <input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" id="start_<?php echo $sValue['TASKID'];?>-<?php echo $x; ?>"
																	        data-subAct="<?php echo $sValue['TASKID'];?>" data-num="<?php echo $x ?>"
																	        data-subStart<?php echo $sValue['TASKID']; ?> = "<?php echo $sValue['TASKSTARTDATE']; ?>"
																	        data-subEnd<?php echo $sValue['TASKID']; ?> = "<?php echo $sValue['TASKENDDATE']; ?>" required>
																	      </div>
																	    </div>
																	  </td>
																	  <td>
																	    <div class="form-group">
																	      <div class="input-group date">
																	        <div class="input-group-addon">
																	          <i class="fa fa-calendar"></i>
																	        </div>
																	        <input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" id="end_<?php echo $sValue['TASKID'];?>-<?php echo $x; ?>"
																	        data-subAct="<?php echo $sValue['TASKID']; ?>" data-num="<?php echo $x; ?>" required>
																	      </div>
																	    </div>
																	  </td>
																	  <td>
																	    <div class="form-group">
																	      <input id = "projectPeriod_<?php echo $sValue['TASKID']; ?>-<?php echo $x; ?>" type="text" class="form-control period" value="" readonly>
																	    </div>
																	  </td>
																	  <td></td>
																	  <!-- <td class='btn'><a class='btn delButton' data-id = " + i +"><i class='glyphicon glyphicon-trash'></i></a></td> -->
																	</tr>
																	<?php $x++; ?>
																<?php endif; ?>


														</tbody>
													</table>
													<?php $c++; ?>
													<?php endif; ?>
												<?php endforeach; ?>
												<!-- END OF TASKS -->
			            </div>

									<!-- SUB ACT TABLE END -->

									<!-- <?php $x = 0; ?> -->
										<!-- END TEMPLATES -->
									<?php endforeach; ?>


								<?php else: ?>
									<?php $c = 0; ?>

									<?php foreach ($mainActivity as $key=>$value): ?>

										<!-- MAIN ACT TABLE START -->

									<div class="box-body table-responsive no-padding">
			              <table class="table table-hover" id="table_<?php echo $key;?>">

											<?php if($key == 0): ?>

											<thead>
				                <tr>
													<th width="5%"></th>
													<th width="25%">Task Title</th>
													<th width="25%">Department</th>
													<th width="15%">Start Date</th>
													<th width="15%">Target End Date</th>
													<th width="10%">Period</th>
													<th width="5%"></th>
				                </tr>
											</thead>

										<?php endif; ?>

											<tbody>
												<tr>
													<td width="5%"></td>
													<td width="25%"><b><?php echo $value['TASKTITLE']; ?></b></td>
													<td width="25%"><b>
														<?php
															$mDepts = array();

															foreach ($tasks as $row)
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
														$dDiff = intval($diff->format('%a'));
													?>

													<td width="15%"><b><?php echo date_format($startdate, "M d, Y"); ?></b></td>
													<td width="15%"><b><?php echo date_format($enddate, "M d, Y"); ?></b></td>
													<td width="10%"><b>
														<?php
															if (($dDiff + 1) <= 1)
																echo ($dDiff + 1) . " day";
															else
																echo ($dDiff + 1) . " days";
														?>
													</b></td>
													<td width="5%"></td>
												</tr>
											</tbody>
											</table>

											<!-- MAIN ACTIVITY TABLE END  -->

												<?php foreach ($subActivity as $sKey => $sValue): ?>

													<!-- SUB ACT TABLE START -->

														<?php if ($sValue['tasks_TASKPARENT'] == $value['TASKID']): ?>
															<table class="table table-hover" id = "ma<?php echo $key; ?>_s<?php echo $sKey; ?>">
																<thead>
																	<tr>
																		<th width="5%"></th>
																		<th width="25%"></th>
																		<th width="25%"></th>
																		<th width="15%"></th>
																		<th width="15%"></th>
																		<th width="10%"></th>
																		<th width="5%"></th>
																	</tr>
																</thead>
															<tbody>
															<tr>
																<td></td>
																<!-- <td class="btn" id="addRow"><a class="btn addButton" data-subTot="<?php echo count($subActivity); ?>" data-mTable = "<?php echo $key; ?>" data-sTable="<?php echo $sKey; ?>" data-subAct="<?php echo $sValue['TASKID']; ?>" counter="1"
																	data-sum = "<?php echo count($groupedTasks); ?>"><i class="glyphicon glyphicon-plus-sign"></i></a></td> -->
																<td style="padding-left:20px;"><i><?php echo $sValue['TASKTITLE']; ?></i></td>
																<td><i>
																	<?php

																		$depts = array();

																		foreach ($tasks as $row)
																		{
																			if($sValue['TASKTITLE'] == $row['TASKTITLE'])
																			{
																				foreach ($departments as $row2)
																				{
																					if($row['USERID'] == $row2['users_DEPARTMENTHEAD'])
																					{
																						$depts[] = $row2['DEPARTMENTNAME'];
																					}
																				}
																			}
																		}

																		echo implode(", ", $depts);
																	?>
																</i></td>

																<?php
																	$sdate = date_create($sValue['TASKSTARTDATE']);
																	$edate = date_create($sValue['TASKENDDATE']);
																	$diff = date_diff($edate, $sdate);
																	$sDiff = intval($diff->format('%a'));
																?>

																<td><i><?php echo date_format($sdate, "M d, Y"); ?></i></td>
																<td><i><?php echo date_format($edate, "M d, Y"); ?></i></td>
																<td><i>
																	<?php
																		if (($sDiff + 1) <= 1)
																			echo ($sDiff + 1) . " day";
																		else
																			echo ($sDiff + 1) . " days";
																	?>
																</i></td>
																<td></td>
															</tr>
															<tr>
																<td class="btn" id="addRow"><a class="btn addButton" data-subTot="<?php echo count($subActivity); ?>" data-mTable = "<?php echo $key; ?>" data-sTable="<?php echo $sKey; ?>" data-subAct="<?php echo $sValue['TASKID']; ?>" data-dept='<?php echo json_encode($depts); ?>' counter="1"
																	data-sum = "<?php echo count($groupedTasks); ?>"><i class="glyphicon glyphicon-plus-sign"></i></a></td>
																<td>
																	<div class="form-group">

																		<input type="hidden" name="subActivity_ID[]" value="<?php echo $sValue['TASKID']; ?>">

																		<input type="text" class="form-control" placeholder="Enter Task Title" name = "title[]" required>
																		<input type="hidden" name="row[]" value="<?php echo $c; ?>">
																	</div>
																</td>
																<td style="padding-top:10px">
																	<select id ="select<?php echo $c; ?>" class="form-control select2" name = "department[<?php echo $c; ?>][]" data-placeholder="Select Departments">
																		<option></option>
																		<?php
																		$selectDepts = array();

																		foreach ($tasks as $row)
																		{
																			if($sValue['TASKTITLE'] == $row['TASKTITLE'])
																			{
																				foreach ($departments as $row2)
																				{
																					if($row['USERID'] == $row2['users_DEPARTMENTHEAD'])
																					{
																						// echo "<option>" . $row2['DEPARTMENTNAME'] . "</option>";
																						array_push($selectDepts, $row2['DEPARTMENTNAME']);
																					}
																				}
																			}
																		}

																		foreach ($selectDepts as $sD)
																		{
																			if (count($selectDepts) != 1)
																			{
																				echo "<option>" . $sD . "</option>";
																			}

																			else
																			{
																				echo "<option selected='selected'>" . $sD . "</option>";
																			}
																		}
																		?>
																	</select>
																</td>
																<td>
																	<div class="form-group">
																		<div class="input-group date">
																			<div class="input-group-addon">
																				<i class="fa fa-calendar"></i>
																			</div>
																			<input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" id="start_<?php echo $sValue['TASKID'];?>-0"
																			data-subAct="<?php echo $sValue['TASKID'];?>" data-num="0"
																			data-subStart<?php echo $sValue['TASKID']; ?> = "<?php echo $sValue['TASKSTARTDATE']; ?>"
																			data-subEnd<?php echo $sValue['TASKID']; ?> = "<?php echo $sValue['TASKENDDATE']; ?>" required>
																		</div>
																	</div>
																</td>
																<td>
																	<div class="form-group">
																		<div class="input-group date">
																			<div class="input-group-addon">
																				<i class="fa fa-calendar"></i>
																			</div>
																			<input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" id="end_<?php echo $sValue['TASKID'];?>-0"
																			data-subAct="<?php echo $sValue['TASKID']; ?>" data-num="0" required>
																		</div>
																	</div>
																</td>
																<td>
																	<div class="form-group">
																		<input id = "projectPeriod_<?php echo $sValue['TASKID']; ?>-0" type="text" class="form-control period" value="" readonly>
																	</div>
																</td>
																<td></td>
																<!-- <td class='btn'><a class='btn delButton' data-id = " + i +"><i class='glyphicon glyphicon-trash'></i></a></td> -->
															</tr>
														</tbody>
													</table>

													<?php $c++; ?>

													<?php endif; ?>
												<?php endforeach; ?>
			            </div>

									<!-- SUB ACT TABLE END -->
									<?php endforeach; ?>
								<?php endif; ?>

		            <!-- /.box-body -->
								<div class="box-footer">
									<!-- <button type="button" class="btn btn-success"><i class="fa fa-backward"></i> Add Sub Activities</button> -->
									<button type="submit" class="btn btn-success pull-right" id="addTasks"><i class="fa fa-forward"></i> Add Dependencies</button>
									<!-- <button id ="skipStep" type="button" class="btn btn-primary pull-right" style="margin-right: 5%"><i class="fa fa-fast-forward"></i> Skip This Step</button> -->
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
		$.fn.datepicker.defaults.format = 'yyyy-mm-dd';
		$.fn.datepicker.defaults.autoclose = 'true';

		$(document).ready(function() {

		 var i = <?php echo (count($subActivity)); ?>;
		 var x = 2;

		 $(document).on("click", "a.addButton", function() {

			 var mTable = $(this).attr('data-mTable');
			 var sTable = $(this).attr('data-sTable');
			 var tot = $(this).attr('data-subTot');
			 var subAct = $(this).attr('data-subAct');
			 var counter = parseInt($(this).attr('data-sum'));
			 var depts = $(this).attr('data-dept');
			 var d = JSON.parse(depts);

			 var department = "";

			 for (var k = 0; k < d.length; k++)
			 {
				 if (d.length == 1)
				 {
					 department = department + "<option selected='selected'>" + d[k] + "</option>";
				 }

				 else
				 {
					 department = department + "<option>" + d[k] + "</option>";
				 }
			 }

				 $('#ma' + mTable + '_s' + sTable).append(
					 					"<tr id='ma" + mTable + "_s" + (i) +
										"'><td></td><td><div class ='form-group'> <input type='hidden' name='subActivity_ID[]' value='" +
										subAct + "'> <input type='text' class='form-control' placeholder='Enter Task Title' name ='title[]' required>  <input type='hidden' name = 'row[]' value='" + i + "' >  </div></td>" +
										"<td style='padding-top:10px'><select id = 'select" + i + "' class='form-control select2' name = '' data-placeholder='Select Departments'> " +
										"<option></option>" + department +
										"</select></td> <td><div class='form-group'><div class='input-group date'><div class='input-group-addon'>" +
										"<i class='fa fa-calendar'></i></div><input type='text' class='form-control pull-right taskStartDate' " +
										"name='taskStartDate[]' id='start_" + subAct + "-" + x +"' data-subAct = '" + subAct + "' data-num='" + x +
										"' required></div></div></td> <td><div class='form-group'><div class='input-group date'>" +
										"<div class='input-group-addon'><i class='fa fa-calendar'></i></div><input type='text' class='form-control pull-right taskEndDate'" +
										"name='taskEndDate[]' id='end_" + subAct + "-" + x + "' data-subAct = '" + subAct + "' data-num='" + x +
										"' required></div></div></td><td><div class='form-group'><input id = 'projectPeriod_" + subAct + "-" + x + "' type='text'" +
										" class='form-control period' value=''readonly></div></td> <td class='btn'><a class='btn delButton' data-mTable = " + mTable +
										" counter = " + x + " data-sTable = " + (i) + "><i class='glyphicon glyphicon-trash'></i></a></td></tr>");

					$("#end_" + subAct + "-" + x).prop('disabled', true);

				 var newCount = counter + 1;
				 var newTot = tot + 1;

				 $("a.addButton").attr('counter', newCount);
				 $("a.addButton").attr('data-subTot', newTot);

				  $('.select2').select2();
					$("#select" + i).attr("name", "department[" + i + "][]");

				 i++;
				 x++;
			});

			$(document).on("click", "a.delButton", function() {
					if (x > 2)
					{
						var mTable = $(this).attr('data-mTable');
						var sTable = $(this).attr('data-sTable');

						$('#ma' + mTable + '_s' + sTable).remove();
					}
				});
			 });

	  $(function ()
		{
			//Initialize Select2 Elements
	    $('.select2').select2();
			$(".taskEndDate").prop('disabled', true);

			//Date picker
			$('body').on('focus',".taskStartDate", function(){
				var subAct = $(this).attr('data-subAct');
				var counter = $(this).attr('data-num');
				var subStart = $("#start_" + subAct + "-0").attr('data-subStart' + subAct);
				var subEnd = $("#start_" + subAct + "-0").attr('data-subEnd' + subAct);

					$(this).datepicker({
						format: 'yyyy-mm-dd',
						 autoclose: true,
						 startDate: subStart,
						 endDate: subEnd,
						 orientation: 'auto'
					});
			});

			$("body").on("change", ".taskStartDate", function(e) {
				var subAct = $(this).attr('data-subAct');
				var counter = $(this).attr('data-num');
				var newDate = $(this).val();

				console.log("#start_" + subAct + "-" + counter);
				console.log("#end_" + subAct + "-" + counter);
				console.log("#projectPeriod_" + subAct + "-" + counter);

			$("#end_" + subAct + "-" + counter).prop('disabled', false);
			var diff = new Date($("#end_" + subAct + "-" + counter).datepicker("getDate") - $("#start_" + subAct + "-" + counter).datepicker("getDate"));
			var period = (diff/1000/60/60/24)+1;
			if ($("#start_" + subAct + "-" + counter).val() != "" &&  $("#end_" + subAct + "-" + counter).val() != "" && period >=1)
			{
				if(period > 1)
					$("#projectPeriod_" + subAct + "-" + counter).attr("value", period + " days");
				else
					$("#projectPeriod_" + subAct + "-" + counter).attr("value", period + " day");
			}
			else
			{
				$("#projectPeriod_" + subAct + "-" + counter).attr("value", "");
				$("#end_" + subAct + "-" + counter).val("");
			}

			var subEnd = $("#start_" + subAct + "-0").attr('data-subEnd' + subAct);
			$("#end_" + subAct + "-" + counter).data('datepicker').setStartDate(new Date($("#start_" + subAct + "-" + counter).val()));
			$("#end_" + subAct + "-" + counter).data('datepicker').setEndDate(new Date(subEnd));


			});

			$('body').on('focus',".taskEndDate", function(){
				var subAct = $(this).attr('data-subAct');
				var counter = $(this).attr('data-num');

					$(this).datepicker({
						format: 'yyyy-mm-dd',
						 autoclose: true,
						 orientation: 'auto'
					});
			});

			$("body").on("change", ".taskEndDate", function() {
				var subAct = $(this).attr('data-subAct');
				var counter = $(this).attr('data-num');
				var diff = new Date($("#end_" + subAct + "-" + counter).datepicker("getDate") - $("#start_" + subAct + "-" + counter).datepicker("getDate"));
				var period = (diff/1000/60/60/24)+1;

				console.log("#start_" + subAct + "-" + counter);
				console.log("#end_" + subAct + "-" + counter);

				if ($("#start_" + subAct + "-" + counter).val() != "" &&  $("#end_" + subAct + "-" + counter).val() != "" && period >=1)
				{
					console.log("#projectPeriod_" + subAct + "-" + counter);

					if(period > 1)
						$("#projectPeriod_" + subAct + "-" + counter).attr("value", period + " days");
					else
						$("#projectPeriod_" + subAct + "-" + counter).attr("value", period + " day");
				}
				else
					$("#projectPeriod_" + subAct + "-" + counter).attr("value", "");
			});

	 });

		</script>

	</body>
</html>
