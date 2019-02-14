<html>
	<head>
		<title>Admin - Manage Users</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/archivesStyle.css")?>"> -->
	</head>
	<body>
		<?php require("frame.php"); ?>
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Manage Users
					<small>Who uses Kernel?</small>
				</h1>

				<ol class="breadcrumb">
          <?php $dateToday = date('F d, Y | l');?>
          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
        </ol>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<div class="box box-danger">
					<div class="box-header">
						<h3 class="box-title">
							<span data-toggle='modal' data-target='#modal-addUser'><button id="addUser" type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Add User"><i class="fa fa-user-plus"></i></button></span>
						</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="usersList" class="table table-hover">
							<thead>
							<tr>
								<th width="15%">Last Name</th>
								<th width="15%">First Name</th>
								<th width="15%">Middle Name</th>
								<th width="10%" class='text-center'>Department</th>
								<th width="15%">Position</th>
								<th width="25%">Job Description</th>
								<th width="5%" class='text-center'>Action</th>
							</tr>
							</thead>
							<tbody>
								<?php foreach ($users as $u): ?>
									<tr>
										<td><?php echo $u['LASTNAME']; ?></td>
										<td><?php echo $u['FIRSTNAME']; ?></td>
										<td></td>
										<td class='text-center'><?php echo $u['DEPT']; ?></td>
										<td><?php echo $u['POSITION']; ?></td>
										<td><?php echo $u['JOBDESCRIPTION']; ?></td>
										<td class='text-center'>
											<span data-toggle="modal"><button type="button"
												class="btn btn-primary btn-sm editBtn" data-id="<?php echo $u['USERID'];?>"
												data-toggle="tooltip" data-placement="top" title="Edit">
											<i class="fa fa-edit"></i></button></span>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

				<!-- ADD/EDIT USER MODAL -->
				<div class="modal fade" id="modal-addUser" tabindex="-1">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h2 class="modal-title" id = "modalTitle"></h2>
				      </div>
				      <div class="modal-body" style="text-align:center">
				        <div style="text-align:left; display:inline-block">
				          <form id="userForm" name="" class="form-horizontal" action="" method="POST">
				          <div class="form-group">
				            <label for="lastName" class="col-sm-4 control-label"><span style="color:red">*</span>Last Name</label>
				            <div class="col-sm-8">
				              <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Enter Last Name" value="" required>
				            </div>
				          </div>
				          <div class="form-group">
				            <label for="firstName" class="col-sm-4 control-label"><span style="color:red">*</span>First Name</label>
				            <div class="col-sm-8">
				              <input type="text" class="form-control" name="firstName" id="firstName" placeholder="Enter First Name" required>
				            </div>
				          </div>
				          <div class="form-group">
				            <label for="middleName" class="col-sm-4 control-label"><span style="color:red">*</span>Middle Name</label>
				            <div class="col-sm-8">
				              <input type="text" class="form-control" name="middleName" id="middleName" placeholder="Enter Middle Name" required>
				            </div>
				          </div>
									<div class="form-group">
				            <label for="email" class="col-sm-4 control-label"><span style="color:red">*</span>Email</label>
				            <div class="col-sm-8">
				              <input type="text" class="form-control" name="email" id="email" placeholder="Enter Email Address" required>
				            </div>
				          </div>
									<div class="form-group">
				            <label for="password" class="col-sm-4 control-label"><span style="color:red">*</span>Password</label>
				            <div class="col-sm-8">
				              <input type="text" class="form-control" name="password" id="password" placeholder="Enter Password" required>
				            </div>
				          </div>
									<div class="form-group">
				            <label for="userType" class="col-sm-4 control-label"><span style="color:red">*</span>User Type</label>
										<div class="col-sm-8">
											<select name="userType" id="userType" class="form-control select2" required>
												<option disabled selected value = "0">-- Select User Type -- </option>
												<option value = "2">Executive</option>
												<option value = "3">Department Head</option>
												<option value = "4">Supervisor</option>
												<option value = "5">Staff</option>
											</select>
										</div>
				          </div>
									<div class="form-group">
				            <label for="department" class="col-sm-4 control-label"><span style="color:red">*</span>Department</label>
										<div class="col-sm-8">
											<select name="department" id="department" class="form-control select2" required>
												<option disabled selected value = "0">-- Select a Department -- </option>
												<?php
													foreach ($departments as $department) {
														echo "<option value=" . $department['DEPARTMENTID'] . ">" . $department['DEPARTMENTNAME'] . "</option>";
													}
												?>
											</select>
										</div>
				          </div>
									<div class="form-group">
				            <label for="position" class="col-sm-4 control-label"><span style="color:red">*</span>Position</label>
				            <div class="col-sm-8">
				              <input type="text" class="form-control" name="position" id="position" placeholder="Enter Position" required>
				            </div>
				          </div>
									<div class="form-group">
				            <label for="jobDesc" class="col-sm-4 control-label"><span style="color:red">*</span>Job Description</label>
				            <div class="col-sm-8">
				              <input type="text" class="form-control" name="jobDesc" id="jobDesc" placeholder="Enter Job Description" required>
				            </div>
				          </div>
									<div class="form-group">
				            <label for="supervisor" class="col-sm-4 control-label"><span style="color:red">*</span>Supervisor</label>
										<div class="col-sm-8">
 										 <select name="supervisor" id="supervisor" class="form-control select2" required>
 											 <option disabled selected value = "0">-- Select a Supervisor -- </option>
 											 <?php
 												 foreach ($users as $user) {
 													 echo "<option value=" . $user['USERID'] . ">" . $user['FIRSTNAME'] . " " . $user['LASTNAME'] . "</option>";
 												 }
 											 ?>
 										 </select>
 									 </div>
				          </div>
									<div class="form-group">
				            <label for="active" class="col-sm-4 control-label"><span style="color:red">*</span>Active</label>
										<div class="col-sm-8">
 										 <select name="active" id="active" class="form-control select2" required>
 											 <option disabled selected value = "2">-- Yes or No -- </option>
											 <option value = "1">Yes</option>
											 <option value = "0">No</option>
 										 </select>
 									 </div>
				          </div>
				          <p><span style="color:red">*</span><small>Required</small></p>
				        </div>
				      </div>
				      <div class="modal-footer">
				        <button id="closeAddUser" type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
				        <button type="submit" class="btn btn-success" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
				      </form>
				      </div>
				    </div>
				  </div>
				</div>
				<!-- ADD/EDIT USER MODAL -->

			</section>
		</div>
		<?php require("footer.php"); ?>
		</div> <!--.wrapper closing div-->
		<script>
		$("#manageUsers").addClass("active");

		$(function () {
			$('#usersList').DataTable({
				'paging'      : false,
				'lengthChange': false,
				'searching'   : true,
				'ordering'    : true,
				'info'        : false,
				'autoWidth'   : false
			});
		});

		$("#closeAddUser").click(function()
		{
			$("#supervisor").val("0");
			$("#department").val("0");
			$("#userType").val("0");
			$("#active").val("2");
		});

		$("#addUser").click(function()
		{
			$("#userForm").attr("name", "addNewUser");
			$("#userForm").attr("action", "addNewUser");

			$("#modalTitle").html("Add New User");
			$("#lastName").attr("value", "");
			$("#firstName").attr("value", "");
			$("#email").attr("value", "");
			$("#password").attr("value", "");
			$("#userType").val("0");
			$("#department").val("0");
			$("#position").attr("value", "");
			$("#jobDesc").attr("value", "");
			$("#supervisor").val("0");
			$("#active").val("2");
		});

		$(".editBtn").click(function()
		{
			$("#modalTitle").html("Edit User");
			$("#userForm").attr("name", "editUser");
			$("#userForm").attr("action", "editUser");
			var $userID = $(this).attr('data-id');
			$("#userForm").append("<input type='hidden' name='user_ID' value= " + $userID + ">");

			$.ajax({
			 type:"POST",
			 url: "<?php echo base_url("index.php/controller/getUserDetails"); ?>",
			 data: {user_ID: $userID},
			 dataType: 'json',
			 success:function($data)
			 {
				 $("#lastName").attr("value", $data['userEdit'].LASTNAME);
				 $("#firstName").attr("value", $data['userEdit'].FIRSTNAME);
				 $("#email").attr("value", $data['userEdit'].EMAIL);
				 $("#password").attr("value", $data['userEdit'].PASSWORD);
				 $("#userType").val($data['userEdit'].usertype_USERTYPEID);
				 $("#department").val($data['userEdit'].departments_DEPARTMENTID);
				 $("#position").attr("value", $data['userEdit'].POSITION);
				 $("#jobDesc").attr("value", $data['userEdit'].JOBDESCRIPTION);
				 $("#supervisor").val($data['userEdit'].users_SUPERVISORS);
				 $("#active").val($data['userEdit'].isACT);
			 },
			 error:function()
			 {
				 alert("There was a problem in retrieving the user details");
			 }
			 });

			 $("#modal-addUser").modal('show');
		});
		</script>
	</body>
</html>
