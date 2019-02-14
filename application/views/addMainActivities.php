<html>
	<head>
		<title>Kernel - Add Main Activities</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/addMainsStyle.css")?>">
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
					    <li class="is-active">Add Main Activities</li>
					    <li>Add Sub Activities</li>
					    <li>Add Tasks</li>
					    <li>Identify Dependencies</li>
					  </ul>
					</div>
					<br>
					<div class="row">
		        <div class="col-xs-12">
		          <div class="box box-danger">
		            <div class="box-header">
									<?php if (isset($_SESSION['edit'])): ?>
										<h3 class="box-title">Edit main activities for this project</h3>
									<?php else: ?>
										<h3 class="box-title">Enter main activities for this project</h3>
									<?php endif; ?>
		            </div>
		            <!-- /.box-header -->
								<?php if (isset($_SESSION['edit'])): ?>
									<form id='addTasks' name = 'addTasks' action = 'editSubActivity' method="POST">
									<input type="hidden" name="project_ID" value="<?php echo $templateProject['PROJECTID']; ?>">
								<?php else: ?>
									<form id='addTasks' name = 'addTasks' action = 'addTasksToProject' method="POST">
									<input type="hidden" name="project_ID" value="<?php echo $project['PROJECTID']; ?>">
								<?php endif; ?>



									<?php if (isset($_SESSION['templates'])): ?>
									<input type="hidden" name="templates" value="<?php echo $templateProject['PROJECTID']; ?>">
								<?php endif; ?>

		            <div class="box-body table-responsive no-padding">
		              <table class="table table-hover" id="table">
		                <tr>
											<th width="27.5%">Main Activity Name</th>
											<th width="27.5%">Department</th>
											<th width="15%">Start Date</th>
											<th width="15%">Target End Date</th>
											<th width="10%">Period</th>
											<th width="5%"></th>
		                </tr>

		                <?php if (isset($_SESSION['templates'])): ?>

											<?php foreach ($templateMainActivity as $key=> $tMain): ?>
												<tr id ="row<?php echo  $key?>">
													<td>
														<div class="form-group">
															<input type="text" class="form-control" placeholder="Enter Main Activity Name" name = "title[]" value = "<?php echo $tMain['TASKTITLE']; ?>" required>
															<input type="hidden" name="row[]" value="<?php echo $key; ?>">
															<input type="hidden" name="templateTaskID[]" value="<?php echo $tMain['TASKID']; ?>"
														</div>
													</td>
													<td>
														<select id ="select<?php echo $key; ?>" class="form-control select2" multiple="multiple" name = "department[<?php echo $key; ?>][]" data-placeholder="Select Departments">
															<option>
																All
															</option>
															<?php foreach ($departments as $row): ?>
																<?php if ($row['DEPARTMENTNAME'] != 'Executive'): ?>
																	<option>
																		<?php echo $row['DEPARTMENTNAME']; ?>
																	</option>
																<?php endif; ?>
															<?php endforeach; ?>
														</select>
													</td>
													<td>
														<div class="form-group">
															<div class="input-group date">
																<div class="input-group-addon">
																	<i class="fa fa-calendar"></i>
																</div>
																<input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" id="start-<?php echo $key; ?>" data-mainAct="<?php echo $key; ?>" data-start="<?php echo $project['PROJECTSTARTDATE'];?>" data-end="<?php echo $project['PROJECTENDDATE'];?>" required>
															</div>
															<!-- /.input group -->
														</div>
													</td>
													<td>
														<div class="form-group">
															<div class="input-group date">
																<div class="input-group-addon">
																	<i class="fa fa-calendar"></i>
																</div>
																<input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" id="end-<?php echo $key; ?>" data-mainAct="<?php echo $key; ?>" required>
															</div>
														</div>
													</td>
													<td>
														<div class="form-group">

															<?php
																$startdate = date_create($templateProject['PROJECTSTARTDATE']);
																$enddate = date_create($templateProject['PROJECTACTUALENDDATE']);
																$temp = date_diff($enddate, $startdate);
																$dFormat = $temp->format('%a');
																$diff = (int)$dFormat + 1;

																if ($diff >= 1)
																{
																	$period = $diff . " day";
																}

																else
																{
																	$period = $diff . " days";
																}
															?>

															<input id = "projectPeriod<?php echo $key; ?>" type="text" class="form-control" value="<?php echo $period; ?>" readonly>
														</div>
													</td>
													<td class='btn'>
														<!-- <a id = "del<?php echo $key; ?>" class='btn delButton' data-id = "<?php echo $key; ?>"><i class='glyphicon glyphicon-trash'></i></a> -->
													</td>
												<!-- <td class="btn"><a class="btn delButton"></a></td> -->
												</tr>

												<?php if ($key == (count($templateMainActivity) - 1)): ?>
														<tr id="row<?php echo ($key + 1) ;?>">
														</tr>
												<?php endif; ?>

											<?php endforeach; ?>

										<?php elseif (isset($_SESSION['edit'])): ?>

											<?php foreach ($templateMainActivity as $key=> $tMain): ?>
												<tr id ="row<?php echo  $key?>">
													<td>
														<div class="form-group">
															<input type="text" class="form-control" placeholder="Enter Main Activity Name" name = "title[]" value = "<?php echo $tMain['TASKTITLE']; ?>" required>
															<input type="hidden" name="row[]" value="<?php echo $key; ?>">
															<input type="hidden" name="taskID[]" value="<?php echo $tMain['TASKID']; ?>"
														</div>
													</td>
													<td>
														<select id ="select<?php echo $key; ?>" class="form-control select2" multiple="multiple" name = "department[<?php echo $key; ?>][]" data-placeholder="Select Departments">
															<option>
																All
															</option>

															<?php foreach ($departments as $row): ?>
																	<?php foreach ($templateRaci as $tRaci): ?>
																		<?php if ($tRaci['tasks_TASKID'] == $tMain['TASKID']): ?>
																			<?php if ($tRaci['uDept'] == $row['DEPARTMENTID']): ?>
																				<?php echo "<option selected='selected'>" . $row['DEPARTMENTNAME'] . "</option>"; ?>
																			<?php else: ?>
																				<?php echo "<option>" . $row['DEPARTMENTNAME'] . "</option>"; ?>
																			<?php endif; ?>
																		<?php endif; ?>
																	<?php endforeach; ?>
															<?php endforeach; ?>


														</select>
													</td>
													<td>
														<div class="form-group">
															<div class="input-group date">
																<div class="input-group-addon">
																	<i class="fa fa-calendar"></i>
																</div>
																<input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" id="start-<?php echo $key; ?>" data-mainAct="<?php echo $key; ?>" data-start="<?php echo $project['PROJECTSTARTDATE'];?>" data-end="<?php echo $project['PROJECTENDDATE'];?>" value="<?php echo $tMain['TASKSTARTDATE']; ?>" required>
															</div>
															<!-- /.input group -->
														</div>
													</td>
													<td>
														<div class="form-group">
															<div class="input-group date">
																<div class="input-group-addon">
																	<i class="fa fa-calendar"></i>
																</div>
																<input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" id="end-<?php echo $key; ?>" data-mainAct="<?php echo $key; ?>" value="<?php echo $tMain['TASKENDDATE']; ?>" required>
															</div>
														</div>
													</td>
													<td>
														<div class="form-group">

															<?php
																$startdate = date_create($templateProject['PROJECTSTARTDATE']);
																$enddate = date_create($templateProject['PROJECTACTUALENDDATE']);
																$temp = date_diff($enddate, $startdate);
																$dFormat = $temp->format('%a');
																$diff = (int)$dFormat + 1;

																if ($diff >= 1)
																{
																	$period = $diff . " day";
																}

																else
																{
																	$period = $diff . " days";
																}
															?>

															<input id = "projectPeriod<?php echo $key; ?>" type="text" class="form-control" value="<?php echo $period; ?>" readonly>
														</div>
													</td>
													<td class='btn'>
														<!-- <a id = "del<?php echo $key; ?>" class='btn delButton' data-id = "<?php echo $key; ?>"><i class='glyphicon glyphicon-trash'></i></a> -->
													</td>
												<!-- <td class="btn"><a class="btn delButton"></a></td> -->
												</tr>

												<?php if ($key == (count($templateMainActivity) - 1)): ?>
														<tr id="row<?php echo ($key + 1) ;?>">
														</tr>
												<?php endif; ?>

											<?php endforeach; ?>

										<?php else: ?>
											<tr id="row0">
			                  <td>
													<div class="form-group">
				                  	<input type="text" class="form-control" placeholder="Enter Main Activity Name" name = "title[]" required>
														<input type="hidden" name="row[]" value="0">
				                	</div>
												</td>
												<td>
					                <select id ="select0" class="form-control select2" multiple="multiple" name = "department[0][]" data-placeholder="Select Departments">
														<option>
															All
														</option>
														<?php foreach ($departments as $row): ?>
															<?php if ($row['DEPARTMENTNAME'] != 'Executive'): ?>
																<option>
																	<?php echo $row['DEPARTMENTNAME']; ?>
																</option>
															<?php endif; ?>
														<?php endforeach; ?>
					                </select>
												</td>
												<td>
													<div class="form-group">
						                <div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" id="start-0" data-mainAct="0" data-start="<?php echo $project['PROJECTSTARTDATE'];?>" data-end="<?php echo $project['PROJECTENDDATE'];?>" required>
						                </div>
						                <!-- /.input group -->
						              </div>
												</td>
												<td>
													<div class="form-group">
						                <div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" id="end-0" data-mainAct="0" required>
						                </div>
													</div>
												</td>
												<td>
													<div class="form-group">
				                  	<input id = "projectPeriod0" type="text" class="form-control" value="" readonly>
				                	</div>
												</td>
												<td class='btn'>
													<!-- <a id = "del0" class='btn delButton' data-id = ""><i class='glyphicon glyphicon-trash'></i></a> -->
												</td>
											<!-- <td class="btn"><a class="btn delButton"></a></td> -->
			                </tr>

											<tr id="row1">
												<!-- NEW LINE WILL BE INSERTED HERE  -->
											</tr>
										<?php endif; ?>

										<tfoot>
											<tr align="center">
												<td class="btn" id="addRow" colSpan="6"><a class="btn addButton"  data-counter = "1"><i class="glyphicon glyphicon-plus-sign"></i> Add more main activities</a></td>
											</tr>
										</tfoot>

		              </table>

								</div>
		            <!-- /.box-body -->
								<div class="box-footer">
									<!-- <button type="button" class="btn btn-success"><i class="fa fa-backward"></i> Project details</button> -->
									<button type="submit" class="btn btn-success pull-right" id="arrangeTask" data-id= <?php echo $project['PROJECTID']; ?>><i class="fa fa-forward"></i>
										<?php if (isset($_SESSION['edit'])): ?>
											Edit Sub Activities
										<?php else: ?>
											Add Sub Activities
										<?php endif; ?>
									</button>
									<!-- <button type="button" class="btn btn-primary pull-right" style="margin-right: 5%"><i class="fa fa-window-maximize"></i> Use a Template</button> -->
									<!-- <button type="button" class="btn btn-primary pull-right" style="margin-right: 5%">Save</button> -->
								</div>
								</form>
		          </div>
		          <!-- /.box -->
		        </div>

				</div>

		      </div>
		    </section>
		    <!-- /.content -->
				<?php require("footer.php"); ?>

		  </div>

		</div>
		<!-- ./wrapper -->

		<!-- REQUIRED JS SCRIPTS -->

		<script>

			$.fn.datepicker.defaults.format = 'yyyy-mm-dd';
			$.fn.datepicker.defaults.autoclose = 'true';
			$.fn.datepicker.defaults.endDate = $("#start-0").attr('data-end');

			$("#myProjects").addClass("active");

			<?php if (isset($_SESSION['edit'])): ?>
				$(".taskEndDate").prop('disabled', false);
			<?php else: ?>
				$(".taskEndDate").prop('disabled', true);
			<?php endif; ?>

		  $(function ()
			{
				//Initialize Select2 Elements
		    $('.select2').select2()

				//Date picker

			 $('body').on('focus',".taskStartDate", function(){
					 $(this).datepicker({
						 autoclose: true,
						 orientation: 'auto',
						 format: 'yyyy-mm-dd',
						 startDate: $("#start-0").attr('data-start'), // start date of project
						 endDate: $("#start-0").attr('data-end') // end date of project
					 });
			 });

			 $("body").on("change", ".taskStartDate", function(e) {
				 var mainAct = $(this).attr('data-mainAct');

				 $("#end-" + mainAct).prop('disabled', false);
				 if(new Date($("#end-" + mainAct).val()) < $(this).val()) //Removes Target Date Input if new Start Date comes after it
					 $("#end-" + mainAct).val("");

					var diff = new Date($("#end-"+mainAct).datepicker("getDate") - $("#start-" + mainAct).datepicker("getDate"));
	 				var period = (diff/1000/60/60/24)+1;
					if ( $("#start-" + mainAct).val() != "" &&  $("#end-" + mainAct).val() != "" && period >=1)
	 				{
						if(period > 1)
							$("#projectPeriod" + mainAct).attr("value", period + " days");
						else
							$("#projectPeriod" + mainAct).attr("value", period + " day");
	 				}
	 				else
	 					$("#projectPeriod").attr("value", "");

					$("#end-" + mainAct).data('datepicker').setStartDate(new Date($("#start-" + mainAct).val()));

			});

			 $('body').on('focus',".taskEndDate", function(){

				 var mainAct = $(this).attr('data-mainAct');
				 var counter = $(this).attr('data-num');

					 $(this).datepicker({
		 	       autoclose: true,
						 orientation: 'auto',
						 format: 'yyyy-mm-dd',
						 endDate: $("#start-0").attr('data-end') // end date of project
					 });
			 });

			 $("body").on("change", ".taskEndDate", function() {
				var mainAct = $(this).attr('data-mainAct');
				var diff = new Date($("#end-"+mainAct).datepicker("getDate") - $("#start-" + mainAct).datepicker("getDate"));
				var period = (diff/1000/60/60/24)+1;
				if ( $("#start-" + mainAct).val() != "" &&  $("#end-" + mainAct).val() != "" && period >=1)
				{
					if(period > 1)
						$("#projectPeriod" + mainAct).attr("value", period + " days");
					else
						$("#projectPeriod" + mainAct).attr("value", period + " day");
				}
				else
					$("#projectPeriod" + mainAct).attr("value", "");
			 });
		 });

		 $(document).ready(function() {

			 <?php if (isset($_SESSION['templates']) || isset ($_SESSION['edit'])): ?>
			 		var i = <?php echo (count($templateMainActivity)); ?>;
					var x = <?php echo (count($templateMainActivity) + 1); ?>;
				<?php else: ?>
					var i = 1;
					var x = 2;
			 <?php endif; ?>

			 $(document).on("click", "a.addButton", function() {

				 // var str = new String("department[\'dept\'][]");

				 // console.log("hello "+ str);
				 var counter = parseInt($(this).attr('data-counter'));

				 $('#row' + i).html("<td><div class ='form-group'><input type='text' class='form-control' placeholder='Enter Main Activity Name' name ='title[]' required>  <input type='hidden' name = 'row[]' value='" + i + "' ></div></td> " +
				 " <td> <select id ='select" + i + "' class='form-control select2' multiple='multiple' name = '' data-placeholder='Select Departments'> <?php foreach ($departments as $row) { echo '<option>' . $row['DEPARTMENTNAME'] . '</option>';  }?>" +
				 "</select></td> <td><div class='form-group'><div class='input-group date'><div class='input-group-addon'>" +
				 "<i class='fa fa-calendar'></i></div> <input type='text' class='form-control pull-right taskStartDate' name='taskStartDate[]' id='start-" + i + "' data-mainAct='"+ i +"' required></div></div></td> <td><div class='form-group'><div class='input-group date'><div class='input-group-addon'>" +
				 "<i class='fa fa-calendar'></i></div> <input type='text' class='form-control pull-right taskEndDate' name='taskEndDate[]' id='end-" + i + "' data-mainAct='" + i + "' required></div></div></td> <td> <div class = 'form-group'> <input id='projectPeriod" + i + "' type ='text' class='form-control' value='' readonly> </div> </td> <td class='btn'><a id='del" + counter + "' class='btn delButton' data-id = " + i +" counter = " + x + "><i class='glyphicon glyphicon-trash'></i></a></td>");

				 // counter++;

				 	$('.select2').select2();
					$("#end-" + i).prop('disabled', true);
					$("#select" + i).attr("name", "department[" + i + "][]");

					counter++;
					$("a.addButton").attr('data-counter', counter);

					 $('#table').append('<tr id="row' + (i + 1) + '"></tr>');
					 i++;
					 x++;
				});

				$(document).on("click", "a.delButton", function() {
							if (x > 2)
							{
								var j = $(this).attr('data-id');

								var counter = $("a.addButton").attr('data-counter');

								$('#row' + j).remove();

								counter--;
								$("a.addButton").attr('data-counter', counter);
							}
					});
        });
		</script>
	</body>
</html>
