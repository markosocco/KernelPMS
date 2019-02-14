<html>
	<head>
		<title>Kernel - New Project</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/newProjectStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini fixed">
		<?php include_once("frame.php"); ?>

			<div class="content-wrapper">
		    <!-- Content Header (Page header) -->
		    <section class="content-header">
		      <h1>
						<?php if (isset($_SESSION['edit'])): ?>
							Edit project
			        <small>Let's edit a new project</small>
						<?php else: ?>
							Create a new project
			        <small>Let's create a new project</small>
						<?php endif; ?>
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
					    <li class="is-active">Input Project Details</li>
					    <li>Add Main Activities</li>
					    <li>Add Sub Activities</li>
					    <li>Add Tasks</li>
					    <li>Identify Dependencies</li>
					  </ul>
					</div>
					<br>

					<div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Input project details</h3>
            </div>

            <!-- /.box-header -->
            <!-- form start -->
						<?php if (isset($_SESSION['edit'])): ?>
							<form role="form" name = "editProject" id = "addProject" action = "editMainActivity" method = "POST">
								<input type="hidden" name="edit" value="<?php echo $project['PROJECTID']; ?>">

						<?php else: ?>
							<!-- <?php echo form_open_multipart('controller/addMainActivities');?> -->
							<form action="addMainActivities" id="newProjectForm" method="post" enctype="multipart/form-data">
							<!-- <form role="form" name = "addProject" id = "addProject" action = "addMainActivities" method = "POST"> -->
						<?php endif; ?>

							<?php if (isset($_SESSION['templates'])): ?>
								<input type="hidden" name="templates" value="<?php echo $project['PROJECTID']; ?>">
							<?php endif;?>
              <div class="box-body">
                <div class="form-group">
                  <label>Project Title</label>
									<?php if (isset($_SESSION['templates']) || isset($_SESSION['edit'])): ?>
										<input type="text" class="form-control" id="projectTitle" name="projectTitle" placeholder="Enter Project Title" value ="<?php echo $project['PROJECTTITLE']; ?>" required>
									<?php else: ?>
										<input type="text" class="form-control" id="projectTitle" name="projectTitle" placeholder="Enter Project Title" required>
									<?php endif; ?>
                </div>
                <div class="form-group">
									<label>Project Details</label>
									<?php if (isset($_SESSION['templates']) || isset($_SESSION['edit'])): ?>
										<textarea class="form-control" rows="5" placeholder="Enter project details..." name="projectDetails" required><?php echo $project['PROJECTDESCRIPTION']; ?></textarea>
									<?php else: ?>
										<textarea class="form-control" rows="5" placeholder="Enter project details..." name="projectDetails" required></textarea>
									<?php endif; ?>
                </div>

								<?php $projectTypes = array(
									array("ID" => 1, "TYPE" => "Store Opening"),
									array("ID" => 2, "TYPE" => "New Product Launch"),
									array("ID" => 3, "TYPE" => "Marketing Promotion"),
									array("ID" => 4, "TYPE" => "Onboarding"),
									array("ID" => 5, "TYPE" => "Offboarding"),
									array("ID" => 6, "TYPE" => "System Development"),
									array("ID" => 7, "TYPE" => "Miscellaneous"),
								); ?>

								<?php if (isset($_SESSION['templates']) || isset($_SESSION['edit'])): ?>
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="projectperiod">Project Type</label>
												<select class="form-control select2" name="type" id="type" style="width: 100%;" required>
													<!-- <option value="" selected disabled hidden>Choose a Project Type</option> -->
													<?php foreach ($projectTypes as $typeKey => $types): ?>
														<?php if ($project['PROJECTTYPE'] == $types['ID']): ?>
															<option value="<?php echo $types['ID']; ?>" selected><?php echo $types['TYPE']; ?></option>
														<?php else: ?>
															<option value="<?php echo $types['ID']; ?>"><?php echo $types['TYPE']; ?></option>
														<?php endif; ?>
													<?php endforeach; ?>
												</select>
											</div>
										</div>

								<?php else: ?>
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="projectperiod">Project Type</label>
												<select class="form-control select2" name="type" id="type" style="width: 100%;" required>
													<option value="" selected disabled hidden>Choose a Project Type</option>
													<?php foreach ($projectTypes as $types): ?>
														<option value="<?php echo $types['ID']; ?>"><?php echo $types['TYPE']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
								<?php endif; ?>


					        <div class="col-md-3">
										<div class="form-group">
			                <label>Start Date</label>
			                <div class="input-group date">
			                  <div class="input-group-addon">
			                    <i class="fa fa-calendar"></i>
			                  </div>
												<?php if (isset($_SESSION['edit'])): ?>
													<input type="text" class="form-control pull-right" id="startDate" name="startDate" value="<?php echo $project['PROJECTSTARTDATE']; ?>" required>
												<?php else: ?>
													<input type="text" class="form-control pull-right" id="startDate" name="startDate" required>
												<?php endif; ?>
			                </div>
			              </div>
									</div>

									<div class="col-md-3">
			              <div class="form-group">
			                <label>Target End Date</label>
			                <div class="input-group date">
			                  <div class="input-group-addon">
			                    <i class="fa fa-calendar"></i>
			                  </div>
												<?php if (isset($_SESSION['edit'])): ?>
													<input type="text" class="form-control pull-right" id="endDate" name ="endDate" value="<?php echo $project['PROJECTENDDATE']; ?>" required>
												<?php else: ?>
													<input type="text" class="form-control pull-right" id="endDate" name ="endDate" required>
												<?php endif; ?>
			                </div>
			              </div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="projectperiod">Project Period</label>

											<?php if (isset($_SESSION['templates']) || isset($_SESSION['edit'])): ?>

												<?php
													$startdate = date_create($project['PROJECTSTARTDATE']);

													if (isset($_SESSION['templates']))
													{
														$projectEndDate = date_create($project['PROJECTACTUALENDDATE']);
													}

													else
													{
														$projectEndDate = date_create($project['PROJECTENDDATE']);
													}

													$temp = date_diff($projectEndDate, $startdate);
													$dFormat = $temp->format('%a');
													$diff = (int)$dFormat + 1;

													if ($diff <= 1)
													{
														$period = $diff . " day";
													}

													else
													{
														$period = $diff . " days";
													}
												?>

												<input type="text" class="form-control" id="projectPeriod" name="period" value="<?php echo $period; ?>" readonly>

											<?php else: ?>
												<input type="text" class="form-control" id="projectPeriod" value="" readonly>
											<?php endif; ?>
										</div>
									</div>
								</div>


              <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right"><i class="fa fa-forward"></i>
									<?php if (isset($_SESSION['edit'])): ?>
										Edit Main Activities
									<?php else: ?>
										Add Main Activities
									<?php endif; ?>
								</button>

								<?php if (!isset($_SESSION['edit'])): ?>
									<button id="importBtn" type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modal-uploadExcel" style="margin-right: 2%"><i class="fa fa-file-excel-o"></i> Import from Spreadsheet</button>
								<?php endif; ?>
							</div>

          </div>

		    </section>
		    <!-- /.content -->
		  </div>

			<div class="modal fade" id="modal-uploadExcel">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Upload Project from a File</h4>
						</div>
						<div id="uploadDiv">
						<div class="modal-body">
							<a href="http://localhost/Kernel/assets/uploads/templates/template.xlsx" download>Download the Template here</a>
							<div class="form-group">
								<label for="uploadDoc">Select an Excel file to upload</label>
								<input type="file" id="uploadFile" name="uploadFile">
							</div>
						</div>
						<div class="modal-footer">
							<button id="uploadBack" type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
							<button  id="uploadConfirm" type="button" class="btn btn-success" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
						</div>
						</form>
					</div>

					<!-- CONFIRM UPLOAD -->
					<div id="confirmUpload">
						<div class="modal-body">
							<h4>Are you sure you want to upload this project?</h4>
							<h5>All changes to the form will not apply after importing this project.</h5>
						</div>
						<div class="modal-footer">
							<button id="backConfirm" type="button" class="btn btn-default pull-left" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
							<button id = "confirmUploadBtn" type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
						</div>
					</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->
			<?php include_once("footer.php"); ?>

		</div>
		<!-- ./wrapper -->

		<script>
			$("#myProjects").addClass("active");

			$("#confirmUpload").hide();

			$("#confirmUploadBtn").click(function()
			{
				$("#newProjectForm").append("<input type='hidden' name='isImport' value= 1>");
				$("#newProjectForm").submit();
			});

			$("body").on('click','#uploadConfirm',function()
			{
				$("#uploadDiv").hide();
				$("#confirmUpload").show();
			});

			$("body").on('click','#backConfirm',function()
			{
				$("#uploadDiv").show();
				$("#confirmUpload").hide();
			});

			$("#importBtn").click(function()
			{
				$("#uploadDiv").show();
				$("#confirmUpload").hide();
			});

			$("#uploadBack").click(function()
			{
				$("#uploadFile").val("");
			});

			<?php if (isset($_SESSION['edit'])): ?>
				$("#endDate").prop('disabled', false);
			<?php else: ?>
				$("#endDate").prop('disabled', true);
			<?php endif; ?>

			var currDate = new Date();

		  $(function ()
			{
				// $('.select2').select2()

			 <?php if (isset($_SESSION['edit'])): ?>

					var startDate = new Date(<?php echo $project['PROJECTSTARTDATE']; ?>);
					var endDate = new Date(<?php echo $project['PROJECTENDDATE']; ?>);

					$('#startDate').datepicker({
						"format": 'yyyy-mm-dd',
		        "setDate": startDate,
		        "autoclose": true
					});

					$('#endDate').datepicker({
						"format": 'yyyy-mm-dd',
		        "setDate": endDate,
		        "autoclose": true
					});

				<?php else: ?>

				//Date picker
			$('#startDate').datepicker({
				 format: 'yyyy-mm-dd',
				 startDate: currDate,
				 autoclose: true,
				 orientation: 'auto'
			 });

			 <?php endif; ?>

			 $("#startDate").on("change", function() {
				$("#endDate").prop('disabled', false);
				$('#endDate').data('datepicker').setStartDate(new Date($(this).val()));
				if(new Date($("#endDate").val()) < new Date($("#startDate").val())) //Removes Target Date Input if new Start Date comes after it
					$("#endDate").val("");
				var diff = new Date($("#endDate").datepicker("getDate") - $("#startDate").datepicker("getDate"));
				var period = (diff/1000/60/60/24)+1;
				if ($("#startDate").val() != "" && $("#endDate").val() != "" && period >=1)
				{
					if(period > 1)
						$("#projectPeriod").attr("value", period + " days");
					else
						$("#projectPeriod").attr("value", period + " day");
				}
				else
					$("#projectPeriod").attr("value", "");
			 });

 	     $('#endDate').datepicker({
				 format: 'yyyy-mm-dd',
 	       autoclose: true,
				 orientation: 'auto'
 	     });

			 $("#endDate").on("change", function() {
				 var ed = $("#endDate").datepicker("getDate");
				 var sd = $("#startDate").datepicker("getDate");

				var diff = new Date($("#endDate").datepicker("getDate") - $("#startDate").datepicker("getDate"));
				var period = (diff/1000/60/60/24)+1;

				if ($("#startDate").val() != "" && $("#endDate").val() != "" && period >=1)
				{
					if(period > 1)
						$("#projectPeriod").attr("value", period + " days");
					else
						$("#projectPeriod").attr("value", period + " day");
				}
				else
					$("#projectPeriod").attr("value", "");
			 });
		 });

		</script>

	</body>
</html>
