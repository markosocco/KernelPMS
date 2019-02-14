<html>
	<head>
		<title>Kernel - Project Documents</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/projectDocumentsStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini sidebar-collapse fixed">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<div style="margin-bottom:10px">
						<button id="backBtn" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to Project"><i class="fa fa-arrow-left"></i></button>
						<form id="backForm" action = 'projectGantt' method="POST" data-id="<?php echo $projectProfile['PROJECTID']; ?>">
						</form>

					</div>

					<?php
						$startdate = date_create($projectProfile['PROJECTSTARTDATE']);
						$enddate = date_create($projectProfile['PROJECTENDDATE']);
					?>

					<h1>
						Documents
						<small><?php echo $projectProfile['PROJECTTITLE']; ?> (<?php echo date_format($startdate, "F d, Y") . " - " . date_format($enddate, "F d, Y");?>)</small>
					</h1>

					<ol class="breadcrumb">
	          <?php $dateToday = date('F d, Y | l');?>
	          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
	        </ol>
				</section>

				<!-- Main content -->
				<section class="content container-fluid">

					<form action='acknowledgeDocument' method='POST' id ='acknowledgeForm' name=''>
					</form>

					<!-- <div id="filterButtons">
						<h5>Arrange by</h5>
					</div> -->

					<div class="row">
		        <div class="col-xs-12">
		          <div class="box box-danger">
		            <div class="box-header">
		              <h3 class="box-title">
										<?php if($projectProfile['PROJECTSTATUS'] != 'Completed' &&  $projectProfile['PROJECTSTATUS'] != 'Archived'):?>
											<span data-toggle="modal" data-target="#modal-upload"><button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Upload Document"><i class="fa fa-upload"></i></button></span>
										<?php endif;?>
									</h3>
									<!-- <?php if ($documentsByProject != null):?>
			              <div class="box-tools">
			                <div class="input-group input-group-sm" style="width: 150px;">
			                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

			                  <div class="input-group-btn">
			                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
			                  </div>
			                </div>
			              </div>
									<?php endif;?> -->
		            </div>
		            <!-- /.box-header -->

		            <div class="box-body">
									<?php if($documentsByProject == NULL):?>
										<h3 class="box-title" style="text-align:center">There are no documents uploaded</h3>
									<?php else: ?>
		              <table id="documentsTable" class="table table-hover">
										<thead>
			                <tr>
			                  <th width="25%" class="text-center">Document Name</th>
			                  <th width="15%" class="text-center">Uploaded By</th>
			                  <th width="15" class="text-center">Department</th>
												<th width="10" class="text-center">Uploaded On</th>
			                  <th width="25%" class="text-center">Remarks</th>
												<th width="10%" class="text-center">Action</th>
			                </tr>
										</thead>
										<tbody>



										<?php

											foreach($documentsByProject as $document){
												if($document['DOCUMENTSTATUS'] == 'For Acknowledgement'){

													if($document['users_UPLOADEDBY'] == $_SESSION['USERID']){

														$buttonAction = "<a href = '" . $document['DOCUMENTLINK']. "' download>
														<button type='button' class='btn btn-success' data-toggle='tooltip' data-placement='top' title='Download'>
														<i class='fa fa-download'></i></button></a>

														<button disabled type='button' class='btn btn-warning document' name='documentButton' data-toggle='tooltip' data-placement='top' title='Acknowledge'>
															<i class='fa fa-check-circle'></i></button>";

															echo "<tr>";
																echo "<td>" . $document['DOCUMENTNAME'] . "</td>";
																echo "<td>" . $document['FIRSTNAME'] . " " . $document['LASTNAME'] . "</td>";
																echo "<td>" . $document['DEPARTMENTNAME'] . "</td>";
																echo "<td>" . date('M d, Y', strtotime($document['UPLOADEDDATE'])) . "</td>";
																echo "<td>" . $document['REMARKS'] . "</td>";
																echo "<td align='center'> " . $buttonAction . " </td>";
															echo "</tr>";

													} else {

														foreach ($documentAcknowledgement as $docuToAcknowledge){
															if($docuToAcknowledge['documents_DOCUMENTID'] == $document['DOCUMENTID']){
																if($docuToAcknowledge['ACKNOWLEDGEDDATE'] == NULL){

																	$buttonAction = "<a href = '" . $document['DOCUMENTLINK']. "' download>
																	<button type='button' class='btn btn-success' data-toggle='tooltip' data-placement='top' title='Download'>
																	<i class='fa fa-download'></i></button></a>

																	<span data-toggle='tooltip' data-placement='top' title='Acknowledge'>
																	<button type='button' class='btn btn-warning document acknowledgeButton' name='documentButton'
																		data-toggle='modal' data-target='#confirmAcknowledge'
																		data-docuID ='" . $document['DOCUMENTID'] . "'
																		data-projectID = '" . $projectProfile['PROJECTID'] . "'
																		data-docuName = '" . $document['DOCUMENTNAME'] ."'>
																		<i class='fa fa-check-circle'></i></button></span>";
																}

																else {

																	$buttonAction = "<a href = '" . $document['DOCUMENTLINK']. "' download>
																	<button type='button' class='btn btn-success' data-toggle='tooltip' data-placement='top' title='Download'>
																	<i class='fa fa-download'></i></button></a>

																	<button disabled type='button' class='btn btn-warning document' name='documentButton' data-toggle='tooltip' data-placement='top' title='Acknowledge'>
																		<i class='fa fa-check-circle'></i></button>";

																}
															}
														}
														echo "<tr>";
															echo "<td>" . $document['DOCUMENTNAME'] . "</td>";
															echo "<td>" . $document['FIRSTNAME'] . " " . $document['LASTNAME'] . "</td>";
															echo "<td>" . $document['DEPARTMENTNAME'] . "</td>";
															echo "<td>" . date('M d, Y', strtotime($document['UPLOADEDDATE'])) . "</td>";
															echo "<td>" . $document['REMARKS'] . "</td>";
															echo "<td align='center'> " . $buttonAction . " </td>";
														echo "</tr>";
													}

												}

												else {

													$buttonAction = "<a href = '" . $document['DOCUMENTLINK']. "' download>
													<button type='button' class='btn btn-success' data-toggle='tooltip' data-placement='top' title='Download'>
													<i class='fa fa-download'></i></button></a>

													<button disabled type='button' class='btn btn-warning document' name='documentButton' data-toggle='tooltip' data-placement='top' title='Acknowledge'>
														<i class='fa fa-check-circle'></i></button>";

														echo "<tr>";
															echo "<td>" . $document['DOCUMENTNAME'] . "</td>";
															echo "<td>" . $document['FIRSTNAME'] . " " . $document['LASTNAME'] . "</td>";
															echo "<td>" . $document['DEPARTMENTNAME'] . "</td>";
															echo "<td>" . date('M d, Y', strtotime($document['UPLOADEDDATE'])) . "</td>";
															echo "<td>" . $document['REMARKS'] . "</td>";
															echo "<td align='center'> " . $buttonAction . " </td>";
														echo "</tr>";
												}
											}
										?>
									</tbody>
		              </table>
								<?php endif;?>
		            </div>
		            <!-- /.box-body -->
		          </div>
		          <!-- /.box -->
		        </div>



						<?php echo form_open_multipart('controller/uploadDocument');?>

							<input type="hidden" name="project_ID" value= "<?php echo $projectProfile['PROJECTID']; ?>">

						<div class="modal fade" id="modal-upload">
		          <div class="modal-dialog">
		            <div class="modal-content">
		              <div class="modal-header">
		                <h4 class="modal-title">Upload a Document</h4>
		              </div>
									<div id="uploadDiv">
		              <div class="modal-body">
										<p><b>Upload this document for</b></p>
										<div class="row">
											<div class="col-lg-6">
												<p>Departments</p>
												<select id ="departments" class="form-control select2 departments" multiple="multiple" name = "departments[]" data-placeholder="Select Departments" style="width:100%">

													<?php foreach ($departments as $row): ?>

														<option value="<?php echo $row['DEPARTMENTID']?>">
															<?php echo $row['DEPARTMENTNAME']; ?>
														</option>

													<?php endforeach; ?>
												</select>
											</div>
											<!-- /.col-lg-6 -->
											<div class="col-lg-6">
												<!-- <div class="input-group"> -->
												<p>Users</p>
													<select id ="users" class="form-control select2 users" multiple="multiple" name = "users[]" data-placeholder="Select Departments" style="width:100%">

														<?php foreach ($users as $row): ?>

															<option value="<?php echo $row['USERID']?>">
																<?php echo $row['FIRSTNAME'] . " " . $row['LASTNAME']; ?>
															</option>

														<?php endforeach; ?>


													</select>
												<!-- </div> -->
												<!-- /input-group -->
											</div>
											<!-- /.col-lg-6 -->
										</div>
										<!-- /.row -->
										<br>
										<div class="form-group">
		                  <label for="uploadDoc">Select a file to upload</label>
		                  <input type="file" id="upload" name="document">
		                </div>
										<div class="form-group">
		                  <label>Remarks</label>
		                  <input type="text" class="form-control"  name="remarks" placeholder="Ex. Approved, Final">
		                </div>
		              </div>
		              <div class="modal-footer">
		                <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
		                <button  id="uploadConfirm" type="button" class="btn btn-success" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
		              </div>
									</form>
								</div>

								<!-- CONFIRM DELEGATE -->
								<div id="confirmUpload">
									<div class="modal-body">
										<h4>Are you sure you want to upload this document?</h4>
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

						<!-- CONFIRM ACKNOWLEDGEMENT -->
						<div class="modal fade" id="confirmAcknowledge" tabindex="-1">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h2 class="modal-title">Confirm Document Acknowledgement</h2>
									</div>
									<div class="modal-body">
										<h4>Are you sure you want to acknowledge this document?</h4>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-close"></i></button>
											<button id = "doneConfirm" type="submit" class="btn btn-success" data-docuID="" data-projectID="" data-docuName="" data-toggle="tooltip" data-placement="top" title="Confirm"><i class="fa fa-check"></i></button></i>
										</div>
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
		</div>
		<script>
			$("#myProjects").addClass("active");
			$('.select2').select2();

			$("#confirmUpload").hide();


			$("body").on('click','#uploadConfirm',function(){
				$("#uploadDiv").hide();
				$("#confirmUpload").show();
			});

				$("#confirmUploadBtn").click(function()
				{
					$("form_open_multipart").submit();
				});

			$(document).on("click", "#backBtn", function() {
				var $project = $("#backForm").attr('data-id');
				$("#backForm").attr("name", "formSubmit");
				$("#backForm").append("<input type='hidden' name='project_ID' value= " + $project + ">");
				$("#backForm").submit();
				});

			$(document).on("click", "#backConfirm", function() {
				$("#uploadDiv").show();
				$("#confirmUpload").hide();
				});

			$(document).on("click", ".acknowledgeButton", function() {

				var $documentID = $(this).attr('data-docuID');
				var $projectID = $(this).attr('data-projectID');
				var $documentName = $(this).attr('data-docuName');

				$("#doneConfirm").attr('data-docuID', $documentID);
				$("#doneConfirm").attr('data-projectID', $projectID);
				$("#doneConfirm").attr('data-docuName', $documentName);

			});

			$(document).on("click", "#doneConfirm", function() {

				var $documentID = $(this).attr('data-docuID');
				var $projectID = $(this).attr('data-projectID');
				var $documentName = $(this).attr('data-docuName');

				console.log($documentID);
				console.log($projectID);
				console.log($documentName);

				$("#acknowledgeForm").attr("name", "formSubmit");
				$("#acknowledgeForm").append("<input type='hidden' name='documentID' value= " + $documentID + ">");
				$("#acknowledgeForm").append("<input type='hidden' name='projectID' value= " + $projectID + ">");
				$("#acknowledgeForm").append("<input type='hidden' name='fileName' value= " + $documentName + ">");
				$("#acknowledgeForm").submit();
			});

			$(function () {
				$('#documentsTable').DataTable({
					'paging'      : false,
					'lengthChange': false,
					'searching'   : true,
					'ordering'    : true,
					'info'        : false,
					'autoWidth'   : false
				})
			});

		</script>
	</body>
</html>
